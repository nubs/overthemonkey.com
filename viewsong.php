<?PHP
include("functions.php");
setup_mysql();

if(isset($submit))
{
	setcookie("showmode", $show_mode, 0);
	setcookie("showdiff", $show_diff, 0);
	setcookie("showstartmonth", $show_start_month, 0);
	setcookie("showstartday", $show_start_day, 0);
	setcookie("showstartyear", $show_start_year, 0);
	setcookie("showendmonth", $show_end_month, 0);
	setcookie("showendday", $show_end_day, 0);
	setcookie("showendyear", $show_end_year, 0);
	
	refresh_header();
	die();
}

print_header();

if(isset($id))
{
	$song_result=mysql_query("SELECT * FROM song WHERE id=".$id) or die(mysql_error());
	$song_row=mysql_fetch_array($song_result);
	$show_start_month=(isset($_COOKIE["showstartmonth"])?$_COOKIE["showstartmonth"]:"1");
	$show_start_day=(isset($_COOKIE["showstartday"])?$_COOKIE["showstartday"]:"1");
	$show_start_year=(isset($_COOKIE["showstartyear"])?$_COOKIE["showstartyear"]:"2004");
	$show_end_month=(isset($_COOKIE["showendmonth"])?$_COOKIE["showendmonth"]:"12");
	$show_end_day=(isset($_COOKIE["showendday"])?$_COOKIE["showendday"]:"31");
	$show_end_year=(isset($_COOKIE["showendyear"])?$_COOKIE["showendyear"]:"2005");
	$show_start_date=mktime(0, 0, 0, $show_start_month, $show_start_day, $show_start_year);
	$show_end_date=mktime(0, 0, 0, $show_end_month, $show_end_day, $show_end_year);
}
else
{
	echo "<table id=\"body\"><tr><td>\n";
	
	echo "<table id=\"head\">\n";
	echo "<tr><td class=\"banner\"><h1>overTHEmonkey</h1></td>\n";
	echo "<td class=\"nav\">\n";
	echo "<table class=\"navbar\">\n";
	echo "<tr><td>Home</td><td>Profile</td><td>Songs</td></tr>\n";
	echo "<tr><td>A</td><td>B</td><td>C</td></tr>\n";
	echo "</table>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	
	$show_mode=(isset($_COOKIE["showmode"])?$_COOKIE["showmode"]:"singles");
	$show_diff=(isset($_COOKIE["showdiff"])?$_COOKIE["showdiff"]:"default");
	$show_start_month=(isset($_COOKIE["showstartmonth"])?$_COOKIE["showstartmonth"]:"1");
	$show_start_day=(isset($_COOKIE["showstartday"])?$_COOKIE["showstartday"]:"1");
	$show_start_year=(isset($_COOKIE["showstartyear"])?$_COOKIE["showstartyear"]:"2004");
	$show_end_month=(isset($_COOKIE["showendmonth"])?$_COOKIE["showendmonth"]:"12");
	$show_end_day=(isset($_COOKIE["showendday"])?$_COOKIE["showendday"]:"31");
	$show_end_year=(isset($_COOKIE["showendyear"])?$_COOKIE["showendyear"]:"2005");
	$show_start_date=mktime(0, 0, 0, $show_start_month, $show_start_day, $show_start_year);
	$show_end_date=mktime(0, 0, 0, $show_end_month, $show_end_day, $show_end_year);
	
	$mode=array("singles", "doubles");
	$diff=array("beg", "lig", "sta", "hea", "oni");
	
	echo "<table id=\"main\">\n";
	
	echo "<tr><td class=\"left\">\n";
	
	echo "<form name=\"form1\" action=\"submitscore.php\" method=\"post\">\n";
	echo "<input type=\"submit\" name=\"songselect\" value=\"submit\">\n";
	echo "<table class=\"songsheet\" id=\"table1\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "<tr><th width=\"30%\">&nbsp;</th>";
	
	foreach($mode as $m)
	{
		foreach($diff as $d)
		{
			if(($m==$show_mode||$show_mode=="both")&&($d==$show_diff||$show_diff=="all"||($show_diff=="default"&&($d=="hea"||$d=="oni")))&&(!($m=="doubles"&&$d=="beg")))
			{
				echo "<th class=\"selectable\" onclick=\"check(this, document.form1.hid".$m.$d.")\"><input name=\"hid".$m.$d."\" type=\"hidden\" value=\"0\">&nbsp;</th><th class=\"song\"><img src=\"Images/song_mode_".$m."_16_16.jpg\"><img src=\"Images/song_diff_".$d."_16_16.jpg\"></th>";
			}
		}
	}
	
	echo "</tr>\n";
	
	$tdclass="gray1";
	$song_result=mysql_query("SELECT * FROM song ORDER BY name, subname") or die(mysql_error());
	while($song_row=mysql_fetch_array($song_result))
	{
		echo "<tr class=\"".$tdclass."\"><td><a href=\"viewsong.php?id=".$song_row["id"]."\">".$song_row["name"].($song_row["subname"]==""?"":" <i><sub>".$song_row["subname"]."</sub></i>")."</a></td>";
		
		foreach($mode as $m)
		{
			foreach($diff as $d)
			{
				if(($m==$show_mode||$show_mode=="both")&&($d==$show_diff||$show_diff=="all"||($show_diff=="default"&&($d=="hea"||$d=="oni")))&&(!($m=="doubles"&&$d=="beg")))
				{
					print_steps_square($song_row[$d."_".$m], $show_start_date, $show_end_date);
				}
			}
		}
		
		echo "</tr>\n";
		
		$tdclass=($tdclass=="gray1"?"gray2":"gray1");
	}
	
	echo "</table>\n";
	echo "</form>\n";
	
	echo "</td>\n";
	echo "<td class=\"right\">\n";
	
	echo "<form name=\"form2\" action=\"viewsong.php\" method=\"post\">\n";
	echo "<table class=\"sidebar\">\n";
	echo "<tr class=\"gray1\"><td>Mode</td><td><select name=\"show_mode\"><option value=\"singles\"".($show_mode=="singles"?" selected":"").">Single</option><option value=\"doubles\"".($show_mode=="doubles"?" selected":"").">Double</option><option value=\"both\"".($show_mode=="both"?" selected":"").">Both</option></select></td></tr>\n";
	echo "<tr class=\"gray2\"><td>Difficulty</td><td><select name=\"show_diff\"><option value=\"beg\"".($show_diff=="beg"?" selected":"").">Beginner</option><option value=\"lig\"".($show_diff=="lig"?" selected":"").">Light</option><option value=\"sta\"".($show_diff=="sta"?" selected":"").">Standard</option><option value=\"hea\"".($show_diff=="hea"?" selected":"").">Heavy</option><option value=\"oni\"".($show_diff=="oni"?" selected":"").">Challenge</option><option value=\"default\"".($show_diff=="default"?" selected":"").">Default</option><option value=\"all\"".($show_diff=="all"?" selected":"").">All</option></select></td></tr>\n";
	echo "<tr class=\"gray1\"><td>Start Date</td><td><select name=\"show_start_month\"><option value=\"1\"".($show_start_month=="1"?" selected":"").">January</option><option value=\"2\"".($show_start_month=="2"?" selected":"").">February</option><option value=\"3\"".($show_start_month=="3"?" selected":"").">March</option><option value=\"4\"".($show_start_month=="4"?" selected":"").">April</option><option value=\"5\"".($show_start_month=="5"?" selected":"").">May</option><option value=\"6\"".($show_start_month=="6"?" selected":"").">June</option><option value=\"7\"".($show_start_month=="7"?" selected":"").">July</option><option value=\"8\"".($show_start_month=="8"?" selected":"").">August</option><option value=\"9\"".($show_start_month=="9"?" selected":"").">September</option><option value=\"10\"".($show_start_month=="10"?" selected":"").">October</option><option value=\"11\"".($show_start_month=="11"?" selected":"").">November</option><option value=\"12\"".($show_start_month=="12"?" selected":"").">December</option></select><select name=\"show_start_day\">";
	for($i=1;$i<=31;$i++)
	{
		echo "<option value=\"".$i."\"".($show_start_day==$i?" selected":"").">".$i."</option>";
	}
	echo "</select><select name=\"show_start_year\"><option value=\"2004\"".($show_start_year=="2004"?" selected":"").">2004</option><option value=\"2005\"".($show_start_year=="2005"?" selected":"").">2005</option></select></td></tr>\n";
	echo "<tr class=\"gray2\"><td>End Date</td><td><select name=\"show_end_month\"><option value=\"1\"".($show_end_month=="1"?" selected":"").">January</option><option value=\"2\"".($show_end_month=="2"?" selected":"").">February</option><option value=\"3\"".($show_end_month=="3"?" selected":"").">March</option><option value=\"4\"".($show_end_month=="4"?" selected":"").">April</option><option value=\"5\"".($show_end_month=="5"?" selected":"").">May</option><option value=\"6\"".($show_end_month=="6"?" selected":"").">June</option><option value=\"7\"".($show_end_month=="7"?" selected":"").">July</option><option value=\"8\"".($show_end_month=="8"?" selected":"").">August</option><option value=\"9\"".($show_end_month=="9"?" selected":"").">September</option><option value=\"10\"".($show_end_month=="10"?" selected":"").">October</option><option value=\"11\"".($show_end_month=="11"?" selected":"").">November</option><option value=\"12\"".($show_end_month=="12"?" selected":"").">December</option></select><select name=\"show_end_day\">";
	for($i=1;$i<=31;$i++)
	{
		echo "<option value=\"".$i."\"".($show_end_day==$i?" selected":"").">".$i."</option>";
	}
	echo "</select><select name=\"show_end_year\"><option value=\"2004\"".($show_end_year=="2004"?" selected":"").">2004</option><option value=\"2005\"".($show_end_year=="2005"?" selected":"").">2005</option></select></td></tr>\n";
	echo "<tr><th class=\"submitbar\" colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	echo "</td></tr>\n";
	echo "</table>\n";
	
	echo "</td></tr></table>\n";
}

echo "</body>\n";
echo "</html>";
?>