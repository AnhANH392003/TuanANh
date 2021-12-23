<?php 
/**
 * EmallShop Woocommerce Customizer Functions
 *
 * @package PressLayouts
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* 	woocommerce fucntions and customize


/*  Prodcut live search form
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_products_live_search_form' ) ) :
	function emallshop_products_live_search_form() {
		if(!is_woocommerce_activated()):
			return false;
		endif;
		?>
	<div class="search-area">
		<form id="search-header-form" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
			<div class="search-control-group control-group">
				<div class="search-field-area">
					<label class="screen-reader-text"><?php esc_html_e( 'I\'m shopping for...', 'emallshop' ); ?></label>
					<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'I\'m shopping for...', 'placeholder', 'emallshop' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'I\'m shopping for...', 'label', 'emallshop' ); ?>" />
					<img class="Typeahead-spinner" src="<?php echo esc_url(EMALLSHOP_IMAGES.'/ajax-loader.gif');?>" alt="search loader">
				</div>
				<div class="search-bar-controls">
				<?php
					$selected_cat = isset($_GET['product_cat']) ? $_GET['product_cat'] : '';     
					
					$args = array(
					  'name'         => 'product_cat',
					  'value_field'  =>'slug',
					  'class'        => 'categories-filter selectBox',
					  'show_option_none' => esc_html__( 'All Categories','emallshop' ),
					  'option_none_value' => '0',
					  'hide_empty'   => 1,
					  'orderby'      => 'name',
					  'order'        => 'asc',
					  'echo'         => 0,
					  'taxonomy'     => 'product_cat',
					);
					
					if($selected_cat !=''):
						$args['selected'] = $selected_cat;
					else:
						$args['selected'] = 0;
					endif;
					
					if(emallshop_get_option('search-categories','all') =='parent'):
						$args['depth'] = 1;
					endif;
					
					if( emallshop_get_option('categories-hierarchical', 1) ==1):
						$args['hierarchical'] = true;
					endif;
					
					if(emallshop_get_option('show-categories-dropdow', 1) ==1):
						echo wp_dropdown_categories( $args );
					endif;
					?>
				
				<button type="submit" class="search-btn"></button>
				<input type="hidden" name="post_type" value="product" />
				</div>
			</div>	
		</form>
	</div><?php
	}
endif;

/* 	Prodcut live search
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_products_live_search' ) && is_woocommerce_activated() ) :
	function emallshop_products_live_search() {
		
		if ( isset( $_REQUEST['fn'] ) && 'get_ajax_search' == $_REQUEST['fn'] ) {
			$query_args = array(
				//'posts_per_page' 	=> 10,
				'no_found_rows' 	=> true,
				'post_type'			=> 'product',
				'post_status'		=> 'publish',
				'meta_query'		=> array(
					array(
						'key' 		=> '_visibility',
						'value' 	=> array( 'search', 'visible' ),
						'compare' 	=> 'IN'
					)
				)
			);

			if( isset( $_REQUEST['terms'] ) ) {
				$query_args['s'] = $_REQUEST['terms'];
			}
			
			if(isset($_REQUEST['cat_slug']) && $_REQUEST['cat_slug']!='0' && $_REQUEST['cat_slug']!='-1'){
				$query_args['tax_query']=array(
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'slug',
									'terms'    => $_REQUEST['cat_slug'],
									'operator' => 'IN'
								)
							);
			}

			$search_query = new WP_Query( $query_args );
	 
			$results = array();
			if ( $search_query->get_posts() ) {
				foreach ( $search_query->get_posts() as $the_post ) {
					$title = get_the_title( $the_post->ID );
					if ( has_post_thumbnail( $the_post->ID ) ) {
						$post_thumbnail_ID = get_post_thumbnail_id( $the_post->ID );
						$post_thumbnail_src = wp_get_attachment_image_src( $post_thumbnail_ID, 'thumbnail' );
					}else{
						$dimensions = wc_get_image_size( 'thumbnail' );
						$post_thumbnail_src = array(
							wc_placeholder_img_src(),
							esc_attr( $dimensions['width'] ),
							esc_attr( $dimensions['height'] )
						);
					}

					$product = new WC_Product( $the_post->ID );
					$price = $product->get_price_html();
					$title = html_entity_decode( $title , ENT_QUOTES, 'UTF-8' );
					
					$results[] = array(
						'p_title' 	=> $title,
						'p_url' 	=> get_permalink( $the_post->ID ),
						'p_image' 	=> $post_thumbnail_src[0],
						'p_price'	=> $price,
						'empty_msg' => 'none',
					);
				}
			} else {
				$results[] = array(
						'empty_msg' => esc_html__( 'Sorry. No results match your search.', 'emallshop' ),
				);
			}		
			wp_reset_postdata();
			echo json_encode( $results );
		}
		die();
	}
endif;

/* 	Ensure cart contents update when products are added to the cart via AJAX
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_header_mini_cart_fragment' ) ) {
	function emallshop_header_mini_cart_fragment( $fragments ) {
		global $woocommerce;
		$header_style=emallshop_get_option('header-layout','header-1');
		ob_start();	?>
		
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
			<?php }elseif($header_style=="header-4" || $header_style=="header-6" || $header_style=="header-7" || $header_style=="header-8" || $header_style=="header-9" ){?>
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
		$fragments['div.header-cart-content'] = ob_get_clean();
		return $fragments;
	}
}
add_filter('add_to_cart_fragments', 'emallshop_header_mini_cart_fragment');

if( ! function_exists( 'emallshop_topbar_cart_fragment' ) ) {
	function emallshop_topbar_cart_fragment( $fragments ) {
		global $woocommerce;
		$cart_url = $woocommerce->cart->get_cart_url();
		ob_start();?>
		<span class="topbar-cart">
			<a href="<?php echo esc_url($cart_url);?>">
				<i class="fa fa-shopping-cart"></i>
				<label class="cart-count"><?php echo esc_attr($woocommerce->cart->cart_contents_count);?></label>
				<span class="header-cart-text"><?php esc_html_e('Shopping Cart','emallshop');?></span>
			</a>
		</span>
		<?php
		$fragments['span.topbar-cart'] = ob_get_clean();
		return $fragments;
	}
}
add_filter('add_to_cart_fragments', 'emallshop_topbar_cart_fragment');

/* 	Ajax Count Wishlist Product
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_ajax_wishlist_count' ) ) {
	function emallshop_ajax_wishlist_count(){
		if( function_exists( 'YITH_WCWL' ) ){
			wp_send_json( YITH_WCWL()->count_products() );
			die();
		}
	}
}
add_action( 'wp_ajax_emallshop_ajax_wishlist_count', 'emallshop_ajax_wishlist_count' );
add_action( 'wp_ajax_nopriv_emallshop_ajax_wishlist_count', 'emallshop_ajax_wishlist_count' );

/* 	Ajax Count Compare Product
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_ajax_compare_count' ) ) {
	function emallshop_ajax_compare_count(){
		if( defined( 'YITH_WOOCOMPARE' ) ){			
			
			$products_list=array();
			$products_list = isset( $_COOKIE[ 'yith_woocompare_list' ] ) && !empty($_COOKIE[ 'yith_woocompare_list' ]) ? maybe_unserialize( $_COOKIE[ 'yith_woocompare_list' ] ) : array();
			$products_list= json_decode($products_list);
			if (!empty($products_list) && $products_list > 0) {
				
				if( isset( $_REQUEST['id'] ) ) {
					if ( $_REQUEST['id'] == 'all' ) {
						unset($products_list);
					} else {
						$products_list=array_diff($products_list, array($_REQUEST['id']));
					}
				}			
				
				echo count($products_list);
			} else {
				echo '0';
			}
		}
		die();
	}
}
add_action( 'wp_ajax_emallshop_ajax_compare_count', 'emallshop_ajax_compare_count' );
add_action( 'wp_ajax_nopriv_emallshop_ajax_compare_count', 'emallshop_ajax_compare_count' );

/* 	Output the start of page wrapper
/* --------------------------------------------------------------------- */

