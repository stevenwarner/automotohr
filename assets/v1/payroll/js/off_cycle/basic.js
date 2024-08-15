$(function () {
	/**
	 * AJAX call holder
	 */
	let XHR = null;

	/**
	 * holds the reason
	 */
	const reason = getSegment(1);
	/**
	 * select all employees
	 */
	$(".jsSelectAll").click(function () {
		$(".jsSelectSingle").prop("checked", $(this).prop("checked"));
	});

	/**
	 * select single employee
	 */
	$(".jsSelectSingle").click(function () {
		$(".jsSelectAll").prop(
			"checked",
			$(".jsSelectSingle").length === $(".jsSelectSingle:checked").length
		);
	});

	/**
	 * toggle tax view
	 */
	$(".jsToggleTaxView").click(function (event) {
		//
		event.preventDefault();
		//
		$(".jsTaxView").toggleClass("hidden");
	});

	/**
	 * change pay schedule
	 */
	$('[name="payroll[withholding_pay_period]"]').change(setSchedule);

	/**
	 * toggle supplement text
	 */
	$('[name="payroll[fixed_withholding_rate]"]').click(function () {
		if ($(this).val() == "true") {
			$(".jsSelectNonSupplementBox").addClass("hidden");
			$(".jsSelectSupplementBox").removeClass("hidden");
		} else {
			$(".jsSelectNonSupplementBox").removeClass("hidden");
			$(".jsSelectSupplementBox").addClass("hidden");
		}
	});

	/**
	 * saves the process
	 */
	$(".jsOffCycleSave").click(function (event) {
		//
		event.preventDefault();
		//
		let obj = {
			off_cycle_reason:
				$('[name="payroll[off_cycle_reason]"]:checked').val() || "",
			start_date: $('[name="payroll[start_date]"]').val().trim() || "",
			end_date: $('[name="payroll[end_date]"]').val().trim() || "",
			check_date: $('[name="payroll[check_date]"]').val().trim() || "",
			skip_regular_deductions:
				$('[name="payroll[skip_regular_deductions]"]:checked').val() ||
				"",
			withholding_pay_period:
				$(
					'[name="payroll[withholding_pay_period]"] option:selected'
				).val() || "",
			fixed_withholding_rate:
				$('[name="payroll[fixed_withholding_rate]"]:checked').val() ||
				"",
			employees: [],
		};
		//
		$('[name="payroll[employees]"]:checked').map(function () {
			obj.employees.push($(this).val());
		});
		// set error array
		const errorArray = [];
		// validation
		if (!obj.off_cycle_reason) {
			errorArray.push('"Off cycle reason" is missing.');
		}
		if (!obj.start_date) {
			errorArray.push('"Start date" is missing.');
		}
		if (!obj.end_date) {
			errorArray.push('"End date" is missing.');
		}
		if (!obj.check_date) {
			errorArray.push('"Check date" is missing.');
		}
		if (obj.skip_regular_deductions == "") {
			errorArray.push('"Deductions and contributions" is missing.');
		}
		if (!obj.withholding_pay_period) {
			errorArray.push('"Withholding pay period" is missing.');
		}
		if (obj.fixed_withholding_rate == "") {
			errorArray.push('"Withholding pay period" is missing.');
		}
		if (!obj.employees.length) {
			errorArray.push("Please select at least one employee.");
		}
		//
		if (errorArray.length) {
			return _error(getErrorsStringFromArray(errorArray));
		}
		//
		processOffCycle(obj);
	});

	/**
	 * process off cycle payroll
	 * @param {object} data
	 */
	function processOffCycle(data) {
		// check for existing call
		if (XHR !== null) {
			return;
		}
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/off-cycle/basics"),
			method: "POST",
			data,
		})
			.always(function () {
				// flush the call
				XHR = null;
				// hides the loader
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.msg, function () {
					window.location.href = baseUrl(
						`payrolls/${reason}/${resp.id}/hours_and_earnings`
					);
				});
			});
	}

	/**
	 * show schedule
	 */
	function setSchedule() {
		$(".jsShowSchedule").text(
			$('[name="payroll[withholding_pay_period]"] option:selected')
				.val()
				.toLowerCase()
		);
	}

	// set the view
	setSchedule();
	// add date picker
	$(".jsDatePicker").daterangepicker({
		showDropdowns: true,
		singleDatePicker: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
		},
	});
	// hides the loader
	ml(false, "jsPageLoader");
});
