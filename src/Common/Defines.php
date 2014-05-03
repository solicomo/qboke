<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-18
 * */
namespace QBoke\Common\Defines;

/** Define VERSION */
define( 'VERSION', '9' );

/** Define VER_STR */
define( 'VER_STR', '0.0.9' );

/** Define SRC_DIR */
define( 'SRC_DIR', ABSPATH . '/src' );

/** Define INC_DIR */
define( 'INC_DIR', SRC_DIR . '/include' );

/** Define LANG_DIR */
define( 'LANG_DIR', INC_DIR . '/lang' );

/** Define DATA_DIR */
define( 'DATA_DIR', ABSPATH . '/data' );

/** Define DATA_SAMPLE_DIR */
define( 'DATA_SAMPLE_DIR', ABSPATH . '/data_sample' );

/** Define PLUGIN_DIR */
define( 'PLUGIN_DIR', SRC_DIR . '/Plugin' );

/** Define THEME_DIR */
define( 'THEME_DIR', SRC_DIR . '/Theme' );

/** Define PARSER_DIR */
define( 'PARSER_DIR', SRC_DIR . '/Parser' );

/** Define SCM_DIR */
define( 'SCM_DIR', SRC_DIR . '/SCM' );

/** Define CACHE_DIR */
define( 'CACHE_DIR', ABSPATH . '/cache' );

/** Define PUBLIC_DIR */
define( 'PUBLIC_DIR', ABSPATH . '/public' );

/** Define LANG */
define('LANG', 'en_US');

/** Define TZ */
define('TZ', 'UTC');

date_default_timezone_set(TZ);

