<?php
if(emallshop_get_option('show-topbar', 1)==1):?>
<div class="header-topbar">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 text-left">
				<?php 
				emallshop_currency();
				emallshop_language();?>
				
			</div>
			<div class="col-sm-8 text-right">
				<div class="topbar-right">
					<?php if( function_exists( 'emallshop_dokan_header_user_menu' ) ) {
						emallshop_dokan_header_user_menu();
					}else{					
						if( function_exists( 'emallshop_myaccount' ) ) {
							emallshop_myaccount();
						}
						if( function_exists( 'emallshop_checkout' ) ) {
							emallshop_checkout();
						}
					}
					if( function_exists( 'emallshop_wishlist' ) ) {
						emallshop_wishlist();
					}
					if( function_exists( 'emallshop_campare' ) ) {
						emallshop_campare();
					}
					if( function_exists( 'emallshop_cart' ) ) {
						emallshop_cart();
					}?>					
					
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif;?>
<div class="header-middle">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<?php emallshop_header_logo();?>
			</div>
			<div class="col-sm-9">
				<?php emallshop_header_menu();?>
			</div>    
		</div>
	</div>
</div>
