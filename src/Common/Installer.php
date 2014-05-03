<?php
/**
 * @author Soli
 * @date   2014-04-08
 */
namespace QBoke\Common;

use Composer\Script\Event;

require_once __DIR__ . '/Defines.php';

class Installer
{
	public function run(Event $event)
	{
		if ($event->getName() === 'post-create-project-cmd') {
			$this->postCreateProject($event);
		} elseif ($event->getName() === 'post-update-cmd') {
			$this->postCreateProject($event);
		}
	}

	public function postCreateProject(Event $event)
	{
		// 1. ln -s src/Plugin public/Plugin
		$target = PLUGIN_DIR;
		$link   = PUBLIC_DIR . '/' . basename($target);
		if (@symlink($target, $link)) {
			echo "[ OK ] ln -s $target $link\n";
		} else {
			echo "[Fail] ln -s $target $link\n";
		}

		// 2. ln -s src/Theme public/Theme
		$target = THEME_DIR;
		$link   = PUBLIC_DIR . '/' . basename($target);
		if (@symlink($target, $link)) {
			echo "[ OK ] ln -s $target $link\n";
		} else {
			echo "[Fail] ln -s $target $link\n";
		}

		// 3. chmod +x src/SCM/Git/git.sh
		if (@chmod(SCM_DIR . '/Git/git.sh', 0755)) {
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

		if (@mkdir(CACHE_DIR, 0777)) {
			echo '[ OK ] mkdir ' . CACHE_DIR . "\n";
		} else {
			echo '[Fail] mkdir ' . CACHE_DIR . "\n";
		}

		if (@chmod(PUBLIC_DIR, 0777)) {
			echo '[ OK ] chmod go+w ' . PUBLIC_DIR . "\n";
		} else {
			echo '[Fail] chmod go+w ' . PUBLIC_DIR . "\n";
		}
		umask($oldumask);
	}
}
