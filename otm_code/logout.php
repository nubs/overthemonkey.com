<?php

require_once("header.php");

if($request["user"]["id"] == 1)
	otm_add_message("already_logged_out");
else
{
	setcookie("userid", "", time()-3600, "/");
	setcookie("sid", "", time()-3600, "/");

	otm_add_message("success");
}

require_once("footer.php");

# vim: set ft=php sw=4 ts=4 :
?>
