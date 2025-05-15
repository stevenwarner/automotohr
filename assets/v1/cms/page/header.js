$(function () {
	let XHR = null;

	$("#jsSection0File").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section0.sourceType,
		placeholderImage: section0.sourceFile,
		fileLimit: "200mb",
	});

	// section 0
	$("#jsSection0Form").validate({
		rules: {},
		submitHandler: function (form) {
			const fileObject = $("#jsSection0File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}
			formDataObj.append("section", "section_0");
			return processData(formDataObj, $("#jsSection0Btn"), "section_0");
		},
	});

	// section 1
	$("#jsSection1Form").validate({
		rules: {
			menu1Text: { required: true },
			menu1Link: { required: true },

			menu2Text: { required: true },
			menu2Link: { required: true },

			menu3Text: { required: true },
			menu3Link: { required: true },

			menu4Text: { required: true },
			menu4Link: { required: true },

			menu5Text: { required: true },
			menu5Link: { required: true },

			subMenu1Text: { required: true },
			subMenu1Details: { required: true },
			subMenu1Link: { required: true },

			subMenu2Text: { required: true },
			subMenu2Details: { required: true },
			subMenu2Link: { required: true },

			subMenu3Text: { required: true },
			subMenu3Details: { required: true },
			subMenu3Link: { required: true },

			subMenu4Text: { required: true },
			subMenu4Details: { required: true },
			subMenu4Link: { required: true },

			subMenu5Text: { required: true },
			subMenu5Details: { required: true },
			subMenu5Link: { required: true },

			subMenu6Text: { required: true },
			subMenu6Details: { required: true },
			subMenu6Link: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_1");
			return processData(formDataObj, $("#jsSection1Btn"), "section_1");
		},
	});

	// section 2
	$("#jsSection2Form").validate({
		rules: {
			buttonTextSchedule: { required: true },
			buttonLinkSchedule: { required: true },

			buttonTextLogin: { required: true },
			buttonLinkLogin: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_2");
			return processData(formDataObj, $("#jsSection2Btn"), "section_2");
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
