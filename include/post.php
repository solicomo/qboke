<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-22
 * */
require_once INC_DIR . '/global.php';

class QBPost {
	private $site;
	private $parent;
	private $name;
	private $config = array();
	private $title;
	private $date;
	private $tags;
	private $content;

	function __construct($parent, $name) {
		$this->parent = $parent;
		$this->name = $name;
	}

	function load() {
		$this->load_config();
		return true;
	}

	function site() {
		if (!isset($this->site)) {
			$this->site = $this->parent->site();
		}

		return $this->site;
	}

	function catalog() {
		return $this->parent;
	}

	function path() {
		$ppath = $this->parent->path();
		$path  = $ppath . '/' . $this->name;
		return rtrim($path, '/\\');
	}

	function url_path() {
		$purl = $this->parent->url_path();
		$url  = $purl . '/' . $this->slug();
		return rtrim($url, '/\\');
	}

	function url() {
		$site = $this->site();
		$url = $site->root() . $this->url_path() . $site->url_suffix();

		return $url;
	}

	function slug() {
		if (isset($this->config) && isset($this->config['slug'])) {
			return $this->config['slug'];
		}

		return $this->name;
	}

	function title() {
		if (isset($this->title)) {
			return $this->title;
		}

		if (isset($this->config) && isset($this->config['title'])) {
			$this->title = $this->config['title'];
			return $this->title;
		}

		if (preg_match('@<h1[^>]*>([^<]*)</h1>@i', $this->content(), $matches)) {
			$this->title = $matches[1];
			return $this->title;
		}

		$dot_pos = strrpos($this->name, '.');

		if ($dot_pos > 0) {
			$this->title = substr($this->name, 0, $dot_pos);
		} else {
			$this->title = $this->name;
		}

		return $this->title;
	}

	function author() {
		if (isset($this->config) && isset($this->config['author'])) {
			return $this->config['author'];
		}

		return false;
	}

	function date() {
		if (isset($this->date)) {
			return $this->date;
		}

		if (isset($this->config) && isset($this->config['date'])) {
			$this->date = $this->config['date'];

			if ( ! is_string( $this->date ) ) {
				$datetime = new DateTime();
				$datetime->setTimestamp( $this->date );
				$this->date = $datetime->format('Y-m-d H:i:s');
			}

			return $this->date;
		}

		$path = $this->path();
		$fstat = stat($path);
		if ($fstat === false) {
			$datetime = new DateTime();
			$this->date = $datetime->format('Y-m-d H:i:s');
			return $this->date;
		}

		$datetime = new DateTime("@{$fstat['ctime']}");
		$this->date = $datetime->format('Y-m-d H:i:s');
		return $this->date;
	}

	function timestamp() {
		$datetime = new DateTime($this->date());
		return $datetime->getTimestamp();
	}

	function tags() {
		if (isset($this->tags)) {
			return $this->tags;
		}

		$this->tags = array();

		if (!isset($this->config) || !isset($this->config['tags'])) {
			return $this->tags;
		}

		if (!is_array($this->config['tags'])) {
			$this->config['tags'] = explode(',', $this->config['tags']);
		}

		$this->config['tags'] = array_unique($this->config['tags']);

		foreach ($this->config['tags'] as $tag) {
			$this->tags[$tag][$this->url_path()] = $this;
		}

		return $this->tags;
	}

	function format() {
		if (isset($this->config) && isset($this->config['format'])) {
			return $this->config['format'];
		}

		return 'none';
	}

	function type() {
		if (isset($this->config) && isset($this->config['type'])) {
			return $this->config['type'];
		}

		return 'post';
	}

	function excerpt() {
		if (isset($this->config) && isset($this->config['excerpt'])) {
			return $this->config['excerpt'];
		}

		return $this->content();
	}

	public function options($name) {
		if ( !isset($this->config) ) {
			return false;
		}

		$config = $this->config;

		if (isset($config['opts']) && isset($config['opts'][$name])) {
			return $config['opts'][$name];
		}

		return false;
	}

	function content() {
		if ( isset($this->content) ) {
			return $this->content;
		}

		$err = "File Not Found!";

		$path = $this->path();
		if( !is_readable($path) ) {
			return $err;
		}

		// get rid of YAML Front Matter
		$offset = -1;
		$fh = @fopen($path, 'r');

		if (!$fh) {
			return $err;
		}

		$fline = fgets($fh);

		if ($fline !== false && trim($fline) === '<!--') {
			while (($line = fgets($fh)) !== false) {
				if (trim($line) === '-->') {
					break;
				}
			}

			$offset = ftell($fh);
		}

		fclose($fh);

		// content without YAML Front Matter
		$this->content = file_get_contents( $path, false, NULL, $offset );
		if( false === $this->content ) {
			return $err;
		}

		$convertor = get_convertor( $this->format() );
		$this->content = $convertor->go( $this->content );

		$this->content = call_hooks('qb_get_content', $this->content);
		return $this->content;
	}

	/*************************************************/

	private function load_config() {
		$path = $this->path();

		if( !is_readable($path) ) {
			return false;
		}

		$fh = @fopen($path, 'r');

		if (!$fh) {
			return false;
		}

		$fline = fgets($fh);

		if ($fline === false || trim($fline) !== '<!--') {
			return false;
		}

		$config = '';

		while (($line = fgets($fh)) !== false) {
			if (trim($line) === '-->') {
				break;
			}

			$config .= $line;
		}

		fclose($fh);

		//$this->config = json_decode( $config, true );
		$this->config = yaml_parse( $config );

		if( is_null( $this->config ) ) {
			$this->config = array();
			return false;
		}

		return true;
	}

}

