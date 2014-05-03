<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-14
 * */
namespace QBoke\Plugin\CodePrettify;

use QBoke\Hook\Functions;

$code_prettify = new CodePrettifyPlugin();
Functions\add_hook('qb_header', array(&$code_prettify, 'header'));
Functions\add_hook('qb_footer', array(&$code_prettify, 'footer'));
