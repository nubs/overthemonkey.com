<?php

eval(otm_build_page_compile($request["page"]));

otm_timer($request["page"]);
otm_timer("page");

if($request["env"] != "live")
{
	$functions = get_defined_functions();
	$functions = $functions["user"];
	$includes = get_included_files();
	$vars = array("time", "request", "otm_template", "functions", "includes", "_SESSION", "_SERVER", "get", "post", "cookies");

	echo "<script type=\"text/javascript\" src=\"{$otm_template["directory"]}javascript/functions.js\"></script>\n";
	echo "<pre style=\"clear:both\">\n";

	foreach($vars as $var)
		echo (empty(${$var})?"":"<a href=\"javascript:void()\" onclick=\"expand('debug_$var')\">\$$var:</a>\n<span id=\"debug_$var\" style=\"display:none\">".print_r(${$var}, true)."</span>");

	echo "</pre>\n";
}

# vim: set ft=php sw=4 ts=4 :
?>
