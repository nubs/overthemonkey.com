<?PHP
include("functions.php");
setup_mysql();
print_header();

if(isset($_COOKIE["loggedin"]))
{
	echo "<table>\n";
	echo "<tr><th class=\"backgray0\">Register</th></tr>\n";
	echo "<tr><td class=\"backgray1\">You are already registered and logged in.</td></tr>\n";
	echo "</table>\n";
	
	die();
}

if(isset($submit))
{
	$user_result=mysql_query("SELECT * FROM user WHERE name=\"".$username."\"") or die(mysql_error());
	if(mysql_num_rows($user_result)>0)
	{
		echo "<table>\n";
		echo "<tr><th class=\"backred0\">Register</th></tr>\n";
		echo "<tr><td class=\"backred1\">That username is already taken.</td></tr>\n";
		echo "</table>\n";
		
		echo "<form name=\"form1\" action=\"register.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "<tr><th class=\"backgray0\" colspan=\"2\">Register</th></tr>\n";
		echo "<tr><td class=\"backgray1\">Desired Username</td><td class=\"backgray1\"><input type=\"text\" name=\"username\" size=\"32\" maxlength=\"32\"></td></tr>\n";
		echo "<tr><td class=\"backgray2\">Password</td><td class=\"backgray2\"><input type=\"password\" name=\"password\" size=\"32\" maxlength=\"32\"></td></tr>\n";
		echo "<tr><th class=\"backgray0\" colspan=\"2\" style=\"text-align: right;\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		
		die();
	}
	
	mysql_query("INSERT INTO user (name, pass, date) VALUES(\"".$username."\", \"".md5($password)."\", NOW())") or die(mysql_error());

	echo "<table>\n";
	echo "<tr><th class=\"backgray0\">Register</th></tr>\n";
	echo "<tr><td class=\"backgray1\">Your username has been successfully registered.  Click <a href=\"login.php\">here</a> to go to the login page.</td></tr>\n";
	echo "</table>\n";
}
else
{
	echo "<form name=\"form1\" action=\"register.php\" method=\"post\">\n";
	echo "<table>\n";
	echo "<tr><th class=\"backgray0\" colspan=\"2\">Register</th></tr>\n";
	echo "<tr><td class=\"backgray1\">Desired Username</td><td class=\"backgray1\"><input type=\"text\" name=\"username\" size=\"32\" maxlength=\"32\"></td></tr>\n";
	echo "<tr><td class=\"backgray2\">Password</td><td class=\"backgray2\"><input type=\"password\" name=\"password\" size=\"32\" maxlength=\"32\"></td></tr>\n";
	echo "<tr><th class=\"backgray0\" colspan=\"2\" style=\"text-align: right;\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

echo "</body>\n";
echo "</html>";
?>