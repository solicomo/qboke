<?php
/**
 * @author Soli
 * @date   2014-04-08
 */
namespace QBoke;

use QBoke\Common\Defines;
use Composer\Script\Event;


class Installer
{
	public static function run(Event $event)
	{
		if ($event->getName() === 'post-create-project-cmd') {
			$this->postCreateProject($event);
		}
	}

	public static function postCreateProject(Event $event)
	{
		self::load();

		// 1. ln -s src/Plugin public/Plugin
		$target = PLUGIN_DIR;
		$link   = PUBLIC_DIR . '/Plugin';
		if (symlink($target, $link)) {
			echo "[ OK ] ln -s $target $link\n";
		} else {
			echo "[Fail] ln -s $target $link\n";
		}

		// 2. ln -s src/Theme public/Theme
		$target = THEME_DIR;
		$link   = PUBLIC_DIR . '/Theme';
		if (symlink($target, $link)) {
			echo "[ OK ] ln -s $target $link\n";
		} else {
			echo "[Fail] ln -s $target $link\n";
		}

		// 3. chmod +x src/SCM/Git/git.sh
		if (chmod(SCM_DIR . '/Git/git.sh', 0755)) {
			echo "[ OK ] chmod +x src/SCM/Git/git.sh\n";
		} else {
			echo "[Fail] chmod +x src/SCM/Git/git.sh\n";
		}

		// 4. chmod
		$oldumask = umask(0);
		if (@mkdir(DATA_DIR, 0777, true)) {
			echo '[ OK ] mkdir ' . DATA_DIR . "\n";
		} else {
			echo '[Fail] mkdir ' . DATA_DIR . "\n";
		}

		if (chmod(CACHE_DIR, 0777)) {
			echo '[ OK ] chmod go+w ' . CACHE_DIR . "\n";
		} else {
			echo '[Fail] chmod go+w ' . CACHE_DIR . "\n";
		}

		if (chmod(PUBLIC_DIR, 0777)) {
			echo '[ OK ] chmod go+w ' . PUBLIC_DIR . "\n";
		} else {
			echo '[Fail] chmod go+w ' . PUBLIC_DIR . "\n";
		}
		umask($oldumask);

		// 5. cp config_sample.php config.php
		if (@copy(ABSPATH . '/config_sample.php', ABSPATH . '/config.php')) {
			echo "[ OK ] cp config_sample.php config.php\n";
		} else {
			echo "[Fail] cp config_sample.php config.php\n";
		}
	}
}
