<?PHP
include ("functions.php");
setup_mysql();

if(!isset($_COOKIE["loggedin"]))
{
	print_header();
	
	echo "<table>\n";
	echo "<tr><th class=\"backgray0\">Logout</th></tr>\n";
	echo "<tr><td class=\"backgray1\">You are already logged out.</td></tr>\n";
	echo "</table>\n";
	
	die();
}

setcookie("loggedin", FALSE, time()-600);

print_header();

echo "<table>\n";
echo "<tr><th class=\"backgray0\">Logout</th></tr>\n";
echo "<tr><td class=\"backgray1\">You have been successfully logged out.  Click <a href=\"/\">here</a> to return to the main page.</td></tr>\n";
echo "</table>\n";

echo "</body>\n";
echo "</html>";
?>