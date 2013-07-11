<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-20
 * */

require_once INC_DIR. '/functions.php';

class Theme {
	protected $site;
	protected $name = 'default';
	protected $full_name = 'QBoke Theme Interface';
	protected $version = '0.0.2';

	function __construct($site) {
		$this->site = $site;
	}

	function name() {
		return $this->name;
	}

	function full_name() {
		return $this->full_name;
	}

	function version() {
		return $this->version;
	}

	function dir() {
		$ref = new ReflectionClass($this);
		$dir = dirname($ref->getFileName());
		return $dir;
	}

	function url() {
		$url = $this->site->url() . substr($this->dir(), strlen(THEMES_DIR));
		return $url;
	}

	function render() {

		$path  =  $this->dir() . PATH_SEPARATOR;
		$theme = $this;
		$site  = $this->site;

		if ( $site->is_404() ) {
			header("Status: 404 Not Found");
			include $path . '404.php';
		} else if ( $site->is_post() ) {
			include $path . 'post.php';
		} else if ( $site->is_index() ) {
			include $path . 'index.php';
		} else if ( $site->is_catalog() ) {
			include $path . 'catalog.php';
		} else if ( $site->is_tag() ) {
			include $path . 'tag.php';
		} else {
			header("Status: 404 Not Found");
			include $path . '404.php';
		}
	}
}
?>
