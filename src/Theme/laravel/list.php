<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-29
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
	<div id="<?php echo "post-$pidx"; ?>" class="post">
		<div class="post-header">
			<h2 class="post-title">
				<a href="<?php echo $post->url(); ?>" title="<?php echo $post->title(); ?>">
					<?php echo $post->title(); ?>
				</a>
			</h2>
		</div>
		<div class="clear"></div>
		<article class="post-content">
			<?php
				echo $post->excerpt() . '<span class="post-more"><a href="' . $post->url() . '">...</a></span>';
			?>
		</article>
		<div class="hl"></div>
		<div class="post-footer">
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
		<div class="clear"></div>
	</div>
	<?php
}/*foreach end*/ ?>
</div>
<!-- main end -->

<!-- main-nav start -->
<div id="main-nav" class="rcbox">
<?php
$is_nav = false;
if ($response->pre_url() && $response->pre_name()) {
	$is_nav = true;
	?>
	<a id="main-pre" href="<?php echo $response->pre_url(); ?>"><?php echo $response->pre_name(); ?></a>
	<?php
}

if ($response->next_url() && $response->next_name()) {
	$is_nav = true;
	?>
	<a id="main-next" href="<?php echo $response->next_url(); ?>"><?php echo $response->next_name(); ?></a>
	<?php
}

if (!$is_nav) {
	echo '<center style="color: #ccc;">NOT ANY MORE</center>';
}
?>
<div class="clear"></div>
</div>
<!-- main-nav end -->

<?php
include __DIR__ . '/footer.php';
?>
