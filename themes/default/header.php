<?php
/**
 * author: Soli soli@cbug.org
 * date  : 2013-04-29
 * */
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo blog_name(); ?></title>
	<meta name="keywords" content="<?php echo blog_keywords(); ?>" />
	<meta name="description" content="<?php echo blog_description(); ?>" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?php echo get_theme_url(); ?>/css/style.css" />
</head>
<body>
	<!-- wrap start -->
	<div id="wrap">
		<!-- container start -->
		<div id="container">
			<!-- header start -->
			<div id="header">
				<h1 id="blogname"><?php blog_name(); ?></h1>
				<div id="blogsub"><?php blog_subhead(); ?></div>
			</div>
			<!-- header end -->
			<!-- nav start -->
			<div id="nav">
			<ul id="menus">
			<?php
			$menus = get_settings('menu');
			$midx = 0;
			foreach ($menus as $menu) {
				$midx++;
				echo "<li id=\"menu-{$midx}\"><a href=\"{$menu['url']}\">{$menu['name']}</a></li>";
			}
			?>
			</ul>
			</div>
			<!-- nav end -->
			<!-- content start -->
			<div id="content">
