<?PHP
function setup_mysql()
{
	$host="";
	$dbuser="";
	$dbpassword="";
	$dbname="";
	mysql_connect("$host","$dbuser","$dbpassword");
	mysql_select_db("$dbname");
}

function print_header()
{
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<script type=\"text/javascript\" src=\"js.js\"></script>\n";
	echo "<title>Over The Monkey v2.0</title>\n";
	echo "</head>\n";
	echo "<body class=\"backgray2\">\n";

	if(isset($_COOKIE["loggedin"]))
	{
		$user_result=mysql_query("SELECT * FROM user WHERE id=".$_COOKIE["loggedin"]) or die(mysql_error());
		$user_row=mysql_fetch_array($user_result);
		
		if($user_row["status"]=="banned")
		{
			echo "<table>\n";
			echo "<tr><th class=\"backgray0\">Banned</th></tr>\n";
			echo "<td class=\"backgray1\">You have been banned.</td></tr>\n";
			echo "</table>\n";
			
			setcookie("loggedin", FALSE, time()-600);
			
			die();
		}
		//LOGGED IN
	}
	else
	{
		//NOT LOGGED IN
	}
}

function refresh_header()
{
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0\">\n";
	echo "<title>Over The Monkey v2.0</title>\n";
	echo "</head>\n";
	echo "<body>\n";
}

function print_steps_square($stepsid, $start_date, $end_date)
{
	if($stepsid==NULL)
	{
		echo "<td colspan=\"2\">&nbsp;</td>";
	}
	else
	{
		$steps_result=mysql_query("SELECT * FROM steps WHERE id=".$stepsid) or die(mysql_error());
		$steps_row=mysql_fetch_array($steps_result);
		$score_result=mysql_query("SELECT dp FROM score WHERE userid=".(int)$_COOKIE["loggedin"]." AND stepsid=".$stepsid." AND date>FROM_UNIXTIME(\"".$start_date."\") AND date<FROM_UNIXTIME(\"".$end_date."\") AND newest=1") or die(mysql_error());
		$score_row=mysql_fetch_array($score_result);
		
		echo "<th class=\"selectable\" onclick=\"check(this, document.form1.hid".$stepsid.")\"><input name=\"hid".$stepsid."\" type=\"hidden\" value=\"0\">&nbsp;</th><td class=\"song\" onMouseOver=this.style.backgroundColor=\"#CCCCF0\" onMouseOut=this.style.backgroundColor=\"\" onClick=window.location.href=\"viewsteps.php?id=".$stepsid."\"><a href=\"viewsteps.php?id=".$stepsid."\">".(isset($score_row["dp"])?"<span class=\"blue\">".$score_row["dp"]."/".$steps_row["dp"]." DP</span>":"<span class=\"red\">".$steps_row["dp"]." DP</span>")."<br>".$steps_row["scores"]." Scores</a></td>";
	}
}

