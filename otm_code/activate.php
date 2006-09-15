<?php
require_once("header.php");

$get = otm_validate($_GET, array("username", "sid"), array());

if(!($user_row = otm_mysql_select_single("SELECT status, last_gen FROM otm_user WHERE name='{$get["username"]}'")) || $get["sid"] != md5($get["username"] . $user_row["last_gen"]))
	otm_add_message("info_wrong");
elseif($user_row["status"] != 2)
	otm_add_message("already_activated");
else
{
	otm_mysql_execute("UPDATE otm_user SET status=4 WHERE name='{$get["username"]}'");
	otm_add_message("success");
}

require_once("footer.php");

# vim: set ft=php sw=4 ts=4 :
?>
