<?PHP 
$host="";
$dbuser="";
$dbpassword="";
$dbname="";
mysql_connect("$host","$dbuser","$dbpassword"); 
mysql_select_db("$dbname");

if(isset($_POST["username"]))
{
	$result=mysql_query("SELECT pass FROM user WHERE name=\"".addslashes($_POST["username"])."\" AND pass=\"".md5($_POST["password"])."\"") or die(mysql_error());
	if(mysql_num_rows($result)>0)
	{
		setcookie("loggedin", TRUE, time()+1800);
		setcookie("username",$_POST["username"], time()+1800);
		
		include("header.php");
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Login</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">Welcome ".$_POST["username"].".  Click <a class=\"small\" href=\"index.php\">here</a> to return.</td></tr></table>";
	}
	else
	{
		include("header.php");
		echo "<form action=\"login.php\" method=\"post\">\n";
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Login</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">Incorrect username or password.</td></tr>\n";
		echo "<tr><td><table width=\"100%\"><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\" width=\"30%\">Username: </td><td bgcolor=\"".$colors["color_bg_td_1"]."\" width=\"70%\" align=\"right\"><input type=\"text\" name=\"username\" size=\"50\" maxlength=\"15\"></td></tr>\n";
		echo "<tr><td bgcolor=\"".$colors["color_bg_td_1"]."\" width=\"30%\">Password: </td><td bgcolor=\"".$colors["color_bg_td_1"]."\" width=\"70%\" align=\"right\"><input type=\"password\" name=\"password\" size=\"50\" maxlength=\"15\"></td></tr></table></td></tr>\n";
		echo "<tr><td colspan=\"2\" align=\"right\"><input type=\"submit\" value=\"submit\" name=\"submit\"></td></tr></table></form>\n";
	}
}
else
{
	include("header.php");
	
	echo "<form action=\"login.php\" method=\"post\">\n";
	echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Login</td></tr>\n";
	echo "<tr><td><table width=\"100%\"><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\" width=\"30%\">Username: </td><td bgcolor=\"".$colors["color_bg_td_1"]."\" width=\"70%\" align=\"right\"><input type=\"text\" name=\"username\" size=\"50\" maxlength=\"15\"></td></tr>\n";
	echo "<tr><td bgcolor=\"".$colors["color_bg_td_1"]."\" width=\"30%\">Password: </td><td bgcolor=\"".$colors["color_bg_td_1"]."\" width=\"70%\" align=\"right\"><input type=\"password\" name=\"password\" size=\"50\" maxlength=\"15\"></td></tr></table></td></tr>\n";
	echo "<tr><td colspan=\"2\" align=\"right\"><input type=\"submit\" value=\"submit\" name=\"submit\"></td></tr></table></form>\n";
}
echo "</BODY>\n";
echo "</HTML>";
?>
