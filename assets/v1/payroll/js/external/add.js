$(function addExternalPayrolls() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	//
	$(".jsExternalPayrollCreateBtn").click(function (event) {
		//
		event.preventDefault();
		//
		const dataOBJ = {
			check_date: $("#jsExternalPayrollCheckDate").val().trim(),
			payment_period_start_date: $("#jsExternalPayrollPayrollPeriodStart")
				.val()
				.trim(),
			payment_period_end_date: $("#jsExternalPayrollPayrollPeriodEnd")
				.val()
				.trim(),
		};
		// set errors array
		const errorsArray = [];
		// validation
		if (!dataOBJ.check_date) {
			errorsArray.push('"Check date" is required.');
		}
		if (!dataOBJ.payment_period_start_date) {
			errorsArray.push('"Payment period start date" is required.');
		}
		if (!dataOBJ.payment_period_end_date) {
			errorsArray.push('"Payment period end date" is required.');
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		startProcessOfExternalPayroll(dataOBJ);
	});

	/**
	 * start the process of external payroll creation
	 * @param {object} dataOBJ
	 */
	function startProcessOfExternalPayroll(dataOBJ) {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(true, "jsPageLoader");
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/external/create"),
			method: "POST",
			data: dataOBJ,
		})
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.message, function () {
					window.location.href = baseUrl("payrolls/external");
				});
			});
	}

	//
	$(".jsDatePicker").daterangepicker({
		showDropdowns: true,
		singleDatePicker: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
		},
	});
	//
	ml(false, "jsPageLoader");
});
