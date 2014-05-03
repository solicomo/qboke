<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-16
 * */
namespace QBoke\Plugin\Signature;

use QBoke\Common\QBGlobal;

$sig_plugin = new SignaturePlugin();
$g = QBGlobal::getInstance();

$g->add_hook('qb_get_content', array(&$sig_plugin, 'signature'));
