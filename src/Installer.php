<?php
/**
 * @author Soli
 * @date   2014-04-08
 */
namespace QBoke;

use Composer\Script\Event;


class Installer
{
	private static function load()
	{
		/** Define ABSPATH */
		define( 'ABSPATH', dirname(dirname(__FILE__)) );

		require_once ABSPATH . '/vendor/autoload.php';
		require_once ABSPATH . '/src/include/def.php';
	}

	public static function postCreateProject(Event $event)
	{
		self::load();

		// 1. ln -s src/Plugin public/Plugin
		$target = PLUGIN_DIR;
		$link   = PUBLIC_DIR . '/Plugin';
		if (!symlink($target, $link)) {
			echo "[Fail] ln -s $target $link\n";
		} else {
			echo "[ OK ] ln -s $target $link\n";
		}

		// 2. ln -s src/Theme public/Theme
		$target = THEME_DIR;
		$link   = PUBLIC_DIR . '/Theme';
		if (!symlink($target, $link)) {
			echo "[Fail] ln -s $target $link\n";
		} else {
			echo "[ OK ] ln -s $target $link\n";
		}

		// 3. chmod +x src/SCM/Git/git.sh
		chmod(SCM_DIR . '/Git/git.sh', 0755);

		// 4. chmod
		$oldumask = umask(0);
		mkdir(DATA_DIR, 0777, true);
		chmod(CACHE_DIR, 0777);
		chmod(PUBLIC_DIR, 0777);
		umask($oldumask);
	}
}
