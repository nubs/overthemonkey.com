<?php

require_once("header.php");

if(isset($_POST["ban_submit"]) && $request["user"]["theme"] == "admin" && $users_result = otm_mysql_select_multiple("SELECT id, status FROM otm_user"))
{
	while($users_row = mysql_fetch_assoc($users_result))
	{
		if(isset($_POST["ban{$users_row["id"]}"]) && $_POST["ban{$users_row["id"]}"] == "ban" && $users_row["status"] != 1)
			otm_mysql_execute("UPDATE otm_user SET status=1 WHERE id={$users_row["id"]}");
		elseif((!isset($_POST["ban{$users_row["id"]}"]) || $_POST["ban{$users_row["id"]}"] != "ban") && $users_row["status"] == 1)
			otm_mysql_execute("UPDATE otm_user SET status=4 WHERE id={$users_row["id"]}");
	}
}

$get = otm_validate($_GET, array(), array("search_name", "search_location", "search_email"));

if($users_result = otm_mysql_select_multiple("SELECT * FROM otm_user WHERE id!=1" . ($request["user"]["theme"] == "admin" ? "" : " AND status>2") . ($get["search_name"] ? " AND name REGEXP '{$get["search_name"]}'" : "") . ($get["search_location"] ? " AND location REGEXP '{$get["search_location"]}'" : "") . ($get["search_email"] ? " AND email REGEXP '{$get["search_email"]}'" : "") . " ORDER BY name ASC"))
{
	while($users_row = mysql_fetch_assoc($users_result))
		$otm_template["users"][] = array("n_id" => $users_row["id"], "s_name" => $users_row["name"], "s_location" => $users_row["location"], "s_email" => $users_row["email"], "v_rowstyle" => otm_alternate($rowstyle, "row1", "row2"), "banned" => ($users_row["status"]==1 ? " checked" : ""));
}

$otm_template["f_search_name"] = $get["search_name"];
$otm_template["f_search_location"] = $get["search_location"];
$otm_template["f_search_email"] = $get["search_email"];

require_once("footer.php");

# vim: set ft=php sw=4 ts=4 :
?>
