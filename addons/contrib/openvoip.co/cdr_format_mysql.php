<?php
/*
INPUT:
CREATE TABLE `cdr` (
  `calldate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clid` varchar(80) NOT NULL DEFAULT '',
  `src` varchar(80) NOT NULL DEFAULT '',
  `dst` varchar(80) NOT NULL DEFAULT '',
  `dcontext` varchar(80) NOT NULL DEFAULT '',
  `channel` varchar(80) NOT NULL DEFAULT '',
  `dstchannel` varchar(80) NOT NULL DEFAULT '',
  `lastapp` varchar(80) NOT NULL DEFAULT '',
  `lastdata` varchar(80) NOT NULL DEFAULT '',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) NOT NULL DEFAULT '',
  `amaflags` int(11) NOT NULL DEFAULT '0',
  `accountcode` varchar(20) NOT NULL DEFAULT '',
  `userfield` varchar(255) NOT NULL DEFAULT '',
  `uniqueid` varchar(32) NOT NULL DEFAULT '',
  `linkedid` varchar(32) NOT NULL DEFAULT '',
  `sequence` varchar(32) NOT NULL DEFAULT '',
  `peeraccount` varchar(32) NOT NULL DEFAULT '',
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `src` (`src`),
  KEY `accountcode` (`accountcode`),
  KEY `duration` (`duration`),
  KEY `billsec` (`billsec`)
)

OUTPUT:
FromT1 ToT1   Date       CallTime Duration   Dialed number           CarrierID+ITUT Number   Caller ID
048-20 036-03 12-31-2015 23:57:05 00:07:53 A 01173472630592          01573472630592          3108294615

MAPPING:
FromT1
091-00 - a2b01
092-00 - ab202
ToT1: extract carrier_gateway from dstchannelm for example carrier_5_1 = 005-01
CallTime: call start time
A - means ANSWERED, R - should mean that attempt made, but failed and next trunk attempted, " " (empty space) - other status
Dialed number - 23 symbols! for 011 - remove 011, for all - add carrier id (3 symbols for example 015)
CarrierID+ITUT Number - 23 symbols!
*/

require_once __DIR__ . "../../../../common/lib/phpagi/phpagi.php";
require_once __DIR__ . "../../../../common/lib/phpagi/phpagi-asmanager.php";

class CdrParser {
    const A2B_CONFIG = '/etc/a2billing.conf';
    const DEL = "\n-----------------------------------------------------";
    const MAX_CALL_DURATION = 3600; // seconds

    protected $columns = array( // cdr table legit columns
        'calldate',
        'clid',
        'src',
        'dst',
        'channel',
        'dstchannel',
        'duration',
        'billsec',
        'disposition',
        'accountcode',
        'uniqueid',
        'lastapp'
    );
    protected $args = array(); // app console args
    protected $params_ini = array(); // INI params from A2B_CONFIG
    protected $params_a2b = array(); // A2B params from database
    protected $cdr_db = null;
    protected $cdr_table = null;
    protected $cdr_id_column = null;
    protected $cdr_processed_column = null;
    protected $cdr_batch = null;
    protected $active_channels = null;
    protected $asterisk_hosts = null;

    public function __construct() {
        $this->args = self::parse_args();
        $this->params_ini = self::parse_ini_config();
        $this->init_db();
        $this->params_a2b = $this->parse_a2b_config();

        $this->cdr_db = $this->get_arg('cdr_db', 'asteriskcdrdb');
        $this->cdr_table = $this->get_arg('cdr_table', 'cdr');
        $this->cdr_batch = intval($this->get_arg('cdr_batch', 10000));
        $this->active_channels = $this->get_active_channels();
        $this->cdr_id_column = $this->get_arg('cdr_id_column', 'id');
        $this->cdr_processed_column = $this->get_arg('cdr_processed_column', 'processed');

        // add dynamic columns
        $this->columns[] = $this->cdr_id_column;
        $this->columns[] = $this->cdr_processed_column;
    }

