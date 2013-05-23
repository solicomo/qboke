<?php
/**
 * author: Soli soli@cbug.org
 * date  : 2013-04-29
 * */



?>
<!-- sidebar start -->
<div id="sidebar">
<?php
$sidebars = get_settings('sidebar');
$sidx = 0;
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
}/*foreach end*/ ?>
</div>
<!-- sidebar end -->
<?php





/***********************/
function get_tag_list() {
	$content = "<ul>\n";

	$home_url = blog_home_url();
	$tags = blog_tags();
	foreach (array_keys($tags) as $tag) {
		$content .= "<li class=\"taglist\"><a href=\"{$home_url}/tag/{$tag}.html\">{$tag}</a> ({$tags[$tag]})</li>\n";
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
