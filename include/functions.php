<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-04-05
 * */

require_once INC_DIR . '/vars.php';
require_once INC_DIR . '/post.php';

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
	$spath = $spath . '/setting.json';
	if( !is_readable($spath) ) {
		return false;
	}

	$settings = file_get_contents( $spath );
	if( false === $settings ) {
		return false;
	}

	$g_settings = json_decode( $settings, true );

	if( json_last_error() === JSON_ERROR_NONE) {
		date_default_timezone_set($g_settings['tz']);
		return true;
	}

	return false;
}

function load_index() {
	global $g_index;
	$dpath = get_data_path();
	if( false === $dpath ) {
		return false;
	}

	$flist = array();

	foreach( glob($dpath . '/*.md') as $f ) {
		if( !is_file($f) ) {
			continue;
		}

		$fstat = stat($f);
		if ($fstat === false) {
			continue;
		}

		$finfo = array();
		$finfo['file']		= $f;
		$finfo['lname']		= basename($f, '.md');
		$finfo['datetime']	= $fstat['ctime'];
		$finfo['date']		= strftime('%Y-%m-%d %H:%M:%S', $finfo['datetime']);
		$finfo['format']	= 'markdownex';
		$finfo['tags']		= array();

		$flist[] = $finfo;
	}

	usort($flist, function($a, $b){
		if ($a['datetime'] == $b['datetime']) {
			return 0;
		}

		if ($a['datetime'] > $b['datetime']) {
			return -1;
		}

		return 1;
	});

	$g_index = $flist;

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
		$g_settings['theme'] = $name;
	}

	load_theme_textdomain( $name );
	require_once( THEMES_DIR . "/$name/$name.php" );

	$theme = get_theme();
	if( !isset( $theme ) ) {
		$name = 'default';
		$g_settings['theme'] = $name;
		load_theme_textdomain( $name );
		require_once( THEMES_DIR . "/$name/$name.php" );
	}
}

function load_convertors() {
	foreach( get_subdirs( CONVERTORS_DIR ) as $cvt ) {
		load_convertor_textdomain( $cvt );
		include_once( CONVERTORS_DIR . "/$cvt/$cvt.php" );
	}
}

function blog_home_url() {
	global $g_home_url;
	if ( !isset($g_home_url) ) {
		$g_home_url = dirname($_SERVER['SCRIPT_NAME']);
		$g_home_url = rtrim( $g_home_url, '/' );
	}

	return $g_home_url;
}

function blog_index_url() {
	return blog_home_url() . '/index.html';
}

function blog_name() {
	global $g_settings;
	return $g_settings['name'];
}

function blog_subhead() {
	global $g_settings;
	return $g_settings['sub'];
}

function blog_keywords() {
	global $g_settings;
	return $g_settings['keywords'];
}

function blog_description() {
	global $g_settings;
	return $g_settings['desc'];
}

function blog_linage() {
	global $g_settings;
	return $g_settings['linage'];
}

function blog_tags() {
	global $g_index;
	global $g_tags;

	if ( isset($g_tags) ) {
		return $g_tags;
	}

	$g_tags = array();
	foreach ( $g_index as $post ) {
		foreach ( $post['tags'] as $tag ) {
			$g_tags[$tag]++;
		}
	}

	return $g_tags;
}

function get_settings($name) {
	global $g_settings;
	return $g_settings['opts'][$name];
}

function get_theme_dir() {
	global $g_settings;
	return THEMES_DIR . "/{$g_settings['theme']}";
}

function get_theme_url() {
	global $g_settings;
	return blog_home_url() . "/themes/{$g_settings['theme']}";
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

/**
 * /index.html
 * /tag/<tag>.html
 * /post/<post>.html
 *
 * /index/1.html
 * /tag/<tag>/2.html
 *
 * */
function parse_uri() {
	$uri = $_SERVER['REQUEST_URI'];
	$uri_root = blog_home_url();

	if ( ( $qp = strpos( $uri, '?' ) ) !== false ) {
		$uri = substr( $uri, 0, $qp );
	}

	if ( $uri_root !== '' ) {
		if ( strpos( $uri, $uri_root ) !== 0 ) {
			return false;
		}

		$uri = substr( $uri, strlen( $uri_root ) );
	}

	if ( $uri === '/' || $uri === '' || $uri === false) {
		$uri = '/index.html';
	}

	$ext = substr($uri, -5);
	if ( $ext === '.html' ) {
		$uri = substr( $uri, 0, strlen($uri) - 5 );
	}

	$qs = explode( '/', $uri, 4 );
	if ( $qs === false ) {
		return false;
	}

	if ( $qs[1] !== 'index' && $qs[1] !== 'tag' && $qs[1] !== 'post' ) {
		return false;
	}

	global $g_req_type;
	global $g_req_value;
	global $g_req_page;

	$g_req_type = $qs[1];
	if ($g_req_type === 'index') {
		$g_req_page = $qs[2];
	} else {
		$g_req_value= $qs[2];
		$g_req_page= $qs[3];
	}

	if ( !isset($g_req_page) || $g_req_page < 1) {
		$g_req_page = 1;
	}

	return true;
}

function is_index() {
	if ( get_req_type() === 'index' ) {
		return true;
	}

	return false;
}

function is_tag() {
	if ( get_req_type() === 'tag') {
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

function is_404() {
	if ( get_error() === 404 ) {
		return true;
	}

	return false;
}

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
