<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
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

global $post, $product, $woocommerce;
$attachment_ids = $product->get_gallery_attachment_ids();

if ( $attachment_ids ) {
	$loop 		= 0;
	$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	?>
	<div class="thumbnails <?php echo 'columns-' . $columns; ?> thumbnails-carousel owl-carousel"><?php

		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop === 0 || $loop % $columns === 0 ) {
				$classes[] = 'first';
			}

			if ( ( $loop + 1 ) % $columns === 0 ) {
				$classes[] = 'last';
			}

			$image_class = implode( ' ', $classes );
			$props       = wc_get_product_attachment_props( $attachment_id, $post );

			if ( ! $props['url'] ) {
				continue;
			}
			
			if(emallshop_get_option('product-image-zoom-type','inner-zoom')!='default'){?>
				<div class="item">
					<a class="fancybox" href="<?php echo esc_url($props['url']);?>"></a>
					<?php 
					$image = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
					'class' => 'lazyOwl',
					'data-large' => $props['url'],
					'title'	=> $props['caption'],
					'alt'	=> $props['caption']
					) );
					
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '%s', $image ), $attachment_id, $post->ID, esc_attr( $image_class ));?>	
				</div>
			<?php }else{?>
				<div class="item"><?php 
				echo apply_filters(
					'woocommerce_single_product_image_thumbnail_html',
					sprintf(
						'<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>',
						esc_url( $props['url'] ),
						esc_attr( $image_class ),
						esc_attr( $props['caption'] ),
						wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $props )
					),
					$attachment_id,
					$post->ID,
					esc_attr( $image_class )
				);?>
				</div>
			<?php }			

			$loop++;
		}

	?></div>
	<?php
}
