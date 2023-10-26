$(function () {
	let XHR = null;
	const rules = {
		name: {
			required: true,
		},
		email: {
			required: true,
		},
		message: {
			required: true,
			minlength: 50,
		},
	};
	const messages = {
		name: {
			required: "Please provide user name.",
		},
		email: {
			required: "Please provide valid email.",
		},
		message: {
			required: "Please provide a message",
			minlength: "Message must be minimum of 50 characters long.",
		},
	};

	/**
	 *
	 */
	$("#jsContactForm").validate({
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
		const ref = callButtonHook($(".jsContactBtn"), true, true);
		//
		XHR = $.ajax({
			url: baseUrl("contact-us"),
			type: "POST",
			data: dataObj,
		})
			.always(function () {
				XHR = null;
				callButtonHook(ref, false);
			})
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl("contact-us");
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
