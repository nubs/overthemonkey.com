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
		
		echo "<form name=\"form1\" enctype=\"multipart/form-data\" method=\"post\" action=\"http://www.overthemonkey.com/newpm.php\">\n";
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\" colspan=\"2\">New Private Message</td></tr><tr><td><table width=\"100%\"><tr><td class=\"small\" bgcolor=\"".$colors["color_bg_td_1"]."\">To:</td><td align=\"right\" bgcolor=\"".$colors["color_bg_td_1"]."\"><input type=\"text\" name=\"to\" size=\"70\" maxlength=\"15\"></td></tr>\n";
		echo "<tr><td class=\"small\" bgcolor=\"".$colors["color_bg_td_2"]."\">Subject:</td><td align=\"right\" bgcolor=\"".$colors["color_bg_td_2"]."\"><input type=\"text\" name=\"subject\" size=\"70\" maxlength=\"50\"></td></tr>\n";
		echo "<tr><td class=\"small\" bgcolor=\"".$colors["color_bg_td_1"]."\">Message:</td><td align=\"right\" bgcolor=\"".$colors["color_bg_td_1"]."\"><textarea name=\"message\" rows=\"20\" cols=\"70\" wrap=\"virtual\"></textarea></td></tr>\n";
		echo "<tr><td class=\"small\" bgcolor=\"".$colors["color_bg_td_2"]."\">Attachment:</td><td align=\"right\" bgcolor=\"".$colors["color_bg_td_2"]."\"><input name=\"file\" type=\"file\" id=\"uploadFile\"></td></tr>\n";
		echo "</table></td></tr><tr><td align=\"right\" colspan=2><input type=\"submit\" value=\"submit\" name=\"submit\"></td></tr></table>\n";
		echo "</form>\n";
	}
	else
	{
		if(isset($_FILES["file"]) && $_FILES["file"]["name"]!="")
		{
			if ((($_FILES["file"]["type"]=="application/zip") || ($_FILES["file"]["type"]=="image/x-png") || ($_FILES["file"]["type"]=="image/gif") || ($_FILES["file"]["type"]=="image/bmp") || ($_FILES["file"]["type"]=="image/pjpeg")) && ($_FILES["file"]["size"]<1048576))
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
   				$_POST["message"]=$_POST["message"]."\n<A HREF=\"http://www.overthemonkey.com/upload/".$_FILES["file"]["name"]."\" target=\"_blank\">attachment</a>";		
			}
			else
			{
				echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Photo</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">".$_FILES["file"]["name"]." is not a valid file - must be a gif, bmp, png, jpeg, or zip under 1MB size.</td></tr></table>\n";
				die();
			}
		}	
		if($_POST["subject"]=="" || $_POST["message"]=="" || $_POST["to"]=="")
		{
			echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Private Message</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">Must include a username, subject, and message.</td></tr></table>";
		}
		else
		{
			mysql_query("INSERT INTO message VALUES(NULL, \"".$_POST["subject"]."\", \"".$_POST["message"]."\", \"".$_COOKIE["username"]."\", NULL, ".((isset($_FILES["file"]) && $_FILES["file"]["name"]!="")?"\"http://www.overthemonkey.com/upload/".$_FILES["file"]["name"]."\"":"NULL").", \"pm\", NULL, NULL, \"".$_COOKIE["username"]."\", \"".$_POST["to"]."\", 0)") or die(mysql_error());
			
			echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Private Message</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">Thanks for your submission.  Click <a class=\"small\" href=\"http://www.overthemonkey.com/index.php\">here</a> to return to the main page.</td></tr></table>\n";
		}
	}
}
else
{
	echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">New Private Message</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">You must <a class=\"small\" href=\"http://www.overthemonkey.com/login.php\">login</a> to send a private message.  Click <a class=\"small\" href=\"http://www.overthemonkey.com/index.php\">here</a> to return to the main page.</td></tr></table>\n";
}
echo "</BODY>\n";
echo "</HTML>\n";
?>
