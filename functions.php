<?PHP

function make_clickable($text)
{
	$ret = ' ' . $text;
	$ret = preg_replace("#(^|[\n ])([\w]+?://.*?[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:/[^ \"\t\n\r<]*)?)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
	$ret = substr($ret, 1);
	
	return($ret);
}

function parse($str)
{
	$str=nl2br($str);
	$str=make_clickable($str);
	
	$result=mysql_query("SELECT * FROM emoticon") or die(mysql_error());
	while($row=mysql_fetch_array($result))
	{
		$emotes[]=$row["emote"];
		$images[]="<img src=\"http://www.overthemonkey.com/emoticons/".$row["image"]."\">";
	}
	$str = str_replace($emotes, $images, $str);
	$str=preg_replace('#\[B\](.+?)\[/B\]#si', '<b>$1</b>', $str);
	$str=preg_replace('#\[I\](.+?)\[/I\]#si', '<i>$1</i>', $str);
	$str=preg_replace('#\[IMG\](.+?)\[/IMG\]#si', '<img src="$1">', $str);
	
	return $str;
}

function num_comments($nid)
{
	$result=mysql_query("SELECT COUNT(*) AS num FROM message WHERE type=\"comment\" AND nid=".$nid) or die(mysql_error());
	$row=mysql_fetch_array($result);
	
	return $row["num"];
}

function us_date($date) 
{ 
	return substr($date,4,2)."-".substr($date,6,2)."-".substr($date,0,4); 
} 

function us_time($date) 
{ 
	return substr($date,8,2).":".substr($date,10,2); 
} 
?>