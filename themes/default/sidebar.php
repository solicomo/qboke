<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-29
 * */

require_once __DIR__ . '/functions.php';

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
