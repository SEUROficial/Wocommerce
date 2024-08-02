jQuery(document).ready(function($){
    var seur_country_state_process = 'admin-ajax.php?action=seur_country_state_process';

    $("#emp-SaveForm").on( "change", 'select.rate', seur_rate_change );
    $("#emp-SaveForm").on( "change", 'select.country', seur_country_change );
    $("#emp-UpdateForm").on( "change", 'select.rate', seur_rate_change );
    $("#emp-UpdateForm").on( "change", 'select.country', seur_country_change );

    function seur_rate_change() {
        var selectedRate = $(".rate option:selected").val();
        $.ajax({
            type: "POST",
            url: seur_country_state_process,
            data: { rate : selectedRate }
        }).done(function(data){
            $("#countryid").replaceWith( $("#countryid").html(data) );
        });
    }

    function seur_country_change() {
        var selectedCountry = $(".country option:selected").val();
        $.ajax({
            type: "POST",
            url: seur_country_state_process,
            data: { country : selectedCountry }
        }).done(function(data){
            $("#states").replaceWith( $("#states").html(data) );
        });
    }
});