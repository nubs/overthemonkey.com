<?php

require_once("header.php");

$get = otm_validate($_GET, array("username"), array());
if($user_row = otm_mysql_select_single("SELECT * FROM otm_user WHERE name='{$get["username"]}'"))
{
	$otm_template["page"] = "{$get["username"]}'s Profile";
	$otm_template["s_user_name"] = $get["username"];
	$otm_template["s_user_profile"] = $user_row["profile"];
}

require_once("footer.php");

# vim : set ft=php sw=4 ts=4 :
?>
