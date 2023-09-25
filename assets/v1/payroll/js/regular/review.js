$(function regularPayrollsTimeOff() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	/**
	 * get the payroll id from segment
	 */
	const payrollId = getSegment(2);
	// save payroll
	$(document).on("click", ".jsRegularPayrollStep2Save", function (event) {
		//
		event.preventDefault();
		//
		$(".jsRegularPayrollEmployeeRowTimeOff").map(function () {
			//
			const employeeId = $(this).data("id");
			//
			$(this)
				.find(".jsTimeOffField")
				.map(function () {
					// set the proper amount
					payroll["employees"][employeeId]["fixed_compensations"][
						$(this).prop("name")
					]["amount"] = parseFloat($(this).val().trim() || 0);
				});
		});
		//
		ml(true, "jsPageLoader", "Please wait, while we are saving data.");
		//
		if (XHR !== null) {
			return;
		}
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/regular/" + payrollId + "/save/1"),
			method: "POST",
			data: {
				payrollId,
				action: "Time Off",
				employees: payroll.employees,
			},
			cache: false,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					ml(
						true,
						"jsPageLoader",
						"Please wait while we are generating a view."
					);
					window.location.href = baseUrl(
						"payrolls/regular/" + payrollId + "/review"
					);
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			});
	});

	// toggle between hours and pay view
	$(document).on("click", ".jsToggleHoursPay", function (event) {
		//
		event.preventDefault();
		//
		$(".jsToggleHoursPay").removeClass("active");
		$(this).addClass("active");
		$('table[data-key="hours"]').addClass("hidden");
		$('table[data-key="pay"]').addClass("hidden");
		$('table[data-key="' + $(this).data("target") + '"]').removeClass(
			"hidden"
		);
	});

	// submit payroll
	$(document).on("click", ".jsSubmitPayroll", function (event) {
		//
		event.preventDefault();
		//
		submitPayroll();
	});

	/**
	 * get the regular payroll
	 */
	function getRegularPayrollReview() {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(
			true,
			"jsPageLoader",
			"Please wait, while we are generating a view. This might take a few minutes."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/regular/" + payrollId + "/view/3"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$(".jsContentArea").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			});
	}

	/**
	 * submit regular payroll
	 */
	function submitPayroll() {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(
			true,
			"jsPageLoader",
			"Please wait, while we are submitting the regular payroll. This might takes a few minutes."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/regular/" + payrollId + "/submit"),
			method: "PUT",
			cache: false,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(
						"payrolls/history/" + payrollId
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
	getRegularPayrollReview();
});
