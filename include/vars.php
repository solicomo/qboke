<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-18
 * */

require_once CONVERTORS_DIR . '/convertor.php';

global $g_settings;
global $g_index;
global $g_convertors;
global $g_convertor_none;
$g_convertor_none = new Convertor;
global $g_hooks;

global $g_home_url;
global $g_req_type;	// 'index', 'tag', 'post'
global $g_posts;
global $g_cur_post;

?>
