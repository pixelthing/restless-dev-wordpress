<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Career_Spark
 * @since Career Spark 1.0
 */

// HEADER IMAGE

// Check to see if the header image has been removed
$header_image = get_header_image();
if ( $header_image AND DESIGNSWITCH == 'high' ) :
  $header_image_width = HEADER_IMAGE_WIDTH;
	// The header image
	// Check if this is a post or page, if it has a thumbnail, and if it's a big one
	if ( is_singular() && has_post_thumbnail( $post->ID ) &&
			( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( $header_image_width, $header_image_width ) ) ) &&
			$image[1] >= $header_image_width ) :
		// Houston, we have a new header image!
		echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
	else :
		// Compatibility with versions of WordPress prior to 3.4.
		if ( function_exists( 'get_custom_header' ) ) {
			$header_image_width  = get_custom_header()->width;
			$header_image_height = get_custom_header()->height;
		} else {
			$header_image_width  = HEADER_IMAGE_WIDTH;
			$header_image_height = HEADER_IMAGE_HEIGHT;
		}
  endif; // end check for featured image or standard header
endif; // end check for removed header image
			
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]> <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]> ><! <![endif]-->
<html <?php language_attributes(); ?> class="no-js">
<!-- <![endif] -->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'careerspark' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="screen AND (min-width: 960px)" href="<?php echo home_url( '/' ); ?>wp-content/themes/career-spark/style/960.css" />
<?php
  if ( DESIGNSWITCH == 'high' ) {
?>
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link href='http://fonts.googleapis.com/css?family=Asap:700,700italic' rel='stylesheet' type='text/css'>
<?php    
  } else {
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo str_replace('style.', 'style.low.', get_bloginfo( 'stylesheet_url' )); ?>" />
<?php    
  }
?>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script src="<?php echo home_url( '/' ); ?>wp-content/themes/career-spark/js/modernizr.js" type="text/javascript"></script>
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>

<div id="page">

	<header id="branding" class="container_12" role="banner" style="background:url(<?php header_image(); ?>)">
		<hgroup class="grid_4">
			<h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup>

    <div class="grid_8" style="color:#999;">
      powered by restless development
    </div>

		<?php
		  echo rd_designswitch_print_switch();
	  ?>
  </header>
  
  <nav id="nav1" class="container_12" style="color:#fff;background:#666;">
		<h3 class="assistive-text"><?php _e( 'Main menu', 'careerspark' ); ?></h3>
		<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'careerspark' ); ?>"><?php _e( 'Skip to primary content', 'careerspark' ); ?></a></div>
			<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'careerspark' ); ?>"><?php _e( 'Skip to secondary content', 'careerspark' ); ?></a></div>
    <div class="grid_12">
      <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
    </div>
  </nav>


	<div id="main" class="container_12">