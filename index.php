<?php
/**
 * author: Soli <soli@qq.com>
 * date  : 2013-04-05
 * */

require_once( 'def.php' );
require_once( INC_DIR . 'cache.php' );

// cache
if( get_cache() ) {
	exit;
}

// re-generate
require_once( INC_DIR . 'functions.php' );
require_once( INC_DIR . 'l10n.php' );

load_settings();
load_default_textdomain();
load_index();
load_plugins();
load_theme();
load_convertors();
parse_uri();
$g_theme->render();

// cache
put_cache();

?>
