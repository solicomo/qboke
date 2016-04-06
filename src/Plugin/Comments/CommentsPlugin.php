<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-23
 * */
namespace QBoke\Plugin\Comments;

use QBoke\Common\QBGlobal;

class CommentsPlugin
{
	public function init()
	{
		$g = QBGlobal::getInstance();

		$g->add_hook('qb_comments', array(&$this, 'comments'));
	}

	public function comments($post)
	{
		$opts = qb_options('comments');

		if (!isset($opts['enable']) ||
			($opts['enable'] !== true && $opts['enable'] !== 'true') ||
			!isset($opts['disqus_shortname']) ||
			is_null($opts['disqus_shortname'])) {
			return;
		}

		$disqus_shortname = $opts['disqus_shortname'];
		$disqus_pageurl = $post->url();
		$disqus_identifier = $post->options('disqus_identifier');

		if (isset($opt['disqus_urlbase'])) {
			$disqus_pageurl = $opt['disqus_urlbase'] + $disqus_pageurl;
		}
		
		if ($disqus_identifier === false || empty($disqus_identifier)) {
			$disqus_identifier = md5($post->url_path());
		}
		?>

		<div id="disqus_thread"></div>
		<script type="text/javascript">
			var disqus_config = function () {
				this.page.url = '<?php echo $disqus_pageurl; ?>';
				this.page.identifier = '<?php echo $disqus_identifier; ?>';
			};
			(function() {
				var d = document, s = d.createElement('script');
				s.src = '//<?php echo $disqus_shortname; ?>.disqus.com/embed.js';
				s.setAttribute('data-timestamp', +new Date());
				(d.head || d.body).appendChild(s);
			})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
		<?php
	}
}
