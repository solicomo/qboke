<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-08-10
 * */

// Ignore user aborts and allow the script
// to run forever
ignore_user_abort(true);
set_time_limit(0);

require_once __DIR__ . '/load.php';

trigger_error("got sync request from {$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']}", E_USER_NOTICE);

if (!authorize()) {
	trigger_error('sync request forbidden.', E_USER_WARNING);
	header('Status: 403 Forbidden');
	exit('403 Forbidden');
}

load_scms();
sync_content();
load_default_textdomain();
load_plugins();
load_themes();
load_parsers();

$site = load_site();

if ($site === false) {
	trigger_error('load site for sync failed.', E_USER_WARNING);
	header("Status: 500 Internal Server Error");
	exit('load site failed.');
}

if ($site->dump() === false) {
	trigger_error('dump site for sync failed.', E_USER_WARNING);
	header("Status: 500 Internal Server Error");
	exit('dump site failed.');
}

echo 'done';
trigger_error('sync done.', E_USER_NOTICE);

