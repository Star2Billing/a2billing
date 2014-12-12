<?php

class OneWorldApi implements IApi {
    
    private
        $api = null,
        $base_currency = null,
        $currencies = array();
    
    public function init($api) {
        $api->registerApi('card_create', array($this, 'card_create'));
        $api->registerApi('card_delete', array($this, 'card_delete'));
        $api->registerApi('card_find', array($this, 'card_find'));
        $api->registerApi('tariff_find', array($this, 'tariff_find'));
        $api->registerApi('helper_param_get', array($this, 'helper_param_get'));
        $api->registerApi('helper_currency_convert', array($this, 'helper_currency_convert'));
        $api->registerApi('card_balance', array($this, 'card_balance'));
        $api->registerApi('card_balance_add', array($this, 'card_balance_add'));
        $api->registerApi('card_tariff', array($this, 'card_tariff'));
        $api->registerApi('card_tariff_change', array($this, 'card_tariff_change'));
        $api->registerApi('call_rate', array($this, 'call_rate'));
        $api->registerApi('voucher_activate', array($this, 'voucher_activate'));
        
        $this->api = $api;
        $this->base_currency = $api->getParam('base_currency');
        $data = $api->query("select lower(currency) currency, value from cc_currencies");
        foreach ($data as $row)
            $this->currencies[$row['currency']] = $row['value'];
    }
    
    public function getCurrencyRate($currency) {
        $currency = strtolower($currency);

        return array_key_exists($currency, $this->currencies) ? $this->currencies[$currency] : 0;
    }
    
    public function getRate($from, $to) {
        $fromRate = $this->getCurrencyRate($from);
        $toRate = $this->getCurrencyRate($to);
        if ($fromRate == 0 || $toRate == 0)
            return 0;

        $rate = 0;
        if ($from == $this->base_currency) {
            $rate = 1 / $toRate;
        } else if ($to == $this->base_currency) {
            $rate = $fromRate;
        } else {
            $rate = $fromRate * (1 / $toRate);
        }

        return $rate;
    }

    /**
     * Converts from one to another
     * 
     * @param float $amount
     * @param mixed $from
     * @param mixed $to if null - base currency will be used
     * @return float
     */
    public function convertCurrency($amount, $from, $to = null) {
        if ($to === null)
            $to = $this->base_currency;

        return $amount * $this->getRate($from, $to);
    }
    
    /**
     * Expects:
     *  query_column
     *  query_value
     *  query_condition
     * 
     * @return mixed
     */
    private function find_query($table) {
        $response = null;
        $query_value = $this->api->escape($this->api->getQueryParam('query_value', '%'));
        $query_column = $this->api->getQueryParam('query_column', 'id');
        $query_condition = $this->api->getQueryParam('query_condition', 'like');
        if ($this->api->columnExists($table, $query_column)) {
            try {
                $sql = "select * from $table where `$query_column` $query_condition '$query_value'";
                $response = $this->response(true, array('data' => $this->api->query($sql)));
            } catch (Exception $e) {
                $response = $this->response(false, array('msg' => $e->getMessage()));
            }
        } else {
            $response = $this->response(false, array('msg' => "Wrong query_column"));
        }        
        return $response;
    }

    /**
     * Expects:
     *  query_column
     *  query_value
     * 
     * @return mixed
     */
    public function delete_query($table) {
        $response = null;
        $query_value = $this->api->escape($this->api->getQueryParam('query_value'));
        $query_column = $this->api->getQueryParam('query_column', 'id');
        if ($this->api->columnExists($table, $query_column)) {
            try {
                $sql = "delete from $table where `$query_column` = '$query_value' limit 1";
                $result = $this->api->query($sql);
                $response = (isset($result['affected_rows']) && $result['affected_rows'] > 0) ? $this->response(true) : $this->response(false, array('msg' => "Cannot find card where $query_column = $query_value"));
            } catch (Exception $e) {
                $response = $this->response(false, array('msg' => $e->getMessage()));
            }
        } else {
            $response = $this->response(false, array('msg' => "Wrong query_column"));
        }        
        return $response;
    }
    
    private function response($success, $params = array()) {
        return array_merge(array('success' => $success), $params);
    }
    
