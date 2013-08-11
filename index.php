<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-05
 * */

require 'vendor/autoload.php';
require_once 'def.php';
require_once INC_DIR . '/cache.php';

// cache
//TODO:
//if( get_cache() ) {
//	exit;
//}

// re-generate
require_once INC_DIR . '/functions.php';
require_once INC_DIR . '/l10n.php';

if (!load_config()) {
	header("Status: 500 Internal Server Error");
	exit('invalid config.php');
}

load_scms();
//sync_content();
load_default_textdomain();
load_plugins();
load_themes();
load_convertors();
load_sites(false);
$site = get_site($_SERVER['HTTP_HOST']);

if ($site === false) {
	header("Status: 500 Internal Server Error");
	exit('Internal Server Error');
}
$site->load();
$site->get(urldecode($_SERVER['REQUEST_URI']));

// cache
//TODO:
//put_cache();

