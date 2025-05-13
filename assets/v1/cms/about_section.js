$(function () {
	let XHR = null;

	$("#jsAboutSectionFile").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: aboutObj.sourceType,
		placeholderImage: aboutObj.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsAboutSectionForm").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			subHeading: {
				required: true,
			},
			ourMission: {
				required: true,
			},
			ourVision: {
				required: true,
			},
			notableBenefitHeading1: {
				required: true,
			},
			notableBenefitDetail1: {
				required: true,
			},
			notableBenefitHeading2: {
				required: true,
			},
			notableBenefitDetail2: {
				required: true,
			},
			notableBenefitHeading3: {
				required: true,
			},
			notableBenefitDetail3: {
				required: true,
			},
		},
		submitHandler: function (form) {

			const fileObject = $("#jsAboutSectionFile").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			const formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "about");
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
		const btnHook = callButtonHook($(".jsAboutSectionBtn"), true);
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
						"/?page=about_section"
					);
				});
			});
	}
});
