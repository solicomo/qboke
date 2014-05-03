<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-14
 * */
namespace QBoke\Plugin\CodePrettify;

use QBoke\Common\QBGlobal;

$code_prettify = new CodePrettifyPlugin();
$g = QBGlobal::getInstance();

$g->add_hook('qb_header', array(&$code_prettify, 'header'));
$g->add_hook('qb_footer', array(&$code_prettify, 'footer'));
