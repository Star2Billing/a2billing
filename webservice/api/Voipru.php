<?php

require_once dirname(__FILE__) . '/../lib/admin.defines.php';
require_once dirname(__FILE__) . '/../lib/admin.module.access.php';
require_once dirname(__FILE__) . '/../lib/phpagi/phpagi-asmanager.php';

class Voipru implements IApi {
    
    private
        $api = null,
        $base_currency = null,
        $currencies = array();
    
    public function init($api) {
        $this->api = $api;
        
        $api->registerApi('ping', array($this, 'ping'));
        $api->registerApi('voipru_account_create', array($this, 'accountCreate'));
        $api->registerApi('voipru_apply_config', array($this, 'applySipConfig'));
        $api->registerApi('voipru_card_create', array($this, 'createCard'));
        $api->registerApi('voipru_sip_create', array($this, 'createSip'));
    }

    private function response($success, $params = array()) {
        return array_merge(array('success' => $success), $params);
    }

    private function getAllQueryParams($prefix) {
        $data = array();
        foreach ($_REQUEST as $key => $value) {
            if (preg_match("/^$prefix(.+)/i", $key, $matches)) {
                $data[$matches[1]] = $value;
            }
        }
        return $data;
    }
    
    public function ping() {
        return $this->response(true, array('msg' => 'pong'));
    }
    
    public function applySipConfig() {
        if (!USE_REALTIME) {
            ob_start();
            $instance_realtime = new Realtime();
            $instance_realtime->create_trunk_config_file('sip');
            $output = ob_get_clean();

            if (!file_exists(BUDDY_SIP_FILE)) {
                return $this->response(false, array('msg' => 'File is not created, check rights'));
            } else if (strlen($output)) {
                return $this->response(false, array('msg' => $output));
            }
            
            $as = new AGI_AsteriskManager();
            if ($as->connect(MANAGER_HOST, MANAGER_USERNAME, MANAGER_SECRET)) {
                $res = $as->Command('sip reload');
                $as->disconnect();
            }
        }
        
        return $this->response(true);
    }
    
    public function createCard() {
        $data = $this->getAllQueryParams('card_');
        if (!count($data))
            return $this->response(false, array('msg' => "Data array expected, param prefix is 'card_'"));
        
        $values = array_merge(array(
            'username' => gen_card(),
            'useralias' => null,
            'uipass' => null,
            'tariff' => null,
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
            'tag' => 'voipru'
        ), $data);
        
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
    
    public function createSip() {
        $data = $this->getAllQueryParams('sip_');
        if (!count($data))
            return $this->response(false, array('msg' => "Data array expected, param prefix is 'sip_'"));

        if (!isset($data['card_id']))
            return $this->response(false, array('msg' => "Parameter 'sip_card_id' expected"));

        if (!isset($data['secret']))
            return $this->response(false, array('msg' => "Parameter 'sip_secret' expected"));
        
        $sql = "select * from cc_card where id = '{$data['card_id']}' limit 1";
        $records = $this->api->query($sql);
        if (!count($records))
            return $this->response(false, array('msg' => 'Card record not found'));

        $instance_realtime = new Realtime();
        $instance_realtime->insert_voip_config(1, 0, $data['card_id'], $records[0]['username'], $data['secret']);

        $sql = "select * from cc_sip_buddies where id_cc_card = '{$data['card_id']}' limit 1";
        $records = $this->api->query($sql);
        if (!count($records))
            return $this->response(false, array('msg' => 'SIP buddy record not found'));
        
        return $this->response(true, array('sip' => $records[0]));
    }
    
    public function accountCreate() {
        $response = $this->createCard();
        if ($response['success'] && $response['card']) {
            $_REQUEST['sip_card_id'] = $response['card']['id'];
            if (!isset($_REQUEST['sip_secret']))
                $_REQUEST['sip_secret'] = MDP(10);
            $response = $this->createSip();
            if ($response['success']) {
                $response = $this->applySipConfig();
            }
        }
        return $response;
    }
}
