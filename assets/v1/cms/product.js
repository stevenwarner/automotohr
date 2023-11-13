$(function () {
	let XHR = null;

	$("#jsProductSliderForm").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			details: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "banner");
			return processData(formDataObj, $(".jsProductSliderBtn"), "slider");
		},
	});

	$("#jsProductAboutFile").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: aboutObj.sourceType,
		placeholderImage: aboutObj.sourceFile,
		fileLimit: "10mb",
	});

	$("#jsProductAboutForm").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			subHeading: {
				required: true,
			},
			heading: {
				required: true,
			},
			details: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsProductAboutFile").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			const formDataObj = formArrayToObj($(form).serializeArray());
			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}
			formDataObj.append("section", "about");
			return processData(formDataObj, $(".jsProductAboutBtn"), "about");
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
