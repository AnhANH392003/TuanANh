<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
?><!DOCTYPE html>

<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php $favicon_icon=emallshop_get_option('favicon-icon');
	if(! function_exists( 'has_site_icon' ) || ! has_site_icon()) :
		if( !empty($favicon_icon)){?>
			<link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon_icon['url']);?>">
		<?php }
	endif;?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if(emallshop_get_option('site-loader',0)==1):?>
<div id="emallshop-loader-wrapper">
	<div class="emallshop-loader-section">
		<?php $loader_img=EMALLSHOP_IMAGES.'/site-'.emallshop_get_option('site-loader-img', 'loader' ).'.gif';?>
		<img src="<?php echo esc_url($loader_img);?>" alt="loader">
	</div>
</div>
<?php endif;?>

<div class="wrapper<?php echo (emallshop_get_option('theme-layout', 'full-layout')=='boxed-layout') ? ' boxed-layout' : '';?>">
	<header id="header" class="<?php echo esc_attr(emallshop_get_option('header-layout','header-1'));?>">
		
		<?php // Topbar, Header and Navigation		
			get_template_part( 'templates/header/'.emallshop_get_option('header-layout','header-1'));
		?>
		
		<?php // Page Heading and Breadcrumbs
		if(!is_front_page() && emallshop_get_option('show-page-heading', 1)){			
				get_template_part( 'templates/page-heading/'.emallshop_get_option('page-heading-layout','page-heading-2'));			
		}?>
	</header>
	<div id="main-content" class="site-content">