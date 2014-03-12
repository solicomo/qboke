<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-29
 * */



?>
<!-- sidebar start -->
<div id="sidebar">
<?php
$sidebars = $site->option('sidebar');
$sidx = 0;
if (is_array($sidebars)) {
	foreach ($sidebars as $sidebar) {
		$sidx++;
		?>
		<div id="<?php echo "sidebar-$sidx"; ?>" class="widget rcbox">
			<?php if ($sidebar['name'] !== '') {
				echo '<h3 class="wtitle">', $sidebar['name'], "</h3>\n";
			}?>
			<div class="wcontent">
				<?php echo get_sidebar_content($sidebar); ?>
			</div>
		</div>
		<?php
	}/*foreach end*/
} ?>
</div>
<!-- sidebar end -->
<?php





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
?>
