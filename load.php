<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-02-27
 * */

require 'vendor/autoload.php';

/** Define ABSPATH as this file's directory */
define( 'ABSPATH', dirname(__FILE__) );

require_once __DIR__ . '/include/def.php';
require_once INC_DIR . '/global.php';
require_once INC_DIR . '/functions.php';
require_once INC_DIR . '/l10n.php';

if (!load_config()) {
	header("Status: 500 Internal Server Error");
	exit('invalid config.php');
}

set_debug_mode();
