<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-18
 * */

class QBGlobal
{
	/** declare */
	public $scms;
	public $themes;
	public $convertors;
	public $hooks;

	public $config;
	public $locale;
	public $l10n;

	public $site;
	public $request;
	public $response;
	public $theme;
}

global $g;
$g = new QBGlobal();

/** set and get */
function set_scm($name, $scm)
{
	global $g;
	$g->scms[$name] = $scm;
}

function get_scm($name)
{
	global $g;
	if (array_key_exists($name, $g->scms) && isset($g->scms[$name])) {
		return new $g->scms[$name];
	}

	return false;
}

function set_theme($name, $theme)
{
	global $g;
	$g->themes[$name] = $theme;
}

function get_theme($name, $site)
{
	global $g;
	if (is_array($g->themes) && array_key_exists($name, $g->themes) && isset($g->themes[$name])) {
		return new $g->themes[$name]($site);
	}
	return new $g->themes['default']($site);
}

function set_convertor($format, $convertor)
{
	global $g;
	$g->convertors[$format] = $convertor;
}

function get_convertor($format)
{
	global $g;
	if (is_array($g->convertors) && array_key_exists($format, $g->convertors) && isset($g->convertors[$format]) ) {
		return new $g->convertors[$format];
	}

	return new $g->convertors['none'];
}

