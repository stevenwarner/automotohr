$(function () {
	let XHR = null;

	$("#jsFileContainerHomeSection1").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: homeSection1FileObj.sourceType,
		placeholderImage: homeSection1FileObj.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsHomeSection1Form").validate({
		rules: {
			jsMainHeading: {
				required: true,
			},
			jsSubHeading: {
				required: true,
			},
			jsDetails: {
				required: true,
			},
			bullet1: {
				required: true,
			},
			bullet2: {
				required: true,
			},
			bullet3: {
				required: true,
			},
			bullet4: {
				required: true,
			},
			bullet5: {
				required: true,
			},
			bullet6: {
				required: true,
			},
			jsButtonText: {
				required: true,
			},
			jsButtonSlug: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsFileContainerHomeSection1").msFileUploader(
				"get"
			);
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

			return processData(formDataObj);
		},
	});

	//
	function processData(formDataObj) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsHomeSection1Btn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/home/section1/"),
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
						"/?page=home_section_1"
					);
				});
			});
	}
});
