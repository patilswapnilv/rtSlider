jQuery(function(jQuery) {
	
	jQuery('.repeatable-add').click(function() {
		field = jQuery(this).closest('td').find('.custom_repeatable li:last').clone(true);
		fieldLocation = jQuery(this).closest('td').find('.custom_repeatable li:last');
		jQuery('.imgTimThumb', field).attr('src','' );
		jQuery('input, textarea', field).val('').attr('name', function(index, name) {
			return name.replace(/(\d+)/, function(fullMatch, n) {
				return Number(n) + 1;
			});
		})
		var hdnPlgUrl = jQuery('#hdnPlgUrl').text();
		jQuery('.imgTimThumb', field).attr('src',hdnPlgUrl+'/css/noimage.png' );
		field.insertAfter(fieldLocation, jQuery(this).closest('td'))
		return false;
	});
	
	jQuery('.repeatable-remove').click(function(){

		childrenOfUl  = jQuery(this).parent().siblings().length;
		if(childrenOfUl > 0)
			jQuery(this).parent().remove();
		return false;
	});
		
	jQuery('.custom_repeatable').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.sort'
	});


});