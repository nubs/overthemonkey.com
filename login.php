<?PHP
include("functions.php");
setup_mysql();

if(isset($_COOKIE["loggedin"]))
{	
	print_header();
	
	echo "<table>\n";
	echo "<tr><th class=\"backgray0\">Login</th></tr>\n";
	echo "<tr><td class=\"backgray1\">You are already logged in.</td></tr>\n";
	echo "</table>\n";
	
	die();
}

if(!isset($submit))
{
	print_header();
	
	echo "<form name=\"form1\" action=\"login.php\" method=\"post\">\n";
	echo "<table>\n";
	echo "<tr><th class=\"backgray0\" colspan=\"2\">Login</th></tr>\n";
	echo "<tr><td class=\"backgray1\">Username</td><td class=\"backgray2\"><input type=\"text\" name=\"username\" size=\"32\" maxlength=\"32\"></td></tr>\n";
	echo "<tr><td class=\"backgray2\">Password</td><td class=\"backgray2\"><input type=\"password\" name=\"password\" size=\"32\" maxlength=\"32\"></td></tr>\n";
	echo "<tr><td class=\"backgray1\">Remember Me</td><td class=\"backgray1\"><input type=\"checkbox\" name=\"remember\" value=\"1\"></td></tr>\n";
	echo "<tr><th class=\"backgray0\" style=\"text-align: right\" colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></th></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}
else
{
	$user_result=mysql_query("SELECT id, status FROM user WHERE name=\"".$username."\" AND pass=\"".md5($password)."\"") or die(mysql_error());
	if($user_row=mysql_fetch_array($user_result))
	{
		if($user_row["status"]=="banned")
		{
			print_header();
			
			echo "<table>\n";
			echo "<tr><th class=\"backgray0\">Login</th></tr>\n";
			echo "<tr><td class=\"backgray1\">You have been banned.</td></tr>\n";
			echo "</table>\n";
		}
		else
		{
			setcookie("loggedin", $user_row["id"], (isset($remember)?mktime(0,0,0,1,1,2038):0));
			
			print_header();
			
			echo "<table>\n";
			echo "<tr><th class=\"backgray0\">Login</th></tr>\n";
			echo "<tr><td class=\"backgray1\">You have been successfully logged in.  Click <a href=\"viewuser.php?id=".$user_row["id"]."\">here</a> to view your profile.</td></tr>\n";
			echo "</table>\n";
		}
	}
	else
	{
		print_header();
		
		echo "<table>\n";
		echo "<tr><th class=\"backred0\">Login</th></tr>\n";
		echo "<tr><td class=\"backred1\">Username and password do not match.</td></tr>\n";
		echo "</table><br>\n";
		
		echo "<form name=\"form1\" action=\"login.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "<tr><th class=\"backgray0\" colspan=\"2\">Login</th></tr>\n";
		echo "<tr><td class=\"backgray1\">Username</td><td class=\"backgray2\"><input type=\"text\" name=\"username\" size=\"32\" maxlength=\"32\"></td></tr>\n";
		echo "<tr><td class=\"backgray2\">Password</td><td class=\"backgray2\"><input type=\"password\" name=\"password\" size=\"32\" maxlength=\"32\"></td></tr>\n";
		echo "<tr><td class=\"backgray1\">Remember Me</td><td class=\"backgray1\"><input type=\"checkbox\" name=\"remember\" value=\"1\"></td></tr>\n";
		echo "<tr><th class=\"backgray0\" style=\"text-align: right\" colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></th></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
}

echo "</body>\n";
echo "</html>";
?>