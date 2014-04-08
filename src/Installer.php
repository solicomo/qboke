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
		$root = ABSPATH;
		$pub  = $root . '/public';

		// 1. ln -s src/Plugin public/Plugin
		$target = PLUGIN_DIR;
		$link   = $pub  . '/Plugin';
		if (!symlink($target, $link)) {
			echo "[Fail] ln -s $target $link\n";
		} else {
			echo "[ OK ] ln -s $target $link\n";
		}

		// 2. ln -s src/Theme public/Theme
		$target = THEMESDIR;
		$link   = $pub  . '/Theme';
		if (!symlink($target, $link)) {
			echo "[Fail] ln -s $target $link\n";
		} else {
			echo "[ OK ] ln -s $target $link\n";
		}

		// 3. chmod +x src/SCM/Git/git.sh
		chmod(SCM_DIR . '/Git/git.sh', 0755);
	}
}
