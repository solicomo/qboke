<?php
/**
 * author: Soli soli@cbug.org
 * date  : 2013-04-30
 * */

require_once INC_DIR . '/functions.php';

require __DIR__ . '/header.php';
?>
<!-- main start -->
<div id="main">
<?php
$pidx = 0;
while ($post = the_post() ) {
	$pidx++;
	?>
	<div id="<?php echo "post-$pidx"; ?>" class="post rcbox">
		<div class="post-header">
			<h3 class="post-title">
				<a href="<?php echo $post->url(); ?>" title="<?php echo $post->title() ?>">
					<?php echo $post->title() ?>
				</a>
			</h3>
			<div class="post-meta">
				On <?php echo $post->date(); ?>, by <?php echo $post->author(); ?>
			</div>
		</div>
		<div class="post-content">
			<?php echo $post->content(); ?>
		</div>
		<div class="post-footer">
			<div class="post-tags">
				<ul><?php foreach ($post->tags() as $tag) {
					echo "<li>$tag</li>";
				}?></ul>
			</div>
			<div class="post-reply"><?php /* TODO: */ ?></div>
		</div>
	</div>
	<?php
}/*while end*/ ?>
</div>
<!-- main end -->

<?php
require __DIR__ . '/sidebar.php';
require __DIR__ . '/footer.php';
?>