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

$srv  = print_r($_SERVER, true);
qb_notice("got sync request:\n" . $srv);

if (!authorize()) {
	qb_warn('sync request forbidden.');
	header('Status: 403 Forbidden');
	exit('403 Forbidden');
}

load_scms();
sync_content();
load_default_textdomain();
load_plugins();
load_themes();
load_convertors();

$site = load_site();

if ($site === false) {
	qb_warn('load site for sync failed.');
	header("Status: 500 Internal Server Error");
	exit('load site failed.');
}

if ($site->dump() === false) {
	qb_warn('dump site for sync failed.');
	header("Status: 500 Internal Server Error");
	exit('dump site failed.');
}

echo 'done';
qb_notice('sync done.');

