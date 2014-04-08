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
$posts = $response->posts();
$post = current($posts);
if ( $post !== false) {
	$pidx = 1;
	?>
	<div id="<?php echo "post-$pidx"; ?>" class="post">
		<div class="post-header">
			<h2 class="post-title">
				<a href="<?php echo $post->url(); ?>" title="<?php echo $post->title(); ?>">
					<?php echo $post->title(); ?>
				</a>
			</h2>
		</div>
		<article class="post-content">
			<?php echo $post->content(); ?>
		</article>

		<div class="post-footer">
		</div>
	</div>
	<?php
}/*foreach end*/ ?>
</div>
<!-- main end -->

<?php
include __DIR__ . '/footer.php';
?>
