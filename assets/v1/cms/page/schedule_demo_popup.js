$(function () {
	/**
	 * holds the XHR call reference
	 */
	let XHR = null;
	//
	$("#jsSection1Form").validate({
		ignore: [],
		rules: {
			heading: { required: true },
			subHeading: { required: true },
			buttonText: { required: true },
		},
		submitHandler: function (form) {
			// get the form object
			let formDataObj = formArrayToObj($(form).serializeArray());
			// append the section
			formDataObj.append("section", "pageDetails");
			//
			return processData(formDataObj, $(".jsSection1Btn"));
		},
	});

	/**
	 * Updates the data on page
	 * @param {*} formDataObj
	 * @param {*} buttonRef
	 * @returns
	 */
	function processData(formDataObj, buttonRef) {
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
						"manage_admin/edit_page/" + pageId + "/"
					);
				});
			});
	}
});
