<?php

require_once("include/functions.php");

$request["env"] = ($_SERVER["REMOTE_ADDR"] =="127.0.0.1" ? "localhost" : ($_SERVER["REMOTE_ADDR"] == "70.161.245.130" ? "dev" : "live"));
$otm_template["directory"] = ($request["env"] == "localhost" ? "/otm_bemani/" : "/otm/");
set_error_handler("otm_error_handler");

otm_timer("page");
otm_timer("header");

otm_mysql_connect();

header("Content-type: text/html; charset=utf-8");

$cookies = otm_validate($_COOKIE, array(), array("userid", "sid"));
$request["page"] = basename($_SERVER["PHP_SELF"], ".php");
$request["user"] = otm_cookies_validate();

if(isset($_GET["admin"]) && $request["user"]["status"] >= 6)
	$request["user"]["theme"] = "admin";

include_once("languages/{$request["user"]["language"]}/main.lang");
include_once("languages/{$request["page"]}.lang");
include_once("languages/{$request["user"]["language"]}/{$request["page"]}.lang");

$otm_template["page"] = $lang["page"];

otm_timer("header");
otm_timer($request["page"]);

function otm_cookies_validate()
{
	global $request, $cookies;

	if(is_numeric($cookies["userid"]) && $cookies["userid"] != 1 && ($user_row = otm_mysql_select_single("SELECT *, DATE_ADD(last_gen, INTERVAL 1 MONTH)<NOW() AS old_sid FROM otm_user WHERE id={$cookies["userid"]}")) && $cookies["sid"] == md5($user_row["name"] . $user_row["last_gen"]) && $user_row["status"] > 2)
	{
		otm_mysql_execute("UPDATE otm_user SET last_active=NOW()" . ($user_row["old_sid"] ? ", last_gen=NOW()" : "") . " WHERE id={$user_row["id"]}");
		if($user_row["old_sid"])
		{
			$user_row = otm_mysql_select_single("SELECT * FROM otm_user WHERE id={$user_row["id"]}");
			setcookie("sid", md5($user_row["id"] . $user_row["last_gen"]), "2147483647");
		}

		return $user_row;
	}

	setcookie("userid", "", time() - 3600, "/");
	setcookie("sid", "", time() - 3600, "/");

	return otm_mysql_select_single("SELECT * FROM otm_user WHERE id=1");
}

# vim: set ft=php sw=4 ts=4 :
?>
