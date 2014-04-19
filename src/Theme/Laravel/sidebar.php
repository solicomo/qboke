<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-29
 * */

require_once __DIR__ . '/functions.php';

$sidebars = qb_options('sidebar');
?>
<!-- header start -->
<div id="header">
	<h1 id="blogname"><a href="<?php echo $site->url(); ?>"><?php echo $site->name(); ?></a></h1>
	<div id="blogsub"><?php echo $site->subhead(); ?></div>
</div>
<div class="clear"></div>
<!-- header end -->
<?php
$sidx = 0;
if (is_array($sidebars)) {
	foreach ($sidebars as $sidebar) {
		$sidx++;
		?>
		<div id="<?php echo "sidebar-$sidx"; ?>" class="widget">
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

<!-- footer start -->
<div id="footer" class="center">
	<span id="copyright">
	Copyright &copy; <?php
		$copyYear = 2013;
		$curYear = date('Y');
		echo $copyYear . (($copyYear != $curYear) ? ' - ' . $curYear : '');
	?> <a href="<?php echo $site->url(); ?>"><?php echo $site->name(); ?></a>
	</span>
	<span id="powered">
	Proudly powered by <a href="http://www.qboke.org" target="_blank">QBoke</a>.
	</span>
	<?php echo qb_options('footer'); ?>
</div>
<!-- footer end -->
