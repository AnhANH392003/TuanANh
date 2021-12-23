<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

get_header(); ?>

	<div class="container">		
		<div class="row">
        	<div class="content-area col-md-12">

			<div class="error-404 not-found">
				
					<h1>404 <span><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'emallshop' ); ?><span></h1>

					<p><?php esc_html_e( 'Try using the button below to go to back previous page.', 'emallshop' ); ?></p>
					<?php
					if((isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !='')): ?>
						<a class="button" href="<?php echo esc_url($_SERVER['HTTP_REFERER']);?>"><i class="fa fa-reply"></i> <?php esc_html_e('Go to Back','emallshop');?></a>
					<?php endif;?>
			</div><!-- .error-404 -->

		</div>
	</div><!-- .content-area -->

<?php get_footer(); ?>
