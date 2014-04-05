<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-04-05
 * */

require_once INC_DIR . '/functions.php';

include __DIR__ . '/header.php';
?>
<!-- main start -->
<div id="main">
	<div id="post-1" class="post">
		<div class="post-header">
			<h2 class="post-title">
				<a href="<?php echo $site->url(); ?>" title="Error 404 - Page Not Found">
					Error 404 - Page Not Found
				</a>
			</h2>
		</div>
		<article class="post-content">
			<p>Sorry, what you are looking for isn't here.</p>
		</article>
	</div>
</div>
<!-- main end -->

<?php
include __DIR__ . '/footer.php';
?>
