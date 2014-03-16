<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-14
 * */

// Initialize the hook globals.
global $g_hooks;

if ( ! isset( $g_hooks ) ) {
	$g_hooks = array();
	$g_hooks['qb_header'] = array();
	$g_hooks['qb_footer'] = array();
	$g_hooks['qb_get_content'] = array();
}

function add_hook( $tag, $callback, $priority = 10 ) {
	global $g_hooks;
	$idx = _build_hook_id($tag, $callback, $priority);

	if ($idx === false) {
		return false;
	}

	$g_hooks[$tag][$priority][$idx] = $callback;
	return true;
}

function del_hook( $tag, $callback, $priority = 10 ) {
	global $g_hooks;
	$idx = _build_hook_id($tag, $callback, $priority);

	if ($idx === false) {
		return false;
	}

	unset($g_hooks[$tag][$priority][$idx]);

	if (empty($g_hooks[$tag][$priority])) {
		unset($g_hooks[$tag][$priority]);
	}

	return true;
}

function call_hooks( $tag, $value = '' ) {
	global $g_hooks;
	$args = func_get_args();

	ksort($g_hooks[$tag]);

	foreach ($g_hooks[$tag] as $callbacks) {
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