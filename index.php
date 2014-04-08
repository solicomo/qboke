<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-05
 * */

require_once __DIR__ . '/load.php';


load_scms();
load_default_textdomain();
load_plugins();
load_themes();
load_parsers();

$site = load_site();

if ($site === false) {
	header("Status: 500 Internal Server Error");
	exit('Internal Server Error');
}

$site->get(urldecode($_SERVER['REQUEST_URI']));

