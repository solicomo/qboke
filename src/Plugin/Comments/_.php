<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-23
 * */
namespace QBoke\Plugin\Comments;

use QBoke\Common\QBGlobal;

$comments_plugin = new CommentsPlugin();
$g = QBGlobal::getInstance();

$g->add_hook('qb_comments', array(&$comments_plugin, 'comments'));

