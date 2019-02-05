<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* Re-writes MySQL idioms to their Postgres counterparts
*
* Yes,  I've heard of wheels and I don't relish re-inventing them.  This one
* isn't even particularly round.  However,  using an off-the-shelf ORM would
* have taken more work,  with a much greater scope for disruptive consequences.
*
* Copyright (C) 2009 Steve Dommett <steve@st4vs.net>
*
* Please submit bug reports, patches, etc to http://www.a2billing.org/
* and,  ideally,  assign the ticket to 'stavros'
*
* This software is released under the terms of the GNU Lesser General Public License v2.1
* A copy of which is available from http://www.gnu.org/copyleft/lesser.html
*
* @category   Database
* @package    MytoPg
* @author     Steve Dommett <steve@st4vs.net>
* @copyright  2009 A2Billing
* @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @version    CVS: $Id:$
* @since      File available since Release 1.4
*
*/

class MytoPg
{
    // These regexes match the MySQL idioms that we need to rewrite
    public $mytopg = array(
        // The first pass matches only trivial deletions and re-writes
        '(\s*)(REGEXP|TRUNCATE|RAND\(\)|UNIX_TIMESTAMP\(|LIMIT[[:space:]]+[[:digit:]]+[[:space:]]*,[[:space:]]*[[:digit:]]+)(\s*)'
        // The 2nd pass matches functions which consume the following () too
        ,'(\s*)(CONCAT|REPLACE|ADDDATE|DATE_ADD|SUBDATE|DATE_SUB|SUBSTRING|TIMEDIFF|TIME_TO_SEC|DATETIME|TIMESTAMP|YEAR|MONTH|DAY|DATE_FORMAT|DATE_PART)(\s*)\('
        );
    public function __construct($debug = null)
    {
        $this -> DEBUG = $debug;
    }


