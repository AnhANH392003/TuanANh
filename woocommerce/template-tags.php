<?php

/* 	Output the start of page wrapper
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_content_wrapper' ) ) {
	function emallshop_output_content_wrapper() {
		?>		
		<div class="container">        	
		<?php		
	}
}

/* 	Output the end of page wrapper
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_content_wrapper_end' ) ) {
	function emallshop_output_content_wrapper_end() {
		?>		
			</div>
		</div>      	
		<?php		
	}
}

/* 	Before shop loop hook
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_before_shop_loop' ) ) {
	function emallshop_before_shop_loop() {
		?>		
		<div class="product-toolbar" <?php if (!is_search() && !woocommerce_products_will_display()) echo ' style="display:none;"' ?>><?php
			/**
			 * @hooked emallshop_grid_list_view - 5
			 * @hooked woocommerce_catalog_ordering - 10
			 * @hooked emallshop_output_pagination_wrapper - 15
			 * @hooked emallshop_product_show_pager - 20
			 * @hooked woocommerce_pagination - 25
			 * @hooked emallshop_output_wrapper_end - 30
			 */
			do_action( 'emallshop_before_shop_loop' );?>
			
		</div><?php		
	}
}

/* 	Output product toolbar wrapper
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_pagination_wrapper' ) ) {
	function emallshop_output_pagination_wrapper() { ?>		
		<div class="product-toolbar-pagination">        	
		<?php		
	}
}

/* 	After shop loop hook
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_after_shop_loop' ) ) {
	function emallshop_after_shop_loop() {
		?>		
		<div class="after-shop-loop product-toolbar-pagination"><?php
			/**
			 * @hooked emallshop_product_show_pager - 10
			 * @hooked woocommerce_pagination - 20
			 */
			do_action( 'emallshop_after_shop_loop' );?>
			
		</div><?php		
	}
}

/* 	Outputs woocommerce wrapper div start for products
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_loop_wrapper' ) ) {
	function emallshop_output_loop_wrapper() { ?>		
		<div class="product-items">  	
		<?php		
	}
}

/* 	Outputs woocommerce wrapper div end for products
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_wrapper_end' ) ) {
	function emallshop_output_wrapper_end() { ?>		
		</div>        	
		<?php		
	}
}

/* 	Product Loop Start
/* --------------------------------------------------------------------- */
if( ! function_exists( 'woocommerce_product_loop_start' ) ) {
	function woocommerce_product_loop_start() {
		
		$product_style= emallshop_get_option('product-layout-style','product-style1') ;
		$product_image_hover_style= emallshop_get_option('product-image-hover-style','product-image-style2');
		$navigation = ($product_image_hover_style == 'product-image-style3') ? 1 : 0;
		$pagination = ($product_image_hover_style == 'product-image-style4') ? 1 : 0;
		
		$default_view="";
		if(emallshop_get_option('product-default-view-style','grid')=="grid"){
			$default_view="grid grid-view";
		}elseif(emallshop_get_option('product-default-view-style','grid')=="expand-grid"){
			$default_view="grid-expand grid-view";
		}elseif(emallshop_get_option('product-default-view-style','grid')=="list"){
			$default_view="list list-view";
		}elseif(emallshop_get_option('product-default-view-style','grid')=="thin-list"){
			$default_view="list-thin list-view";
		}
		
		$classes=$product_style;
		if((is_woocommerce() && !is_product()) || ( is_dokan_activated() && dokan_is_store_page())): 
			$classes.=' is_shop '.$default_view;
		else:
			$classes.=' product-carousel owl-carousel';
		endif;?>
		
		<ul class="products <?php echo esc_attr($classes);?>" data-navigation="<?php echo esc_attr($navigation);?>" data-pagination="<?php echo esc_attr($pagination);?>">
		<?php		
	}
}

/* 	Outputs woocommerce wrapper div start for product image
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_product_image_wrapper' ) ) {
	function emallshop_product_image_wrapper() {
		$product_image_hover_style= emallshop_get_option('product-image-hover-style', 'product-image-style2');?>
		
		<div class="product-image <?php echo esc_attr($product_image_hover_style);?>">  	
		<?php		
	}
}

/* 	woocommerce_before_shop_loop_item_title hook
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_before_shop_loop_item_title' ) ) {
	function emallshop_before_shop_loop_item_title() {
	
		/**
		 * @hooked woocommerce_show_product_loop_sale_flash - 5
		 * @hooked woocommerce_template_loop_product_thumbnail - 5
		 * @hooked woocommerce_template_loop_product_link_close - 10
		 * @hooked emallshop_loop_image_action_buttons - 15
		 * @hooked emallshop_sale_product_countdown - 20
		 * @hooked emallshop_output_wrapper_end - 25
		 */
		do_action( 'emallshop_before_shop_loop_item_title' );	
	}
}

/* 	Outputs woocommerce wrapper div start for product content
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_product_content_wrapper' ) ) {
	function emallshop_product_content_wrapper() {
		?>		
		<div class="product-content">  	
		<?php		
	}
}

/* 	Shop loop title and rating
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_shop_loop_item_title_rating' ) ) {
	function emallshop_shop_loop_item_title_rating() {
		?>		
		<div class="product-title-rating"><?php
			/**
			 * @hooked emallshop_shop_loop_item_title - 10
			 * @hooked emallshop_product_rating_html - 15
			 */
			do_action( 'emallshop_shop_loop_item_title_rating' );?>
			
		</div><?php		
	}
}

/* 	Outputs woocommerce products listing title
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_shop_loop_item_title' ) ) {
	function emallshop_shop_loop_item_title() {
		?>
		<a class="product-title" href="<?php the_permalink(); ?>">
		<?php the_title( '<h3>', '</h3>' );?>
		</a>
		<?php		
	}
}

/* 	Shop loop price
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_loop_price' ) ) {
	function emallshop_template_loop_price() {
		if(!emallshop_get_option('show-product-price', 1)) return; ?>		
		<div class="product-loop-price"><?php
			/**
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'emallshop_template_loop_price' );?>
			
		</div><?php		
	}
}

/* 	Outputs woocommerce wrapper div start for product buttons
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_product_buttons_wrapper' ) ) {
	function emallshop_output_product_buttons_wrapper() {
		?>		
		<div class="product-buttons">  	
		<?php		
	}
}

/* 	Outputs woocommerce wrapper div start for cart button
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_cart_button_wrapper' ) ) {
	function emallshop_output_cart_button_wrapper() {?>		
		<div class="product-cart">  	
		<?php		
	}
}

/* 	Single Product Summary Title
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_single_title' ) ) {
	function emallshop_template_single_title() {?>		
		<div class="single-product-title"><?php
			/**
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked emallshop_single_product_pagination - 10
			 */
			do_action( 'emallshop_template_single_title' );?>
			
		</div><?php		
	}
}

/* 	Single Product Summary Price
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_single_price' ) ) {
	function emallshop_template_single_price() {
		?>		
		<div class="product-price"><?php
			/**
			 * @hooked emallshop_product_show_pager - 5
			 * @hooked emallshop_single_product_pagination - 10
			 */
			do_action( 'emallshop_template_single_price' );?>
			
		</div><?php		
	}
}