    /**
     * Creates new card
     * 
     * Expectes:
     *  parameters like col_REALCOLUMNNAME and a value for it
     * 
     * @return array
     */
    public function card_create() {
        $username = $this->api->escape($this->api->getQueryParam('username', ''));
        $password = $this->api->escape($this->api->getQueryParam('password', ''));
        $tariff = intval($this->api->getQueryParam('tariff', 0));
        
        $sql = "select * from cc_tariffgroup where id = '$tariff'";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Bad tariff'));
        
        $values = array(
            'username' => $username,
            'useralias' => $username,
            'uipass' => $password,
            'tariff' => $tariff,
            'credit' => '0',
            'activated' => 't',
            'status' => '1',
            'lastname' => '',
            'firstname' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'country' => '',
            'zipcode' => '',
            'phone' => '',
            'email' => '',
            'fax' => '',
            'typepaid' => '0',
            'loginkey' => '',
            'email_notification' => '',
            'company_name' => '',
            'company_website' => '',
            'traffic_target' => '0',
            'simultaccess' => '0',
            'max_concurrent' => '1',
            'redial' => '',
            'tag' => 'oneworld'
        );
                
        $fields = array();
        foreach ($values as $col => $value)
            $fields[] = "$col = '$value'";

        $response = null;
        try {
            $sql = "insert into cc_card set " . join(', ', $fields);
            $result = $this->api->query($sql);
            if (isset($result['insert_id']) && $result['insert_id'] > 0) {
                $sql = "select * from cc_card where id = '{$result['insert_id']}'";
                $data = $this->api->query($sql);
                $response = count($data) > 0 ? $this->response(true, array('card' => $data[0])) : $this->response(false, array('msg' => "Cannot find card by id {{$result['insert_id']}}"));
            } else {
                $response = $this->response(false, array('msg' => "Cannot create card"));
            }
        } catch (Exception $e) {
            $response = $this->response(false, array('msg' => $e->getMessage()));
        }
        
        return $response;
    }
    
    public function card_delete() {
        return $this->delete_query('cc_card');
    }
    
    public function tariff_find() {
        return $this->find_query('cc_tariffgroup');
    }

    public function card_find() {
        return $this->find_query('cc_card');
    }

    /**
     * Expects:
     *  key (optional)
     *  group (optional)
     *  default (optional)
     * 
     */
    public function helper_param_get() {
        $key = $this->api->getQueryParam('key', '');
        $group = $this->api->getQueryParam('group', 'global');
        $default = $this->api->getQueryParam('default', null);
        
        return $this->response(true, array('data' => $this->api->getParam($key, $default, $group)));
    }
    
    /**
     * Expects:
     *  amount
     *  from
     *  to (optional)
     * 
     */
    public function helper_currency_convert() {
        $amount = $this->api->getQueryParam('amount', 0);
        $from = $this->api->getQueryParam('from');
        $to = $this->api->getQueryParam('to', $this->base_currency);
        
        return $this->response(true, array('data' => $this->convertCurrency($amount, $from, $to)));
    }
    
    public function card_balance() {
        $username = $this->api->escape($this->api->getQueryParam('username', ''));

        $sql = "select credit, currency from cc_card where username = '$username' limit 1";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Billing record not found.'));
        
        return $this->response(true, array('balance' => $this->convertCurrency($data[0]['credit'], $this->base_currency, $data[0]['currency']), 'currency' => $data[0]['currency']));
    }
    
    public function card_tariff_change() {
        $username = $this->api->escape($this->api->getQueryParam('username', ''));
        $tariff = intval($this->api->getQueryParam('tariff', 0));
        
        $sql = "select * from cc_tariffgroup where id = '$tariff' limit 1";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Tariff record not found.'));

        $new_tariff = $data[0];
        
        $sql = "select c.id card_id, t.id tariff_id from cc_card c left join cc_tariffgroup t on t.id = c.tariff where c.username = '$username' limit 1";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Billing record not found.'));
        
        $ids = $data[0];
        
        if ($ids['tariff_id'] == $tariff)
            return $this->response(false, array('msg' => 'Tariff is same.'));
        
        // changing tariff
        $sql = "update cc_card set tariff = '$tariff' where id = '{$ids['card_id']}' limit 1";
        $data = $this->api->query($sql);
        
        if (!isset($data['affected_rows']) || !$data['affected_rows']) {
            $this->response(false, array('msg' => isset($data['error']) ? $data['error'] : 'Cannot change tariff.'));
        } else {
            // TODO: additional actions
            
            
        }
        
        return $this->response(true, array('plan' => $new_tariff['tariffgroupname'], 'id' => $new_tariff['id']));
    }
    
    public function card_tariff() {
        $username = $this->api->escape($this->api->getQueryParam('username', ''));
        
        $sql = "select t.id id, t.tariffgroupname tariff from cc_card c left join cc_tariffgroup t on t.id = c.tariff where c.username = '$username' limit 1";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Billing record not found.'));
                
        return $this->response(true, array('plan' => $data[0]['tariff'], 'id' => $data[0]['id']));
    }
    
