<?php
/**
 * author: Soli <soli@qq.com>
 * date  : 2013-04-05
 * */

require_once('vars.php');

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

function get_settings() {
	global $g_settings;
	$spath = get_data_path();
	if( false === $spath ) {
		return false;
	}

	$spath = $spath . '/settings.json';
	if( !is_readable($spath) ) {
		return false;
	}

	$settings = file_get_contents( $spath );
	if( false === $settings ) {
		return false;
	}

	$g_settings = json_decode( $settings );

	if( json_last_error() === JSON_ERROR_NONE) {
		return true;
	}

	return false;
}

function get_index() {
	global $g_indexes;
	$spath = get_data_path();
	if( false === $spath ) {
		return false;
	}

	$spath = $spath . '/index.json';
	if( !is_readable($spath) ) {
		return false;
	}

	$index = file_get_contents( $spath );
	if( false === $settings ) {
		return false;
	}

	$g_indexes = json_decode( $index );

	if( json_last_error() === JSON_ERROR_NONE) {
		return true;
	}

	return false;
}

function set_convertor($format, $convertor)
{
	global $g_convertors;
	$g_convertors[$format] = $convertor;
}

function get_convertor($format)
{
	global $g_convertors;
	if(array_key_exists($format, $g_convertors))
	{
		return $g_convertors[$format];
	}

	return $g_convertor_none;
}
?>
