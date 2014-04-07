<?php
/**
 * @author: Soli <soli@cbug.org>
* @date  : 2013-07-19
* */
namespace QBoke\Site;

class QBResponse
{
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

	public function __construct($request, $type, $url)
	{
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

	public function set_posts($posts)
	{
		$this->posts = $posts;
	}

	public function set_path($path)
	{
		$this->path = $path;
	}

	public function set_nav($pre, $next)
	{
		if ($pre) {
			$this->pre_name  = $pre['name'];
			$this->pre_url   = $pre['url'];
		}

		if ($next) {
			$this->next_name = $next['name'];
			$this->next_url  = $next['url'];
		}
	}

	public function is_post()
	{
		return ($this->type === QBRequestType::Post);
	}

	public function is_page()
	{
		return ($this->type === QBRequestType::Page);
	}

	public function is_single()
	{
		return ($this->type === QBRequestType::Post ||
				$this->type === QBRequestType::Page);
	}

	public function is_file()
	{
		return ($this->type === QBRequestType::File);
	}

	function is_index() {
		return ($this->type === QBRequestType::Index);
	}

	public function is_catalog()
	{
		return ($this->type === QBRequestType::Catalog);
	}

	public function is_tag()
	{
		return ($this->type === QBRequestType::Tag);
	}

	public function is_list()
	{
		return ($this->type === QBRequestType::Index ||
				$this->type === QBRequestType::Catalog ||
				$this->type === QBRequestType::Tag);
	}

	public function is_error()
	{
		return ($this->type === QBRequestType::Error);
	}

	public function posts()
	{
		return $this->posts;
	}

	public function path()
	{
		return $this->path;
	}

	public function pre_name()
	{
		return $this->pre_name;
	}

	public function pre_url()
	{
		return $this->pre_url;
	}

	public function next_name()
	{
		return $this->next_name;
	}

	public function next_url()
	{
		return $this->next_url;
	}
}