    public function My_to_Pg(&$q)
    {
        $dbg = $this -> DEBUG;
        $count = 0;
        $slices = 0;
        $d = '';
        $matchpos = 0;

        $start = microtime(true);
        $old_length = strlen($q);
        if ($dbg > 1) {
            $backup = $q;
        }

        // iterate over both of our regexes
        for ($i = 0; $i < sizeof($this->mytopg); $i++) {
            $pos = 0;
            while ($pos < strlen($q)) {
                $slices++;
                $f = preg_match("/".$this->mytopg[$i]."/i", substr($q, $pos), $match);

                // there are no matches; use the remainder of the input verbatim
                if (!$f) {
                    if ($dbg > 3) $d .= ">>>> Skipping from pos $pos";
                    $pos = strlen($q);
                    continue;
                }

                // we're not interested in matches that are within quotes
                if ($this -> Parse_helper('quoted', $q, $matchpos, $dbg, $d)) {
                    if ($dbg > 3) $d .= ">>>> Skipping because it's quoted "
                        ." match: $match[2] at $matchpos";
                    $pos++;
                    continue;
                }

                 // we have a match;  process it and form the replacement text
                 $matchpos = strpos($q, $match[0], $pos);
                 $matched = strtoupper($match[2]);
                if ($dbg > 3) $d .= ">>>> Matched $match[2] at $matchpos";

                // these if()s on $i inside $i's own while() should circumvent
                // execution of superfluous, possibly costly, regex comparisons
                if ($i == 0) {
                    // this 1st regex matches only trivial rewrites
                    $remove =  strlen($match[0]);
                    if ('REGEXP' == $matched) {
                        // this is just a simple replacement,  nothing difficult
                        $new = $match[1].'~*'.$match[3];
                        $pos = $matchpos + strlen($match[1].$match[3]) + 2;

                    } elseif ('TRUNCATE' == $matched) {
                        // this is just a simple replacement,  nothing difficult
                        $new = $match[1].'TRUNC'.$match[3];
                        $pos = $matchpos + strlen($match[1].$match[3]) + 5;

                    } elseif ('RAND()' == $matched) {
                        // Again just a simple replace
                        $new = $match[1].'RANDOM()'.$match[3];
                        $pos = $matchpos + strlen($match[1].$match[3]) + 6;

                    } elseif ('UNIX_TIMESTAMP(' == $matched) {
                        // These get re-written again in pass 2
                        $new = $match[1].'date_part(\'epoch\','.$match[3];
                        $pos = $matchpos + strlen($match[1].$match[3]) + 18;

                    } elseif (preg_match('/LIMIT([[:space:]])([[:digit:]]*)'
                        .'([[:space:]]*),([[:space:]]*)'
                        .'([[:digit:]]*)/i', $match[2], $params)) {
                        // slightly more complex; we've got two parameters too
                        $new = "$match[1]LIMIT$params[1]$params[5]$params[3] "
                            ."OFFSET $params[4]$params[2]$match[3]";
                        $pos = $matchpos + strlen($match[1].$match[3]) + 1;

                    } else {
                        exit (">>>> Bug in My_to_Pg:  didn't process match "
                            ."$match[2] in \$mytopg[$i] ".__FILE__.':'.__LINE__);
                    }

                } elseif ($i == 1) {
                    // This 2nd regex matches functions taking parameters.
                    // These are more difficult as we must find the matching
                    // closing bracket, and thus the whole braced expression
                    $end = $this -> Parse_helper('brace', $q, $matchpos, $dbg, $d);
                    $exp = substr($q, $matchpos+strlen($match[0]), $end-$matchpos-strlen($match[0]));
                    $remove = strlen($match[0].$exp) + 1;

                    // split each element of this braced expression on comma
                    $rep = $this -> Parse_helper('split', $exp, ',', $dbg, $d);

                    // concat is simple
                    if ('CONCAT' == $matched) {
                        $new = "$match[1]$match[3](".implode($rep, ' || ').')';
                        $pos = $matchpos + strlen($match[1].$match[3]);

                    } elseif ('REPLACE' == $matched) {
                        // first we need to add a little escaping
                        foreach ($rep as &$value) {
                            // if a string literal contains '.',  add escaping
                            if (preg_match('/^\'.*[.].*\'$/', trim(ltrim($value)))) {
                                $value = preg_replace('/\'(.*)([.])(.*)\'/', ' E\'$1\\\\\\\$2$3\'', trim(ltrim($value)));
                            }
                        }
                        unset($value);
                        $new = "$match[1]REGEXP_REPLACE$match[3]("
                            .implode($rep, ',').", 'g')";
                        $pos = $matchpos + strlen($match[1].$match[3])+9;

                    } elseif (preg_match('/(ADD|SUB)DATE|DATE_(ADD|SUB)/', $matched, $tmp)) {
                        // determine whether to add or subtract
                        if ($tmp[1] == 'SUB' || (sizeof($tmp) > 2 && $tmp[2] == 'SUB')) {
                            $sign = ' - ';
                        } else {
                            $sign = ' + ';
                        }

                        // MySQL's ADD/SUBDATE has two possible syntaxes
                        $tmp = ltrim($rep[1]);
                        $tmp = $this -> Parse_helper('split', $tmp, ' ', $dbg, $d);
                        if ($dbg > 3) $d.=">>>> Found $match[2]$match[3]\$rep:>$rep[1]<\t>$rep[2]<\t>$rep[3]< , $tmp[0] '$tmp[1] $tmp[2]'";
                        if (sizeof($tmp) == 1) {
                            $rep[1] = "INTERVAL '$tmp[0] DAYS'";
                        } else {
                            $rep[1] = "$tmp[0] '$tmp[1] $tmp[2]'";
                        }

                        $rep[0] = $this -> Cast_date_part($rep[0]);
                        $new = "$match[1]$match[3](".implode($rep, $sign).')';
                        $pos = $matchpos + strlen($match[1].$match[3]);

                    } elseif ('SUBSTRING' == $matched) {
                        if ($dbg > 3) $d.=">>>> $match[2] : \$exp>$exp< \$rep[0-2]>$rep[0]<>$rep[1]<>$rep[2]<";
                        // if it looks like a field name containing time or date
                        if (preg_match('/\s*(\w*time|date\w*)\s*/i', $rep[0])) {
                            if ($rep[1] == 1 && $rep[2] == 10) {
                                // rewrite as cast to datestamp
                                $new = "$match[1]$match[3]("
                                    .rtrim($rep[0])."::date)";
                                $pos = $matchpos + strlen($match[1].$match[3]);

                            } elseif ($rep[1] == 1 && $rep[2] == 19) {
                                // rewrite as cast to timestamp
                                $new = "$match[1]$match[3]("
                                    .rtrim($rep[0])."::timestamp)";
                                $pos = $matchpos + strlen($match[1].$match[3]);

                            } else {
                                // we can only cast to text, which sucks
                                $new = "$match[0]".rtrim($rep[0])
                                    ."::text,$rep[1],$rep[2])";
                                $pos = $matchpos + strlen($match[0].$rep[0]);
                            }

                        } else {
                            // skip this field
                            $pos = $matchpos + strlen($match[1].$match[3]) + 1;
                            $remove = -1;
                        }

                    } elseif ('TIMEDIFF' == $matched) {
                        $new = "$match[1]$match[3](".implode($rep, ' - ').')';
                        $pos = $matchpos + strlen($match[1].$match[3]);

                    } elseif ('TIME_TO_SEC' == $matched) {
                        $new = "$match[1]EXTRACT$match[3](EPOCH FROM $exp)";
                        $pos = $matchpos + strlen($match[1].$match[3]) + 7;

                    } elseif ('TIMESTAMP' == $matched) {
                        $new = "$match[1]$match[3]($rep[0]::timestamp)";
                        $remove = strlen($new)-2;
                        $pos = $matchpos + strlen($match[1].$match[3]);

                    } elseif ('DATETIME' == $matched) {
                        // if it looks like a field name containing time or date
                        if (preg_match('/(\s*\w*)(time|date)(\w*)(\s*)/i', $rep[0], $parms)) {
                            // add a cast to timestamp
                            $rep[0] = "$parm[1]$parm[2]$parm[3]::timestamp$parm[4]";
                        } elseif (preg_match('/\'now\'/', $rep[0], $parms)) {
                            $rep[0] = "now()";
                        } else {
                            $rep[0] = $this -> Cast_date_part($rep[0]);
                        }
                        $new = "$match[1]($rep[0])$match[3]";
                        $pos = $matchpos + strlen($match[1].$match[3]) + 0;

                    } elseif ('YEAR' == $matched || 'MONTH' == $matched || 'DAY' == $matched) {
                        $new = "$match[1]date_part$match[3]('$matched',$exp)";
                        $pos = $matchpos + strlen($match[1].$match[3].$matched)+9;

                    } elseif ('DATE_FORMAT' == $matched) {
                        if (ltrim(trim($rep[1])) == "'%Y-%m-01'") {
                            $new = "$match[1](date_trunc('month',$rep[0])::date)$match[3]";
                            $pos = $matchpos + strlen($match[1].$match[3]);
                        } else {
                        exit (">>>> My_to_Pg needs to be extended to re-write $matched(x, $rep[0])"
                            ." in \$mytopg[$i] ".__FILE__.':'.__LINE__);
                        }

                    } elseif ('DATE_PART' == $matched) {
                        $rep[1] = $this -> Cast_date_part($rep[1]);
                        $new = "$match[1]$match[2]($rep[0],$rep[1])$match[3]";
                        $pos = $matchpos + strlen($match[1].$match[3]) + 1;

                    } else {
                        exit (">>>> Bug in My_to_Pg:  didn't process match "
                            ."$match[2] in \$mytopg[$i] ".__FILE__.':'.__LINE__);
                    }

                } else {
                    exit ("Bug in My_to_Pg: Found regex #$i : "
                        .($this -> $mytopg[$i])
                        ." but no handler found for it! ".__FILE__.':'.__LINE__);
                }

                // Finally (!) splice in the replacement, unless flagged not to
                if ($remove != -1) {
                    $count++;
                    $q = substr($q, 0, $matchpos)
                        . $new . substr($q, $matchpos+$remove);
                }

                if ($dbg > 3) {
                    $d .= " >>>> pos:$pos matchpos:$matchpos "
                        ."match:$match[2] >>>> exp:$exp >>>> new:$new";
                }
            }
        }

        // Even if debug = 0 log brief details if the rewrite took 30ms or more
        $time = (microtime(true)-$start)*1000;
        if ($time >= 30.0 || $dbg) {
            $msg = "My_to_Pg took ".sprintf('%0.3f',$time).'ms, '
            ."$count/$slices replacements/loops, length "
            ."$old_length -> ".strlen($q)." chars"
            .(($dbg > 1)?">>>>$backup>>>>to>>>>$q$d":'');

        openlog("A2B-MytoPg", LOG_PID, LOG_LOCAL0); //  | LOG_PERROR
        syslog(LOG_DEBUG, $msg);
        closelog();
        }
    }



