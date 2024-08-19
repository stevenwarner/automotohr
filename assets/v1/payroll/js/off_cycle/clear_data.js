$(function () {
	/**
	 * get the payroll id from segment
	 */
	const payrollId = getSegment(2);
	/**
	 * clear draft data
	 */
	$(".jsClearDraftData").click(function (event) {
		event.preventDefault();
		return _confirm(
			"<p><strong>Are you sure you want to clear this draft data?</strong></p><br /><p>Draft data may be present in multiple steps of this draft payroll.</p>",
			clearDraftData
		);
	});

	/**
	 * clear draft data
	 */
	function clearDraftData() {
		//
		ml(
			true,
			"jsPageLoader",
			"Please wait, while we are clearing draft data."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/off-cycle/" + payrollId + "/clear"),
			method: "POST",
			cache: false,
		})
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl("payrolls/off-cycle");
				});
			});
	}
});
