$(function externalPayrollsTaxLiabilities() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	//
	$(".jsTaxLiabilitiesSaveBtn").click(function (event) {
		//
		event.preventDefault();
		//
		const dataOBJ = [];
		//
		$(".jsTaxLiabilitySelect").map(function () {
			//
			dataOBJ.push({
				tax_id: $(this).data("id"),
				last_unpaid_external_payroll_uuid: $(this)
					.find("option:selected")
					.data("uuid"),
				unpaid_liability_amount: $(this).find("option:selected").val(),
			});
		});
		//
		updateTaxLiabilities(dataOBJ);
	});

	/**
	 * update external payroll tax liabilities
	 * @param {array} dataOBJ
	 * @returns
	 */
	function updateTaxLiabilities(dataOBJ) {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(true, "jsPageLoader");
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/external/tax-liabilities"),
			method: "POST",
			data: {tax_liabilities: dataOBJ},
			cache: false,
		})
			.success(function (resp) {
				//
				return _success(resp.message, function () {
					window.location.href = baseUrl(
						"payrolls/external/tax-liabilities/confirm"
					);
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
