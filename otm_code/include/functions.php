<?php

require_once("include/template.php");
require_once("include/mysql.php");

function otm_alternate(& $var, $val1, $val2)
{
	return $var = ($var == $val1 ? $val2 : $val1);
}

function otm_validate($array, $required, $optional)
{
	foreach($required as $field)
	{
		$ret[$field] = trim($array[$field]);

		switch($field)
		{
		case "email":
			if(!otm_is_valid_email($ret[$field]))
				$ret[$field] = "";

			break;
		case "username":
			if(!otm_is_valid_name($ret[$field]))
				$ret[$field] = "";

			break;
		default:
			break;
		}

		if($ret[$field] == "")
			otm_add_message("{$field}_invalid");
	}

	foreach($optional as $field)
		$ret[$field] = (isset($array[$field]) ? trim($array[$field]) : "");
	
	return $ret;
}

function otm_timer($name)
{
	global $time;

	$now = array_sum(explode(" ", microtime()));
	$time[$name] = (isset($time[$name]) ? ($now - $time[$name]) * 1000 . "ms" : $now);
}

function otm_add_message($id)
{
	global $otm_template, $lang, $request;

	$otm_template["messages"][] = $lang["{$request["page"]}_messages"][$id];
}

function otm_error_handler($errno, $errmsg, $filename, $linenum, $vars)
{
	global $request;

	$err = "Date:\t\t".date("Y-m-d H:i:s (T)")."\nError #:\t$errno\nError Message:\t$errmsg\nFilename:\t$filename\nLine:\t\t$linenum\n";

	error_log($err, 3, "/var/www/log/php.log");

	if($errno & (E_ERROR | E_PARSE | E_CORE_ERROR | E_USER_ERROR))
	{
		if($request["env"] == "live")
		{
			mail("anubis@vt.edu", "Critical User Error", $err . print_r($vars, true));
			die("error_message");
		}
		else
			die("<pre>\n$err" . print_r($vars, true) . "</pre>\n");
	}
}

function otm_is_valid_name($name)
{
	return preg_match("/^[a-z0-9\-_]{1,16}$/i", $name);
}

function otm_is_valid_email($email)
{
	return preg_match("/^[a-z0-9._%\-]+@[a-z0-9.\-]+\.[a-z0-9]+$/i", $email);
}

# vim: set ft=php sw=4 ts=4 :
?>
