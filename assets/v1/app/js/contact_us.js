$(function () {
	$("#jsContactForm").validate({
		ignore: ":hidden:not(select)",
		rules: {
			email: {
				required: true,
				email: true,
			},
			name: {
				required: true,
			},
			message: {
				required: true,
			},
		},
		messages: {
			email: {
				required:
					'<p class="error"><i class="fa fa-exclamation-circle"></i> Your Valid Email is required</p>',
			},
			name: {
				required:
					'<p class="error"><i class="fa fa-exclamation-circle"></i> Your Name is required</p>',
			},
			message: {
				required:
					'<p class="error"><i class="fa fa-exclamation-circle"></i> Your Message is required</p>',
			},
		},
		submitHandler: function (form) {
			// captcha verification
			if ($(form).find("#g-recaptcha-response").val() == "") {
				alertify.alert("Captcha is required.");
                return false;
			}
            $(form).find("button").buttonLoader("start");
			form.submit();
		},
	});
});
