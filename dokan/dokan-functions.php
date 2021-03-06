<?php 
/**
 * EmallShop Dokan Customizer Functions
 *
 * @package PressLayouts
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* 	Dokan Hook*/
add_action( 'wp_enqueue_scripts', 'emallshop_dokan_scripts', 15 );
add_action( 'widgets_init',	'emallshop_setup_dokan_sidebars',	15 );
add_action( 'woocommerce_after_main_content', 'emallshop_dokan_after_wc_content', 11 );
add_action( 'emallshop_dokan_after_main_container', 'emallshop_output_content_wrapper_end', 10 );

/* 	Dokan fucntions and customize*/
if ( ! function_exists( 'emallshop_dokan_scripts' ) ) {
	function emallshop_dokan_scripts() {		
		wp_enqueue_style( 'emallshop-dokan', EMALLSHOP_CSS . '/dokan.css', array(), '' );
	}
}

if ( ! function_exists( 'emallshop_setup_dokan_sidebars' ) ) {
	function emallshop_setup_dokan_sidebars() {
		register_sidebar( array(
			'name'          => esc_html__( 'Dokan Widget Area', 'emallshop' ),
			'id'            => 'dokan-widget-area',
			'description'   => esc_html__( 'Add widgets here to appear in your dokan page sidebar.', 'emallshop' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
}

if( ! function_exists( 'emallshop_get_dokan_store_sidebar' ) ) {
	function emallshop_get_dokan_store_sidebar() {
		$store_user   = get_userdata( get_query_var( 'author' ) );
		$store_info   = dokan_get_store_info( $store_user->ID );
		$map_location = isset( $store_info['location'] ) ? esc_attr( $store_info['location'] ) : '';

		if ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' ) == 'off' ) { ?>
			<div id="dokan-secondary" class="dokan-clearfix col-xs-12 col-sm-4 col-md-3 col-md-pull-9 dokan-store-sidebar" role="complementary" style="margin-right:3%;">
				<div class="dokan-widget-area widget-collapse">
					 <?php do_action( 'dokan_sidebar_store_before', $store_user, $store_info ); ?>
					<?php
					if ( ! dynamic_sidebar( 'sidebar-store' ) ) {

						$args = array(
							'before_widget' => '<aside class="widget">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title">',
							'after_title'   => '</h3>',
						);

						if ( class_exists( 'Dokan_Store_Location' ) ) {
							the_widget( 'Dokan_Store_Category_Menu', array( 'title' => __( 'Store Category', 'dokan' ) ), $args );

							if ( dokan_get_option( 'store_map', 'dokan_general', 'on' ) == 'on' ) {
								the_widget( 'Dokan_Store_Location', array( 'title' => __( 'Store Location', 'dokan' ) ), $args );
							}

							if ( dokan_get_option( 'contact_seller', 'dokan_general', 'on' ) == 'on' ) {
								the_widget( 'Dokan_Store_Contact_Form', array( 'title' => __( 'Contact Seller', 'dokan' ) ), $args );
							}
						}

					}
					?>

					<?php do_action( 'dokan_sidebar_store_after', $store_user, $store_info ); ?>
				</div>
			</div><!-- #secondary .widget-area -->
		<?php
		} else {
			get_sidebar( 'store' );
		}
	}
}

if ( ! function_exists( 'emallshop_dokan_after_wc_content' ) ) {
	function emallshop_dokan_after_wc_content() {
		if( dokan_is_store_page() ){
			emallshop_get_dokan_store_sidebar();
		}
	}
}

/**
 * User top navigation menu
 *
 * @return void
 */
if ( !function_exists( 'emallshop_dokan_header_user_menu' ) ) :
	function emallshop_dokan_header_user_menu() {
		?>
		<ul class="nav navbar-nav navbar-right">
			<li class="topbar-dokan-cart">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php printf( __( 'Cart %s', 'emallshop' ), '<span class="dokan-cart-amount-top">(' . WC()->cart->get_cart_total() . ')</span>' ); ?> <b class="caret"></b></a>

				<ul class="dropdown-menu">
					<li>
						<div class="widget_shopping_cart_content"></div>
					</li>
				</ul>
			</li>

			<?php if ( is_user_logged_in() ) { ?>

				<?php
				global $current_user;

				$user_id = $current_user->ID;
				if ( dokan_is_user_seller( $user_id ) ) {
					?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php _e( 'Seller Dashboard', 'emallshop' ); ?> <b class="caret"></b></a>

						<ul class="dropdown-menu">
							<li><a href="<?php echo dokan_get_store_url( $user_id ); ?>" target="_blank"><?php _e( 'Visit your store', 'emallshop' ); ?> <i class="fa fa-external-link"></i></a></li>
							<li class="divider"></li>
							<?php
							$nav_urls = dokan_get_dashboard_nav();

							foreach ($nav_urls as $key => $item) {
								printf( '<li><a href="%s">%s &nbsp;%s</a></li>', $item['url'], $item['icon'], $item['title'] );
							}
							?>
						</ul>
					</li>
				<?php } ?>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo esc_html( $current_user->display_name ); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><?php _e( 'My Account', 'emallshop' ); ?></a></li>
						<li><a href="<?php echo wc_customer_edit_account_url(); ?>"><?php _e( 'Edit Account', 'emallshop' ); ?></a></li>
						<li class="divider"></li>
						<li><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'billing', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php _e( 'Billing Address', 'emallshop' ); ?></a></li>
						<li><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'shipping', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php _e( 'Shipping Address', 'emallshop' ); ?></a></li>
					</ul>
				</li>

				<li><?php wp_loginout( home_url() ); ?></li>

			<?php } else { ?>
				<li><a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><?php _e( 'Log in', 'emallshop' ); ?></a></li>
				<li><a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><?php _e( 'Sign Up', 'emallshop' ); ?></a></li>
			<?php } ?>
		</ul>
		<?php
	}
endif;

/*Dokan sellet name*/
if ( !function_exists( 'emallshop_dokan_user_name' ) ) :
	function emallshop_dokan_user_name() {
		$store_user    = get_userdata( get_query_var( 'author' ) );
		$store_info    = dokan_get_store_info( $store_user->ID );
		echo esc_html( $store_info['store_name'] );
	}
endif;