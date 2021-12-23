<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
?>
<div class="images">
	<div id="product-image">
	<?php
		if ( has_post_thumbnail() ) {
			$attachment_count = count( $product->get_gallery_attachment_ids() );
			$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
			$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
			) );
			
			if(emallshop_get_option('product-image-zoom-type','outer-zoom')!='default'){
				$image_single_link = wp_get_attachment_image_src(get_post_thumbnail_id(), 'shop_single');?>
				
				<img id="<?php echo emallshop_get_option('product-image-zoom-type','outer-zoom');?>" src="<?php echo esc_url($image_single_link[0]);?>" data-large="<?php echo esc_url($props['url']);?>" alt="<?php echo esc_attr($props['caption']);?>" />

			<?php }else{
				echo apply_filters(
					'woocommerce_single_product_image_html',
					sprintf(
						'<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto%s">%s</a>',
						esc_url( $props['url'] ),
						esc_attr( $props['caption'] ),
						$gallery,
						$image
					),
					$post->ID
				);
			}
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'emallshop' ) ), $post->ID );
		}
	?>
	</div>
	<?php
		do_action( 'woocommerce_product_thumbnails' );
	?>
</div>
