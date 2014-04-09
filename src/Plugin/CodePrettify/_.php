<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-14
 * */
namespace QBoke\Plugin\CodePrettify;

$code_prettify = new CodePrettifyPlugin();
add_hook('qb_header', array(&$code_prettify, 'header'));
add_hook('qb_footer', array(&$code_prettify, 'footer'));
