$(function editSchedule() {
	/**
	 * holds xhr call
	 * @var object
	 */
	let XHR = null;

	/**
	 * holds the range picker conf
	 * @var object
	 */
	const configDateRangPicker = {
		showDropdowns: true,
		singleDatePicker: true,
		locale: {
			format: "MM/DD/YYYY",
		},
		minYear: 2023,
		autoApply: true,
	};

	// attach date range picker
	$('[name="first_pay_date"]').daterangepicker(configDateRangPicker);
	$('[name="deadline_to_run_payroll"]').daterangepicker(configDateRangPicker);
	$('[name="first_pay_period_end_date"]').daterangepicker(
		configDateRangPicker
	);

	// apply validator
	const validator = $("#jsEditScheduleForm").validate({
		rules: {
			pay_frequency: { required: true },
			first_pay_date: { required: true, date: true },
			deadline_to_run_payroll: { required: true, date: true },
			first_pay_period_end_date: { required: true },
			status: { required: true },
		},
		submitHandler: function (form) {
			// get the form object
			const formObj = convertFormArrayToObject($(form).serializeArray());
			//
			formObj.id = getSegment(2);
			// convert dates to moment
			// first_pay_date
			const fpd = moment(formObj.first_pay_date, "MM/DD/YYYY");
			// first_pay_period_end_date
			const fped = moment(
				formObj.first_pay_period_end_date,
				"MM/DD/YYYY"
			);
			// check if date is in current year
			if (fpd.format("YYYY") < moment().format("YYYY")) {
				return validator.showErrors({
					first_pay_date: "First pay day cannot be in a prior year.",
				});
			}
			// check if difference is mor than 10 days
			if (fped.diff(fpd, "days") <= -10) {
				return validator.showErrors({
					first_pay_date:
						"The end of the pay period may not be more than 10 days before the pay day.",
				});
			}
			// convert the dates to moment
			return processScheduleProcess(formObj, $(".jsEditScheduleBtn"));
		},
	});

	/**
	 * first pay date change event
	 */
	$('[name="first_pay_date"]').change(function () {
		// check for pay frequency
		if (
			$('[name="pay_frequency"]').val() !== "Every week" &&
			$('[name="pay_frequency"]').val() !== "Every other week"
		) {
			return;
		}
		//
		if (XHR !== null) {
			// check and abort the previous call
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl(
				"schedules/deadline/" +
					moment($(this).val(), "MM/DD/YYYY").format("YYYY-MM-DD")
			),
			method: "get",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$('[name="deadline_to_run_payroll"]').val(
					moment(resp.deadlineDate, "YYYY-MM-DD").format("MM/DD/YYYY")
				);
			});
	});

	/**
	 * frequency change
	 */
	$('[name="pay_frequency"]').change(function () {
		//
		$(".jsFrequency").addClass("hidden");
		$('[name="day_1"]').rules("remove");
		$('[name="day_2"]').rules("remove");
		$('[name="deadline_to_run_payroll"]').rules("add", {
			required: true,
			date: true,
		});
		//
		if ($(this).val() === "Twice a month: Custom") {
			$(".jsFrequency").removeClass("hidden");
			$('[name="day_1"]').rules("add", { required: true });
			$('[name="day_2"]').rules("add", { required: true });
		}
		//
		if ($(this).val() === "Monthly") {
			$(".jsMonthlyFrequency").removeClass("hidden");
			$('[name="day_1"]').rules("add", { required: true });
		}
		//
		if (
			$(this).val() !== "Every week" &&
			$(this).val() !== "Every other week"
		) {
			$('[name="deadline_to_run_payroll"]').val("");
			// $('[name="deadline_to_run_payroll"]').rules("remove");
		} else {
			$('[name="first_pay_date"]').trigger("change");
		}
	});

	/**
	 * convert form array to an object
	 * @param {array} formArray
	 * @returns object
	 */
	function convertFormArrayToObject(formArray) {
		//
		const obj = {};
		//
		formArray?.map(function (v) {
			obj[v.name] = v.value.trim();
		});
		//
		return obj;
	}

	/**
	 * process schedule
	 * @param {object} formObj
	 * @param {object} buttonRef
	 */
	function processScheduleProcess(formObj, buttonRef) {
		// check the XHR object
		if (XHR !== null) {
			return false;
		}
		//
		const buttonHook = callButtonHook(buttonRef, true, true);
		//
		XHR = $.ajax({
			url: baseUrl("schedules/edit/" + getSegment(2)),
			method: "POST",
			data: formObj,
		})
			.always(function () {
				XHR = null;
				callButtonHook(buttonHook, false);
			})
			.fail(function (resp) {
				return _error(resp.responseJSON.msg);
			})
			.done(function (resp) {
				//
				return _success(resp.msg, function () {
					window.location.href = baseUrl("schedules");
				});
			});
	}
});
