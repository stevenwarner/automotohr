/**
 * Manage shifts
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @package Time & Attendance
 * @version 1.0
 */
$(function manageMyShifts() {
	//
	const mode = getSearchParam("mode") || "month";

	let XHR = null;
	const modalId = "jsModalPage";
	const modalLoader = modalId + "Loader";
	const modalBody = modalId + "Body";

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
				"shifts/my?mode=" +
				mode +
				"&year=" +
				picker.startDate.clone().format("YYYY") +
				"&month=" +
				picker.startDate.clone().format("MM")
			));
		}

		//
		window.location.href = baseUrl(
			"shifts/my?mode=" +
			mode +
			"&start_date=" +
			startDate +
			"&end_date=" +
			endDate
		);
	});

	// adjust the height of cells
	$(".schedule-employee-row").map(function () {
		$(".schedule-column-" + $(this).data("id")).height($(this).height());
	});




	function applyTimePicker() {
		$(".jsTimeField").timepicker({
			timeFormat: "h:mm p",
			dynamic: false,
			dropdown: false,
			scrollbar: false,
		});
	}




	//
	$(".item-openshift").click(function (event) {
		// prevent the event from happening
		event.preventDefault();
		event.stopPropagation();
		//
		callToEditOpenBox($(this).data("id"));
	});
	/**
	 * add the break
	 */
	$(document).on("click", ".jsAddBreak", function (event) {
		event.preventDefault();
		//
		const uniqId = getRandomCode();
		// generate html
		$(".jsBreakContainer").append(generateBreakHtml(uniqId));
		//
		$('[name="breaks[' + uniqId + '][break]"]').rules("add", {
			required: true,
		});
		$('[name="breaks[' + uniqId + '][duration]"]').rules("add", {
			required: true,
			number: true,
			digits: true,
			greaterThanZero: true,
		});
		//
		applyTimePicker();
	});

//

function callToEditOpenBox(shiftId) {
	makePage("Claim Shift", "open_single_shift_claim", shiftId, function (resp) {
		// hides the loader
		ml(false, modalLoader);
		//

		applyTimePicker();

		//
		validatorRef = $("#jsPageCreateSingleShiftForm").validate({
			rules: {
				//shift_employee: { required: true },
				shift_date: { required: true },
				start_time: { required: true, timeIn12Format: true },
				end_time: { required: true, timeIn12Format: true },
			},
			errorPlacement: function (error, element) {
				if ($(element).parent().hasClass("input-group")) {
					$(element).parent().after(error);
				} else {
					$(element).after(error);
				}
			},
			submitHandler: function (form) {
				return processCallWithoutContentType(
					formArrayToObj($(form).serializeArray()),
					$(".jsPageCreateSingleShiftBtn"),
					"settings/shifts/opensingle/claim",
					function (resp) {
						_success(resp.msg, function () {
							window.location.reload();
						});
					}
				);
			},
		});
	});
}



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
	function processCallWithoutContentType(formObj, buttonRef, url, cb) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			return;
		}
		let btnRef;
		//
		if (buttonRef) {
			btnRef = callButtonHook(buttonRef, true);
		}

		// make a new call
		XHR = $.ajax({
			url: baseUrl(url),
			method: "POST",
			data: formObj,
			processData: false,
			contentType: false,
		})
			.always(function () {
				//
				if (buttonRef) {
					callButtonHook(btnRef, false);
				}
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

	//
	function applyDatePicker() {
		$("#shift_date_from").daterangepicker(
			{
				opens: "center",
				singleDatePicker: true,
				showDropdowns: true,
				autoApply: true,
				startDate: getStartDate(),
				locale: {
					format: "MM/DD/YYYY",
				},
			},
			function (start, end, label) {
				$("#shift_date_to").data("daterangepicker").setStartDate(start);
			}
		);

		$("#shift_date_to").daterangepicker({
			opens: "center",
			singleDatePicker: true,
			showDropdowns: true,
			autoApply: true,
			startDate: getEndDate(),
			locale: {
				format: "MM/DD/YYYY",
			},
		});
	}

	applyDatePicker();

	//
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
				"shifts/my?mode=" +
				mode +
				"&start_date=" +
				endDate.clone().format("MM/DD/YYYY") +
				filterFields +
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
				"shifts/my?mode=" +
				mode +
				"&year=" +
				startDateObj.clone().format("YYYY") +
				"&month=" +
				startDateObj.clone().format("MM") +
				filterFields
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
				"shifts/my?mode=" +
				mode +
				"&start_date=" +
				startDate
					.clone()
					.subtract(mode === "week" ? 1 : 2, "week")
					.format("MM/DD/YYYY") +
				"&end_date=" +
				startDate.clone().subtract(1, "day").format("MM/DD/YYYY") +
				filterFields
			));
		} else {
			//
			const startDateObj = startDate.clone().subtract(1, "month");
			//
			return (window.location.href = baseUrl(
				"shifts/my?mode=" +
				mode +
				"&year=" +
				startDateObj.clone().format("YYYY") +
				"&month=" +
				startDateObj.clone().format("MM") +
				filterFields
			));
		}
	});

	// filter
	$(".jsFilterBtn").click(function (event) {
		event.preventDefault();
		//
		$(".jsFilterRow").toggleClass("hidden");
	});

	$(".jsFilterResetBtn").click(function (event) {
		event.preventDefault();
		//
		return (window.location.href = baseUrl("shifts/my?mode=month"));
	});

	$(".jsFilterApplyBtn").click(function (event) {
		event.preventDefault();
		let newSearchURL = "";

		let filterStartDate = $("#start_date").val();
		let filterEndDate = $("#end_date").val();

		if (!filterStartDate) {
			return _error("Please select a start date.");
		}

		if (!filterEndDate) {
			return _error("Please select a end date.");
		}

		newSearchURL =
			"shifts/my?mode=week" +
			"&start_date=" +
			filterStartDate +
			"&end_date=" +
			filterEndDate;

		//
		return (window.location.href = baseUrl(newSearchURL));
	});

	if (getSearchParam("start_date")) {
		$(".jsFilterBtn").trigger("click");
	}
});
