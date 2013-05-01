<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-20
 * */

require_once INC_DIR. '/functions.php';

class Theme {
	protected $name = 'QBoke Theme Interface';
	protected $version = '0.0.1';

	public function name() {
		return $this->name;
	}

	public function version() {
		return $this->version;
	}

	public function dir() {
		return __DIR__;
	}

	public function render() {
		if ( is_post() ) {
			require $this->dir() . '/post.php';
		} else if ( is_index() ) {
			require $this->dir() . '/index.php';
		} else if ( is_tag() ) {
			require $this->dir() . '/tag.php';
		} else {
			require $this->dir() . '/404.php';
		}
	}
}
?>