if( ! function_exists( 'emallshop_output_primary_wrapper' ) ) {
	function emallshop_output_primary_wrapper() {
			
		if(is_single()):
			$column_classs=emallshop_woo_page_colunm_class(emallshop_get_option('single-product-page-layout','right'));
		elseif( is_dokan_activated() && dokan_is_store_page() ):
			$column_classs=emallshop_woo_page_colunm_class(emallshop_get_option('dokan-store-page-layout','left'));
		else:
			$column_classs=emallshop_woo_page_colunm_class(emallshop_get_option('shop-page-layout','left'));
		endif;
		?>
		<div class="row">
        	<div class="content-area <?php echo esc_attr($column_classs);?>">
	<?php	
	}
}

/* 	shop / single page colunm class
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_woo_page_colunm_class')) {
	function emallshop_woo_page_colunm_class($shop_page_layout)
	{
		if(isset($shop_page_layout) && $shop_page_layout!="full-layout"):
			if($shop_page_layout=="left"):
				if(is_rtl()){
					$column_classs="col-xs-12 col-sm-8 col-md-9 col-sm-pull-4 col-md-pull-3";
				}else{
					$column_classs="col-xs-12 col-sm-8 col-md-9 col-sm-push-4 col-md-push-3";
				}
				
			else:
				$column_classs="col-xs-12 col-sm-8 col-md-9";
			endif;
		else:
			$column_classs="col-xs-12 col-sm-12 col-md-12";
		endif;
		
		return $column_classs;
	}
}

/* 	shop / single page sidebar position
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_woo_page_sidebar_position')) {
	function emallshop_woo_page_sidebar_position($shop_page_layout)
	{
		$sidebar_position='';
		if(isset($shop_page_layout) && $shop_page_layout!="full_layout"):
			if($shop_page_layout=="left"):
				if(is_rtl()){
					$sidebar_position='col-sm-push-8 col-md-push-9';
				}else{
					$sidebar_position='col-sm-pull-8 col-md-pull-9';
				}			
			endif;		
		endif;
		
		return $sidebar_position;
	}
}

/* 	Removes the "shop" title on the main shop page
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_woo_hide_page_title')) {
	function emallshop_woo_hide_page_title() {
		if( emallshop_get_option('show-page-title', 1) && emallshop_get_option('show-title-breadcrumb-content','in-page-heading')=="in-page-content" ){
			return true;
		}else{
			return false;	
		}
	}
}
add_filter( 'woocommerce_show_page_title' , 'emallshop_woo_hide_page_title' );

/* 	Change woocommerce breadcrumb seperator
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_woocommerce_breadcrumbs')) {
	function emallshop_woocommerce_breadcrumbs() {
		return array(
				'delimiter'   => is_rtl() ? ' <span><i class="fa fa-angle-left"></i></span> ' : ' <span><i class="fa fa-angle-right"></i></span> ',
				'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
				'wrap_after'  => '</nav>',
				'before'      => '<span>',
				'after'       => '</span>',
				'home'        => _x( 'Home', 'breadcrumb', 'emallshop' ),
			);
	} 
}
add_filter( 'woocommerce_breadcrumb_defaults', 'emallshop_woocommerce_breadcrumbs' );

/* 	Category Banner
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_caregory_banner')) {
	function emallshop_caregory_banner() {
		
		$cate = get_queried_object();
		$enable_banner=emallshop_get_option('banner-sub-categories-brands',array());
		if((!empty($enable_banner) && !in_array('category-banner', $enable_banner)) || !isset($cate->term_id)) return;
		
		$thumbnail_id = get_woocommerce_term_meta( $cate->term_id, 'banner_thumbnail_id' );
		$image_src = wp_get_attachment_image_src( $thumbnail_id, 'full' );
		if(!empty($image_src)){ ?>		
			<div class="category-banner-content">
				<img class="category-banner" src="<?php echo esc_url($image_src[0]);?>" alt="category-banner"/>
			</div>
		<?php }
	}
}

/* 	Sub Categories slider
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_sub_caregories')) {
	function emallshop_sub_caregories() {
		
		$cate = get_queried_object();
		$enable_banner=emallshop_get_option('banner-sub-categories-brands',array());
		if( ( !empty($enable_banner) && !in_array('sub-categories', $enable_banner)) || !isset($cate->term_id)) return;
		
		$args = array(
				    'hide_empty' => 1,
					'number' => 12,
					'parent' => $cate->term_id,
				    'taxonomy' => 'product_cat'
				);
		$subcats = get_categories($args);
		if ( !empty($subcats) ) : $lastElement = end($subcats);
			
			$id = uniqid();
			$categories_row=1;
			$category_style=emallshop_get_option('sub-categories-style', 'only_category');
			global $emallshop_owlparam;
			$emallshop_owlparam['productsCarousel']['section-'.$id] = array(
				'autoplay'    => "false",
				'loop'        => "false",
				'navigation'  => "true",
				'dots'  => "false",
				'rp_desktop'     => 4,
				'rp_small_desktop' => 4,
				'rp_tablet'     => 3,
				'rp_mobile'     => 2,
				'rp_small_mobile' => 1,
			);			
			$row=1;?>
			<div id="section-<?php echo esc_attr($id);?>" class="product-section categories-slider-content product-items <?php echo esc_attr($category_style);?>">
				<div class="section-header">
					<div class="section-title">
						<h3><?php esc_html_e("Categories","emallshop");?></h3>
					</div>
				</div>
				<ul class="product-carousel owl-carousel">
					<?php foreach($subcats as $cate):
										
						$cate_link = get_term_link( $cate );
						
						//Get Sub Categories								
						$args['parent']= $cate->term_id;
						$args['number']= 4;
						$inner_subcats = get_categories($args);
						if($category_style=="sub_category_box" && !empty($inner_subcats)){
							if($row==1){?>
								<li class="slide-row">
									<ul>
							<?php }?>							
							
							<li class="category-entry">	
								<h6 class="category-title">
									<a href="<?php echo esc_url($cate_link);?>"><?php echo esc_html($cate->name);?></a>
								</h6>
								<div class="category-image">
									<a href="<?php echo esc_url($cate_link);?>">
										<?php $thumbnail_id = get_woocommerce_term_meta( $cate->term_id, 'thumbnail_id', true );
										$catalog_img = wp_get_attachment_image_src( $thumbnail_id, 'shop_catalog' );
										if ( !empty($catalog_img) ) {?>											
											<img src="<?php echo esc_url($catalog_img[0]);?>" alt="<?php echo esc_html($cate->name);?>" />
										<?php }else{?>
											<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/product-listing-placeholder.jpg');?>"/>
										<?php }?>
									</a>
								</div>
								<div class="sub-categories-list">
									<?php if(!empty($inner_subcats)){?>
										<ul class="sub-categories">
											<?php foreach($inner_subcats as $iner_cate){ 
												$inner_subcat_link = get_term_link( $iner_cate ); ?>
												<li>
													<a href="<?php echo esc_url($inner_subcat_link);?>"><?php echo esc_html($iner_cate->name);?></a>
												</li>
											<?php }?>
											<li class="show-all-cate">
												<a href="<?php echo esc_url($cate_link);?>"><?php echo esc_html__('Show All', 'emallshop');?></a>
											</li>
									</ul>
									<?php }?>
								</div>
							</li>
							<?php if($row==$categories_row || $cate==$lastElement){ $row=0;?>
									</ul>
								</li>
							<?php } $row++;
						}elseif($category_style=="only_category"){
							if($row==1){?>
								<li class="slide-row">
									<ul>
							<?php }?>
								<li class="category-entry">
									<a href="<?php echo esc_url($cate_link);?>">
										<div class="category-image">
											<?php $thumbnail_id = get_woocommerce_term_meta( $cate->term_id, 'thumbnail_id', true );
											$catalog_img = wp_get_attachment_image_src( $thumbnail_id, 'shop_catalog' );
											if ( !empty($catalog_img) ) {?>											
												<img src="<?php echo esc_url($catalog_img[0]);?>" alt="<?php echo esc_html($cate->name);?>" />
											<?php }else{?>
												<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/product-listing-placeholder.jpg');?>"/>
											<?php }?>											
										</div>
										<div class="category-content">
											<h3><?php echo esc_html($cate->name);?></h3>
											<?php echo apply_filters( 'woocommerce_subcategory_count_html', sprintf( '<span class="category-items" />%s %s</span>', $cate->count, esc_html__( 'Items', 'emallshop' ) ), $cate );?>											
										</div>
									</a>
								</li>
							<?php if($row==$categories_row || $cate==$lastElement){ $row=0;?>
									</ul>
								</li>
							<?php } $row++;
						}?>
					<?php endforeach; // end of the loop. ?>
				</ul>
			</div>
		<?php endif;
		wp_reset_query();
	}
}

/* 	Prodcut Brands
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_caregory_brands')) {
	function emallshop_caregory_brands() {
		$cate = get_queried_object();
		
		if(!in_array('caregory-brands', emallshop_get_option('banner-sub-categories-brands','')) || !isset($cate->term_id)) return;
		
		$args = array(
					'orderby' => 'ID',
					'order' => 'DESC',
					'number' => 16,
					'hierarchical' => 1,
				    'show_option_none' => '',
				    'hide_empty' => 1,
				    'taxonomy' => 'product_brand'
				);
		
		$brands = get_categories($args);
		
		$id = uniqid();
		$brands_row=1;
		global $emallshop_owlparam;
		$emallshop_owlparam['productsBrands']['section-'.$id] = array(
			'item_columns'     => 6,
			'autoplay'    => 'false',
			'loop'        => 'false',
			'navigation'  => 'true',
			'dots'  	  => 'false',
		);		
		$row=1;?>
		<?php if ( !empty($brands) ) :?>
		<div id="section-<?php echo esc_attr($id);?>" class="products_brands-content product-section products_brands">
			<div class="section-header">
				<div class="section-title">
					<h3><?php esc_html_e("Brands","emallshop");?></h3>
				</div>
			</div>			
			<?php $row=1;?>
			<div class="product-items">
				<ul class="brands brands-carousel owl-carousel">
				<?php foreach($brands as $brand): ?>
					<?php if($row==1){?>
						<li class="slide-row">
							<ul>
					<?php }
						$thumbnail_id = get_woocommerce_term_meta( $brand->term_id, 'thumbnail_id' ) ;
						$image_src = wp_get_attachment_image_src( $thumbnail_id, 'full' ) ;
						$brand_link = get_term_link( $brand, 'product_brand' ) ;?>
						<li class="brand-item">
							<a href="<?php echo esc_url($brand_link) ?>">									
							<?php if ( !empty($image_src) ) {?>
								<img class="lazyOwl" alt="<?php echo esc_attr($brand->cat_name)?>" src="<?php echo esc_url($image_src[0])?>"/>
							<?php }else{?>
								<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/brand-placeholder.jpg');?>"/>
							<?php }?>									
							</a>
						</li>
					<?php if($row==$brands_row){ $row=0;?>
						</ul>
							</li>
					<?php } $row++;?>
				<?php endforeach; // end of the loop. ?>
				</ul>
			</div>
			<?php wp_reset_query();?>
		</div>
	<?php endif;
	}
}

/* 	Grid / List view toggle
/* --------------------------------------------------------------------- */
function emallshop_grid_list_view() {
	if(!emallshop_get_option('show-grid-list-button', 1)) return; ?>	
	
	<div class="gridlist-toggle">
		<?php if(in_array('grid', emallshop_get_option('product-view-style',array( 'grid', 'list' )))){?>
			<a href="#" class="grid grid-view <?php echo (emallshop_get_option('product-default-view-style','grid')=="grid") ? 'active' : '';?>" title="<?php esc_html_e('View as Grid', 'emallshop'); ?>"><i class="fa fa-th"></i></a>
		<?php }?>
		<?php if(in_array('expand-grid', emallshop_get_option('product-view-style',array( 'grid', 'list' )))){?>
			<a href="#" class="grid-expand grid-view <?php echo (emallshop_get_option('product-default-view-style','grid')=="expand-grid") ? 'active' : '';?>" title="<?php esc_html_e('View as Expand Grid', 'emallshop'); ?>"><i class="fa fa-th-large"></i></a>
		<?php }?>
		<?php if(in_array('list', emallshop_get_option('product-view-style',array( 'grid', 'list' )))){?>
			<a href="#" class="list list-view <?php echo (emallshop_get_option('product-default-view-style','grid')=="list") ? 'active' : '';?>" title="<?php esc_html_e('View as List', 'emallshop'); ?>"><i class="fa fa-th-list"></i></a>
		<?php }?>
		<?php if(in_array('thin-list', emallshop_get_option('product-view-style',array( 'grid', 'list' )))){?>
			<a href="#" class="list-thin list-view <?php echo (emallshop_get_option('product-default-view-style','grid')=="thin-list") ? 'active' : '';?>" title="<?php esc_html_e('View as Thin List ', 'emallshop'); ?>"><i class="fa fa-list"></i></a>	
		<?php }?>
	</div>
	<?php
}

