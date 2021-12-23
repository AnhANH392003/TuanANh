/* global screenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */
var $ = jQuery.noConflict();
var emallshopOwlArg = emallshopOwlArg || {};

//*******************************************************************
//* Init isotope portfolio
//********************************************************************/
// Isotope Portfolio
function emallshopIsotopePortfolio(emallshop_rtl){
	var $container = $('.portfolioContainer');
	var $filter = $('.portfolioFilter');
	var $layoutMode="masonry";

	if($container.hasClass('normal-grid') ){
		$layoutMode="fitRows"
	}

	$(window).load(function () {
		// Initialize Isotope
		
		if(emallshop_rtl){			
			$container.isotope({
				itemSelector: '.portfolio-item',
				isOriginLeft: false,
				layoutMode: $layoutMode,
			});
		}
		else{
			$container.isotope({
				itemSelector: '.portfolio-item',
				layoutMode: $layoutMode,
			});
		}
		$('.portfolioFilter a').click(function () {
			var selector = $(this).attr('data-filter');
			$container.isotope({ filter: selector });
			return false;
		});
		$filter.find('a').click(function () {
			var selector = $(this).attr('data-filter');
			$filter.find('a').removeClass('current');
			$(this).addClass('current');
		});
	});
}

//*******************************************************************
//* List/Grid view toggle
//*******************************************************************/
function emallshopGridListView(){
	
	$(".gridlist-toggle a").click(function(){
		var className = $(this).attr('class').split(' ');
		jQuery('.gridlist-toggle a').removeClass('active');
		$(this).addClass('active');
		jQuery.cookie('gridcookie',className[0] +" "+className[1], { path: '/' });
		jQuery('ul.products.is_shop').fadeOut(300, function() {
			
			jQuery(this).removeClass('grid grid-view').removeClass('grid-expand grid-view').removeClass('list list-view').removeClass('list-thin list-view').addClass(className[0] +" "+className[1]).fadeIn(300);
			
		});
		return false;
	});	
	
	if (jQuery.cookie('gridcookie')) {		
		jQuery('ul.products.is_shop').removeClass('grid-expand grid-view').removeClass('list list-view').removeClass('list-thin list-view');
        jQuery('ul.products.is_shop').addClass(jQuery.cookie('gridcookie'));
		var className = jQuery.cookie('gridcookie').split(' ');
		jQuery('.gridlist-toggle a').removeClass('active');
		jQuery('.gridlist-toggle'+ ' .'+ className[0]).addClass('active');
    }	
	
	jQuery('.gridlist-toggle a').click(function(event) {
	    event.preventDefault();
	});	
}

//*******************************************************************
//* Product quickview
//*******************************************************************/
function emallshopQuickview(emallshop_rtl){
	$(document).on('click', '.quickview', function(e) {
		 e.preventDefault();
		 var pid = $(this).attr('data-product_id');
		 $.fancybox({
			href : myAjax.ajaxurl + '?action=emallshop_product_quickview&pid=' + pid,
			type : 'ajax',
			helpers : {
				overlay: {
					locked: true

				}
			},
			error    : '<p class="fancybox-error"></p>',
			autoSize: false,
			autoWidth : false,
			afterShow : function() {
				$('.thumbnails-carousel').owlCarousel({
					loop: true,
					responsiveClass: true,
					responsiveRefreshRate : 10,
					rtl : emallshop_rtl,
					nav: true,
					navText: ['',''],
					dots:false,
					items: 3,					
					
				});
				
				$('.thumbnails-carousel img').click(function(){
					var large = $(this).attr('data-large');
					setActive($(this));
					$('#product-image img').fadeOut(300, changeImg(large, $('#product-image img')));
					$('#product-image a.fullscreen-button').attr('href', large);
				});
				
				$("#inner-zoom").elevateZoom({ scrollZoom : true, zoomType: "inner", cursor: "crosshair"});
				if(emallshop_rtl){
					$("#outer-zoom").elevateZoom({ scrollZoom : true, zoomWindowFadeIn: 500, zoomWindowFadeOut: 500, lensFadeIn: 500, lensFadeOut: 500, zoomWindowPosition: 11 });
				}else{
					$("#outer-zoom").elevateZoom({ scrollZoom : true, lensFadeIn: 500, lensFadeOut: 500});
				}
				$("#lens-zoom").elevateZoom({ zoomType: "lens", lensShape : "round", lensSize: 200 });
			},
		
		});
		return false;
	});
}

