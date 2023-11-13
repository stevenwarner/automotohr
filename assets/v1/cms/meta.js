$(function () {
	let XHR = null;
	// validation
	$("#jsMetaForm").validate({
		ignore: [],
		rules: {
			meta_title: {
				required: true,
				minlength: 50,
			},
			meta_description: {
				required: true,
				minlength: 50,
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
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnHook, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg);
			});
	}
});
