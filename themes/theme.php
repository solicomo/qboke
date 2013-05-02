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

	public function render() {
		if ( is_post() ) {
			require get_theme_dir() . '/post.php';
		} else if ( is_index() ) {
			require get_theme_dir() . '/index.php';
		} else if ( is_tag() ) {
			require get_theme_dir() . '/tag.php';
		} else {
			require get_theme_dir() . '/404.php';
		}
	}
}
?>
