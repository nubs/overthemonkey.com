<?PHP
include("functions.php");

setup_mysql();
print_header();
if(isset($submit))
{
	if(!isset($_FILES["file"]))
	{
		echo "FILE NOT SENT";
		die();
	}
	
	if (file_exists("stepfiles/".$_FILES["file"]["name"]))
	{
		echo "FILE ALREADY EXISTS";
		die();
	}
	
	if(!move_uploaded_file($_FILES["file"]["tmp_name"], "stepfiles/".$_FILES["file"]["name"]))
	{
		echo "CAN'T UPLOAD FILE";
		die();
	}
	
	$song_data=parse_sm_file("stepfiles/".$_FILES["file"]["name"]);

	$song_result=mysql_query("SELECT * FROM song WHERE id=".$id) or die(mysql_error());
	$song_row=mysql_fetch_array($song_result);
	
	foreach($song_data["steps"] AS $steps)
	{
		if(isset($song_row[$steps["diff"]."_".$steps["mode"]]))
		{
			$steps_result=mysql_query("SELECT * FROM steps WHERE songid=".$id." AND difficulty=\"".$steps["diff"]."\" AND mode=\"".$steps["mode"]."\"") or die(mysql_error());
			$steps_row=mysql_fetch_array($steps_result);
			
			mysql_query("UPDATE steps SET stream=".$steps["stream"].", voltage=".$steps["voltage"].", air=".$steps["air"].", freeze=".$steps["freeze"].", chaos=".$steps["chaos"]." WHERE id=".$steps_row["id"]) or die(mysql_error());
		}
	}
}
elseif(isset($id))
{
	echo "<form name=\"form1\" enctype=\"multipart/form-data\" method=\"post\" action=\"updatesimfiles.php?id=".$id."\">\n";
	echo "<table>\n";
	echo "<tr><td colspan=\"2\">Add Song</td></tr>\n";
	echo "<tr><td>File:</td><td><input name=\"file\" type=\"file\"></td></tr>\n";
	echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\"submit\" name=\"submit\"></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}
else
{
	echo "<table>\n";
	$song_result=mysql_query("SELECT * FROM song ORDER BY name, subname") or die(mysql_error());
	while($song_row=mysql_fetch_array($song_result))
	{
		echo "<tr><td><a href=\"updatesimfiles.php?id=".$song_row["id"]."\">".$song_row["name"]." ".$song_row["subname"]."</a></td></tr>\n";
	}
	echo "</table>\n";
}

echo "</body>\n";
echo "</html>\n";
?>