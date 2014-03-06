<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-04-05
 * */

require_once INC_DIR . '/site.php';
require_once INC_DIR . '/post.php';

function load_config() {
	global $g_config;
	$path = ABSPATH . '/config.php';
	if( is_readable($path) ) {
		$g_config = include_once $path;
		if ( $g_config === null) {
			return false;
		}
		return true;
	}

	$path = ABSPATH . '/config_sample.php';
	if( is_readable($path) ) {
		$g_config = include_once $path;
		if ( $g_config === null) {
			return false;
		}
		return true;
	}

	return false;
}

function load_themes() {
	foreach( get_subdirs( THEMES_DIR ) as $theme ) {
		include_once THEMES_DIR . "/$theme/_.php";
	}
}

function load_plugins() {
	foreach( get_subdirs( PLUGINS_DIR ) as $plugin ) {
		include_once PLUGINS_DIR . "/$plugin/_.php";
	}
}

function load_scms() {
	foreach( get_subdirs( SCMS_DIR ) as $scm ) {
		include_once SCMS_DIR . "/$scm/_.php";
	}
}

function load_convertors() {
	foreach( get_subdirs( CONVERTORS_DIR ) as $cvt ) {
		include_once CONVERTORS_DIR . "/$cvt/_.php";
	}
}

function set_debug_mode() {
	global $g_config;
	error_reporting( E_ALL );

	if ($g_config['debug'] === 'on') {
		ini_set( 'display_errors', 1 );
	} else {
		ini_set( 'display_errors', 0 );
	}

	ini_set( 'log_errors', 1 );
	ini_set( 'error_log', CACHE_DIR . '/qboke.log' );
}

function authorize() {
	global $g_config;
	if ($_GET['key'] === $g_config['key']) {
		return true;
	}
	return false;
}

function sync_content() {
	global $g_config;

	$repo = $g_config['repo'];
	$name = $repo['type'];
	$path = get_data_path();

	$scm = get_scm($name);

	if ($scm === false) {
		continue;
	}

	if (!$scm->init($path, $repo)) {
		continue;
	}

	$scm->pull();
}

function load_site() {
	$path = get_data_path();
	$site = new QBSite($path);

	if ($site->load()) {
		return $site;
	}

	return false;
}

/********************************/

/**
 * Get data directory.
 *
 * @return string|bool The data directory (without '/') or false if the data directory is not readable.
 * */
function get_data_path() {
	if( is_readable(DATA_DIR) ) {
		return DATA_DIR;
	}

	if( is_readable(DATA_SAMPLE_DIR) ) {
		return DATA_SAMPLE_DIR;
	}

	return false;
}

function get_subdirs($path) {
	$subs = array();
	if( !is_dir( $path ) ) {
		return $subs;
	}

	if( ! $dh = opendir( $path ) ) {
		return $subs;
	}

	while ( ( $sub = readdir( $dh ) ) !== false ) {
		if ( $sub != '.' && $sub != '..' ) {
			if( is_dir( "$path/$sub" ) ) {
				$subs[] = $sub;
			}
		}
	}

	closedir( $dh );
	return $subs;
}

if ( ! function_exists('yaml_parse') ) {
function yaml_parse($str) {

	$yaml = null;

	try {
		$yaml = Symfony\Component\Yaml\Yaml::parse( $str );
	} catch ( Symfony\Component\Yaml\Exception\ParseException $e ) {
		$yaml = null;
		trigger_error( print_r($e), E_USER_WARNING );
	}

	return $yaml;
}
}

