<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-23
 * */

if(!class_exists('QBComments')) {
class QBComments {
	function qb_comments($post) {
		$opts = qb_options('comments');

		if (!isset($opts['enable']) ||
			($opts['enable'] !== true && $opts['enable'] !== 'true') ||
			!isset($opts['disqus_shortname']) ||
			is_null($opts['disqus_shortname'])) {
			return;
		}

		$disqus_shortname = $opts['disqus_shortname'];
		$disqus_identifier = $post->options('disqus_identifier');
		$disqus_disable_mobile = $opts['disqus_disable_mobile'] == true ? 'true' : 'false';

		if ($disqus_identifier !== false || empty($disqus_identifier)) {
			$disqus_identifier = md5($post->url_path());
		}
		?>

		<div id="disqus_thread"></div>
		<script type="text/javascript">
			var disqus_shortname = '<?php echo $disqus_shortname; ?>';
			var disqus_identifier = '<?php echo $disqus_identifier; ?>';
			var disqus_disable_mobile = '<?php echo $disqus_disable_mobile; ?>';

			(function() {
				var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
		<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
		<?php
	}
}
}

if(class_exists('QBComments')) {
	$qb_comments = new QBComments();
	add_hook('qb_comments', array(&$qb_comments, 'qb_comments'));
}