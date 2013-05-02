<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-20
 * */

require_once THEMES_DIR . '/theme.php';

class Theme_Default extends Theme {
	protected $name = 'QBoke Default Theme';
	protected $version = '0.0.1';
}

$theme = new Theme_Default;
set_theme($theme);
?>
