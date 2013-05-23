<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-22
 * */
require_once INC_DIR . '/vars.php';

class Post {
	var $post;
	var $url;
	var $content;

	function __construct($post) {
		$this->post = $post;
	}

	function lname() {
		return $this->post['lname'];
	}

	function url() {
		if (! isset($this->url) ) {
			$this->url = blog_home_url() . '/post/' . $this->lname() . '.html';;
		}
		return $this->url;
	}

	function title() {
		return $this->post['title'];
	}

	function date() {
		return $this->post['date'];
	}

	function author() {
		return $this->post['author'];
	}

	function tags() {
		return $this->post['tags'];
	}

	function format() {
		return $this->post['format'];
	}

	function abstr() {
		return $this->post['abstr'];
	}

	function content() {
		if ( isset($this->content) ) {
			return $this->content;
		}

		$err = "File Not Found!";

		$dpath = get_data_path();
		if( false === $dpath ) {
			return $err;
		}
		$dpath = $dpath . '/' . $this->post['file'];
		if( !is_readable($dpath) ) {
			return $err;
		}

		$this->content = file_get_contents( $dpath );
		if( false === $this->content ) {
			return $err;
		}

		$convertor = get_convertor( $this->format() );
		$this->content = $convertor->go( $this->content );
		return $this->content;
	}
}

?>
