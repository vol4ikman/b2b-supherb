<?php
/**
 * Default page template
 *
 * @package WordPress
 */

get_header();
?>

<div class="page-wrapper">
	<div class="main-container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<?php the_content(); ?>

		<?php endwhile; ?>
	</div>
</div>

<?php
get_footer();
