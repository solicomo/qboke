<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-22
 * */

class Post {
	var $post;
	var $url;

	function __construct($post) {
		$this->post = $post;
		$this->url = blog_home_url() . '/post/' . $this->post['lname'] . '.html';
	}

	function url() {
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

	function abstr() {
		return $this->post['abstr'];
	}

	function tags() {
		return $this->post['tags'];
	}
}

?>
