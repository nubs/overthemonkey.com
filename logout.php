<?PHP
setcookie("loggedin", FALSE, time()-600);
setcookie("username","", time()-600);

include("header.php");

echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Logout</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">Click <a class=\"small\" href=\"http://www.overthemonkey.com/index.php\">here</a> to return.</td></tr></table>\n";
echo "</BODY>\n";
echo "</HTML>\n";
?>