/* 	Change number of products to be displayed 
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_pre_get_products' ) ) {
	function emallshop_pre_get_products() {
		
		$default_products=emallshop_get_option('products-show-per-page','12');		
		
		return $number = isset( $_GET['showproducts'] ) ? absint( $_GET['showproducts'] ) : $default_products;
	}
}
add_filter('loop_shop_per_page', 'emallshop_pre_get_products');
	
/* 	Product show per page 
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_product_show_pager' ) ) {
	function emallshop_product_show_pager() {
		
		if(!emallshop_get_option('show-products-per-page', 1) ) return;
		
		$numbers = array(6, 8, 10, 12, 15, 16, 18, 20, 24, 27, 28, 30, 32, 33, 36, 40, 48, 60, 72, 84, 108, 120 );

		$options   = array();
		$showproducts = get_query_var( 'posts_per_page' );
		if(!$showproducts){
			$showproducts =emallshop_get_option('products-show-per-page','12');	
		}
		foreach ( $numbers as $number ):
			$options[] = sprintf(
				'<option value="%s" %s>%s %s</option>',
				esc_attr( $number ),
				selected( $number, $showproducts, false ),
				$number,'','');
		endforeach;
		?>		
		<form class="show-products-number" method="get">
			<span><?php esc_html_e( 'View', 'emallshop' ) ?>:</span>
			<select name="showproducts" class="selectBox">
				<?php echo implode( '', $options ); ?>
			</select>
			<?php
			foreach( $_GET as $name => $value ) {
				if ( 'showproducts' != $name ) {
					printf( '<input type="hidden" name="%s" value="%s">', esc_attr( $name ), esc_attr( $value ) );
				}
			}
			?>
		</form>
		<?php
	}
}

/* 	Change number or products per row
/* --------------------------------------------------------------------- */
add_filter('loop_shop_columns', 'emallshop_loop_columns');
if (!function_exists('emallshop_loop_columns')) {
	function emallshop_loop_columns() {
		
		return emallshop_get_option('products-per-row','4'); // products per row
		
		
	}
}

