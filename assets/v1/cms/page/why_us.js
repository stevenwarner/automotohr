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
			mainHeading: {
				required: true,
			},
			subHeading: {
				required: true,
			},
			details: {
				required: true,
			},
			buttonSlug: {
				required: true,
			},
			buttonText: {
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

	// section 8
	$("#jsSection8File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section8.sourceType,
		placeholderImage: section8.sourceFile,
		fileLimit: "20mb",
	});

	$("#jsSection8Form").validate({
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
			buttonSlug: {
				required: true,
			},
			buttonText: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsSection8File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_8");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			return processData(formDataObj, $("#jsSection8Btn"), "section_8");
		},
	});

	addTriggers("jsSection2", section2, section2Points, "section_2");
	addTriggers("jsSection3", section3, section3Points, "section_3");
	addTriggers("jsSection4", section4, section4Points, "section_4");
	addTriggers("jsSection5", section5, section5Points, "section_5");
	addTriggers("jsSection6", section6, section6Points, "section_6", 2);
	addTriggers("jsSection7", section7, section7Points, "section_7", 2);

	/**
	 * dynamic create page content
	 * @param {*} key
	 * @param {*} sectionFile
	 * @param {*} sectionPoints
	 * @param {*} section
	 */
	function addTriggers(
		key,
		sectionFile,
		sectionPoints,
		section,
		fileSize = 3
	) {
		// section 3
		$("#" + key + "File").msFileUploader({
			allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
			allowLinks: true,
			activeLink: sectionFile.sourceType,
			placeholderImage: sectionFile.sourceFile,
			fileLimit: "20mb",
		});

		$.each(sectionPoints, function (i, v) {
			// Point 1
			$("#" + i).msFileUploader({
				allowedTypes: ["jpg", "jpeg", "png", "webp"],
				allowLinks: false,
				activeLink: v.sourceType,
				placeholderImage: v.sourceFile,
				fileLimit: "20mb",
			});
		});

		$("#" + key + "Form").validate({
			rules: {
				mainHeading: {
					required: true,
				},
				headingPoint1: {
					required: true,
				},
				detailsPoint1: {
					required: true,
				},

				headingPoint2: {
					required: true,
				},
				detailsPoint2: {
					required: true,
				},

				headingPoint3: {
					required: true,
				},
				detailsPoint3: {
					required: true,
				},
			},
			submitHandler: function (form) {
				const fileObject = $("#" + key + "File").msFileUploader("get");
				//
				if (!isValidFile(fileObject)) {
					return _error("Please select a valid source.");
				}

				//
				let formDataObj = formArrayToObj($(form).serializeArray());
				formDataObj.append("section", section);

				if (fileSize >= 1) {
					const pointFile1 = $(
						"#" + key + "Point1File"
					).msFileUploader("get");
					//
					if (!isValidFile(pointFile1)) {
						return _error(
							"Please select a valid source for point 1."
						);
					}
					// point 1
					if (pointFile1.link !== undefined) {
						formDataObj.append(
							"source_type_point_1",
							pointFile1.type
						);
						formDataObj.append(
							"source_link_point_1",
							pointFile1.link
						);
					} else {
						formDataObj.append("source_type_point_1", "upload");
						formDataObj.append("file_point_1", pointFile1);
					}
				}

				if (fileSize >= 2) {
					const pointFile2 = $(
						"#" + key + "Point2File"
					).msFileUploader("get");
					//
					if (!isValidFile(pointFile2)) {
						return _error(
							"Please select a valid source for point 2."
						);
					}
					// point 2
					if (pointFile2.link !== undefined) {
						formDataObj.append(
							"source_type_point_2",
							pointFile2.type
						);
						formDataObj.append(
							"source_link_point_2",
							pointFile2.link
						);
					} else {
						formDataObj.append("source_type_point_2", "upload");
						formDataObj.append("file_point_2", pointFile2);
					}
				}

				if (fileSize >= 3) {
					const pointFile3 = $(
						"#" + key + "Point3File"
					).msFileUploader("get");
					//
					if (!isValidFile(pointFile3)) {
						return _error(
							"Please select a valid source for point 3."
						);
					}
					// point 3
					if (pointFile3.link !== undefined) {
						formDataObj.append(
							"source_type_point_3",
							pointFile3.type
						);
						formDataObj.append(
							"source_link_point_3",
							pointFile3.link
						);
					} else {
						formDataObj.append("source_type_point_3", "upload");
						formDataObj.append("file_point_3", pointFile3);
					}
				}

				//
				if (fileObject.link !== undefined) {
					formDataObj.append("source_type", fileObject.type);
					formDataObj.append("source_link", fileObject.link);
				} else {
					formDataObj.append("source_type", "upload");
					formDataObj.append("file", fileObject);
				}

				return processData(formDataObj, $("#" + key + "Btn"), section);
			},
		});
	}

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
