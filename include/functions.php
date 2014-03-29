<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-04-05
 * */

require_once INC_DIR . '/hook.php';
require_once INC_DIR . '/plugin.php';
require_once INC_DIR . '/site.php';
require_once INC_DIR . '/post.php';

function load_config() {
	global $g;
	$path = ABSPATH . '/config.php';
	if( is_readable($path) ) {
		$g->config = include_once $path;
		if ( $g->config === null) {
			return false;
		}
		return true;
	}

	$path = ABSPATH . '/config_sample.php';
	if( is_readable($path) ) {
		$g->config = include_once $path;
		if ( $g->config === null) {
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
	global $g;
	error_reporting( E_ALL );

	if ($g->config['debug'] === 'on') {
		ini_set( 'display_errors', 1 );
	} else {
		ini_set( 'display_errors', 0 );
	}

	ini_set( 'log_errors', 1 );
	ini_set( 'error_log', CACHE_DIR . '/qboke.log' );
}

function authorize() {
	global $g;
	if ($_GET['key'] === $g->config['key']) {
		return true;
	}
	return false;
}

function sync_content() {
	global $g;

	$repo = $g->config['repo'];
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
	global $g;

	$path = get_data_path();
	$g->site = new QBSite($path);

	if ($g->site->load()) {
		return $g->site;
	}

	return false;
}

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

function qb_options($name) {
	global $g;
	return $g->site->options($name);
}

function qb_site_root() {
	global $g;
	return $g->site->root();
}

function qb_site_url() {
	global $g;
	return $g->site->url();
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

function mime($file) {
	$ftype = 'application/octet-stream';
	$finfo = @new finfo(FILEINFO_MIME);
	$fres = @$finfo->file($file);
	if (is_string($fres) && !empty($fres)) {
		$ftype = $fres;
	}

	return $ftype;
}

function return_include($file) {
	ob_start();
	include $file;
	return ob_get_clean();
}

function real_copy($src, $dst) {
	$ddir = dirname($dst);

	if (!is_dir($ddir)) {
		$oldumask = umask(0);
		@mkdir($ddir, 0777, true);
		umask($oldumask);
	}

	@copy($src, $dst);
}

function mkdir_p($dir)
{
	if (!is_dir($dir)) {
		$oldumask = umask(0);
		@mkdir($dir, 0777, true);
		umask($oldumask);
	}
}