/* 	Add second thumbnail in products list page
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_loop_product_thumbnail' ) ) {
	function emallshop_template_loop_product_thumbnail(){
		global $post;
		$product_image_hover_style= emallshop_get_option('product-image-hover-style','product-image-style2');
		$id = get_the_ID();
		$gallery = get_post_meta($id, '_product_image_gallery', true);
		$size = 'shop_catalog';
		$thumb_image = get_the_post_thumbnail($id , $size, array('class' => 'front-image'));
		if(!wp_get_attachment_url( get_post_thumbnail_id() )){
			$thumb_image ="<img src='".EMALLSHOP_URI."/images/product-listing-placeholder.jpg' />";
		}elseif(!$thumb_image) {
			if ( wc_placeholder_img_src() ) {
				$thumb_image = wc_placeholder_img( $size );
			}
		}
		
		$attachment_image = '';
		if (!empty($gallery) && ($product_image_hover_style=="product-image-style2")) {
			$galleries1 = explode(',', $gallery);
			$first_image_id = $galleries1[0];
			$attachment_image = wp_get_attachment_image($first_image_id , $size, false, array('class' => 'back-image'));
		}
		
		if (!empty($gallery) && ($product_image_hover_style=="product-image-style3" || $product_image_hover_style=="product-image-style4" )) {
			$galleries2 = explode(',', $gallery);
			
			$attachment_image ='<ul class="product_image_gallery owl-carousel owl-theme">';
			$attachment_image .='<li>'.$thumb_image.'</li>';
			foreach($galleries2 as $gallery2):
				//$image_id = $gallery2;
				
				$attachment_image .='<li>';
				$attachment_image .= wp_get_attachment_image($gallery2 , $size, false, array());
				$attachment_image .='</li>';
				
			endforeach;
			$attachment_image .='</ul>';			
		}				
		echo $thumb_image;
		echo $attachment_image;
	}
}

/*  Product image action buttons
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_loop_image_action_buttons' ) ) {
	function emallshop_loop_image_action_buttons() {
		global $post;
		$id = get_the_ID();
		$gallery = get_post_meta($id, '_product_image_gallery', true);
		$product_image_hover_style=  emallshop_get_option('product-image-hover-style','product-image-style2');
		$navigation = ($product_image_hover_style == 'product-image-style3') ? true : false;
		
		if(!empty($gallery) && $navigation):?>
			<div class="product-slider-controls owl-nav post-<?php echo esc_attr($id);?>">
				<div class="owl-prev"></div>
				<div class="owl-next"></div>
			</div>
		<?php endif;?>
		
		<?php if(!emallshop_get_option('show-product-buttons', 1)) return;?>
		
		<div class="product-buttons">
			<?php 
			if( emallshop_get_option('show-wishlist-button', 1) ==1 ): 
				if( function_exists( 'YITH_WCWL' ) ):
					echo do_shortcode('[yith_wcwl_add_to_wishlist]');
				endif;
			endif;
			
			if( emallshop_get_option('show-compare-button', 1) ==1 ): 
				if(defined( 'YITH_WOOCOMPARE' )):
					echo do_shortcode('[yith_compare_button]');
				endif;
			endif;
			
			if( emallshop_get_option('show-quick-view-button', 1) ==1 ): ?>
				<div class="quickview-button">
					<a class="quickview" href="#" data-product_id="<?php echo $post->ID; ?>"><?php esc_html_e('Quick View','emallshop');?></a>
				</div><?php 
			endif;?>
		</div>
		<?php 
	}
}

/*  Product content action buttons
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_loop_content_action_buttons' ) ) {
	function emallshop_loop_content_action_buttons() {
		global $post;
		
		if(!emallshop_get_option('show-product-buttons', 1)) return;
		
		if( emallshop_get_option('show-wishlist-button', 1)==1 ): 
			if( function_exists( 'YITH_WCWL' ) ):
				echo do_shortcode('[yith_wcwl_add_to_wishlist]');
			endif;
		endif;
		
		if( emallshop_get_option('show-compare-button', 1)==1): 
			if(defined( 'YITH_WOOCOMPARE' )):
				echo do_shortcode('[yith_compare_button]');
			endif;
		endif;
		
		if( emallshop_get_option('show-quick-view-button', 1) ==1 ): ?>
			<div class="quickview-button">
				<a class="quickview" href="#" data-product_id="<?php echo $post->ID; ?>"><?php esc_html_e('Quick View','emallshop');?></a>
			</div><?php 
		endif;
	}
}

/*  Product action buttons
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_show_product_loop_sale_flash' ) ) {
	function emallshop_show_product_loop_sale_flash() {
		global $post, $product;
		if((!is_product() && !emallshop_get_option('show-product-highlight-labels', 1) ) || (is_product() && !emallshop_get_option('show-single-product-highlight-labels', 1))) return; 
		
		if( emallshop_get_option('show-product-highlight-labels', 1) ==1):?>
			<div class="product-highlight">

				<?php if ( (!is_product() && emallshop_get_option('show-outofstock-product-highlight-label', 1))  || (is_product() && emallshop_get_option('show-single-product-highlight-label-outofstock', 1)) ) :
				
					$outofstock_label	= emallshop_get_option('outofstock-highlight-label-text', esc_html__('Out Of Stock','emallshop')) ;
					$availability = $product->get_availability();
					if ( $availability['availability'] == 'Out of stock') :
						echo apply_filters( 'woocommerce_stock_html', '<div class="' . esc_attr( $availability['class'] ) . '"><span>' . esc_attr($outofstock_label). '</span></div>', $availability['availability'] );
					endif;
				endif;?>
				
				<?php if ( (!is_product() && emallshop_get_option('show-new-product-highlight-label', 1))  || (is_product() && emallshop_get_option('show-single-product-highlight-label-new', 1))) :
				
					$postdate 		= get_the_time( 'Y-m-d' );			// Post date
					$postdatestamp 	= strtotime( $postdate );			// Timestamped post date
					$newness 		= emallshop_get_option('product-newness-days', 30); 	// Newness in days
					$newness_label	= emallshop_get_option('new-highlight-label-text',esc_html__('New','emallshop')) ;

					if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) { // If the product was published within the newness time frame display the new badge						
						echo apply_filters( 'woocommerce_sale_flash','<div class="new"><span>' . esc_attr($newness_label). '</span></div>',$post, $product );
					}
					
				endif;?>
				
				<?php if ( (!is_product() && emallshop_get_option('show-featured-product-highlight-label', 1) && $product->is_featured())  || (is_product() && emallshop_get_option('show-single-product-highlight-label-featured', 1) && $product->is_featured()) ) :
				
					$featured_label	= emallshop_get_option('featured-highlight-label-text', esc_html__('Featured','emallshop'));
					echo apply_filters( 'woocommerce_sale_flash','<div class="featured"><span>' . esc_attr($featured_label). '</span></div>',$post, $product );
					
				endif;?>
				
				<?php if ( (!is_product() && emallshop_get_option('show-sale-product-highlight-label', 1) && $product->is_on_sale())  || (is_product() && emallshop_get_option('show-single-product-highlight-label-sale', 1) && $product->is_on_sale()) ) :
					
					if( emallshop_get_option('sale-label-percentages-text','percentages')=='percentages'):
				
						$sale_percentage_label	= emallshop_get_option('sale-highlight-percentages-label-text', esc_html__('Off','emallshop'));
						if ( ! $product->is_in_stock() ) return;
						$sale_price = get_post_meta( $product->id, '_price', true);
						$regular_price = get_post_meta( $product->id, '_regular_price', true);
						
						if ($regular_price == ""){ //then this is a variable product
							$available_variations = @$product->get_available_variations();
							$regular_prices=$sale_prices=array();
							foreach($available_variations as $available_variation){
								$variation_id=$available_variation['variation_id']; 							 
								$variable_product1= new WC_Product_Variation( $variation_id );
								if($variable_product1 ->sale_price!=""){
									$regular_prices[] = $variable_product1 ->regular_price;
									$sale_prices[] = $variable_product1 ->sale_price;
								}
							}
							$regular_price=max($regular_prices);
							$sale_price=min($sale_prices);
						}
						
						$sale = ceil(( ($regular_price - $sale_price) / $regular_price ) * 100);
					
						if ( !empty( $regular_price ) && !empty( $sale_price ) && $regular_price > $sale_price ) :
							
							echo apply_filters( 'woocommerce_sale_flash', '<div class="onsale"><span>' .$sale.'% '. esc_attr($sale_percentage_label) . '</span></div>', $post, $product );	
							
						endif;
					else:
						$sale_label	= emallshop_get_option('sale-highlight-label-text', esc_html__('Sale','emallshop'));
						echo apply_filters( 'woocommerce_sale_flash', '<div class="onsale"><span>'. esc_attr($sale_label).'</span></div>', $post, $product );
					endif;
					
				endif; ?>
			
			</div>
		<?php endif;
	}
}

/*  Get Product's ratting
/* --------------------------------------------------------------------- */
function emallshop_product_rating_html( $rating = null ) {
	global $product;
	if(!emallshop_get_option('show-product-rating', 1)) return; 
	$rating_html = '';
	
	if ( ! is_numeric( $rating ) ) {
        $rating = $product->get_average_rating();
		$review_count = $product->get_review_count();
    }

    $rating_html  = '<div class="product-rating"> <div class="rating-content"> ';
	$rating_html .= '<div class="star-rating" title="' . $rating . '">';

    $rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . esc_html__( 'out of 5', 'emallshop' ) . '</span>';
	
	$rating_html .= ' </div>';
	$rating_html .='<span class="product-rating-count">('.$review_count.')</span>';
    $rating_html .= '</div></div>';

    echo $rating_html;

}

