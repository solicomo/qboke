<?php
/**
 * change this for your site.
 * */

$config_json = <<<EOF
{
	"key": "your_secret_key_for_sync",
	"debug": "off",
	"repo": {
		"type"  : "git",
		"remote": "https://bitbucket.org/soli/qboke.org.git",
		"branch": "master",
		"pkey"  : ""
	}
}
EOF;

return json_decode($config_json, true);
