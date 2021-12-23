<?php
/**
 * EmallShop Include Customizer Function
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Admin Functions
 */
 require_once ( EMALLSHOP_ADMIN.'/admin-function.php' );

/**
 * Theme options
 */
if (  file_exists ( EMALLSHOP_ADMIN.'/theme_options.php' ) ) {
    require_once ( EMALLSHOP_ADMIN.'/theme_options.php' );
}
global $emallshop_options;

/**
 * Extras Functions
 */
require EMALLSHOP_INC . '/extras.php';
 
/**
 * Include mega menu
 */
require EMALLSHOP_LIB . '/classes/mega-menus.php';

/**
 * Breadcrumbs
 */
require EMALLSHOP_INC . '/breadcrumbs.php';

/**
 * Add metabox	
 */
require_once EMALLSHOP_INC . '/meta-boxes.php';
require_once EMALLSHOP_LIB . '/meta-box/meta-box.php';

/**
 * 
 *Add admin custom sidebar widget area	
 */
require EMALLSHOP_LIB . '/classes/sidebar-generator.class.php';
new BIGBAZZAR_SIDEBAR();

require EMALLSHOP_INC . '/plugin_activetion_class.php';

/**
 * WooCommerce Customizer
 */
if( is_woocommerce_activated() ) {
	require EMALLSHOP_LIB . '/woocommerce/hooks.php';
	require EMALLSHOP_LIB . '/woocommerce/woo-functions.php';
	require EMALLSHOP_LIB . '/woocommerce/template-tags.php';
}

/**
 * Dokan Customizer
 */
if( is_dokan_activated() ) {
	require EMALLSHOP_LIB . '/dokan/hooks.php';
	require EMALLSHOP_LIB . '/dokan/dokan-functions.php';
}

function emallshop_body_classes( $classes ) {
	
	if(emallshop_get_option('sticky-header', 1)==1){
		$classes[]=emallshop_get_option('sticky-header-part', "sticky-navigation");
	}
	if(emallshop_get_option('categories-menu', 0)==1){
		$classes[] = " open-categories-menu";
	}      
    return $classes;
}
add_filter( 'body_class','emallshop_body_classes' );

