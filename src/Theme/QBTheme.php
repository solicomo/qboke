<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-20
 * */
namespace QBoke\Theme;

use \ReflectionClass;
use QBoke\Hook\Hooks;
use QBoke\Common\Functions;

class QBTheme
{
	protected $site;
	protected $name = 'default';
	protected $full_name = 'QBoke Theme Interface';
	protected $version = '0.0.2';

	public function __construct($site)
	{
		$this->site = $site;
	}

	public function name()
	{
		return $this->name;
	}

	public function full_name()
	{
		return $this->full_name;
	}

	public function version()
	{
		return $this->version;
	}

	public function dir()
	{
		$ref = new ReflectionClass($this);
		$dir = dirname($ref->getFileName());
		return $dir;
	}

	public function url()
	{
		$url = qb_theme_url() . substr($this->dir(), strlen(THEME_DIR));
		return $url;
	}

	public function render($response, $return = false)
	{
		if ($return) {
			ob_start();
		}

		$this->do_render($response);

		if ($return) {
			return ob_get_clean();
		}
	}

	protected function do_render($response)
	{
		$theme_path  = $this->dir();
		$theme = $this;
		$site  = $this->site;

		if ( $response->is_error() ) {
			header("Status: 404 Not Found");
			include $theme_path . '/404.php';
		} else if ( $response->is_post() ) {
			include $theme_path . '/post.php';
		} else if ( $response->is_page() ) {
			include $theme_path . '/page.php';
		} else if ( $response->is_index() ) {
			include $theme_path . '/index.php';
		} else if ( $response->is_catalog() ) {
			include $theme_path . '/catalog.php';
		} else if ( $response->is_tag() ) {
			include $theme_path . '/tag.php';
		} else if ( $response->is_file() ) {
			$file = $response->path();
			if ($file !== false && file_exists($file)) {
				header('Content-Type: ' . mime($file));
				header('Content-Length: ' . filesize($file));
				ob_clean();
				flush();
				@readfile($file);
			} else {
				header("Status: 404 Not Found");
				include $theme_path . '/404.php';
			}
		} else {
			header("Status: 404 Not Found");
			include $theme_path . '/404.php';
		}
	}
}
