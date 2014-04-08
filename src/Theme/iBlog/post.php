<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-23
 * */

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
	<div id="<?php echo "post-$pidx"; ?>" class="post rcbox">
		<div class="post-header">
			<h2 class="post-title">
				<a href="<?php echo $post->url(); ?>" title="<?php echo $post->title(); ?>">
					<?php echo $post->title(); ?>
				</a>
			</h2>
			<em class="post-meta rcbox">
				<?php
				$dt = $post->date();
				if ( $dt ) {
					echo "On $dt ";
				}
				$author = $post->author();
				if ( $author ) {
					echo "By $author";
				}
				?>
			</em>
		</div>
		<article class="post-content">
			<?php echo $post->content(); ?>
		</article>
		<div class="hl"></div>
		<div class="post-footer">
			<div class="post-tags">
				<ul><?php foreach (array_keys($post->tags()) as $tag) {
					echo '<li><a href="' . $site->url() . 'tag/' . $tag . '/1.html">' . $tag .'</a></li>';
				}?></ul>
			</div>
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
<div class="clear"></div>
</div>
<?php if ($post !== false) { ?>
<div id="<?php echo "post-$pidx-comments"; ?>" class="post-comments rcbox">
	<?php qb_comments($post); ?>
</div>
<?php } ?>
</div>
<!-- main end -->

<?php
include __DIR__ . '/sidebar.php';
include __DIR__ . '/footer.php';
?>
