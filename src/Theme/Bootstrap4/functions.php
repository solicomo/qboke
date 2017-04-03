<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-20
 * */

/***********************/
function get_tag_list() {
	global $site;
	$content = "<ul>\n";

	$site_url = $site->url();
	$tags = $site->tags();
	foreach ($tags as $tag => $posts) {
		$cnt = count($posts);
		$content .= "<li class=\"taglist\"><a href=\"{$site_url}tag/{$tag}/1.html\">{$tag}</a> ({$cnt})</li>\n";
	}

	$content .= "</ul>\n";
	return $content;
}

function get_blogroll(&$blogroll) {
	$content = "<ul>\n";

	foreach ($blogroll as $blog) {
		$content .= "<li class=\"taglist\"><a href=\"{$blog['url']}\" title=\"{$blog['desc']}\">{$blog['name']}</a></li>\n";
	}

	$content .= "</ul>\n";
	return $content;
}

function get_sidebar_content(&$sidebar) {
	switch ($sidebar['type']) {
		case 'text':
			echo $sidebar['content'];
			break;
		case 'tag_list':
			echo get_tag_list();
			break;
		case 'blogroll':
			echo get_blogroll($sidebar['content']);
			break;
	}
}