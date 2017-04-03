<?php
/**
 * author: Soli <soli@cbug.org>
 * date  : 2013-04-29
 * */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php echo $site->name(); ?></title>
	<meta name="keywords" content="<?php echo implode(",", $site->keywords()); ?>" />
	<meta name="description" content="<?php echo $site->description(); ?>" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
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
	<div id="wrap" class="container-fluid">
		<div class="row">
		<!-- sidebar start -->
		<div id="sidebar" class="col-12 col-md-4">
			<?php
			include __DIR__ . '/sidebar.php';
			?>
		</div>
		<!-- sidebar end -->
		<!-- content start -->
		<div id="content" class="col-12 offset-md-4 col-md-8">
			<!-- container start -->
			<div id="container" class="p-3 p-md-5">