//*******************************************************************
//* product search category exist.
//*******************************************************************/
function emallshopDropdownCategory(){
	$(document).ready(function(){
        var $dropdownCategory = $('.category-item'), $searchParam = $('#search-param');
        $dropdownCategory.on('click', function(){
            var $this = $(this);
            if( '' === $this.data('value') ){
                $searchParam.attr('name', 'post_type');
                $searchParam.val('product');
            }else{
                $searchParam.attr('name', 'product_cat');
                $searchParam.val( $this.data('value') );
				$this.addClass('selected');
            }
            $('#category-filter-value').text( $this.text() );
            $('#category-filter-value').dropdown('toggle');
            adjustSearchFieldWidth();
            return false;
        });

        adjustSearchFieldWidth();
    });

    $(window).resize(function() {
        adjustSearchFieldWidth();
    });

    function adjustSearchFieldWidth(){
        var widthSearchControlGroup = $('.search-control-group').width();
        var widthSearchBarControls = $('.search-bar-controls').width();
        var widthSearchField = widthSearchControlGroup - (widthSearchBarControls + 30 );
        $('.search-field-area .search-field').width(widthSearchField);
    }
}
//*******************************************************************
//* Ajax Count Wishlist Product
//*******************************************************************/
function emallshopWishlistCount(){
	var emallshop_ajax_wishlist_count = function() {
		$.ajax({
			type: 'POST',
			url: yith_wcwl_l10n.ajax_url,
			data      : {
				action: 'emallshop_ajax_wishlist_count'
			},
			beforeSend: function () {
			},
			complete  : function () {
			},			
			success   : function (data) {
				//console.log( data );
				$("samp.wishlist-count").html(data);
			},
		});
	};
	$('body').on( 'added_to_wishlist removed_from_wishlist', emallshop_ajax_wishlist_count );
}


