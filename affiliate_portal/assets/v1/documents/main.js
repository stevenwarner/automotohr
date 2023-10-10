$(function () {
	//
	let fileKey;
	//
	$(".jsViewDocument").click(function (event) {
		//
		event.preventDefault();
		//
		fileKey = $(this).data("key");
		//
		Modal(
			{
				Title: "Preview Document",
				Id: "jsDocumentPreviewModal",
				Loader: "jsDocumentPreviewModalLoader",
				Body: '<div id="jsDocumentPreviewModalBody"></div>',
			},
			generateFileBody
		);
	});

	function generateFileBody() {
		// let's split the file
		const ext = fileKey.substr(fileKey.lastIndexOf(".") + 1).toLowerCase();
		//
		$.ajax({
			url: window.location.origin + "/file/preview",
			method: "POST",
			data: {
				key: fileKey,
				ext: ext,
			},
		})
			.success(function (resp) {
				$("#jsDocumentPreviewModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsDocumentPreviewModalLoader");
			});
	}
});
