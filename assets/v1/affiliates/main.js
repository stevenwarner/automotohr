$(function () {
	$(".csFileStyle").filestyle({
		text: "Upload File",
		btnClass: "btn-success",
		placeholder: "No file selected",
	});

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
});
