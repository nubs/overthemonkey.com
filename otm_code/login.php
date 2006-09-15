<?php

require_once("header.php");

if($request["user"]["id"] != 1)
	otm_add_message("already_logged_in");
elseif(isset($_POST["submit"]))
{
	$post = otm_validate($_POST, array("username", "password"), array());

	if($user_row = otm_mysql_select_single("SELECT * FROM otm_user WHERE name='{$post["username"]}' AND password='".md5($post["password"])."'"))
	{
		if($user_row["status"] < 3)
			otm_add_message("bad_permissions");
	}
	else
		otm_add_message("password_wrong");

	if(isset($otm_template["messages"]))
	{
		$otm_template["login_form"] = $lang["login_form"];
		$otm_template["login_form"][0]["f_username"] = $post["username"];
	}
	else
	{
		$request["user"] = $user_row;
		setcookie("userid", $user_row["id"], "2147483647", "/");
		setcookie("sid", md5($user_row["name"] . $user_row["last_gen"]), "2147483647", "/");

		otm_add_message("success");
	}
}
else
	$otm_template["login_form"] = $lang["login_form"];

require_once("footer.php");

# vim: set ft=php sw=4 ts=4 :
?>
