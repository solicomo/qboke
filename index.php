<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-05
 * */

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

load_config();
load_scms();
//sync_content();
load_default_textdomain();
load_plugins();
load_themes();
load_convertors();
load_sites();

$site = get_site($_SERVER['HTTP_HOST']);

if ($site === false) {
	header("Status: 500 Internal Server Error");
	exit('Internal Server Error');
}

$site->load();
$site->get($_SERVER['REQUEST_URI']);

// cache
//TODO:
//put_cache();

?>
