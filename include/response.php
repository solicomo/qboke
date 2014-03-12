<?php
/**
 * @author: Soli <soli@cbug.org>
* @date  : 2013-07-19
* */
require_once INC_DIR. '/request.php';

class QBResponse {
	private $request;
	private $type;
	private $url;
	private $http_code;
	private $posts;
	private $pre_name;
	private $pre_url;
	private $next_name;
	private $next_url;
	private $path;

	function __construct($request, $type, $url) {
		$this->request = $request;
		$this->type = $type;
		$this->url = $url;

		if ($type === QBRequestType::Error) {
			$this->http_code = intval($url);
		} else {
			$this->http_code = 200;
		}

		$this->posts     = array();
		$this->pre_name  = false;
		$this->pre_url   = false;
		$this->next_name = false;
		$this->next_url  = false;
		$this->path      = false;
	}

	function set_posts($posts) {
		$this->posts = $posts;
	}

	function set_path($path) {
		$this->path = $path;
	}

	function set_nav($pre, $next) {
		if ($pre) {
			$this->pre_name  = $pre['name'];
			$this->pre_url   = $pre['url'];
		}

		if ($next) {
			$this->next_name = $next['name'];
			$this->next_url  = $next['url'];
		}
	}

	function is_post() {
		return ($this->type === QBRequestType::Post);
	}

	function is_page() {
		return ($this->type === QBRequestType::Page);
	}

	function is_file() {
		return ($this->type === QBRequestType::File);
	}

	function is_index() {
		return ($this->type === QBRequestType::Index);
	}

	function is_catalog() {
		return ($this->type === QBRequestType::Catalog);
	}

	function is_tag() {
		return ($this->type === QBRequestType::Tag);
	}

	function is_error() {
		return ($this->type === QBRequestType::Error);
	}

	function posts() {
		return $this->posts;
	}

	function path() {
		return $this->path;
	}

	function pre_name() {
		return $this->pre_name;
	}

	function pre_url() {
		return $this->pre_url;
	}

	function next_name() {
		return $this->next_name;
	}

	function next_url() {
		return $this->next_url;
	}
}
