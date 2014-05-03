<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-06-12
 * */
namespace QBoke\Parser\MarkdownExtra;

use QBoke\Common\QBGlobal;

$g = QBGlobal::getInstance();
$g->set_parser('markdownextra', __NAMESPACE__ . '\MarkdownExtraParser');
$g->set_parser('markdownex', __NAMESPACE__ . '\MarkdownExtraParser');

