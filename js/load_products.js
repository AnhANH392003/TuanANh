var lmp_update_state;
var ajax_pagination = ajax_pagination || {};
(function ($){
    $(document).ready( function () {
		if ( ajax_pagination.length === 0 || typeof ajax_pagination.the_lmp_js_data === 'undefined' ) {
			return;
		}
		$.each( ajax_pagination.the_lmp_js_data, function ( id, the_lmp_js_data ) {
			var lmp_is_loading = false,	lmp_loading_style;
			var $container = $('.masonry-grid');
			if ( $( the_lmp_js_data.products ).length > 0 ) {
				$( the_lmp_js_data.products ).after( $( the_lmp_js_data.load_more ) );
				current_style();
				$(window).resize( function () {
					current_style();
				});
				$(window).scroll ( function () {
					if ( lmp_loading_style == 'infinity_scroll' ) {
						var products_bottom = $( the_lmp_js_data.products ).offset().top + $( the_lmp_js_data.products ).height() - the_lmp_js_data.buffer;
						var bottom_position = $(window).scrollTop() + $(window).height();
						if ( products_bottom < bottom_position && !lmp_is_loading ) {
							load_next_page();
						}
					}
				});
				$(document).on( 'click', '.lmp_button', function (event) {
					event.preventDefault();
					$( '.lmp_load_more_button' ).hide();
					load_next_page();
				});
				if ( ! the_lmp_js_data.is_AAPF ) {
					$(document).on( 'click', the_lmp_js_data.pagination+' a', function (event) {
						event.preventDefault();
						load_next_page( true, $(this).attr('href') );
					});
				}
			}
			function load_next_page( replace, user_next_page ) {
				if ( typeof( replace ) == 'undefined' ) {
					user_next_page = false;
				}
				if ( typeof( user_next_page ) == 'undefined' ) {
					user_next_page = false;
				}
				var $next_page = $( the_lmp_js_data.next_page );
				if ( $next_page.length > 0 || user_next_page !== false ) {
					start_ajax_loading()
					var next_page;
					if( user_next_page !== false ) {
						next_page = user_next_page;
					} else {
						next_page = $next_page.attr('href');
					}
					$.get( next_page, function( data ) {
						var $data = $(data);
						if( ( the_lmp_js_data.lazy_load_m && $(window).width() <= the_lmp_js_data.mobile_width ) || ( the_lmp_js_data.lazy_load && $(window).width() > the_lmp_js_data.mobile_width ) ) {
							$data.find(the_lmp_js_data.item+', .berocket_lgv_additional_data').find( 'img' ).each( function ( i, o ) {
								$(o).attr( 'data-src', $(o).attr( 'src' ) ).removeAttr( 'src' );
							});
							$data.find(the_lmp_js_data.item+', .berocket_lgv_additional_data').addClass('lazy');
						}
						var $products = $data.find( the_lmp_js_data.products ).html();
						if ( replace ) {
							$( the_lmp_js_data.products ).html( $products );
							$container.imagesLoaded( function() { // When images are loaded, fire masonry again.
								$container.masonry('reloadItems').masonry();
								$('.portfolioContainer').isotope( 'reloadItems' ).isotope();
							});
							ProductGalleryCarousel1();
						} else {
							$( the_lmp_js_data.products ).append( $products );
							$container.imagesLoaded( function() { // When images are loaded, fire masonry again.
								$container.masonry('reloadItems').masonry();
								$('.portfolioContainer').isotope( 'reloadItems' ).isotope();
							});
							ProductGalleryCarousel1();
						}
						if( (the_lmp_js_data.lazy_load_m && $(window).width() <= the_lmp_js_data.mobile_width ) || (the_lmp_js_data.lazy_load && $(window).width() > the_lmp_js_data.mobile_width ) ) {
							$( the_lmp_js_data.products+' .lazy' ).find( 'img' ).lazyLoadXT();
							$( the_lmp_js_data.products ).find('.lazy').on( 'lazyshow', function () {
								$(this).removeClass('lazy').addClass('animated').addClass(the_lmp_js_data.LLanimation);
								if( ! $(this).is('.berocket_lgv_additional_data') ) {
									$(this).next( '.berocket_lgv_additional_data' ).removeClass('lazy').addClass('animated').addClass(the_lmp_js_data.LLanimation);
								}
							});
						}
						var $pagination = $data.find( the_lmp_js_data.pagination );
						$( the_lmp_js_data.pagination ).html( $pagination.html() );
						current_style();
						end_ajax_loading();
					});
				}
			}
			function start_ajax_loading() {
				lmp_is_loading = true;
				$( the_lmp_js_data.products ).after( $( the_lmp_js_data.load_image ) );
			}
			function end_ajax_loading() {
				$( the_lmp_js_data.load_img_class ).remove();
				lmp_is_loading = false;
				var $next_page = $( the_lmp_js_data.next_page );
				if( ( lmp_loading_style == 'infinity_scroll' || lmp_loading_style == 'more_button' ) && $next_page.length <= 0 ) {
					$( the_lmp_js_data.products ).append( $( the_lmp_js_data.end_text ) );
				}
			}
			function current_style() {
				if ( the_lmp_js_data.use_mobile && $(window).width() <= the_lmp_js_data.mobile_width ) {
					set_style( the_lmp_js_data.mobile_type );
				} else {
					set_style( the_lmp_js_data.type );
				}
			}
			function set_style( style ) {
				var $next_page = $( the_lmp_js_data.next_page );
				$( the_lmp_js_data.pagination ).hide();
				$( '.lmp_load_more_button' ).hide();
				if ( style == 'more_button' ) {
					if ( $next_page.length > 0 ) {
						$( '.lmp_load_more_button' ).show();
					} else {
						setTimeout( test_next_page, 4000 );
					}
				} else if ( style == 'pagination' ) {
					$( the_lmp_js_data.pagination ).show();
				}
				lmp_loading_style = style;
			}
			function test_next_page() {
				var $next_page = $( the_lmp_js_data.next_page );
				if ( $next_page.length > 0 ) {
					current_style();
				} else {
					setTimeout( test_next_page, 4000 );
				}
			}
			lmp_update_state = function() {
				current_style();
			}
		});
		
		$(document).on('ready yith-wcan-ajax-filtered', ProductGalleryCarousel1);
		
		function ProductGalleryCarousel1(e){ 
			var pagination = $('ul.products').data('pagination'),
			navigation = $('ul.products').data('navigation');
			var emallshop_rtl = false;
			if($("body").hasClass("rtl")){
				emallshop_rtl =  true;
			}
			
			if(pagination==1 || navigation==1){
				$('.product-items li.product').each(function(){ 
					var productGalleryCarousel = $(this).find('.product_image_gallery');
					var interval; 
					if (!productGalleryCarousel.hasClass("owl-loaded")) {
						productGalleryCarousel.owlCarousel({
							items:				1,
							loop:				true,
							autoplayTimeout:	1500,
							rtl:				emallshop_rtl,
							smartSpeed:			450,
							mouseDrag:			false,
							touchDrag:			false,
							//nav:				true,
							navText: 			['',''],
							dots: 				( pagination ==1 ) ? true : false,
						});
						
						$(this).hover(function(){
							productGalleryCarousel.owlCarousel('invalidate', 'all').owlCarousel('refresh');
						});

						if(pagination ==1){
							$(this).hover(
								function(){
									interval = setInterval(function() {
										productGalleryCarousel.trigger('next.owl.carousel');
									}, 1500);								
								},
								function(){
									clearInterval(interval);
								}
							);
						}	
						
						if(navigation ==1){
							var nextOwl = $(this).find('.product-slider-controls .owl-next');
							var prevOwl = $(this).find('.product-slider-controls .owl-prev');
							
							prevOwl.click(function(){
								productGalleryCarousel.trigger('prev.owl.carousel');
							});								
							nextOwl.click(function(){
								productGalleryCarousel.trigger('next.owl.carousel');
							});								
						}
					}
				});				
			}
		}
    });
})(jQuery);
function lmp_execute_func ( func ) {
    if( the_lmp_js_data.javascript != 'undefined'
        && the_lmp_js_data.javascript != null
        && typeof func != 'undefined' 
        && func.length > 0 ) {
        eval( func );
    }
}