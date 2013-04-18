<?php
/**
 * author: Soli <soli@qq.com>
 * date  : 2013-04-05
 * */

/** Define ABSPATH as this file's directory */
define( 'ABSPATH', dirname(__FILE__) . '/' );

/** Define INC_DIR */
define( 'INC_DIR', ABSPATH . 'includes' );

/** Define LANG_DIR */
define( 'LANG_DIR', ABSPATH . 'lang' );

/** Define DATA_DIR */
define( 'DATA_DIR', ABSPATH . 'data' );

/** Define DATA_SAMPLE_DIR */
define( 'DATA_SAMPLE_DIR', ABSPATH . 'data_sample' );

/** Define PLUGINS_DIR */
define( 'PLUGINS_DIR', ABSPATH . 'plugins' );

/** Define THEMES_DIR */
define( 'THEMES_DIR', ABSPATH . 'themes' );

/** Define LANG */
define('LANG', 'en_US');

require( INC_DIR . '/pomo/mo.php' );
?>
