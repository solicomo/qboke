<?php
/**
 * @author: Soli <soli@cbug.org>
 * @date  : 2013-06-04
 * */
namespace QBoke\Site;

use QBoke\Site\QBCatalog;
use QBoke\Site\QBPost;
use QBoke\Site\QBRequest;
use QBoke\Site\QBResponse;
use QBoke\Common\QBGlobal;

class QBSite
{
	private $path	= '';
	private $config	= array();
	private $catalog;

	private $catalogs;
	private $posts;
	private $pages;
	private $files;
	private $tags;

	private $status;
	private $theme;

	public function __construct()
	{
		$this->path = DATA_DIR;
	}

	public function init()
	{
		$g = QBGlobal::getInstance();
		$g->site = $this;

		if (!$this->load_config()) {
			return false;
		}

		$this->load_plugins();

		return $this->load();
	}

	private function load_config()
	{
		$path = $this->path() . '/.site';

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

	private function load_plugins()
	{
		foreach( $this->config['plugins'] as $plugin ) {
			$c = "\\QBoke\\Plugin\\$plugin\\$plugin" . "Plugin";

			if (class_exists($c)) {
				$p = new $c;
				$p->init();
			}
		}
	}

	private function load()
	{
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

	public function site()
	{
		return $this;
	}

	public function path()
	{
		return rtrim($this->path, '/\\');
	}

	public function url_path()
	{
		return '';
	}

	public function url()
	{
		$url  = $this->slug();
		return rtrim($url, '/\\') . '/';
	}

	public function root()
	{
		return rtrim($this->url(), '/\\');
	}

	public function id()
	{
		if (isset($this->config) && isset($this->config['id'])) {
			return $this->config['id'];
		}

		return false;
	}

	public function domains()
	{
		if (isset($this->config) && isset($this->config['domains'])) {
			return $this->config['domains'];
		}

		return array('.*');
	}

	public function slug()
	{
		if (isset($this->config) && isset($this->config['slug'])) {
			return $this->config['slug'];
		}

		return '/';
	}

	public function url_suffix()
	{
		if (isset($this->config) && isset($this->config['url_suffix'])) {
			return $this->config['url_suffix'];
		}

		return '.html';
	}

	public function name()
	{
		if (isset($this->config) && isset($this->config['name'])) {
			return $this->config['name'];
		}
		return false;
	}

	public function subhead()
	{
		if (isset($this->config) && isset($this->config['subhead'])) {
			return $this->config['subhead'];
		}
		return false;

	}

	public function keywords()
	{
		if (isset($this->config) && isset($this->config['keywords'])) {
			return $this->config['keywords'];
		}
		return false;
	}

	public function description()
	{
		if (isset($this->config) && isset($this->config['description'])) {
			return $this->config['description'];
		}
		return false;
	}

	public function linage()
	{
		if (!isset($this->config) || !isset($this->config['linage'])) {
			return 1;
		}

		$linage = intval($this->config['linage']);

		if ( $linage < 1 ) {
			return 1;
		}

		return $linage;
	}

	public function catalogs()
	{
		if (isset($this->catalogs)) {
			return $this->catalogs;
		}

		if (!isset($this->catalog)) {
			return false;
		}

		$this->catalogs = $this->catalog->catalogs(true);
		return $this->catalogs;
	}

	public function tags()
	{
		if (isset($this->tags)) {
			return $this->tags;
		}

		if (!isset($this->catalog)) {
			return false;
		}

		$this->tags = $this->catalog->tags(true);
		return $this->tags;
	}

	public function posts()
	{
		if (isset($this->posts)) {
			return $this->posts;
		}

		if (!isset($this->catalog)) {
			return false;
		}

		$this->posts = $this->catalog->posts(true);
		return $this->posts;
	}

	public function pages()
	{
		if (isset($this->pages)) {
			return $this->pages;
		}

		if (!isset($this->catalog)) {
			return false;
		}

		$this->pages = $this->catalog->pages(true);
		return $this->pages;
	}

	public function files()
	{
		if (isset($this->files)) {
			return $this->files;
		}

		if (!isset($this->catalog)) {
			return false;
		}

		$this->files = $this->catalog->files(true);
		return $this->files;
	}

	public function options($name)
	{
		if ( !isset($this->config) ) {
			return false;
		}

		$config = $this->config;

		if (isset($config['options']) && isset($config['opts'][$name])) {
			return $config['options'][$name];
		}

		return false;
	}

	/**
	 * URL formats:
	 *
	 * /<file url>
	 * /<page url><url_suffix>
	 * /<post url><url_suffix>
	 * /[<page><url_suffix>]
	 * /<catalog url>/<page><url_suffix>
	 * /tag/<tag>/<page><url_suffix>
	 *
	 * excamples:
	 *
	 * /screen.jpg
	 * /about.html
	 * /life/food/sweet_and_sour_fish.html
	 * /
	 * /2.html
	 * /life/food/1.html
	 * /life/food/3.html
	 * /tag/fish/1.html
	 * /tag/fish/2.html
	 *
	 * */
	private function parse_uri($uri)
	{
		$url_prefix = $this->root();
		$url_suffix = $this->url_suffix();

		if (!preg_match("@^{$url_prefix}/(.*)$@", $uri, $matches)) {
			return new QBRequest( QBRequest::TYPE_ERROR, 404 );
		}

		$uri = '/' . $matches[1];

		$files = $this->files();
		if (array_key_exists($uri, $files)) {
			return new QBRequest(QBRequest::TYPE_FILE, $uri);
		}

		if ($uri === '/') {
			return new QBRequest( QBRequest::TYPE_INDEX, null );
		}

		if (!preg_match("@^(.*){$url_suffix}$@", $uri, $matches)) {
			return new QBRequest( QBRequest::TYPE_ERROR, 404 );
		}

		$uri = $matches[1];

		$pages = $this->pages();
		if (array_key_exists($uri, $pages)) {
			return new QBRequest(QBRequest::TYPE_PAGE, $uri);
		}

		$posts = $this->posts();
		if (array_key_exists($uri, $posts)) {
			return new QBRequest(QBRequest::TYPE_POST, $uri);
		}

		if (preg_match("@^/([\d]+)$@", $uri, $matches)) {
			return new QBRequest( QBRequest::TYPE_INDEX, null, $matches[1] );
		}

		if (preg_match("@^/tag/(.*)/([\d]+)$@", $uri, $matches)) {
			$tags = $this->tags();
			$tag  = $matches[1];
			if (array_key_exists($tag, $tags)) {
				return new QBRequest(QBRequest::TYPE_TAG, $tag, $matches[2]);
			}
		}

		if (preg_match("@^(.*)/([\d]+)$@", $uri, $matches)) {
			return new QBRequest( QBRequest::TYPE_CATALOG, '/' . $matches[1], $matches[2] );
		}

		return new QBRequest( QBRequest::TYPE_ERROR, 404 );
	}

	public function theme()
	{
		if (isset($this->theme)) {
			return $this->theme;
		}

		$name = 'Laravel';
		if (isset($this->config) && isset($this->config['theme'])) {
			$name = $this->config['theme'];
		}

		$theme_cls = "\\QBoke\\Theme\\$name\\$name" . 'Theme';

		if (!class_exists($theme_cls)) {
			return null;
		}

		$theme = new $theme_cls($this);
		//load_theme_textdomain( $theme->name() );

		return $theme;
	}

	public function get($uri, $return = false)
	{
		$g = QBGlobal::getInstance();

		$g->request  = $this->parse_uri($uri);
		$g->response = $this->prepare($g->request);
		$g->theme    = $this->theme();
		$g->theme->render($g->response);
	}

	public function dump()
	{
		// index
		$this->dump_index();
		// catalogs
		$this->dump_catalogs();
		// tags
		$this->dump_tags();
		// files
		$this->dump_files();
		// posts
		$this->dump_posts();
		// pages
		$this->dump_pages();
	}

	private function dump_index()
	{
		$g = QBGlobal::getInstance();
		$url_suffix = $this->url_suffix();
		$posts = $this->posts();
		$linage= $this->linage();
		$count = count($posts);
		$page_max = ceil($count / $linage);

		for ($i = 1; $i <= $page_max; $i++) {
			$g->request  = new QBRequest(QBRequest::TYPE_INDEX, null, $i);
			$g->response = $this->prepare($g->request);
			$g->theme    = $this->theme();
			$content    = $g->theme->render($g->response, true);

			$path = PUBLIC_DIR . "/$i" . $url_suffix;
			echo "dump $path <br />\n";
			file_put_contents($path, $content);

			if ($i === 1) {
				$path = PUBLIC_DIR . "/index" . $url_suffix;
				echo "dump $path <br />\n";
				file_put_contents($path, $content);
			}
		}
	}

	private function dump_catalogs()
	{
		$g = QBGlobal::getInstance();

		$url_suffix = $this->url_suffix();
		$linage     = $this->linage();
		$catalogs   = $this->catalogs();

		foreach ($catalogs as $cata_url => $cata) {
			$posts = $cata->posts();
			$count = count($posts);
			$page_max = ceil($count / $linage);

			for ($i = 1; $i <= $page_max; $i++) {
				$g->request  = new QBRequest(QBRequest::TYPE_CATALOG, $cata_url, $i);
				$g->response = $this->prepare($g->request);
				$g->theme    = $this->theme();
				$content    = $g->theme->render($g->response, true);

				$path = PUBLIC_DIR . "$cata_url/$i" . $url_suffix;
				mkdir_p(dirname($path));

				echo "dump $path <br />\n";
				file_put_contents($path, $content);

				if ($i === 1) {
					$path = PUBLIC_DIR . "$cata_url/index" . $url_suffix;
					echo "dump $path <br />\n";
					file_put_contents($path, $content);
				}
			}
		}
	}

	private function dump_tags()
	{
		$g = QBGlobal::getInstance();

		$url_suffix = $this->url_suffix();
		$linage     = $this->linage();
		$tags       = $this->tags();

		foreach ($tags as $tag => $posts) {
			$count = count($posts);
			$page_max = ceil($count / $linage);

			for ($i = 1; $i <= $page_max; $i++) {
				$g->request  = new QBRequest(QBRequest::TYPE_TAG, $tag, $i);
				$g->response = $this->prepare($g->request);
				$g->theme    = $this->theme();
				$content    = $g->theme->render($g->response, true);

				$path = PUBLIC_DIR . "/tag/$tag/$i" . $url_suffix;
				mkdir_p(dirname($path));

				echo "dump $path <br />\n";
				file_put_contents($path, $content);

				if ($i === 1) {
					$path = PUBLIC_DIR . "/tag/$tag/index" . $url_suffix;
					echo "dump $path <br />\n";
					file_put_contents($path, $content);
				}
			}
		}
	}

	private function dump_files()
	{
		$files = $this->files();

		foreach ($files as $dst => $src) {
			$dst = PUBLIC_DIR . $dst;

			echo "copy($src, $dst) <br />\n";
			real_copy($src, $dst);
		}
	}

	private function dump_posts()
	{
		$g = QBGlobal::getInstance();

		$url_suffix = $this->url_suffix();
		$posts      = $this->posts();

		foreach ($posts as $url => $post) {
			$g->request  = new QBRequest(QBRequest::TYPE_POST, $url);
			$g->response = $this->prepare($g->request);
			$g->theme    = $this->theme();
			$content    = $g->theme->render($g->response, true);

			$path = PUBLIC_DIR . "$url" . $url_suffix;
			mkdir_p(dirname($path));

			echo "dump $path <br />\n";
			file_put_contents($path, $content);
		}
	}

	private function dump_pages()
	{
		$g = QBGlobal::getInstance();

		$url_suffix = $this->url_suffix();
		$pages      = $this->pages();

		foreach ($pages as $url => $page) {
			$g->request  = new QBRequest(QBRequest::TYPE_PAGE, $url);
			$g->response = $this->prepare($g->request);
			$g->theme    = $this->theme();
			$content    = $g->theme->render($g->response, true);

			$path = PUBLIC_DIR . "$url" . $url_suffix;
			mkdir_p(dirname($path));

			echo "dump $path <br />\n";
			file_put_contents($path, $content);
		}
	}

	private function prepare($request)
	{
		if ($request->type() === QBRequest::TYPE_INDEX) {
			return $this->prepare_index($request);
		}

		if ($request->type() === QBRequest::TYPE_POST) {
			return $this->prepare_post($request);
		}

		if ($request->type() === QBRequest::TYPE_PAGE) {
			return $this->prepare_page($request);
		}

		if ($request->type() === QBRequest::TYPE_FILE) {
			return $this->prepare_file($request);
		}

		if ($request->type() === QBRequest::TYPE_TAG) {
			return $this->prepare_tag($request);
		}

		if ($request->type() === QBRequest::TYPE_CATALOG) {
			return $this->prepare_catalog($request);
		}

		return $this->prepare_error($request);
	}

	private function prepare_list($request, $posts)
	{
		if (intval($request->page()) < 1) {
			return new QBResponse($request, QBRequest::TYPE_ERROR, 404);
		}

		$linage= $this->linage();
		$count = count($posts);
		$page  = $request->page();
		$page_max = ceil($count / $linage);

		if ($page > $page_max) {
			return new QBResponse($request, QBRequest::TYPE_ERROR, 404);
		}

		//TODO: sort hook

		$cur_posts = array_slice($posts, ($page - 1) * $linage, $linage);

		$prev = false;
		$next = false;
		$url_suffix = $this->url_suffix();
		$url_prefix = $this->root();

		if ($request->type() === QBRequest::TYPE_TAG) {
			$url_prefix .= '/tag/' . $request->url();
		} elseif ($request->type() !== QBRequest::TYPE_INDEX) {
			$url_prefix .= '/' . $request->url();
		}


		if ($page > 1) {
			$prev = array();
			$prev['name'] = 'Previous';
			$prev['url']  = $url_prefix . '/' . strval($page - 1) . $url_suffix;
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

	private function prepare_index($request)
	{
		$posts = $this->posts();
		return $this->prepare_list($request, $posts);
	}

	private function prepare_catalog($request)
	{
		$catalogs = $this->catalogs();
		$url = $request->url();

		if (!array_key_exists($url, $catalogs)) {
			return new QBResponse($request, QBRequest::TYPE_ERROR, 404);
		}

		$catalog = $catalogs[$url];
		$posts = $catalog->posts();

		return $this->prepare_list($request, $posts);
	}

	private function prepare_tag($request)
	{
		$tags = $this->tags();
		$url = $request->url();

		if (!array_key_exists($url, $tags)) {
			return new QBResponse($request, QBRequest::TYPE_ERROR, 404);
		}

		$posts = $tags[$url];

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

		return $this->prepare_list($request, $posts);
	}

	private function prepare_post($request)
	{
		$posts = $this->posts();
		$url = $request->url();

		if (!array_key_exists($url, $posts)) {
			return new QBResponse($request, QBRequest::TYPE_ERROR, 404);
		}

		$cur_posts[$url] = $posts[$url];

		//find pre and next
		reset($posts);
		$prev_post = false;
		$next_post = false;
		while(key($posts) !== $url) {
			$prev_post = current($posts);
			next($posts);
		}
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

	private function prepare_page($request)
	{
		$pages = $this->pages();
		$url = $request->url();

		$cur_pages[$url] = $pages[$url];

		$response = new QBResponse($request, $request->type(), $url);
		$response->set_posts($cur_pages);

		return $response;
	}

	private function prepare_file($request)
	{
		$files = $this->files();
		$url = $request->url();

		$cur_file = $files[$url];

		$response = new QBResponse($request, $request->type(), $url);
		$response->set_path($cur_file);

		return $response;
	}

	private function prepare_error($request)
	{
		return new QBResponse($request, QBRequest::TYPE_ERROR, $request->http_code());
	}

}
