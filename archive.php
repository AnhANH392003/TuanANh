<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

get_header(); ?>

	<div class="container">		
		<div class="row">
        	<div class="content-area col-md-9">
				<div class="blog-posts">
					<?php if( emallshop_get_option('show-title-breadcrumb-content','in-page-heading')=="in-page-content" ):?>
						<header class="entry-header">
							<?php
								the_archive_title( '<h1 class="page-title">', '</h1>' );
								the_archive_description( '<div class="taxonomy-description">', '</div>' );
							?>
						</header><!-- .page-header -->
					<?php endif;?>
					
					<?php if ( have_posts() ) : ?>
						<?php if ( is_home() && ! is_front_page() ) : ?>
							<header>
								<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
							</header>
						<?php endif; ?>
						<?php
						// Start the loop.
						while ( have_posts() ) : the_post();
							 /*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'content', get_post_format() );
						// End the loop.
						endwhile;        
						// Previous/next page navigation.
						emallshop_pagination_nav();        
					// If no content, include the "No posts found" template.
					else :
						get_template_part( 'content', 'none' );        
					endif;
					?>
				</div>
        	</div>
           	<?php get_sidebar(); ?>
		</div>
	</div><!-- .content-area -->

<?php get_footer(); ?>
