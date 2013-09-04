<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-06-04
 * */
require_once INC_DIR. '/catalog.php';
require_once INC_DIR. '/post.php';
require_once INC_DIR. '/request.php';
require_once INC_DIR. '/response.php';

class QBSite {
	private $path	= '';
	private $config	= array();
	private $catalog;

	private $status;
	private $theme;

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

		return array('.*');
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
			return new QBRequest( QBRequestType::Error, 404 );
		}

		$uri = $matches[1];

		if ($uri === '' || $uri === "index{$url_suffix}") {
			return new QBRequest( QBRequestType::Index, null );
		}

		if (!preg_match("@^(.*){$url_suffix}$@", $uri, $matches)) {
			return new QBRequest( QBRequestType::Error, 404 );
		}

		$uri = $matches[1];

		if (preg_match("@index/([\d]+)$@", $uri, $matches)) {
			return new QBRequest( QBRequestType::Index, null, $matches[1] );
		}

		if (preg_match("@post/(.*)/([\d]+)$@", $uri, $matches)) {
			return new QBRequest( QBRequestType::Post, '/' . $matches[1], $matches[2] );
		}

		if (preg_match("@post/(.*)$@", $uri, $matches)) {
			return new QBRequest( QBRequestType::Post, '/' . $matches[1], 1 );
		}

		if (preg_match("@tag/(.*)/([\d]+)$@", $uri, $matches)) {
			return new QBRequest( QBRequestType::Tag, $matches[1], $matches[2] );
		}

		if (preg_match("@tag/(.*)$@", $uri, $matches)) {
			return new QBRequest( QBRequestType::Tag, $matches[1], 1 );
		}

		if (preg_match("@catalog/(.*)/([\d]+)$@", $uri, $matches)) {
			return new QBRequest( QBRequestType::Catalog, '/' . $matches[1], $matches[2] );
		}

		if (preg_match("@catalog/(.*)$@", $uri, $matches)) {
			return new QBRequest( QBRequestType::Catalog, '/' . $matches[1], 1 );
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

	function get($uri) {
		$request = $this->parse_uri($uri);
		$response= $this->prepare($request);
		$theme = $this->theme();
		$theme->render($response);
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

	private function prepare($request) {
		if ($request->type() === QBRequestType::Index) {
			return $this->prepare_index($request);
		}

		if ($request->type() === QBRequestType::Post) {
			return $this->prepare_post($request);
		}

		if ($request->type() === QBRequestType::Tag) {
			return $this->prepare_tag($request);
		}

		if ($request->type() === QBRequestType::Catalog) {
			return $this->prepare_catalog($request);
		}

		return $this->prepare_error($request);
	}

	private function prepare_list($request, $posts) {
		if (intval($request->page()) < 1) {
			return new QBResponse($request, QBRequestType::Error, 404);
		}

		$linage= $this->linage();
		$count = count($posts);
		$page  = $request->page();
		$page_max = ceil($count / $linage);

		if ($page > $page_max) {
			return new QBResponse($request, QBRequestType::Error, 404);
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

		$cur_posts = array_slice($posts, ($page - 1) * $linage, $linage);

		$prev  = false;
		$next = false;
		$url_prefix = $this->root();
		$url_suffix = $this->url_suffix();

		switch ($request->type()) {
			case QBRequestType::Index :
				$url_prefix .= '/index';
				break;
			case QBRequestType::Tag :
				$url_prefix .= '/tag/' . $request->url();
				break;
			case QBRequestType::Catalog :
				$url_prefix .= '/catalog' . $request->url();
				break;
			default:
				return new QBResponse($request, QBRequestType::Error, 404);
		}

		if ($page > 1) {
			$prev = array();
			$prev['name'] = 'Previous';
			$prev['url']  = $url_prefix;
			if ($page > 2) {
				$prev['url'] .= '/' . strval($page - 1);
			}
			$prev['url'] .= $url_suffix;
		}

		if ($page < $page_max) {
			$next = array();
			$next['name'] = 'Next';
			$next['url']  = $url_prefix . '/' . strval($page + 1) . $url_suffix;
		}

		$response = new QBResponse($request, $request->type(), $request->url());
		$response->set_posts($cur_posts);
		$response->set_nav($prev, $next);

		return $response;
	}

	private function prepare_index($request) {
		$posts = $this->posts();
		return $this->prepare_list($request, $posts);
	}

	private function prepare_catalog($request) {
		$catalogs = $this->catalogs();
		$url = $request->url();

		if (!array_key_exists($url, $catalogs)) {
			return new QBResponse($request, QBRequestType::Error, 404);
		}

		$catalog = $catalogs[$url];
		$posts = $catalog->posts();

		return $this->prepare_list($request, $posts);
	}

	private function prepare_tag($request) {
		$tags = $this->tags();
		$url = $request->url();

		if (!array_key_exists($url, $tags)) {
			return new QBResponse($request, QBRequestType::Error, 404);
		}

		$posts = $tags[$url];

		return $this->prepare_list($request, $posts);
	}

	private function prepare_post($request) {
		$posts = $this->posts();
		$url = $request->url();

		if (!array_key_exists($url, $posts)) {
			return new QBResponse($request, QBRequestType::Error, 404);
		}

		$cur_posts[$url] = $posts[$url];

		//find pre and next
		reset($posts);
		while(key($posts) !== $url) next($posts);
		$prev_post = prev($posts);
		next($posts);
		$next_post = next($posts);

		$prev = false;
		$next = false;
		if ($prev_post !== false) {
			$prev = array();
			$prev['name'] = $prev_post->title();
			$prev['url']  = $prev_post->url();
		}

		if ($next_post !== false) {
			$next = array();
			$next['name'] = $next_post->title();
			$next['url']  = $next_post->url();
		}

		$response = new QBResponse($request, $request->type(), $url);
		$response->set_posts($cur_posts);
		$response->set_nav($prev, $next);

		return $response;
	}

	private function prepare_error($request) {
		return new QBResponse($request, QBRequestType::Error, $request->http_code());
	}

}
