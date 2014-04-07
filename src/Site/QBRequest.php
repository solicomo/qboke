<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-07-19
 * */
namespace QBoke\Site;

class QBRequestType
{
	const Error = 0;
	const Index = 1;
	const Catalog = 2;
	const Tag   = 3;
	const File  = 4;
	const Post  = 5;
	const Page  = 6;

	private function __construct()
	{
		//Nonthing to do
		;
	}
}

class QBRequest
{
	private $type;
	private $url;
	private $page;
	private $http_code;

	public function __construct($type, $url, $page = 1)
	{
		$this->type = $type;
		$this->url = $url;
		$this->page = $page;

		if ($type === QBRequestType::Error) {
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
