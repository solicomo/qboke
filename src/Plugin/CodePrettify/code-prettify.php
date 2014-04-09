<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2014-03-16
 * */

if(!class_exists('CodePrettify')) {
class CodePrettify {
	function qb_header() {
		$opts = qb_options('code-prettify');

		if (!isset($opts['enable']) || ($opts['enable'] !== true && $opts['enable'] !== 'true')) {
			return;
		}

		$css_url = qb_site_url() . 'plugins/code-prettify/google-code-prettify/prettify.css';
		$custom_css = '';

		if (isset($opts['skin']) && $opts['skin'] !== 'default') {
			$css_url = qb_site_url() . 'plugins/code-prettify/google-code-prettify/' . $opts['skin'] . '.css';
		}

		if (isset($opts['custom_css']) && $opts['custom_css'] !== '') {
			$custom_css = $opts['custom_css'];
		}

		?>

		<!--wp code prettify-->
		<link id="prettify_css" href="<?php echo $css_url; ?>" type="text/css" rel="stylesheet" />
		<?php if(!empty($custom_css)) { ?>
			<style type="text/css" id="prettify_custom">
				<?php echo $custom_css; ?>
			</style>
		<?php }

		if (!isset($opts['load_pos']) || $opts['load_pos'] !== 'header') {
			return;
		}

		$js_url  = qb_site_url() . 'plugins/code-prettify/google-code-prettify/prettify.js';
		$lang_url   = '';

		if (isset($opts['lang']) && $opts['lang'] !== 'auto') {
			$lang_url = qb_site_url() . 'plugins/code-prettify/google-code-prettify/lang-' . $opts['lang'] . '.js';
		}

		if(!empty($lang_url)) { ?>
			<script type="text/javascript" src="<?php echo $lang_url; ?>"></script>
		<?php } ?>
		<script type="text/javascript" src="<?php echo $js_url; ?>"></script>
		<script type="text/javascript">
			window.onload = function(){
				prettyPrint();

				// for markdown does not add class to <pre> node.
				var codes = document.getElementsByTagName("code");
				for (var i in codes) {
					var pre = codes[i].parentNode;
					if (undefined != pre && "pre" === pre.nodeName.toLowerCase()) {
						pre.className += " " + codes[i].className;
					}
				}
			}
		</script>
		<!--//wp code prettify-->
		<?php
	}

	function qb_footer() {
		$opts = qb_options('code-prettify');

		if (!isset($opts['enable']) || ($opts['enable'] !== true && $opts['enable'] !== 'true')) {
			return;
		}

		if (isset($opts['load_pos']) && $opts['load_pos'] === 'header') {
			return;
		}

		$js_url  = qb_site_url() . 'plugins/code-prettify/google-code-prettify/prettify.js';
		$lang_url   = '';

		if (isset($opts['lang']) && $opts['lang'] !== 'auto') {
			$lang_url = qb_site_url() . 'plugins/code-prettify/google-code-prettify/lang-' . $opts['lang'] . '.js';
		}
		?>

		<!--wp code prettify-->
		<?php if(!empty($lang_url)) { ?>
			<script type="text/javascript" src="<?php echo $lang_url; ?>"></script>
		<?php } ?>
		<script type="text/javascript" src="<?php echo $js_url; ?>"></script>
		<script type="text/javascript">
			window.onload = function() {
				prettyPrint();

				// for markdown does not add class to <pre> node.
				var codes = document.getElementsByTagName("code");
				for (var i in codes) {
					var pre = codes[i].parentNode;
					if (undefined != pre && "pre" === pre.nodeName.toLowerCase()) {
						pre.className += " " + codes[i].className;
					}
				}
			}
		</script>
		<!--//wp code prettify-->
		<?php
	}
}
}

if(class_exists('CodePrettify')) {
	$code_prettify = new CodePrettify();
	add_hook('qb_header', array(&$code_prettify, 'qb_header'));
	add_hook('qb_footer', array(&$code_prettify, 'qb_footer'));
}