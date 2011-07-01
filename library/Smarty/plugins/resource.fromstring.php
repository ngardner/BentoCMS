<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.fromstring.php
 * Type:     resource
 * Name:     fromstring
 * Purpose:  Allows you to parse a string as a template and have it parsed
 * By: Nathan Gardner <nathan@factory8.com>
 * -------------------------------------------------------------
 */
function smarty_resource_fromstring_source($template_code, &$tpl_source, &$smarty) {
	
	//populate tpl_source
	$tpl_source = $template_code;
	return true;
	
}

function smarty_resource_fromstring_timestamp($template_id, &$tpl_timestamp, &$smarty) {
	
	//populate tpl_timestamp
	$tpl_timestamp = date("Y-m-d H:i:s"); // what is this for? caching logic??
	return true;
	
}

function smarty_resource_fromstring_secure($tpl_name, &$smarty) {
	
	// assume all templates are secure
	return true;
	
}

function smarty_resource_fromstring_trusted($tpl_name, &$smarty) {
	
	// not used for templates
	return true;
	
}
?>
