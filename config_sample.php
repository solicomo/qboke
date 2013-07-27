<?php

require_once INC_DIR . '/vars.php';

global $g_config;

$g_config = array(
	0 => array(
		"path" => ".",
		"scm"  => "git",
		"opts" => array(
			"repo"   => "",
			"branch" => "",
			"user"   => "",
			"pass"   => "",
			"email"  => ""
		)
	)
);
