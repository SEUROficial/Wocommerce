jQuery(document).ready(function($){
    var seur_country_state_process = 'admin.php?page=seur_country_state_process';

    $("select.country").change(function(){
        var selectedCountry = $(".country option:selected").val();
        $.ajax({
            type: "POST",
            url: seur_country_state_process,
            data: { country : selectedCountry }
        }).done(function(data){
            $("#states").replaceWith( $("#states").html(data) );
        });
    });
});