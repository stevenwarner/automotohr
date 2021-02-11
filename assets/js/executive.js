/**
 * Created by Adee on 9/13/2017.
 */
$(document).ready(function() {

    $("#generate-pass").validate({
        rules: {
            password: {
                required: true,
                minlength: 6

            } ,

            cpassword: {
                equalTo: "#password",
                minlength: 6
            }


        },
        messages:{
            password: {
                required:"Password is required of min 6 digits"

            }
        }

    });

});