/*  Product short description
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_product_short_description' ) ) {
	function emallshop_product_short_description() {
		global $post;
		if(!emallshop_get_option('show-short-description', 1)) return; ?>
		<div class="short-description">
			<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt );?>
		</div><?php 
	}
}

/*  Change variation price 
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_variation_price_format' ) ):
	function emallshop_variation_price_format( $price, $product ) {
		// Main Price
		$prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
		$price = $prices[0] !== $prices[1] ?  wc_price( $prices[0] ) : wc_price( $prices[0] );
		// Sale Price
		$prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
		sort( $prices );
		//$saleprice = $prices[0] !== $prices[1] ? sprintf( esc_html__( 'From','%1$s', 'emallshop' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
		$saleprice = $prices[0] !== $prices[1] ?  wc_price( $prices[0] ) : wc_price( $prices[0] );
		if ( $price !== $saleprice ) {
		$price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
		}
		return $price;
	}
endif;
add_filter( 'woocommerce_variable_sale_price_html', 'emallshop_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'emallshop_variation_price_format', 10, 2 );

/* 	Quick view product
/* --------------------------------------------------------------------- */
add_action('wp_ajax_emallshop_product_quickview', 'emallshop_product_quickview');
add_action('wp_ajax_nopriv_emallshop_product_quickview', 'emallshop_product_quickview');
function emallshop_product_quickview() {
	global $post, $product;
	$post= get_post($_GET['pid']);
	
	$product = wc_get_product( $post->ID );
	
	if ( post_password_required() ) {
        echo get_the_password_form();
        die();
        return;
    }?>
	<div class="woocommerce">
		<div class="quickview-wrap quickview-wrap-<?php echo esc_attr($post->ID); ?> product">
			<div class="single-product-entry">
				<div class="ccol-sm-5 col-md-5">
					<div class="single-product-image">
					<?php do_action( 'woocommerce_before_single_product_summary' );	?>
					</div>
				</div>

				<div class="col-md-7 col-sm-7">
					<div class="summary entry-summary">
					<?php do_action( 'woocommerce_single_product_summary' ); ?>
					</div>
				</div><!-- .summary -->
			</div>
		</div>
	</div>
	<?php die();
}

