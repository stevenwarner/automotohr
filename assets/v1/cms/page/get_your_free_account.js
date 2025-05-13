$(function () {
	let XHR = null;
	// section 1
	$("#jsSection1File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section1.sourceType,
		placeholderImage: section1.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsSection1Form").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			subHeading: {
				required: true,
			},
			details: {
				required: true,
			},
			point1: {
				required: true,
			},
			point2: {
				required: true,
			},
			point3: {
				required: true,
			},
			point4: {
				required: true,
			},
			point5: {
				required: true,
			},
			point6: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsSection1File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_1");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			return processData(formDataObj, $("#jsSection1Btn"), "section_1");
		},
	});

	// section 2
	$("#jsSection2File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section2.sourceType,
		placeholderImage: section2.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsSection2Form").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			subHeading: {
				required: true,
			},
			details: {
				required: true,
			},
			point1: {
				required: true,
			},
			point2: {
				required: true,
			},
			point3: {
				required: true,
			},
			point4: {
				required: true,
			},
			point5: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsSection2File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_2");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			return processData(formDataObj, $("#jsSection2Btn"), "section_2");
		},
	});

	// section 3

	$("#jsSection3File").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section3.file.sourceType,
		placeholderImage: section3.file.sourceFile,
		fileLimit: "200mb",
	});
	$("#jsSection3FileLogo").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section3.logo.sourceType,
		placeholderImage: section3.logo.sourceFile,
		fileLimit: "200mb",
	});
	$("#jsSection3Form").validate({
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
			const fileObject = $("#jsSection3File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			const fileObjectLogo = $("#jsSection3FileLogo").msFileUploader(
				"get"
			);
			//
			if (!isValidFile(fileObjectLogo)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_3");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			//
			if (fileObjectLogo.link !== undefined) {
				formDataObj.append("source_type_logo", fileObjectLogo.type);
				formDataObj.append("source_link_logo", fileObjectLogo.link);
			} else {
				formDataObj.append("source_type_logo", "upload");
				formDataObj.append("file_logo", fileObjectLogo);
			}

			return processData(formDataObj, $("#jsSection3Btn"), "section_3");
		},
	});

	// section 4
	$("#jsSection4File").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section4.file.sourceType,
		placeholderImage: section4.file.sourceFile,
		fileLimit: "200mb",
	});
	$("#jsSection4FileLogo").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section4.logo.sourceType,
		placeholderImage: section4.logo.sourceFile,
		fileLimit: "200mb",
	});
	$("#jsSection4Form").validate({
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
			const fileObject = $("#jsSection4File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			const fileObjectLogo = $("#jsSection4FileLogo").msFileUploader(
				"get"
			);
			//
			if (!isValidFile(fileObjectLogo)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_4");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			//
			if (fileObjectLogo.link !== undefined) {
				formDataObj.append("source_type_logo", fileObjectLogo.type);
				formDataObj.append("source_link_logo", fileObjectLogo.link);
			} else {
				formDataObj.append("source_type_logo", "upload");
				formDataObj.append("file_logo", fileObjectLogo);
			}

			return processData(formDataObj, $("#jsSection4Btn"), "section_4");
		},
	});

	// section 5
	$("#jsSection5File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section5.file.sourceType,
		placeholderImage: section5.file.sourceFile,
		fileLimit: "200mb",
	});
	$("#jsSection5FileLogo").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section5.logo.sourceType,
		placeholderImage: section5.logo.sourceFile,
		fileLimit: "200mb",
	});
	$("#jsSection5Form").validate({
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
			const fileObject = $("#jsSection5File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			const fileObjectLogo = $("#jsSection5FileLogo").msFileUploader(
				"get"
			);
			//
			if (!isValidFile(fileObjectLogo)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_5");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			//
			if (fileObjectLogo.link !== undefined) {
				formDataObj.append("source_type_logo", fileObjectLogo.type);
				formDataObj.append("source_link_logo", fileObjectLogo.link);
			} else {
				formDataObj.append("source_type_logo", "upload");
				formDataObj.append("file_logo", fileObjectLogo);
			}

			return processData(formDataObj, $("#jsSection5Btn"), "section_5");
		},
	});

	// section 6
	$("#jsSection6File").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section6.sourceType,
		placeholderImage: section6.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsSection6Form").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			buttonText: {
				required: true,
			},
			buttonLink: {
				required: true,
			},
			phoneNumber: {
				required: true,
			},
			emailAddress: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsSection6File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_6");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}
			return processData(formDataObj, $("#jsSection6Btn"), "section_6");
		},
	});

	// section 0
	$("#jsSection0File").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section0.sourceType,
		placeholderImage: section0.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsSection0Form").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			buttonText: {
				required: true,
			},
			buttonLink: {
				required: true,
			},
			phoneNumber: {
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