//*******************************************************************
//* Ajax Count Compare Product
//*******************************************************************/
function emallshopCompareCount(){
	$('body').on( 'yith_woocompare_open_popup woocompare_open_popup_mod', function () {				
		$.ajax({
			type: 'post',
			url: myAjax.ajaxurl,
			data      : {
				action: 'emallshop_ajax_compare_count'
			},
			beforeSend: function () { 
			},
			complete  : function () { 
			},	
			success: function (response) { 
				$("samp.compare-count").html(response);				
			},
			error: function(errorThrown){
				//alert(errorThrown);
		   } 
		});
	});

	$(window).on('yith_woocompare_product_removed', function () {
		$('body').trigger('woocompare_open_popup_mod');
	});
	
	//Remove product in compare product widget
	$('.yith-woocompare-widget').on('click', 'li a.remove, a.clear-all', function (e) {

		e.preventDefault();
		var product_id = $(this).data('product_id');
		
		$.ajax({
			type: 'post',
			url: myAjax.ajaxurl,
			data      : {
				action: 'emallshop_ajax_compare_count',
				id:product_id
			},
			beforeSend: function () { 
			},
			complete  : function () { 
			},	
			success: function (response) { 
				$("label.compare-count").html(response);				
			},
			error: function(errorThrown){
				//alert(errorThrown);
		   } 
		});

	});
}
//*******************************************************************
//* Product sale countdown
//*******************************************************************/
function emallshopProductCountdown(){
	$('.countdown').each(function(){
		var $this = $(this),
			endDate = $this.data(),
			until = new Date(
				endDate.year,
				endDate.month || 0,
				endDate.day || 1,
				endDate.hours || 0,
				endDate.minutes || 0,
				endDate.seconds || 0
			);
		// initialize
		$this.countdown({
			until : until,
			format : 'dHMS',
			labels : ['years', 'month', 'weeks', 'days', 'hours', 'min', 'sec']
		});
	});
}
//*******************************************************************
//* owl carousel slider of products
//*******************************************************************/
function productsCarousel(emallshop_rtl) {
	if ( emallshopOwlArg.length === 0 || typeof emallshopOwlArg.productsCarousel === 'undefined' ) {
		return;
	}
	$.each( emallshopOwlArg.productsCarousel, function ( id, productsCarousel ) {
		var autoplay = ( productsCarousel.autoplay === 'true' ) ? true : false;
		var navigation = ( productsCarousel.navigation === 'true' ) ? true : false;
		var loop = ( productsCarousel.loop === 'true' ) ? true : false;
		var dots = ( productsCarousel.dots === 'true' ) ? true : false;
		var rp_desktop = ( productsCarousel.rp_desktop > 0 ) ? productsCarousel.rp_desktop : 5;
		var rp_small_desktop = ( productsCarousel.rp_small_desktop > 0 ) ? productsCarousel.rp_small_desktop : 4;
		var rp_tablet = ( productsCarousel.rp_tablet > 0 ) ? productsCarousel.rp_tablet : 3;
		var rp_mobile = ( productsCarousel.rp_mobile > 0 ) ? productsCarousel.rp_mobile : 2;
		var rp_small_mobile = ( productsCarousel.rp_small_mobile > 0 ) ? productsCarousel.rp_small_mobile : 1;
		
		var numItems = $( document.getElementById( id ) ).children('.owl-item').length;
		
		$( document.getElementById( id ) ).find( '.product-carousel').owlCarousel({				
			autoplay: 			autoplay,
			autoplayHoverPause: true,
			lazyLoad:			true,
			rtl : 				emallshop_rtl,
			loop: 				( numItems >= rp_desktop && loop) ? true : false,
			rewind: 			true,
			smartSpeed:			850,
			nav: 				navigation,
			navText: 			['',''],
			dots: 				dots,
			responsive:{
				0:{
					items: rp_small_mobile
				},
				445:{
					items: rp_mobile
				},
				621:{
					items: rp_tablet
				},
				992:{
					items : rp_small_desktop
				},
				1199:{
					items : rp_desktop
				},
			}
		});			

	} );
	$( '.owl-carousel').addClass('owl-theme');
}
//*******************************************************************
//* owl carousel slider of products brands
//*******************************************************************/
function productsBrandsCarousel(emallshop_rtl) {
	if ( emallshopOwlArg.length === 0 || typeof emallshopOwlArg.productsBrands === 'undefined' ) {
		return;
	}//console.log(emallshopOwlArg);
	$.each( emallshopOwlArg.productsBrands, function ( id, productsBrands ) {
		var autoplay = ( productsBrands.autoplay === 'true' ) ? true : false;
		var navigation = ( productsBrands.navigation === 'true' ) ? true : false;
		var loop = ( productsBrands.loop === 'true' ) ? true : false;
		var dots = ( productsBrands.dots === 'true' ) ? true : false;

		if( productsBrands.item_columns > 1 ) {
			var items = productsBrands.item_columns;
		}

		$( document.getElementById( id ) ).find( '.brands-carousel').owlCarousel({
			autoPlay:			autoplay,
			loop : 				loop,
			rtl : 				emallshop_rtl,
			nav: 				navigation,
			navText: 			['',''],
			stopOnHover: 		true,
			dots: 				dots,
			smartSpeed:			750,
			responsive:{
				0:{
					items:3
				},
				480:{
					items:4
				},
				768:{
					items:6
				},
				1024:{
					items : items
				},
			}
		});

	} );
}
//*******************************************************************
//* owl carousel slider of product banner
//*******************************************************************/
function productBannerCarousel(emallshop_rtl){
	$('.banner-carousel').owlCarousel({
		autoPlay : true,
		rtl : emallshop_rtl,
		responsive : true,
		pagination : true,
		rewindSpeed : 1000,
		singleItem : true,
	});
}

