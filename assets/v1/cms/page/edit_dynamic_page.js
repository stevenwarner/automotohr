$(function () {
	/**
	 * holds the XHR call reference
	 */
	let XHR = null;
	/**
	 * holds the page id
	 */
	let pageId = getSegment(4);

	// page details form
	$("#jsPageDetailsForm").validate({
		ignore: [],
		rules: {
			title: { required: true },
			slug: { required: true },
		},
		submitHandler: function (form) {
			// get the form object
			let formDataObj = formArrayToObj($(form).serializeArray());
			// append the section
			formDataObj.append("section", "main");

			return processPageSection(
				formDataObj,
				$(".jsPageDetailsBtn"),
				"details"
			);
		},
	});

	// meta form
	$("#jsMetaForm").validate({
		ignore: [],
		rules: {
			title: { required: true },
			slug: { required: true },
		},
		submitHandler: function (form) {
			// get the form object
			let formDataObj = formArrayToObj($(form).serializeArray());
			// append the section
			formDataObj.append("section", "meta");
			//
			return processPageSection(formDataObj, $(".jsMetaBtn"), "meta");
		},
	});
	// section 1
	CKEDITOR.replace("jsSection1Details");
	//
	$("#jsSection1Banner").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section1.sourceType,
		placeholderImage: section1.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsSection1Form").validate({
		ignore: [],
		rules: {
			title: { required: true },
			slug: { required: true },
		},
		submitHandler: function (form) {
			// get the form object
			let formDataObj = formArrayToObj($(form).serializeArray());
			// append the section
			formDataObj.append("section", "pageDetails");
			const fileObject = $("#jsSection1Banner").msFileUploader("get");
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

			formDataObj.append(
				"details",
				CKEDITOR.instances.jsSection1Details.getData()
			);
			//
			return processPageSection(
				formDataObj,
				$(".jsSection1Btn"),
				"pageDetails"
			);
		},
	});

	/**
	 * convert string to slug
	 */
	$(".jsToSlug").keyup(function () {
		//
		const to = $(this).data("target");
		//
		$('[name="' + to + '"]').val($(this).val().toSlug());
	});

	$(".jsPageStatus").click(function (event) {
		//
		event.preventDefault();
		//
		const formDataObj = new FormData();
		formDataObj.append("section", "status");
		formDataObj.append("status", $(this).data("key"));
		//
		processPageSection(formDataObj, $(".jsPageStatus"), "pageDetails");
	});

	/**
	 * Creates a new page
	 * @param {*} formDataObj
	 * @param {*} buttonRef
	 * @returns
	 */
	function processPageSection(formDataObj, buttonRef, redirectTo) {
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
						"manage_admin/cms/page/edit/" +
						pageId +
						"/?page=" +
						redirectTo
					);
				});
			});
	}
});
