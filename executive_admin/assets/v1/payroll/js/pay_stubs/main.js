$(function payStubs() {
	// view a pay stub
	$(document).on("click", ".jsViewPayStub", function (event) {
		//
		event.preventDefault();
		//
		const referenceId = $(this).closest("tr").data("key");
		//
		Modal(
			{
				Id: "jsPayStubModal",
				Loader: "jsPayStubModalLoader",
				Title: "Pay stub - view",
				Body: '<div id="jsPayStubModalBody"></div>',
			},
			function () {
				//
				viewPayStub(referenceId);
			}
		);
	});

	// download a pay stub
	$(document).on("click", ".jsDownloadPayStub", function (event) {
		//
		event.preventDefault();
		//
		window.location.href = baseUrl(`payrolls/pay-stubs/${$(this).data("key")}/download`);
	});

	/**
	 * view a pay stub
	 * @param {int} referenceId
	 * @returns
	 */
	function viewPayStub(referenceId) {
		//
		ml(
			true,
			"jsPayStubModalLoader",
			"Please wait, while we are generating a preview."
		);
		//
		$.ajax({
			url: baseUrl("payrolls/pay-stubs/" + referenceId + "/view"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsPayStubModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				ml(false, "jsPayStubModalLoader");
			});
	}
	//
	ml(false, "jsPageLoader");
});
