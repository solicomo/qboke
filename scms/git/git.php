<?php
/**
 * author: Soli <soli@qq.com>
 * date  : 2013-06-04
 */

include_once SCMS_DIR . '/scm.php';

use Gitter\Client;
use Gitter\Repository;

class GitSCM extends SCM {
	private $cli  = null;
	private $repo = null;

	function __construct() {
	}

	function init($path, $opts) {
		$this->cli = new Client();

		try {
			$this->repo = $this->cli->getRepository($path);
			return true;
		} catch (Exception $e) {
			db_warn( print_r($e) );
		}

		//
		try {
			//mkdir($path, 0, true);
			$this->repo = new Repository($path, $this->cli);
			$this->cli->run($this->repo, "clone {$opts['remote']} '{$path}'");
			$this->repo->checkout($opts['branch']);

			return true;
		} catch (Exception $e) {
			db_error( print_r($e) );
		}

		return false;
	}

	function pull() {
		if ($this->repo === null) {
			return false;
		}

		try {
			$this->repo->pull();
			return true;
		} catch (Exception $e) {
			db_error( print_r($e) );
		}
		return false;
	}

	function add($param) {
		if ($this->repo === null) {
			return false;
		}

		try {
			if ($param === true) {
				$this->repo->addAll();
				return true;
			}

			$this->repo->add($param);
			return true;
		} catch (Exception $e) {
			db_error( print_r($e) );
		}
		return false;
	}

	function del($param) {
		if ($this->repo === null) {
			return false;
		}

		try {
			if (is_array($param)) {
				$files = implode(' ', array_map('escapeshellarg', $param));
			} else {
				$files = escapeshellarg($param);
			}

			$this->cli->run($this->repo, "rm $files");
			return true;
		} catch (Exception $e) {
			db_error( print_r($e) );
		}
		return false;
	}

	function push() {
		if ($this->repo === null) {
			return false;
		}
		try {
			$this->repo->push();
			return true;
		} catch (Exception $e) {
			db_error( print_r($e) );
		}
		return false;
	}
}
?>
