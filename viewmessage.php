<?PHP 
$host="";
$dbuser="";
$dbpassword="";
$dbname="";
mysql_connect("$host","$dbuser","$dbpassword"); 
mysql_select_db("$dbname");

include("header.php");
include("functions.php");

$result=mysql_query("SELECT type, sid FROM message WHERE id=".$id) or die(mysql_error());
$row=mysql_fetch_array($result);

if($row["type"]=="pm" && (!isset($_COOKIE["loggedin"]) || $_COOKIE["username"]!=$row["sid"]))
{
	echo "<table class=\"header\" align=\"center\"><tr><td>You must <a class=\"small\" href=\"http://www.overthemonkey.com/login.php\">login</a> to view your private messages.  Click <a class=\"small\" href=\"http://www.overthemonkey.com/index.php\">here</a> to return to the main page.</td></tr></table>";
	die();
}

$result=mysql_query("SELECT date FROM message WHERE id=".$id) or die(mysql_error());
$row=mysql_fetch_array($result);
mysql_query("UPDATE message SET views=views+1, date=\"".$row["date"]."\" WHERE id=".$id) or die(mysql_error());

echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\" width=\"10%\">User</td><td class=\"header\" width=\"90%\">Comment</td></tr>\n";
echo "<tr><td colspan=2 bgcolor=\"".$colors["color_bg_td_1"]."\"></td></tr><tr><td class=\"small\">".(isset($_COOKIE["loggedin"])?"<a href=\"http://www.overthemonkey.com/addcomment.php?id=".$id."\">Add comment</a>":"")."</td><td class=\"small\" align=\"right\">";
$result=mysql_query("SELECT COUNT(*) AS num FROM message WHERE nid=".$id." OR id=".$id) or die(mysql_error());
$row=mysql_fetch_array($result);
if($row["num"]>20)
{
	echo "<a href=\"http://www.overthemonkey.com/viewmessage.php?id=".$id."&start=0\">1</a>";
	$count=2;
	$x=20;
	while($x<$row["num"])
	{
		echo ", <a href=\"http://www.overthemonkey.com/viewmessage.php?id=".$id."&start=".$x."\">".$count."</a>";
		$count++;
		$x+=20;
	}
}
echo "</td></tr>";
$color="\"".$colors["color_bg_td_1"]."\"";

$result=mysql_query("SELECT message.title, message.info, message.date, message.u_name, message.file, message.type, user.avatar, user.sig FROM message,user WHERE (message.nid=".$id." OR message.id=".$id.") AND user.name=message.u_name ORDER BY message.date LIMIT ".(isset($start)?$start:"0").", 20") or die(mysql_error());	
while($row=mysql_fetch_array($result))
{
	echo "<tr><td bgcolor=".$color." width=\"10%\" height=\"100%\">";
	echo "<table bgcolor=".$color." width=\"100%\" height=\"100%\" border=\"0\">";
	echo "<tr><td valign=\"top\" align=\"right\"><a href=\"http://www.overthemonkey.com/".$row["u_name"]."\">".$row["u_name"]."</a></td></tr>";
	if($row["avatar"]!="")
	{
		echo "<tr><td valign=\"top\" align=\"right\" height=\"100%\"><img src=\"".$row["avatar"]."\"></td></tr>";
	}
	echo "<tr><td class=\"time\" valign=\"bottom\">".us_date($row["date"])."<BR>".us_time($row["date"])."</td></tr></table>";
	echo "</td><td bgcolor=".$color." width=\"90%\" height=\"100%\">";
	echo "<table bgcolor=".$color." width=\"100%\" height=\"100%\" border=\"0\">";
	echo ($row["title"]==""?"":"<tr><td class=\"small\" valign=\"top\"><b>".parse($row["title"])."</b></td></tr>\n");
	echo "<tr><td class=\"small\" valign=\"top\" height=\"100%\">".($row["file"]==""?"":"<img src=\"".$row["file"]."\"><br>").parse($row["info"])."</td></tr>".($row["sig"]==""?"":"<tr><td class=\"small\" valign=\"bottom\"><br><br><hr align=\"left\">".parse($row["sig"])."</td></tr>")."</table></td></tr>\n"; 
	$color=($color=="\"".$colors["color_bg_td_1"]."\""?"\"".$colors["color_bg_td_2"]."\"":"\"".$colors["color_bg_td_1"]."\"");
}
echo "<tr><td class=\"small\">".(isset($_COOKIE["loggedin"])?"<a href=\"http://www.overthemonkey.com/addcomment.php?id=".$id."\">Add comment</a>":"")."</td><td class=\"small\" align=\"right\">";
$result=mysql_query("SELECT COUNT(*) AS num FROM message WHERE nid=".$id." OR id=".$id) or die(mysql_error());
$row=mysql_fetch_array($result);
if($row["num"]>20)
{
	echo "<a href=\"http://www.overthemonkey.com/viewmessage.php?id=".$id."&start=0\">1</a>";
	$count=2;
	$x=20;
	while($x<$row["num"])
	{
		echo ", <a href=\"http://www.overthemonkey.com/viewmessage.php?id=".$id."&start=".$x."\">".$count."</a>";
		$count++;
		$x+=20;
	}
}
echo "</td></tr></table><br>\n";
echo "</body>\n</html>";
?>
