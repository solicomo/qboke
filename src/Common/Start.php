<?php
/**
 * @author Soli
 * @date   2014-04-08
 */
namespace QBoke\Common;

use QBoke\Common\QBGlobal;
use QBoke\Common\Defines;
use QBoke\Site\QBSite;

require_once __DIR__ . '/Defines.php';
require_once __DIR__ . '/Functions.php';

class Start
{
	public static function index()
	{
		qb_set_debug_mode();

		$site = new QBSite();

		if ($site->init() === false) {
			trigger_error('init site failed.', E_USER_WARNING);
			header("Status: 500 Internal Server Error");
			exit('load site failed.');
		}

		$site->get(urldecode($_SERVER['REQUEST_URI']));
	}

	public static function sync()
	{
		qb_set_debug_mode();

		trigger_error("got sync request from {$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']}", E_USER_NOTICE);

		$g = QBGlobal::getInstance();
		$repo = $g->config['repo'];
		$name = $repo['type'];
		$path = DATA_DIR;

		if ($_GET['key'] !== $g->config['key']) {
			trigger_error('sync request forbidden.', E_USER_WARNING);
			header('Status: 403 Forbidden');
			exit('403 Forbidden');
		}

		$scm_type = $repo['type'];
		$scm_cls = "\\QBoke\\SCM\\$scm_type\\$scm_type" . 'SCM';

		if (!class_exists($scm_cls)) {
			trigger_error("no scm [$name]", E_USER_WARNING);
			header('Status: 500 Internal Server Error');
			exit('500 Internal Server Error');
		}

		$scm = new $scm_cls;

		if (!$scm->init($path, $repo)) {
			trigger_error("scm [$name] init failed.", E_USER_WARNING);
			header('Status: 500 Internal Server Error');
			exit('500 Internal Server Error');
		}

		if (!$scm->pull()) {
			trigger_error("scm [$name] pull failed", E_USER_WARNING);
			header('Status: 500 Internal Server Error');
			exit('500 Internal Server Error');
		}

		$site = new QBSite();

		if ($site->init() === false) {
			trigger_error('init site failed.', E_USER_WARNING);
			header("Status: 500 Internal Server Error");
			exit('load site failed.');
		}

		if ($site->dump() === false) {
			trigger_error('dump site failed.', E_USER_WARNING);
			header("Status: 500 Internal Server Error");
			exit('dump site failed.');
		}

		echo 'done';
		trigger_error('sync done.', E_USER_NOTICE);
	}

}