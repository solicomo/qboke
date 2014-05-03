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
	public $parsers;
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
		if (is_null(self::$g)) {
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
		return new $this->themes['default']($site);
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

	public function add_hook( $tag, $callback, $priority = 10 ) {
		$idx = _build_hook_id($tag, $callback, $priority);

		if ($idx === false) {
			return false;
		}

		$this->hooks[$tag][$priority][$idx] = $callback;
		return true;
	}

	public function del_hook( $tag, $callback, $priority = 10 ) {
		$idx = _build_hook_id($tag, $callback, $priority);

		if ($idx === false) {
			return false;
		}

		unset($this->hooks[$tag][$priority][$idx]);

		if (empty($this->hooks[$tag][$priority])) {
			unset($this->hooks[$tag][$priority]);
		}

		return true;
	}

	public function call_hooks( $tag, $value = '' ) {
		$args = func_get_args();

		ksort($this->hooks[$tag]);

		foreach ($this->hooks[$tag] as $callbacks) {
			foreach ($callbacks as $cb) {
				if (!is_null($cb)) {
					$args[1] = $value;
					$value = call_user_func_array($cb, array_slice($args, 1));
				}
			}
		}

		return $value;
	}

	private function _build_hook_id($tag, $function, $priority) {
		static $hook_id_count = 0;

		if ( is_string($function) )
			return $function;

		if ( is_object($function) ) {
			// Closures are currently implemented as objects
			$function = array( $function, '' );
		} else {
			$function = (array) $function;
		}

		if (is_object($function[0]) ) {
			// Object Class Calling
			if ( function_exists('spl_object_hash') ) {
				return spl_object_hash($function[0]) . $function[1];
			}

			$obj_idx = get_class($function[0]).$function[1];

			if ( !isset($function[0]->qb_filter_id) ) {
				$obj_idx .= $hook_id_count;
				++$hook_id_count;
			} else {
				$obj_idx .= $function[0]->qb_filter_id;
			}

			return $obj_idx;

		} else if ( is_string($function[0]) ) {
			// Static Calling
			return $function[0] . '::' . $function[1];
		}

		return false;
	}

	private function load()
	{
		if (!$this->load_config()) {
			header("Status: 500 Internal Server Error");
			exit('invalid config.php');
		}

		//load_default_textdomain();
		$this->load_plugins();
	}

	private function load_config()
	{
		$path = ABSPATH . '/config.php';

		if( is_readable($path) ) {
			$this->config = include_once $path;
			if ( $this->config === null) {
				return false;
			}
			return true;
		}

		return false;
	}

	private function load_plugins()
	{
		foreach( \get_subdirs( PLUGIN_DIR ) as $plugin ) {
			include_once PLUGIN_DIR . "/$plugin/_.php";
		}
	}
}
