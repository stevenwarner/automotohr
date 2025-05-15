$(function () {
	let XHR = null;

	// section 0
	CKEDITOR.replace("jsSection0Details");

	$("#jsSection0File").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section0.sourceType,
		placeholderImage: section0.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsSection0Form").validate({
		rules: {
			details: {
				required: true,
			},
			buttonText: {
				required: true,
			},
			buttonLink: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsSection0File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_0");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			formDataObj.append(
				"details",
				CKEDITOR.instances.jsSection0Details.getData()
			);

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
