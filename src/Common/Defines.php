<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-18
 * */
namespace QBoke\Common\Defines;


define( 'VERSION', '10' );
define( 'VER_STR', '0.0.9' );

define( 'CACHE_DIR', ABSPATH . '/cache' );
define( 'DATA_DIR', ABSPATH . '/data' );
define( 'PUBLIC_DIR', ABSPATH . '/public' );

define( 'QB_ROOT', dirname(dirname(__DIR__)));

define( 'QB_SRC_DIR', QB_ROOT . '/src');
define( 'LANG_DIR', QB_SRC_DIR . '/L10N/lang' );
define( 'PLUGIN_DIR', QB_SRC_DIR . '/Plugin' );
define( 'THEME_DIR', QB_SRC_DIR . '/Theme' );
define( 'PARSER_DIR', QB_SRC_DIR . '/Parser' );
define( 'SCM_DIR', QB_SRC_DIR . '/SCM' );

define('LANG', 'en_US');
define('TZ', 'UTC');

date_default_timezone_set(TZ);
