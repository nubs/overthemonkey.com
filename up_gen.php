<?PHP 
$host="";
$dbuser="";
$dbpassword="";
$dbname="";
mysql_connect("$host","$dbuser","$dbpassword"); 
mysql_select_db("$dbname");

function mkdirs($dirname)
{
	$dir=split("/", $dirname);

	for ($i=0;$i<count($dir);$i++)
	{
		$path.=$dir[$i]."/";

		if (!is_dir($path))
		{
			@mkdir($path,0777);
		}

		@chmod($path,0777);
	}

	if(is_dir($dirname))
	{
		return 1;
	}
} 

$result=mysql_query("SELECT id, name FROM user") or die(mysql_error());

while($row=mysql_fetch_array($result))
{
	if(!mkdirs($row["name"]))
	{
		echo "Couldn't make user directory for ".$row["name"];
		exit();
	}
	$file=fopen($row["name"]."/index.php","w");
	fwrite($file,"<?PHP\n"); 
	fwrite($file,"function make_clickable(\$text)\n");
	fwrite($file,"{\n");
	fwrite($file,"	\$ret = ' ' . \$text;\n");
	fwrite($file,"	\$ret = preg_replace(\"#(^|[\\n ])([\\w]+?://.*?[^ \\\"\\n\\r\\t<]*)#is\", \"\\\\1<a href=\\\"\\\\2\\\" target=\\\"_blank\\\">\\\\2</a>\", \$ret);\n");
	fwrite($file,"	\$ret = preg_replace(\"#(^|[\\n ])((www|ftp)\\.[\\w\\-]+\\.[\\w\\-.\\~]+(?:/[^ \\\"\\t\\n\\r<]*)?)#is\", \"\\\\1<a href=\\\"http://\\\\2\\\" target=\\\"_blank\\\">\\\\2</a>\", \$ret);\n");
	fwrite($file,"	\$ret = preg_replace(\"#(^|[\\n ])([a-z0-9&\\-_.]+?)@([\\w\\-]+\\.([\\w\\-\\.]+\\.)*[\\w]+)#i\", \"\\\\1<a href=\\\"mailto:\\\\2@\\\\3\\\">\\\\2@\\\\3</a>\", \$ret);\n");
	fwrite($file,"	\$ret = substr(\$ret, 1);\n");
	fwrite($file,"	return(\$ret);\n");
	fwrite($file,"}\n");
	fwrite($file,"function parse(\$str)\n");
	fwrite($file,"{\n");
	fwrite($file,"	\$str=nl2br(\$str);\n");
	fwrite($file,"	\$str=make_clickable(\$str);\n");
	fwrite($file,"	\$result=mysql_query(\"SELECT * FROM emoticon\") or die(mysql_error());\n");
	fwrite($file,"	while(\$row=mysql_fetch_array(\$result))\n");
	fwrite($file,"	{\n");
	fwrite($file,"		\$emotes[]=\$row[\"emote\"];\n");
	fwrite($file,"		\$images[]=\"<img src=\\\"http://www.overthemonkey.com/emoticons/\".\$row[\"image\"].\"\\\">\";\n");
	fwrite($file,"	}\n");
	fwrite($file,"	\$str=str_replace(\$emotes, \$images, \$str);\n");
	fwrite($file,"	\$str=preg_replace('#\\[B\\](.+?)\\[/B\\]#si', '<b>$1</b>', \$str);\n");
	fwrite($file,"	\$str=preg_replace('#\\[I\\](.+?)\\[/I\\]#si', '<i>$1</i>', \$str);\n");
	fwrite($file,"	\$str=preg_replace('#\\[IMG\\](.+?)\\[/IMG\\]#si', '<img src=\"$1\">', \$str);\n");
	fwrite($file,"	return \$str;\n");
	fwrite($file,"}\n");
	fwrite($file,"function num_comments(\$nid)\n");
	fwrite($file,"{\n");
	fwrite($file,"	\$result=mysql_query(\"SELECT COUNT(*) AS num FROM message WHERE type=\\\"comment\\\" AND nid=\".\$nid) or die(mysql_error());\n");
	fwrite($file,"	\$row=mysql_fetch_array(\$result);	\n");
	fwrite($file,"	return \$row[\"num\"];\n");
	fwrite($file,"}\n");
	fwrite($file,"function us_time(\$date)\n");
	fwrite($file,"{\n");
	fwrite($file,"	return substr(\$date,8,2).\":\".substr(\$date,10,2);\n"); 
	fwrite($file,"}\n");
	fwrite($file,"function us_date(\$date)\n");
	fwrite($file,"{\n");
	fwrite($file,"	return substr(\$date,4,2).\"-\".substr(\$date,6,2).\"-\".substr(\$date,0,4);\n");
	fwrite($file,"}\n");
	fwrite($file,"function num_topics(\$uid)\n");
	fwrite($file,"{\n");
	fwrite($file,"	\$result=mysql_query(\"SELECT COUNT(*) AS num FROM news WHERE uid=\".\$uid) or die(mysql_error());\n");
	fwrite($file,"	\$row=mysql_fetch_array(\$result);\n");
	fwrite($file,"	return \$row[\"num\"];\n");
	fwrite($file,"}\n");
	fwrite($file,"\$host=\"db42.perfora.net\";\n");
	fwrite($file,"\$dbuser=\"dbo89119892\";\n");
	fwrite($file,"\$dbpassword=\"Jx4ankWB\";\n");
	fwrite($file,"\$dbname=\"db89119892\";\n");
	fwrite($file,"mysql_connect(\"\$host\",\"\$dbuser\",\"\$dbpassword\");\n");
	fwrite($file,"mysql_select_db(\"\$dbname\");\n");
	fwrite($file,"if(isset(\$_COOKIE[\"loggedin\"]))\n");
	fwrite($file,"{\n");
	fwrite($file,"	\$result=mysql_query(\"SELECT tid FROM user WHERE name=\\\"\".\$_COOKIE[\"username\"].\"\\\"\") or die(mysql_error());\n");
	fwrite($file,"	\$row=mysql_fetch_array(\$result);\n");
	fwrite($file,"	\$result=mysql_query(\"SELECT * FROM theme WHERE id=\".\$row[\"tid\"]) or die(mysql_error());\n");
	fwrite($file,"	\$colors=mysql_fetch_array(\$result);\n");
	fwrite($file,"}\n");
	fwrite($file,"else\n");
	fwrite($file,"{\n");
	fwrite($file,"	\$result=mysql_query(\"SELECT * FROM theme WHERE id=1\") or die(mysql_error());\n");
	fwrite($file,"	\$colors=mysql_fetch_array(\$result);\n");
	fwrite($file,"}\n");
	fwrite($file,"echo \"<html>\\n<head>\\n\";\n");
	fwrite($file,"echo \"<style type=\\\"text/css\\\">\\na {font-family: verdana; font-weight: bold; outline-style: none; color: \".\$colors[\"color_fg_ulink\"].\"; text-decoration: none;}\\na.small {font-size: 70%;}\\na.header {color: \".\$colors[\"color_fg_th\"].\"}\\na.header:hover {color: \".\$colors[\"color_fg_th\"].\"}\\na:hover {color: \".\$colors[\"color_fg_alink\"].\"}\\nimg {border: 0px}\\ntd {color: \".\$colors[\"color_fg_td_1\"].\"}\\ntd.small {font-family: verdana; font-size: 70%;}\\ntd.header {font-family: verdana; font-size: 60%; font-weight: bold; text-align: center; color: \".\$colors[\"color_fg_th\"].\"}\\ntd.time {font-family:verdana; font-size: 60%; text-align: right;}\\ntable.header {background-color: \".\$colors[\"color_bg_table\"].\"; width: 80%;}\\n</style>\\n\";\n");
	fwrite($file,"echo \"<title>Over The Monkey</title>\\n</head>\\n<body bgcolor=\\\"\".\$colors[\"color_bg_body\"].\"\\\">\\n\";\n");
	fwrite($file,"echo \"<div align=\\\"center\\\"><img src=\\\"\".\$colors[\"image_banner\"].\"\\\"></div>\\n\";\n");
	fwrite($file,"echo \"<table class=\\\"header\\\" align=\\\"center\\\"><tr><td class=\\\"header\\\"><a href=\\\"http://www.overthemonkey.com/index.php\\\">Home</a> || <a href=\\\"http://www.overthemonkey.com/browse.php\\\">Browse</a></td></tr>\\n\";\n");
	fwrite($file,"if(isset(\$_COOKIE[\"loggedin\"]))\n");
	fwrite($file,"{\n");
	fwrite($file,	"echo \"<tr><td class=\\\"header\\\"><a href=\\\"http://www.overthemonkey.com/newtopic.php\\\">New Topic</a> || <a href=\\\"http://www.overthemonkey.com/newphoto.php\\\">New Photo</a> || <a href=\\\"http://www.overthemonkey.com/usercp.php\\\">Control Panel</a> || <a href=\\\"http://www.overthemonkey.com/logout.php\\\">Logout</a></td></tr></table>\\n\";\n");
	fwrite($file,"}\n");
	fwrite($file,"else\n");
	fwrite($file,"{\n");
	fwrite($file,"	echo \"<tr><td class=\\\"header\\\"><a href=\\\"http://www.overthemonkey.com/login.php\\\">Login</a> || <a href=\\\"http://www.overthemonkey.com/register.php\\\">Register</a></td></tr></table>\\n\";\n");
	fwrite($file,"}\n");
	fwrite($file,"echo \"<br>\";");
	fwrite($file,"echo \"<table width=\\\"95%\\\" align=\\\"center\\\"><tr><td width=\\\"50%\\\" valign=\\\"top\\\">\";\n");
	fwrite($file,"echo \"<table bgcolor=\\\"\".\$colors[\"color_bg_table\"].\"\\\" width=\\\"100%\\\"><tr><td class=\\\"header\\\">Photo Gallery</td></tr><tr><td bgcolor=\\\"\".\$colors[\"color_bg_td_1\"].\"\\\"></td></tr><tr><td>\\n\";\n");
	fwrite($file,"\$result=mysql_query(\"SELECT COUNT(*) AS num FROM message WHERE type=\\\"photo\\\" AND u_name=\\\"".$row["name"]."\\\"\") or die(mysql_error());\n");	
	fwrite($file,"\$row=mysql_fetch_array(\$result);\n");
	fwrite($file,"if(\$row[\"num\"]>0)\n");
	fwrite($file,"{\n");
	fwrite($file,"	echo \"<table width=\\\"100%\\\">\\n<tr><td class=\\\"header\\\" width=\\\"55%\\\"><a class=\\\"header\\\" href=\\\"http://www.overthemonkey.com/".$row["name"]."/index.php?sort=title&order=\".(isset(\$order)?(\$order==\"asc\"?\"desc\":\"asc\"):\"desc\").\"\\\">Title</a></td><td class=\\\"header\\\" width=\\\"10%\\\">Views</td><td class=\\\"header\\\" width=\\\"35%\\\"><a class=\\\"header\\\" href=\\\"http://www.overthemonkey.com/".$row["name"]."/index.php?sort=date&order=\".(isset(\$order)?(\$order==\"asc\"?\"desc\":\"asc\"):\"desc\").\"\\\">Added on</a></td></tr>\\n\";\n");
	fwrite($file,"	\$result=mysql_query(\"SELECT id, title, date, views FROM message WHERE type=\\\"photo\\\" AND u_name=\\\"".$row["name"]."\\\" ORDER BY \".(isset(\$sort)?(\$sort==\"date\"?\"date\":\"title\"):\"date\").\" \".(isset(\$order)?\$order:\"DESC\")) or die(mysql_error());\n");
	fwrite($file,"	\$color=\"\\\"\".\$colors[\"color_bg_td_1\"].\"\\\"\";\n");
	fwrite($file,"	while(\$row=mysql_fetch_array(\$result))\n");
	fwrite($file,"	{\n");
	fwrite($file,"		echo \"<tr><td bgcolor=\".\$color.\" width=\\\"55%\\\" onMouseOver=this.style.backgroundColor=\\\"\".\$colors[\"color_bg_th\"].\"\\\" onMouseOut=this.style.backgroundColor=\\\"\\\" onclick=\\\"window.location.href='http://www.overthemonkey.com/viewmessage.php?id=\".\$row[\"id\"].\"'\\\"><a class=\\\"small\\\" href=\\\"http://www.overthemonkey.com/viewmessage.php?id=\".\$row[\"id\"].\"\\\">\".parse(\$row[\"title\"]).\"</a></td><td class=\\\"small\\\" bgcolor=\".\$color.\" width=\\\"10%\\\" align=\\\"center\\\">\".(\$row[\"views\"]>0?\$row[\"views\"]:\"\").\"</td><td class=\\\"time\\\" bgcolor=\".\$color.\" width=\\\"35%\\\">\".us_date(\$row[\"date\"]).\" \".us_time(\$row[\"date\"]).\"</td></tr>\\n\";\n");
	fwrite($file,"		\$color=(\$color==\"\\\"\".\$colors[\"color_bg_td_1\"].\"\\\"\"?\"\\\"\".\$colors[\"color_bg_td_2\"].\"\\\"\":\"\\\"\".\$colors[\"color_bg_td_1\"].\"\\\"\");\n");
	fwrite($file,"	}\n");	
	fwrite($file,"	echo \"</table>\\n\";\n");
	fwrite($file,"}\n");
	fwrite($file,"else\n");
	fwrite($file,"{\n");
	fwrite($file,"	echo \"<table width=\\\"100%\\\"><tr><td class=\\\"header\\\" width=\\\"100%\\\">-no entries-</td></tr></table>\\n\";\n");
	fwrite($file,"}\n");
	fwrite($file,"\$result=mysql_query(\"SELECT email, aim, age, home, movie, music FROM user WHERE id=".$row["id"]."\") or die(mysql_error());\n");
	fwrite($file,"\$row=mysql_fetch_array(\$result);\n");
	fwrite($file,"echo \"</td></tr></table></td><td width=\\\"50%\\\" valign=\\\"top\\\"><table bgcolor=\\\"\".\$colors[\"color_bg_table\"].\"\\\" width=\\\"100%\\\"><tr><td class=\\\"header\\\" colspan=\\\"2\\\">User Profile</td></tr><tr><td class=\\\"small\\\" width=\\\"30%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_1\"].\"\\\">Email:</td><td class=\\\"small\\\" width=\\\"70%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_1\"].\"\\\">\".\$row[\"email\"].\"</td></tr><tr><td class=\\\"small\\\" width=\\\"30%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_2\"].\"\\\">AIM Name:</td><td class=\\\"small\\\" width=\\\"70%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_2\"].\"\\\">\".\$row[\"aim\"].\"</td></tr><tr><td class=\\\"small\\\" width=\\\"30%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_1\"].\"\\\">Age:</td><td class=\\\"small\\\" width=\\\"70%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_1\"].\"\\\">\".\$row[\"age\"].\"</td></tr><tr><td class=\\\"small\\\" width=\\\"30%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_2\"].\"\\\">Location:</td><td class=\\\"small\\\" width=\\\"70%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_2\"].\"\\\">\".\$row[\"home\"].\"</td></tr><tr><td class=\\\"small\\\" width=\\\"30%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_1\"].\"\\\">Favorite Movie:</td><td class=\\\"small\\\" width=\\\"70%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_1\"].\"\\\">\".\$row[\"movie\"].\"</td></tr><tr><td class=\\\"small\\\" width=\\\"30%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_2\"].\"\\\">Favorite Band:</td><td class=\\\"small\\\" width=\\\"70%\\\" bgcolor=\\\"\".\$colors[\"color_bg_td_2\"].\"\\\">\".\$row[\"music\"].\"</td></tr></table></td></tr></table><br>\\n\";\n");
	fwrite($file,"echo \"<table class=\\\"header\\\" align=\\\"center\\\"><tr><td class=\\\"header\\\">Journal</td></tr><tr><td bgcolor=\\\"\".\$colors[\"color_bg_td_1\"].\"\\\"></td></tr><tr><td>\\n\";\n");
	fwrite($file,"\$result=mysql_query(\"SELECT COUNT(*) AS num FROM message WHERE type=\\\"news\\\" AND u_name=\\\"".$row["name"]."\\\"\") or die(mysql_error());\n");	
	fwrite($file,"\$row=mysql_fetch_array(\$result);\n");
	fwrite($file,"if(\$row[\"num\"]>0)\n");
	fwrite($file,"{\n");
	fwrite($file,"	echo \"<table width=\\\"100%\\\">\\n<tr><td class=\\\"header\\\" width=\\\"55%\\\"><a class=\\\"header\\\" href=\\\"http://www.overthemonkey.com/".$row["name"]."/index.php?sort=title&order=\".(isset(\$order)?(\$order==\"asc\"?\"desc\":\"asc\"):\"desc\").\"\\\">Title</a></td><td class=\\\"header\\\" width=\\\"10%\\\">Comments</td><td class=\\\"header\\\" width=\\\"10%\\\">Views</td><td class=\\\"header\\\" width=\\\"25%\\\"><a class=\\\"header\\\" href=\\\"http://www.overthemonkey.com/".$row["name"]."/index.php?sort=date&order=\".(isset(\$order)?(\$order==\"asc\"?\"desc\":\"asc\"):\"desc\").\"\\\">Last Comment</a></td></tr>\\n\";\n");
	fwrite($file,"	\$result=mysql_query(\"SELECT id, title, lc_time, lc_user, views FROM message WHERE type=\\\"news\\\" AND u_name=\\\"".$row["name"]."\\\" ORDER BY \".(isset(\$sort)?(\$sort==\"date\"?\"lc_time\":\"title\"):\"lc_time\").\" \".(isset(\$order)?\$order:\"DESC\")) or die(mysql_error());\n");
	fwrite($file,"	\$color=\"\\\"\".\$colors[\"color_bg_td_1\"].\"\\\"\";\n");
	fwrite($file,"	while(\$row=mysql_fetch_array(\$result))\n");
	fwrite($file,"	{\n");
	fwrite($file,"	echo \"<tr><td bgcolor=\".\$color.\" width=\\\"65%\\\" onMouseOver=this.style.backgroundColor=\\\"\".\$colors[\"color_bg_th\"].\"\\\" onMouseOut=this.style.backgroundColor=\\\"\\\" onclick=\\\"window.location.href='http://www.overthemonkey.com/viewmessage.php?id=\".\$row[\"id\"].\"'\\\"><a class=\\\"small\\\" href=\\\"http://www.overthemonkey.com/viewmessage.php?id=\".\$row[\"id\"].\"\\\">\".parse(\$row[\"title\"]).\"</a></td><td bgcolor=\".\$color.\" width=\\\"10%\\\" align=\\\"center\\\" class=\\\"small\\\">\".(num_comments(\$row[\"id\"])>0?num_comments(\$row[\"id\"]):\"\").\"</td><td bgcolor=\".\$color.\" class=\\\"small\\\" width=\\\"10%\\\" align=\\\"center\\\">\".(\$row[\"views\"]>0?\$row[\"views\"]:\"\").\"<td bgcolor=\".\$color.\" width=\\\"25%\\\"><table bgcolor=\".\$color.\" width=\\\"100%\\\"><tr><td class=\\\"time\\\" bgcolor=\".\$color.\" width=\\\"100%\\\">\".us_date(\$row[\"lc_time\"]).\" \".us_time(\$row[\"lc_time\"]).\"</td></tr><tr><td bgcolor=\".\$color.\" width=\\\"100%\\\" align=\\\"right\\\"><a class=\\\"small\\\" href=\\\"http://www.overthemonkey.com/\".\$row[\"lc_user\"].\"\\\">\".\$row[\"lc_user\"].\"</a></td></tr></table></td></tr>\\n\";\n");
	fwrite($file,"		\$color=(\$color==\"\\\"\".\$colors[\"color_bg_td_1\"].\"\\\"\"?\"\\\"\".\$colors[\"color_bg_td_2\"].\"\\\"\":\"\\\"\".\$colors[\"color_bg_td_1\"].\"\\\"\");\n");
	fwrite($file,"	}\n");
	fwrite($file,"	echo \"</table>\\n\";\n");
	fwrite($file,"}\n");
	fwrite($file,"else\n");
	fwrite($file,"{\n");
	fwrite($file,"	echo \"<table width=\\\"100%\\\"><tr><td class=\\\"header\\\" width=\\\"100%\\\">-no entries-</td></tr></table>\\n\";\n");
	fwrite($file,"}\n");
	fwrite($file,"echo \"</td></tr></table>\\n\";\n");
	fwrite($file,"echo \"</body>\\n</html>\";\n");
	fwrite($file,"?>");
	fclose($file);
	
}
?>
