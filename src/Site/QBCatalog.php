<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-06-05
 * */
namespace QBoke\Site;

class QBCatalog
{
	private $site;
	private $parent;
	private $name	= '';
	private $config	= array();

	private $subs	= array();
	private $posts	= array();
	private $pages	= array();
	private $files	= array();
	private $tags;

	public function __construct($parent, $name)
	{
		$this->parent = $parent;
		$this->name   = $name;
	}

	public function load()
	{
		$this->load_config();

		$path = $this->path();
		if( ! $dh = opendir( $path ) ) {
			return false;
		}

		while ( ( $sub = readdir( $dh ) ) !== false ) {
			if ( $sub === '.' || $sub === '..' ) {
				continue;
			}

			//TODO: filter($sub, $path)
			if ( substr($sub, 0, 1) === '.' ) {
				continue;
			}

			if( is_dir( "$path/$sub" ) ) {
				$catalog = new QBCatalog( $this, $sub );

				if ( !$catalog->load() ) {
					continue;
				}

				$this->subs[$catalog->url_path()] = $catalog;

			} elseif ( is_file("$path/$sub") ) {
				//TODO: quick fix
				if ( pathinfo($sub, PATHINFO_EXTENSION) !== 'md') {
					$this->files[$this->url_path() . "/$sub"] = "$path/$sub";
					continue;
				}

				$post = new QBPost( $this, $sub );

				if ( !$post->load() ) {
					continue;
				}

				if ($post->type() === 'page') {
					$this->pages[$post->url_path()] = $post;
				} else {
					$this->posts[$post->url_path()] = $post;
				}
			}
		}

		closedir( $dh );
		return true;
	}

	public function site()
	{
		if (!isset($this->site)) {
			$this->site = $this->parent->site();
		}

		return $this->site;
	}

	public function path()
	{
		$ppath = $this->parent->path();
		$path  = $ppath . '/' . $this->name;
		return rtrim($path, '/\\');
	}

	public function url_path()
	{
		$purl = $this->parent->url_path();
		$url  = $purl . '/' . $this->slug();
		return rtrim($url, '/\\');
	}

	public function url($page = '')
	{
		$site = $this->site();

		if (intval($page) > 0) {
			$url = $site->root() . $this->url_path() . '/' . strval(intval($page)) . $site->url_suffix();
		} else {
			$url = $site->root() . $this->url_path() . '/1' . $site->url_suffix();
		}

		return $url;
	}

	public function slug()
	{
		if (isset($this->config) && isset($this->config['slug'])) {
			return $this->config['slug'];
		}

		return $this->name;
	}

	public function options($name)
	{
		if ( !isset($this->config) ) {
			return false;
		}

		$config = $this->config;

		if (isset($config['opts']) && isset($config['opts'][$name])) {
			return $config['opts'][$name];
		}

		return false;
	}

	public function catalogs($recursive = true)
	{
		if (!$recursive) {
			return $this->subs;
		}

		$subs = $this->subs;

		foreach ($this->subs as $sub) {
			$subs = array_merge($subs, $sub->catalogs(true));
		}

		return $subs;
	}

	public function tags($recursive = true)
	{
		if (!$recursive && isset($this->tags)) {
			return $this->tags;
		}

		$tags = array();
		foreach ($this->posts as $post) {
			foreach ($post->tags() as $tag_name => $posts) {
				if (array_key_exists($tag_name, $tags)) {
					$tags[$tag_name] = array_merge($tags[$tag_name], $posts);
				} else {
					$tags[$tag_name] = $posts;
				}
			}
		}
		$this->tags = $tags;

		foreach ($this->subs as $sub) {
			foreach ($sub->tags(true) as $tag_name => $posts) {
				if (array_key_exists($tag_name, $tags)) {
					$tags[$tag_name] = array_merge($tags[$tag_name], $posts);
				} else {
					$tags[$tag_name] = $posts;
				}
			}
		}

		return $tags;
	}

	public function posts($recursive = true)
	{
		$posts = $this->posts;

		if ($recursive) {
			foreach ($this->subs as $sub) {
				$posts = array_merge($posts, $sub->posts(true));
			}
		}

		// sort by time
		uasort($posts, function($a, $b){
			if ($a->timestamp() == $b->timestamp()) {
				return 0;
			}

			if ($a->timestamp() > $b->timestamp()) {
				return -1;
			}

			return 1;
		});

		return $posts;
	}

	public function pages($recursive = true)
	{
		if (!$recursive) {
			return $this->pages;
		}

		$pages = $this->pages;

		foreach ($this->subs as $sub) {
			$pages = array_merge($pages, $sub->pages(true));
		}

		return $pages;
	}

	public function files($recursive = true)
	{
		if (!$recursive) {
			return $this->files;
		}

		$files = $this->files;

		foreach ($this->subs as $sub) {
			$files = array_merge($files, $sub->files(true));
		}

		return $files;
	}

	/*************************************************/

	private function load_config()
	{
		$path = $this->path() . '/.meta';

		if( !is_readable($path) ) {
			return false;
		}

		$config = file_get_contents( $path );
		if( false === $config ) {
			return false;
		}

		$this->config = yaml_parse( $config );

		if( is_null( $this->config ) ) {
			$this->config = array();
			return false;
		}

		return true;
	}

}
