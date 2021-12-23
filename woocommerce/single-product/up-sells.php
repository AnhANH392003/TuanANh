<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $woocommerce_loop, $emallshop_owlparam;

if ( (! $upsells = $product->get_upsells()) || !emallshop_get_option('show-upsell-products', 1) ) {
	return;
}

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => $posts_per_page,
	'orderby'             => $orderby,
	'post__in'            => $upsells,
	'post__not_in'        => array( $product->id ),
	'meta_query'          => WC()->query->get_meta_query()
);

$products                    = new WP_Query( $args );
$woocommerce_loop['name']    = 'up-sells';
$woocommerce_loop['columns'] = apply_filters( 'woocommerce_up_sells_columns', $columns );

$id = uniqid();
$emallshop_owlparam['productsCarousel']['section-'.$id] = array(
	'autoplay'     => (emallshop_get_option('related-upsell-auto-play', 1)) ? 'true' : 'false',
	'loop'         => (  emallshop_get_option('related-upsell-loop', 1)) ? 'true' : 'false',
	'navigation'   => ( emallshop_get_option('related-upsell-navigation', 1)) ? 'true' : 'false',
	'dots'         => ( emallshop_get_option('related-upsell-product-dots', 0)) ? 'true' : 'false',
	'rp_desktop'   => emallshop_get_option('related-upsell-products-per-row', '4') ,
	'rp_small_desktop' => 3,
	'rp_tablet'    => 2,
	'rp_mobile'    => 2,
	'rp_small_mobile' => 1,
);
$products_row=1;

if ( $products->have_posts() ) : 
$row=1;?>
	<div class="up-sells upsells products">

		<h2><span><?php esc_html_e( 'You may also like&hellip;', 'emallshop' ) ?></span></h2>
		
		<div id="section-<?php echo esc_attr($id);?>" class="product-items">
			<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
				
				<?php if($row==1){?>
					<li class="slide-row">
						<ul>
				<?php }?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
				<?php if($row==$products_row || $products->current_post+1==$products->post_count){ $row=0;?>
						</ul>
					</li>
				<?php } $row++;?>

			<?php endwhile; // end of the loop. ?>
			
			<?php woocommerce_product_loop_end(); ?>
		</div>		

	</div>

<?php endif;

wp_reset_postdata();
