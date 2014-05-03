<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-16
 * */

use QBoke\Hook\Functions;

function qb_header() {
	Functions\call_hooks('qb_header');
}

function qb_footer() {
	Functions\call_hooks('qb_footer');
}

function qb_comments($post)
{
	Functions\call_hooks('qb_comments', $post);
}