    public function run() {
        if ($this->has_arg('h') || $this->has_arg('help')) { // help
            self::print_ln(
                'Usage: php cdr_format_mysql.php [options]',
                'Options are:',
                '-h or --help           - show this help',
                '--test                 - test only, no real actions, just output to console',
                '--cdr_db               - CDR database name, default "asteriskcdrdb"',
                '--cdr_table            - CDR table name, default "cdr"',
                '--cdr_batch            - CDR parsing batch limit, default 10000',
                '--cdr_id_column        - CDR db table ID column, must be "bigint UNSIGNED NOT NULL Auto_increment"',
                '--cdr_processed_column - CDR db table column that is used to mark processed cdrs, must "int(1) UNSIGNED DEFAULT 0 NULL"',
                '--asterisk_hosts       - comma separated list of all asterisk hosts in cluster, like "host1,192.168.0.2"'
            );
            return;
        }

        if ($this->is_test()) {
            self::print_ln('>>> TEST RUN <<<');
            self::print_ln(self::DEL);
            self::print_ln('Passed arguments:');
            self::print_ln($this->args);
            self::print_ln(self::DEL);
            self::print_ln('INI params:');
            self::print_ln($this->params_ini);
        }

        // process cdrs
        $cdrs = $this->get_cdrs();
        $cdr_ids = array();
        for ($i = 0; $i < count($cdrs); ++$i) {
            $cdr = $cdrs[$i];
            $cdr_ids[] = $cdr[$this->cdr_id_column];

            // we need to parse only following
            if (!in_array($cdr['lastapp'], array('Dial', 'ResetCDR')))
                continue;

            if ($this->is_test())
                self::print_ln(self::DEL, 'Processing cdr #' . $i . ':', $cdr);

            // detect from
            $from = '';
            if ($cdr['accountcode'] == '4528339244') {
                $from = '091-00';
            } elseif ($cdr['accountcode'] == '0744553513') {
                $from = '092-00';
            }

            // detect to, cdr format
            $carrier = '';
            $to = '';
            if (preg_match('/[A-Z0-9]+\/carrier\_(\d+)\_(\d+)/', $cdr['dstchannel'], $matches) || preg_match('/[A-Z0-9]+\/\d+\@carrier\_(\d+)\_(\d+)/', $cdr['dstchannel'], $matches)) {
                $carrier = $matches[1];
                $to = sprintf('%03d-%02d', $carrier, $matches[2]);
            }

            // detect date
            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $cdr['calldate']);

            // detect duration
            $duration = gmdate("H:i:s", $cdr['billsec']);

            // detect answer flag
            $flag = '';
            if ($cdr['disposition'] == 'ANSWERED') { // answer
                $flag = 'A';
            } elseif ($this->is_retry($cdrs, $i)) { // retry
                $flag = 'R';
            }

            // detect dialed number
            $number = sprintf('%03d', $carrier) . preg_replace('/^011/', '', $cdr['dst']);

            // output data
            if ($this->is_test())
                self::print_ln(self::DEL, 'OUTPUT cdr #' . $i . ':');

            printf("%-6s %-6s %-10s %-8s %-8s %1s %-23s %-23s %-23s %s\r\n", $from, $to, $datetime->format('m-d-Y'), $datetime->format('H:i:s'), $duration, $flag, $cdr['dst'], $number, $cdr['src'], $cdr['uniqueid']);
        }

