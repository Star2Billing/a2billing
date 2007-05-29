<?php
 /**
  * phpagi-asmanager.php : PHP Asterisk Manager functions
  * Website: http://phpagi.sourceforge.net
  *
  * $Id: phpagi-asmanager.php,v 1.6 2005/02/16 23:31:50 pinhole Exp $
  *
  * Copyright (c) 2004 Matthew Asham <matthewa@bcwireless.net>
  * All Rights Reserved.
  *
  * This software is released under the terms of the GNU Public License v2
  *  A copy of which is available from http://www.fsf.org/licenses/gpl.txt
  * 
  *
  *  You are requested to drop me an Email letting me know that you're 
  *  using it.  This is more of a courtesy than anything else, but I am 
  *  interested to know how it is being used.
  *
  * @package phpAGI
  * @version 2.0
  */


 /**
  * Written for PHP 4.3.4, should work with older PHP 4.x versions.  
  * Please submit bug reports, patches, etc to http://sourceforge.net/projects/phpagi/
  * Gracias. :)
  *
  */

  if(!class_exists('AGI'))
  {
    require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'phpagi.php');
  }

 /**
  * Asterisk Manager class
  *
  * @link http://www.voip-info.org/wiki-Asterisk+config+manager.conf
  * @link http://www.voip-info.org/wiki-Asterisk+manager+API
  * @example examples/sip_show_peer.php Get information about a sip peer
  * @package phpAGI
  */
  class AGI_AsteriskManager
  {
   /**
    * Config variables
    *
    * @var array
    * @access public
    */
    var $config;

   /**
    * Socket
    *
    * @access public
    */
    var $socket = NULL;

   /**
    * Server we are connected to
    *
    * @access public
    * @var string
    */
    var $server;

   /**
    * Port on the server we are connected to
    *
    * @access public
    * @var integer
    */
    var $port;

   /**
    * Parent AGI
    *
    * @access private
    * @var AGI
    */
    var $pagi;

   /**
    * Event Handlers
    *
    * @access private
    * @var array
    */
    var $event_handlers;

   /**
    * Constructor
    *
    * @param string $config is the name of the config file to parse or a parent agi from which to read the config
    * @param array $optconfig is an array of configuration vars and vals, stuffed into $this->config['asmanager']
    */
    function AGI_AsteriskManager($config=NULL, $optconfig=array())
    {
      // load config
      if(!is_null($config) && file_exists($config))
        $this->config = parse_ini_file($config, true);
      elseif(file_exists(DEFAULT_PHPAGI_CONFIG))
        $this->config = parse_ini_file(DEFAULT_PHPAGI_CONFIG, true);

      // If optconfig is specified, stuff vals and vars into 'asmanager' config array.
      foreach($optconfig as $var=>$val)
        $this->config['asmanager'][$var] = $val;

      // add default values to config for uninitialized values
      if(!isset($this->config['asmanager']['server'])) $this->config['asmanager']['server'] = 'localhost';
      if(!isset($this->config['asmanager']['port'])) $this->config['asmanager']['port'] = 5038;
      if(!isset($this->config['asmanager']['username'])) $this->config['asmanager']['username'] = 'phpagi';
      if(!isset($this->config['asmanager']['secret'])) $this->config['asmanager']['secret'] = 'phpagi';
    }

   /**
    * Send a request
    *
    * @param string $action
    * @param array $parameters
    * @return array of parameters
    */
    function send_request($action, $parameters=array())
    {
      $req = "Action: $action\r\n";
      foreach($parameters as $var=>$val)
        $req .= "$var: $val\r\n";
      $req .= "\r\n";
      fwrite($this->socket, $req);
      return $this->wait_response();
    }

   /**
    * Wait for a response
    *
    * If a request was just sent, this will return the response.
    * Otherwise, it will loop forever, handling events.
    *
    * @param boolean $allow_timeout if the socket times out, return an empty array
    * @return array of parameters, empty on timeout
    */
    function wait_response($allow_timeout=false)
    {
      $timeout = false;
      do
      {
        $type = NULL;
        $parameters = array();

        $buffer = trim(fgets($this->socket, 4096));
        while($buffer != '')
        {
          $a = strpos($buffer, ':');
          if($a)
          {
            if(!count($parameters)) // first line in a response?
            {
              $type = strtolower(substr($buffer, 0, $a));
              if(substr($buffer, $a + 2) == 'Follows')
              {
                // A follows response means there is a miltiline field that follows.
                $parameters['data'] = '';
                $buff = fgets($this->socket, 4096);
                while(substr($buff, 0, 6) != '--END ')
                {
                  $parameters['data'] .= $buff;
                  $buff = fgets($this->socket, 4096);
                }
              }
            }

            // store parameter in $parameters
            $parameters[substr($buffer, 0, $a)] = substr($buffer, $a + 2);
          }
          $buffer = trim(fgets($this->socket, 4096));
        }

        // process response
        switch($type)
        {
          case '': // timeout occured
            $timeout = $allow_timeout;
            break;
          case 'event':
            $this->process_event($parameters);
            break;
          case 'response':
            break;
          default:
            $this->log('Unhandled response packet from Manager: ' . print_r($parameters, true));
            break;
        }
      } while($type != 'response' && !$timeout);
      return $parameters;
    }

   /**
    * Connect to Asterisk
    *
    * @example examples/sip_show_peer.php Get information about a sip peer
    *
    * @param string $server
    * @param string $username
    * @param string $secret
    * @return boolean true on success
    */
    function connect($server=NULL, $username=NULL, $secret=NULL)
    {
      // use config if not specified
      if(is_null($server)) $server = $this->config['asmanager']['server'];
      if(is_null($username)) $username = $this->config['asmanager']['username'];
      if(is_null($secret)) $secret = $this->config['asmanager']['secret'];

      // get port from server if specified
      if(strpos($server, ':') !== false)
      {
        $c = explode(':', $server);
        $this->server = $c[0];
        $this->port = $c[1];
      }
      else
      {
        $this->server = $server;
        $this->port = $this->config['asmanager']['port'];
      }

      // connect the socket
      $errno = $errstr = NULL;
      $this->socket = @fsockopen($this->server, $this->port, $errno, $errstr);
      if($this->socket == false)
      {
        $this->log("Unable to connect to manager {$this->server}:{$this->port} ($errno): $errstr");
        return false;
      }

      // read the header
      $str = fgets($this->socket);
      if($str == false)
      {
        // a problem.
        $this->log("Asterisk Manager header not received.");
        return false;
      }
      else
      {
        // note: don't $this->log($str) until someone looks to see why it mangles the logging
      }

      // login
      $res = $this->send_request('login', array('Username'=>$username, 'Secret'=>$secret));
      if($res['Response'] != 'Success')
      {
        $this->log("Failed to login.");
        $this->disconnect();
        return false;
      }
      return true;
    }

   /**
    * Disconnect
    *
    * @example examples/sip_show_peer.php Get information about a sip peer
    */
    function disconnect()
    {
      $this->logoff();
      fclose($this->socket);
    }

   /**
    * Set Absolute Timeout
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+AbsoluteTimeout
    * @param string $channel
    * @param integer $timeout
    */
    function AbsoluteTimeout($channel, $timeout)
    {
      return $this->send_request('AbsoluteTimeout', array('Channel'=>$channel, 'Timeout'=>$timeout));
    }

   /**
    * Change monitoring filename of a channel
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+ChangeMonitor
    * @param string $channel
    * @param string $file
    */
    function ChangeMonitor($channel, $file)
    {
      return $this->send_request('ChangeMontior', array('Channel'=>$channel, 'File'=>$file));
    }

   /**
    * Execute Command
    *
    * @example examples/sip_show_peer.php Get information about a sip peer
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Command
    * @link http://www.voip-info.org/wiki-Asterisk+CLI
    * @param string $commadn
    */
    function Command($command)
    {	
      return $this->send_request('Command', array('Command'=>$command));
    }

   /**
    * Enable/Disable sending of events to this manager
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Events
    * @param string $eventmask is either 'on', 'off', or 'system,call,log'
    */
    function Events($eventmask)
    {
      return $this->send_request('Events', array('EventMask'=>$eventmask));
    }

   /**
    * Check Extension Status
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+ExtensionState
    * @param string $exten
    * @param string $context
    * @param string $actionid
    */
    function ExtensionState($exten, $context, $actionid)
    {
      return $this->send_request('ExtensionState', array('Exten'=>$exten, 'Context'=>$context, 'ActionID'=>$actionid));
    }

   /**
    * Gets a Channel Variable
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+GetVar
    * @param string $channel
    * @param string $variable
    */
    function GetVar($channel, $variable)
    {
      return $this->send_request('GetVar', array('Channel'=>$channel, 'Variable'=>$variable));
    }

   /**
    * Hangup Channel
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Hangup
    * @param string $channel
    */
    function Hangup($channel)
    {
      return $this->send_request('Hangup', array('Channel'=>$channel));
  }

   /**
    * List IAX Peers
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+IAXpeers
    */
    function IAXPeers()
    {
      return $this->send_request('IAXPeers');
    }

   /**
    * List available manager commands
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+ListCommands
    */
    function ListCommands()
    {
      return $this->send_request('ListCommands');
    }

   /**
    * Logoff Manager
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Logoff
    */
    function Logoff()
    {
      return $this->send_request('Logoff');
    }

   /**
    * Check Mailbox Message Count
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+MailboxCount
    * @param string $mailbox
    */
    function MailboxCount($mailbox)
    {
      return $this->send_request('MailboxCount', array('Mailbox'=>$mailbox));
    }

   /**
    * Check Mailbox
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+MailboxStatus
    * @param string $mailbox
    */
    function MailboxStatus($mailbox)
    {	
      return $this->send_request('MailboxStatus', array('Mailbox'=>$mailbox));
    }

   /**
    * Monitor a channel
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Monitor
    * @param string $channel
    * @param string $file
    */
    function Monitor($channel, $file)
    {
      return $this->send_request('Monitor', array('Channel'=>$channel, 'File'=>$file));
    }

   /**
    * Originate Call
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Originate
    * @param string $channel
    * @param string $exten
    * @param string $context
    * @param string $priority
    * @param integer $timeout
    * @param string $callerid
    * @param string $variable
    * @param string $account
    * @param string $application
    * @param string $data
    */
    function Originate($channel, $exten, $context, $priority, $timeout, $callerid, $variable, $account, $application, $data)
    {
      $parameters = array();
      if($channel) $parameters['Channel'] = $channel;
      if($exten) $parameters['Exten'] = $exten;
      if($context) $parameters['Context'] = $context;
      if($priority) $parameters['Priority'] = $priority;
      if($timeout) $parameters['Timeout'] = $timeout;
      if($callerid) $parameters['CallerID'] = $callerid;
      if($variable) $parameters['Variable'] = $variable;
      if($account) $parameters['Account'] = $account;
      if($application) $parameters['Application'] = $application;
      if($data) $parameters['Data'] = $data;
      return $this->send_request('Originate', $parameters);
    }	

   /**
    * List parked calls
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+ParkedCalls
    */
    function ParkedCalls()
    {
      return $this->send_request('ParkedCalls');
    }

   /**
    * Ping
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Ping
    */
    function Ping()
    {
      return $this->send_request('Ping');
    }

   /**
    * Queues
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Queues
    */
    function Queues()
    {
      return $this->send_request('Queues');
    }

   /**
    * Queue Status
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+QueueStatus
    */
    function QueueStatus()
    {
      return $this->send_request('QueueStatus');
    }

   /**
    * Redirect
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Redirect
    * @param string $channel
    * @param string $extrachannel
    * @param string $exten
    * @param string $context
    * @param string $priority
    */
    function Redirect($channel, $extrachannel, $exten, $context, $priority)
    {
      return $this->send_request('Redirect', array('Channel'=>$channel, 'ExtraChannel'=>$extrachannel, 'Exten'=>$exten,
                                                   'Context'=>$context, 'Priority'=>$priority));
    }

   /**
    * Set the CDR UserField
    */
    function SetCDRUserField()
    {
      // XXX need to look at source to find this function...
      return $this->send_request('SetCDRUserField');
    }

   /**
    * Set Channel Variable
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+SetVar
    * @param string $channel
    * @param string $variable
    * @param string $value
    */
    function SetVar($channel, $variable, $value)
    {
      return $this->send_request('SetVar', array('Channel'=>$channel, 'Variable'=>$variable, 'Value'=>$value));
    }

   /**
    * List SIP Peers
    */
    function SIPpeers()
    {
      // XXX need to look at source to find this function...
      return $this->send_request('SIPpeers');
    }

   /**
    * Channel Status
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+Status
    * @param string $channel
    */
    function Status($channel)
    {	
      return $this->send_request('Status', array('Channel'=>$channel));
    }

   /**
    * Stop monitoring a channel
    *
    * @link http://www.voip-info.org/wiki-Asterisk+Manager+API+Action+StopMonitor
    * @param string $channel
    */
    function StopMontor($channel)
    {
      return $this->send_request('StopMonitor', array('Channel'=>$channel));
    }

   /**
    * Dial over Zap channel while offhook
    */
    function ZapDialOffhook()
    {
      // XXX need to look at source to find this function...
      return $this->send_request('ZapDialOffhook');
    }

   /**
    * Toggle Zap channel Do Not Disturb status OFF
    */
    function ZapDNDoff()
    {
      // XXX need to look at source to find this function...
      return $this->send_request('ZapDNDoff');
    }

   /**
    * Toggle Zap channel Do Not Disturb status ON
    */
    function ZapDNDon()
    {
      // XXX need to look at source to find this function...
      return $this->send_request('ZapDNDon');
    }

   /**
    * Hangup Zap Channel
    */
    function ZapHangup()
    {
      // XXX need to look at source to find this function...
      return $this->send_request('ZapHangup');
    }

   /**
    * Transfer Zap Channel
    */
    function ZapTransfer()
    {
      // XXX need to look at source to find this function...
      return $this->send_request('ZapTransfer');
    }

   /*
    * Log a message
    *
    * @param string $message
    * @param integer $level from 1 to 4
    */
    function log($message, $level=1)
    {
      if($this->pagi != false)
        $this->pagi->conlog($message, $level);
      else
        error_log(date('r') . ' - ' . $message);
    }

   /**
    * Add event handler
    *
    * Known Events include ( http://www.voip-info.org/wiki-asterisk+manager+events )
    *   Link - Fired when two voice channels are linked together and voice data exchange commences.
    *   Unlink - Fired when a link between two voice channels is discontinued, for example, just before call completion.
    *   Newexten -
    *   Hangup -
    *   Newchannel -
    *   Newstate -
    *   Reload - Fired when the "RELOAD" console command is executed.
    *   Shutdown -
    *   ExtensionStatus -
    *   Rename -
    *   Newcallerid -
    *   Alarm -
    *   AlarmClear -
    *   Agentcallbacklogoff -
    *   Agentcallbacklogin -
    *   Agentlogoff -
    *   MeetmeJoin -
    *   MessageWaiting -
    *   join -
    *   leave -
    *   AgentCalled -
    *   ParkedCall -
    *   Cdr -
    *   ParkedCallsComplete -
    *   QueueParams -
    *   QueueMember -
    *   QueueStatusEnd -
    *   Status -
    *   StatusComplete -
    *   ZapShowChannels -
    *   ZapShowChannelsComplete -
    *
    * @param string $event type or * for default handler
    * @param string $callback function
    * @return boolean sucess
    */
    function add_event_handler($event, $callback)
    {
      $event = strtolower($event);
      if(isset($this->event_handlers[$event]))
      {
        $this->log("$event handler is already defined, not over-writing.");
        return false;
      }
      $this->event_handlers[$event] = $callback;
      return true;
    }

   /**
    * Process event
    *
    * @access private
    * @param array $parameters
    * @return mixed result of event handler or false if no handler was found
    */
    function process_event($parameters)
    {
      $ret = false;
      $e = strtolower($parameters['Event']);
      $this->log("Got event.. $e");		

      $handler = '';
      if(isset($this->event_handlers[$e])) $handler = $this->event_handlers[$e];
      elseif(isset($this->event_handlers['*'])) $handler = $this->event_handlers['*'];

      if(function_exists($handler))
      {
        $this->logg("Execute handler $handler");
        $ret = $handler($e, $parameters, $this->server, $this->port);
      }
      else
        $this->log("No event handler for event '$e'");
      return $ret;
    }
  }
?>
