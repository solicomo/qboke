<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-20
 * */

require_once THEMES_DIR . '/theme.php';
require_once INC_DIR. '/functions.php';

class Theme_Default extends Theme {
}

$theme = new Theme_Default;
set_theme($theme);
?>