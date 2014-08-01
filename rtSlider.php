<?php
/*
Plugin Name: rtSlider
Plugin URI: http://github.com/patilswapnilv/rtSlider
Description: A responsive and swipe slider.
Author: Swapnil V. Patil
Version: 0.0.1
Author URI: http://www.swapnilpatil.in/
*/

// Url to plugin
define('RT_SLIDER_PLUGIN_URL', WP_PLUGIN_URL.'/'. dirname( plugin_basename(__FILE__) ) );


/*
 * Load Javascript
 */

function rtSlider_script(){
	if (!is_admin()){
		
		wp_enqueue_script( "flexslider", RT_SLIDER_PLUGIN_URL ."/flex/js/jquery.flexslider.js", array('jquery'), '1.0', true );
		wp_enqueue_script( "easing", RT_SLIDER_PLUGIN_URL ."/flex/js/jquery.easing.js", array('jquery'), '1.0', true );
		wp_enqueue_script( "mousewheel", RT_SLIDER_PLUGIN_URL ."/flex/js/jquery.mousewheel.js", array('jquery'), '1.0', true );
		
		wp_enqueue_style('flexslider', RT_SLIDER_PLUGIN_URL . '/flex/css/flexslider.css');
						
	}
}
add_action('wp_print_scripts', 'rtSlider_script');



// Shortcode
include('shortcode.php');

add_action('init', 'add_post_type_rtslider');
function add_post_type_rtslider() {
	register_post_type(
		'rtslider',
		array(
			'labels' => array(
				'name' => 'Sliders', //general name for the post type, usually plural. The same as, and overridden by $post_type_object->label
			    'singular_name' => 'Slider'
			), // (string) (optional) A plural descriptive name for the post type marked for translation.
			'public' => false, // (boolean) (optional) Whether posts of this type should be shown in the admin UI.
			'exclude_from_search' => false, //(boolean) (importance) Whether to exclude posts with this post type from search results.
			'publicly_queryable' => true,//(boolean) (optional) Whether post_type queries can be performed from the front page.
			'show_ui' => true, // (boolean) (optional) Whether to generate a default UI for managing this post type.
			//'inherit_type' => '', //(string) (optional) The post type from which to inherit the edit link and capability type.
			'capability_type' => 'page',//(string) (optional) The post type to use for checking read, edit, and delete capabilities.
			'hierarchical' => false, // (boolean) (optional) Whether the post type is hierarchical.
			'show_in_nav_menus' => false,
			'query_var' => true,
			'supports' => array(
				'title',
				#'editor',
				#'author',
				#'thumbnail',
				#'excerpt',
				#'custom-fields'
				#'trackbacks',
				#'comments',
				#'revisions'//,
				#'page-attributes' //(parent, template, and menu order)
			)
			
		)
	);
}


require_once('metabox/add_responsive_slider_meta_box.php');

add_action("manage_posts_custom_column",  "rtSlider_custom_columns");
add_filter("manage_edit-rtslider_columns", "rtSlider_edit_columns");

function rtSlider_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Title",
	"description_rs" => "Shortcode",
    "date" => "Date"
  );

  return $columns;
}
function rtSlider_custom_columns($column){
  global $post;

  switch ($column) {

    case "description_rs":
       echo '[rtSlider id="'.$post->ID.'"]';
      break;


  }// end switch
}