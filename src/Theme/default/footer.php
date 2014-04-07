<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-29
 * */
?>
			</div>
			<!-- content end -->
			<div class="clear"></div>
			<!-- footer start -->
			<div id="footer" class="center">
				<span id="copyright">
				Copyright &copy; 2013 <a href="<?php echo $site->url(); ?>"><?php echo $site->name(); ?></a>
				</span>
				<span id="powered">
				Proudly powered by <a href="http://www.qboke.org" target="_blank">QBoke</a>
				</span>
				<div id="custom_footer"><?php echo $site->options('footer'); ?></div>
			</div>
			<!-- footer end -->
		</div>
		<!-- container end -->
	</div>
	<!-- wrap end -->
	<?php
	/* Always have qb_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	qb_footer();
	?>
</body>
</html>