/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists( 'emallshop_scripts' ) ) {
	function emallshop_scripts() {
		// Add custom fonts, used in the main stylesheet.
		wp_enqueue_style( 'emallshop-fonts', emallshop_fonts_url(), array(), null );
		
		wp_enqueue_style( 'emallshop-bootstrap-min', EMALLSHOP_CSS . '/bootstrap.min.css', array(), '3.3.7' );
		wp_enqueue_style( 'emallshop-Font-Awesome', EMALLSHOP_CSS . '/font-awesome.min.css', array(), '4.6.3' );
		wp_enqueue_style( 'emallshop-woocommerce', EMALLSHOP_CSS . '/woocommerce.css', array(), '' );
		wp_enqueue_style( 'emallshop-woocommerce-layout', EMALLSHOP_CSS . '/woocommerce-layout.css', array(), '' );
		wp_enqueue_style( 'emallshop-jquery.fancybox', EMALLSHOP_CSS . '/jquery.fancybox.css', array(), '' );
		wp_enqueue_style( 'emallshop-owl-carousel', EMALLSHOP_JS . '/owl-carousel/assets/owl.carousel.css', array(), '' );
		wp_enqueue_style( 'emallshop-owl-theme-default-min', EMALLSHOP_JS . '/owl-carousel/assets/owl.theme.default.min.css', array(), '' );
		wp_enqueue_style( 'emallshop-animate', EMALLSHOP_JS . '/owl-carousel/assets/animate.css', array(), '' );	
		
		if (is_rtl()) {
			wp_enqueue_style( 'emallshop-rtl', EMALLSHOP_URI . '/rtl.css', array('emallshop-style'), '' );
		}
		
		// Load our main stylesheet.
		wp_enqueue_style( 'emallshop-style', get_stylesheet_uri());
		
		// Load the Internet Explorer specific stylesheet.
		wp_enqueue_style( 'emallshop-ie', EMALLSHOP_CSS . '/ie.css', array( 'emallshop-style' ), '' );
		wp_style_add_data( 'emallshop-ie', 'conditional', 'lt IE 9' );

		// Load the Internet Explorer 7 specific stylesheet.
		wp_enqueue_style( 'emallshop-ie7', EMALLSHOP_CSS . '/ie7.css', array( 'emallshop-style' ), '' );
		wp_style_add_data( 'emallshop-ie7', 'conditional', 'lt IE 8' );
		
		//theme js load
		wp_enqueue_script( 'emallshop-bootstrap.min', EMALLSHOP_JS . '/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
		wp_enqueue_script('masonry');
		$enable_live_search = ( emallshop_get_option('live-search', '1') ==1) ? true : false;
		if($enable_live_search){
			wp_enqueue_script( 'typeahead_bundle', EMALLSHOP_JS . '/typeahead.bundle.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'handlebars', EMALLSHOP_JS . '/handlebars.min.js', array( 'typeahead_bundle' ), '', true );
		}
		wp_enqueue_script( 'emallshop-jquery.countdown.plugin.min', EMALLSHOP_JS . '/jquery.countdown.plugin.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-jquery.countdown.min', EMALLSHOP_JS . '/jquery.countdown.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-jquery.fancybox.pack', EMALLSHOP_JS . '/jquery.fancybox.pack.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-jquery.lazyloadxt.min', EMALLSHOP_JS . '/jquery.lazyloadxt.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-theia-sticky-sidebar', EMALLSHOP_JS . '/theia-sticky-sidebar.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-jquery.isotope.min', EMALLSHOP_JS . '/jquery.isotope.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-jquery.cookie.min', EMALLSHOP_JS . '/jquery.cookie.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-cookiealert', EMALLSHOP_JS . '/cookiealert.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-jquery.owl.carousel.min', EMALLSHOP_JS . '/owl-carousel/owl.carousel.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-jquery.nicescroll.min', EMALLSHOP_JS . '/jquery.nicescroll.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-elevateZoom', EMALLSHOP_JS . '/jquery.elevateZoom-3.0.8.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-modernizr.custom', EMALLSHOP_JS . '/modernizr.custom.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-toucheffects', EMALLSHOP_JS . '/toucheffects.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop_lmp_js', EMALLSHOP_JS. '/load_products.js', array( 'jquery' ), '', true );
		
		wp_enqueue_script( 'emallshop-script', EMALLSHOP_JS . '/functions.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'emallshop-typeahead_search' , EMALLSHOP_JS . '/wp-typeahead.js', array( 'typeahead_bundle' ), '', true );
		
		//product search ajax	
		$is_rtl = is_rtl() ? true : false ;
		
		$wp_typeahead_vars = apply_filters( 'emallshop_localize_script_data', array( 
		'rtl' 					=> $is_rtl,
		'ajaxurl' => admin_url( 'admin-ajax.php' ),	
		'enable_live_search'	=> $enable_live_search,
		));
		wp_localize_script( 'emallshop-typeahead_search', 'wp_typeahead', $wp_typeahead_vars );	
		
		//general ajax
		wp_localize_script( 'emallshop-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'emallshop_scripts' );


if ( ! function_exists( 'emallshop_admin_scripts' ) ) {
	function emallshop_admin_scripts() {		
		//Admin css
		wp_enqueue_style('emallshop_admin_css',EMALLSHOP_CSS .'/admin_css.css');
		wp_enqueue_style( 'Font-Awesome', EMALLSHOP_CSS . '/font-awesome.min.css', array(), '4.6.3' );
		
		//Admin js
		wp_enqueue_script( 'emallshop_admin_js' , EMALLSHOP_JS . '/admin.js', array( 'jquery' ), '', true );
		
		wp_localize_script( 'emallshop_admin_js', 'emallshop_admin_vars', array(
			'import_options_msg' => esc_html__('WARNING: Clicking this button will replace your current theme options, sliders.  It can also take a minute to complete. Importing data is recommended on fresh installs only once. Importing on sites with content or importing twice will duplicate menus, pages and all posts.', 'emallshop'),
			'theme_option_url' => admin_url('admin.php?page=EmallShop')
		) );
	}
}
add_action('admin_enqueue_scripts', 'emallshop_admin_scripts');

/**
  * Remove Query String from Static Resources 
  */
function emallshop_remove_cssjs_ver( $src ) {
	if( strpos( $src, '?ver=' ) )
	$src = remove_query_arg( 'ver', $src );
	return $src;
}
add_filter( 'style_loader_src', 'emallshop_remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'emallshop_remove_cssjs_ver', 10, 2 );

/**
 * Load custom js in footer
 * @since 1.0.0
 * @return void
 */
function emallshop_owl_footer() {
	global $emallshop_owlparam;
	wp_localize_script( 'emallshop-script', 'emallshopOwlArg', $emallshop_owlparam );
}
add_action( 'wp_footer', 'emallshop_owl_footer' );

/*
* custome theme fucntion 
*/

/* 	Topbar Customer support
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_customer_support' ) ):
	function emallshop_customer_support(){?>
		<?php if(emallshop_get_option('show-topbar-email', 1) ==1 || emallshop_get_option('show-topbar-number', 1) ==1):?>
			<div class="customer-support">
				<?php if(emallshop_get_option('show-topbar-email', 1) ==1 ):?>
					<div class="customer-support-email"><i class="fa fa-envelope"></i><span><?php echo emallshop_get_option('topbar-email','info@example.com');?></span></div>
				<?php endif;?>
				<?php if(emallshop_get_option('show-topbar-number', 1) ==1):?>
					<div class="customer-support-call"><i class="fa fa-phone"></i><span><?php echo emallshop_get_option('topbar-number','+81 59832452528');?></span></div>
				<?php endif;?>
			</div>
		<?php endif;?>
	<?php }
endif;

/* 	Topbar notification
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_topbar_notification' ) ):
	function emallshop_topbar_notification(){?>
		<?php if(emallshop_get_option('show-topbar-news', 1) ==1 ):?>
			<div class="topbar-notification">
				<div class="news-title">
					<i class="fa fa-rss"></i>
					<span><?php esc_html_e('News','emallshop');?></span>
				</div>
				<div class="news-text">
					<marquee behavior="scroll" direction="left"><span class="break-new"><?php echo emallshop_get_option("topbar-news","<a href='#'>Super Sale 50%</a><a href='#'>Big Promotion on Valentine days</a><a href='#'>Gift 15 Voucher for</a>");?> </span></marquee>
				</div>
			</div>
		<?php endif;?>
	<?php }
endif;

/* 	Topbar Social link
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_social_link' ) ):
	function emallshop_social_link(){?>
		<ul class="social-link">
			<?php if(emallshop_get_option('facebook_link') !=''):?>
				<li class="icon-facebook"><a href="<?php echo esc_url(emallshop_get_option('facebook_link'));?>"><i class="fa fa-facebook"></i></a></li>
			<?php endif;?>
			<?php if(emallshop_get_option('twitter_link') !=''):?>
				<li class="icon-twitter"><a href="<?php echo esc_url(emallshop_get_option('twitter_link'));?>"><i class="fa fa-twitter"></i></a></li>
			<?php endif;?>
			<?php if(emallshop_get_option('instagram_link') !=''):?>
				<li class="icon-instagram"><a href="<?php echo esc_url(emallshop_get_option('instagram_link'));?>"><i class="fa fa-instagram"></i></a></li>
			<?php endif;?>
			<?php if(emallshop_get_option('linkedin_link') !=''):?>
				<li class="icon-linkedin"><a href="<?php echo esc_url(emallshop_get_option('linkedin_link'));?>"><i class="fa fa-linkedin"></i></a></li>
			<?php endif;?>
			<?php if(emallshop_get_option('flickr_link') !=''):?>
				<li class="icon-google-plus"><a href="<?php echo esc_url(emallshop_get_option('flickr_link'));?>"><i class="fa fa-google-plus"></i></a></li>
			<?php endif;?>
			<?php if(emallshop_get_option('google_plus_link') !=''):?>
				<li class="icon-flickr"><a href="<?php echo esc_url(emallshop_get_option('google_plus_link'));?>"><i class="fa fa-flickr"></i></a></li>
			<?php endif;?>
			<?php if(emallshop_get_option('rss_link') !=''):?>
				<li class="icon-rss"><a href="<?php echo esc_url(emallshop_get_option('rss_link'));?>"><i class="fa fa-rss"></i></a></li>
			<?php endif;?>
			<?php if(emallshop_get_option('pinterest_link') !=''):?>
				<li class="icon-pinterest"><a href="<?php echo esc_url(emallshop_get_option('pinterest_link'));?>"><i class="fa fa-pinterest"></i></a></li>
			<?php endif;?>
		</ul>
	<?php }
endif;

/* 	Topbar Welcome Message
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_welcome_message' ) ):
	function emallshop_welcome_message(){?>
		<?php if(emallshop_get_option('show-topbar-welcome-message', 0) ==1 ):?>
			<div class="topbar-welcome-message">
				<span class="welcome-message-text"><?php echo emallshop_get_option("topbar-welcome-message","Welcome to my Shop");?> </span>
			</div>
		<?php endif;?>
	<?php }
endif;

/* 	Topbar user login
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_login' ) ){
	function emallshop_login(){
		$myaccount_url = get_permalink(get_option( 'woocommerce_myaccount_page_id' ));	?>
		<span class="user-login">
			<?php
			if(!is_user_logged_in()):?>
				<a href="<?php echo esc_url($myaccount_url);?>"><i class="fa fa-lock"></i><span><?php esc_html_e('Login','emallshop');?></span></a>
			<?php else:?>
				<a href="<?php echo esc_url(wp_logout_url(get_permalink()));?>"><i class="fa fa-unlock"></i><span><?php esc_html_e('Logout','emallshop');?></span></a>
			<?php endif;?>
		</span>
	<?php }
}

/* 	EmallShop Registration
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_register' ) ){
	function emallshop_register(){
		if(!is_user_logged_in()):?>
			<span class="user-register">
				<a href="#">
					<i class="fa fa-user-plus"></i>
					<span class="user-register-text"><?php esc_html_e('Register','emallshop');?></span>
				</a>
			</span>
	<?php endif;
	}
}

/* 	woocommerce myaccount
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_all_myaccount' ) ):

	function emallshop_all_myaccount(){
		
		if(!is_woocommerce_activated()):
			return false;
		endif;
		
		global $woocommerce;
		$myaccount_page_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
		$cart_url = $woocommerce->cart->get_cart_url();
		$checkout_url = $woocommerce->cart->get_checkout_url();	?>
		<span class="wcaccount-topbar">
			
			<a href="<?php echo get_permalink(woocommerce_get_page_id( 'myaccount' ));?>"><i class="fa fa-user"></i><span><?php esc_html_e('My Account','emallshop');?> </span><i class="fa fa-caret-down"></i></a>
			
			<ul class="wcaccount-dropdown">
				<li><a href="<?php echo esc_url($checkout_url);?>"><i class="fa fa-check-square-o"></i><span><?php esc_html_e('Checkout','emallshop');?></span></a></li>
				<li><a href="<?php echo esc_url($cart_url);?>"><i class="fa fa-shopping-cart"></i><span><?php esc_html_e('Cart','emallshop');?></span></a></li>
				
				<?php if( function_exists( 'YITH_WCWL' ) ):
					$wishlist_url = YITH_WCWL()->get_wishlist_url(); ?>
					<li class=""><a href="<?php echo esc_url($wishlist_url);?>"><i class="fa fa-heart"></i><span><?php esc_html_e('My Wishlist','emallshop');?></span> (<samp class="wishlist-count"><?php echo YITH_WCWL()->count_products();?></samp>)</a></li>
				<?php endif; ?>
				
				<?php if(defined( 'YITH_WOOCOMPARE' )): 
					global $yith_woocompare; 
					$product_count = count($yith_woocompare->obj->products_list); ?>
					<li><a href="#" class="yith-woocompare-open"><i class="fa fa-refresh"></i><span><?php esc_html_e( "Compare", 'emallshop') ?></span> (<samp class="compare-count"><?php echo esc_attr( $product_count ); ?></samp>)</a></li>
				<?php endif; ?>
				<?php if(is_user_logged_in()):?>
					<li><a href="<?php echo esc_url(wp_logout_url(get_permalink()));?>"><i class="fa fa-unlock"></i><span><?php esc_html_e('sign Out','emallshop');?></span></a></li>
				<?php endif;?>
			</ul>
		</span>
	<?php }
endif;

/* 	EmallShop My Account
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_myaccount' ) ){
	function emallshop_myaccount(){
		if(!is_woocommerce_activated()):
			return false;
		endif;?>
		<span class="header-myaccount">
			<a href="<?php echo get_permalink(woocommerce_get_page_id( 'myaccount' ));?>">
				<i class="fa fa-user"></i>
				<span class="header-myaccount-text"><?php esc_html_e('My Account','emallshop');?></span>
			</a>
		</span>
		<?php 
	}
}

/* 	EmallShop Cart
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_cart' ) ){
	function emallshop_cart(){
		if(!is_woocommerce_activated()):
			return false;
		endif;
		global $woocommerce;
		$cart_url = $woocommerce->cart->get_cart_url();?>
		<span class="topbar-cart">
			<a href="<?php echo esc_url($cart_url);?>">
				<i class="fa fa-shopping-cart"></i>
				<samp class="cart-count"><?php echo esc_attr($woocommerce->cart->cart_contents_count);?></samp>
				<span class="header-cart-text"><?php esc_html_e('Shopping Cart','emallshop');?></span>
			</a>
		</span><?php
	}
}

/* 	EmallShop Checkout
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_checkout' ) ){
	function emallshop_checkout(){
		if(!is_woocommerce_activated()):
			return false;
		endif;
		global $woocommerce;
		$checkout_url = $woocommerce->cart->get_checkout_url();?>
		<span class="header-checkout">
			<a href="<?php echo esc_url($checkout_url);?>">
				<i class="fa fa-check-square-o"></i>
				<span class="header-checkout-text"><?php esc_html_e('Checkout','emallshop');?></span>
			</a>
		</span><?php
	}
}

/* 	EmallShop Wishlist
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_wishlist' ) ){
	function emallshop_wishlist(){
		if( function_exists( 'YITH_WCWL' ) ):
			$wishlist_url = YITH_WCWL()->get_wishlist_url(); ?>
			<span class="header-wishlist">
				<a href="<?php echo esc_url($wishlist_url);?>">
					<i class="fa fa-heart"></i>
					<samp class="wishlist-count"><?php echo YITH_WCWL()->count_products();?></samp>
					<span class="header-wishlist-text"><?php esc_html_e('Wishlist','emallshop');?></span>
				</a>
			</span>
		<?php endif;
	}
}

/* 	EmallShop Campare
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_campare' ) ){
	function emallshop_campare(){
		if(defined( 'YITH_WOOCOMPARE' )): 
			global $yith_woocompare; 
			$product_count = count($yith_woocompare->obj->products_list); ?>
			<span class="header-compare">
				<a href="#" class="yith-woocompare-open">
					<i class="fa fa-refresh"></i>
					<samp class="compare-count"><?php echo esc_attr( $product_count ); ?></samp>
					<span class="header-compare-text"><?php esc_html_e( "Compare", 'emallshop') ?></span>
				</a>
			</span>
		<?php endif;
	}
}

/* 	Topbar currency
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_currency' ) ):
	function emallshop_currency(){
		
		if( emallshop_get_option('show-currency-switcher',1) ==1):
			if (class_exists('woocommerce_wpml')) { ?>
				<span class="currency-topbar">
					<?php echo(do_shortcode('[currency_switcher]')); ?>					
				</span>				
			<?php }
		endif;
	}
endif;

/* 	Topbar language
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_language' ) ):
	function emallshop_language(){
		
		if( emallshop_get_option('show-language-switcher','1')==1):
			if (class_exists('SitePress')) {?>
			<span class="language-topbar">
				<?php do_action('wpml_add_language_selector'); ?>				
			</span>
		<?php }
		endif;
	}
endif;

/* 	EmallShop help
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_help' ) ){
	function emallshop_help(){
		global $emallshop?>
		<span class="header-help">
			<a href="<?php echo esc_url(emallshop_get_option('topbar-help', '#')); ?>">
				<i class="fa fa-question-circle"></i>
				<span class="header-help-text"><?php esc_html_e('Help','emallshop');?></span>
			</a>
		</span><?php
	}
}

/* 	Header Logo
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_logo' ) ){
	function emallshop_header_logo(){?>
	
		<div class="header-logo">
			<?php $header_logo=emallshop_get_option('header-logo');
			if( !empty( $header_logo)):?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <img src="<?php echo esc_url($header_logo['url']);?>" alt="logo"></a>
			<?php else:?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <img src="<?php echo esc_url(get_template_directory_uri());?>/images/logo.png" alt="logo"></a>
			<?php endif;?>
		</div><?php
	}
}

/* 	Header cart
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_services' ) ):
	function emallshop_header_services(){?>
		
		<div class="header-services">
			<div class="box-service">
				<span class="icon-service">
					<i class="fa <?php echo emallshop_get_option('service-icon1', 'fa-phone'); ?>"> </i>
				</span>
				<div class="content-service">
					<h6><?php echo emallshop_get_option('service-title1', '08 143 456 753');?></h6>
					<span><?php echo emallshop_get_option('service-des1', 'lorem ipsum dolor.');?></span>
				</div>
			</div>
			<div class="box-service">
				<span class="icon-service">
					<i class="fa <?php echo emallshop_get_option('service-icon2', 'fa-truck');?>"> </i>
				</span>
				<div class="content-service">
					<h6><?php echo emallshop_get_option('service-title2', esc_html__('Free Shipping','emallshop'));?></h6>
					<span><?php echo emallshop_get_option('service-des2', esc_html__('all order over $100.','emallshop'));?></span>
				</div>
			</div>
			<div class="box-service">
				<span class="icon-service">
					<i class="fa <?php echo emallshop_get_option('service-icon3', 'fa-refresh'); ?>"> </i>
				</span>
				<div class="content-service">
					<h6><?php echo emallshop_get_option('service-title3', esc_html__('Free Shipping','emallshop'));?></h6>
					<span><?php echo emallshop_get_option('service-des3', esc_html__('Return & Exchange','emallshop'));?></span>
				</div>
			</div>
		</div>
	<?php }
endif;

/* 	Header cart
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_cart' ) ){
	function emallshop_header_cart(){
		
		if ( !class_exists( 'WooCommerce' ) ) : 
			return;
		endif;	
		
		global $woocommerce;
		$header_style=emallshop_get_option('header-layout','header-2');?>
		<div class="header-cart-content">
			<?php if($header_style=="header-5"){?>			
				<div class="heading-cart cart-style-1">
					<a class="cart-contents" href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>">
						<span class="cart-icon fa fa-shopping-cart"></span>
						<span class="cart-count"><?php echo sprintf(_n('%d item', '%d item(s)', $woocommerce->cart->cart_contents_count, 'emallshop'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></span>
					</a>
				</div>				
			<?php }elseif($header_style=="header-1" || $header_style=="header-2" || $header_style=="header-3"){?>
				<span class="header-cart cart-style-2">
					<a href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>">
						<i class="fa fa-shopping-cart"></i>
						<samp class="cart-count"><?php echo esc_attr($woocommerce->cart->cart_contents_count);?></samp>
						<span class="header-cart-text"><?php esc_html_e('Cart','emallshop');?></span>
					</a>
				</span>			
			<?php }elseif($header_style=="header-4" || $header_style=="header-6" || $header_style=="header-7" || $header_style=="header-8" || $header_style=="header-9"){?>
				<div class="heading-cart cart-style-3">
					<a class="cart-contents" href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>">
						<h6><?php esc_html_e('Shopping Cart','emallshop');?></h6>
						<span><?php echo sprintf(_n('%d item', '%d item(s)', $woocommerce->cart->cart_contents_count, 'emallshop'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></span>
					</a>
				
				</div>
			<?php }?>
			<div class="cart-product-list woocommerce">
				<?php woocommerce_mini_cart();?>
			</div>
		</div>
	<?php 
	}
}

/* 	Header menu
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_header_menu' ) ):
	function emallshop_header_menu(){
		
		if ( has_nav_menu( 'primary' ) ) { ?>
			<div class="header-main-navigation" role="navigation">			
				<?php wp_nav_menu( array( 'theme_location' 	=> 'primary',
										'menu_class'      	=> 'emallshop-horizontal-menu main-navigation',
										'container_class'	=> 'emallshop-main-menu visible-lg',
										'fallback_cb' 		=> 'EmallShopFrontendWalker::fallback',
										'walker' 			=> new EmallShopFrontendWalker(),
								  ) ); ?>
				<div class="mobile-main-navigation visible-xs visible-sm visible-md">
					<div class="toggle-menu">
						<h4><?php esc_html_e('Main Menu','emallshop');?></h4>
						<span class="down-up"><i class="fa fa-list"></i></span>
					</div>
				</div>
				<?php wp_nav_menu( array( 'theme_location' 	=> 'primary',
										'menu_class'      	=> 'main-navigation emallshop-horizontal-menu',
										'container_class'	=> 'emallshop-main-menu emallshop-mobile-menu',
								  ) ); ?>
			</div>
	<?php }
	}
endif;

/* 	Header Category/Vertical menu
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_category_menu' ) ):
	function emallshop_category_menu($title=null){ 
	
		if ( has_nav_menu( 'vertical_menu' ) ) { ?>
			<div class="category-menu open">
				<div class="category-menu-title">
					<h4><?php echo ($title !=null) ? esc_attr($title) : esc_html_e('Shopping Categories','emallshop');?></h4>
					<span class="down-up"><i class="fa fa-list"></i></span>
				</div>
				<div class="categories-list">
				<?php	wp_nav_menu( array( 'theme_location' 	=> 'vertical_menu',
										'menu_class'      	=> 'emallshop-vertical-menu main-navigation',
										'container_class'	=> 'emallshop-main-menu visible-lg',
										'fallback_cb' 		=> 'EmallShopFrontendWalker::fallback',
										'walker' 			=> new EmallShopFrontendWalker(),
								  ) ); 
				
						wp_nav_menu( array( 'theme_location' 	=> 'vertical_menu',
										'menu_class'      	=> 'main-navigation emallshop-vertical-menu',
										'container_class'	=> 'emallshop-main-menu emallshop-mobile-menu hidden-lg',
								  ) ); ?>
				</div>
			</div>
	<?php }
	}
endif;

/* 	Sub Categories
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'emallshop_sub_category' ) ):
	function emallshop_sub_category(){
		
		$categories = get_categories( array( 'parent' => $parent_arg , 'hide_empty' => 1, 'taxonomy' => 'product_cat' ) ); 
	}
endif;

/* 	Page / Post colunm class
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getColumnClass')) {
	function emallshop_getColumnClass($sidebar_position){
		if ( (isset($sidebar_position) && $sidebar_position == 'none') || (function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page()) || (function_exists('is_order_tracking') && is_order_tracking()) || (is_dokan_activated() && is_page( 'dashboard' )) || (is_dokan_activated() && is_page( 'store-listing' ))) :
				$column_classs="col-md-12";
		elseif(isset($sidebar_position) && $sidebar_position=='left'):
			if(is_rtl()){
				$column_classs="col-xs-12 col-sm-8 col-md-9 col-md-pull-3";
			}else{
					$column_classs="col-xs-12 col-sm-8 col-md-9 col-md-push-3";
			}
		else:
			$column_classs="col-xs-12 col-sm-8 col-md-9";
		endif;
		
		return $column_classs;
	}
}

/* 	Sidebar position
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getSidebarPosition')) {
	function emallshop_getSidebarPosition($sidebar_position){
		
		return (isset($sidebar_position) && $sidebar_position !='') ? $sidebar_position : "right";
	}
}

/* 	Sidebar Widget
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getSidebarWidget')) {
	function emallshop_getSidebarWidget($sidebar_widget){
		
		return (isset($sidebar_widget) && $sidebar_widget !='') ? $sidebar_widget : "sidebar-1";
	}
}

/* 	Show Breadcrumb
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getShowBreadsrumb')) {
	function emallshop_getShowBreadsrumb($show_breadsrumb){
		
		return (isset($show_breadsrumb) && $show_breadsrumb !='') ? $show_breadsrumb : "yes";
	}
}

/* 	Sidebar Widget
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getShowTitle')) {
	function emallshop_getShowTitle($show_title){
		
		return (isset($show_title) && $show_title !='') ? $show_title : "yes";
	}
}

/* 	Portfolio style of custom template
/* --------------------------------------------------------------------- */

if ( !function_exists('emallshop_getPortfolioStyle')) {
	function emallshop_getPortfolioStyle($grid_column)
	{		
		if(isset($grid_column)):
			$grid_column='portfolio_'.$grid_column.'_column';	
		else:
			$grid_column='portfolio_two_column';
		endif;
		return $grid_column;
	}
}

/* 	Set the number of a Blog post type posts per page
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_blog_archive_query')) {
	function emallshop_blog_archive_query( $query ) {
		if( $query->is_main_query() && $query->is_post_type_archive('post') ) {
			$query->set( 'posts_per_page', emallshop_get_option('blog-post-per-page', 10) );
		}
	}
}
add_filter( 'pre_get_posts', 'emallshop_blog_archive_query' );

/* 	Set the number of a portfolio post type posts per page
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_portfolio_archive_query')) {
	function emallshop_portfolio_archive_query( $query ) {
		if( $query->is_main_query() && $query->is_post_type_archive('portfolio') ) {
			$query->set( 'posts_per_page', emallshop_get_option('portfolio-per-page', 10) );
		}
	}
}
add_filter( 'pre_get_posts', 'emallshop_portfolio_archive_query' );

// Remove that filter again
add_filter( 'max_srcset_image_width', create_function( '', 'return 1;' ) );

/* 	Get post thumbnail
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_get_post_thumbnail')) {
	function emallshop_get_post_thumbnail($image_size){
		$prefix = 'es_';
		switch(get_post_format()) {
			
			case 'image' :
					$attachment_id=get_post_meta ( get_the_ID(), $prefix .'post_format_image', true )  ;
					if ( is_singular() ) : ?>
						<div class="post-thumbnail">
							<?php if(wp_get_attachment_url( $attachment_id )) :
								 echo wp_get_attachment_image( $attachment_id, 'large' );
							else:?>
								<img src="<?php echo esc_url(EMALLSHOP_URI.'/images/blog-placeholder.jpg');?>"/>
							<?php endif;?>
						</div><!-- .post-thumbnail -->

						<?php else : ?>
						
						<div class="entry-thumbnail">
							<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
								<?php if(wp_get_attachment_url( $attachment_id )) :
									echo wp_get_attachment_image( $attachment_id, $image_size );
								else:?>
									<img src="<?php echo esc_url(EMALLSHOP_URI.'/images/blog-placeholder.jpg');?>"/>
								<?php endif;?>
							</a>							
						</div>
					<?php endif; // End is_singular()
					break;			
			case 'gallery' :
					$attachment_ids=get_post_meta ( get_the_ID(), $prefix .'post_format_gallery' )  ;
					$output = '';
					if (!empty($attachment_ids) && is_array($attachment_ids)) {
						$output .= "<div class='post-slider entry-media owl-carousel'>";
						foreach ($attachment_ids as $attachment_id) {
							if (is_single()) {
								$output .= "<div class='item'>";
									$output.= wp_get_attachment_image( $attachment_id, 'large' );
							$output .= "</div>";
							} else {
								$output .= "<div class='item'>";
								$output .= '<a data-group="entry-'. esc_attr(get_the_ID()) .'" class="single-image" href="'. get_permalink(get_the_ID()) .'">';
									$output.= wp_get_attachment_image( $attachment_id,  $image_size );
								$output .= '</a>';
							$output .= "</div>";
							}				
						}
						$output .= "</div>";
						echo $output;
					}
					break;
			case 'video' :
						$video_url_embed=get_post_meta ( get_the_ID(), $prefix .'post_format_video', true ) ;
						$video_url_embed = preg_replace( '|^\s*(https?://[^\s"]+)\s*$|im', "[embed]$1[/embed]", strip_tags($video_url_embed));
						preg_match("!\[embed.+?\]|\[video.+?\]!", $video_url_embed, $match_video);
						$output = '';
						if (!empty($match_video)) {
							global $wp_embed;

							$image_size = emallshop_get_image_size($image_size);
							$video = $match_video[0];
							$video = str_replace('[embed]', '[embed width="'. $image_size['width'] .'" height="'. $image_size['height'] .'"]', $video);

							$output = "<div class='entry-media'>";
								$output .= do_shortcode($wp_embed->run_shortcode($video));
							$output .= "</div>";
						}
						echo $output;
					break;
					
			case 'audio' :
					$audio_url_embed=get_post_meta ( get_the_ID(), $prefix .'post_format_audio', true ) ;
					$output = '';					
					$audio_url_embed = preg_replace( '|^\s*(http?://[^\s"]+)\s*$|im', "[audio src='$1']", strip_tags($audio_url_embed ) );
					preg_match("!\[audio.+?\]!", $audio_url_embed, $match_audio);
					preg_match("!\[embed.+?\]!", $audio_url_embed, $match_embed);

					if (!empty($match_embed) && strpos($match_embed[0], 'soundcloud.com') !== false) {
						global $wp_embed;
						$image_size = emallshop_get_image_size($image_size);
						$embed = $match_embed[0];
						$embed = str_replace('[embed]', '[embed width="'. $image_size['width'] .'" height="250"]', $embed);

						$output = "<div class='entry-media'>";
							$output .= $wp_embed->run_shortcode($embed);
						$output .= "</div>";
						
					} else if (!empty($match_audio)) {
						$output = "<div class='entry-media'>";
							$output .= do_shortcode($match_audio[0]);
						$output .= "</div>";
					}
					echo $output;
		
					break;
					
			case 'link' :
					$link_url=get_post_meta ( get_the_ID(), $prefix .'post_format_link_url', true ) ;
					$link_text=get_post_meta ( get_the_ID(), $prefix .'post_format_link_text', true ) ;
					$output = '';
					if(isset($link_url) && $link_url != ''):
						$output = '<div class="entry-media">';
							$output .= '<div class="post-link">';
								$output .= '<a href="'.$link_url.'" alt="'.$link_text.'">'.$link_text.'</a>';
							$output .= '</div>';
						$output .= '</div>';
					endif;
					echo $output;
					break;
					
			case 'quote' :	
					$quote=get_post_meta ( get_the_ID(), $prefix .'post_format_quote', true ) ;
					$quote_author=get_post_meta ( get_the_ID(), $prefix .'post_format_quote_author', true ) ;
					$quote_author_url=get_post_meta ( get_the_ID(), $prefix .'post_format_quote_author_url', true ) ;
					$output = '';
					if(isset($quote) && $quote !=''):
						$output = '<div class="entry-media">';
							$output .= '<blockquote>';
								$output .= $quote;
								$output .='<br><a href="'.$quote_author_url.'" alt="'.$quote_author.'">'.$quote_author.'</a>';
							$output .= '</blockquote>';
						$output .= '</div>';
					endif;
					echo $output;					
					break;
			default:
				if( $image_size== 'medium' ):
					emallshop_small_post_thumbnail();
				else:
					emallshop_post_thumbnail();
				 endif;
				 
				 break;				 
		}		
	}
}

// Add SoundCloud oEmbed
function add_oembed_soundcloud(){
	wp_oembed_add_provider( 'http://soundcloud.com/*', 'http://soundcloud.com/oembed' );
}
add_action('init','add_oembed_soundcloud');

/* 	Get Excerpt content
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_excerpt')) {
	function emallshop_excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		} else {
		$excerpt = implode(" ",$excerpt);
		}	
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
		return $excerpt;
	}
}

/* 	Get related post
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getRelatedPosts')) {
	function emallshop_getRelatedPosts($post_id) {
		
		$args = '';
	
		$item_cats = get_the_terms($post_id, 'category');
		if ($item_cats) :
			foreach($item_cats as $item_cat) {
				$item_array[] = $item_cat->term_id;
			}
		endif;
		
		$args = wp_parse_args($args, array(
			'showposts' => '10',
			'post__not_in' => array($post_id),
			'ignore_sticky_posts' => 0,
			'post_type' => 'post',
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => $item_array
				)
			),
			'orderby' => 'DESC'
		));
	
		$query = new WP_Query($args);
		wp_reset_query();
		return $query;
	}
}

/* 	Get related portfolio
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_getRelatedPortfolios')) {
	function emallshop_getRelatedPortfolios($post_id) {
		$args = '';
	
		$item_cats = get_the_terms($post_id, 'portfolio_cat');
		if ($item_cats) :
			foreach($item_cats as $item_cat) {
				$item_array[] = $item_cat->term_id;
			}
		endif;
		
		$args = wp_parse_args($args, array(
			'showposts' => emallshop_get_option('show-related-Projects', 1),
			'post__not_in' => array($post_id),
			'ignore_sticky_posts' => 0,
			'post_type' => 'portfolio',
			'tax_query' => array(
				array(
					'taxonomy' => 'portfolio_cat',
					'field' => 'id',
					'terms' => $item_array
				)
			),
			'orderby' => 'DESC'
		));
	
		$query = new WP_Query($args);
		wp_reset_query();
		return $query;
	}
}

/* 	get post image size
/* ---------------------------------------------------------------------- */
if (!function_exists('emallshop_get_image_size')) {

	function emallshop_get_image_size( $image_type ) {
		
		if($image_type=='medium'){
			$image_size=array('width'=>'463', 'height'=>'348', 'image_type'=>$image_type);
		}elseif($image_type=='large'){
			$image_size=array('width'=>'870', 'height'=>'510', 'image_type'=>$image_type);
		}
		return $image_size;
	}
}

/* 	Post Comment structure
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_comment')) {
	function emallshop_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>

		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body clearfix">
				<div class="comment-avatar"><?php echo get_avatar($comment, 70); ?></div>
                <div class="comment-area-wrap">
                    <div class="comment-meta">
                        <b class="comment-author"><?php echo get_comment_author_link() ?></b>
                        <span class="comment-date"> <?php printf(esc_html__('%1$s', 'emallshop'), get_comment_date()) ?></span>
                        <?php echo get_comment_reply_link(array_merge(
                            array('reply_text' => esc_html__('Reply', 'emallshop')),
                            array('depth' => $depth, 'max_depth' => $args['max_depth'])
                        ));
                        ?>
                    </div><!--/ .comment-meta-->
                    <div class="comment-content"><?php comment_text(); ?></div>
                </div>
			</article><!--/ .comment-wrap-->
		</li>
	<?php
	}
}

if( ! function_exists( 'emallshop_ajax_load_more_pagination' ) ){
	function emallshop_ajax_load_more_pagination(){
	
		$load_more_option=array();
		$ajax_pagination=array();
		
		if( emallshop_get_option('blogs-pagination-type','infinity_scroll')!="default_pagination" || ( isset($GLOBALS['blog_pagination']) && $GLOBALS['blog_pagination']!="default_pagination")):
			$load_more_options[]=emallshop_load_more_blogs();
		endif;
		
		if( emallshop_get_option('portfolio-pagination-type','infinity_scroll')!="default_pagination" || ( isset($GLOBALS['portfolio_pagination']) && $GLOBALS['portfolio_pagination']!="default_pagination")):
			$load_more_options[]=emallshop_load_more_portfolios();
		endif;
		
		if( is_woocommerce_activated() && emallshop_get_option('product-pagination-style','infinity_scroll')!="default_pagination" ):
			$load_more_options[]=emallshop_load_more_products();		
		endif;
		
		if(!empty($load_more_options)){
			foreach($load_more_options as $load_more_option){
				$ajax_pagination['the_lmp_js_data'][]=array(
					'type'          => $load_more_option['type'],
					'use_mobile'    => $load_more_option['use_mobile'],
					'mobile_type'   => $load_more_option['mobile_type'],
					'mobile_width'  => $load_more_option['mobile_width'],
					'is_AAPF'       => '',
					'buffer'        => 50,

					'load_image'    => $load_more_option['image'],
					'load_img_class'=> '.lmp_products_loading',

					'load_more'     => $load_more_option['load_more_button'],

					'lazy_load'     => $load_more_option['lazy_load'],
					'lazy_load_m'   => $load_more_option['lazy_load_m'],
					'LLanimation'   => $load_more_option['LLanimation'],
				
					'loading'       => $load_more_option['loading'],
					'loading_class' => '',

					'end_text'      => $load_more_option['end_text'],
					'end_text_class'=> '',

					//'javascript'    => $javascript_options,

					'products'      => $load_more_option['products_selector'],
					'item'          => $load_more_option['item_selector'],
					'pagination'    => $load_more_option['pagination_selector'],
					'next_page'     => $load_more_option['next_page_selector'],
				);
			}
		}
		
		wp_localize_script(
			'emallshop_lmp_js',
			'ajax_pagination',
			$ajax_pagination
		);
	}
}
add_action ( 'wp_footer', 'emallshop_ajax_load_more_pagination' );

/* 	Ajax Blog Pagination Options
/* --------------------------------------------------------------------- */
function emallshop_load_more_blogs() {
	$load_more_options=array();
	
	if(isset($GLOBALS['blog_pagination'])):
		$load_more_options['type']=$GLOBALS['blog_pagination'];
	else:
		$load_more_options['type']= emallshop_get_option('blogs-pagination-type','infinity_scroll');
	endif;
	
	$load_more_options['use_mobile']=false;
	$load_more_options['mobile_type']='more_button';
	$load_more_options['mobile_width']=767;
	
	$load_more_text= emallshop_get_option('blog-load-more-button-text',esc_html__('Load More','emallshop'));
	$load_more_options['lazy_load']=false ;
	
	$load_more_options['lazy_load_m']=false;
	
	$load_more_options['LLanimation']='zoomInUp' ;
	
	$loading_image= EMALLSHOP_ADMIN_URI.'/images/ajax-'.emallshop_get_option('pagination-loading-image','loader').'.gif';
	
	$load_more_options['loading']=esc_html__('Loading...','emallshop');
	$load_more_options['loading_class'] = '';
	$load_more_options['end_text'] = esc_html__('No more blog','emallshop');
	
	$load_more_options['products_selector'] = '.blog-posts';
	$load_more_options['item_selector'] = 'article.post';
	$load_more_options['pagination_selector'] = '.posts-navigation';
	$load_more_options['next_page_selector'] = '.posts-navigation a.next';
		
	$image_class = 'lmp_rotate';
	
	$image = '<div class="lmp_products_loading">';	
	$image .= '<img src="'.esc_url($loading_image).'">';
	$image .= '</div>';
	
	$load_more_options['image']=$image;

	$load_more_button = '<div class="lmp_load_more_button">';
	$load_more_button .= '<a class="lmp_button"';	
	$load_more_button .= ' href="#load_next_page">'.$load_more_text.'</a>';
	$load_more_button .= '</div>';
	
	$load_more_options['load_more_button']=$load_more_button;
	
	return $load_more_options;
	
}

/* 	Ajax Portfolio Pagination Options
/* --------------------------------------------------------------------- */
function emallshop_load_more_portfolios() {
	$load_more_options=array();
	if(isset($GLOBALS['portfolio_pagination'])):
		$load_more_options['type']=$GLOBALS['portfolio_pagination'];
	else:
		$load_more_options['type']= emallshop_get_option('portfolio-pagination-type','infinity_scroll');
	endif;
	
	$load_more_options['use_mobile']=false;
	$load_more_options['mobile_type']='more_button';
	$load_more_options['mobile_width']=767;
	
	$load_more_text= emallshop_get_option('blog-load-more-button-text',esc_html__('Load More','emallshop'));
	$load_more_options['lazy_load']=false ;
	
	$load_more_options['lazy_load_m']=false;
	
	$load_more_options['LLanimation']='zoomInUp' ;
	
	$loading_image= EMALLSHOP_ADMIN_URI.'/images/ajax-'.emallshop_get_option('pagination-loading-image','loader').'.gif';
	
	$load_more_options['loading']=esc_html__('Loading...','emallshop');
	$load_more_options['loading_class'] = '';
	$load_more_options['end_text'] = esc_html__('No more blog','emallshop');
	
	$load_more_options['products_selector'] = '.portfolioContainer';
	$load_more_options['item_selector'] = '.portfolio-item';
	$load_more_options['pagination_selector'] = '.posts-navigation';
	$load_more_options['next_page_selector'] = '.posts-navigation a.next';
		
	$image_class = 'lmp_rotate';
	
	$image = '<div class="lmp_products_loading">';	
	$image .= '<img src="'.esc_url($loading_image).'">';
	$image .= '</div>';
	
	$load_more_options['image']=$image;

	$load_more_button = '<div class="lmp_load_more_button">';
	$load_more_button .= '<a class="lmp_button"';	
	$load_more_button .= ' href="#load_next_page">'.$load_more_text.'</a>';
	$load_more_button .= '</div>';
	
	$load_more_options['load_more_button']=$load_more_button;
	
	return $load_more_options;
	
}

/* 	Post navigation
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_post_navigation')) {
	function emallshop_post_navigation($atts, $content = null){
		$next_post = get_next_post();
		$prev_post = get_previous_post();
			if(!empty($prev_post) || !empty($next_post)):
		?>
			<div class="navigation" role="navigation">
				<h3><span><?php esc_html_e('Post Navigation','emallshop');?></span></h3>
				<div class="post-navigation">
					<div class="nav-links">
					<?php if(!empty($prev_post)) : ?>
						<div class="nav-previous">
							<a href="<?php echo get_permalink($prev_post->ID); ?>" ><span class="meta-nav">&laquo; <?php echo get_the_title($prev_post->ID); ?></span></a>
							<div class="post-nav-thumb">
								<?php echo get_the_post_thumbnail( $prev_post->ID, 'thumbnail'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if(!empty($next_post)) : ?>
						<div class="nav-next">
							<a href="<?php echo get_permalink($next_post->ID); ?>"><span class="meta-nav"><?php echo get_the_title($next_post->ID); ?>  &raquo;</span></a>
							<div class="post-nav-thumb">
								<?php echo get_the_post_thumbnail( $next_post->ID,'thumbnail' ); ?>
							</div>
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		endif;
	}
}

/* 	header post navigation
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_header_post_navigation')) {
	function emallshop_header_post_navigation($atts, $content = null){
		$next_post = get_next_post();
		$prev_post = get_previous_post();
			if(!empty($prev_post) || !empty($next_post)):
		?>
			<div class="header-post-navigation hidden-xs" role="navigation">
				<div class="navigation">
					<ul class="nav-links">
					<?php if(!empty($prev_post)) : ?>
						<li class="nav-previous">
							<a href="<?php echo get_permalink($prev_post->ID); ?>" ><?php echo get_the_title($prev_post->ID); ?></a>
							<div class="post-nav-thumb">
								<?php echo get_the_post_thumbnail( $prev_post->ID, 'thumbnail'); ?>
							</div>
						</li>
					<?php endif; ?>
						<li class="archive-page">
							<a href="<?php echo get_post_type_archive_link($atts['archive_post']) ?>"></a>
						</li>
					<?php if(!empty($next_post)) : ?>
						<li class="nav-next">
							<a href="<?php echo get_permalink($next_post->ID); ?>"> <?php echo get_the_title($next_post->ID); ?> </a>
							<div class="post-nav-thumb">
								<?php echo get_the_post_thumbnail( $next_post->ID,'thumbnail' ); ?>
							</div>
						</li>
					<?php endif; ?>
					</ul>
				</div>
			</div>
		<?php
		endif;
	}
}

if( !function_exists('emallshop_related_post')){
	function emallshop_related_post($post_id){
		$related_posts = emallshop_getRelatedPosts($post_id);
		$id = uniqid();
		global $emallshop_owlparam;
		$emallshop_owlparam['productsCarousel']['section-'.$id] = array(
			'item_columns'     => emallshop_get_option('blog-per-row','3'),
			'autoplay'    => emallshop_get_option('blog-carousel-auto-play', 1) ? 'true' : 'false',
			'navigation'  => emallshop_get_option('blog-carousel-navigation', 1) ? 'true' : 'false',
			'loop'        => emallshop_get_option('blog-carousel-loop', 1) ? 'true' : 'false',
			'rp_desktop'     => emallshop_get_option('blog-per-row','3'),
			'rp_small_desktop' => 3,
			'rp_tablet'     => 2,
			'rp_mobile'     => 2,
			'rp_small_mobile' => 1,
			
		);?>
		<div id="section-<?php echo esc_attr($id);?>" class="blogs_carousel related-posts">
		<h3 class="related-posts-title"><span><?php esc_html_e('Related Post','emallshop');?></span></h3>
		<?php if ( $related_posts->have_posts() ) : ?>
			<?php $row=1;?>
			<div class="section-content">
				<div class="row">
					<ul class="product-carousel owl-carousel">
					<?php function new_excerpt_length($length) {
							return 20;
						}
						add_filter('excerpt_length', 'new_excerpt_length');	?>
					<?php while( $related_posts->have_posts() ): $related_posts->the_post();?>
						
						<?php if($row==1){?>
							<li class="slide-row">
								<ul>
						<?php }?>
							<li class="blog-entry">
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<?php // Post thumbnail.
										emallshop_get_post_thumbnail('medium');
									?>													
									<div class="blog-entry-content">
										<div class="entry-date">
											<span class="entry-day"><?php  echo get_the_time('d');?></span>
											<span class="entry-month"><?php  echo get_the_time('M');?></span>
										</div>
										<header class="entry-header">
											<?php the_title( sprintf( '<h4 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );?>
										</header><!-- .entry-header -->
										
										<div class="entry-content">
											<?php the_excerpt(); ?>												
										</div><!-- .entry-content -->
									</div>

								</article><!-- #post-## -->
							</li>
						<?php if($row==1 || $related_posts->current_post+1==$related_posts->post_count){ $row=0;?>
								</ul>
							</li>
						<?php } $row++;?>
					<?php endwhile; // end of the loop. ?>
					</ul>
				</div>
			</div>
		<?php endif;
		wp_reset_postdata();?>
		</div><?php 
	}
}

/* 	Regex
/* ---------------------------------------------------------------------- */
if (!function_exists('emallshop_regex')) {

	/*
	*	Regex for url: http://mathiasbynens.be/demo/url-regex
	*/
	function emallshop_regex($string, $pattern = false, $start = "^", $end = "") {
		if (!$pattern) return false;

		if ($pattern == "url") {
			$pattern = "!$start((https?|ftp)://(-\.)?([^\s/?\.#-]+\.?)+(/[^\s]*)?)$end!";
		} else if ($pattern == "mail") {
			$pattern = "!$start\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$end!";
		} else if ($pattern == "image") {
			$pattern = "!$start(https?(?://([^/?#]*))?([^?#]*?\.(?:jpg|gif|png)))$end!";
		} else if ($pattern == "mp4") {
			$pattern = "!$start(https?(?://([^/?#]*))?([^?#]*?\.(?:mp4)))$end!";
		} else if (strpos($pattern,"<") === 0) {
			$pattern = str_replace('<',"",$pattern);
			$pattern = str_replace('>',"",$pattern);

			if (strpos($pattern,"/") !== 0) { $close = "\/>"; $pattern = str_replace('/',"",$pattern); }
			$pattern = trim($pattern);
			if (!isset($close)) $close = "<\/".$pattern.">";

			$pattern = "!$start\<$pattern.+?$close!";
		}

		preg_match($pattern, $string, $result);

		if (empty($result[0])) {
			return false;
		} else {
			return $result;
		}
	}
}
 
/*  QR Code generation
/* --------------------------------------------------------------------- */ 
if(!function_exists('emallshop_generate_qr_code')) {
    function emallshop_generate_qr_code($text='QR Code', $title = 'QR Code', $size = 128, $class = '', $self_link = false, $lightbox = false ) {
        if($self_link) {
            global $wp;
            $text = @(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
            if ( $_SERVER['SERVER_PORT'] != '80' )
                $text .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
            else 
                $text .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }
        $image = 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chld=H|1&chl=' . $text;

        if($lightbox) {
            $class .= 'fancybox';
            $output = '<a href="'.$image.'" data-fancybox-type="image" class="'.$class.'">'.$title.'</a>';
        } else{
            $class .= ' qr-code-image';
            $output = '<img src="'.$image.'"  class="'.$class.'" />';
        }
        return $output;
    }
}

/*  Add google analytics code
/* --------------------------------------------------------------------- */ 
add_action('init', 'emallshop_google_analytics');
if(!function_exists('emallshop_google_analytics')) {
	function emallshop_google_analytics() {
		$googleCode = emallshop_get_option('google-analytics','');

		if(empty($googleCode)) return;

		if(strpos($googleCode,'UA-') === 0) {

			$googleCode = "

			<script type='text/javascript'>

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '".$googleCode."']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();

			</script>";
		}

		add_action('wp_head', 'emallshop_print_google_code');
	}

	function emallshop_print_google_code() {
		$googleCode = emallshop_get_option('google-analytics','');

		if(!empty($googleCode)) {
			echo $googleCode;
		}
	}
}

/* Change WP coockie notice position
/* --------------------------------------------------------------------*/
if( class_exists('Cookie_Notice') ) {
    remove_action( 'wp_footer', array( $cookie_notice, 'add_cookie_notice' ), 1000 );
    add_action( 'et_after_body', array( $cookie_notice, 'add_cookie_notice' ), 1000 );
}


if( !function_exists('emallshop_newsletter_popup')){
	function emallshop_newsletter_popup(){
	
		if(!emallshop_get_option('newsletter-enable', 0)) return;?>
		
		<div id="newsletterPopup" class="modal fade newsletterPopup">
			<div class="modal-dialog">
				<div class="newsletter-content modal-content modal-md">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="newsletter-logo">
						<?php $newsletter_logo=emallshop_get_option('newsletter-logo',array('url'=>EMALLSHOP_IMAGES.'/logo.png'));
						if( !empty( $newsletter_logo)):?>
							<img src="<?php echo esc_url($newsletter_logo['url']);?>" alt="logo">
						<?php endif;?>
					</div>
					<div class="newsletter-text">
						<h1><?php echo emallshop_get_option('newsletter-title', 'Join Us Now!')?></h1>
						<p class="tag-line"><?php echo emallshop_get_option('newsletter-tag-line', 'Signup today for free and be the first to hear of special promotions, new arrivals and designer news.')?></p>
					</div>
					<div class="newsletter-form">
						<?php if( function_exists( 'mc4wp_show_form' ) ) {
							mc4wp_show_form();
						}?>
						<div class="checkbox-group form-group-top clearfix">
						  <input type="checkbox" id="checkBox1">
						  <label for="checkBox1"> 
							<span class="check"></span>
							<span class="box"></span>
							<?php echo emallshop_get_option('newsletter-dont-show', 'Don\'t show this popup again')?>
						  </label>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php }
}
add_action( 'emallshop_after_footer', 'emallshop_newsletter_popup', 5 );

/* 	Get Social share
/* --------------------------------------------------------------------- */

if( ! function_exists( 'emallshop_add_single_sharing' ) ) {
	function emallshop_add_single_sharing() {
		global $post;
			
		$permalinked = urlencode(get_permalink($post->ID));
		$spermalink = get_permalink($post->ID);
		$title = urlencode($post->post_title);
		$stitle = $post->post_title;
		
		if(is_woocommerce_activated() && is_product() && !emallshop_get_option('show-single-product-sharing', 1)) return;
		
		$data = '<div class="social-share-button">';
		$data .= '<style type="text/css" scoped="scoped">
					.social-share-button{
						display: table;
						margin: 2.6781em 0;
						width: 100%;
					}
					.social-share-item{
						float: left;
						margin-right:.6781em;
					}
					.social-share-linkedin, .social-share-twitter{margin-top: .2em;}
				</style>';
		
		if (emallshop_get_option('social-product-share-fb', 1)) {
			$data .='<div class="social-share-fb social-share-item" >';
			$data .= '<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, \'script\', \'facebook-jssdk\'));</script>';
			$data .= '<div class="fb-like" data-href="'.$spermalink.'" data-send="true" data-layout="button_count" data-width="200" data-show-faces="false"></div>';
			$data .= '</div> <!--Facebook Button-->';
		}
		
		if (emallshop_get_option('social-product-share-tw', 1)) {
			$data .='<div class="social-share-twitter social-share-item" >
						<a href="'.esc_url('https://twitter.com/share').'" class="twitter-share-button" data-url="'. $spermalink .'" data-text="'.$stitle.'" data-count="horizontal">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
					</div> <!--Twitter Button-->';
		}
		
		if (emallshop_get_option('social-product-share-in', 1)) {
			$data .= '<div class="social-share-linkedin social-share-item">
						<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
						<script type="IN/Share" data-url="'. $permalinked .'" data-counter="right"></script>
					</div> <!--linkedin Button-->';
		}
		
		if (emallshop_get_option('social-product-share-gp', 1)) {
			$data .= '<div class="social-share-google-plus social-share-item">
						<!-- Place this tag where you want the +1 button to render -->
						<div class="g-plusone" data-size="medium" data-href="'. $permalinked .'"></div>
			
						<!-- Place this render call where appropriate -->
						<script type="text/javascript">
						  (function() {
							var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
							po.src = "https://apis.google.com/js/plusone.js";
							var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
						  })();
						</script>
					</div> <!--google plus Button-->';
		}

		$data .= '</div>';
		echo $data;

	}
}

/* Get RGB color value
/* --------------------------------------------------------------------- */ 
function emallshop_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns the rgb values separated by commas
   //return $rgb; // returns an array with the rgb values
}

/* Font-Awesome Icon
/* --------------------------------------------------------------------- */ 
function emallshop_font_awesome_icon(){
	return array('fa-glass','fa-music','fa-search','fa-envelope-o','fa-heart','fa-star','fa-star-o','fa-user','fa-film','fa-th-large','fa-th','fa-th-list','fa-check','fa-remove','fa-close','fa-times','fa-search-plus','fa-search-minus','fa-power-off','fa-signal','fa-gear','fa-cog','fa-trash-o','fa-home','fa-file-o','fa-clock-o','fa-road','fa-download','fa-arrow-circle-o-down','fa-arrow-circle-o-up','fa-inbox','fa-play-circle-o','fa-rotate-right','fa-repeat','fa-refresh','fa-list-alt','fa-lock','fa-flag','fa-headphones','fa-volume-off','fa-volume-down','fa-volume-up','fa-qrcode','fa-barcode','fa-tag','fa-tags','fa-book','fa-bookmark','fa-print','fa-camera','fa-font','fa-bold','fa-italic','fa-text-height','fa-text-width','fa-align-left','fa-align-center','fa-align-right','fa-align-justify','fa-list','fa-dedent','fa-outdent','fa-indent','fa-video-camera','fa-photo','fa-image','fa-picture-o','fa-pencil','fa-map-marker','fa-adjust','fa-tint','fa-edit','fa-pencil-square-o','fa-share-square-o','fa-check-square-o','fa-arrows','fa-step-backward','fa-fast-backward','fa-backward','fa-play','fa-pause','fa-stop','fa-forward','fa-fast-forward','fa-step-forward','fa-eject','fa-chevron-left','fa-chevron-right','fa-plus-circle','fa-minus-circle','fa-times-circle','fa-check-circle','fa-question-circle','fa-info-circle','fa-crosshairs','fa-times-circle-o','fa-check-circle-o','fa-ban','fa-arrow-left','fa-arrow-right','fa-arrow-up','fa-arrow-down','fa-mail-forward','fa-share','fa-expand','fa-compress','fa-plus','fa-minus','fa-asterisk','fa-exclamation-circle','fa-gift','fa-leaf','fa-fire','fa-eye','fa-eye-slash','fa-warning','fa-exclamation-triangle','fa-plane','fa-calendar','fa-random','fa-comment','fa-magnet','fa-chevron-up','fa-chevron-down','fa-retweet','fa-shopping-cart','fa-folder','fa-folder-open','fa-arrows-v','fa-arrows-h','fa-bar-chart-o','fa-bar-chart','fa-twitter-square','fa-facebook-square','fa-camera-retro','fa-key','fa-gears','fa-cogs','fa-comments','fa-thumbs-o-up','fa-thumbs-o-down','fa-star-half','fa-heart-o','fa-sign-out','fa-linkedin-square','fa-thumb-tack','fa-external-link','fa-sign-in','fa-trophy','fa-github-square','fa-upload','fa-lemon-o','fa-phone','fa-square-o','fa-bookmark-o','fa-phone-square','fa-twitter','fa-facebook-f','fa-facebook','fa-github','fa-unlock','fa-credit-card','fa-rss','fa-hdd-o','fa-bullhorn','fa-bell','fa-certificate','fa-hand-o-right','fa-hand-o-left','fa-hand-o-up','fa-hand-o-down','fa-arrow-circle-left','fa-arrow-circle-right','fa-arrow-circle-up','fa-arrow-circle-down','fa-globe','fa-wrench','fa-tasks','fa-filter','fa-briefcase','fa-arrows-alt','fa-group','fa-users','fa-chain','fa-link','fa-cloud','fa-flask','fa-cut','fa-scissors','fa-copy','fa-files-o','fa-paperclip','fa-save','fa-floppy-o','fa-square','fa-navicon','fa-reorder','fa-bars','fa-list-ul','fa-list-ol','fa-strikethrough','fa-underline','fa-table','fa-magic','fa-truck','fa-pinterest','fa-pinterest-square','fa-google-plus-square','fa-google-plus','fa-money','fa-caret-down','fa-caret-up','fa-caret-left','fa-caret-right','fa-columns','fa-unsorted','fa-sort','fa-sort-down','fa-sort-desc','fa-sort-up','fa-sort-asc','fa-envelope','fa-linkedin','fa-rotate-left','fa-undo','fa-legal','fa-gavel','fa-dashboard','fa-tachometer','fa-comment-o','fa-comments-o','fa-flash','fa-bolt','fa-sitemap','fa-umbrella','fa-paste','fa-clipboard','fa-lightbulb-o','fa-exchange','fa-cloud-download','fa-cloud-upload','fa-user-md','fa-stethoscope','fa-suitcase','fa-bell-o','fa-coffee','fa-cutlery','fa-file-text-o','fa-building-o','fa-hospital-o','fa-ambulance','fa-medkit','fa-fighter-jet','fa-beer','fa-h-square','fa-plus-square','fa-angle-double-left','fa-angle-double-right','fa-angle-double-up','fa-angle-double-down','fa-angle-left','fa-angle-right','fa-angle-up','fa-angle-down','fa-desktop','fa-laptop','fa-tablet','fa-mobile-phone','fa-mobile','fa-circle-o','fa-quote-left','fa-quote-right','fa-spinner','fa-circle','fa-mail-reply','fa-reply','fa-github-alt','fa-folder-o','fa-folder-open-o','fa-smile-o','fa-frown-o','fa-meh-o','fa-gamepad','fa-keyboard-o','fa-flag-o','fa-flag-checkered','fa-terminal','fa-code','fa-mail-reply-all','fa-reply-all','fa-star-half-empty','fa-star-half-full','fa-star-half-o','fa-location-arrow','fa-crop','fa-code-fork','fa-unlink','fa-chain-broken','fa-question','fa-info','fa-exclamation','fa-superscript','fa-subscript','fa-eraser','fa-puzzle-piece','fa-microphone','fa-microphone-slash','fa-shield','fa-calendar-o','fa-fire-extinguisher','fa-rocket','fa-maxcdn','fa-chevron-circle-left','fa-chevron-circle-right','fa-chevron-circle-up','fa-chevron-circle-down','fa-html5','fa-css3','fa-anchor','fa-unlock-alt','fa-bullseye','fa-ellipsis-h','fa-ellipsis-v','fa-rss-square','fa-play-circle','fa-ticket','fa-minus-square','fa-minus-square-o','fa-level-up','fa-level-down','fa-check-square','fa-pencil-square','fa-external-link-square','fa-share-square','fa-compass','fa-toggle-down','fa-caret-square-o-down','fa-toggle-up','fa-caret-square-o-up','fa-toggle-right','fa-caret-square-o-right','fa-euro','fa-eur','fa-gbp','fa-dollar','fa-usd','fa-rupee','fa-inr','fa-cny','fa-rmb','fa-yen','fa-jpy','fa-ruble','fa-rouble','fa-rub','fa-won','fa-krw','fa-bitcoin','fa-btc','fa-file','fa-file-text','fa-sort-alpha-asc','fa-sort-alpha-desc','fa-sort-amount-asc','fa-sort-amount-desc','fa-sort-numeric-asc','fa-sort-numeric-desc','fa-thumbs-up','fa-thumbs-down','fa-youtube-square','fa-youtube','fa-xing','fa-xing-square','fa-youtube-play','fa-dropbox','fa-stack-overflow','fa-instagram','fa-flickr','fa-adn','fa-bitbucket','fa-bitbucket-square','fa-tumblr','fa-tumblr-square','fa-long-arrow-down','fa-long-arrow-up','fa-long-arrow-left','fa-long-arrow-right','fa-apple','fa-windows','fa-android','fa-linux','fa-dribbble','fa-skype','fa-foursquare','fa-trello','fa-female','fa-male','fa-gittip','fa-gratipay','fa-sun-o','fa-moon-o','fa-archive','fa-bug','fa-vk','fa-weibo','fa-renren','fa-pagelines','fa-stack-exchange','fa-arrow-circle-o-right','fa-arrow-circle-o-left','fa-toggle-left','fa-caret-square-o-left','fa-dot-circle-o','fa-wheelchair','fa-vimeo-square','fa-turkish-lira','fa-try','fa-plus-square-o','fa-space-shuttle','fa-slack','fa-envelope-square','fa-wordpress','fa-openid','fa-institution','fa-bank','fa-university','fa-mortar-board','fa-graduation-cap','fa-yahoo','fa-google','fa-reddit','fa-reddit-square','fa-stumbleupon-circle','fa-stumbleupon','fa-delicious','fa-digg','fa-pied-piper','fa-pied-piper-alt','fa-drupal','fa-joomla','fa-language','fa-fax','fa-building','fa-child','fa-paw','fa-spoon','fa-cube','fa-cubes','fa-behance','fa-behance-square','fa-steam','fa-steam-square','fa-recycle','fa-automobile','fa-car','fa-cab','fa-taxi','fa-tree','fa-spotify','fa-deviantart','fa-soundcloud','fa-database','fa-file-pdf-o','fa-file-word-o','fa-file-excel-o','fa-file-powerpoint-o','fa-file-photo-o','fa-file-picture-o','fa-file-image-o','fa-file-zip-o','fa-file-archive-o','fa-file-sound-o','fa-file-audio-o','fa-file-movie-o','fa-file-video-o','fa-file-code-o','fa-vine','fa-codepen','fa-jsfiddle','fa-life-bouy','fa-life-buoy','fa-life-saver','fa-support','fa-life-ring','fa-circle-o-notch','fa-ra','fa-rebel','fa-ge','fa-empire','fa-git-square','fa-git','fa-hacker-news','fa-tencent-weibo','fa-qq','fa-wechat','fa-weixin','fa-send','fa-paper-plane','fa-send-o','fa-paper-plane-o','fa-history','fa-genderless','fa-circle-thin','fa-header','fa-paragraph','fa-sliders','fa-share-alt','fa-share-alt-square','fa-bomb','fa-soccer-ball-o','fa-futbol-o','fa-tty','fa-binoculars','fa-plug','fa-slideshare','fa-twitch','fa-yelp','fa-newspaper-o','fa-wifi','fa-calculator','fa-paypal','fa-google-wallet','fa-cc-visa','fa-cc-mastercard','fa-cc-discover','fa-cc-amex','fa-cc-paypal','fa-cc-stripe','fa-bell-slash','fa-bell-slash-o','fa-trash','fa-copyright','fa-at','fa-eyedropper','fa-paint-brush','fa-birthday-cake','fa-area-chart','fa-pie-chart','fa-line-chart','fa-lastfm','fa-lastfm-square','fa-toggle-off','fa-toggle-on','fa-bicycle','fa-bus','fa-ioxhost','fa-angellist','fa-cc','fa-shekel','fa-sheqel','fa-ils','fa-meanpath','fa-buysellads','fa-connectdevelop','fa-dashcube','fa-forumbee','fa-leanpub','fa-sellsy','fa-shirtsinbulk','fa-simplybuilt','fa-skyatlas','fa-cart-plus','fa-cart-arrow-down','fa-diamond','fa-ship','fa-user-secret','fa-motorcycle','fa-street-view','fa-heartbeat','fa-venus','fa-mars','fa-mercury','fa-transgender','fa-transgender-alt','fa-venus-double','fa-mars-double','fa-venus-mars','fa-mars-stroke','fa-mars-stroke-v','fa-mars-stroke-h','fa-neuter','fa-facebook-official','fa-pinterest-p','fa-whatsapp','fa-server','fa-user-plus','fa-user-times','fa-hotel','fa-bed','fa-viacoin','fa-train','fa-subway','fa-medium');
}