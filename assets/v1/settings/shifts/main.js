/**
 * Manage shifts
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @package Time & Attendance
 * @version 1.0
 */
$(function manageShifts() {
	/**
	 * XHR holder
	 */
	let XHR = null;

	/**
	 * XHR validator
	 */
	let validatorRef = null;

	/**
	 * holds the modal page id
	 */
	const modalId = "jsModalPage";
	const modalLoader = modalId + "Loader";
	const modalBody = modalId + "Body";

	//
	const mode = getSearchParam("mode") || "month";

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
				"settings/shifts/manage?mode=" +
					mode +
					"&year=" +
					picker.startDate.clone().format("YYYY") +
					"&month=" +
					picker.startDate.clone().format("MM")
			));
		}

		//
		window.location.href = baseUrl(
			"settings/shifts/manage?mode=" +
				mode +
				"&start_date=" +
				startDate +
				"&end_date=" +
				endDate
		);
	});
	// navigate to previous dates
	$(".jsNavigateLeft").click(function (event) {
		event.preventDefault();

		const startDate =
			$(".jsWeekDaySelect").data("daterangepicker").startDate;
		//
		if (mode !== "month") {
			//
			return (window.location.href = baseUrl(
				"settings/shifts/manage?mode=" +
					mode +
					"&start_date=" +
					startDate
						.clone()
						.subtract(mode === "week" ? 1 : 2, "week")
						.format("MM/DD/YYYY") +
					"&end_date=" +
					startDate.clone().subtract(1, "day").format("MM/DD/YYYY")
			));
		} else {
			//
			const startDateObj = startDate.clone().subtract(1, "month");
			//
			return (window.location.href = baseUrl(
				"settings/shifts/manage?mode=" +
					mode +
					"&year=" +
					startDateObj.clone().format("YYYY") +
					"&month=" +
					startDateObj.clone().format("MM")
			));
		}
	});
	// navigate to future dates
	$(".jsNavigateRight").click(function (event) {
		event.preventDefault();

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
				"settings/shifts/manage?mode=" +
					mode +
					"&start_date=" +
					endDate.clone().format("MM/DD/YYYY") +
					"&end_date=" +
					endDate
						.clone()
						.add(adder, "week")
						.subtract(1, "day")
						.format("MM/DD/YYYY")
			));
		} else {
			//
			const startDateObj = endDate.clone().add(1, "month");
			//
			return (window.location.href = baseUrl(
				"settings/shifts/manage?mode=" +
					mode +
					"&year=" +
					startDateObj.clone().format("YYYY") +
					"&month=" +
					startDateObj.clone().format("MM")
			));
		}
	});

	// adjust the height of cells
	$(".schedule-employee-row").map(function () {
		$(".schedule-column-" + $(this).data("id")).height($(this).height());
	});

	/**
	 * capture click event on cell
	 */
	$(".schedule-column-clickable").click(function (event) {
		// prevent the event from happening
		event.preventDefault();
		//
		callToCreateBox(
			$(this).data("eid"),
			$(this).closest(".schedule-column-container").data("date")
		);
	});

	/**
	 * Apply template
	 */
	$(".jsApplyTemplate").click(function (event) {
		event.preventDefault();

		makePage(
			"Apply shift template",
			"apply_shift_templates",
			0,
			function () {
				// hides the loader
				ml(false, modalLoader);

				$(".jsSelectScheduleTemplate").click(function (event) {
					event.preventDefault();

					$(".jsSelectScheduleTemplate").removeClass("active");
					$(this).addClass("active");
				});

				$(".jsSelectAll").click(function (event) {
					event.preventDefault();
					$(".jsPageApplyTemplateEmployees").prop("checked", true);
				});
				$(".jsRemoveAll").click(function (event) {
					event.preventDefault();
					$(".jsPageApplyTemplateEmployees").prop("checked", false);
				});

				$("#jsPageApplyShiftTemplateForm").submit(function (event) {
					event.preventDefault();

					const passObj = {
						start_date: getStartDate(),
						schedule_id: $(".jsSelectScheduleTemplate.active").data(
							"id"
						),
						employees: [],
					};

					passObj.end_date = getEndDate(passObj.start_date);
					//
					$(".jsPageApplyTemplateEmployees:checked").map(function () {
						passObj.employees.push($(this).val());
					});

					if (!passObj.schedule_id) {
						return _error("Please select a schedule.");
					}

					if (!passObj.employees.length) {
						return _error("Please select at least one employee.");
					}

					processCall(
						passObj,
						$(".jsPageApplyShiftTemplateBtn"),
						"settings/shifts/template/apply",
						function (resp) {
							//
							let html = "";
							// check the keys
							if (Object.keys(resp.list).length > 0) {
								//
								$.each(resp.list, function (i, v) {
									html += "<p>";
									html += $(
										'.jsPageApplyTemplateEmployees[value="' +
											i +
											'"]'
									)
										.parent()
										.text()
										.trim();
									html +=
										" has already shift on the following dates;";
									html += "</p>";
									v.dates.map(function (v0) {
										html +=
											"<span>" +
											moment(v0, "YYYY-MM-DD").format(
												"MM/DD/YYYY"
											) +
											"</span>, ";
									});
									html += "<br />";
									html += "<br />";
								});
							}
							// show the message
							_success(resp.msg + html, function () {
								window.location.href = baseUrl(
									"settings/shifts/manage" +
										window.location.search
								);
							});
						}
					);
				});
			}
		);
	});

	/**
	 * generates the modal
	 * @param {string} pageTitle
	 * @param {string} pageSlug
	 * @param {number} pageId
	 * @param {function} cb
	 */
	function makePage(pageTitle, pageSlug, pageId, cb) {
		Modal(
			{
				Id: modalId,
				Title: pageTitle,
				Body: '<div id="' + modalBody + '"></div>',
				Loader: modalLoader,
			},
			function () {
				fetchPage(pageSlug, pageId, cb);
			}
		);
	}

	/**
	 * fetch page from server
	 * @param {string} pageSlug
	 * @param {function} cb
	 */
	function fetchPage(pageSlug, pageId, cb) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			XHR.abort();
		}
		// make a new call
		XHR = $.ajax({
			url: baseUrl("settings/page/" + pageSlug + "/" + pageId),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				// load the new body
				$("#" + modalBody).html(resp.view);
				// call the callback
				cb(resp);
			});
	}

	/**
	 * process the call
	 * @param {object} formObj
	 * @param {string} buttonRef
	 * @param {string} url
	 * @param {Object} cb
	 */
	function processCall(formObj, buttonRef, url, cb) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			return;
		}
		//
		const btnRef = callButtonHook(buttonRef, true);
		// make a new call
		XHR = $.ajax({
			url: baseUrl(url),
			method: "POST",
			data: formObj,
			processData: true,
		})
			.always(function () {
				//
				callButtonHook(btnRef, false);
				//
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				//
				validatorRef?.destroy();
				return cb(resp);
			});
	}

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

	function callToCreateBox(employeeId, date) {
		alert(employeeId + "  " + date);
	}
});
