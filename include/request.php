<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-07-19
 * */
class QBRequestType {
	const Error = 0;
	const Index = 1;
	const Post  = 2;
	const Tag   = 3;
	const Catalog = 4;

	private function __construct() {
		//Nonthing to do
		;
	}
}

class QBRequest {
	private $type;
	private $url;
	private $page;
	private $http_code;

	function __construct($type, $url, $page = 1) {
		$this->type = $type;
		$this->url = $url;
		$this->page = $page;

		if ($type === QBRequestType::Error) {
			$this->http_code = intval($url);
		} else {
			$this->http_code = 200;
		}
	}

	function type() {
		return $this->type;
	}

	function url() {
		return $this->url;
	}

	function page() {
		return $this->page;
	}

	function http_code() {
		return $this->http_code;
	}
}