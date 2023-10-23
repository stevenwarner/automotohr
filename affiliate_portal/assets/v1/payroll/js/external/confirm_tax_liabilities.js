$(function externalPayrollsConfirmTaxLiabilities() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	//
	$(".jsTaxLiabilitiesConfirmSaveBtn").click(function (event) {
		//
		event.preventDefault();
		return _confirm(
			"<p><strong>You will not be able to change any external payroll data after you confirm.</strong></p><br /><p>Do you want to proceed?</p>",
			confirmTaxLiabilities
		);
	});

	/**
	 * confirm external payroll tax liabilities
	 * @returns
	 */
	function confirmTaxLiabilities() {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(true, "jsPageLoader");
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/external/tax-liabilities/confirm"),
			method: "PUT",
			cache: false,
		})
			.success(function (resp) {
				//
				return _success(resp.message, function () {
					window.location.href = baseUrl("payrolls/external");
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			});
	}

	//
	ml(false, "jsPageLoader");
});
