<?PHP
$host="";
$dbuser="";
$dbpassword="";
$dbname="";
mysql_connect("$host","$dbuser","$dbpassword"); 
mysql_select_db("$dbname");

include("header.php");
include("functions.php");

if(isset($_COOKIE["loggedin"]))
{
	if(!isset($_POST["submit"]))
	{
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Private Messages</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\"></td></tr><tr><td colspan=\"2\"><table bgcolor=\"".$colors["color_bg_table"]."\" width=\"100%\" align=\"center\">\n<tr><td width=\"75%\" class=\"header\">Title</td><td width=\"25%\" class=\"header\">Sent</td></tr>\n";
		$color="\"".$colors["color_bg_td_1"]."\"";

		$result=mysql_query("SELECT id, title, u_name, date FROM message WHERE type=\"pm\" AND sid=\"".$_COOKIE["username"]."\" ORDER BY date DESC LIMIT 0, 10") or die(mysql_error());
		while($row=mysql_fetch_array($result))
		{
			echo "<tr><td bgcolor=".$color." width=\"75%\" onMouseOver=this.style.backgroundColor=\"".$colors["color_bg_th"]."\" onMouseOut=this.style.backgroundColor=\"\" onclick=\"window.location.href='http://www.overthemonkey.com/viewmessage.php?id=".$row["id"]."'\"><a href=\"http://www.overthemonkey.com/viewmessage.php?id=".$row["id"]."\">".parse($row["title"])."</a></td><td bgcolor=".$color." width=\"25%\"><table bgcolor=".$color." width=\"100%\" border=\"0\"><tr><td class=\"time\" bgcolor=".$color." width=\"100%\">".us_date($row["date"])." ".us_time($row["date"])."</td></tr><tr><td bgcolor=".$color." width=\"100%\" align=\"right\"><a href=\"http://www.overthemonkey.com/".$row["u_name"]."\">".$row["u_name"]."</a></td></tr></table></td></tr>\n";
			$color=($color=="\"".$colors["color_bg_td_1"]."\""?"\"".$colors["color_bg_td_2"]."\"":"\"".$colors["color_bg_td_1"]."\"");
		}
		echo "</td></tr></table></td></tr><tr><td><a href=\"http://www.overthemonkey.com/newpm.php\">New Private Message</td></tr></table>\n";
		
		$result=mysql_query("SELECT email, aim, age, home, movie, music, tid, avatar, sig FROM user WHERE name=\"".$_COOKIE["username"]."\"") or die(mysql_error());
		$row=mysql_fetch_array($result);

		echo "<form action=\"http://www.overthemonkey.com/usercp.php\" enctype=\"multipart/form-data\" method=\"post\">\n";
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">User Profile</td></tr>\n";
		echo "<tr><td><table width=\"100%\">\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_1"]."\">Email:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_1"]."\" align=\"right\"><input type=\"text\" value=\"".$row["email"]."\" name=\"email\" size=\"50\"></td></tr>\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_2"]."\">AIM Name:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_2"]."\" align=\"right\"><input type=\"text\" value=\"".$row["aim"]."\" name=\"aim\" size=\"50\"></td></tr>\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_1"]."\">Age:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_1"]."\" align=\"right\"><input type=\"text\" value=\"".$row["age"]."\" name=\"age\" size=\"50\"></td></tr>\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_2"]."\">Location:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_2"]."\" align=\"right\"><input type=\"text\" value=\"".$row["home"]."\" name=\"home\" size=\"50\"></td></tr>\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_1"]."\">Favorite Movie:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_1"]."\" align=\"right\"><input type=\"text\" value=\"".$row["movie"]."\" name=\"movie\" size=\"50\"></td></tr>\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_2"]."\">Favorite Band:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_2"]."\" align=\"right\"><input type=\"text\" value=\"".$row["music"]."\" name=\"music\" size=\"50\"></td></tr>\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_2"]."\">Theme:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_2"]."\" align=\"right\"><select name=\"theme\">";
		$result=mysql_query("SELECT id, name FROM theme") or die(mysql_error());
		while($row2=mysql_fetch_array($result))
		{
			echo "<option value=\"".$row2["id"]."\"".($row2["id"]==$row["tid"]?" SELECTED":"").">".$row2["name"]."</option>";
		}
		echo "</select></td></tr>\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_1"]."\">Avatar:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_1"]."\" align=\"right\"><input name=\"avatar\" type=\"file\" value=\"".$row["avatar"]."\" id=\"uploadFile\"></td></tr>\n";
		echo "<tr><td class=\"small\" width=\"30%\" bgcolor=\"".$colors["color_bg_td_1"]."\">Signature:</td><td class=\"small\" width=\"70%\" bgcolor=\"".$colors["color_bg_td_1"]."\" align=\"right\"><input name=\"sig\" type=\"text\" value=\"".$row["sig"]."\" size=\"50\"></td></tr></table>\n";
		echo "<tr><td align=\"right\" bgcolor=\"".$colors["color_bg_table"]."\"><input type=\"submit\" value=\"submit\" name=\"submit\"></td></tr></table>\n";
		echo "</form>\n";
	}
	else
	{
		if(isset($_FILES["avatar"]) && $_FILES["avatar"]["name"]!="")
		{
			list($width, $height, $type, $attr) = getimagesize($_FILES["avatar"]["tmp_name"]);
			if ((($_FILES["avatar"]["type"]=="image/x-png") || ($_FILES["avatar"]["type"]=="image/gif") || ($_FILES["avatar"]["type"]=="image/bmp") || ($_FILES["avatar"]["type"]=="image/pjpeg")) && ($_FILES["avatar"]["size"]<20480) && ($width<100) && ($height<100))
			{
   				if (file_exists("upload/".$_FILES["avatar"]["name"]))
   				{
       				echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Control Panel</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">".$_FILES["avatar"]["name"]." already exists.</td></tr></table>\n";
       				die();
   				}
   				if(!move_uploaded_file($_FILES["avatar"]["tmp_name"], "upload/".$_FILES["avatar"]["name"]))
   				{
       				echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Control Panel</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">".$_FILES["avatar"]["name"]." cannot be uploaded.</td></tr></table>\n";
       				die();
   				}
			}
			else
			{
				echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Control Panel</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">".$_FILES["avatar"]["name"]." is not a valid file - must be a 100x100 or smaller gif, bmp, png, or jpeg under 20 KB filesize.</td></tr></table>\n";
				die();
			}
		}
		$result=mysql_query("SELECT id FROM user WHERE name='".$_COOKIE["username"]."'") or die(mysql_error());
		$row=mysql_fetch_array($result);
		mysql_query("UPDATE user SET email=\"".$_POST["email"]."\", aim=\"".$_POST["aim"]."\", age=".($_POST["age"]==""?"NULL":$_POST["age"]).", home=\"".$_POST["home"]."\", movie=\"".$_POST["movie"]."\", music=\"".$_POST["music"]."\", tid=".$_POST["theme"].(isset($_FILES["avatar"]) && $_FILES["avatar"]["name"]!=""?", avatar=\"http://www.overthemonkey.com/upload/".$_FILES["avatar"]["name"]."\"":"").", sig=\"".$_POST["sig"]."\" WHERE id=".$row["id"]) or die(mysql_error());
		
		echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Control Panel</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">User Profile Saved.  Click <a class=\"small\" href=\"http://www.overthemonkey.com/index.php\">here</a> to return to the main page.</td></tr></table>\n";
	}
}
else
{
	echo "<table class=\"header\" align=\"center\"><tr><td class=\"header\">Control Panel</td></tr><tr><td bgcolor=\"".$colors["color_bg_td_1"]."\">You must <a class=\"small\" href=\"http://www.overthemonkey.com/login.php\">login</a> to view your user control panel.  Click <a class=\"small\" href=\"http://www.overthemonkey.com/index.php\">here</a> to return to the main page.</td></tr></table>\n";
}
echo "</BODY>\n";
echo "</HTML>\n";
?>
