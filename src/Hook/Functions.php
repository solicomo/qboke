<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-14
 * */

// Initialize the hook globals.
global $g;

function add_hook( $tag, $callback, $priority = 10 ) {
	global $g;
	$idx = _build_hook_id($tag, $callback, $priority);

	if ($idx === false) {
		return false;
	}

	$g->hooks[$tag][$priority][$idx] = $callback;
	return true;
}
