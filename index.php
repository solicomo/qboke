<?php
/**
 * author: Soli <soli@qq.com>
 * date  : 2013-04-05
 * */

require_once('def.php');

// cache
if(is_readable(DATA_DIR . $_SERVER['REQUEST_URI'])
{
	readfile(DATA_DIR . $_SERVER['REQUEST_URI']);
	exit;
}

// re-generate

?>
