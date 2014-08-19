<?php get_header(); ?>


	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php  the_content(); ?>
		<?php echo do_shortcode('[rtSlider id="'.$post->ID.'"]'); ?>
		<!-- echo '[rtSlider id="'.$post->ID.'"]'; -->
	<?php endwhile; ?>			
			

<?php get_footer(); ?>
