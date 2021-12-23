<?php
/**
 * The sidebar containing the shop widget area
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
$is_sidebar=0;

if(is_single()):	
	$is_sidebar = (emallshop_get_option('single-product-page-layout','right')=="full-layout") ? 0 : 1;	
	$sidebar_position=emallshop_woo_page_sidebar_position(emallshop_get_option('single-product-page-layout','right'));
else:
	$is_sidebar= (emallshop_get_option('shop-page-layout','left') =="full-layout") ? 0 : 1;	
	$sidebar_position=emallshop_woo_page_sidebar_position(emallshop_get_option('shop-page-layout','right'));
endif;

if(is_single()):
	$shop_page_sidebar=emallshop_get_option('single-product-page-sidebar-widget', 'single-product');
elseif(!is_single()):
	$shop_page_sidebar=emallshop_get_option('shop-page-sidebar-widget', 'shop-page');
else:
	$shop_page_sidebar="woocommerce-widget";
endif;

if($is_sidebar):?>
<div id="sidebar" class="sidebar col-xs-12 col-sm-4 col-md-3 <?php echo esc_attr($sidebar_position);?>">
	<div id="secondary" class="secondary">

		<?php if ( is_active_sidebar( $shop_page_sidebar ) ) : ?>
			<div id="widget-area" class="widget-area" role="complementary">
				<?php dynamic_sidebar( $shop_page_sidebar ); ?>
			</div><!-- .widget-area -->
		<?php endif; ?>

	</div><!-- .secondary -->
</div>
<?php endif;?>
