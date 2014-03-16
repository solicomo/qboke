<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-18
 * */

require_once CONVERTORS_DIR . '/convertor.php';

/** declare */
global $g_config;
global $g_scms;
global $g_themes;
global $g_convertors;
global $g_hooks;
global $g_locale;
global $g_l10n;

global $g_site;
global $g_request;
global $g_response;
global $g_theme;

/** init */

/** set and get */
function set_scm($name, $scm) {
	global $g_scms;
	$g_scms[$name] = $scm;
}

function get_scm($name) {
	global $g_scms;
	if (array_key_exists($name, $g_scms) && isset($g_scms[$name])) {
		return new $g_scms[$name];
	}

	return false;
}

function set_theme($name, $theme) {
	global $g_themes;
	$g_themes[$name] = $theme;
}

function get_theme($name, $site) {
	global $g_themes;
	if (is_array($g_themes) && array_key_exists($name, $g_themes) && isset($g_themes[$name])) {
		return new $g_themes[$name]($site);
	}
	return new $g_themes['default']($site);
}

function set_convertor($format, $convertor) {
	global $g_convertors;
	$g_convertors[$format] = $convertor;
}

function get_convertor($format) {
	global $g_convertors;
	if (is_array($g_convertors) && array_key_exists($format, $g_convertors) && isset($g_convertors[$format]) ) {
		return new $g_convertors[$format];
	}

	return new $g_convertors['none'];
}

