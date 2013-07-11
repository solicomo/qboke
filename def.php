<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-18
 * */

/** Define VERSION */
define( 'VERSION', '2' );

/** Define VER_STR */
define( 'VER_STR', '0.0.2' );

/** Define ABSPATH as this file's directory */
define( 'ABSPATH', dirname(__FILE__) );

/** Define INC_DIR */
define( 'INC_DIR', ABSPATH . '/include' );

/** Define LANG_DIR */
define( 'LANG_DIR', ABSPATH . '/lang' );

/** Define DATA_DIR */
define( 'DATA_DIR', ABSPATH . '/data' );

/** Define DATA_SAMPLE_DIR */
define( 'DATA_SAMPLE_DIR', ABSPATH . '/data_sample' );

/** Define PLUGINS_DIR */
define( 'PLUGINS_DIR', ABSPATH . '/plugins' );

/** Define THEMES_DIR */
define( 'THEMES_DIR', ABSPATH . '/themes' );

/** Define CONVERTORS_DIR */
define( 'CONVERTORS_DIR', ABSPATH . '/convertors' );

/** Define SCMS_DIR */
define( 'SCMS_DIR', ABSPATH . '/scms' );

/** Define CACHE_DIR */
define( 'CACHE_DIR', ABSPATH . '/cache' );

/** Define LANG */
define('LANG', 'en_US');

/** Define TZ */
define('TZ', 'UTC');

date_default_timezone_set(TZ);

?>
