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

class CdrParser {
    const A2B_CONFIG = '/etc/a2billing.conf';
    const DEL = "\n-----------------------------------------------------";

    protected $args = array(); // app console args
    protected $params_ini = array(); // INI params from A2B_CONFIG
    protected $params_a2b = array(); // A2B params from database
    protected $cdr_db = null;
    protected $cdr_table = null;
    protected $cdr_marker = null;
    protected $cdr_batch = null;
    protected $active_channels = null;

    public function __construct() {
        $this->args = self::parse_args();
        $this->params_ini = self::parse_ini_config();
        $this->init_db();
        //$this->params_a2b = $this->parse_a2b_config();

        $this->cdr_db = $this->get_arg('cdr_db', 'asteriskcdrdb');
        $this->cdr_table = $this->get_arg('cdr_table', 'cdr');
        $this->cdr_marker = $this->get_arg('cdr_marker', __DIR__ . '/.cdr_marker');
        $this->cdr_batch = $this->get_arg('cdr_batch', 10000);
        $this->active_channels = $this->get_active_channels();
    }

    public function run() {
        if ($this->has_arg('h') || $this->has_arg('help')) { // help
            self::print_ln(
                'Usage: php cdr_format_mysql.php [options]',
                'Options are:',
                '-h or --help    - show this help',
                '--test          - test only, no real actions, just output to console',
                '--cdr_db        - CDR database name, default "asteriskcdrdb"',
                '--cdr_table     - CDR table name, default "cdr"',
                '--cdr_marker    - CDR latest processed unique ID marker file path, default "./.cdr_marker"',
                '--cdr_batch     - CDR parsing batch limit, default 10000',
                '--cdr_mark      - create CDR mark by passing cdr unique ID, it will be marked as the last processed cdr',
                '--asterisk_path - path to asterisk binary, default is /usr/sbin/asterisk'
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

        // mark cdr only
        if ($unique_id = $this->get_arg('cdr_mark')) {
            $cdr = $this->get_cdr($unique_id);
            if ($cdr && $this->is_test())
                self::print_ln(self::DEL, 'Marking CDR as last:');
            $this->set_last_cdr();
            return;
        }

        // process cdrs
        $cdrs = $this->get_cdrs();
        $cdr = null;
        for ($i = 0; $i < count($cdrs); ++$i) {
            $cdr = $cdrs[$i];
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
        if ($cdr && $this->is_test())
            self::print_ln(self::DEL, 'Saving last CDR:');
        $this->set_last_cdr($cdr);
    }

    protected function is_retry($cdrs, $i) {
        $current_cdr = $cdrs[$i];

        // all cdrs is cdrs + active calls
        static $all_cdrs = null;
        if (!$all_cdrs)
            $all_cdrs = array_merge($cdrs, $this->active_channels);

        // check if we have a retry in the following CDRs and active calls
        for ($j = $i + 1; $j < count($all_cdrs); $j++) {
            $cdr = $all_cdrs[$j];
            if ($current_cdr['channel'] === $cdr['channel'] && $current_cdr['dst'] === $cdr['dst']) {
                return true;
            }
        }
        return false;
    }

    protected function get_last_cdr() {
        if (!file_exists($this->cdr_marker))
            return false;

        $serialized_cdr = file_get_contents($this->cdr_marker);
        return !empty($serialized_cdr) ? unserialize($serialized_cdr) : false;
    }

    protected function set_last_cdr($cdr) {
        if (!is_array($cdr))
            return;

        if ($this->is_test()) {
            echo serialize($cdr);
        } else {
            file_put_contents($this->cdr_marker, serialize($cdr));
        }
    }

    protected function get_cdrs() {
        $sql = 'select * from ' . $this->cdr_db . '.' . $this->cdr_table . ' where lastapp = \'Dial\'';

        $last_cdr = $this->get_last_cdr();
        if (is_array($last_cdr))
            $sql .= ' and calldate >= \'' . $this->escape($last_cdr['calldate']) . '\'';

        $sql .= ' order by calldate asc limit ' . $this->cdr_batch;

        if ($this->is_test())
            self::print_ln(self::DEL, 'CDRs SQL:', $sql);

        $cdrs = $this->query($sql);

        // start from the next cdr
        if (is_array($last_cdr)) {
            while (count($cdrs)) {
                $cdr = array_shift($cdrs);
                if (self::is_cdrs_equal($cdr, $last_cdr))
                    break;
            }
        }

        if ($this->is_test())
            self::print_ln(self::DEL, 'CDRs count: ' . count($cdrs));

        return $cdrs;
    }

    protected function get_cdr($unique_id) {
        $sql = 'select * from ' . $this->cdr_db . '.' . $this->cdr_table . ' where lastapp = \'Dial\' and uniqueid = \'' . $this->escape($unique_id) . '\'';

        if ($this->is_test())
            self::print_ln(self::DEL, 'CDR SQL:', $sql);

        $cdrs = $this->query($sql);

        if ($this->is_test())
            self::print_ln(self::DEL, 'CDR FOUND:', $cdrs);

        return count($cdrs) == 1 ? $cdrs[0] : false;
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
        $shortargs = 'h::';
        $longargs = array(
            'help::',
            'test::',
            'cdr_db:',
            'cdr_table:',
            'cdr_marker:',
            'cdr_batch:',
            'cdr_mark:',
            'asterisk_path:'
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

    protected static function is_cdrs_equal($a, $b) {
        if (!is_array($a) || !is_array($b))
            return false;

        $columns = array(
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
            'uniqueid'
        );
        foreach ($columns as $column) {
            if (!array_key_exists($column, $a) || !array_key_exists($column, $b))
                return false;
            if ($a[$column] != $b[$column])
                return false;
        }
        return true;
    }

    protected function get_active_channels() {
        $result = array();
        $asterisk_path = $this->get_arg('asterisk_path', '/usr/sbin/asterisk');
        if (!file_exists($asterisk_path))
            return $result;

        $status = 0;
        $output = array();
        exec($asterisk_path . " -rx 'core show channels concise' 2>/dev/null", $output, $status);

        // parse asterisk cmd output, format:
        // Channel!Context!Exten!Priority!Stats!Application!Data!CallerID!Accountcode!Amaflags!Duration!Bridged
        foreach ($output as $line) {
            $parts = explode('!', $line);
            if (!is_array($parts) || count($parts) < 3)
                continue;

            // we need channel and number
            $result[] = array(
                'channel' => $parts[0],
                'dst' => $parts[2]
            );
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
