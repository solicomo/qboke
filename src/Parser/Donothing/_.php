<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-06-12
 * */
namespace QBoke\Parser\Donothing;

use QBoke\Common\QBGlobal;

$g = QBGlobal::getInstance();
$g->set_parser('donothing', __NAMESPACE__ . '\DonothingParser');
