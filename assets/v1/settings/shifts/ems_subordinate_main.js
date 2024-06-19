/**
 * Manage shifts
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @package Time & Attendance
 * @version 1.0
 */
$(function manageShifts() {
	//
	const mode = getSearchParam("mode") || "month";

	//
	$('[data-toggle="cpopover"]').popover({
		trigger: 'hover click',
		placement: 'left auto',
		html: true
	});

	// apply date picker
	$(".jsWeekDaySelect").daterangepicker({
		opens: "center",
		singleDatePicker: true,
		showDropdowns: true,
		autoApply: true,
		startDate: getStartDate(),
		locale: {
			format: "MM/DD/YYYY",
		},
		isInvalidDate: function (date) {
			// Check if the date is within the week (Monday to Sunday)
			if (mode === "month") {
				return date.format("DD") !== "01";
			} else {
				return date.day() !== 1;
			}
		},
	});
	// capture change event
	$(".jsWeekDaySelect").on("apply.daterangepicker", function (ev, picker) {
		//do something, like clearing an input
		const startDate =
			mode === "month"
				? picker.startDate.clone().startOf("month").format("MM/DD/YYYY")
				: picker.startDate.clone().format("MM/DD/YYYY");
		//
		let incrementDays = 0;

		//
		if (mode === "week") {
			incrementDays = 6;
		} else if (mode === "two_week") {
			incrementDays = 13;
		} else {
			incrementDays = picker.startDate
				.endOf("month")
				.format("MM/DD/YYYY");
		}

		const endDate = picker.startDate
			.add(incrementDays, "days")
			.format("MM/DD/YYYY");

		if (mode == "month") {
			return (window.location.href = baseUrl(
				"shifts/my/subordinates?mode=" +
					mode +
					"&year=" +
					picker.startDate.clone().format("YYYY") +
					"&month=" +
					picker.startDate.clone().format("MM") +
					"&departments=" +
					selectedDepartments +
					"&teams=" +
					selectedTeams
			));
		}

		//
		window.location.href = baseUrl(
			"shifts/my/subordinates?mode=" +
				mode +
				"&start_date=" +
				startDate +
				"&end_date=" +
				endDate +
				"&departments=" +
				selectedDepartments +
				"&teams=" +
				selectedTeams
		);
	});

	// adjust the height of cells
	$(".schedule-employee-row").map(function () {
		$(".schedule-column-" + $(this).data("id")).height($(this).height());
	});

	/**
	 * get the start date
	 * @returns
	 */
	function getStartDate() {
		let startDate = "";

		if (mode === "month") {
			// make date from month and year
			const year = getSearchParam("year") || moment().format("YYYY");
			const month = getSearchParam("month") || moment().format("MM");
			//
			startDate = month + "/01/" + year;
		} else {
			startDate =
				getSearchParam("start_date") ||
				moment().weekday(1).format("MM/DD/YYYY");
		}
		return startDate;
	}

	/**
	 * get the end date
	 *
	 * @param {string} startDate
	 * @returns
	 */
	function getEndDate(startDate) {
		let endDate = "";

		if (mode === "week") {
			endDate =
				getSearchParam("end_date") ||
				moment(startDate, "MM/DD/YYYY")
					.endOf("week")
					.add(1, "day")
					.format("MM/DD/YYYY");
		} else if (mode === "two_week") {
			endDate =
				getSearchParam("end_date") ||
				moment(startDate, "MM/DD/YYYY")
					.add(2, "week")
					.subtract(1, "days")
					.format("MM/DD/YYYY");
		} else {
			// make date from month and year
			const year = getSearchParam("year") || moment().format("YYYY");
			const month = getSearchParam("month") || moment().format("MM");
			//
			endDate = moment(year + "-" + month + "-01")
				.endOf("month")
				.format("MM/DD/YYYY");
		}

		return endDate;
	}

	//
	function applyDatePicker() {
		$("#shift_date_from").daterangepicker({
			opens: "center",
			singleDatePicker: true,
			showDropdowns: true,
			autoApply: true,
			startDate: getStartDate(),
			locale: {
				format: "MM/DD/YYYY",
			},
		});

		$("#shift_date_to").daterangepicker({
			opens: "center",
			singleDatePicker: true,
			showDropdowns: true,
			autoApply: true,
			startDate: getEndDate(getStartDate()),
			locale: {
				format: "MM/DD/YYYY",
			},
		});
	}

	applyDatePicker();

	$(".jsSelect2").select2();

	$(".jsNavigateRightMy").click(function (event) {
		event.preventDefault();
		//

		let filterFields = "";
		const startDate =
			$(".jsWeekDaySelect").data("daterangepicker").startDate;

		// get the end date
		let endDate = "";
		let adder = 1;
		if (mode === "week") {
			endDate = startDate.clone().add(1, "week");
		} else if (mode === "two_week") {
			adder = 2;
			endDate = startDate.clone().add(2, "week");
		} else {
			endDate = startDate.clone().endOf("month");
		}
		//
		if (mode !== "month") {
			//
			return (window.location.href = baseUrl(
				"shifts/my/subordinates?mode=" +
					mode +
					"&start_date=" +
					endDate.clone().format("MM/DD/YYYY") +
					filterFields +
					"&end_date=" +
					endDate
						.clone()
						.add(adder, "week")
						.subtract(1, "day")
						.format("MM/DD/YYYY") +
					"&departments=" +
					selectedDepartments +
					"&teams=" +
					selectedTeams
			));
		} else {
			//
			const startDateObj = endDate.clone().add(1, "month");

			selectedDepartments;
			//
			return (window.location.href = baseUrl(
				"shifts/my/subordinates?mode=" +
					mode +
					"&year=" +
					startDateObj.clone().format("YYYY") +
					"&month=" +
					startDateObj.clone().format("MM") +
					filterFields +
					"&departments=" +
					selectedDepartments +
					"&teams=" +
					selectedTeams
			));
		}
	});

	//
	$(".jsNavigateLeftMy").click(function (event) {
		event.preventDefault();

		let filterFields = "";
		const startDate =
			$(".jsWeekDaySelect").data("daterangepicker").startDate;
		//
		if (mode !== "month") {
			//
			return (window.location.href = baseUrl(
				"shifts/my/subordinates?mode=" +
					mode +
					"&start_date=" +
					startDate
						.clone()
						.subtract(mode === "week" ? 1 : 2, "week")
						.format("MM/DD/YYYY") +
					"&end_date=" +
					startDate.clone().subtract(1, "day").format("MM/DD/YYYY") +
					filterFields +
					"&departments=" +
					selectedDepartments +
					"&teams=" +
					selectedTeams
			));
		} else {
			//
			const startDateObj = startDate.clone().subtract(1, "month");
			//
			return (window.location.href = baseUrl(
				"shifts/my/subordinates?mode=" +
					mode +
					"&year=" +
					startDateObj.clone().format("YYYY") +
					"&month=" +
					startDateObj.clone().format("MM") +
					filterFields +
					"&departments=" +
					selectedDepartments +
					"&teams=" +
					selectedTeams
			));
		}
	});

	// filter
	$(".jsFilterBtn").click(function (event) {
		event.preventDefault();
		//
		$(".jsFilterRow").toggleClass("hidden");
	});

	if (getSearchParam("start_date")) {
		$(".jsFilterBtn").trigger("click");
	}
});
