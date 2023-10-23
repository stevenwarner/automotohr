$(function () {
	let XHR = null;
	const rules = {
		name: {
			required: true,
		},
		email: {
			required: true,
		},
		phone_number: {
			required: true,
		},
		company_name: {
			required: true,
		},
		company_size: {
			required: true,
		},
		job_roles: {
			required: true,
		},
	};
	const messages = {
		name: {
			required: "Please provide user name.",
		},
		email: {
			required: "Please provide valid email.",
		},
		phone_number: {
			required: "Please provide valid phone number",
		},
		company_name: {
			required: "Please provide company name.",
		},
		company_size: {
			required: "Please provide employee count.",
		},
		job_roles: {
			required: "Please provide employee count.",
		},
	};
	//
	$("#jsScheduleFreeDemoMain").validate({
		rules,
		messages,
		submitHandler: submitFormHandler,
	});

	/**
	 * handles the form submission
	 * @param {*} form
	 */
	function submitFormHandler(form) {
		const dataObj = convertFormToObject(form);
		// check the captcha
		if (!dataObj["g-recaptcha-response"]) {
			//
			_error("Captcha is required.");
			return;
		}
		if (XHR !== null) {
			return;
		}
		//
		const ref = callButtonHook($(".jsScheduleDemoMainBtn"), true, true);
		//
		XHR = $.ajax({
			url: baseUrl("schedule_free_demo"),
			type: "POST",
			data: dataObj,
		})
			.always(function () {
				XHR = null;
				callButtonHook(ref, false);
			})
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl();
				});
			})
			.fail(handleErrorResponse);
	}

	/**
	 * convert form array to object
	 *
	 * @param {object} form
	 * @return object
	 */
	function convertFormToObject(form) {
		const formToArray = $(form).serializeArray();
		//
		const dataObj = {};
		//
		formToArray.map(function (obj) {
			dataObj[obj.name] = obj.value;
		});
		//
		return dataObj;
	}
});
