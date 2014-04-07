<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-14
 * */

// Initialize the hook globals.
global $g;

if ( ! isset( $g->hooks ) ) {
	$g->hooks = array();
	$g->hooks['qb_header'] = array();
	$g->hooks['qb_footer'] = array();
	$g->hooks['qb_get_content'] = array();
	$g->hooks['qb_comments'] = array();
}

function add_hook( $tag, $callback, $priority = 10 ) {
	global $g;
	$idx = _build_hook_id($tag, $callback, $priority);

	if ($idx === false) {
		return false;
	}

	$g->hooks[$tag][$priority][$idx] = $callback;
	return true;
}

function del_hook( $tag, $callback, $priority = 10 ) {
	global $g;
	$idx = _build_hook_id($tag, $callback, $priority);

	if ($idx === false) {
		return false;
	}

	unset($g->hooks[$tag][$priority][$idx]);

	if (empty($g->hooks[$tag][$priority])) {
		unset($g->hooks[$tag][$priority]);
	}

	return true;
}

function call_hooks( $tag, $value = '' ) {
	global $g;
	$args = func_get_args();

	ksort($g->hooks[$tag]);

	foreach ($g->hooks[$tag] as $callbacks) {
		foreach ($callbacks as $cb) {
			if (!is_null($cb)) {
				$args[1] = $value;
				$value = call_user_func_array($cb, array_slice($args, 1));
			}
		}
	}

	return $value;
}

function _build_hook_id($tag, $function, $priority) {
	static $hook_id_count = 0;

	if ( is_string($function) )
		return $function;

	if ( is_object($function) ) {
		// Closures are currently implemented as objects
		$function = array( $function, '' );
	} else {
		$function = (array) $function;
	}

	if (is_object($function[0]) ) {
		// Object Class Calling
		if ( function_exists('spl_object_hash') ) {
			return spl_object_hash($function[0]) . $function[1];
		}

		$obj_idx = get_class($function[0]).$function[1];

		if ( !isset($function[0]->qb_filter_id) ) {
			$obj_idx .= $hook_id_count;
			++$hook_id_count;
		} else {
			$obj_idx .= $function[0]->qb_filter_id;
		}

		return $obj_idx;

	} else if ( is_string($function[0]) ) {
		// Static Calling
		return $function[0] . '::' . $function[1];
	}

	return false;
}