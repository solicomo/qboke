<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-18
 * */

require_once CONVERTORS_DIR . '/convertor.php';

/** declare */
global $g_settings;
global $g_index;
global $g_convertors;
global $g_convertor_none;
global $g_hooks;

global $g_home_url;
global $g_req_type;	// 'index', 'tag', 'post'
global $g_req_value;
global $g_req_page;

global $g_posts; // array of post index in $g_index
global $g_page_cnt;

/** init */
if ( !isset($g_convertor_none) ) {
	$g_convertor_none = new Convertor;;
}

/** get and set */
function get_req_type() {
	global $g_req_type;
	return $g_req_type;
}

function get_req_value() {
	global $g_req_value;
	return $g_req_value;
}

function get_req_page() {
	global $g_req_page;
	return $g_req_page;
}

function get_page_count() {
	global $g_page_cnt;
	return $g_page_cnt;
}
?>