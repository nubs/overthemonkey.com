<?PHP
include("functions.php");
setup_mysql();
print_header();
if(!isset($_COOKIE["loggedin"]))
{
	echo "You must <a href=\"login.php\">Login</a> before submitting scores.\n";
	die();
}

if(isset($scoresubmit))
{
	$stepsid_result=mysql_query("SELECT id FROM steps ORDER BY sortname") or die(mysql_error());
	while($stepsid_row=mysql_fetch_array($stepsid_result))
	{
		if($_POST["hid".$stepsid_row["id"]]=="true")
		{
			$steps_result=mysql_query("SELECT song.name, song.subname, song.banner, steps.difficulty, steps.mode, steps.id, steps.dp, steps.freezes, steps.steps, steps.maxcombo, steps.scores FROM song, steps WHERE song.id=steps.songid AND steps.id=".$stepsid_row["id"]) or die(mysql_error());
			$steps_row=mysql_fetch_array($steps_result);
			
			$dp=(($_POST["dp".$steps_row["id"]]=="")?0:$_POST["dp".$steps_row["id"]]);
			$combo=(($_POST["combo".$steps_row["id"]]=="")?0:$_POST["combo".$steps_row["id"]]);
			$error=false;
			
			if($dp!=0)
			{
				$perfects=$greats=$goods=$boos=$misses=$oks=0;
				if($dp>$steps_row["dp"])
				{
					echo "Invalid score for ".song_name_span($steps_row["name"], $steps_row["subname"], $steps_row["difficulty"], $steps_row["mode"]).".  DP greater than maximum dp for song.<br>\n";
					$error=true;
				}
				
				if($dp==$steps_row["dp"])
				{
					$combo=$steps_row["maxcombo"];
				}
				
				$distrib=0;
			}
			else
			{
				$perfects=(($_POST["perfects".$steps_row["id"]]=="")?0:$_POST["perfects".$steps_row["id"]]);
				$greats=($_POST["greats".$steps_row["id"]]==""?0:$_POST["greats".$steps_row["id"]]);
				$goods=($_POST["goods".$steps_row["id"]]==""?0:$_POST["goods".$steps_row["id"]]);
				$boos=($_POST["boos".$steps_row["id"]]==""?0:$_POST["boos".$steps_row["id"]]);
				$misses=($_POST["misses".$steps_row["id"]]==""?0:$_POST["misses".$steps_row["id"]]);
				$oks=($_POST["oks".$steps_row["id"]]==""?0:$_POST["oks".$steps_row["id"]]);
				
				if($perfects<0||$greats<0||$goods<0||$boos<0||$misses<0||$oks<0)
				{
					echo "Invalid score for ".song_name_span($steps_row["name"], $steps_row["subname"], $steps_row["difficulty"], $steps_row["mode"]).".  Negative values entered.<br>\n";
					$error=true;
				}
				
				$numsteps=$greats+$goods+$boos+$misses;
				$perfects=($_POST["autop".$steps_row["id"]]=="1"?$steps_row["steps"]-$numsteps:$perfects);
				$oks=($_POST["autof".$steps_row["id"]]=="1"?$steps_row["freezes"]:$oks);
				$numsteps=$perfects+$greats+$goods+$boos+$misses;
				$dp=$perfects*2+$greats-$boos*4-$misses*8+$oks*6;
				
				if($goods+$boos+$misses==0)
				{
					$combo=$steps_row["maxcombo"];
				}
				
				if($numsteps!=$steps_row["steps"])
				{
					echo "Invalid score for ".song_name_span($steps_row["name"], $steps_row["subname"], $steps_row["difficulty"], $steps_row["mode"]).".  Number of steps are incorrect.<br>\n";
					$error=true;
				}
				
				if($oks>$steps_row["freezes"])
				{
					echo "Invalid score for ".song_name_span($steps_row["name"], $steps_row["subname"], $steps_row["difficulty"], $steps_row["mode"]).".  Number of freezes are incorrect.<br>\n";
					$error=true;
				}
				
				if(($combo>($steps_row["maxcombo"]-($goods+$boos+$misses)))||$combo<0)
				{
					echo "Invalid score for ".song_name_span($steps_row["name"], $steps_row["subname"], $steps_row["difficulty"], $steps_row["mode"]).".  Entered combo is not possible.<br>\n";
					$error=true;
				}
				
				$distrib=1;
			}
			
			if($error==false)
			{
				$percent=$dp/$steps_row["dp"]*100;
				$lettergrade=($percent==100?"AAA":($percent>=93?"AA":($percent>=80?"A":($percent>=65?"B":($percent>=45?"C":"D")))));
				
				$score_result=mysql_query("SELECT * FROM score WHERE userid=".$_COOKIE["loggedin"]." AND stepsid=".$steps_row["id"]." AND newest=1") or die(mysql_error());
				if(mysql_num_rows($score_result)>0)
				{
					if($dp<=$score_row["dp"])
					{
						$score_row=mysql_fetch_array($score_result);
						echo "CONFIRM REPLACE";
					}
					else
					{
						$score_row=mysql_fetch_array($score_result);
						
						echo "Your score for ".song_name_span($steps_row["name"], $steps_row["subname"], $steps_row["difficulty"], $steps_row["mode"])." was successfully entered.<br>\n";
						echo "You improved your score from ".$score_row["dp"]."/".$steps_row["dp"]."DP (".$score_row["percent"]."%) to ".$dp."/".$steps_row["dp"]."DP (".$percent."%).  An improvement of ".($dp-$score_row["dp"])."DP (".($percent-$score_row["percent"])."%).<br>\n";
						
						if($lettergrade!=$score_row["lettergrade"])
						{
							echo "That's a new ".$lettergrade.".<br>\n";
						}
						
						echo "Your rank increased from ".user_rank_on_song($_COOKIE["loggedin"], $steps_row["id"])."/".$steps_row["scores"];
						
						mysql_query("UPDATE score SET newest=0 WHERE id=".$score_row["id"]) or die(mysql_error());
						mysql_query("INSERT INTO score (userid, stepsid, dp, percent, lettergrade, combo, date, perfects, greats, goods, boos, misses, oks, newest, distrib) VALUES(".$_COOKIE["loggedin"].", ".$steps_row["id"].", ".$dp.", ".$percent.", \"".$lettergrade."\", ".$combo.", NOW(), ".$perfects.", ".$greats.", ".$goods.", ".$boos.", ".$misses.", ".$oks.", 1, ".$distrib.")") or die(mysql_error());
						
						echo " to ".user_rank_on_song($_COOKIE["loggedin"], $steps_row["id"])."/".$steps_row["scores"].".<br>\n";
						
						$average_score_result=mysql_query("SELECT ROUND(AVG(dp)) AS avgdp, COUNT(dp) AS numscores FROM score WHERE newest=1 AND stepsid=".$steps_row["id"]) or die(mysql_error());
						$average_score_row=mysql_fetch_array($average_score_result);
						
						$std_dev_result=mysql_query("SELECT SQRT(SUM((dp-".($average_score_row["avgdp"]==""?0:$average_score_row["avgdp"]).")*(dp-".($average_score_row["avgdp"]==""?0:$average_score_row["avgdp"])."))/".$average_score_row["numscores"].") AS std_dev FROM score WHERE newest=1 AND stepsid=".$steps_row["id"]) or die(mysql_error());
						$std_dev_row=mysql_fetch_array($std_dev_result);
						mysql_query("UPDATE steps SET avg_score=".$average_score_row["avgdp"].", scores=".$average_score_row["numscores"].", std_dev=".$std_dev_row["std_dev"]." WHERE id=".$steps_row["id"]) or die(mysql_error());
					}
				}
				else
				{
					mysql_query("INSERT INTO score (userid, stepsid, dp, percent, lettergrade, combo, date, perfects, greats, goods, boos, misses, oks, newest, distrib) VALUES(".$_COOKIE["loggedin"].", ".$steps_row["id"].", ".$dp.", ".$percent.", \"".$lettergrade."\", ".$combo.", NOW(), ".$perfects.", ".$greats.", ".$goods.", ".$boos.", ".$misses.", ".$oks.", 1, ".$distrib.")") or die(mysql_error());
					
					$average_score_result=mysql_query("SELECT ROUND(AVG(dp)) AS avgdp, COUNT(dp) AS numscores FROM score WHERE newest=1 AND stepsid=".$steps_row["id"]) or die(mysql_error());
					$average_score_row=mysql_fetch_array($average_score_result);
					
					$std_dev_result=mysql_query("SELECT SQRT(SUM((dp-".($average_score_row["avgdp"]==""?0:$average_score_row["avgdp"]).")*(dp-".($average_score_row["avgdp"]==""?0:$average_score_row["avgdp"])."))/".$average_score_row["numscores"].") AS std_dev FROM score WHERE newest=1 AND stepsid=".$steps_row["id"]) or die(mysql_error());
					$std_dev_row=mysql_fetch_array($std_dev_result);
					mysql_query("UPDATE steps SET avg_score=".$average_score_row["avgdp"].", scores=".$average_score_row["numscores"].", std_dev=".$std_dev_row["std_dev"]." WHERE id=".$steps_row["id"]) or die(mysql_error());
					
					echo "Your score for ".song_name_span($steps_row["name"], $steps_row["subname"], $steps_row["difficulty"], $steps_row["mode"])." was successfully entered.<br>\n";
					echo "Your score is ".$dp."/".$steps_row["dp"]."DP (".$percent."%).<br>\n";
					echo "That's a new ".$lettergrade.".<br>\n";
					echo "Your rank is ".user_rank_on_song($_COOKIE["loggedin"], $steps_row["id"])."/".($steps_row["scores"]+1).".<br>\n";
				}
			}
		}
	}
}
elseif(isset($songselect))
{
	echo "<form name=\"form1\" action=\"submitscore.php\" method=\"post\">\n";
	
	$stepsid_result=mysql_query("SELECT id FROM steps ORDER BY sortname") or die(mysql_error());
	while($stepsid_row=mysql_fetch_array($stepsid_result))
	{
		if($_POST["hid".$stepsid_row["id"]]=="1")
		{
			$steps_result=mysql_query("SELECT song.name, song.subname, song.banner, steps.difficulty, steps.mode, steps.id, steps.dp, steps.freezes, steps.maxcombo FROM song, steps WHERE song.id=steps.songid AND steps.id=".$stepsid_row["id"]) or die(mysql_error());
			$steps_row=mysql_fetch_array($steps_result);
			
			$score_result=mysql_query("SELECT * FROM score WHERE userid=".$_COOKIE["loggedin"]." AND stepsid=".$steps_row["id"]." AND newest=1") or die(mysql_error());
			$score_row=mysql_fetch_array($score_result);
			
			echo "<input type=\"hidden\" name=\"hid".$steps_row["id"]."\" value=\"true\">\n";
			echo "<table class=\"empty borderall\">\n";
			echo "<tr><td colspan=\"6\"><span class=\"forered major\">".$steps_row["name"]."</span>".(isset($steps_row["subname"])?"<span class=\"forered sub\">".$steps_row["subname"]."</span>":"")." <span class=\"foreblack major\">".($steps_row["difficulty"]=="beg"?"Beginner":($steps_row["difficulty"]=="lig"?"Light":($steps_row["difficulty"]=="sta"?"Standard":($steps_row["difficulty"]=="hea"?"Heavy":"Challenge"))))." ".($steps_row["mode"]=="singles"?"Single":"Double")."</span></td></tr>\n";
			echo "<tr><td>P: <input type=\"text\" name=\"perfects".$steps_row["id"]."\" size=\"3\" maxlength=\"3\"".($score_row["id"]==NULL?"":" value=\"".$score_row["perfects"]."\"").">Auto<input type=\"checkbox\" name=\"autop".$steps_row["id"]."\" value=\"1\" checked></td><td>G: <input type=\"text\" name=\"greats".$steps_row["id"]."\" size=\"3\" maxlength=\"3\"".($score_row["id"]==NULL?"":" value=\"".$score_row["greats"]."\"")."></td><td>G: <input type=\"text\" name=\"goods".$steps_row["id"]."\" size=\"3\" maxlength=\"3\"".($score_row["id"]==NULL?"":" value=\"".$score_row["goods"]."\"")."></td><td>B: <input type=\"text\" name=\"boos".$steps_row["id"]."\" size=\"3\" maxlength=\"3\"".($score_row["id"]==NULL?"":" value=\"".$score_row["boos"]."\"")."></td><td>M: <input type=\"text\" name=\"misses".$steps_row["id"]."\" size=\"3\" maxlength=\"3\"".($score_row["id"]==NULL?"":" value=\"".$score_row["misses"]."\"")."></td><td>OK: <input type=\"text\" name=\"oks".$steps_row["id"]."\" size=\"3\" maxlength=\"3\"".($score_row["id"]==NULL?"":" value=\"".$score_row["oks"]."\"").">Auto<input type=\"checkbox\" name=\"autof".$steps_row["id"]."\" value=\"1\" checked></td></tr>\n";
			echo "<tr><td>DP: <input type=\"text\" name=\"dp".$steps_row["id"]."\" size=\"3\" maxlength=\"4\"></td><td>Combo: <input type=\"text\" name=\"combo".$steps_row["id"]."\" size=\"3\" maxlength=\"4\"".($score_row["id"]==NULL?"":" value=\"".$score_row["combo"]."\"")."></td><td colspan=\"2\">Comment: <input type=\"text\" name=\"comment".$steps_row["id"]."\" size=\"16\" maxlength=\"25\"".($score_row["id"]==NULL?"":" value=\"".$score_row["comment"]."\"")."></td><td colspan=\"2\">Picture: <input type=\"text\" name=\"pic".$steps_row["id"]."\" size=\"16\" maxlength=\"100\"".($score_row["id"]==NULL?"":" value=\"".$score_row["picture"]."\"")."></td></tr>\n";
			echo "</table><br>\n";
			
		}
	}
	
	echo "<input type=\"submit\" name=\"scoresubmit\" value=\"submit\">\n";
	echo "</form>\n";
}
echo "</body>\n";
echo "</html>";
?>