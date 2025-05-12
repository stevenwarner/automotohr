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
		const btnHook = callButtonHook($("#jsMetaBtn"), true);
		//
		var pageId = getSegment(2);

		if (getSegment(3) == 'commonedit') {
			pageId = getSegment(4);

		}

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

					if (getSegment(3) == 'commonedit') {
						window.location.href = baseUrl(
							"manage_admin/cms/page/commonedit/" +
							getSegment(4)+"/?page=meta");
							} else {

						window.location.href = baseUrl(
							"manage_admin/edit_page/" +
							getSegment(2) +
							"/?page=meta"
						);
					}


				});
			});
	}
});
