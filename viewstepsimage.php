<?PHP
include("functions.php");
include("jpgraph/src/jpgraph.php");
include("jpgraph/src/jpgraph_bar.php");

setup_mysql();

$steps_result=mysql_query("SELECT * FROM steps WHERE id=".$id) or die(mysql_error());
$steps_row=mysql_fetch_array($steps_result);

$min_score_result=mysql_query("SELECT MIN(dp) AS mindp FROM score WHERE stepsid=".$steps_row["id"]." AND newest=1") or die(mysql_error());
$min_score_row=mysql_fetch_array($min_score_result);

$dprange=$steps_row["dp"]-$min_score_row["mindp"]+1;
$datay=array();
$datax=array();

$k=0;
$barwidth=min(max(floor(800/$dprange), 4),15);
$dpperbar=ceil($dprange*$barwidth/800);
$numbars=$dprange/$dpperbar;
for($i=$min_score_row["mindp"];$i<=$steps_row["dp"];$i+=$dpperbar)
{
	$query="SELECT COUNT(dp) AS num FROM score WHERE stepsid=".$steps_row["id"]." AND newest=1 AND (";
	for($j=0;$j<$dpperbar;$j++)
	{
		if($j!=0)
		{
			$query=$query." OR ";
		}
		
		$query=$query."dp=".($i+$j);
	}
	$query=$query.")";
	
	$score_count_result=mysql_query($query) or die(mysql_error());
	$score_count_row=mysql_fetch_array($score_count_result);
	
	$datay[$k]=$score_count_row["num"];
	$datax[$k]=$i;
	$k++;
}

$graph = new Graph(180, $numbars*$barwidth+40, "auto", 60);    
$graph->SetScale("textlin");

$graph->Set90AndMargin(30,10,35,5);

$graph->SetMarginColor("#EAEAEA");
$graph->SetFrame(true, "#EAEAEA");
$graph->SetColor("#EAEAEA");
$graph->ygrid->SetColor("#000000");

//$graph->yaxis->SetLabelFormat('%0.0f'); 

$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetTextLabelInterval(ceil($numbars/60));
$graph->xaxis->HideTicks();

$bplot = new BarPlot($datay);

$bplot->SetFillColor('orange');
$bplot->SetWidth(1.0);
$graph->Add($bplot);

$cline = new PlotLine(VERTICAL,(.45*$steps_row["dp"]-$min_score_row["mindp"])/$dpperbar-(.45*$steps_row["dp"]<$min_score_row["mindp"]?100:0),"purple",2);
$bline = new PlotLine(VERTICAL,(.65*$steps_row["dp"]-$min_score_row["mindp"])/$dpperbar-(.65*$steps_row["dp"]<$min_score_row["mindp"]?100:0),"darkblue",2);
$aline = new PlotLine(VERTICAL,(.8*$steps_row["dp"]-$min_score_row["mindp"])/$dpperbar-(.8*$steps_row["dp"]<$min_score_row["mindp"]?100:0),"darkgreen",2);
$aaline = new PlotLine(VERTICAL,(.93*$steps_row["dp"]-$min_score_row["mindp"])/$dpperbar-(.93*$steps_row["dp"]<$min_score_row["mindp"]?100:0),"lightgreen",2);
$graph->Add($cline);
$graph->Add($bline);
$graph->Add($aline);
$graph->Add($aaline);

$graph->yaxis->title->Set("Number of Scores");
$graph->yaxis->title->SetAngle(0);
$graph->yaxis->title->SetAlign("center");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->Stroke();
?>