    // Being careful of escaping, quoting and brackets,  returns when $mode is:
    //  brace: the position of the matching close bracket after $p
    //  quote: the position of the matching close quote after $p
    //  split: an array of the string split on character $p
    // quoted: 1 if the position $p is within a quoted string
    public function Parse_helper($mode, &$str, $p, $dbg, &$d)
    {
        $lastpos = 0;
        $brackets = 0;
        $squotes = 0;
        $dquotes = 0;
        if ($mode == 'brace' || $mode == 'quote') {
            $pos = $p;
            $match = 0;
        } else {
            $pos = 0;
        }
        $element = 0;
        $char = '';
        $loop = 1;
        $length = strlen($str);
        $splits = array("'", '"', '(', ')');
        if ($mode == 'split') {
            $splits[sizeof($splits)] = $p;
        }

        do {
            $char = substr($str, $pos, 1);
            if ($dbg > 4) $d .= ">>>> mode $mode pos $pos char $char min $min length $length\n";
            if ($pos == 0 || substr($str, $pos-1, 1) != '\\') {
                if ($char == "'" && !$dquotes) {
                    $squotes = ($squotes+1) % 2;
                }
                if ($char == '"' && !$squotes) {
                    $dquotes = ($dquotes+1) % 2;
                }
                if ($char == '(' && !($squotes || $dquotes)) {
                    $brackets++;
                }
                if ($char == ')' && !($squotes || $dquotes)) {
                    $brackets--;
                    if ($brackets < 0) $brackets = 0;
                }
                if ($mode == 'brace') {
                    if ($brackets > $match && !$match) {
                        $match = $brackets;
                    }
                    $loop = ($brackets || !$match);
                    if ($dbg > 4) $d.=">>>> brace char $char pos $pos match $match loop $loop";

                } elseif ($mode == 'quote') {
                    if ($char == '"' || $char == "'") {
                        if ($match && !$squotes && !$dquotes) {
                            $loop = 0;
                        } elseif (!$match && ($squotes XOR $dquotes)) {
                            $match = $char;
                        }
                    }

                } elseif ($mode == 'split' && $char == $p && !$squotes && !$dquotes && !$brackets) {
                    if ($dbg > 4) $d .= ">>>>split iter: char:'$char'\tpos:$pos\tloop:$loop\tsq:$squotes\tdq:$dquotes";
                    $res[$element++] = substr($str,$lastpos,$pos-$lastpos);
                    $lastpos = $pos+1;	// skip the separator character too

                } elseif ($mode == 'quoted') {
                    $loop = ($pos < $p);
                    if ($dbg > 4) $d .= ">>>> $mode iter: char:'$char'\tpos:$pos\tloop:$loop\tsq:$squotes\tdq:$dquotes";
                }
            }
            $pos++;
            if ($dbg > 4) $d .= ">>>> $mode end: char:'$char'\t\tpos++:$pos\tsq:$squotes\tdq:$dquotes";

            // If you can figure out why this optimisation causes those two
            // modes to fail,  I'd really like to know! email: steve@st4vs.net
            if ($mode == 'brace' || $mode == 'quote')
                continue;
            // Fast forward to the next character of interest
            $min = 100000000;
            for ($i = 0; $i < sizeof($splits); $i++) {
                $tmp = strpos($str, $splits[$i], $pos);
                if ($tmp !== FALSE && $tmp < $min) $min = $tmp;
            }
            if ($min < $length) {
                $pos = $min;
            } else {
                $loop = 0;
            }
            if ($dbg > 4) $d .= ">>>> $mode ffwd: \tpos:$pos\tloop:$loop\tmin:$min";

        } while ($pos < $length && $loop);

        // Now return the correct form of result
        if ($mode == 'brace' || $mode == 'quote') {
            return ($pos-1);

        } elseif ($mode == 'split') {
            $res[$element] = substr($str, $lastpos);

            return ($res);

        } elseif ($mode == 'quoted') {
            return ($squotes || $dquotes);

        } else {
            exit ("MytoPG's Parse_helper has no mode $mode ".__FILE__.':'.__LINE__);
        }
    }

