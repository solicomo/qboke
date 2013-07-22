<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-06-05
 * */
class QBCatalog {
	private $site;
	private $parent;
	private $name	= '';
	private $config	= array();
	private $subs	= array();
	private $posts	= array();

	function __construct($parent, $name) {
		$this->parent = $parent;
		$this->name   = $name;
	}

	function load() {
		$this->load_config();

		$path = $this->path();
		if( ! $dh = opendir( $path ) ) {
			return false;
		}

		while ( ( $sub = readdir( $dh ) ) !== false ) {
			if ( $sub === '.' || $sub === '..' ) {
				continue;
			}

			//TODO filter($sub, $path)

			if( is_dir( "$path/$sub" ) ) {
				$catalog = new QBCatalog( $this, $sub );

				if ( !$catalog->load() ) {
					continue;
				}

				$this->subs[$catalog->url_path()] = $catalog;

			} elseif ( is_file("$path/$sub") ) {
				$post = new QBPost( $this, $sub );

				if ( !$post->load() ) {
					continue;
				}

				$this->posts[$post->url_path()] = $post;

				foreach ($post->tags() as $tag) {
					$this->tags[$tag][] = $post;
				}
			}
		}

		closedir( $dh );
		return true;
	}

	function site() {
		if (!isset($this->site)) {
			$this->site = $this->parent->site();
		}

		return $this->site;
	}

	function path() {
		$ppath = $this->parent->path();
		$path  = $ppath . '/' . $this->name;
		return rtrim($path, '/\\');
	}

	function url_path() {
		$purl = $this->parent->url_path();
		$url  = $purl . '/' . $this->lname();
		return rtrim($url, '/\\');
	}

	function url($page = '') {
		$site = $this->site();

		if (intval($page) > 0) {
			$url = $site->url() . 'catalog' . $this->url_path() . '/' . strval(intval($page)) . $site->url_suffix();
		} else {
			$url = $site->url() . 'catalog' . $this->url_path() . $site->url_suffix();
		}

		return $url;
	}

	function lname() {
		if (isset($this->config) && isset($this->config['lname'])) {
			return $this->config['lname'];
		}

		return $this->name;
	}

	function catalogs($recursive = true) {
		if (!$recursive) {
			return $this->subs;
		}

		$subs = $this->subs;

		foreach ($this->subs as $sub) {
			$subs = array_merge($subs, $sub->catalogs(true));
		}

		return $subs;
	}

	function tags($recursive = true) {
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

		if (!$recursive) {
			return $tags;
		}

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

	function posts($recursive = true) {
		if (!$recursive) {
			return $this->posts;
		}

		$posts = $this->posts;

		foreach ($this->subs as $sub) {
			$posts = array_merge($posts, $sub->posts(true));
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
		//TODO: sort hook

		return $posts;
	}

	/*************************************************/

	private function load_config() {
		$path = $this->path() . '/.meta.json';

		if( !is_readable($path) ) {
			return false;
		}

		$config = file_get_contents( $path );
		if( false === $config ) {
			return false;
		}

		$this->config = json_decode( $config, true );

		if( json_last_error() !== JSON_ERROR_NONE ) {
			$this->config = array();
			return false;
		}

		return true;
	}

}
?>