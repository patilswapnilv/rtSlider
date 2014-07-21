<?php

function rtSlider_shortcode($atts) {
	
	extract(shortcode_atts(array(
	    'id' => 0,
	    'title' => false,
	    'text' => false,
		'size' => 'full'
	), $atts));
	

	// if Post ID isn't set
	if($atts['id'] == 0) return '<strong>Please set the post id in short code eg. [rtSlider id="27"].</strong>';

	$postid = $atts['id'];

	// JAVASCRIPT
	$out = "
	<script type='text/javascript'>
		jQuery(document).ready(function($){

			  $('.flexslider').flexslider({
				animation: 'slide',
				start: function(slider){
				  $('#containerFlexDiv').height('auto');
				  $('.loading_rtSlider').hide();
				}
			  });
		";

	// End Javascript
	$out .= "
		});
	</script>
	";
	
	$metaSliders 		= get_post_meta($postid, 'rtSlider_repeatable', true); 
	$meta_thumbnail	 	= get_post_meta($postid, 'meta_box_select_rsthumb', true); 
	$meta_display	 	= get_post_meta($postid, 'meta_box_check_text', true); 
	$meta_display_title	= get_post_meta($postid, 'meta_box_check_title', true); 
	
	$att_size = '';
	if(isset($meta_thumbnail))
		$att_size = $meta_thumbnail;
	else
		$att_size = 'full';
		
	
	$image_size = get_thumb_image_width( $att_size );
	if ( !empty( $image_size ) ) {
		$width = $image_size['width'];
		$height = $image_size['height'];
	}

	$hide = false;
	if(isset($meta_display) && $meta_display == 'on')
		$hide = true;

	$hide_title = false;
	if(isset($meta_display_title) && $meta_display_title == 'on')
		$hide_title = true;

		
		
		
	//echo $att_size.' width='.$width.' height='.$height;
	$custom_style = '';
	if($width > 100 && $height > 100)
		$custom_style = 'style="height:'.($height+100).'px ; max-width:'.$width.'px"';
		
	
	if($hide_title == false):
		$out .= '<div class="rsTitle"><h3>'.get_the_title($postid).'</h3></div>';
	endif;
	// slide start
	$out .= '
			<div id="containerFlexDiv" class="cf" '.$custom_style.'>
			  <div class="loading_rtSlider"></div>
			  <section class="slider">
				<div class="flexslider">
				  <ul class="slides">';
					
					if ($metaSliders) {
						foreach($metaSliders as $row) {
							$exp_data = explode("::::", $row);

							$attachment_id = $exp_data[2];
							$this_img = wp_get_attachment_image_src( $attachment_id , $att_size );
							$imgSrc = $this_img[0];
							
							$title = $exp_data[0];
							$description = $exp_data[1];
							$href = $exp_data[3];
							
							#echo nl2br($exp_data[1]);

							$out .= '<li>';
								$out .= '<div class="rtSliderWrap">';
									$out .= '<img src='.$imgSrc.' alt="'.$title.'" />';
									
									if($hide == false):
										$out .= '<div class="rtSliderContent">';
											$out .= '<div><a href="'.$href.'" >'.$title.'</a></div>';
											$out .= '<div>'.$description.'</div>';
										$out .= '</div>';
									endif;
									
									
								$out .= '</div>';
							$out .= '</li>';
									

						}
					}


		// slide end
		$out .= '
				 </ul>
				</div>
			  </section>
		   </div>';
		
	return $out;
}
add_shortcode('rtSlider', 'rtSlider_shortcode');


function get_thumb_image_width( $name ) {
	global $_wp_additional_image_sizes;


	if($name == 'thumbnail'){
		$return_dimensions['width'] = get_option( 'thumbnail_size_w');
		$return_dimensions['height'] = get_option( 'thumbnail_size_h');
		return $return_dimensions;
	}
	elseif($name == 'medium'){
		$return_dimensions['width'] = get_option( 'medium_size_w');
		$return_dimensions['height'] = get_option( 'medium_size_h');
		return $return_dimensions;
	}
	elseif($name == 'large'){
		$return_dimensions['width'] = get_option( 'large_size_w');
		$return_dimensions['height'] = get_option( 'large_size_h');
		return $return_dimensions;
	}
	elseif ( isset( $_wp_additional_image_sizes[$name] ) ){
		return $_wp_additional_image_sizes[$name];
	}

	return false;
}