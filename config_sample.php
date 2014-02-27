<?php
/**
 * change this for your site.
 * */

define('DEBUG_MODE', true);
define('LOG_FILE', '/tmp/qboke.log');

$config_json = <<<EOF
{
	"key": "your_secret_key_for_sync",
	"public_path": "public",
	"repo": {
		"type"  : "git",
		"remote": "",
		"branch": "",
		"user"  : "",
		"pass"  : "",
		"email" : ""
	}
}
EOF;

global $g_config;
$g_config = json_decode($config_json, true);
