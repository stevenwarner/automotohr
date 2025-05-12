$(function () {
	/**
	 * holds the XHR call reference
	 */
	let XHR = null;

	// validate the form
	$("#jsCreatePageForm").validate({
		ignore: [],
		rules: {
			title: { required: true },
			slug: { required: true },
		},
		submitHandler: function (form) {
			// get the form object
			let formDataObj = formArrayToObj($(form).serializeArray());

			return processCreatePage(formDataObj, $(".jsCreatePageBtn"));
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

	/**
	 * Creates a new page
	 * @param {*} formDataObj
	 * @param {*} buttonRef
	 * @returns
	 */
	function processCreatePage(formDataObj, buttonRef) {
		//
		if (XHR !== null) {
			return false;
		}
		const btnHook = callButtonHook(buttonRef, true);


		var isDefault = $('#is_defaultpage').is(':checked');
		//
		XHR = $.ajax({
			url: baseUrl("manage_admin/cms/page/add"),
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
					if (isDefault == true) {
						window.location.href = baseUrl(
							"manage_admin/cms/page/commonedit/" +
							resp.pageId +
							"/?page=meta"
						);
					} else {
						window.location.href = baseUrl(
							"manage_admin/cms/page/edit/" +
							resp.pageId +
							"/?page=meta"
						);
					}
				});
			});
	}
});