    public function call_rate() {
        $username = $this->api->escape($this->api->getQueryParam('username', ''));
        $number = $this->api->escape($this->api->getQueryParam('number', ''));

        $sql = "select * from cc_card where username = '$username' limit 1";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Billing record not found.'));
        
        $card = $data[0];
        
        if (!strlen($number))
            return $this->response(false, array('msg' => 'Number is empty.'));
        
        require_once dirname(__FILE__) . '/../lib/admin.defines.php';
        require_once dirname(__FILE__) . '/../lib/Class.RateEngine.php';        
        
        $A2B->DBHandle = DbConnect();
        $A2B->set_instance_table(new Table());
        $A2B->cardnumber = $username;
        if (!$A2B->callingcard_ivr_authenticate_light($error_msg, $balance))
            return $this->response(false, array('msg' => $error_msg));
        
        $RateEngine = new RateEngine();
        $RateEngine->webui = 0;

        $A2B->agiconfig['accountcode'] = $A2B->cardnumber ;
        $A2B->agiconfig['use_dnid'] = 1;
        $A2B->agiconfig['say_timetocall'] = 0;
        $A2B->dnid = $A2B ->destination = $number;
        if ($A2B->removeinterprefix) $A2B->destination = $A2B->apply_rules($A2B->destination);
        
        $resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B->destination, $card['tariff']);
        if (!$resfindrate)
            return $this->response(false, array('msg' => 'No matching rate found.'));
        
        $res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
        
        if (!is_array($RateEngine->ratecard_obj) || !count($RateEngine->ratecard_obj))
            return $this->response(false, array('msg' => 'No matching rate found.'));
        
        $rate = $RateEngine->ratecard_obj[0];
        
        return $this->response(true, array('rate' => $rate['rateinitial'], 'currency' => $this->base_currency, 'duration' => $rate['timeout']));
    }
    
    public function voucher_activate() {
        $username = $this->api->escape($this->api->getQueryParam('username', ''));
        $voucher_id = $this->api->escape($this->api->getQueryParam('voucher_id', ''));

        $sql = "select * from cc_card where username = '$username' limit 1";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Billing record not found.'));
        
        $card = $data[0];
        
        $sql = "select * from cc_voucher where expirationdate >= CURRENT_TIMESTAMP AND activated='t' AND voucher='$voucher_id' limit 1";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Voucher record not found.'));
        
        $voucher = $data[0];
        
        if (!array_key_exists(strtolower($voucher['currency']), $this->currencies))
            return $this->response(false, array('msg' => 'Voucher currency is invalid.'));

        $amount = $this->convertCurrency($voucher['credit'], $voucher['currency']);
        if ($amount <= 0)
            return $this->response(false, array('msg' => 'Invalid amount.'));
        
        $sql = "update cc_voucher set activated = 'f', usedcardnumber = '$username', usedate = now() where voucher = '$voucher_id' limit 1";
        $data = $this->api->query($sql);
        if (!isset($data['affected_rows']) || !$data['affected_rows'])
            $this->response(false, array('msg' => isset($data['error']) ? $data['error'] : 'Cannot activate voucher.'));

        $sql = "update cc_card set credit = credit + '$amount' where username = '$username'";
        $data = $this->api->query($sql);
        if (!isset($data['affected_rows']) || !$data['affected_rows'])
            $this->response(false, array('msg' => isset($data['error']) ? $data['error'] : 'Cannot add balance.'));
        
        return $this->card_balance();
    }
    
    public function card_balance_add() {
        $username = $this->api->escape($this->api->getQueryParam('username', ''));
        $amount = floatval($this->api->getQueryParam('amount', 0));
        $currency = $this->api->escape($this->api->getQueryParam('currency', $this->base_currency));
        
        $sql = "select * from cc_card where username = '$username' limit 1";
        $data = $this->api->query($sql);
        if (!count($data))
            return $this->response(false, array('msg' => 'Billing record not found.'));
        
        $card = $data[0];

        if (!array_key_exists(strtolower($currency), $this->currencies))
            return $this->response(false, array('msg' => 'Currency is invalid.'));
        
        $amount = $this->convertCurrency($amount, $currency);
        
        if ($amount <= 0)
            return $this->response(false, array('msg' => 'Invalid amount.'));
        
        $sql = "insert into cc_logrefill set credit = '$amount', card_id = '{$card['id']}', description = 'oneworld refill', refill_type = '0', added_invoice = '0'";
        $data = $this->api->query($sql);
        if (!isset($data['insert_id']) || !$result['insert_id'])
            $this->response(false, array('msg' => isset($data['error']) ? $data['error'] : 'Cannot add refill record.'));
        
        $sql = "update cc_card set credit = credit + '$amount' where username = '$username'";
        $data = $this->api->query($sql);
        if (!isset($data['affected_rows']) || !$data['affected_rows'])
            $this->response(false, array('msg' => isset($data['error']) ? $data['error'] : 'Cannot add balance.'));
                
        return $this->card_balance();
    }
    
}