function print_steps_info($id)
{
	if($id==NULL)
	{
		return;
	}
	$steps_result=mysql_query("SELECT * FROM steps WHERE id=".$id) or die(mysql_error());
	if(mysql_num_rows($steps_result)==0)
	{
		return;
	}
	
	$steps_row=mysql_fetch_array($steps_result);
	
	$song_result=mysql_query("SELECT * FROM song WHERE id=".$steps_row["songid"]) or die(mysql_error());
	$song_row=mysql_fetch_array($song_result);
	
	$lettergrade_result=mysql_query("SELECT COUNT(*) AS num, lettergrade FROM score WHERE stepsid=".$id." AND newest=1 GROUP BY lettergrade") or die(mysql_error());
	while($lettergrade_row=mysql_fetch_array($lettergrade_result))
	{
		$lettergrade[$lettergrade_row["lettergrade"]]=$lettergrade_row["num"];
	}
	
	echo "<table class=\"empty\">\n";
	echo "<tr><td colspan=\"2\">".song_name_span($song_row["name"], $song_row["subname"], $steps_row["difficulty"], $steps_row["mode"])."</td><td class=\"right\" width=\"256\" rowspan=\"3\"><img src=\"Images/Banners/Songs/".$song_row["banner"]."\"></td></tr>\n";
	echo "<tr><td colspan=\"2\">by <span class=\"foreblack\">".$song_row["artist"]."</span></td></tr>\n";
	echo "<tr><td>".$song_row["bpmlow"].(isset($song_row["bpmhigh"])?"-".$song_row["bpmhigh"]:"")." BPM</td><td class=\"right\"><a href=\"".$steps_row["stepchart"]."\">Stepchart</a></td></tr>\n";
	echo "<tr><td><table class=\"empty big borderall\"><tr><td class=\"right\">".$steps_row["steps"]."</td><td>Steps</td></tr><tr><td class=\"right\">".$steps_row["freezes"]."</td><td>Freezes</td></tr><tr><td class=\"right\">".$steps_row["dp"]."</td><td>DP</td></tr><tr><td class=\"right\">".$steps_row["maxcombo"]."</td><td>Combo</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table></td>\n";
	echo "<td><table class=\"empty big borderall\"><tr><td class=\"right\">".$steps_row["scores"]."</td><td>Scores</td></tr><tr><td class=\"right\">".$steps_row["avg_score"]."</td><td>DP Average</td></tr><tr><td class=\"right\">&nbsp;</td><td>&nbsp;</td></tr><tr><td class=\"right\">&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table></td>\n";
	echo "<td><table class=\"empty big borderall\"><tr><td>AAA</td><td class=\"right\">".$steps_row["dp"]."/".$steps_row["dp"]."</td><td>[0]</td><td class=\"right\">".(int)$lettergrade["AAA"]."</td><td>Scores</td></tr><tr><td>AA</td><td class=\"right\">".ceil($steps_row["dp"]*.93)."/".$steps_row["dp"]."</td><td>[".(ceil($steps_row["dp"]*.93)-$steps_row["dp"])."]</td><td class=\"right\">".(int)$lettergrade["AA"]."</td><td>Scores</td></tr><tr><td>A</td><td class=\"right\">".ceil($steps_row["dp"]*.80)."/".$steps_row["dp"]."</td><td>[".(ceil($steps_row["dp"]*.80)-$steps_row["dp"])."]</td><td class=\"right\">".(int)$lettergrade["A"]."</td><td>Scores</td></tr><tr><td>B</td><td class=\"right\">".ceil($steps_row["dp"]*.65)."/".$steps_row["dp"]."</td><td>[".(ceil($steps_row["dp"]*.65)-$steps_row["dp"])."]</td><td class=\"right\">".(int)$lettergrade["B"]."</td><td>Scores</td></tr><tr><td>C</td><td class=\"right\">".ceil($steps_row["dp"]*.45)."/".$steps_row["dp"]."</td><td>[".(ceil($steps_row["dp"]*.45)-$steps_row["dp"])."]</td><td class=\"right\">".(int)$lettergrade["C"]."</td><td>Scores</td></tr><tr><td>D</td><td class=\"right\">0/".$steps_row["dp"]."</td><td>[-".$steps_row["dp"]."]</td><td class=\"right\">".(int)$lettergrade["D"]."</td><td>Scores</td></tr></table></td></tr>\n";
	echo "</table><br>\n";
}

function song_name_span($name, $subname, $difficulty, $mode)
{
	return "<span class=\"forered major\">".$name."</span>".(isset($subname)?"<span class=\"forered sub\">".$subname."</span>":"")." <span class=\"foreblack major\">".($difficulty=="beg"?"Beginner":($difficulty=="lig"?"Light":($difficulty=="sta"?"Standard":($difficulty=="hea"?"Heavy":"Challenge"))))." ".($mode=="singles"?"Single":"Double")."</span>";
}

function user_name($name, $id, $status)
{
	$arr_status=array("admin"=>"$", "mod"=>"+", "normal"=>"~", "warned"=>"!", "banned"=>"`");
	
	return "<a href=\"viewuser.php?id=".$id."\">".$arr_status[$status].htmlspecialchars($name)."</a>";
}

