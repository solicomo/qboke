<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-13
 * */

require_once INC_DIR . '/functions.php';

include __DIR__ . '/header.php';
?>
<!-- main start -->
<div id="main">
<?php
$pidx = 0;
foreach ($response->posts() as $post) {
	$pidx++;
	?>
	<div id="<?php echo "post-$pidx"; ?>" class="post rcbox">
		<div class="post-header">
			<h2 class="post-title">
				<a href="<?php echo $post->url(); ?>" title="<?php echo $post->title(); ?>">
					<?php echo $post->title(); ?>
				</a>
			</h2>
		</div>
		<article class="post-content">
			<?php
				echo $post->content();
			?>
		</article>
		<div class="hl"></div>
		<div class="post-footer">
			<div class="post-reply"><?php /* TODO: */ ?></div>
		</div>
	</div>
	<?php
}/*foreach end*/ ?>
<div id="main-nav" class="rcbox">
<?php
if ($response->pre_url() && $response->pre_name()) {
	?>
	<a id="main-pre" href="<?php echo $response->pre_url(); ?>"><?php echo $response->pre_name(); ?></a>
	<?php
}

if ($response->next_url() && $response->next_name()) {
	?>
	<a id="main-next" href="<?php echo $response->next_url(); ?>"><?php echo $response->next_name(); ?></a>
	<?php
}
?>
</div>
</div>
<!-- main end -->

<?php
include __DIR__ . '/sidebar.php';
include __DIR__ . '/footer.php';
?>
