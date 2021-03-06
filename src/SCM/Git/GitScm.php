<?php
/**
 * author: Soli <soli@qq.com>
 * date  : 2013-06-04
 */
namespace QBoke\SCM\Git;

use Gitter\Client;
use Gitter\Repository;
use QBoke\SCM\QBScm;

class GitScm extends QBScm
{
	private $cli  = null;
	private $repo = null;

	public function __construct()
	{
	}

	public function init($path, $opts)
	{
		$git = null;
		if (array_key_exists('pkey', $opts) && strlen($opts['pkey']) > 0) {
			$pkey = $opts['pkey'];
			if (substr($pkey, 0, 1) != '/') {
				$pkey = ABSPATH . "/$pkey";
			}

			$pkey = realpath($pkey);
			$git  = realpath(__DIR__ . '/git.sh');

			$git = "$git -i $pkey";
		}

		$this->cli = new Client($git);

		try {
			$this->repo = $this->cli->getRepository($path);
			return true;
		} catch (\Exception $e) {
			trigger_error( print_r($e), E_USER_WARNING );
		}

		//
		try {
			//mkdir($path, 0, true);
			$this->repo = new Repository($path, $this->cli);
			$this->cli->run($this->repo, "clone {$opts['remote']} '{$path}'");
			$this->repo->checkout($opts['branch']);

			return true;
		} catch (\Exception $e) {
			trigger_error( print_r($e), E_USER_ERROR );
		}

		return false;
	}

	public function pull()
	{
		if ($this->repo === null) {
			return false;
		}

		try {
			$this->repo->pull();
			return true;
		} catch (\Exception $e) {
			trigger_error( print_r($e), E_USER_ERROR );
		}
		return false;
	}

	public function add($param)
	{
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
		} catch (\Exception $e) {
			trigger_error( print_r($e), E_USER_ERROR );
		}
		return false;
	}

	public function del($param)
	{
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
		} catch (\Exception $e) {
			trigger_error( print_r($e), E_USER_ERROR );
		}
		return false;
	}

	public function push()
	{
		if ($this->repo === null) {
			return false;
		}
		try {
			$this->repo->push();
			return true;
		} catch (\Exception $e) {
			trigger_error( print_r($e), E_USER_ERROR );
		}
		return false;
	}
}

