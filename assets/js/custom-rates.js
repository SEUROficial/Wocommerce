jQuery(document).ready(function($) {
	var doc = $(document);
	jQuery('a.add-type').on('click', function(e) {
		e.preventDefault();
		var content = jQuery('#type-container .type-row'),
		element = null;
		for(var i = 0; i<1; i++){
			element = content.clone();
			var type_div = 'teams_'+jQuery.now();
			element.attr('id', type_div);
			element.find('.remove-type').attr('targetDiv', type_div);
			element.appendTo('#type_container');
		}
	});
	jQuery(".remove-type").on('click', function (e) {
		var didConfirm = confirm("Are you sure You want to delete");
		if (didConfirm === true) {
			var id = jQuery(this).attr('data-id');
			var targetDiv = jQuery(this).attr('targetDiv');
			//if (id == 0) {
			//var trID = jQuery(this).parents("tr").attr('id');
			jQuery('#' + targetDiv).remove();
		// }
			return true;
		} else {
			return false;
		}
	});
});