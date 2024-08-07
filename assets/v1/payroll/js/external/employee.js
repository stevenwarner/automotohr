$(function externalPayrollsEmployee() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	/**
	 * external payroll id
	 */
	const externalPayrollId = parseInt(getSegment(2));
	/**
	 * employee id
	 */
	const employeeId = parseInt(getSegment(3));
	//
	$(".jsExternalPayrollCalculateTaxesBtn").click(function (event) {
		//
		event.preventDefault();
		saveExternalPayroll(true);
	});

	//
	$(".jsExternalPayrollSaveBtn").click(function (event) {
		//
		event.preventDefault();
		//
		saveExternalPayroll();
	});

	/**
	 * save employee external payroll
	 * @param {bool} doCalculate
	 * @returns
	 */
	function saveExternalPayroll(doCalculate = false) {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		// lets create array
		const dataOBJ = {
			applicable_earnings: [],
			applicable_benefits: [],
			applicable_taxes: [],
		};
		// get the earnings
		$(".jsExternalPayrollApplicableInputs").map(function () {
			dataOBJ["applicable_earnings"].push({
				id: $(this).data("id"),
				inputType: $(this).data("input"),
				type: $(this).data("type"),
				value: $(this).val().trim() || 0.0,
			});
		});
		// get the taxes
		$(".jsExternalPayrollTaxInputs").map(function () {
			dataOBJ["applicable_taxes"].push({
				id: $(this).data("id"),
				value: $(this).val().trim() || 0.0,
			});
		});
		//
		ml(true, "jsPageLoader");
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/external/" + externalPayrollId + "/" + employeeId
			),
			method: "POST",
			data: dataOBJ,
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
				//
				if (doCalculate) {
					ml(true, "jsPageLoader");
					setTimeout(function () {
						calculateEmployeeExternalPayrollTaxes();
					}, 1000);
				} else {
					return _success(resp.message, function () {
						window.location.reload();
					});
				}
			});
	}

	/**
	 * calculate employee external payroll taxes
	 * @returns
	 */
	function calculateEmployeeExternalPayrollTaxes() {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(true, "jsPageLoader");
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/external/" +
					externalPayrollId +
					"/" +
					employeeId +
					"/calculates_taxes"
			),
			method: "GET",
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
				//
				resp.data.map(function (taxSuggestion) {
					$(
						'.jsExternalPayrollTaxInputs[data-id="' +
							taxSuggestion.tax_id +
							'"]'
					).val(taxSuggestion.amount);
				});
				return _success(resp.message);
			});
	}
	//
	ml(false, "jsPageLoader");
});
