rtSlider creates multiple gallery each having multiple images

== Description ==
rtSlider can create multiple image galleries and you can display by using short code [rtSlider id=27]. Where 27 is the post id. You can get this shortcode in the admin panel.

Each gallery can be of different size. You can set the size of gallery under the Slider panel setting. If you want to add your own custom size then use add_image_size() in the function.php file. More detail can be found here how to add custom image sizes http://codex.wordpress.org/Function_Reference/add_image_size
After doing that you can select your own custom size in the Slider setting panel.

More over you can choose to display the title and description of each image and you can also hide it admin panel setting

== Screenshots ==

1. Admin panel will look like this. You can use this shortcode in the content.
2. Add new slides inside gallery.
3. Slider setting panel

== Installation ==

1. Download the plugin and extract the files
2. Upload `rtSlider` to your `~/wp-content/plugins/` directory
3. Edit the templates your Theme uses and add the following code:
    
	`<?php
		echo do_shortcode("[rtSlider id=27]");
	?>`
	
4. You can also add the short code in the content.

Test it out and enjoy!

