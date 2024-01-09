$(function () {
	$(".csFileStyle").filestyle({
		text: "Upload File",
		btnClass: "btn-success",
		placeholder: "No file selected",
	});

	//
	let XHR = null;
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
			w8_form: {
				extension: "docx|rtf|doc|pdf|PDF",
			},
			w9_form: {
				extension: "docx|rtf|doc|pdf|PDF",
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
			w8_form: {
				extension: "Only .doc, .docx, and .pdf files are allowed.",
			},
			w9_form: {
				extension: "Only .doc, .docx, and .pdf files are allowed.",
			},
		},
		submitHandler: function (form) {
		},
		submitHandler: function (form) {
			if (!$(form).find(".g-recaptcha-response").val()) {
				return _error("Please complete the captcha.");
			}
			callButtonHook($(".jsFrmBtn"), true, true);
			form.submit();
		},
	});

	$(".chosen-select").selectize({
		plugins: ["remove_button"],
		delimiter: ",",
		persist: true,
		create: function (input) {
			return {
				value: input,
				text: input,
			};
		},
	});
	$("[target]").click(function () {
		const $href = $(this).attr("target");
		const $anchor = $("#" + $href).offset();
		window.scrollTo($anchor.left, $anchor.top);
		return false;
	});

	/**
	 * terms of service popup
	 */
	$(".jsTermsOfServicePopUp").click(function (event) {
		//
		event.preventDefault();
		if (XHR !== null) {
			return;
		}
		//
		XHR = $.ajax({
			url: baseUrl("popup/terms_of_service"),
			method: "get",
		})
			.always(function () {
				XHR = null;
			})
			.done(function (resp) {
				showModal(resp.view);
			});
	});

	/**
	 * privacy policy popup
	 */
	$(".jsPrivacyPolicyPopUp").click(function (event) {
		//
		event.preventDefault();
		if (XHR !== null) {
			return;
		}
		//
		XHR = $.ajax({
			url: baseUrl("popup/privacy_policy"),
			method: "get",
		})
			.always(function () {
				XHR = null;
			})
			.done(function (resp) {
				showModal(resp.view);
			});
	});

	function showModal(modalView) {
		$("#jsPopUpModal").remove();
		$("body").append(modalView);
		$("#jsPopUpModal").modal("show");
	}
});
