<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-16
 * */
namespace QBoke\Plugin\Signature;

use QBoke\Hook\Functions;

$sig_plugin = new SignaturePlugin();
Functions\add_hook('qb_get_content', array(&$sig_plugin, 'signature'));
