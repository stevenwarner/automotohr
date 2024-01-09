$(function () {
	let XHR = null;
	// validation
	$("#jsMetaForm").validate({
		ignore: [],
		rules: {
			meta_title: {
				required: true,
			},
			meta_description: {
				required: true,
			},
			meta_keywords: {
				required: true,
			},
		},
		messages: {
			meta_title: {
				minlength: "The meta title can be between 50 to 60 characters.",
			},
			meta_description: {
				minlength:
					"The meta description can be between 50 to 160 characters.",
			},
		},
		submitHandler: function (form) {
			return processMeta(form);
		},
	});

	function processMeta(form) {
		//
		if (XHR !== null) {
			return false;
		}
		//
		const formObj = formArrayToObj($(form).serializeArray());
		const pageId = getSegment(2);
		const btnHook = callButtonHook($("#jsMetaBtn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/meta"),
			method: "POST",
			data: formObj,
			processData: false,
			contentType: false
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
							"/?page=meta"
					);
				});
			});
	}
});