        // set marker
        $this->mark_cdrs_processed($cdr_ids);
    }

    protected function is_retry($cdrs, $i) {
        $result = false;
        $current_cdr = $cdrs[$i];

        // all cdrs is cdrs + active calls
        static $all_cdrs = null;
        if (!$all_cdrs)
            $all_cdrs = array_merge($cdrs, $this->active_channels);

        // prepare time limit
        $until_time = \DateTime::createFromFormat('Y-m-d H:i:s', $current_cdr['calldate']);
        $until_time->add(new DateInterval('PT' . self::MAX_CALL_DURATION . 'S'));

        // check if we have a retry in the following CDRs and active calls
        // we go forward on self::MAX_CALL_DURATION seconds only!
        $j = $i + 1;
        for ($j; $j < count($all_cdrs); $j++) {
            $cdr = $all_cdrs[$j];
            if ($current_cdr['channel'] === $cdr['channel'] && $current_cdr['dst'] === $cdr['dst']) {
                $result = true;
                break;
            }
            $cdr_time = \DateTime::createFromFormat('Y-m-d H:i:s', $cdr['calldate']);
            if ($cdr_time > $until_time)
                break;
        }

        if ($this->is_test())
            self::print_ln(
                self::DEL,
                'RETRY RESULT: ' . ($result ? 'TRUE' : 'FALSE'),
                'Checked cdr(s): ' . ($j - $i)
            );

        return $result;
    }

    protected function get_cdrs() {
        $columns = implode(', ', $this->columns);
        $sql = "select $columns from {$this->cdr_db}.{$this->cdr_table}
        where {$this->cdr_processed_column} = 0 order by calldate asc limit {$this->cdr_batch}";

        if ($this->is_test())
            self::print_ln(self::DEL, 'CDRs SQL:', $sql);

        $cdrs = $this->query($sql);

        if ($this->is_test())
            self::print_ln(self::DEL, 'CDRs count: ' . count($cdrs));

        return $cdrs;
    }

    protected function mark_cdrs_processed($cdr_ids) {
        if (!is_array($cdr_ids) || !count($cdr_ids))
            return;

        $cdrs_chunks = array_chunk($cdr_ids, 500);
        foreach ($cdrs_chunks as $cdr_ids) {
            $cdr_ids_sql = join(', ', $cdr_ids);
            $sql = "update {$this->cdr_db}.{$this->cdr_table}
            set {$this->cdr_processed_column} = 1
            where {$this->cdr_id_column} in ($cdr_ids_sql)";

            if ($this->is_test()) {
                self::print_ln(self::DEL, 'CDRs mark SQL: ' . $sql);
            } else {
                $this->query($sql);
            }
        }
    }

    protected function get_a2b_param($key, $default_value = null) {
        return self::_get_param($this->params_a2b, $key, $default_value);
    }

    protected function get_ini_param($key, $default_value = null) {
        return self::_get_param($this->params_ini, $key, $default_value);
    }

    protected static function _get_param($data, $key, $default_value = null) {
        $keys = explode('.', $key);
        if (!is_array($keys))
            $keys = array($key);

        // get param
        $value = $data;
        foreach ($keys as $key) {
            $value = isset($value[$key]) ? $value[$key] : $default_value;
        }

        return $value;
    }

    protected function escape($value) {
        return $this->mysqli ? $this->mysqli->real_escape_string($value) : $value;
    }

    protected function init_db() {
        // connecting to DB
        if (strcasecmp($this->get_ini_param('database.dbtype', ''), 'mysql') != 0)
            throw new \RuntimeException('Supports only mysql database');

        $host = $this->get_ini_param('database.hostname', '127.0.0.1');
        $user = $this->get_ini_param('database.user', 'root');
        $pass = $this->get_ini_param('database.password', '');
        $db = $this->get_ini_param('database.dbname', 'a2billing');
        $mysqli = new mysqli($host, $user, $pass, $db);

        if ($mysqli->connect_error)
            throw new \RuntimeException('Mysql connection error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);

        $this->mysqli = $mysqli;
        $this->mysqli->set_charset("utf8");
    }

    public function query($sql) {
        $data = array();
        if (is_object($result = $this->mysqli->query($sql))) {
            while ($row = $result->fetch_assoc())
                $data[] = $row;
            $result->free();
        }
        return $data;
    }

    protected function is_test() {
        return $this->has_arg('test');
    }

    protected function has_arg($name) {
        return array_key_exists($name, $this->args);
    }

    protected function get_arg($name, $default_value = null) {
        return $this->has_arg($name) ? $this->args[$name] : $default_value;
    }

    protected static function parse_args() {
        $shortargs = 'h';
        $longargs = array(
            'help',
            'test',
            'cdr_db:',
            'cdr_table:',
            'cdr_processed_column:',
            'cdr_id_column:',
            'cdr_batch:',
            'asterisk_hosts:'
        );
        return getopt($shortargs, $longargs);
    }

    protected function parse_a2b_config() {
        $result = [];
        $sql = "select * from cc_config";
        $data = $this->query($sql);
        foreach ($data as $row) {
            $group = $row['config_group_title'];
            if (!isset($result[$group]))
                $result[$group] = array();
            $result[$group][$row['config_key']] = $row['config_value'];
        }
        return $result;
    }

    protected static function parse_ini_config() {
        return file_exists(self::A2B_CONFIG) ? parse_ini_file(self::A2B_CONFIG, true) : array();
    }

    protected static function _print_ln($value, $block_name = null) {
        if (!empty($block_name) && !is_numeric($block_name))
            echo '[' . $block_name . ']' . PHP_EOL;
        foreach ($value as $name => $arg) {
            if (is_array($arg)) {
                self::_print_ln($arg, $name);
            } else {
                echo (!is_numeric($name) ? $name . ': ' : '') . $arg . PHP_EOL;
            }
        }
    }

    protected static function print_ln() {
        self::_print_ln(func_get_args());
    }

    protected function get_active_channels() {
        $result = array();
        $asm = new AGI_AsteriskManager();
        $hosts = $this->get_arg('asterisk_hosts', $this->get_a2b_param('global.manager_host', '127.0.0.1'));
        $hosts = explode(',', $hosts);
        $username = $this->get_a2b_param('global.manager_username', '');
        $secret = $this->get_a2b_param('global.manager_secret', '');

        if (!is_array($hosts) || !count($hosts)) {
            if ($this->is_test())
                self::print_ln('No asterisk hosts found!');

            return $result;
        }

        // search calls on all hosts
        foreach ($hosts as $host) {
            if (!$asm->connect($host, $username, $secret)) {
                if ($this->is_test())
                    self::print_ln('Cannot connect to asterisk host "' . $host . '"!');
                continue;
            }

            $response = $asm->send_request('COMMAND', array(
                'command' => 'core show channels concise',
                'actionid' => md5(rand())
            ));

            if (empty($response['data'])) {
                if ($this->is_test())
                    self::print_ln('Got no response from asterisk: ' . $host);

                continue;
            }

            if ($this->is_test())
                self::print_ln('Got asterisk response:', $response['data']);

            $lines = preg_split('/\r\n|\n/', $response['data']);
            if (!is_array($lines)) {
                if ($this->is_test())
                    self::print_ln('Cannot parse lines: ', $lines);

                continue;
            }

            foreach ($lines as $line) {
                if (strpos($line, '!')) {
                    $parts = explode('!', $line);
                    if (!is_array($parts) || count($parts) < 3)
                        continue;

                    // we need channel and number
                    $result[] = array(
                        'calldate' => date('Y-m-d H:i:s'),
                        'channel' => $parts[0],
                        'dst' => $parts[2]
                    );
                }
            }

            $asm->disconnect();
        }

        if ($this->is_test())
            self::print_ln(self::DEL, 'ACTIVE CHANNELS COUNT: ' . count($result));

        return $result;
    }
}

// run app
try {
    $parser = new CdrParser();
    $parser->run();
    exit(0);
} catch (\Exception $err) {
    error_log($err->getMessage());
    exit(1);
}
