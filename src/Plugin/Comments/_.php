<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-23
 * */
namespace QBoke\Plugin\Comments;

$comments_plugin = new CommentsPlugin();
add_hook('qb_comments', array(&$comments_plugin, 'comments'));

