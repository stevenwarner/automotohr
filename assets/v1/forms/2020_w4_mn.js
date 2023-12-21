$(function () {
	/**
	 * holds the xhr
	 */
	let xhr = null;

	$('[name="date"]').datepicker({
		format: "mm/dd/yyyy",
		minDate: 0,
		maxDate: 0,
	});

	$("#jsStateFormW4Form").validate({
		rules: {
			first_name: { required: true },
			initial: { required: true, minlength: 1, maxlength: 1 },
			last_name: { required: true },
			ssn: {
				required: true,
				pattern: /\d/g,
				minlength: 9,
				maxlength: 9,
			},
			street_1: { required: true },
			city: { required: true },
			state: { required: true },
			zip_code: {
				required: true,
				minlength: 5,
				maxlength: 5,
				pattern: /\d/g,
			},
			martial_status: { required: true },
			date: { required: true },
			day_time_phone_number: { required: true },
		},
		submitHandler: function (form) {},
	});

	$(".jsLoadSignature").click(function () {

		common_get_e_signature("employee");
	});


});
