<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2017-04-03
 * */

require_once __DIR__ . '/functions.php';

$sidebars = qb_options('sidebar');
?>
<!-- header start -->
<div class="row">
	<div id="header" class="col text-center">
		<h1 id="blogname"><a href="<?php echo $site->url(); ?>"><?php echo $site->name(); ?></a></h1>
		<div id="blogsub"><?php echo $site->subhead(); ?></div>
	</div>
</div>
<!-- header end -->
<!-- sidebar widgets start -->
<div class="row">
	<div id="sidebar-widgets" class="col">
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
	</div>
</div>
<!-- sidebar widgets end -->
<!-- footer start -->
<div class="row">
	<div id="footer" class="col-md-4 text-center">
		<div id="copyright">
		Copyright &copy; <?php
			$copyYear = 2013;
			$curYear = date('Y');
			echo $copyYear . (($copyYear != $curYear) ? ' - ' . $curYear : '');
		?> <a href="<?php echo $site->url(); ?>"><?php echo $site->name(); ?></a>
		</div>
		<div id="powered">
		Proudly powered by <a href="http://www.qboke.org" target="_blank">QBoke</a>.
		</div>
		<?php echo qb_options('footer'); ?>
	</div>
</div>
<!-- footer end -->
