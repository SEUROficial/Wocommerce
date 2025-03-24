// JavaScript Document

jQuery(document).ready(function($){

	var custom_rates_page			= 'admin.php?page=seur_rates_prices&tab=custom_rates';
	var custom_rates_page_add_form	= 'admin.php?page=seur_add_form';
	var custom_rates_page_create	= 'admin.php?page=seur_create_custom_rate';

	$("#btn-view").hide();

	$("#btn-add").click(function(){
		$(".content-loader").fadeOut('slow', function()
		{
			//$(".content-loader").fadeIn('slow');
			$(".content-loader").load( custom_rates_page_add_form );
			$(".content-loader").delay( 2000 ).fadeIn('slow');
			$("#btn-add").hide();
			$("#btn-view").show();
		});
	});

	$("#btn-view").click(function(){

		$("body").fadeOut('slow', function()
		{
			$("body").load( custom_rates_page );
			$("body").delay( 2000 ).fadeIn('slow');
			window.location.href= custom_rates_page ;
		});
	});

});

jQuery(document).ready(function($) {
	$('#example').DataTable();

	$('#example')
	.removeClass( 'display' )
	.addClass('table table-bordered');
});

jQuery(document).ready(function($){

	var custom_rates_page			= 'admin.php?page=seur_rates_prices&tab=custom_rates';
	var custom_rates_page_create	= 'admin.php?page=seur_create_custom_rate';
	var custom_rates_page_delete	= 'admin.php?page=seur_delete_rate';
	var custom_rates_page_update	= 'admin.php?page=seur_update_custom_rate';
	var custom_rates_page_edit		= 'admin.php?page=seur_edit_rate&edit_id=';

	function validatePostcodeFormat(postcode) {
		const pattern = /^(\*|[A-Z0-9]+\*|[A-Z0-9]+\.\.[A-Z0-9]+|[A-Z0-9]+)$/i;
		return pattern.test(postcode);
	}

	function validatePostcodes(postcodes) {
		const lines = postcodes.split("\n").map(line => line.trim()); // Separar por líneas y eliminar espacios
		return lines.every(line => validatePostcodeFormat(line)); // Verificar que todas las líneas sean válidas
	}

	/* Data Insert Starts Here */

	$(document).on('submit', '#emp-SaveForm', function() {
		const postcode = document.getElementById("postcode").value;
		if (!validatePostcodes(postcode)) {
			event.preventDefault(); // Detiene el envío del formulario
			$("#dis").fadeOut();
			$("#dis").fadeIn('slow', function () {
				$("#dis").html('<div class="notice notice notice-error"><p>Postcode: Formato inválido</p></div>');
			});
		} else {
			$.post(custom_rates_page_create, $(this).serialize())
				.done(function (data) {
					$("#dis").fadeOut();
					$("#dis").fadeIn('slow', function () {
						$("#dis").html('<div class="alert alert-info"><p>' + data + '</p></div>');
						$("#emp-SaveForm")[0].reset();
					});
				});
			return false;
		}
    });
	/* Data Insert Ends Here */
	
	/* Data Delete Starts Here */
	$(".delete-link").click(function()
	{
		var id = $(this).attr("id");
		var del_id = id;
		var parent = $(this).parent("td").parent("tr");
		if(confirm('Sure to Delete ID no ' +del_id))
		{
			$.ajax({
				url: custom_rates_page_delete,
				type: 'post',
				data: {
					'del_id':del_id
        			},
				success: function(data)
					{ parent.fadeOut('slow');
				}
				});
		}
		return false;
	});
	/* Data Delete Ends Here */

	/* Get Edit ID  */
	$(".edit-link").click(function()
	{
		var id = $(this).attr("id");
		var edit_id = id;
        $(".content-loader").fadeOut('slow', function()
         {
            $(".content-loader").load( custom_rates_page_edit + edit_id );
            $(".content-loader").delay( 3000 ).fadeIn('slow');
            $("#btn-add").hide();
            $("#btn-view").show();
        });
        return false;
	});
	/* Get Edit ID  */

	/* Update Record  */
	$(document).on('submit', '#emp-UpdateForm', function() {
		const postcode = document.getElementById("postcode").value;
		if (!validatePostcodes(postcode)) {
			event.preventDefault(); // Detiene el envío del formulario
			$("#dis").fadeOut();
			$("#dis").fadeIn('slow', function () {
				$("#dis").html('<div class="notice notice notice-error"><p>Postcode: Formato inválido</p></div>');
			});
		} else {
			edit_id = $(this).find('input[name="id"]').val();
			$.post(custom_rates_page_update, $(this).serialize())
			.done(function (data) {
				$("#dis").fadeOut();
				$("#dis").fadeIn('slow', function () {
					$("#dis").html('<div class="alert alert-info">' + data + '</div>');
					$("#emp-UpdateForm")[0].reset();
				});
			});
			return false;
		}
    });
	/* Update Record  */
});