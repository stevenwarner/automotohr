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
		$('[name="payroll[employees][]"]:checked').map(function () {
			obj.employees.push($(this).val());
		});
		// set error array
		const errorArray = [];
		// validation
		if (!obj.off_cycle_reason) {
			errorArray.push('"Off cycle reason date" is missing.');
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
		if (!obj.skip_regular_deductions) {
			errorArray.push('"Deductions and contributions" is missing.');
		}
		if (!obj.skip_regular_deductions) {
			errorArray.push('"Deductions and contributions" is missing.');
		}

		console.log(obj);
		// validation
		// if ($(".jsSelectSingle:checked").length === 0) {
		// 	return _error(
		// 		getErrorsStringFromArray([
		// 			"Please select at least one employee.",
		// 		])
		// 	);
		// }
		// //
		// const employeeIds = [];
		// // get all selected employees
		// $(".jsSelectSingle:checked").map(function () {
		// 	employeeIds.push($(this).val());
		// });
		// //
		// saveEmployees(employeeIds);
	});

	/**
	 * saves the employees and start he off cycle process
	 * @param {number} employeeIds
	 */
	function saveEmployees(employeeIds) {
		// check for existing call
		if (XHR !== null) {
			return;
		}
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/off-cycle/employees"),
			method: "POST",
			data: {
				employees: employeeIds,
				reason: reason,
			},
		})
			.success(function () {
				window.location.href = baseUrl(`payrolls/${reason}/basic`);
			})
			.fail(handleErrorResponse)
			.always(function () {
				// flush the call
				XHR = null;
				// hides the loader
				ml(false, "jsPageLoader");
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
	$(".jsDatePicker").datepicker({
		format: "mm/dd/yyyy",
		changeYear: true,
		changeMonth: true,
	});
	// hides the loader
	ml(false, "jsPageLoader");
});
