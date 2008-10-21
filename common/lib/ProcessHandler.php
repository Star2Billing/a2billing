<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class ProcessHandler{

	function isActive() {
        $pid = ProcessHandler::getPID();
        
        if ($pid == null) {
            $ret = false;
        } else {
            $ret = posix_kill ( $pid, 0 );
        }

        if ($ret == false) {
            ProcessHandler::activate();
        }

        return $ret;
    }
	
    function activate() {
        $pidfile = PID;
        $pid = ProcessHandler::getPID();

        if ($pid != null && $pid == getmypid()) {
            return "Already running!\n";
        } else {
            $fp = fopen($pidfile,"w+");

            if ($fp) {
                if (!fwrite($fp,"<"."?php\n\$pid = ".getmypid().";\n?".">")) {
                    die("Can not create pid file!\n");
                }

                fclose($fp);
            } else {
                die("Can not create pid file!\n");
            }
        }
    }
	
    function getPID() {
        if (file_exists(PID)) {
            require(PID);
            return $pid;
        } else {
            return null;
        }
    }
	
}
?>
