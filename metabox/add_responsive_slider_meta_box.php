<?php
add_action("admin_init", "admin_init_metaboxes");
function admin_init_metaboxes(){
	add_meta_box( 'rtSlider_meta_box', 'Images', 'show_rtSlider_meta_box','rtslider','normal','high'); 
	add_meta_box( 'slider_setting', 'Slider Setting Panel', 'slider_meta_setting', 'rtslider', 'side', 'low' );	
}

// Field Array
$prefix = 'rtSlider_';
$rtSlider_meta_fields = array(
	array(
		'label'	=> 'Name',
		'desc'	=> 'Add your images',
		'id'	=> $prefix.'repeatable',
		'id2'	=> $prefix.'links',
		'id3'	=> $prefix.'linksname',
		'id4'	=> $prefix.'href',
		'type'	=> 'repeatable'
	)
);

// enqueue scripts and styles, but only if is_admin
if(is_admin()) {

	wp_enqueue_script('admin-js', RT_SLIDER_PLUGIN_URL.'/metabox/responsive_meta_box_js.js', array( 'jquery' ));
	wp_enqueue_style('rtSlider', RT_SLIDER_PLUGIN_URL . '/css/rtSlider_admin.css');

}

	
function load_custom_wp_admin_style() {
	if( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );



// The Callback
function show_rtSlider_meta_box() {
	global $rtSlider_meta_fields, $post;
?>

<script>
( function( $ ) {

   $(document).ready(

       function()
       {
			/*var uploadID = '';
		   jQuery('.upload_image_button').click(function(e) {
				e.preventDefault();
				uploadID = $(this).siblings('div').children('input:last');
				uploadThumb = $(this).children('img');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				jQuery("#TB_window,#TB_overlay,#TB_HideSelect").one("unload",function(e){
					e.stopPropagation();
					e.stopImmediatePropagation();
					return false;
				});				
				return false;
			});
			
			window.send_to_editor = function(html) {
				imgurl = jQuery('img',html).attr('src');
				uploadID.val(imgurl); //assign the value to the input
				uploadThumb.attr('src','');
				var hdnHomeUrl = jQuery('#hdnHomeUrl').text();
				var hdnSitePath = jQuery('#hdnSitePath').text();				
				var relativeURL = imgurl.replace(hdnHomeUrl,'');
				var timThumbUrl = jQuery('#hdnPlgUrl').text()+'/timthumb/timthumb.php?src='+hdnSitePath+relativeURL+'&h=140&w=256&q=100';
				uploadThumb.attr('src',timThumbUrl );
				tb_remove();
			};*/
			// Uploading files



			var media_upload_frame;
			// Bind to our click event in order to open up the new media experience.
			jQuery('.upload_image_button').click(function(e) {
				// Prevent the default action from occuring.
				e.preventDefault();
		
				// If the frame already exists, re-open it.
				/*if ( media_upload_frame ) {
					media_upload_frame.open();
					return;
				}*/
	
				media_upload_frame = wp.media.frames.media_upload_frame = wp.media({
					className: 'media-frame rs-media-frame',
					frame: 'select',
					multiple: false,
					title: 'Choose Image file',
					library: {
						type: 'image'
					},
					button: {
						text:  'Select Image'
					}
				});
		
				var uploadThumb = jQuery(this).children('img');
				var uploadID = jQuery(this).siblings('div').find('input:last');
				

				media_upload_frame.on('select', function(){

					var media_attachment = media_upload_frame.state().get('selection').first().toJSON();
					
					var imgurl 				= media_attachment.url; 
					var imgurl_thumbnail 	= media_attachment.sizes.thumbnail.url; 
					var imgid 				= media_attachment.id; 
					uploadThumb.attr('src',imgurl_thumbnail );
					uploadID.val(imgid); 

				});
		
				// Now that everything has been set, let's open up the frame.
				media_upload_frame.open();
			});
				
       }
   );

} ) ( jQuery );
</script>
<?php	
	
	// Use nonce for verification
	echo '<input type="hidden" name="rtSlider_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($rtSlider_meta_fields as $field) {
		// get value of this field if it exists for this post
		//$meta = get_post_custom($post->ID);
		$meta = get_post_meta($post->ID, $field['id'], true);
		
		
	
		
		// begin a table row with
		echo '<tr>
				<td>';
				switch($field['type']) {
					case 'repeatable':
						echo '<div id="hdnPlgUrl" style="display:none;">'.RT_SLIDER_PLUGIN_URL.'</div>';
						echo '<div id="hdnHomeUrl" style="display:none;">'.home_url().'</div>';
							$path = home_url();
							$parsed_url = parse_url($path);
							$path_site = $parsed_url['path'];
						echo '<div id="hdnSitePath" style="display:none;">'.$path_site.'</div>';
						
						echo '<ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
						$i = 0;
						if ($meta) {
							foreach($meta as $row) {
								$exp_data = explode("::::", $row); 
								
								if(!empty($exp_data[2])){
									//$fileInfos = $exp_data[2];
									//$fileInfos = str_replace($path,'',$fileInfos);
									//$imgSrcTim = RT_SLIDER_PLUGIN_URL.'/timthumb/timthumb.php?src='.$path_site.''.$fileInfos.'&h=140&w=256&q=100';
									$attachment_id = $exp_data[2];
									$this_img = wp_get_attachment_image_src( $attachment_id , 'thumbnail' );
									$imgSrcTim = $this_img[0];
									
								}else{
									$imgSrcTim = RT_SLIDER_PLUGIN_URL."/css/noimage.png";
								}
								echo '<li class="li_slide_row">
											<span class="sort hndle span_slide_row">
												<img src="'.RT_SLIDER_PLUGIN_URL.'/css/move.png">
											</span>
											<div class="upload_image_button button_slide_row"  id="upload_image_button_'.$i.'">
												<img class="imgTimThumb" src="'.$imgSrcTim.'">
											</div>
											<div class="content_slide_row">
												
												<div class="input_slide_row">
													<label style="font-weight:bold; margin:0 0 0 2px;">Title</label><br />
													<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'_'.$i.'" value="'.$exp_data[0].'" size="30" /><br />
													<label style="font-weight:bold; margin:0 0 0 2px;">href (Link)</label><br />
													<input type="text" name="'.$field['id4'].'['.$i.']" id="'.$field['id4'].'_'.$i.'" value="'.$exp_data[3].'" size="30" /><br />										
													<input type="hidden" name="'.$field['id3'].'['.$i.']" id="'.$field['id3'].'_'.$i.'" value="'.$exp_data[2].'" size="30" />
												</div>
												<div class="textarea_slide_row">
													<label style="font-weight:bold; margin:0 0 0 2px;">Description</label><br />
													<textarea style=" margin:0 10px 0 0" cols="40" rows="5" name="'.$field['id2'].'['.$i.']" id="'.$field['id2'].'_'.$i.'" >'.$exp_data[1].'</textarea>
												</div>

											</div>
											<a class="repeatable-remove button" href="#">-</a></li>
											';
											
								$i++;
							}
						} else {
							echo '<li class="li_slide_row">
											<span class="sort hndle span_slide_row">
												<img src="'.RT_SLIDER_PLUGIN_URL.'/css/move.png">
											</span>
											<div class="upload_image_button button_slide_row"  id="upload_image_button">
												<img class="imgTimThumb" src="'.RT_SLIDER_PLUGIN_URL.'/css/noimage.png">
											</div>
											<div class="content_slide_row">
												
												<div class="input_slide_row">
													<label style="font-weight:bold; margin:0 0 0 2px;">Title</label><br />
													<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'_'.$i.'" value="" size="30" /><br />
													<label style="font-weight:bold; margin:0 0 0 2px;"> (Link)</label><br />
													<input type="text" name="'.$field['id4'].'['.$i.']" id="'.$field['id4'].'_'.$i.'" value="" size="30" /><br />
													<input type="hidden" name="'.$field['id3'].'['.$i.']" id="'.$field['id3'].'_'.$i.'" value="" size="30" />											
												</div>
												<div class="textarea_slide_row">
													<textarea style=" margin:0 10px 0 0" cols="40" rows="5" name="'.$field['id2'].'['.$i.']" id="'.$field['id2'].'_'.$i.'" ></textarea>
												</div>
																						
											</div>
											<a class="repeatable-remove button" href="#">-</a></li>
											';
						}
						echo '</ul>
							';
						echo '<a class="repeatable-add button" href="#">+ Add New Slide</a>';
					break;
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}
/*
function remove_taxonomy_boxes() {
	remove_meta_box('categorydiv', 'post', 'side');
}
add_action( 'admin_menu' , 'remove_taxonomy_boxes' );*/

// Save the Data
function save_rtSlider_meta($post_id) {
	
	
    global $rtSlider_meta_fields;
	
	// verify nonce
	if (!wp_verify_nonce($_POST['rtSlider_meta_box_nonce'], basename(__FILE__))) 
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}
	
	foreach ($rtSlider_meta_fields as $field) {
		if($field['type'] == 'tax_select') continue;
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		$old2 = get_post_meta($post_id, $field['id2'], true);
		$new2 = $_POST[$field['id2']];

		$old3 = get_post_meta($post_id, $field['id3'], true);
		$new3 = $_POST[$field['id3']];
		
		$old4 = get_post_meta($post_id, $field['id4'], true);
		$new4 = $_POST[$field['id4']];		
						
		$new_save = array();
		$old_save = array();
		
		foreach($new as $key=>$newValue){
			$new_save[$key] = $new[$key].'::::'.$new2[$key].'::::'.$new3[$key].'::::'.$new4[$key];
			$old_save[$key] = $new[$key].'::::'.$new2[$key].'::::'.$new3[$key].'::::'.$new4[$key];
		}
		
		if ( ($new && $new != $old) || ($new2 && $new2 != $old2) || ($new3 && $new3 != $old3)  || ($new4 && $new4 != $old4) ) {
			update_post_meta($post_id, $field['id'], $new_save);
		} elseif ( ('' == $new && $old) || ('' == $new2 && $old2) || ('' == $new3 && $old3) || ('' == $new4 && $old4)) {
			delete_post_meta($post_id, $field['id'], $old_save);
		}
	} 
	// save taxonomies
	$post = get_post($post_id);
	$category = $_POST['category'];
	wp_set_object_terms( $post_id, $category, 'category' );
}
add_action('save_post', 'save_rtSlider_meta');
function admin_scripts()
{
   wp_enqueue_script('media-upload');
   wp_enqueue_script('thickbox');
}

function admin_styles()
{
   wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'admin_scripts');
add_action('admin_print_styles', 'admin_styles');


function slider_meta_setting( $post )
{
	$values = get_post_custom( $post->ID );
	$selected = isset( $values['meta_box_select_rsthumb'] ) ? esc_attr( $values['meta_box_select_rsthumb'][0] ) : '';
	$check_title = isset( $values['meta_box_check_title'] ) ? esc_attr( $values['meta_box_check_title'][0] ) : '';
	$check = isset( $values['meta_box_check_text'] ) ? esc_attr( $values['meta_box_check_text'][0] ) : '';
	
	
	wp_nonce_field( 'setting_meta_box_nonce', 'meta_box_nonce' );
	?>
    <p>
		<label for="meta_box_select_rsthumb">Select size</label>
        <?php $image_sizes = get_intermediate_image_sizes(); ?>
        <select name="meta_box_select_rsthumb" id="meta_box_select_rsthumb">
			<?php foreach ($image_sizes as $size_key => $size_value): ?>
            <option value="<?php echo $size_value ?>" <?php selected( $selected, $size_value ); ?>><?php echo $size_value ?></option>
            <?php endforeach; ?>
        </select>        
	</p>
	<p>
		<input type="checkbox" name="meta_box_check_title" id="meta_box_check_title" <?php checked( $check_title, 'on' ); ?> />
		<label for="meta_box_check_title"><strong>Hide</strong> title of slider</label>
	</p>    
	<p>
		<input type="checkbox" name="meta_box_check_text" id="meta_box_check_text" <?php checked( $check, 'on' ); ?> />
		<label for="meta_box_check_text"><strong>Hide</strong> title/description on slides</label>
	</p>   
	<?php	
}


add_action( 'save_post', 'save_rtSlider_meta_meta' );
function save_rtSlider_meta_meta( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'setting_meta_box_nonce' ) ) return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;
		
	if( isset( $_POST['meta_box_select_rsthumb'] ) )
		update_post_meta( $post_id, 'meta_box_select_rsthumb', esc_attr( $_POST['meta_box_select_rsthumb'] ) );
    
	$chk_title = ( isset( $_POST['meta_box_check_title'] ) && $_POST['meta_box_check_title'] ) ? 'on' : 'off';
		update_post_meta( $post_id, 'meta_box_check_title', $chk_title );
		
	// This is purely my personal preference for saving checkboxes
	$chk = ( isset( $_POST['meta_box_check_text'] ) && $_POST['meta_box_check_text'] ) ? 'on' : 'off';
		update_post_meta( $post_id, 'meta_box_check_text', $chk );	

}


?>