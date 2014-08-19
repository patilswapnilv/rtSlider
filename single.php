/*<?php get_header(); ?>


	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php  the_content(); ?>
		<?php echo do_shortcode('[rtSlider id="'.$post->ID.'"]'); ?>
		<!-- echo '[rtSlider id="'.$post->ID.'"]'; -->
	<?php endwhile; ?>			
			

<?php get_footer(); ?>*/

<?php
/**
 * The Template for displaying all Single Project posts.
 *
 * @package WordPress
 * @subpackage Laurel Streng
 * @since Laurel Streng 2.0
 */
 
get_header(); ?>
 
<?php while ( have_posts() ) : the_post(); ?>
 
        <?php if ( is_single() ) : ?>
 
                <div class="row" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  		echo'rtslider' .$post->ID;
        <?php endif; // is_single() ?>
 
<?php endwhile; ?>
 
<?php get_footer(); ?>