/*  Product attributes
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_product_attr' ) ) {
	function emallshop_output_product_attr() {
		global $product;
		$has_row    = false;
		$attributes = $product->product_attributes;
		
		if ( empty($attributes) || !emallshop_get_option('show-product-variation', 1) ) {
			return;
		}
		
		ob_start();	?>	
		
		<div class="product-extra-info">
			<div class="product-attrs">
				<?php foreach ( $attributes as $attribute ) :
					if ( empty( $attribute['is_visible'] ) || empty( $attribute['is_variation'] )  && ! taxonomy_exists( $attribute['name'] ) ) {
						continue;
					} else {
						$has_row = true;
					}?>
					
					<div class="product-attribute">
					<?php echo wc_attribute_label( $attribute['name'] )." : "; 
					
					if ( $attribute['is_taxonomy'] ) {

						$values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
						$lastElement = end($values);
						foreach($values as $value){?>
							<span class="attr-value">
								<?php echo esc_attr($value);?>
							</span><?php 
							if($value != $lastElement) {
								echo ' - ';
							}
						}
					}else{
						// Convert pipes to commas and display values
						$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
						$lastElement = end($values);
						foreach($values as $value){?>
							<span class="attr-value">
								<?php echo esc_attr($value);?>
							</span><?php 
							if($value != $lastElement) {
								echo ' - ';
							}
						}
					}?>
					</div>								
				<?php endforeach;?>
			</div>
		</div>
		<?php
		if ( $has_row ) {
			echo ob_get_clean();
		} else {
			ob_end_clean();
		}
	}
}

/*  Product attributes
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_related_products' ) ) {
	function emallshop_related_products() {
		woocommerce_get_template( 'single-product/related.php' );
	}
}

/* 	Get Product info added in cart
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_get_productinfo' ) ) {
	function emallshop_get_productinfo() {
		global $product, $woocommerce_loop, $road_opt;
		
		$productid = intval( $_POST['data']['pid'] );
		$product = get_product( $productid );
		$quantity = intval( $_POST['data']['quantity'] );
		?>
		<h3><?php esc_html_e('Product is added to cart', 'emallshop');?></h3>
		<div class="product-wrapper">
			<div class="product-image">
				<?php echo wp_kses($product->get_image('shop_thumbnail'), array(
					'img'=>array(
						'src'=>array(),
						'height'=>array(),
						'width'=>array(),
						'class'=>array(),
						'alt'=>array(),
					)
				));?>
			</div>
			<div class="product-info">
				<h4><?php echo esc_html($product->get_title());?></h4>
				<p class="price"><?php echo ''.$product->get_price_html(); ?></p>
			</div>
		</div>
		<div class="buttons">
			<a class="button" href="<?php echo get_permalink( wc_get_page_id( 'cart' ) );?>"><?php esc_html_e('View Cart', 'emallshop');?></a>
		</div>
		<?php
		die();
	}
}
add_action( 'wp_ajax_get_productinfo', 'emallshop_get_productinfo' );
add_action( 'wp_ajax_nopriv_get_productinfo', 'emallshop_get_productinfo' );

/*  Category title and items
/* --------------------------------------------------------------------- */
if( ! function_exists( 'woocommerce_template_loop_category_title' ) ) {
	function woocommerce_template_loop_category_title( $category ) { ?>
		<div class="category-content">
			<h3>
				<?php echo esc_attr($category->name);	?>
			</h3>
			<?php if ( $category->count > 0 )
				echo apply_filters( 'woocommerce_subcategory_count_html', sprintf( '<span class="category-items" />%s %s</span>', $category->count, esc_html__( 'Items', 'emallshop' ) ), $category );
			?>
		</div>
	<?php }
}

