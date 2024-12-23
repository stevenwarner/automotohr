$(function () {
	let XHR = null;
	let pageId = getSegment(2);

	// section 1
	$("#jsSection1File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section1.sourceType,
		placeholderImage: section1.sourceFile,
		fileLimit: "20mb",
	});
	$("#jsSection1Form").validate({
		rules: {
			mainHeading: { required: true },
			subHeading: { required: true },
			details: { required: true },
			mainHeadingContent: { required: true },
			detailsContent: { required: true },
			buttonText: { required: true },
			buttonLink: { required: true },
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
	$("#jsSection2Form").validate({
		rules: {
			mainHeading: { required: true },
			subHeading: { required: true },
			formHeading: { required: true },
			terms: { required: true },
			buttonText: { required: true },
			buttonLink: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_2");
			//
			const fileObject = $("#jsSection1File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
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
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section3.sourceType,
		placeholderImage: section3.sourceFile,
		fileLimit: "20mb",
	});
	$("#jsSection3Form").validate({
		rules: {
			mainHeading: { required: true },
			details: { required: true },
			point1: { required: true },
			point2: { required: true },
			point3: { required: true },
			point4: { required: true },
			point5: { required: true },
			point6: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_3");
			const fileObject = $("#jsSection3File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			return processData(formDataObj, $("#jsSection3Btn"), "section_3");
		},
	});

	// section 4
	$("#jsSection4File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section4.sourceType,
		placeholderImage: section4.sourceFile,
		fileLimit: "20mb",
	});
	$("#jsSection4Form").validate({
		rules: {
			mainHeading: { required: true },
			details: { required: true },
			point1: { required: true },
			point2: { required: true },
			point3: { required: true },
			point4: { required: true },
			point5: { required: true },
			point6: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_4");
			const fileObject = $("#jsSection4File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}
			return processData(formDataObj, $("#jsSection4Btn"), "section_4");
		},
	});

	// section 5
	$("#jsSection5File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section5.sourceType,
		placeholderImage: section5.sourceFile,
		fileLimit: "20mb",
	});
	$("#jsSection5Form").validate({
		rules: {
			mainHeading: { required: true },
			heading1: { required: true },
			details1: { required: true },
			heading2: { required: true },
			details2: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_5");
			const fileObject = $("#jsSection5File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}
			return processData(formDataObj, $("#jsSection5Btn"), "section_5");
		},
	});

	// section 6
	$("#jsSection6File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section6.sourceType,
		placeholderImage: section6.sourceFile,
		fileLimit: "20mb",
	});
	$("#jsSection6Form").validate({
		rules: {
			mainHeading: { required: true },
			details: { required: true },
			point1: { required: true },
			point2: { required: true },
			point3: { required: true },
			point4: { required: true },
			point5: { required: true },
			point6: { required: true },
			detailsBottom: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_6");
			const fileObject = $("#jsSection6File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
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

	// section 7
	$("#jsSection7File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section7.sourceType,
		placeholderImage: section7.sourceFile,
		fileLimit: "20mb",
	});
	$("#jsSection7Form").validate({
		rules: {
			mainHeading: { required: true },
			heading1: { required: true },
			details1: { required: true },
			heading2: { required: true },
			details2: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_7");
			const fileObject = $("#jsSection7File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}
			return processData(formDataObj, $("#jsSection7Btn"), "section_7");
		},
	});

	// section 8
	$("#jsSection8Form").validate({
		rules: {
			mainHeading: { required: true },
			details: { required: true },
			heading1: { required: true },
			details1: { required: true },
			heading2: { required: true },
			details2: { required: true },
			heading3: { required: true },
			details3: { required: true },
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section_8");
			return processData(formDataObj, $("#jsSection8Btn"), "section_8");
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


	//
	$(".jsPageStatus").click(function (event) {
		//
		event.preventDefault();
		//
		const formDataObj = new FormData();
		formDataObj.append("section", "status");
		formDataObj.append("status", $(this).data("key"));
		//
		processPageSectionStatus(formDataObj, $(".jsPageStatus"), "pageDetails");
	});

	//

	function processPageSectionStatus(formDataObj, buttonRef, redirectTo) {
		//
		if (XHR !== null) {
			return false;
		}
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("manage_admin/cms/page/edit/" + pageId),
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
							pageId 
					);
				});
			});
	}

});
