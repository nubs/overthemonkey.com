<?PHP
$host="";
$dbuser="";
$dbpassword="";
$dbname="";
mysql_connect("$host","$dbuser","$dbpassword"); 
mysql_select_db("$dbname");
if(isset($_COOKIE["loggedin"]))
{
	$result=mysql_query("SELECT tid FROM user WHERE name=\"".$_COOKIE["username"]."\"") or die(mysql_error());
	$row=mysql_fetch_array($result);
	
	$result=mysql_query("SELECT * FROM theme WHERE id=".$row["tid"]) or die(mysql_error());
	$colors=mysql_fetch_array($result);
}
else
{
	$result=mysql_query("SELECT * FROM theme WHERE id=1") or die(mysql_error());
	$colors=mysql_fetch_array($result);
}
echo "<html>\n<head>\n";
echo "<style type=\"text/css\">\na {font-family: verdana; font-weight: bold; outline-style: none; color: ".$colors["color_fg_ulink"]."; text-decoration: none;}\na.small {font-size: 70%;}\na.header {color: ".$colors["color_fg_th"]."}\na.header:hover {color: ".$colors["color_fg_th"]."}\na:hover {color: ".$colors["color_fg_alink"]."}\nimg {border: 0px}\ntd {color: ".$colors["color_fg_td_1"]."}\ntd.small {font-family: verdana; font-size: 70%;}\ntd.header {background-color: ".$colors["color_bg_table"]."; font-family: verdana; font-size: 60%; font-weight: bold; text-align: center; color: ".$colors["color_fg_th"]."}\ntd.time {font-family:verdana; font-size: 60%; text-align: right;}\ntable.header {background-color: ".$colors["color_bg_table"]."; width: 80%;}\ninput {background-color: ".$colors["color_bg_input"]."; color: ".$colors["color_fg_input"]."; font-family: verdana};\ntextarea {background-color: ".$colors["color_bg_input"]."; color: ".$colors["color_fg_input"]."; font-family: verdana};\nselect {background-color: ".$colors["color_bg_input"]."; color: ".$colors["color_fg_input"]."; font-family: verdana};\nhr {width: 50%; align: left;}\n</style>\n";
echo "<title>Over The Monkey</title>\n</head>\n<body bgcolor=\"".$colors["color_bg_body"]."\">\n";
echo "<div align=\"center\"><img src=\"".$colors["image_banner"]."\"></div>\n";

echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\"><a href=\"http://www.overthemonkey.com/index.php\">Home</a> || <a href=\"http://www.overthemonkey.com/browse.php\">Browse</a></td></tr>\n";
if(isset($_COOKIE["loggedin"]))
{
	setcookie("loggedin", TRUE, time()+1800);
	setcookie("username",$_COOKIE["username"], time()+1800);
	echo "<tr><td class=\"header\"><a href=\"http://www.overthemonkey.com/newtopic.php\">New Topic</a> || <a href=\"http://www.overthemonkey.com/newphoto.php\">New Photo</a> || <a href=\"http://www.overthemonkey.com/usercp.php\">Control Panel</a> || <a href=\"http://www.overthemonkey.com/logout.php\">Logout</a></td></tr></table>\n";
}
else
{
	echo "<tr><td class=\"header\"><a href=\"http://www.overthemonkey.com/login.php\">Login</a> || <a href=\"http://www.overthemonkey.com/register.php\">Register</a></td></tr></table>\n";
}
echo "<br>\n";
?>