//*******************************************************************
//* owl carousel slider of product detail page thumbnails
//*******************************************************************/
function emallshopProductThumbnailsCarousel(emallshop_rtl){
	var numItems = $('.thumbnails-carousel').children('.owl-item').length;	
	$('.thumbnails-carousel').owlCarousel({
		loop: ( numItems >= 3 ) ? true : false,
		responsiveClass: true,
		rtl : emallshop_rtl,
		nav: true,
		navText: ['',''],
		dots:false,
		items: 3,
	})
}

//*******************************************************************
//*  post gallery carousel
//*******************************************************************/
function postGalleryCarousel(emallshop_rtl){
	$('.post-slider').owlCarousel({
		autoplay : 	true,
		rtl : 		emallshop_rtl,
		rewind: 	true,
		smartSpeed: 850,
		nav : 		true,
		navText: 	['',''],
		dots: 		true,		
		items: 		1,
	});
	$( '.post-slider').addClass('owl-theme');
}

//*******************************************************************
//*  testimonial carousel
//*******************************************************************/
function testimonialCarousel(emallshop_rtl){
	$('.testimonial-carousel').owlCarousel({
		autoplay:			false,
		rtl : 				emallshop_rtl,
		nav: 				true,
		navText:		 	['',''],
		dots: 				false,
		smartSpeed:			750,
		margin:				15,
		items:				1,
	});
}

//*******************************************************************
//* Category menu toggle
//*******************************************************************/
function categoryMenuToggle(){	
	var windowWidth = $(window).width(); 
	var getVerticalMenuHeight = $('.categories-list').outerHeight();
	//var headerHeight = $('#header').outerHeight();
	if(windowWidth > 1199){
		$('.home.open-categories-menu .header-navigation .category-menu').removeClass('open').find('.categories-list').slideDown();	
	}
	
	$(document).on('click','.category-menu .down-up',function(){	
		$(this).closest('.header-navigation .category-menu.open').find('.categories-list').slideToggle();
		return false;
	});		
	
	$(window).scroll(function() {
		var currentPosition = $(window).scrollTop();
		if(windowWidth > 991){
			if(currentPosition > ((getVerticalMenuHeight * 90)/ 100) ){
				$('.home.open-categories-menu .header-navigation .category-menu').addClass('open').find('.categories-list').slideUp();
			}
			
			if(currentPosition < ((getVerticalMenuHeight * 90)/ 100) ){
				$('.home.open-categories-menu .header-navigation .category-menu').removeClass('open').find('.categories-list').slideDown();
			}
		}				
	});
}

