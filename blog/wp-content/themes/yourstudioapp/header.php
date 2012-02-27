<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php
        global $page, $paged;
        wp_title( '|', true, 'right' );
        bloginfo( 'name' );
        if ( $paged >= 2 || $page >= 2 )
            echo ' | ' . sprintf('Page %s', max( $paged, $page ));
    ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php wp_head();?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <!--[if lt IE 9]>
        <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo YOURSTUDIOAPP_WEBSITE . '/' ?>favicon.ico" />
</head>
<body>
    <div id="header-wrapper">
        <header class="w cf">
            <h1><a href="<?php echo YOURSTUDIOAPP_WEBSITE . '/' ?>">YourStudioApp</a></h1>
			<nav>
				<ul id="yw0" class="cf">
					<li><a href="<?php echo YOURSTUDIOAPP_WEBSITE . '/' ?>">Home</a></li>
					<li><a href="<?php echo YOURSTUDIOAPP_WEBSITE . '/' ?>contact/">Contact</a></li>
					<li class="active"><a href="<?php echo YOURSTUDIOAPP_WEBSITE . '/' ?>blog/">Blog</a></li>
					<li><a href="<?php echo YOURSTUDIOAPP_WEBSITE . '/' ?>faq/">Faq</a></li>
					<li><a href="<?php echo YOURSTUDIOAPP_WEBSITE . '/' ?>tour/">Tour</a></li>
					<li><a href="<?php echo YOURSTUDIOAPP_WEBSITE . '/' ?>pricing/">Pricing</a></li>
				</ul>
            </nav>
        </header>
    </div>
	<section id="content" class="cf">
		<div class="page-header-wrapper">
			<section class="page-header">
				<h2>Blog</h2>
			</section>
		</div>
		<div class="general-page cf" id="blog">
				<div id="blog-content">
