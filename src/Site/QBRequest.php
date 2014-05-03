<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-07-19
 * */
namespace QBoke\Site;

class QBRequest
{
	const TYPE_ERROR = 0;
	const TYPE_INDEX = 1;
	const TYPE_CATALOG = 2;
	const TYPE_TAG   = 3;
	const TYPE_FILE  = 4;
	const TYPE_POST  = 5;
	const TYPE_PAGE  = 6;

	private $type;
	private $url;
	private $page;
	private $http_code;

	public function __construct($type, $url, $page = 1)
	{
		$this->type = $type;
		$this->url = $url;
		$this->page = $page;

		if ($type === self::TYPE_ERROR) {
			$this->http_code = intval($url);
		} else {
			$this->http_code = 200;
		}
	}

	public function type()
	{
		return $this->type;
	}

	public function url()
	{
		return $this->url;
	}

	public function page()
	{
		return $this->page;
	}

	public function http_code()
	{
		return $this->http_code;
	}
}