jQuery(document).ready(function($){
	"use strict";
	
	var $style = jQuery('head style').first();
	
	var windowWidth = $(window).width();
	var windowHeight = $(window).height();
	backToTop(); // Back to top arrow
	backToTopEvent(); // Initialize back to top event	

	/*
	* If check is site RTL
	*/		
	$("html[dir='rtl'] body").addClass("rtl");
	var emallshop_rtl = false;
	if($("body").hasClass("rtl")){
		emallshop_rtl =  true;
	}
	
	/*
	* Page Loader
	*/
	jQuery(window).load(function() {
		setTimeout(function(){
			jQuery('body').addClass('loaded');
		}, 1000);
	});
	
	//*******************************************************************
	//* Newsletter Popup.
	//*******************************************************************/
	var obj = {
      init: function() {
        try
        {
          if ($.cookie('popupNewsletter') != 'disable') { 
            this.checkboxEvent();
            setTimeout(function() {
              $('#newsletterPopup').modal('show');
              var date = new Date();
              var minutes = 1;
              date.setTime(date.getTime() + (minutes * 60 * 1000));
              $.cookie('popupNewsletter', 'disable', {expires:date, path:'/'});
            }, 1500);
          }
        }
        catch (err) {} // ignore errors reading cookies
      },
      checkboxEvent: function() {
        $('input[id^="checkBox"]').change(function(){
          if ($(this).is(':checked')) {
            $.cookie('popupNewsletter', 'disable', {expires:1, path:'/'});
          } else {
            $.cookie("popupNewsletter", null, { path: '/' });
          }
        });
      }
    };
	
    $(document).ready(function() {
      obj.init();
    });
		
	//*******************************************************************
	//* Sticky header menu 
	//********************************************************************/
	// get header height (without border)
	var sticky_class='';
	if($( "body" ).hasClass( "sticky-topbar" )){
		sticky_class='topbar';
		var getHeaderHeight = $('.header-topbar').outerHeight();
	}else if($( "body" ).hasClass( "sticky-navigation" )){	
		sticky_class='navigation';
		var getHeaderHeight = $('.header-navigation').outerHeight();
	}else if($( "body" ).hasClass( "sticky-middle" )){
		sticky_class='middle';
		var getHeaderHeight = $('.header-middle').outerHeight();
	}
	
	// Admin bar height
	if(	jQuery( '#wpadminbar' ).length ) {
		var admin_bar_height1 = jQuery( '#wpadminbar' ).height();
	} else {
		var admin_bar_height1 = 0;
	}
	
	// init variable for last scroll position
	var lastScrollPosition = 0;

	// set negative top position to create the animated header effect
	if($( "body" ).hasClass( "sticky-"+ sticky_class )){
		$('.header-'+ sticky_class).css('top', '-' + (getHeaderHeight ) + 'px');
	}

	$(window).scroll(function() {
		var currentScrollPosition = $(window).scrollTop();
		 var width = $(window).width();
		 
		if ($(window).scrollTop() > 2 * ( getHeaderHeight ) && width > 991) {

			$('body').addClass('scrollActive').css('padding-top', getHeaderHeight);
			if($( "body" ).hasClass( "sticky-"+ sticky_class )){
				$('.header-'+ sticky_class).css('top', admin_bar_height1 );	
			}					

			if (currentScrollPosition > lastScrollPosition) {
				if($( "body" ).hasClass( "sticky-"+ sticky_class )){
					$('.header-'+ sticky_class).css('top', '-' + (getHeaderHeight) + 'px');
				}				
			}
			lastScrollPosition = currentScrollPosition;

		} else {
			$('body').removeClass('scrollActive').css('padding-top', 0);
		}
	});	
	
	//*******************************************************************
	//* Back to top button 
	//********************************************************************/
	$(window).scroll(function(){		
		backToTop(); // Back to top				
	});
	function backToTop(){
		
		var button = $('.back-to-top');
		var w_scroll = $(window).scrollTop();
		
		if( w_scroll > 150  && windowWidth > 767 ){
			button.fadeIn(400);	
		}else{
			button.fadeOut(400);	
		}		
	}
	function backToTopEvent(){		
		var button = $('.back-to-top');		
		button.click(function(){			
			$('html,body').animate({scrollTop:0}, 600);			
		});		
	}
	
	//*******************************************************************
	//* Init Masonry grid
	//********************************************************************/
    $( '.blog-posts.masonry-grid' ).masonry( {} );
	
	$( '.dokan-seller-wrap' ).masonry( {} );
	
	
	emallshopIsotopePortfolio(emallshop_rtl);
	
	emallshopDropdownCategory();
	
	emallshopQuickview(emallshop_rtl);
	
	emallshopWishlistCount();	
	
	emallshopCompareCount();

	emallshopGridListView();
	
	
	//*******************************************************************
	//*Change number of products to be viewed on shop page
	//*******************************************************************/
	$( '.show-products-number' ).change( function() {
		$( this ).closest( 'form' ).submit();
	} );
	
	emallshopProductCountdown();	
	
	//*******************************************************************
	//* Change number of products to be viewed on shop page
	//*******************************************************************/
	$( '.show-products-number' ).on( 'change', 'select.showpagers', function() {
		$( this ).closest( 'form' ).submit();
	});
	
	emallshopProductThumbnailsCarousel(emallshop_rtl);
	
	//*******************************************************************
	//* FancyBox 
	//*******************************************************************/
	$(document).ready(function(){
		$(".fancybox").fancybox();
	});
		
	/*
	* Single Product image zoom style
	*/
	$("#inner-zoom").elevateZoom({ scrollZoom : true, zoomType: "inner", cursor: "crosshair"});
	if(emallshop_rtl){
		$("#outer-zoom").elevateZoom({ scrollZoom : true, zoomWindowFadeIn: 500, zoomWindowFadeOut: 500, lensFadeIn: 500, lensFadeOut: 500, zoomWindowPosition: 11 });
	}else{
		$("#outer-zoom").elevateZoom({ scrollZoom : true, lensFadeIn: 500, lensFadeOut: 500 });
	}
	$("#lens-zoom").elevateZoom({ zoomType: "lens", lensShape : "round", lensSize: 200 });
	
	//*******************************************************************
	//* Change product image
	//*******************************************************************/
	$(document).ready(function(){
		$('.thumbnails-carousel img').click(function(){
			var large = $(this).attr('data-large');
			setActive($(this));
			$('#product-image img').fadeOut(300, changeImg(large, $('#product-image img')));
			$('#product-image a.fullscreen-button').attr('href', large);
		});
	});
	
	function changeImg(large, element){
		var element = element;
		var large = large;
		setTimeout(function(){ startF()},300);
		function startF(){
			element.attr('src', large)
			element.attr('data-large', large)
			element.fadeIn(300);
		}
		var ez =   $('#product-image img').data('elevateZoom');
		ez.swaptheimage(large, large); 
		
	}
	
	//*******************************************************************
	//* Add active class in current thumbnail image 
	//*******************************************************************/
	function setActive(el){
		var element = el;
		$('.thumbnails-carousel .item').removeClass('active-item');
		element.parent('.item').addClass('active-item');
	}
	
	//*******************************************************************
	//* FullScreen Button 
	//*******************************************************************/
	$('a.fullscreen-button').click(function(e){
		e.preventDefault();
		var target = $(this).attr('href');
		$('.thumbnails-carousel a.fancybox[href="'+target+'"]').trigger('click');
	});
		
	productsCarousel(emallshop_rtl);	
	
	productBannerCarousel(emallshop_rtl);
	
	productsBrandsCarousel(emallshop_rtl);	
	
	postGalleryCarousel(emallshop_rtl);
	
	testimonialCarousel(emallshop_rtl);
	
	categoryMenuToggle();
	
	//*******************************************************************
	//* Megamenu windows exist
	//*******************************************************************/
	
	// position mega menu correctly
	jQuery.fn.emallshop_position_megamenu = function( variables ) {		

		// top header headnling
		var reference_elem = '';		
		reference_elem = jQuery( this ).parent( '.emallshop-main-menu' );		

		if( jQuery( this ).parent( '.emallshop-main-menu' ).length ) {

			var main_nav_container = reference_elem,
				main_nav_container_position = main_nav_container.offset(),
				main_nav_container_width = main_nav_container.width(),
				main_nav_container_left_edge = main_nav_container_position.left,
				main_nav_container_right_edge = main_nav_container_left_edge + main_nav_container_width;

			if( ! jQuery( 'body.rtl' ).length ) {
				return this.each( function() {

					jQuery( this ).children( 'li' ).each( function() {
						var li_item = jQuery( this ),
							li_item_position = li_item.offset(),
							megamenu_wrapper = li_item.find( '.emallshop-megamenu-wrapper' ),
							megamenu_wrapper_width = megamenu_wrapper.outerWidth(),
							megamenu_wrapper_position = 0;

						// check if there is a megamenu
						if( megamenu_wrapper.length ) {
							megamenu_wrapper.removeAttr( 'style' );

							// set megamenu max width
							var reference_emallshop_row;

							if( jQuery( '.emallshop-secondary-main-menu' ).length ) {
								reference_emallshop_row = jQuery( '.emallshop-header-wrapper .emallshop-secondary-main-menu .emallshop-row' );
							} else {
								reference_emallshop_row = jQuery( '.emallshop-header-wrapper .emallshop-row' );
							}

							if( megamenu_wrapper.hasClass( 'col-span-12' ) && ( reference_emallshop_row.width() < megamenu_wrapper.data( 'maxwidth' ) ) ) {
								megamenu_wrapper.css( 'width', reference_emallshop_row.width() );
							} else {
								megamenu_wrapper.removeAttr( 'style' );
							}

							// reset the megmenu width after resizing the menu
							megamenu_wrapper_width = megamenu_wrapper.outerWidth();

							if( li_item_position.left + megamenu_wrapper_width > main_nav_container_right_edge ) {
								megamenu_wrapper_position = -1 * ( li_item_position.left - ( main_nav_container_right_edge - megamenu_wrapper_width ) );

								megamenu_wrapper.css( 'left', megamenu_wrapper_position );
							}
						}
					});
				});

			} else {
				return this.each( function() {
					jQuery( this ).children( 'li' ).each( function() {
						var li_item = jQuery( this ),
							li_item_position = li_item.offset(),
							li_item_right_edge = li_item_position.left + li_item.outerWidth(),
							megamenu_wrapper = li_item.find( '.emallshop-megamenu-wrapper' ),
							megamenu_wrapper_width = megamenu_wrapper.outerWidth(),
							megamenu_wrapper_position = 0;

						// check if there is a megamenu
						if( megamenu_wrapper.length ) {
							megamenu_wrapper.removeAttr( 'style' );

							// set megamenu max width
							var reference_emallshop_row;

							if( jQuery( '.emallshop-secondary-main-menu' ).length ) {
								reference_emallshop_row = jQuery( '.emallshop-header-wrapper .emallshop-secondary-main-menu .emallshop-row' );
							} else {
								reference_emallshop_row = jQuery( '.emallshop-header-wrapper .emallshop-row' );
							}

							if( megamenu_wrapper.hasClass( 'col-span-12' ) && ( reference_emallshop_row.width() < megamenu_wrapper.data( 'maxwidth' ) ) ) {
								megamenu_wrapper.css( 'width', reference_emallshop_row.width() );
							} else {
								megamenu_wrapper.removeAttr( 'style' );
							}

							if( li_item_right_edge - megamenu_wrapper_width < main_nav_container_left_edge ) {

								megamenu_wrapper_position = -1 * ( megamenu_wrapper_width - ( li_item_right_edge - main_nav_container_left_edge ) );

								megamenu_wrapper.css( 'right', megamenu_wrapper_position );
							}
						}
					});
				});
			}
		}
	};
	
	// Calculate megamenu position
	if( jQuery.fn.emallshop_position_megamenu ) {
		jQuery( '.emallshop-main-menu > ul' ).emallshop_position_megamenu();

		jQuery( '.emallshop-main-menu .emallshop-megamenu-menu' ).mouseenter( function() {
			jQuery( this ).parent().emallshop_position_megamenu();
		});

		jQuery(window).resize(function() {
			jQuery( '.emallshop-main-menu > ul' ).emallshop_position_megamenu();
		});
	}
	
	// position dropdown menu correctly
	jQuery.fn.emallshop_position_menu_dropdown = function( variables ) {

		return 	jQuery( this ).children( '.sub-menu' ).each( function() {

			// reset attributes
			jQuery( this ).removeAttr( 'style' );
			jQuery( this ).show();
			jQuery( this ).removeData( 'shifted' );

			var submenu = jQuery( this );

			if( submenu.length ) {
				var submenu_position = submenu.offset(),
					submenu_left = submenu_position.left,
					submenu_top = submenu_position.top,
					submenu_height = submenu.height(),
					submenu_width = submenu.outerWidth(),
					submenu_bottom_edge = submenu_top + submenu_height,
					submenu_right_edge = submenu_left + submenu_width,
					browser_bottom_edge = jQuery( window ).height(),
					browser_right_edge = jQuery( window ).width();

				if(	jQuery( '#wpadminbar' ).length ) {
					var admin_bar_height = jQuery( '#wpadminbar' ).height();
				} else {
					var admin_bar_height = 0;
				}

				if( jQuery( '#side-header' ).length ) {
					var side_header_top = jQuery( '#side-header' ).offset().top - admin_bar_height;
				}

				// current submenu goes beyond browser's right edge
				if( submenu_right_edge > browser_right_edge ) {

					//if there are 2 or more submenu parents position this submenu below last one
					if( submenu.parent().parent( '.sub-menu' ).parent().parent( '.sub-menu' ).length ) {
						submenu.css({
							'left': '0',
							'top': submenu.parent().parent( '.sub-menu' ).height()
						});

					// first or second level submenu
					} else {
						// first level submenu
						if( ! submenu.parent().parent( '.sub-menu' ).length ) {
							submenu.css( 'left', ( -1 ) * submenu_width + submenu.parent().width() );

						// second level submenu
						} else {
							submenu.css({
								'left': ( -1 ) * submenu_width
							});
						}
					}

					submenu.data( 'shifted', 1 );
				// parent submenu had to be shifted
				} else if( submenu.parent().parent( '.sub-menu' ).length ) {
					if( submenu.parent().parent( '.sub-menu' ).data( 'shifted' ) ) {
						submenu.css( 'left', ( -1 ) * submenu_width );
						submenu.data( 'shifted', 1 );
					}
				}

			}
		});
			
	};

	// Recursive function for positioning menu items correctly on load
	jQuery.fn.walk_through_menu_items = function() {
		jQuery( this ).emallshop_position_menu_dropdown();

		if( jQuery( this ).find( '.sub-menu' ).length ) {
			jQuery( this ).find( '.sub-menu li' ).walk_through_menu_items();
		} else {
			return;
		}
	};
	
	// Calculate main menu dropdown submenu position
	if( jQuery.fn.emallshop_position_menu_dropdown ) {
		jQuery( '.emallshop-dropdown-menu, .emallshop-dropdown-menu li' ).mouseenter( function() {
			jQuery( this ).emallshop_position_menu_dropdown();
		});

		jQuery( '.emallshop-dropdown-menu > ul > li' ).each( function() {
			jQuery( this ).walk_through_menu_items();
		});

		jQuery( window ).on( 'resize', function() {
			jQuery( '.emallshop-dropdown-menu > ul > li' ).each( function() {
				jQuery( this ).walk_through_menu_items();
			});
		});
	}
	
	//*******************************************************************
	//*  Mobile menu display
	//*******************************************************************/		
	$(document).on('click','.mobile-main-navigation .toggle-menu',function(){
		$(this).closest('.header-main-navigation').find('.emallshop-mobile-menu').slideToggle();
		return false;
	});
		
	$('.emallshop-mobile-menu li.menu-item-has-children').append('<span class="toggle-submenu"><i class="fa fa-plus"></i></span>');
	$(document).on('click', '.emallshop-mobile-menu li.menu-item-has-children .toggle-submenu', function(){
		if($(this).parent().siblings('.opening').length){
			var old_open = $(this).parent().siblings('.opening');
			old_open.children('ul').stop().slideUp(200);
			old_open.children('.toggle-submenu').children('.fa').removeClass('fa-minus').addClass('fa-plus');
			old_open.removeClass('opening');
		}
		if($(this).parent().hasClass('opening')){
			$(this).parent().removeClass('opening').children('ul').stop().slideUp(200);
			$(this).parent().children('.toggle-submenu').children('.fa').removeClass('fa-minus').addClass('fa-plus');
		}else{
			$(this).parent().addClass('opening').children('ul').stop().slideDown(200);
			$(this).parent().children('.toggle-submenu').children('.fa').removeClass('fa-plus').addClass('fa-minus');
		}
	});
	
	
	//*******************************************************************
	//*  Sidebar scrolling fixed.
	//*******************************************************************/
	$(document).ready(function(){
		jQuery('#sidebar').theiaStickySidebar({
			// Settings
			additionalMarginTop: 30
		});
	});
	
});