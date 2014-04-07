<?php
/**
 * @author Soli
 * @date   2014-04-08
 */
namespace QBoke;

use Composer\Script\Event;

class Installer
{
	public static function postCreateProject(Event $event)
	{
		$root = dirname(dirname(__FILE__));
		$pub  = $root . '/public';

		// 1. ln -s plugins public/plugins
		$target = $root . '/plugins';
		$link   = $pub  . '/plugins';
		if (!symlink($target, $link)) {
			echo "[Fail] ln -s $target $link\n";
		} else {
			echo "[ OK ] ln -s $target $link\n";
		}

		// 2. ln -s themes public/themes
		$target = $root . '/themes';
		$link   = $pub  . '/themes';
		if (!symlink($target, $link)) {
			echo "[Fail] ln -s $target $link\n";
		} else {
			echo "[ OK ] ln -s $target $link\n";
		}

		// 3. chmod +x scms/git/git.sh
		chmod($root . '/scms/git/git.sh', 0755);
	}
}