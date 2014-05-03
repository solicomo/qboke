<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-18
 * */
namespace QBoke\Common;

class QBGlobal
{
	private static $g = null;

	/** declare */
	public $scms;
	public $themes;
	public $convertors;
	public $hooks;

	public $config;
	public $locale;
	public $l10n;

	public $site;
	public $request;
	public $response;
	public $theme;

	private function __construct()
	{
		$this->hooks = array();
		$this->hooks['qb_header'] = array();
		$this->hooks['qb_footer'] = array();
		$this->hooks['qb_get_content'] = array();
		$this->hooks['qb_comments'] = array();
	}

	public static function getInstance()
	{
		if (!is_null(self::$g)) {
			self::$g = new QBGlobal();
			self::$g->load();
		}

		return self::$g;
	}

	public function set_scm($name, $scm)
	{
		$this->scms[$name] = $scm;
	}

	public function get_scm($name)
	{
		if (array_key_exists($name, $this->scms) && isset($this->scms[$name])) {
			return new $this->scms[$name];
		}

		return false;
	}

	public function set_theme($name, $theme)
	{
		$this->themes[$name] = $theme;
	}

	public function get_theme($name, $site)
	{
		if (is_array($this->themes) && array_key_exists($name, $this->themes) && isset($this->themes[$name])) {
			return new $this->themes[$name]($site);
		}
		return new $g->themes['default']($site);
	}

	public function set_parser($format, $parser)
	{
		$this->parsers[$format] = $parser;
	}

	public function get_parser($format)
	{
		if (is_array($this->parsers) && array_key_exists($format, $this->parsers) && isset($this->parsers[$format]) ) {
			return new $this->parsers[$format];
		}

		return new $this->parsers['none'];
	}

	private function load()
	{
		if (!$this->load_config()) {
			header("Status: 500 Internal Server Error");
			exit('invalid config.php');
		}

		$this->load_scms();
		//load_default_textdomain();
		$this->load_plugins();
		$this->load_themes();
		$this->load_parsers();
	}

	private function load_config()
	{
		$path = ABSPATH . '/config.php';

		if( is_readable($path) ) {
			$this->config = include_once $path;
			if ( $g->config === null) {
				return false;
			}
			return true;
		}

		return false;
	}

	private function load_themes()
	{
		foreach( get_subdirs( THEME_DIR ) as $theme ) {
			include_once THEME_DIR . "/$theme/_.php";
		}
	}

	private function load_plugins()
	{
		foreach( get_subdirs( PLUGIN_DIR ) as $plugin ) {
			include_once PLUGIN_DIR . "/$plugin/_.php";
		}
	}

	private function load_scms()
	{
		foreach( get_subdirs( SCM_DIR ) as $scm ) {
			include_once SCM_DIR . "/$scm/_.php";
		}
	}

	private function load_parsers()
	{
		foreach( get_subdirs( PARSER_DIR ) as $cvt ) {
			include_once PARSER_DIR . "/$cvt/_.php";
		}
	}
}
