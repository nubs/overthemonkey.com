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

if(isset($id))
{
	$user_result=mysql_query("SELECT *, DATE_FORMAT(date, \"%m-%d-%y\") AS formatted_date FROM user WHERE id=".$id) or die(mysql_error());
	$user_row=mysql_fetch_array($user_result);
	
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
	
	echo "<table class=\"profile\">\n";
	echo "<tr><td><h2>".user_name($user_row["name"], $user_row["id"], $user_row["status"])."</h2></td></tr>\n";
	echo "<tr><td>Joined on ".$user_row["formatted_date"].".</td></tr>\n";
	echo "</table>\n";
	
	echo "<table class=\"scoresheet\">\n";
	echo "<tr><th>#</th><th>Song</th><th>Score</th><th>Percent</th></tr>\n";
	$i=1;
	$tdclass="gray1";
	$score_result=mysql_query("SELECT score.dp AS scoredp, score.percent, score.combo, score.lettergrade, score.distrib, score.id AS scoreid, (IF(steps.std_dev=0,0,(score.dp-steps.avg_score)/steps.std_dev)) AS z_score, steps.id AS stepsid, steps.dp AS stepsdp, steps.difficulty, steps.mode, song.id AS songid, song.name, song.subname FROM score, steps, song WHERE score.userid=".$id." AND score.stepsid=steps.id AND steps.songid=song.id AND score.date>FROM_UNIXTIME(\"".$show_start_date."\") AND score.date<FROM_UNIXTIME(\"".$show_end_date."\") AND score.newest=1".($show_mode=="both"?"":" AND steps.mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (steps.difficulty=\"hea\" OR steps.difficulty=\"oni\")":" AND steps.difficulty=\"".$show_diff."\""))." ORDER BY z_score ASC") or die(mysql_error());
	while($score_row=mysql_fetch_array($score_result))
	{
		echo "<tr class=\"".$tdclass."\"><td><a href=\"viewscore.php?id=".$score_result["scoreid"]."\">".$i."</a></td><td><a href=\"viewsteps.php?id=".$score_row["stepsid"]."\">".$score_row["name"].($score_row["subname"]==""?"":" <i><sub>".$score_row["subname"]."</sub></i>")." ".($score_row["difficulty"]=="beg"?"Beginner":($score_row["difficulty"]=="lig"?"Light":($score_row["difficulty"]=="sta"?"Standard":($score_row["difficulty"]=="hea"?"Heavy":"Challenge"))))." ".($score_row["mode"]=="singles"?"Single":"Double")."</a></td><td>".$score_row["scoredp"]."/".$score_row["stepsdp"]." [".($score_row["scoredp"]-$score_row["stepsdp"])."]</td><td>".$score_row["percent"]."% ".$score_row["lettergrade"]."</td></tr>\n";
		
		$tdclass=($tdclass=="gray1"?"gray2":"gray1");
		$i++;
	}
	echo "</table>\n";
	
	echo "</td>\n";
	echo "<td class=\"right\">\n";
	
	echo "<table class=\"sidebar\">\n";
	echo "<form name=\"form2\" action=\"viewuser.php?id=".$id."\" method=\"post\">\n";
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
	echo "<tr><th class=\"submitbar\" colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></th></tr>\n";
	echo "</form>\n";
	echo "</table>\n";
	
	$stats_result=mysql_query("SELECT SUM(score.dp) AS score_sum, ROUND(AVG(score.percent), 4) AS avg FROM steps, score WHERE score.stepsid=steps.id AND score.userid=".$id." AND score.date>FROM_UNIXTIME(\"".$show_start_date."\") AND score.date<FROM_UNIXTIME(\"".$show_end_date."\") AND score.newest=1".($show_mode=="both"?"":" AND steps.mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (steps.difficulty=\"hea\" OR steps.difficulty=\"oni\")":" AND steps.difficulty=\"".$show_diff."\""))) or die(mysql_error());
	$stats_row=mysql_fetch_array($stats_result);
	
	$steps_total_result=mysql_query("SELECT SUM(dp) AS sum FROM steps WHERE 1".($show_mode=="both"?"":" AND mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (difficulty=\"hea\" OR difficulty=\"oni\")":" AND difficulty=\"".$show_diff."\""))) or die(mysql_error());
	$steps_total_row=mysql_fetch_array($steps_total_result);
	
	$sdg_result=mysql_query("SELECT COUNT(score.dp) AS num FROM score, steps WHERE score.stepsid=steps.id AND score.userid=".$id." AND (steps.dp-score.dp)<10 AND score.date>FROM_UNIXTIME(\"".$show_start_date."\") AND score.date<FROM_UNIXTIME(\"".$show_end_date."\") AND score.newest=1".($show_mode=="both"?"":" AND steps.mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (steps.difficulty=\"hea\" OR steps.difficulty=\"oni\")":" AND steps.difficulty=\"".$show_diff."\""))) or die(mysql_error());
	$sdg_row=mysql_fetch_array($sdg_result);
	
	$fc_result=mysql_query("SELECT COUNT(score.dp) AS num FROM score, steps WHERE score.stepsid=steps.id AND score.userid=".$id." AND score.combo=steps.maxcombo AND score.date>FROM_UNIXTIME(\"".$show_start_date."\") AND score.date<FROM_UNIXTIME(\"".$show_end_date."\") AND score.newest=1".($show_mode=="both"?"":" AND steps.mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (steps.difficulty=\"hea\" OR steps.difficulty=\"oni\")":" AND steps.difficulty=\"".$show_diff."\""))) or die(mysql_error());
	$fc_row=mysql_fetch_array($fc_result);
	
	$grade_count_result=mysql_query("SELECT COUNT(score.dp) AS num, score.lettergrade FROM score, steps WHERE score.stepsid=steps.id AND score.userid=".$id." AND score.date>FROM_UNIXTIME(\"".$show_start_date."\") AND score.date<FROM_UNIXTIME(\"".$show_end_date."\") AND score.newest=1".($show_mode=="both"?"":" AND steps.mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (steps.difficulty=\"hea\" OR steps.difficulty=\"oni\")":" AND steps.difficulty=\"".$show_diff."\""))." GROUP BY score.lettergrade") or die(mysql_error());
	while($grade_count_row=mysql_fetch_array($grade_count_result))
	{
		$grade_count[$grade_count_row["lettergrade"]]=$grade_count_row["num"];
	}
	
	$rank_result=mysql_query("SELECT score.userid FROM score, steps WHERE score.stepsid=steps.id AND score.date>FROM_UNIXTIME(\"".$show_start_date."\") AND score.date<FROM_UNIXTIME(\"".$show_end_date."\") AND score.newest=1".($show_mode=="both"?"":" AND steps.mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (steps.difficulty=\"hea\" OR steps.difficulty=\"oni\")":" AND steps.difficulty=\"".$show_diff."\""))." GROUP BY score.userid HAVING SUM(score.dp)>".(int)$stats_row["score_sum"]) or die(mysql_error());
	$rank=mysql_num_rows($rank_result)+1;
	
	$avg_rank_result=mysql_query("SELECT score.userid FROM score, steps WHERE score.stepsid=steps.id AND score.date>FROM_UNIXTIME(\"".$show_start_date."\") AND score.date<FROM_UNIXTIME(\"".$show_end_date."\") AND score.newest=1".($show_mode=="both"?"":" AND steps.mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (steps.difficulty=\"hea\" OR steps.difficulty=\"oni\")":" AND steps.difficulty=\"".$show_diff."\""))." GROUP BY score.userid HAVING ROUND(AVG(score.percent),4)>".(int)$stats_row["avg"]) or die(mysql_error());
	$avg_rank=mysql_num_rows($avg_rank_result)+1;
	
	echo "<table class=\"sidebar\">\n";
	echo "<tr><th>".($show_mode=="both"?"<img src=\"Images/song_mode_singles_16_16.jpg\"><img src=\"Images/song_mode_doubles_16_16.jpg\">":"<img src=\"Images/song_mode_".$show_mode."_16_16.jpg\">").($show_diff=="all"?"<img src=\"Images/song_diff_beg_16_16.jpg\"><img src=\"Images/song_diff_ligg_16_16.jpg\"><img src=\"Images/song_diff_sta_16_16.jpg\"><img src=\"Images/song_diff_hea_16_16.jpg\"><img src=\"Images/song_diff_oni_16_16.jpg\">":($show_diff=="default"?"<img src=\"Images/song_diff_hea_16_16.jpg\"><img src=\"Images/song_diff_oni_16_16.jpg\">":"<img src=\"Images/song_diff_".$show_diff."_16_16.jpg\">"))."</th></tr>\n";
	echo "<tr class=\"gray1\"><td>".($stats_row["score_sum"]==""?"0":$stats_row["score_sum"])."/".$steps_total_row["sum"]." [".((int)$stats_row["score_sum"]-$steps_total_row["sum"])."]<br>Ranked: ".$rank."</td></tr>\n";
	echo "<tr class=\"gray2\"><td>Avg DP%: ".(int)$stats_row["avg"]."%<br>Ranked: ".$avg_rank."</td></tr>\n";
	echo "<tr class=\"gray1\"><td>".(int)$grade_count["AAA"]." AAAs<br>".(int)$grade_count["AA"]." AAs<br>\n".(int)$grade_count["A"]." As<br>\n".(int)$grade_count["B"]." Bs<br>\n".(int)$grade_count["C"]." Cs<br>\n".(int)$grade_count["D"]." Ds<br>\n".(int)$grade_count["E"]." Es</td></tr>\n";
	echo "<tr class=\"gray2\"><td>".(int)$sdg_row["num"]." SDGs<br>\n".(int)$fc_row["num"]." FCs</td></tr>\n";
	echo "</table>\n";
	
	echo "<table class=\"sidebar\">\n";
	echo "<tr><th>Watches</th></tr>\n";
	$friend_result=mysql_query("SELECT * FROM friend, user WHERE friend.a_id=".$id." AND friend.b_id=user.id") or die(mysql_error());
	$tdclass="gray1";
	while($friend_row=mysql_fetch_array($friend_result))
	{
		echo "<tr class=\"".$tdclass."\"><td>".user_name($friend_row["name"], $friend_row["id"], $friend_row["status"])."</td></tr>\n";
		$tdclass=($tdclass=="gray1"?"gray2":"gray1");
	}
	echo "</table>\n";
	
	echo "<table class=\"sidebar\">\n";
	echo "<tr><th>Watched By</th></tr>\n";
	$friend_of_result=mysql_query("SELECT * FROM friend, user WHERE friend.b_id=".$id." AND friend.a_id=user.id") or die(mysql_error());
	$tdclass="gray1";
	while($friend_of_row=mysql_fetch_array($friend_of_result))
	{
		echo "<tr class=\"".$tdclass."\"><td>".user_name($friend_of_row["name"], $friend_of_row["id"], $friend_of_row["status"])."</td></tr>\n";
		$tdclass=($tdclass=="gray1"?"gray2":"gray1");
	}
	echo "</div>\n";
	
	$challenge_result=mysql_query("SELECT COUNT(*) AS num, status FROM challenge WHERE a_id=".$id." GROUP BY status") or die(mysql_error());
	while($challenge_row=mysql_fetch_array($challenge_result))
	{
		$challenge[$challenge_row["status"]]=$challenge_row["num"];
	}
	
	echo "<table class=\"sidebar\">\n";
	echo "<tr><th><a href=\"viewchallenges.php?aid=".$id."\">Challenges</a></th></tr>\n";
	echo "<tr class=\"gray1\"><td><a href=\"viewchallenges.php?aid=".$id."&status=incomplete\">".(int)$challenge["incomplete"]." incomplete</a></td></tr>\n";
	echo "<tr class=\"gray2\"><td><a href=\"viewchallenges.php?aid=".$id."&status=complete\">".(int)$challenge["complete"]." complete</a></td></tr>\n";
	echo "<tr class=\"gray1\"><td><a href=\"viewchallenges.php?aid=".$id."&status=abandoned\">".(int)$challenge["abandoned"]." abandoned</a></td></tr>\n";
	echo "</table>\n";
	
	$challenged_result=mysql_query("SELECT COUNT(*) AS num, status FROM challenge WHERE b_id=".$id." GROUP BY status") or die(mysql_error());
	while($challenged_row=mysql_fetch_array($challenged_result))
	{
		$challenged[$challenged_row["status"]]=$challenged_row["num"];
	}
	
	echo "<table class=\"sidebar\">\n";
	echo "<tr><th><a href=\"viewchallenges.php?bid=".$id."\">Challenges Sent</a></th></tr>\n";
	echo "<tr class=\"gray1\"><td><a href=\"viewchallenges.php?bid=".$id."&status=incomplete\">".(int)$challenge["incomplete"]." incomplete</a></td></tr>\n";
	echo "<tr class=\"gray2\"><td><a href=\"viewchallenges.php?bid=".$id."&status=complete\">".(int)$challenge["complete"]." complete</a></td></tr>\n";
	echo "<tr class=\"gray1\"><td><a href=\"viewchallenges.php?bid=".$id."&status=abandoned\">".(int)$challenge["abandoned"]." abandoned</a></td></tr>\n";
	echo "</table>\n";
	
	echo "</td></tr>\n";
	echo "</table>\n";
	
	echo "</td></tr></table>\n";
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
	
	echo "<table id=\"main\">\n";
	
	echo "<tr><td class=\"left\">\n";
	
	echo "<table class=\"scoresheet\">\n";
	echo "<tr><th>#</th><th>Name</th><th>Date</th><th>Total DP</th><th>Avg DP%</th></tr>\n";
	
	$steps_result=mysql_query("SELECT SUM(dp) AS sum FROM steps WHERE 1".($show_mode=="both"?"":" AND mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (difficulty=\"hea\" OR difficulty=\"oni\")":" AND difficulty=\"".$show_diff."\""))) or die(mysql_error());
	$steps_row=mysql_fetch_array($steps_result);
	
	$i=1;
	$tdclass="gray1";
	$user_result=mysql_query("SELECT * FROM user WHERE date>FROM_UNIXTIME(\"".$show_start_date."\") AND date<FROM_UNIXTIME(\"".$show_end_date."\")") or die(mysql_error());
	while($user_row=mysql_fetch_array($user_result))
	{
		$stats_result=mysql_query("SELECT SUM(score.dp) AS sum, ROUND(AVG(score.percent), 4) AS avg FROM steps, score WHERE score.stepsid=steps.id AND score.userid=".$user_row["id"]." AND score.date>FROM_UNIXTIME(\"".$show_start_date."\") AND score.date<FROM_UNIXTIME(\"".$show_end_date."\") AND score.newest=1".($show_mode=="both"?"":" AND mode=\"".$show_mode."\"").($show_diff=="all"?"":($show_diff=="default"?" AND (difficulty=\"hea\" OR difficulty=\"oni\")":" AND difficulty=\"".$show_diff."\""))) or die(mysql_error());
		$stats_row=mysql_fetch_array($stats_result);
		
		echo "<tr class=\"".$tdclass."\"><td>".$i."</td><td>".user_name($user_row["name"], $user_row["id"], $user_row["status"])."</td><td>".$user_row["date"]."</td><td>".(int)$stats_row["sum"]."/".$steps_row["sum"]." [".((int)$stats_row["sum"]-$steps_row["sum"])."]</td><td>".(int)$stats_row["avg"]."%</td></tr>\n";
		
		$tdclass=($tdclass=="gray1"?"gray2":"gray1");
		$i++;
	}
	
	echo "</table>\n";
	
	echo "</td>\n";
	echo "<td class=\"right\">\n";
	
	echo "<form name=\"form2\" action=\"viewuser.php\" method=\"post\">\n";
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
	echo "<tr><th class=\"submitbar\" colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></th></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	echo "</td></tr>\n";
	echo "</table>\n";
	
	echo "</td></tr></table>\n";
}
echo "</body>\n";
echo "</html>";
?>