<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-23
 * */
namespace QBoke\Plugin\Comments;

use QBoke\Hook\Functions;

$comments_plugin = new CommentsPlugin();
Functions\add_hook('qb_comments', array(&$comments_plugin, 'comments'));

