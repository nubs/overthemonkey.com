<?php

require_once("header.php");

if(isset($_POST["submit"]))
{
	$post = otm_validate($_POST, array("email", "username", "password", "password2"), array("location", "profile", "language", "theme"));
	
	if($post["password"] != $post["password2"])
		otm_add_message("passwords_different");

	if(otm_mysql_select_single("SELECT name FROM otm_user WHERE name='{$post["username"]}'"))
		otm_add_message("username_taken");

	if(isset($otm_template["messages"]))
	{
		$otm_template["register_form"] = $lang["register_form"];
		$otm_template["register_form"][0]["f_email"] = $post["email"];
		$otm_template["register_form"][0]["f_username"] = $post["username"];
		$otm_template["register_form"][0]["f_location"] = $post["location"];
		$otm_template["register_form"][0]["f_profile"] = $post["profile"];
	}
	else
	{
		otm_mysql_execute("INSERT INTO otm_user (name, password, email, join_date, last_gen, location, profile) VALUES ('{$post["username"]}', '".md5($post["password"])."', '{$post["email"]}', NOW(), NOW(), '".str_replace("'", "''", htmlentities($post["location"]))."', '".str_replace("'", "''", htmlentities($post["profile"]))."')");

		$user_row = otm_mysql_select_single("SELECT last_gen FROM otm_user WHERE name='{$post["username"]}'");
		
		mail($post["email"], $lang["register_email"]["subject"], "{$lang["register_email"]["message"]}http://www.overthemonkey.com/otm/activate.php?username={$post["username"]}&sid=".md5($post["username"].$user_row["last_gen"]), "From: Over The Monkey <admin@overthemonkey.com>");

		otm_add_message("success");
	}
}
else
	$otm_template["register_form"] = $lang["register_form"];

require_once("footer.php");

# vim: set ft=php sw=4 ts=4 :
?>
