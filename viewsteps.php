<?PHP
include("functions.php");

setup_mysql();
print_header();

if(isset($id))
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
	
	echo "<table id=\"main\">\n";
	
	echo "<tr><td class=\"left\">\n";
	
	print_steps_info($id);
	
	$steps_result=mysql_query("SELECT * FROM steps WHERE id=".$id) or die(mysql_error());
	$steps_row=mysql_fetch_array($steps_result);
	
	echo "<table class=\"scoresheet\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "<tr><th width=\"4%\">#</th><th width=\"12%\">User</th><th width=\"10%\">Score</th><th width=\"10%\">Percent</th><th width=\"4%\">P</th><th width=\"4%\">G</th><th width=\"4%\">G</th><th width=\"4%\">B</th><th width=\"4%\">M</th><th width=\"4%\">OK</th><th width=\"10%\">Date</th><th width=\"20%\">Comment</th><th width=\"10%\">Photo</th></tr>\n";
	
	$i=1;
	$tdclass="gray1";
	$avg_printed=false;
	$score_result=mysql_query("SELECT *, DATE_FORMAT(date, \"%m-%d-%y\") AS formatted_date FROM score WHERE stepsid=".$id." AND newest=1 ORDER BY dp DESC, date ASC") or die(mysql_error());
	while($score_row=mysql_fetch_array($score_result))
	{
		$user_result=mysql_query("SELECT * FROM user WHERE id=".$score_row["userid"]) or die(mysql_error());
		$user_row=mysql_fetch_array($user_result);
		
		if($steps_row["avg_score"]>$score_row["dp"]&&$avg_printed==false)
		{
			echo "<tr class=\"red1\"><td>&nbsp;</td><td>Average User</td><td>".$steps_row["avg_score"]."/".$steps_row["dp"]." [".($steps_row["avg_score"]-$steps_row["dp"])."]</td><td>&nbsp;</td><td colspan=\"6\">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
			$avg_printed=true;
		}
			
		if($_COOKIE["loggedin"]==$user_row["id"])
		{
			$tdclass="green1";
		}
		
		echo "<tr class=\"".$tdclass."\"><td><a href=\"viewscore.php?id=".$score_row["id"]."\">".$i."</a></td><td>".user_name($user_row["name"], $user_row["id"], $user_row["status"])."</td><td>".$score_row["dp"]."/".$steps_row["dp"]." [".($score_row["dp"]-$steps_row["dp"])."]</td><td>".$score_row["percent"]."% ".$score_row["lettergrade"]." ".($score_row["combo"]==$steps_row["maxcombo"]?" FC":"&nbsp;")."</td>".($score_row["distrib"]==1?"<td class=\"".$tdclass." borderright\">".$score_row["perfects"]."</td><td>".$score_row["greats"]."</td><td>".$score_row["goods"]."</td><td>".$score_row["boos"]."</td><td>".$score_row["misses"]."</td><td>".$score_row["oks"]."</td>":"<td colspan=\"6\">&nbsp;</td>")."<td>".$score_row["formatted_date"]."</td><td>".($score_row["comment"]==""?"&nbsp;":htmlspecialchars($score_row["comment"]))."</td><td>".($score_row["picture"]==""?"&nbsp;":"<a href=\"".$score_row["picture"]."\">Photo</a>")."</td></tr>\n";
		$tdclass=($tdclass=="gray1"?"gray2":"gray1");
		$i++;
	}
	
	echo "</table>\n";
	
	echo "</td>\n";
	echo "<td class=\"right\">\n";
	
	echo "<img src=\"viewstepsimage.php?id=".$id."\" width=\"180\">\n";
	
	echo "</td></tr>\n";
	echo "</table>\n";
	
	echo "</td></tr></table>\n";
}
echo "</body>\n";
echo "</html>";
?>