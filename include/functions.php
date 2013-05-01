<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-04-05
 * */

require_once('vars.php');

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

function load_settings() {
	global $g_settings;
	$spath = get_data_path();
	if( false === $spath ) {
		return false;
	}

	$spath = $spath . '/settings.json';
	if( !is_readable($spath) ) {
		return false;
	}

	$settings = file_get_contents( $spath );
	if( false === $settings ) {
		return false;
	}

	$g_settings = json_decode( $settings );

	if( json_last_error() === JSON_ERROR_NONE) {
		return true;
	}

	return false;
}

function load_index() {
	global $g_index;
	$spath = get_data_path();
	if( false === $spath ) {
		return false;
	}

	$spath = $spath . '/index.json';
	if( !is_readable($spath) ) {
		return false;
	}

	$index = file_get_contents( $spath );
	if( false === $settings ) {
		return false;
	}

	$g_index = json_decode( $index );

	if( json_last_error() === JSON_ERROR_NONE ) {
		return true;
	}

	return false;
}

function load_plugins() {
	foreach( get_subdirs( PLUGINS_DIR ) as $plugin ) {
		load_plugin_textdomain( $plugin );
		include_once( PLUGINS_DIR . "/$plugin/$plugin.php" );
	}
}

function load_theme() {
	$name = $g_settings['theme'];
	if( !is_readable( THEMES_DIR . "/$name/$name.php" ) ) {
		$name = 'default';
	}

	load_theme_textdomain( $name );
	require_once( THEMES_DIR . "/$name/$name.php" );

	$theme = get_theme();
	if( !isset( $theme ) ) {
		load_theme_textdomain( 'default' );
		require_once( THEMES_DIR . '/default/default.php' );
	}
}

function set_theme($theme) {
	global $g_theme;
	$g_theme = $theme;
}

function get_theme() {
	global $g_theme;
	return $g_theme;
}

function load_convertors() {
	foreach( get_subdirs( CONVERTORS_DIR ) as $cvt ) {
		load_convertor_textdomain( $cvt );
		include_once( CONVERTORS_DIR . "/$cvt/$cvt.php" );
	}
}

function set_convertor($format, $convertor) {
	global $g_convertors;
	$g_convertors[$format] = $convertor;
}

function get_convertor($format) {
	global $g_convertors;
	if(array_key_exists($format, $g_convertors)) {
		return $g_convertors[$format];
	}

	return $g_convertor_none;
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

function get_uri_root() {
	global $g_uri_root;
	if ( !isset($g_uri_root) ) {
		$g_uri_root = dirname($_SERVER['SCRIPT_NAME']);
	}

	return $g_uri_root;
}

/**
 * /index.html
 * /tag/<tag>.html
 * /post/<post>.html
 *
 * /index/page_1.html
 * /tag/<tag>/page_2.html
 *
 * */
function parse_uri() {
	$uri = $_SERVER['REQUEST_URI'];
	$uri_root = get_uri_root();

	if ( ( $qp = strpos( $uri, '?' ) ) !== false ) {
		$uri = substr( $uri, 0, $qp );
	}

	if ( strpos( $uri, $uri_root ) !== 0 ) {
		return false;
	}

	$uri = substr( $uri, strlen( $uri_root ) );

	if ( $uri === '/' ) {
		$uri = '/index.html';
	}

	$ext = substr($uri, -5);
	if ( $ext !== '.html' ) {
		return false;
	}

	$uri = substr( $uri, 0, strlen($uri) - 5 );

	$qs = explode( '/', $uri, 3 );
	if ( $qs === false ) {
		return false;
	}

	if ( $qs[1] !== 'index' && $qs[1] !== 'tag' && $qs[1] !== 'post' ) {
		return false;
	}

	global $g_req_type;
	global $g_req_value;
	$g_req_type = $qs[1];
	$g_req_value= $qs[2];

	return true;
}

function get_req_type() {
	global $g_req_type;
	return $g_req_type;
}

function is_index() {
	$req_type = get_req_type();

	if ( $req_type === 'index' ) {
		return true;
	}

	return false;
}

function is_tag() {
	$req_type = get_req_type();

	if ( $req_type === 'tag') {
		return true;
	}

	return false;
}

function is_post() {
	if ( get_req_type() === 'post' ) {
		return true;
	}

	return false;
}

function next_post() {
	//TODO:
}

function the_post() {
	if( ! is_post() ) {
		return false;
	}

	if( ! array_key_exists( $g_cur_post, $g_index ) ) {
		return false;
	}

	return new Post( $g_index[$g_cur_post] );
}

?>
