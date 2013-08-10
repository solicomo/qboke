<?php
/**
 * change this for your site(s).
 * */
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
		}
	]
}
EOF;

global $g_config;
$g_config = json_decode($config_json, true);
