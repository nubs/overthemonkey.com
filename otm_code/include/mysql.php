<?php

function otm_mysql_connect()
{
	global $request;
	
	if($request["env"] == "localhost")
	{
		mysql_connect("localhost", "", "") or trigger_error(mysql_error(), E_USER_ERROR);
		mysql_select_db("otm_bemani") or trigger_error(mysql_error(), E_USER_ERROR);
	}
	else
	{
		mysql_connect("", "", "") or trigger_error(mysql_error(), E_USER_ERROR);
		mysql_select_db("db89119892") or trigger_error(mysql_error(), E_USER_ERROR);
	}
}

function otm_mysql_select_single($query)
{
	$result = mysql_query($query) or trigger_error(mysql_error(), E_USER_ERROR);
	return (mysql_num_rows($result) ? mysql_fetch_assoc($result) : false);
}

function otm_mysql_select_multiple($query)
{
	$result = mysql_query($query) or trigger_error(mysql_error(), E_USER_ERROR);
	return (mysql_num_rows($result) ? $result : false);
}

function otm_mysql_execute($query)
{
	mysql_query($query) or trigger_error(mysql_error(), E_USER_ERROR);
	return mysql_affected_rows($result);
}

# vim: set ft=php sw=4 ts=4 :
?>
