<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-06-04
 * */
class QBSite {
	private $path	= '';
	private $config	= array();
	private $catalog;

	private $status;
	private $theme;

	private $req_type;
	private $req_path;
	private $req_page;

	function __construct($path) {
		$this->path = $path;
	}

	function load() {
		if (!$this->load_config()) {
			return false;
		}

		$path = $this->path();

		if( !is_dir( $path ) ) {
			return false;
		}

		$catalog = new QBCatalog($this, '');
		$ret = $catalog->load();

		if ($ret) {
			$this->catalog = $catalog;
		}

		return $ret;
	}

	function site() {
		return $this;
	}

	function path() {
		return rtrim($this->path, '/\\');
	}

	function url_path() {
		return '';
	}

	function url() {
		$url  = $this->lname();
		return rtrim($path, '/\\') . '/';
	}

	function root() {
		return rtrim($this->url(), '/\\');
	}

	function id() {
		if (isset($this->config) && isset($this->config['id'])) {
			return $this->config['id'];
		}

		return false;
	}

	function domains() {
		if (isset($this->config) && isset($this->config['domains'])) {
			return $this->config['domains'];
		}

		return '.*';
	}

	function lname() {
		if (isset($this->config) && isset($this->config['lname'])) {
			return $this->config['lname'];
		}

		return '/';
	}

	function url_suffix() {
		if (isset($this->config) && isset($this->config['url_suffix'])) {
			return $this->config['url_suffix'];
		}

		return '.html';
	}

	function name() {
		if (isset($this->config) && isset($this->config['name'])) {
			return $this->config['name'];
		}
		return false;
	}

	function subhead() {
		if (isset($this->config) && isset($this->config['subhead'])) {
			return $this->config['subhead'];
		}
		return false;

	}

	function keywords() {
		if (isset($this->config) && isset($this->config['keywords'])) {
			return $this->config['keywords'];
		}
		return false;
	}

	function description() {
		if (isset($this->config) && isset($this->config['description'])) {
			return $this->config['description'];
		}
		return false;
	}

	function linage() {
		if (isset($this->config) && isset($this->config['linage'])) {
			return $this->config['linage'];
		}
		return false;
	}

	function catalogs($recursive = true) {
		if (!isset($this->catalog)) {
			return false;
		}

		return $this->catalog->catalogs($recursive);
	}

	function tags($recursive = true) {
		if (!isset($this->catalog)) {
			return false;
		}

		return $this->catalog->tags($recursive);
	}

	function posts($recursive = true) {
		if (!isset($this->catalog)) {
			return false;
		}

		return $this->catalog->posts($recursive);
	}

	function option($name) {
		if ( !isset($this->config) ) {
			return false;
		}

		$config = $this->config;

		if (isset($config['opts']) && isset($config['opts'][$name])) {
			return $config['opts'][$name];
		}

		return false;
	}

	/**
	 * URL formats:
	 *
	 * /[page<url_suffix>]
	 * /index[/page]<url_suffix>
	 * /catalog/<catalog url>[/page]<url_suffix>
	 * /tag/<tag>[/page]<url_suffix>
	 * /post/<post url><url_suffix>
	 *
	 * excamples:
	 *
	 * /
	 * /2.html
	 * /index.html
	 * /index/1.html
	 * /catalog/life/food.html
	 * /catalog/life/food/3.html
	 * /tag/fish.html
	 * /tag/fish/2.html
	 * /post/life/food/sweet_and_sour_fish.html
	 *
	 * */
	function parse_uri($uri) {
		$url_prefix = $this->root();
		$url_suffix = $this->url_suffix();

		if (!preg_match("@^{$url_prefix}/(.*)$@", $uri, $matches)) {
			$this->status = 404;
			return false;
		}

		$uri = $matches[1];

		if ($uri === '' || $uri === "index{$url_suffix}") {
			return parse_index_uri(1);
		}

		if (!preg_match("@^(.*){$url_suffix}$@", $uri, $matches)) {
			$this->status = 404;
			return false;
		}

		$uri = $matches[1];

		if (preg_match("@index/([\d]+)$@", $uri, $matches)) {
			return parse_index_uri($matches[1]);
		}

		if (preg_match("@post/(.*)/([\d]+)$@", $uri, $matches)) {
			return parse_catalog_uri($matches[1], $matches[2]);
		}

		if (preg_match("@post/(.*)$@", $uri, $matches)) {
			return parse_catalog_uri($matches[1], 1);
		}

		if (preg_match("@tag/(.*)/([\d]+)$@", $uri, $matches)) {
			return parse_catalog_uri($matches[1], $matches[2]);
		}

		if (preg_match("@tag/(.*)$@", $uri, $matches)) {
			return parse_catalog_uri($matches[1], 1);
		}

		if (preg_match("@catalog/(.*)/([\d]+)$@", $uri, $matches)) {
			return parse_catalog_uri($matches[1], $matches[2]);
		}

		if (preg_match("@catalog/(.*)$@", $uri, $matches)) {
			return parse_catalog_uri($matches[1], 1);
		}
	}


	function theme() {
		if (isset($this->theme)) {
			return $this->theme;
		}

		$name = 'default';
		if (isset($this->config) && isset($this->config['theme'])) {
			$name = $this->config['theme'];
		}

		$theme = get_theme($name, $this);
		load_theme_textdomain( $theme->name() );

		return $theme;
	}

	function is_404() {
		if ($this->status === 404) {
			return true;
		}
		return false;
	}

	function is_post() {
		if ($this->req_type === 'post') {
			return true;
		}
		return false;
	}

	function is_index() {
		if ($this->req_type === 'index') {
			return true;
		}
		return false;	}

	function is_catalog() {
		if ($this->req_type === 'catalog') {
			return true;
		}
		return false;
	}

	function is_tag() {
		if ($this->req_type === 'tag') {
			return true;
		}
		return false;
	}

	function get($uri) {
		$theme = $this->theme();
		$this->parse_uri($uri);
		$theme->render();
	}
	/*************************************************/

//	private
	function load_config() {
		$path = $this->path() . '/.site.json';

		if( !is_readable($path) ) {
			return false;
		}

		$config = file_get_contents( $path );
		if( false === $config ) {
			return false;
		}

		$this->config = json_decode( $config, true );

		if( json_last_error() !== JSON_ERROR_NONE) {
			$this->config = array();
			return false;
		}

		return true;
	}

	private function parse_index_uri($url, $page) {
		;
	}

	private function parse_catalog_uri($url, $page) {
		;
	}

	private function parse_tag_uri($url, $page) {
		;
	}

	private function parse_post_uri($url) {
		;
	}

}
?>