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
		country: { required: true },
		state: { required: true },
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
			required: "Please provide valid phone number.",
		},
		country: {
			required: "Please select a country.",
		},
		state: {
			required: "Please select a state.",
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



	/**
	 *
	 */
	$(".jsScheduleDemoPopup").click(function (event) {
		event.preventDefault();
		$("#jsScheduleDemoModal").modal("show");
	});

	/**
	 *
	 */
	$("#jsScheduleFreeDemoMain").validate({
		rules,
		messages,
		submitHandler: submitFormHandler,
	});

	/**
	 *
	 */
	$("#jsScheduleFreeDemoPopUp").validate({
		rules,
		messages,
		submitHandler: submitFormHandler,
	});

	/**
	 *
	 */
	$("#jsScheduleFreeDemoFooter").validate({
		rules,
		messages,
		submitHandler: submitFormHandler,
	});

	$(".jsCountrySelect").change(function () {
		loadCountryStates($(this).val(), $(this).closest("form").prop("id"));
	});


	//
	$("#jsHighlightsForm").validate({
		rules: {
			wname: {
				required: true,
			},
			wemail: {
				required: true,
			},
		},
		messages: {
			wname: {
				required: "Name is required.",
			},
			wemail: {
				required: "Email is required.",
			},
		},
		submitHandler: submithighlightFormHandler,
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
					window.location.href = baseUrl(window.location.pathname);
				});
			})
			.fail(handleErrorResponse);
	}

	/**
	 * handles the form submission
	 * @param {*} form
	 */
	function loadCountryStates(country, formRef) {
		if (XHR !== null) {
			XHR.abort();
		}
		$("#" + formRef)
			.find('[name="state"]')
			.html("");
		//
		XHR = $.ajax({
			url: baseUrl(
				"states/" + country.replace(/\s+/gi, "_").toLowerCase()
			),
			type: "get",
		})
			.always(function () {
				XHR = null;
			})
			.done(function (resp) {
				let options;

				resp &&
					resp.map(function (value) {
						options +=
							'<option value="' +
							value +
							'">' +
							value +
							"</option>";
					});
				$("#" + formRef)
					.find('[name="state"]')
					.html(options);
			});
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


	//
	function submithighlightFormHandler(form) {
		const dataObj = convertFormToObject(form);
		// check the captcha		
		if (!dataObj["g-recaptcha-response"]) {
			//
			_error("Google captcha is required.");
			return;
		}

		if (XHR !== null) {
			return;
		}

		//
		const ref = callButtonHook($(".jsScheduleHighlightsBtn"), true, true);
		//
		XHR = $.ajax({
			url: baseUrl("schedule_highlights"),
			type: "POST",
			data: dataObj,
		})
			.always(function () {
				XHR = null;
				callButtonHook(ref, false);
			})
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(window.location.pathname);
				});
			})
			.fail(handleErrorResponse);
	}
});
