<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-04-05
 * */

require_once INC_DIR . '/vars.php';
require_once INC_DIR . '/post.php';

function load_config() {
	global $g_config;

	$path = ABSPATH . '/config.json';
	if( !is_readable($path) ) {
		return false;
	}

	$config = file_get_contents( $path );
	if( false === $config ) {
		return false;
	}

	$g_config = json_decode( $config, true );

	if( json_last_error() === JSON_ERROR_NONE) {
		return true;
	}

	return false;
}

function load_themes() {
	foreach( get_subdirs( PLUGINS_DIR ) as $theme ) {
		include_once PLUGINS_DIR . "/$theme/_.php";
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
		include_once CONVERTORS_DIR . "/$cvt/index.php";
	}
}

function sync_content() {
	global $g_config;
	//TODO:
}

function load_sites() {
	global $g_config;
	global $g_sites;

	$sites = array();

	foreach ($g_config as $config) {
		$path = $config['path'];

		if (substr($path, 0, 1) !== '/') {
			$path = get_data_path() . '/' . $path;
		}

		$sites = array_merge(find_sites($path));
	}

	$sites = array_unique($sites);

	foreach( $sites as $sp ) {
		$site = new QBSite($sp);

		if ($site->load_config()) {
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
			if (preg_match("^{$domain}$", $host)) {
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






//////////////////////


function prepare_posts() {
	global $g_posts;
	global $g_index;

	if( !isset($g_index) ) {
		return false;
	}

	$posts = array();
	$req = get_req_value();

	if ( is_index() ) {
		$posts = array_keys( $g_index );
	} elseif ( is_post() ) {
		foreach ( $g_index as $idx => $post ) {
			if ( $post['lname'] === $req ) {
				$posts[] = $idx;
			}
		}
	} elseif ( is_tag() ) {
		foreach ( $g_index as $idx => $post ) {
			if ( array_search( $req, $post['tags'] ) !== false ) {
				$posts[] = $idx;
			}
		}
	}

	// 分页
	$cur = get_req_page();
	$cnt = count($posts);
	$linage = blog_linage();

	global $g_page_cnt;
	$g_page_cnt = ceil( $cnt / $linage );

	$g_posts = array();

	if ( $cur > 0 && $cur <= $g_page_cnt ) {
		$g_posts = array_slice($posts, ($cur - 1) * $linage, $linage);
	}
}

function pre_page_url() {
	$cur = get_cur_page();
	if ( $cur < 2 ) {
		return false;
	}

	$pre = $cur - 1;
	$type = get_req_type();
	$val = get_req_value();
	if ( $pre <= 1 ) {
		$url = blog_home_url() . "/$type/$val.html";;
	} else {
		$url = blog_home_url() . "/$type/$va/$pre.html";
	}

	return $url;
}

function next_page_url() {
	$cur = get_cur_page();
	$cnt = get_page_count();
	if ( $cur >= $cnt ) {
		return false;
	}

	$next = $cur + 1;
	$type = get_req_type();
	$val = get_req_value();

	$url = blog_home_url() . "/$type/$va/$next.html";

	return $url;
}

function the_post() {
	global $g_posts;
	global $g_index;

	if( !isset($g_posts) or !isset($g_index) ) {
		return false;
	}

	$cur_post = array_shift($g_posts);

	if( ( !isset($cur_post) ) || ( !array_key_exists( $cur_post, $g_index ) ) ) {
		return false;
	}

	return new Post( $g_index[$cur_post] );
}

?>
