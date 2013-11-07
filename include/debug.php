<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-11-08
 * */

define('QB_DEBUG_DISPLAY', 1);
define('QB_DEBUG_LOG',     2);

function set_debug_mode($mode, $log_file = false) {

	if ($mode === 0) {
		error_reporting( 0 );
		return;
	}

	error_reporting( E_ALL );

	if ($mode & QB_DEBUG_DISPLAY) {
		ini_set( 'display_errors', 1 );
	} else {
		ini_set( 'display_errors', 0 );
	}

	if ($mode & QB_DEBUG_LOG and strlen(trim($log_file)) > 0) {
		ini_set( 'log_errors', 1 );
		ini_set( 'error_log', trim($log_file) );
	} else {
		ini_set( 'log_errors', 0 );
	}
}

function qb_error($msg) {
	return trigger_error($msg, E_USER_ERROR);
}

function qb_warn($msg) {
	return trigger_error($msg, E_USER_WARNING);
}

function qb_notice($msg) {
	return trigger_error($msg, E_USER_NOTICE);
}

function qb_deprecated($msg) {
	return trigger_error($msg, E_USER_DEPRECATED);
}

