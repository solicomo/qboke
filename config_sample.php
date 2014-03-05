<?php
/**
 * change this for your site.
 * */

$config_json = <<<EOF
{
	"key": "your_secret_key_for_sync",
	"debug": "on",
	"repo": {
		"type"  : "git",
		"remote": "https://soli@bitbucket.org/soli/qboke.org.git",
		"branch": "",
		"user"  : "",
		"pass"  : "",
		"email" : ""
	}
}
EOF;

return json_decode($config_json, true);
