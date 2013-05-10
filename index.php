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

load_settings();
load_default_textdomain();
load_index();
load_plugins();
load_theme();
load_convertors();
parse_uri();
prepare_posts();
get_theme()->render();

// cache
//TODO:
//put_cache();

?>