    // If a date participle looks like an immediate constant, cast it appropriately
    public function Cast_date_part($part)
    {
        if (preg_match('/([[:space:]]*)[\'"]now[\'"]([[:space:]]*)/i', $part, $parm)) {
            $part = "$parm[1]now()$parm[2]";
//                                                 |      year         | month 0?1-1     | day 0?1-31           |      hours 0?0-24     | min/secs 0?0-59     |  Timezone? [-+]0-1459] or ABC        |
        } elseif (preg_match ('/([[:space:]]*)([\'"][[:space:]]*[[:digit:]]{4}-[0-1]?[[:digit:]]-[0-3]?[[:digit:]][[:space:]][0-2]?[[:digit:]](:[0-5]?[[:digit:]]){2}([[:space:]]?([-+][[:digit:]]{1,4}|[[:alpha:]]{2,3}))?[[:space:]]*[\'"])([[:space:]]*)/', $part, $parm)) {
            $part = "$parm[1]$parm[2]::timestamp$parm[6]";
//                                                 |      year         | month 0?1-12    | day 0?1-31           |
        } elseif (preg_match ('/([[:space:]]*)([\'"][[:space:]]*[[:digit:]]{4}-[0-1]?[[:digit:]]-[0-3]?[[:digit:]][[:space:]]*[\'"])([[:space:]]*)/', $part, $parm)) {
            $part = "$parm[1]$parm[2]::date$parm[3]";
        }

        return $part;
    }
}
