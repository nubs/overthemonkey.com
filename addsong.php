<?PHP

include("functions.php");
setup_mysql();
if(isset($submit))
{
	if(!isset($_FILES["file"]))
	{
		die();
	}
	
	if (file_exists("stepfiles/".$_FILES["file"]["name"]))
	{
		die();
	}
	
	if(!move_uploaded_file($_FILES["file"]["tmp_name"], "upload/".$_FILES["file"]["name"]))
	{
		die();
	}
	
	$handle=fopen("upload/".$_FILES["file"]["name"], "r");
}
else
{
	echo "<form name=\"form1\" enctype=\"multipart/form-data\" method=\"post\" action=\"addsong.php\">\n";
	echo "<table>\n";
	echo "<tr><td colspan=\"2\">Add Song</td></tr>\n";
	echo "<tr><td>File:</td><td><input name=\"file\" type=\"file\"></td></tr>\n";
	echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\"submit\" name=\"submit\"></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

?>