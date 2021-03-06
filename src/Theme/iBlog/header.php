<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-29
 * */
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo $site->name(); ?></title>
	<meta name="keywords" content="<?php echo implode(",", $site->keywords()); ?>" />
	<meta name="description" content="<?php echo $site->description(); ?>" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?php echo $theme->url(); ?>/css/style.css" />
	<?php
	/* Always have qb_header() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	qb_header();
	?>
</head>
<body>
	<!-- wrap start -->
	<div id="wrap">
		<!-- container start -->
		<div id="container">
			<!-- header start -->
			<div id="header">
				<h1 id="blogname"><a href="<?php echo $site->url(); ?>"><?php echo $site->name(); ?></a></h1>
				<div id="blogsub"><?php echo $site->subhead(); ?></div>
			</div>
			<div class="clear"></div>
			<!-- header end -->
			<!-- nav start -->
			<div id="nav">
			<ul id="menus">
			<?php
			$menus = $site->options('menu');
			$midx = 0;
			if (is_array($menus)) {
				foreach ($menus as $menu) {
					$midx++;
					echo "<li id=\"menu-{$midx}\"><a href=\"{$menu['url']}\">{$menu['name']}</a></li>";
				}
			}
			?>
			</ul>
			</div>
			<!-- nav end -->
			<!-- content start -->
			<div id="content">
