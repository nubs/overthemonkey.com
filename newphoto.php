<?PHP
$host="";
$dbuser="";
$dbpassword="";
$dbname="";
mysql_connect("$host","$dbuser","$dbpassword"); 
mysql_select_db("$dbname");

include("header.php");

if(isset($_COOKIE["loggedin"]))
{
	if(!isset($_POST["submit"]))
	{
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\" colspan=\"11\">Emoticons</td></tr>\n";
		$i=1;
		
		$result=mysql_query("SELECT * FROM emoticon") or die(mysql_error());
		while($row=mysql_fetch_array($result))
		{
			echo (($i-1)%11==0?"<tr>":"")."<td class=\"small\" width=\"9%\" bgcolor=\"".$colors["color_bg_td_1"]."\">".$row["emote"]."</td>".($i%11==0?"</tr>":"");
			$i=$i+1;
		}
		
		echo "</table><br><table class=\"header\" align=\"center\"><tr><td class=\"header\" colspan=\"3\">Formatting Tags</td></tr>\n";
		echo "<tr><td class=\"small\" width=\"33%\" bgcolor=\"".$colors["color_bg_td_1"]."\">[b]bolded text[/b]</td><td class=\"small\" width=\"33%\" bgcolor=\"".$colors["color_bg_td_1"]."\">[i]italicized text[/i]</td><td class=\"small\" width=\"33%\" bgcolor=\"".$colors["color_bg_td_1"]."\">[img]url[/img]</td></tr>\n</table>\n";
		
		echo "<form name=\"form1\" enctype=\"multipart/form-data\" method=\"post\" action=\"http://www.overthemonkey.com/newphoto.php\">\n";
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\" colspan=\"2\">New Photo</td></tr><tr><td><table width=\"100%\"><tr><td class=\"small\" bgcolor=\"".$colors["color_bg_td_1"]."\">Title:</td><td align=\"right\" bgcolor=\"".$colors["color_bg_td_1"]."\"><input type=\"text\" name=\"title\" size=\"70\"></td></tr>\n";
		echo "<tr><td class=\"small\" bgcolor=\"".$colors["color_bg_td_2"]."\">Comment:</td><td align=\"right\" bgcolor=\"".$colors["color_bg_td_2"]."\"><textarea name=\"comment\" rows=\"20\" cols=\"70\" wrap=\"virtual\"></textarea></td></tr>\n";
		echo "<tr><td class=\"small\" bgcolor=\"".$colors["color_bg_td_1"]."\">Photo:</td><td align=\"right\" bgcolor=\"".$colors["color_bg_td_1"]."\"><input name=\"file\" type=\"file\" id=\"uploadFile\"></td></tr>\n";
		echo "</table></td></tr><tr><td align=\"right\" colspan=2><input type=\"submit\" value=\"submit\" name=\"submit\"></td></tr></table>\n";
		echo "</form>\n";
	}
	else
	{
		if(!isset($_FILES["file"]) || $_POST["title"]=="" || $_POST["comment"]=="")
		{
			echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Photo</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">Must include a photo, title, and comment.</td></tr></table>";
			die();
		}

		if ((($_FILES["file"]["type"]=="image/x-png") || ($_FILES["file"]["type"]=="image/gif") || ($_FILES["file"]["type"]=="image/bmp") || ($_FILES["file"]["type"]=="image/pjpeg")) && ($_FILES["file"]["size"]<1048576))
		{
   			if (file_exists("upload/".$_FILES["file"]["name"]))
   			{
       			echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Photo</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">".$_FILES["file"]["name"]." already exists.</td></tr></table>\n";
       			die();
   			}

   			if(!move_uploaded_file($_FILES["file"]["tmp_name"], "upload/".$_FILES["file"]["name"]))
   			{
       			echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Photo</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">".$_FILES["file"]["name"]." cannot be uploaded.</td></tr></table>\n";
       			die();
   			}
   			$filename="http://www.overthemonkey.com/upload/".$_FILES["file"]["name"];
		}
		else
		{
			echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Photo</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">".$_FILES["file"]["name"]." is not a valid file - must be a gif, bmp, png, or jpeg under 1MB size.</td></tr></table>\n";
			die();
		}
		mysql_query("INSERT INTO message VALUES(NULL, \"".$_POST["title"]."\", \"".$_POST["comment"]."\", \"".$_COOKIE["username"]."\", NULL, \"".$filename."\", \"photo\", NULL, NOW(), \"".$_COOKIE["username"]."\", NULL, 0)") or die(mysql_error());
		mysql_query("UPDATE user SET postcount=postcount+1 WHERE name=\"".$_COOKIE["username"]."\"") or die(mysql_error());
			
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Photo</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">Thanks for your submission.  Click <a class=\"small\" href=\"http://www.overthemonkey.com/index.php\">here</a> to return to the main page.</td></tr></table>\n";
	}
}
else
{
	echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Photo</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">You must <a class=\"small\" href=\"http://www.overthemonkey.com/login.php\">login</a> to add a photo.  Click <a class=\"small\" href=\"http://www.overthemonkey.com/index.php\">here</a> to return to the main page.</td></tr></table>\n";
}
echo "</BODY>\n";
echo "</HTML>\n";
?>
