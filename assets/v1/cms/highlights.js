$(function () {
	let XHR = null;

	$("#jsHighlightsForm").validate({
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
		},
		submitHandler: function (form) {
			//
			const formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "specialhighlights");
			return highlightsData(formDataObj);
		},
	});

	//
	function highlightsData(formDataObj) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsHighlightsBtn"), true);
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
							"/?page=highlights"
					);
				});
			});
	}
});