function user_rank_on_song($userid, $stepsid)
{
	$user_score_result=mysql_query("SELECT dp FROM score WHERE stepsid=".$stepsid." AND userid=".$userid." AND newest=1") or die(mysql_error());
	$user_score_row=mysql_fetch_array($user_score_result);
	
	$score_result=mysql_query("SELECT dp FROM score WHERE stepsid=".$stepsid." AND dp>".$user_score_row["dp"]." AND newest=1") or die(mysql_error());
	
	return mysql_num_rows($score_result)+1;
}

function parse_sm_file($filename)
{
	$contents=file_get_contents($filename);
	preg_match_all("|#([^:]+):([^;]*);|U", $contents, $matches, PREG_PATTERN_ORDER);
	
	foreach($matches[1] as $match_number => $match_title)
	{
		$match_data=$matches[2][$match_number];
		if($match_title=="TITLE")
		{
			$title=$match_data;
		}
		elseif($match_title=="SUBTITLE")
		{
			$subtitle=$match_data;
		}
		elseif($match_title=="ARTIST")
		{
			$artist=$match_data;
		}
		elseif($match_title=="TITLETRANSLIT")
		{
			$title_translit=$match_data;
		}
		elseif($match_title=="SUBTITLETRANSLIT")
		{
			$subtitle_translit=$match_data;
		}
		elseif($match_title=="ARTISTTRANSLIT")
		{
			$artist_translit=$match_data;
		}
		elseif($match_title=="BPMS")
		{
			preg_match_all("|([^=]+)=([^,]*),|U", $match_data.",", $beats, PREG_PATTERN_ORDER);
			$min=1000;
			$max=0;
			foreach($beats[2] as $beat)
			{
				$min=($beat<$min?$beat:$min);
				$max=($beat>$max?$beat:$max);
			}
		}
		elseif($match_title=="NOTES")
		{
			
			list($notes_type, $description, $difficulty_class, $difficulty_meter, $radar_values, $note_data) = explode(":", $match_data);
			
			$notes_type=trim($notes_type);
			$description=trim($description);
			$difficulty_class=trim($difficulty_class);
			$difficulty_meter=trim($difficulty_meter);
			$radar_values=trim($radar_values);
			$note_data=trim($note_data);
			
			if($notes_type=="dance-single"||$notes_type=="dance-double")
			{
				$notes_type=($notes_type=="dance-single"?"singles":"doubles");
				$difficulty_class=($difficulty_class=="beginner"?"beg":($difficulty_class=="easy"?"lig":($difficulty_class=="medium"?"sta":($difficulty_class=="hard"?"hea":"oni"))));
				$num_steps=$num_freezes=$combo=0;
				
				$measures=explode(",", $note_data);
				foreach($measures as $measure)
				{
					$lines=explode("\n", $measure);
					foreach($lines as $line)
					{
						$one_count=substr_count($line, "1");
						$two_count=substr_count($line, "2");
						
						if($one_count+$two_count>0)
						{
							$num_steps++;
						}
						if($two_count>0)
						{
							$num_freezes++;
						}
						$combo+=($one_count+$two_count);
					}
				}
				
				list($stream, $voltage, $air, $freeze, $chaos)=explode(",", $radar_values);
				$steps[]=array("mode" => $notes_type, "diff" => $difficulty_class, "feet" => $difficulty_meter, "num_steps" => $num_steps, "num_freezes" => $num_freezes, "combo" => $combo, "stream" => $stream, "voltage" => $voltage, "air" => $air, "freeze" => $freeze, "chaos" => $chaos);
			}
		}
	}
	
	$song=array("title" => ($title_translit==""?$title:$title_translit), "subtitle" => ($subtitle_translit==""?$subtitle:$subtitle_translit), "artist" => ($artist_translit==""?$artist:$artist_translit), "bpmlow" => $min, "bpmhigh" => $max, "steps" => $steps);
	return $song;
}
?>