/*  Change number of related products output
/* --------------------------------------------------------------------- */
add_filter( 'woocommerce_output_related_products_args', 'emallshop_related_products_args' );
if( ! function_exists( 'emallshop_related_products_args' ) ) { 
	function emallshop_related_products_args( $args ) {
		$args['posts_per_page'] = 6; // 4 related products
		return $args;
	}
}

/* 	Load more product
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_load_more_products' ) ) { 
	function emallshop_load_more_products() {
		$load_more_options=array();
		
		$load_more_options['type']= emallshop_get_option('product-pagination-style', 'infinity_scroll');
		
		$load_more_options['use_mobile']=false;
		$load_more_options['mobile_type']='more_button';
		$load_more_options['mobile_width']=767;
		
		$load_more_text='<i class="fa  fa-arrow-circle-o-down"></i> ';
		$load_more_text.= emallshop_get_option('load-more-button-text','Load More');
		$load_more_options['lazy_load']= (emallshop_get_option('enable-lazy-load', 0)==1) ? true : false ;
		
		$load_more_options['lazy_load_m']=false;
		
		$load_more_options['LLanimation']=( emallshop_get_option('enable-lazy-load', 0)==1) ? emallshop_get_option('load-animation-style', 'fadeInUp') : '' ;
		
		$loading_image= EMALLSHOP_ADMIN_URI.'/images/ajax-'.emallshop_get_option('pagination-loading-image','loader').'.gif';
		
		$load_more_options['loading']=esc_html__('Loading...','emallshop');
		$load_more_options['loading_class'] = '';
		$load_more_options['end_text'] = esc_html__('No more product','emallshop');
		
		$load_more_options['products_selector'] = 'ul.products.is_shop';
		$load_more_options['item_selector'] = 'li.product';
		$load_more_options['pagination_selector'] = '.woocommerce-pagination';
		$load_more_options['next_page_selector'] = '.woocommerce-pagination a.next';
			
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
}

/* 	add product detail next and prev products
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_single_product_pagination' ) ) { 
	function emallshop_single_product_pagination(){
		if(!emallshop_get_option('show-product-navigation', 1)) return; 
	
		if ( is_singular('product') ) {
			global $post;
			// get categories
			$terms = wp_get_post_terms( $post->ID, 'product_cat' );
			foreach ( $terms as $term ) $cats_array[] = $term->term_id;
			if(empty($cats_array)):
				$cats_array[] =-1;
			endif;
			// get all posts in current categories
			$query_args = array('posts_per_page' => -1, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $cats_array
				)));
			$r = new WP_Query($query_args);
			// show next and prev only if we have 3 or more
			if ($r->post_count > 2) {
				$prev_product_id = -1;
				$next_product_id = -1;
				$found_product = false;
				$i = 0;
				$first_product_index = 0;
				$current_product_index = $i;
				$current_product_id = get_the_ID();
				if ($r->have_posts()) {
					while ($r->have_posts()) {
						$r->the_post();
						$current_id = get_the_ID();
						if ($current_id == $current_product_id) {
							$found_product = true;
							$current_product_index = $i;
						}
						$is_first = ($current_product_index == $first_product_index);
						if ($is_first) {
							$prev_product_id = get_the_ID(); // if product is first then 'prev' = last product
						} else {
							if (!$found_product && $current_id != $current_product_id) {
								$prev_product_id = get_the_ID();
							}
						}
						if ($i == 0) { // if product is last then 'next' = first product
							$next_product_id = get_the_ID();
						}
						if ($found_product && $i == $current_product_index + 1) {
							$next_product_id = get_the_ID();
						}
						$i++;
					}?>
					<div class="product-next-previous">
					<?php
					if ($prev_product_id != -1) { emallshop_ShowLinkToProduct($prev_product_id, $cats_array, "prev"); }
					if ($next_product_id != -1) { emallshop_ShowLinkToProduct($next_product_id, $cats_array, "next"); }?>
					</div><?php 
				}
				wp_reset_postdata();
			}
		}
	}
}

if( ! function_exists( 'emallshop_ShowLinkToProduct' ) ) { 
	function emallshop_ShowLinkToProduct($post_id, $categories_as_array, $label) {
		// get post according post id
		$query_args = array( 'post__in' => array($post_id), 'posts_per_page' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => $categories_as_array
			)));
		$r_single = new WP_Query($query_args);
		if ($r_single->have_posts()) {
			$r_single->the_post();
			global $product;
		?>
			<div class="product-<?php echo esc_attr($label); ?>">
				<a href="<?php the_permalink() ?>">							
				<span class="product-navbar">
					<?php if($label=="next"):
						if(is_rtl()):?>
							<i class="fa fa-chevron-left"></i>
						<?php else:?>
							<i class="fa fa-chevron-right"></i>
						<?php endif;?>
					<?php else:
						if(is_rtl()):?>
							<i class="fa fa-chevron-right"></i>
						<?php else:?>
							<i class="fa fa-chevron-left"></i>
						<?php endif;?>
					<?php endif; ?>
				</span>
				<div class="product-<?php echo esc_attr($label); ?>-popup">
					<div class="product-thumb">
						 <?php if (has_post_thumbnail()){						
							the_post_thumbnail('shop_thumbnail');
						}?>
					</div>
					<div class="product-title-price">
						<span class="ptitle"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></span>
						<?php echo $product->get_price_html(); ?>
					</div>
				</div>
				</a>
			</div>
		<?php
			wp_reset_postdata();
		}
	}
}

/*  Single Product Availability
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_single_availability' ) ) {
	function emallshop_template_single_availability() {
		global $product;?>
		
		<span class="availability <?php echo $product->is_in_stock() ? 'instock' : ''; ?>"><?php echo $product->is_in_stock() ? esc_html__('In  Stock','emallshop') : ''; ?></span>
		
	<?php }
}

/*  Sale Product Countdown
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_sale_product_countdown' ) ) {
	function emallshop_sale_product_countdown() {
		global $product;
		
		if(is_single() || !emallshop_get_option('show-single-product-countdown', 1)) return; 
		
		if ( $product->is_on_sale() ) : 
			$time_sale = get_post_meta( $product->id, '_sale_price_dates_to', true );
		endif;
		
		/* variable product */
		if( $product->has_child() && $product->is_on_sale()){
			$vsale_end = array();
			
			$pvariables = $product->get_children();
			foreach($pvariables as $pvariable){
				$vsale_end[] = (int)get_post_meta( $pvariable, '_sale_price_dates_to', true );
			}			
			/* get the latest time */
			$time_sale = max($vsale_end);				
		}?>
		
		<?php if( $product->is_on_sale() && $time_sale ) :?>
			<div class="product-countdown">			
				<div class="countdown" data-year="<?php echo date('Y',$time_sale);?>" data-month="<?php echo date('m',$time_sale)-1;?>" data-day="<?php echo date('d',$time_sale);?>" data-hours="<?php echo date('H',$time_sale);?>" data-minutes="<?php echo date('i',$time_sale);?>" data-seconds="<?php echo date('s',$time_sale);?>"></div>
			</div>
		<?php endif;?>	
		
	<?php }
}

