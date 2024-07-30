$(function regularPayrollsHoursAndEarnings() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	/**
	 * get the payroll id from segment
	 */
	const payrollId = getSegment(2);

	/**
	 * get the regular payroll
	 */
	function getRegularPayroll() {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/regular/stage/" + payrollId + "/calculating"
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
				if (resp.success) {
					return window.location.refresh();
				}
				setTimeout(getRegularPayroll, 3000);
			});
	}

	ml(false, "jsPageLoader");
	//
	getRegularPayroll();
});
