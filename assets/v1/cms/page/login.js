$(function () {
	let XHR = null;

	// section 0
	$("#jsSection0Form").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			subHeading: {
				required: true,
			},
			buttonText: {
				required: true,
			},
			buttonLink: {
				required: true,
			},
			mainHeadingExecutiveAdmin: {
				required: true,
			},
			buttonTextExecutiveAdmin: {
				required: true,
			},
			buttonLinkExecutiveAdmin: {
				required: true,
			},
			mainHeadingContact: {
				required: true,
			},
			buttonTextContact: {
				required: true,
			},
			buttonLinkContact: {
				required: true,
			},
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_0");
			return processData(formDataObj, $("#jsSection0Btn"), "section_0");
		},
	});

	/**
	 * Updates the data on page
	 * @param {*} formDataObj
	 * @param {*} buttonRef
	 * @param {string} redirectTo
	 * @returns
	 */
	function processData(formDataObj, buttonRef, redirectTo) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/section/"),
			method: "POST",
			data: formDataObj,
			processData: false,
			contentType: false,
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnHook, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
							getSegment(2) +
							"/?page=" +
							redirectTo
					);
				});
			});
	}
});
