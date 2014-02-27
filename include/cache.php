<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-21
 * */

global $g_cache_file;

function get_cache() {
	$uri = $_SERVERE['REQUEST_URI'];
	if ( ( $qp = strpos( $uri, '?' ) ) !== false ) {
		$uri = substr( $uri, 0, $qp );
	}

	if( $uri === '/' ) {
		$uri = '/index.html';
	}

	$g_cache_file = CACHE_DIR . $uri;

	if( is_readable( $g_cache_file ) && filesize( $g_cache_file ) > 0 ) {
		readfile( $g_cache_file );
		return true;
	}

	return false;
}

function put_cache() {
	///////////////////
	// avoid loop
	clearstatcache();
	if( file_exists( $g_cache_file ) ) {
		return;
	}

	if( !touch( $g_cache_file ) ) {
		return;
	}
	///////////////////

	$cache = file_get_contents( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	if( $cache !== false ) {
		if( file_put_contents( $g_cache_file, $cache ) === false ) {
			unlink( $g_cache_file );
		}
	}
}

