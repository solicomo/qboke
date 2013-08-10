<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-04-05
 * */

require_once INC_DIR . '/vars.php';
require_once INC_DIR . '/site.php';
require_once INC_DIR . '/post.php';

function load_config() {
	global $g_config;
	$path = ABSPATH . '/config.php';
	if( is_readable($path) ) {
		include_once $path;
		if ( $g_config === null) {
			return false;
		}
		return true;
	}

	$path = ABSPATH . '/config_sample.php';
	if( is_readable($path) ) {
		include_once $path;
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

function authorize() {
	global $g_config;
	if ($_GET['key'] === $g_config['key']) {
		return true;
	}
	return false;
}

function sync_content() {
	global $g_config;
	//TODO:
	$path = get_data_path() . '/sync.log';
	$srv  = print_r($_SERVER, true);
	file_put_contents($path, date('[Y-m-d H:i:s] ') . $srv, FILE_APPEND);
}

function load_sites($complete = true) {
	global $g_config;
	global $g_sites;

	$g_sites = array();
	$sites = array();

	foreach ($g_config['repos'] as $repo) {
		$path = $repo['path'];

		if (substr($path, 0, 1) !== '/') {
			$path = get_data_path() . '/' . $path;
		}

		$path = realpath($path);

		if (!$path) {
			continue;
		}

		$sites = array_merge($sites, find_sites($path));
	}

	$sites = array_unique($sites);

	foreach( $sites as $sp ) {
		$site = new QBSite($sp);

		$lr = $complete ? $site->load() : $site->load_config();
		if ($lr) {
			$id = $site->id();
			$g_sites[$id] = $site;
		}
	}

	ksort($g_sites);
}

function get_site($host) {
	global $g_sites;
	global $g_cur_site;

	foreach ($g_sites as $site) {
		$domains = $site->domains();

		foreach ($domains as $domain) {
			if (preg_match("@^{$domain}$@", $host)) {
				$g_cur_site = $site;
				return $site;
			}
		}
	}

	$g_cur_site = false;
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

function find_sites($path) {
	$path = rtrim($path, '/\\');
	$conf = $path . '/.site.json';

	$sites = array();
	if (is_file($conf) && is_readable($conf)) {
		$sites[] = $path;
	}

	if( ! $dh = opendir( $path ) ) {
		return $sites;
	}

	while ( ( $sub = readdir( $dh ) ) !== false ) {
		if ( substr($sub, 0, 1) === '.' ) {
			continue;
		}

		if( is_dir( "$path/$sub" ) ) {
			$sites = array_merge($sites, find_sites("$path/$sub"));
		}
	}

	closedir( $dh );

	return array_unique($sites);
}
