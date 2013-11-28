<?php
/**
 * change this for your site(s).
 * */

define('DEBUG_MODE', 3);
define('DEBUG_LOG', '/tmp/qboke.log');

$config_json = <<<EOF
{
	"key": "your secret key",
	"repos": [
		{
			"path": "./data_sample",
			"scm" : "git",
			"opts": {
				"remote": "",
				"branch": "",
				"user"  : "",
				"pass"  : "",
				"email" : ""
			}
		}
	]
}
EOF;

global $g_config;
$g_config = json_decode($config_json, true);
