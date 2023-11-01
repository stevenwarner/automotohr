$(function () {
	$("#affiliated-form").validate({
		ignore: [],
		rules: {
			firstname: {
				required: true,
			},
			lastname: {
				required: true,
			},
			email: {
				required: true,
			},
			street: {
				required: true,
			},
			city: {
				required: true,
			},
			state: {
				required: true,
			},
			country: {
				required: true,
			},
			contact_number: {
				required: true,
			},
			terms_and_condition: {
				required: true,
			},
		},
		messages: {
			firstname: {
				required: "First Name is required!",
			},
			lastname: {
				required: "Last Name is required!",
			},
			email: {
				required: "Email is required!",
			},
			street: {
				required: "Street Address is required!",
			},
			city: {
				required: "City is required!",
			},
			state: {
				required: "State / Province is required!",
			},
			country: {
				required: "Country is required!",
			},
			contact_number: {
				required: "Contact number is required!",
			},
			terms_and_condition: {
				required: "Please Agree with our terms and policy!",
			},
		},
		submitHandler: function (form) {
			if (!$(form).find(".g-recaptcha-response").val()) {
				return _error("Please complete the captcha.");
			}
			callButtonHook($(".jsFrmBtn"), true, true);
			form.submit();
		},
	});
});