/*  Single Product Brand
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_single_brand' ) ) {
	function emallshop_template_single_brand() {
		global $post, $product;
		
		if(taxonomy_exists('product_brand') && in_array('brand', emallshop_get_option('show-specific-productmeta', array('brand','sku','cats','tags')))):
			$brand_count = sizeof( get_the_terms( $post->ID, 'product_brand' ) );
			echo get_the_term_list( $post->ID, 'product_brand', ' <span class="brand_in">' . _n( 'Brand:', 'Brands:', $brand_count, 'emallshop' ) . ' ', ' , ', ' </span>' );
		endif;
	}
}

/*  Define image sizes
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_woocommerce_image_dimensions' ) ) {
	function emallshop_woocommerce_image_dimensions() {
		$catalog = array(
			'width' 	=> '300',	// px
			'height'	=> '351',	// px
			'crop'		=> 1 		// true
		);

		$single = array(
			'width' 	=> '620',	// px
			'height'	=> '726',	// px
			'crop'		=> 1 		// true
		);

		$thumbnail = array(
			'width' 	=> '120',	// px
			'height'	=> '140',	// px
			'crop'		=> 0 		// false
		);

		// Image sizes
		update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
		update_option( 'shop_single_image_size', $single ); 		// Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	}
}
/*
 * Hook in on activation
 */
add_action( 'init', 'emallshop_woocommerce_image_dimensions', 1 );