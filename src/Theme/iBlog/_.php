<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-06-12
 * */
namespace QBoke\Theme\iBlog;

use QBoke\Common\QBGlobal;

$g = QBGlobal::getInstance();
$g->set_theme('default', __NAMESPACE__ . '\iBlogTheme');
$g->set_theme('iblog', __NAMESPACE__ . '\iBlogTheme');
