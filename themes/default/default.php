<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-20
 * */

require_once THEMES_DIR . '/theme.php';

class Theme_Default extends Theme {
}

$theme = new Theme_Default;
set_theme($theme);
?>