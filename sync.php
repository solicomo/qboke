<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-08-10
 * */

// Ignore user aborts and allow the script
// to run forever
ignore_user_abort(true);
set_time_limit(0);

require_once 'def.php';
require_once INC_DIR . '/cache.php';
require_once INC_DIR . '/functions.php';
require_once INC_DIR . '/l10n.php';

if (!load_config()) {
	header('Status: 500 Internal Server Error');
	exit('invalid config.php');
}

if (!authorize()) {
	header('Status: 403 Forbidden');
	exit('403 Forbidden');
}

load_scms();
sync_content();
load_default_textdomain();
load_plugins();
load_themes();
load_convertors();
load_sites(true);

echo 'done';
// cache
//TODO:
//put_cache();

