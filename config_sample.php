<?php

require_once INC_DIR . '/vars.php';

global $g_config;

$config_json = <<<EOF
{
		"key": "your secret key",
		"repos": [
			{
				"path": ".",
				"scm" : "git",
				"opts": {
					"remote": "",
					"branch": "",
					"user"  : "",
					"pass"  : "",
					"email" : ""
			}
		]
}
EOF;

$g_config = json_decode($config_json, true);
