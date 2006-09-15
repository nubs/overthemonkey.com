<?php

function otm_build_page_compile($template_page)
{
	global $request, $otm_template;

	$code = file_get_contents("templates/{$request["user"]["theme"]}/$template_page.tpl");

	preg_match_all('/\{([a-z0-9\-_]+)\.([a-z0-9\-_]+)\}/is', $code, $varrefs, PREG_SET_ORDER);
	foreach($varrefs as $varref)
	{
		$var = "\$otm_template['{$varref[1]}'][\$_{$varref[1]}_i]['{$varref[2]}']";
		$repl = "'.(isset($var) ? $var : '').'";
		$code = str_replace($varref[0], $repl, $code);
	}

	$code = preg_replace('/\{([a-z0-9\-_]*?)\}/is', '\'.(isset($otm_template[\'\1\'])?$otm_template[\'\1\']:\'\').\'', $code);

	$code_lines = explode("\n", $code);
	for($i=0; $i<count($code_lines); $i++)
	{
		if(preg_match('/<!-- INCLUDE (.*?) -->/', $code_lines[$i], $m))
			$code_lines[$i] = "eval('".addcslashes(otm_build_page_compile($m[1]), "'\\")."');";
		else if(preg_match('/<!-- BEGIN (.*?) -->/', $code_lines[$i], $m))
			$code_lines[$i] = "\$_{$m[1]}_count = (isset(\$otm_template['{$m[1]}'])?sizeof(\$otm_template['{$m[1]}']):0);\nfor(\$_{$m[1]}_i=0; \$_{$m[1]}_i<\$_{$m[1]}_count; \$_{$m[1]}_i++){";
		else if(preg_match('/<!-- END (.*?) -->/', $code_lines[$i]))
			$code_lines[$i] = '}';
		else
			$code_lines[$i] = "echo '{$code_lines[$i]}'.\"\\n\";";
	}

	return implode("\n", $code_lines);
}

# vim: set ft=php sw=4 ts=4 :
?>
