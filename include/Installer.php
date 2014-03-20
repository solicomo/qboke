<?php

namespace QBoke;

use Composer\Script\Event;

class Installer
{
	public static function postCreateProject(Event $event)
	{
		$root = dirname(dirname(__FILE__));
		$pub  = $root . '/public';

		$target = $root . '/plugins';
		$link   = $pub  . '/plugins';
		if (!symlink($target, $link)) {
			echo "[Fail] ln -s $target $link\n";
		} else {
			echo "[ OK ] ln -s $target $link\n";
		}

		$target = $root . '/themes';
		$link   = $pub  . '/themes';
		if (!symlink($target, $link)) {
			echo "[Fail] ln -s $target $link\n";
		} else {
			echo "[ OK ] ln -s $target $link\n";
		}
	}
}