<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<html lang="en">

<!--
 header
-->

<head>
	<meta charset="utf-8">
	<title>Led Notification</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Administration Site WEB">
	<meta name="author" content="Sarrailh Rémi">

	<!-- CSS Loading -->
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet">


	<style>

	.right
	{
		position:relative;
		float:right;
	}

	#alert_zone
	{
		top:45px;
	}

	


	#scroll_menu
	{
		background-color:#eee;
		top:100px;
		width:128px;
		text-transform: capitalize;
	}
	

	</style>
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->

			<!-- Fav icons -->

		</head>
		<body>
<div id="alert_zone" class="navbar-fixed-top"></div>

		<?php echo $menu;?>
		
