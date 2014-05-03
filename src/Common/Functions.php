<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-04-05
 * */
//namespace QBoke\Common\Functions;

use QBoke\Common\QBGlobal;

function qb_set_debug_mode()
{
	$g = QBGlobal::getInstance();
	error_reporting( E_ALL );
var_dump($g);
	if ($g->config['debug'] === 'on') {
		ini_set( 'display_errors', 1 );
	} else {
		ini_set( 'display_errors', 0 );
	}

	ini_set( 'log_errors', 1 );
	ini_set( 'error_log', CACHE_DIR . '/qboke.log' );
}

function qb_options($name)
{
	$g = QBGlobal::getInstance();
	return $g->site->options($name);
}

function qb_site_root()
{
	$g = QBGlobal::getInstance();
	return $g->site->root();
}

function qb_site_url()
{
	$g = QBGlobal::getInstance();
	return $g->site->url();
}

function qb_theme_url()
{
	$g = QBGlobal::getInstance();
	return $g->site->url() . 'Theme';
}

function qb_plugin_url()
{
	$g = QBGlobal::getInstance();
	return $g->site->url() . 'Plugin';
}

function qb_header()
{
	$g = QBGlobal::getInstance();
	$g->call_hooks('qb_header');
}

function qb_footer()
{
	$g = QBGlobal::getInstance();
	$g->call_hooks('qb_footer');
}

function qb_comments($post)
{
	$g = QBGlobal::getInstance();
	$g->call_hooks('qb_comments', $post);
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
