<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-20
 * */

require_once THEMES_DIR . '/theme.php';

class DefaultTheme extends Theme {
	protected $name = 'default';
	protected $full_name = 'QBoke Default Theme';
	protected $version = '0.0.2';
}

set_theme('default', 'DefaultTheme');
?>
