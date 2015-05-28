<?php

ini_set("display_errors", 1);
ini_set('taint.enable', 1);
ini_set('taint.error_level', E_WARNING);

error_reporting(E_ALL);

class NexString
{
    function __construct($s, $tainted=false, $area=[]) {
        $this->areas = [];
        $this->tainted = (bool)$tainted;
        if (!count($area) == 0) {
            array_push($this->areas, (array)$area);
            $tainted = true;
        }
        $this->value = (string)$s;
    }

    function process($callback=null) {
        $copy = $this->value;
        foreach ($this->areas as $area) {
            $amount = ($area[1]-$area[0])+1;
            $sub = substr($copy, $area[0]-1, $amount);
            $copy = substr_replace($copy, $callback($sub), $area[0]-1, $amount);
        }
        return $copy;
    }


    function taint($start=0, $len=0) {
        if (!in_array([$start, $len], $this->areas)) {
            array_push($this->areas, [$start, $len]);
        }
    }

    function areas() {
        return $this->areas;
    }
}

function nex_include($file) {
    if (!in_array(realpath($file), get_included_files())) {
        if ($file instanceof NexString) {
            if ($file->tainted) { 
                die("Taint escalation for an include() statement. Payload: $file\n");
            } else {
                include($file->value);
            }
        } else {
            if (is_tainted($file)) {
                die("Taint escalation for an include() statement. Payload: $file\n");
            } else {
                include($file);
            }
        }
    }
}

function convert_to_nexstr($s) {
    $tainted = (bool)is_tainted($s);
    $arr = [];
    if ($tainted) {
        $arr = [1, strlen($s)];
    } else {
        $arr = [];
    }
    return new NexString($s, $tainted, $arr);
}

function nex_concat($s1, $s2) {
    $ret = null;
    if ($s1 instanceof NexString
        && $s2 instanceof NexString)
    {
        if (count($s2->areas) > 0) {
            foreach ($s2->areas as &$e) {
                $e[0] += strlen($s1->value);
                $e[1] += strlen($s1->value);
            }
        }
        $s1->areas = array_merge($s1->areas, $s2->areas);
        $s1->value = $s1->value . $s2->value;    
        $ret = $s1;
    } elseif ($s1 instanceof NexString) {
        $s2 = convert_to_nexstr($s2);
        $ret = nex_concat($s1, $s2);
    } elseif ($s2 instanceof NexString) {
        $s1 = convert_to_nexstr($s1);
        $ret = nex_concat($s1, $s2);
    } else {
        $s1 = convert_to_nexstr($s1);
        $s2 = convert_to_nexstr($s2);
        $ret = nex_concat($s1, $s2);
    }
    return $ret;
}

function nex_echo($s) {
    if ($s->tainted) {
        die("XSS Vulnerability found payload: $s\n");
    }
    $defense = create_function('$s', 'return htmlentities($s, ENT_QUOTES);');
    $val = $s->process($defense);
    echo $val;
}



function nex_mysql_connect($h, $u, $p, $i, $y) {
    echo "Connected to MySQL\n";
    return "Connected";
}

function nex_mysql_select_db($h) {
    echo "MySQL DB chosen.\n";
    return true;
}


function nex_mysql_query($s) {
    if ($s->tainted) {
        die("SQL Vulnerability found payload: $s\n");
    }
    $defense = create_function('$s', 'return \'UNHEX(\'.bin2hex($s).\')\';');
    $val = $s->process($defense);
    echo $val . "\n";
}

?>
