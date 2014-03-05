<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-11-08
 * */

function set_debug_mode() {
	global $g_config;
	error_reporting( E_ALL );

	if ($g_config['debug'] === 'on') {
		ini_set( 'display_errors', 1 );
	} else {
		ini_set( 'display_errors', 0 );
	}

	ini_set( 'log_errors', 1 );
	ini_set( 'error_log', CACHE_DIR . '/qboke.log' );
}

function qb_error($msg) {
	return trigger_error(date('[Y-m-d H:i:s] ') . $msg, E_USER_ERROR);
}

function qb_warn($msg) {
	return trigger_error(date('[Y-m-d H:i:s] ') . $msg, E_USER_WARNING);
}

function qb_notice($msg) {
	return trigger_error(date('[Y-m-d H:i:s] ') . $msg, E_USER_NOTICE);
}

function qb_deprecated($msg) {
	return trigger_error(date('[Y-m-d H:i:s] ') . $msg, E_USER_DEPRECATED);
}

