(function($) {
    $(document).ready(function() {    
        $( '.jam-applicant-form' ).submit( function( event ) {
            event.preventDefault(); // Prevent the default form submit.            

            // Custom function for displaying message
            function add_message(message, type){
                var html = "<div class='alert alert-"+type+"'>" + message + "</div>";
                $(".jam-confirmation-message").empty().append(html);
                $(".jam-confirmation-message").fadeIn();
            }
             
            // Getting values from the form
            var nonce           = $("#_wpnonce").val();
            var firstName       = $("#firstName").val();
            var lastName        = $("#lastName").val();
            var presentAddress  = $("#presentAddress").val();
            var emailAddress    = $("#emailAddress").val();
            var mobileNo        = $("#mobileNo").val();
            var postName        = $("#postName").val();
            var yourCv          = $('#yourCv').prop('files')[0];
            var data            = new FormData();
            data.append('action', 'jam_datas' );
            data.append('nonce', nonce );
            data.append('firstName', firstName );
            data.append('lastName', lastName );
            data.append('presentAddress', presentAddress );
            data.append('emailAddress', emailAddress );
            data.append('mobileNo', mobileNo );
            data.append('postName', postName );
            data.append('yourCv', yourCv );
            $.ajax({ 
                url: jam_datas.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: data,
                nonce: nonce,
                processData: false,
                contentType: false, 
                cache: false,
                success: function(data) {
                    if(data.response !== 'success') {                   
                        add_message(data.message, 'danger');
                    }
                    else {
                        add_message(data.message, 'success');
                    };
                }
            })
            
            // Error 
            .fail( function() {
                add_message(data.message ? data.message: 'Sorry! Something went wrong.', 'danger');
            })

            // Reset all fields
            .always( function() {
                event.target.reset();
            });
        
        });
    });
})(jQuery);