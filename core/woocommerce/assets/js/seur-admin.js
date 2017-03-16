jQuery(window).load(function(){
	( function($) {

		var seur_enabled = '#woocommerce_seur_enabled';

		// All SEUR API Settings
		var seur_settings_api = [
			'#woocommerce_seur_user_id',
			'#woocommerce_seur_password',
			'#woocommerce_seur_access_key',
			'#woocommerce_seur_shipper_number'
		];

		$( seur_enabled ).on( 'change', function() {
			if ( $(this).is(':checked') ) {
				$(this).closest('table').siblings().show();

				// Hide other settings until API details entered
				if ( $( '#woocommerce_seur_user_id' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_seur_password' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_seur_access_key' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_seur_shipper_number' ).filter(function() { return $(this).val(); }).length <= 0 ) {
					$( '#woocommerce_seur_shipper_number' ).closest('table').nextAll(':not(p.submit)').hide();
				} else {
					$("select#woocommerce_seur_packing_method").change();
				}
			} else {
				$(this).closest('table').nextAll(':not(p.submit)').hide();
			}
		});

		$( seur_settings_api.join(',') ).on( 'change input', function() {
			if ( $( '#woocommerce_seur_user_id' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_seur_password' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_seur_access_key' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_seur_shipper_number' ).filter(function() { return $(this).val(); }).length <= 0 ) {
					$( '#woocommerce_seur_shipper_number' ).closest('table').nextAll(':not(p.submit)').hide();
			} else {
				$( '#woocommerce_seur_shipper_number' ).closest('table').nextAll(':not(p.submit)').show();
				$("select#woocommerce_seur_packing_method").change();
			}
		});

		// When packing method changes, show/hide packaging options
		$("select#woocommerce_seur_packing_method").on( 'change',function(){
			if ($(this).val() === 'per_item') {
				$( '#woocommerce_seur_seur_packaging, .seur_boxes' ).parents('tr').hide();
			}
			if ($(this).val() === 'box_packing') {
				$( '#woocommerce_seur_seur_packaging, .seur_boxes' ).parents('tr').show();
			}
		});

		// Init
		$( seur_enabled ).change();

	})(jQuery);

	jQuery('.seur_boxes .insert').click( function() {
		var $tbody = jQuery('.seur_boxes').find('tbody');
		var size = $tbody.find('tr').size();
		var code = '<tr class="new">\
				<td class="check-column"><input type="checkbox" /></td>\
				<td><input type="text" size="5" name="boxes_outer_length[' + size + ']" />' + wcseur.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_outer_width[' + size + ']" />' + wcseur.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_outer_height[' + size + ']" />' + wcseur.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_inner_length[' + size + ']" />' + wcseur.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_inner_width[' + size + ']" />' + wcseur.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_inner_height[' + size + ']" />' + wcseur.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_box_weight[' + size + ']" />' + wcseur.weight_unit + '</td>\
				<td><input type="text" size="5" name="boxes_max_weight[' + size + ']" />' + wcseur.weight_unit + '</td>\
			</tr>';

		$tbody.append( code );

		return false;
	} );

	jQuery('.seur_boxes .remove').click(function() {
		var $tbody = jQuery('.seur_boxes').find('tbody');

		$tbody.find('.check-column input:checked').each(function() {
			jQuery(this).closest('tr').hide().find('input').val('');
		});

		return false;
	});

	// Ordering
	jQuery('.seur_services tbody').sortable({
		items:'tr',
		cursor:'move',
		axis:'y',
		handle: '.sort',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'wc-metabox-sortable-placeholder',
		start:function(event,ui){
			ui.item.css('baclbsround-color','#f6f6f6');
		},
		stop:function(event,ui){
			ui.item.removeAttr('style');
			seur_services_row_indexes();
		}
	});

	function seur_services_row_indexes() {
		jQuery('.seur_services tbody tr').each(function(index, el){
			jQuery('input.order', el).val( parseInt( jQuery(el).index('.seur_services tr') ) );
		});
	}

});
