$(function(){
    $("#change_password_form").validate({
		ignore: ":hidden:not(select)",
		rules: {
			password: {
				required: true,
			},
			retypepassword: {
				required: true,
				equalTo: "#password",
			},
		},
		messages: {
			password: {
				required:
					'<p class="error_message"><i class="fa fa-exclamation-circle"></i>Password is required</p>',
			},
			retypepassword: {
				required:
					'<p class="error_message"><i class="fa fa-exclamation-circle"></i>Confirm Password is required</p>',
				equalTo:
					'<p class="error_message"><i class="fa fa-exclamation-circle"></i>Confirm Password does not match</p>',
			},
		},
		submitHandler: function (form) {
			form.submit();
		},
	});
})