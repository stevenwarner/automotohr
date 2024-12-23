$(function () {
	let XHR = null;
	let pageId = getSegment(2);

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
