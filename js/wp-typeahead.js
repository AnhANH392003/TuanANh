( function($) {
	/* $.blockUI.defaults.message                      = null;
    $.blockUI.defaults.overlayCSS.background        = '#fff url(' + wp_typeahead.ajax_loader_url + ') no-repeat center';
    $.blockUI.defaults.overlayCSS.backgroundSize    = '16px 16px';
    $.blockUI.defaults.overlayCSS.opacity           = 0.6; */
	
	if( wp_typeahead.enable_live_search == '1' ) {
		var searchProducts = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: wp_typeahead.ajaxurl + '?action=products_live_search&fn=get_ajax_search&terms=%QUERY',
			remote: {
				url: wp_typeahead.ajaxurl + '?action=products_live_search&fn=get_ajax_search&terms=%QUERY&cat_slug=%cat_slug',
				//wildcard: '%QUERY',
				replace: function(url, query) {
				  //var cat_slug = encodeURIComponent($('#product_cat').val());
				  var cat_slug = encodeURIComponent($('#product_cat').val());
				  if(!$('#product_cat').val()){
					  cat_slug=-1;
				  }
				  return url.replace('%QUERY', query).replace('%cat_slug', cat_slug);
				},				
			},
			identify: function(obj) {
				return obj.id;
			}
		});

		searchProducts.initialize();
	
		$( '.search-control-group .search-field' ).typeahead(
			{
				hint: true,
				highlight: true,
				minLength:1,
				limit:10,
				//menu: $('.Typeahead-menu'),
				classNames: {
					cursor: 'is-active',
				}
			},{
				name: 'search',
				source: searchProducts.ttAdapter(),
				displayKey: 'value',
				templates: {
						empty : [
							'<div class="empty-message">Sorry. No results match your search.</div>'
						].join('\n'),
						suggestion: function (data) {
							if(data.empty_msg != "none"){
								return '<div class="empty-message"><p>'+ data.empty_msg +'</p></div>';
							}else{
								return '<div class="search-product-list"><div class="search-product-image"><img src="' + data.p_image + '"></div><div class="search-product-title"><a href="' + data.p_url + '">' + data.p_title +'</a><span class="search-product-price">'+ data.p_price +'</span></div></div>';								
							}
						},
					}
			}
		)
		.on('typeahead:asyncrequest', function() {
			$('.Typeahead-spinner').show();
		})
		.on('typeahead:asynccancel typeahead:asyncreceive', function() {
			$('.Typeahead-spinner').hide();
		});
	}
} )(jQuery);