$(function () {
	let XHR = null;

	$("#jsProcessForm").validate({
		rules: {
			mainheading: {
				required: true,
			},
			heading: {
				required: true,
			},
			details: {
				required: true,
			},
			headingSub1: {
				required: true,
			},
			headingSub2: {
				required: true,
			},
			headingSub3: {
				required: true,
			},
			headingSub4: {
				required: true,
			},
			btnText: {
				required: true,
			},
			btnSlug: {
				required: true,
			},
		},
		submitHandler: function (form) {
			//
			const formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "section9");
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
		const btnHook = callButtonHook($(".jsProcessBtn"), true);
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
							"/?page=process"
					);
				});
			});
	}
});
