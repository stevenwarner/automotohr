$(function () {
	/**
	 * holds the xhr
	 */
	let XHR = null;

	$('[name="date"]').datepicker({
		format: "mm/dd/yyyy",
		minDate: 0,
		maxDate: 0,
	});

	$("#jsStateFormW4Form").validate({
		rules: {
			first_name: { required: true },
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
			user_consent: { required: true },
		},
		submitHandler: function (form) {
			let signature = $("#is_signature").val();
			//
			if (signature == "false") {
				return alertify.alert(
					"Notice!",
					"The e-Signature is required.",
					CB
				);
			}
			//
			let data = $("#jsStateFormW4Form").serialize();
			//
			saveFormData(data);
		},
	});

	$(".jsLoadSignature").click(function () {
		common_get_e_signature("employee");
	});

	function saveFormData(passOBJ) {
		//
		if (XHR !== null) {
			return false;
		}
		//
		loader(true);
		//
		XHR = $.ajax({
			url: window.location.href,
			method: "POST",
			data: passOBJ,
		})
			.success(function () {
				//
				return alertify.alert(
					"Success!",
					"You have successfully signed the State form.",
					function () {
						//
						window.location.reload();
					}
				);
			})
			.fail(function () {
				//
				return alertify.alert(
					"Error!",
					"Something went wrong, Try again!."
				);
			})
			.always(function () {
				XHR = null;
				loader(false);
			});
	}

	//
	function loader(doShow) {
		//
		if (doShow) {
			$(".my_loader").show(0);
			$(".jsLoaderText").html(
				"Please wait, while we are processing your request."
			);
		} else {
			$(".my_loader").hide(0);
			$(".jsLoaderText").html("");
		}
	}
});
