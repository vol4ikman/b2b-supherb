<?php
/**
 * Search page template
 *
 * @package WordPress
 */

get_header();
get_template_part( 'inc/header/search-results', 'banner' );
?>

<section class="search-results-page">
	<div class="container">
		<div class="search-results-content">
			<?php
			while ( have_posts() ) :
				the_post();
				$product_subtitle = get_field( 'subtitle' );
				$item_post_type   = get_post_type();
				?>
				<article>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="sr-thumbnail post-type-<?php echo esc_html( $item_post_type ); ?>">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail(); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="sr-meta">
						<h3>
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h3>
						<div class="desc">
							<?php echo $product_subtitle ? wp_kses_post( $product_subtitle ) : get_the_excerpt(); ?>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
