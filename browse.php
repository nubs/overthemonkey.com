<?PHP 
$host="";
$dbuser="";
$dbpassword="";
$dbname="";
mysql_connect("$host","$dbuser","$dbpassword"); 
mysql_select_db("$dbname");

include("header.php");
include("functions.php");

echo "<table class=\"header\" align=\"center\"><tr><td align=\"center\"".(!isset($type)||$type=="user"?" class=\"header\">Users":" bgcolor=\"".$colors["color_bg_td_1"]."\"><a class=\"small\" href=\"http://www.overthemonkey.com/browse.php?type=user\">Users</a>")."</td><td align=\"center\"".($type=="photo"?" class=\"header\">Photos":" bgcolor=\"".$colors["color_bg_td_1"]."\"><a class=\"small\" href=\"http://www.overthemonkey.com/browse.php?type=photo\">Photos</a>")."</td><td align=\"center\"".($type=="topic"?" class=\"header\">Topics":" bgcolor=\"".$colors["color_bg_td_1"]."\"><a class=\"small\" href=\"http://www.overthemonkey.com/browse.php?type=topic\">Topics</a>")."</td></tr><tr><td colspan=\"3\">\n";

$color="\"".$colors["color_bg_td_1"]."\"";

if(!isset($type)||$type=="user")
{
	echo "<table width=\"100%\">\n<tr><td class=\"header\" width=\"40%\"><a class=\"header\" href=\"http://www.overthemonkey.com/browse.php?type=user&sort=name&order=".(isset($order)?($order=="asc"?"desc":"asc"):"desc")."\">Username</a></td><td class=\"header\" width=\"40%\"><a class=\"header\" href=\"http://www.overthemonkey.com/browse.php?type=user&sort=email&order=".(isset($order)?($order=="asc"?"desc":"asc"):"desc")."\">Email</a></td><td class=\"header\" width=\"20%\"><a class=\"header\" href=\"http://www.overthemonkey.com/browse.php?type=user&sort=postcount&order=".(isset($order)?($order=="asc"?"desc":"asc"):"desc")."\">Postcount</a></td></tr>\n";

	$result=mysql_query("SELECT id, name, postcount, email FROM user ORDER BY ".(isset($sort)?$sort:"name")." ".(isset($order)?$order:"")) or die(mysql_error());
	while($row=mysql_fetch_array($result))
	{
		$color=($color=="\"".$colors["color_bg_td_1"]."\""?"\"".$colors["color_bg_td_2"]."\"":"\"".$colors["color_bg_td_1"]."\"");
		echo "<tr><td width=\"40%\" bgcolor=".$color." onMouseOver=this.style.backgroundColor=\"".$colors["color_bg_th"]."\" onMouseOut=this.style.backgroundColor=\"\" onclick=\"window.location.href='http://www.overthemonkey.com/".$row["name"]."'\"><a class=\"small\" href=\"http://www.overthemonkey.com/".$row["name"]."\">".$row["name"]."</a></td><td width=\"40%\" bgcolor=".$color.">".($row["email"]==NULL?"":"<a class=\"small\" href=\"mailto:".$row["email"]."\">").$row["email"]."</a></td><td width=\"20%\" bgcolor=".$color." class=\"small\">".($row["postcount"]==0?"":$row["postcount"])."</td></tr>\n";
	}
}
else
{
	echo "<table width=\"100%\">\n<tr><td class=\"header\" width=\"55%\"><a class=\"header\" href=\"http://www.overthemonkey.com/browse.php?type=".$type."&sort=title&order=".(isset($order)?($order=="asc"?"desc":"asc"):"desc")."\">Title</a></td><td class=\"header\" width=\"10%\">Comments</td><td class=\"header\" width=\"10%\">Views</td><td class=\"header\" width=\"25%\"><a class=\"header\" href=\"http://www.overthemonkey.com/browse.php?type=".$type."&sort=date&order=".(isset($order)?($order=="asc"?"desc":"asc"):"desc")."\">Last Comment</a></td></tr>\n";

	$result=mysql_query("SELECT id, title, lc_time, lc_user, views FROM message WHERE type=\"".($type=="photo"?"photo":"news")."\" ORDER BY ".(isset($sort)?($sort=="date"?"lc_time":"title"):"lc_time")." ".(isset($order)?$order:"DESC")) or die(mysql_error());
	while($row=mysql_fetch_array($result))
	{
		echo "<tr><td bgcolor=".$color." width=\"55%\" onMouseOver=this.style.backgroundColor=\"".$colors["color_bg_th"]."\" onMouseOut=this.style.backgroundColor=\"\" onclick=\"window.location.href='http://www.overthemonkey.com/viewmessage.php?id=".$row["id"]."'\"><a class=\"small\" href=\"http://www.overthemonkey.com/viewmessage.php?id=".$row["id"]."\">".parse($row["title"])."</a></td><td bgcolor=".$color." width=\"10%\" align=\"center\" class=\"small\">".(num_comments($row["id"])>0?num_comments($row["id"]):"")."</td><td bgcolor=".$color." width=\"10%\" align=\"center\" class=\"small\">".($row["views"]>0?$row["views"]:"")."</td><td bgcolor=".$color." width=\"25%\"><table bgcolor=".$color." width=\"100%\"><tr><td class=\"time\" bgcolor=".$color." width=\"100%\">".us_date($row["lc_time"])." ".us_time($row["lc_time"])."</td></tr><tr><td bgcolor=".$color." width=\"100%\" align=\"right\"><a class=\"small\" href=\"http://www.overthemonkey.com/".$row["lc_user"]."\">".$row["lc_user"]."</a></td></tr></table></td></tr>\n";
		$color=($color=="\"".$colors["color_bg_td_1"]."\""?"\"".$colors["color_bg_td_2"]."\"":"\"".$colors["color_bg_td_1"]."\"");
	}	
	echo "</table>";
}
echo "</td></tr></table>\n";
echo "</body>\n</html>";
?>
