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

		let filterFields = '';
		if (filterTeam != '') {
			filterFields = '&employees=' + filterEmployeesSid + '&team=' + filterTeam + '&jobtitle=' + filterJobtitle
		}

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
				startDate.clone().subtract(1, "day").format("MM/DD/YYYY") + filterFields
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
				startDateObj.clone().format("MM") + filterFields
			));
		}
	});
	// navigate to future dates
	$(".jsNavigateRight").click(function (event) {
		event.preventDefault();
		//

		let filterFields = '';
		if (filterTeam != '') {
			filterFields = '&employees=' + filterEmployeesSid + '&team=' + filterTeam + '&jobtitle=' + filterJobtitle
		}

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
				endDate.clone().format("MM/DD/YYYY") + filterFields +
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
				startDateObj.clone().format("MM") + filterFields
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

	//
	$(".jsEmployeesShifts").click(function (event) {
		// prevent the event from happening
		event.preventDefault();
		//
		callToCreateBoxMultiShifts();
	});

	$(".jsEmployeeShiftsDelete").click(function (event) {
		// prevent the event from happening
		event.preventDefault();
		//
		callToDeleteBoxMultiShifts();
	});

	$(".jsEmployeeShiftsCopy").click(function (event) {
		// prevent the event from happening
		//event.preventDefault();
		//
		callToCopyBoxShifts();
	});

	/**
	 * capture click event on cell
	 */
	$(".schedule-item").click(function (event) {
		// prevent the event from happening
		event.preventDefault();
		event.stopPropagation();
		//
		callToEditBox($(this).data("id"));
	});

	//callToEditBox(1);

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


	/**
	 * mark as day off
	 */
	$(document).on("click", ".jsMarkAsDayOff", function (event) {
		event.preventDefault();
		//
		const shiftId = $(this).data("id");
		// generate html
		alertify.confirm(
			'Are You Sure?',
			'Are you sure want to delete shifts?',
			function () {
				//
				const formObj = new FormData();
				// set the file object
				formObj.append("id", shiftId);
				// 
				processCallWithoutContentType(
					formObj,
					'',
					"settings/shifts/singleshift/delete",
					function (resp) {
						// show the message
						_success(resp.msg, function () {
							window.location.reload();
						});
					}
				);
			},
			function () {

			}
		)
	});

	/**
	 * remove the break
	 */
	$(document).on("click", ".jsDeleteBreakRow", function (event) {
		event.preventDefault();
		//
		const uniqId = $(this).closest(".jsBreakRow").data("key");
		$('.jsBreakRow[data-key="' + uniqId + '"]').remove();
		$('[name="breaks[' + uniqId + '][break]"]').rules("remove");
		$('[name="breaks[' + uniqId + '][duration]"]').rules("remove");
	});

	/**
	 * on break select
	 */
	$(document).on("change", ".jsBreakSelect", function () {
		//
		const uniqId = $(this).closest(".jsBreakRow").data("key");
		//
		$('[name="breaks[' + uniqId + '][duration]"]').val(
			$(this).find("option:selected").data("duration")
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

	/**
	 * Create shift against a specific employee and date
	 * @param {int} employeeId
	 * @param {string} date
	 */
	function callToCreateBox(employeeId, date) {
		makePage(
			"Create Shift",
			"create_single_shift",
			employeeId,
			function (resp) {
				// hides the loader
				ml(false, modalLoader);
				//
				$('[name="shift_date"]').val(
					moment(date, "YYYY-MM-DD").format("MM/DD/YYYY")
				);

				applyTimePicker();

				//
				validatorRef = $("#jsPageCreateSingleShiftForm").validate({
					rules: {
						shift_employee: { required: true },
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
							"settings/shifts/single/create",
							function (resp) {
								if (resp.shiftid != 0) {
									callToCreateBoxSendShifts(resp.shiftid,resp.shiftdate);

								} else {
									_success(resp.msg, function () {
										//
										window.location.reload();
									});

								}
							}
						);
					},
				});
			}
		);
	}

	//
	function callToCreateBoxMultiShifts() {
		makePage("Create Shift", "create_multi_shift", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			applyTimePicker();
			applyDatePicker();

			$(".jsSelectAll").click(function (event) {
				event.preventDefault();
				$(".jsPageApplyTemplateEmployees").prop("checked", true);
			});
			$(".jsRemoveAll").click(function (event) {
				event.preventDefault();
				$(".jsPageApplyTemplateEmployees").prop("checked", false);
			});

			validatorRef = $("#jsPageCreateSingleShiftForm").validate({
				rules: {
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
					$(form).serializeArray();

					const passObj = {
						start_date: $("#shift_date_from").val(),
						end_date: $("#shift_date_to").val(),
						employees: [],
					};

					//
					$(".jsPageApplyTemplateEmployees:checked").map(function () {
						passObj.employees.push($(this).val());
					});

					if (!passObj.start_date.length) {
						return _error("Please select From Date.");
					}
					if (!passObj.end_date.length) {
						return _error("Please select To Date.");
					}

					if (!passObj.employees.length) {
						return _error("Please select at least one employee.");
					}

					//
					processCallWithoutContentType(
						formArrayToObj($(form).serializeArray()),
						$(".jsPageCreateSingleShiftBtn"),
						"settings/shifts/multyshift/apply",
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
				},
			});
		});
	}

	//
	function callToDeleteBoxMultiShifts() {
		makePage("Delete Shifts", "delete_multi_shift", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			applyDatePicker();

			$(".jsSelectAll").click(function (event) {
				event.preventDefault();
				$(".jsPageApplyTemplateEmployees").prop("checked", true);
			});
			$(".jsRemoveAll").click(function (event) {
				event.preventDefault();
				$(".jsPageApplyTemplateEmployees").prop("checked", false);
			});

			validatorRef = $("#jsPageCreateSingleShiftForm").validate({
				submitHandler: function (form) {
					$(form).serializeArray();

					const passObj = {
						start_date: $("#shift_date_from").val(),
						end_date: $("#shift_date_to").val(),
						employees: [],
					};

					//
					$(".jsPageApplyTemplateEmployees:checked").map(function () {
						passObj.employees.push($(this).val());
					});

					if (!passObj.start_date.length) {
						return _error("Please select From Date.");
					}
					if (!passObj.end_date.length) {
						return _error("Please select To Date.");
					}

					if (!passObj.employees.length) {
						return _error("Please select at least one employee.");
					}

					//				
					alertify.confirm(
						'Are You Sure?',
						'Are you sure want to delete shifts?',
						function () {

							processCallWithoutContentType(
								formArrayToObj($(form).serializeArray()),
								$(".jsPageCreateSingleShiftBtn"),
								"settings/shifts/multyshift/delete",
								function (resp) {
									// show the message
									_success(resp.msg, function () {
										window.location.href = baseUrl(
											"settings/shifts/manage" +
											window.location.search
										);
									});
								}
							);
						},
						function () {

						}
					)


				},
			});
		});
	}

	//
	function callToCopyBoxShifts() {
		makePage("Copy Shifts", "copy_shift", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			applyDatePickerCopy();
			applyDatePicker();

			$(".jsSelectAll").click(function (event) {
				event.preventDefault();
				$(".jsPageApplyTemplateEmployees").prop("checked", true);
			});
			$(".jsRemoveAll").click(function (event) {
				event.preventDefault();
				$(".jsPageApplyTemplateEmployees").prop("checked", false);
			});

			validatorRef = $("#jsPageCreateSingleShiftForm").validate({
				submitHandler: function (form) {
					$(form).serializeArray();

					const passObj = {
						last_start_date: $("#shift_date_from").val(),
						last_end_date: $("#shift_date_to").val(),
						start_date: $("#shift_date_from").val(),
						end_date: $("#shift_date_to").val(),
						employees: [],
					};

					//
					$(".jsPageApplyTemplateEmployees:checked").map(function () {
						passObj.employees.push($(this).val());
					});

					if (!passObj.last_start_date.length) {
						return _error("Please select From Date.");
					}
					if (!passObj.last_end_date.length) {
						return _error("Please select To Date.");
					}

					if (!passObj.start_date.length) {
						return _error("Please select From Date.");
					}
					if (!passObj.end_date.length) {
						return _error("Please select To Date.");
					}

					if (!passObj.employees.length) {
						return _error("Please select at least one employee.");
					}

					//
					processCallWithoutContentType(
						formArrayToObj($(form).serializeArray()),
						$(".jsPageCreateSingleShiftBtn"),
						"settings/shifts/multyshift/copy",
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
				},
			});
		});
	}

	/**
	 * Create shift against a specific employee and date
	 * @param {int} employeeId
	 */
	function callToEditBox(shiftId) {
		makePage("Edit Shift", "edit_single_shift", shiftId, function (resp) {
			// hides the loader
			ml(false, modalLoader);
			//

			applyTimePicker();

			//
			validatorRef = $("#jsPageCreateSingleShiftForm").validate({
				rules: {
					shift_employee: { required: true },
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
						"settings/shifts/single/create",
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
	 * apply time picker
	 */

	function applyTimePicker() {
		$(".jsTimeField").timepicker({
			timeFormat: "h:mm p",
			dynamic: false,
			dropdown: false,
			scrollbar: false,
		});
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
			startDate: getEndDate(),
			locale: {
				format: "MM/DD/YYYY",
			},
		});
	}

	function applyDatePickerCopy() {
		$("#last_shift_date_from").daterangepicker({
			opens: "center",
			singleDatePicker: true,
			showDropdowns: true,
			autoApply: true,
			startDate: getStartDate(),
			locale: {
				format: "MM/DD/YYYY",
			},
		});

		$("#last_shift_date_to").daterangepicker({
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

	$(function () {
		$(".expander").on("click", function () {
			$("#TableData").toggle();
		});
	});

	//
	if (filterToggle == true) {
		$("#TableData").toggle();
	}

	$(".js-filter-employee").select2();

	$(".js-filter-jobtitle").select2();


	$(".jsSelect2").select2();

	//Apply Filters
	$(".js-apply-filter-btn").click(function (event) {
		event.preventDefault();

		const startDate =
			$(".jsWeekDaySelect").data("daterangepicker").startDate;
		let searchStartDate = getStartDate(startDate);
		let searchEndDate = getEndDate(startDate);
		let newSearchurl = "";

		let startYear = startDate.clone().format("YYYY");
		let startMonth = startDate.clone().format("MM");
		let employees = $(".js-filter-employee").val();
		let team = $(".jsSelect2").val();
		let jobtitle = $(".js-filter-jobtitle").val();

		if (getSearchParam("year")) {
			newSearchurl =
				"settings/shifts/manage?mode=" +
				mode +
				"&year=" +
				startYear +
				"&month=" +
				startMonth +
				"&employees=" +
				employees +
				"&team=" +
				team +
				"&jobtitle=" + jobtitle;
		} else if (getSearchParam("start_date")) {
			newSearchurl =
				"settings/shifts/manage?mode=" +
				mode +
				"&start_date=" +
				searchStartDate +
				"&end_date=" +
				searchEndDate +
				"&employees=" +
				employees +
				"&team=" +
				team +
				"&jobtitle=" + jobtitle;
		} else {
			newSearchurl =
				"settings/shifts/manage?mode=" +
				mode +
				"&employees=" +
				employees +
				"&team=" +
				team +
				"&jobtitle=" + jobtitle;
		}

		//
		return (window.location.href = baseUrl(newSearchurl));
	});

	$(".js-reset-filter-btn").click(function (event) {
		event.preventDefault();

		const startDate =
			$(".jsWeekDaySelect").data("daterangepicker").startDate;
		let searchStartDate = getStartDate(startDate);
		let searchEndDate = getEndDate(startDate);
		let newSearchurl = "";

		let startYear = startDate.clone().format("YYYY");
		let startMonth = startDate.clone().format("MM");
		let employees = $(".js-filter-employee").val();
		let team = $(".jsSelect2").val();

		if (getSearchParam("year")) {
			newSearchurl =
				"settings/shifts/manage?mode=" +
				mode +
				"&year=" +
				startYear +
				"&month=" +
				startMonth;
		} else if (getSearchParam("start_date")) {
			newSearchurl =
				"settings/shifts/manage?mode=" +
				mode +
				"&start_date=" +
				searchStartDate +
				"&end_date=" +
				searchEndDate;
		} else {
			newSearchurl = "settings/shifts/manage?mode=" + mode;
		}

		//
		return (window.location.href = baseUrl(newSearchurl));
	});


	//
	let jobTitles = filterJobtitle.split(",");
	$('#jobtitleId').val(jobTitles);
	$('#jobtitleId').trigger('change');


	/**
	 * generates break h*ml
	 * @param {number} uniqId
	 * @param {object|undefined} data
	 * @returns
	 */
	function generateBreakHtml(uniqId, data) {
		//
		let breakOptions = "";
		breakOptions += "<option></option>";
		//
		breaksObject.map(function (v) {
			breakOptions +=
				'<option value="' +
				v.break_name +
				'" data-duration="' +
				v.break_duration +
				'" ' +
				(data !== undefined && data.break === v.break_name
					? "selected"
					: "") +
				">" +
				v.break_name +
				" (" +
				v.break_type +
				")</option>";
		});

		//
		let html = "";
		html += '<div class="row jsBreakRow" data-key="' + uniqId + '">';
		html += "    <br> ";
		html += '     <div class="col-sm-5">';
		html += '        <label class="text-medium">';
		html += "            Break ";
		html += '            <strong class="text-red">*</strong>';
		html += "         </label>";
		html +=
			'         <select name="breaks[' +
			uniqId +
			'][break]" class="form-control jsBreakSelect">';
		html += breakOptions;
		html += "         </select>";
		html += "     </div>";
		html += '     <div class="col-sm-3">';
		html += '         <label class="text-medium">';
		html += "             Duration ";
		html += '             <strong class="text-red">*</strong>';
		html += "         </label>";
		html += '         <div class="input-group">';
		html +=
			'             <input type="number" class="form-control jsDuration" name="breaks[' +
			uniqId +
			'][duration]" value="' +
			(data?.duration || "") +
			'" />';
		html += '             <div class="input-group-addon">mins</div>';
		html += "         </div>";
		html += "     </div>";
		html += '     <div class="col-sm-3">';
		html += '         <label class="text-medium">';
		html += "             Start TIme ";
		html += "         </label>";
		html +=
			'         <input type="text" class="form-control jsTimeField jsStartTime" placeholder="HH:MM" name="breaks[' +
			uniqId +
			'][start_time]"value="' +
			(data?.start_time
				? moment(data.start_time, "HH:mm").format("h:mm a")
				: "") +
			'" />';
		html += "     </div>";
		html += '     <div class="col-sm-1">';
		html += "         <br>";
		html +=
			'         <button class="btn btn-red jsDeleteBreakRow" title="Delete this break" type="button">';
		html +=
			'             <i class="fa fa-trash" style="margin-right: 0"></i>';
		html += "         </button>";
		html += "     </div>";
		html += "</div>";
		//
		return html;
	}

	/**
	 * Publish Single Shift
	 */
	$(document).on(
		"click",
		".jsPublishSingleShiftBtn",
		function (event) {
			event.preventDefault();
			publishUnpublishSingleShiftId = $(this).data("id");
			publishUnpublishSingleShiftStatus = $(this).data("publish");
			showConfirmSingleShift();
		}
	);

	/**
	 * UnPublish Single Shift
	 */
	$(document).on("click", ".jsUnpublishSingleShiftBtn", function (event) {
		event.preventDefault();
		publishUnpublishSingleShiftId = $(this).data("id");
		publishUnpublishSingleShiftStatus = $(this).data("publish");
		_confirm(
			"Do you really want to unpublish the shift?",
			function() {
				unpublishShift(publishUnpublishSingleShiftId);
			}
		);
	});

	//
	$(document).on(
		"click",
		".jsPublishSingleShiftBtnOk,.jsUnpublishSingleShiftBtnOkEmail",
		function (event) {
			event.preventDefault();

			//

			let msg = "";
			let sendEmail = $(this).data("sendemail");

			if (publishUnpublishSingleShiftStatus == 1) {
				publishUnpublishSingleShiftStatus = 0;
				msg = " Unpublish ";
			} else {
				publishUnpublishSingleShiftStatus = 1;
				msg = " Publish ";
			}

			alertify.confirm(
				"Are You Sure?",
				"Are you sure want to " + msg + " the shift?",
				function () {
					//
					const formObj = new FormData();

					formObj.append("shiftId", publishUnpublishSingleShiftId);
					formObj.append(
						"publichStatus",
						publishUnpublishSingleShiftStatus
					);
					formObj.append("sendEmail", sendEmail);

					//
					processCallWithoutContentType(
						formObj,
						"",
						"settings/shifts/singleshift/public-status",
						function (resp) {
							// show the message
							_success(resp.msg, function () {
								window.location.reload();
							});
						}
					);
				},
				function () {}
			);
		}
	);

	//
	$(document).on("click", ".jsModalCancel2", function (event) {
		alertify.genericDialog().close();
	});

	/**
	 * Publish Multi Shift
	 */
	$(document).on("click", ".jsPublishMultiShiftBtn", function (event) {
		event.preventDefault();
		//
		publishUnpublishSingleShiftId = $(this).data("ids");
		allShiftsId = $(this).data("shiftsids");
		showConfirm();
	});

	//
	alertify.dialog(
		"publishShift",
		function () {
			return {
				setup: function () {
					var settings = alertify.confirm().settings;
					for (var prop in settings)
						this.settings[prop] = settings[prop];
					var setup = alertify.confirm().setup();
					setup.buttons.push({
						text: "Publish & Send Emails",
						key: 67 /*c*/,
						scope: "auxiliary",
						className: "ajs-ok",
					});
					return setup;
				},
				settings: {
					onPublishAndSendEmails: null,
				},
				callback: function (closeEvent) {
					if (closeEvent.index == 2) {
						if (
							typeof this.get("onPublishAndSendEmails") ===
							"function"
						) {
							returnValue = this.get(
								"onPublishAndSendEmails"
							).call(this, closeEvent);
							if (typeof returnValue !== "undefined") {
								closeEvent.cancel = !returnValue;
							}
						}
					} else {
						alertify.confirm().callback.call(this, closeEvent);
					}
				},
			};
		},
		false,
		"confirm"
	);

	/// invoke the custom dialog
	function showConfirm() {
		alertify.publishShift().setHeader("Confirmation");

		alertify.publishShift("Are you sure want to publish shifts?").set({
			onok: function () {
				const formObj = new FormData();

				formObj.append("shiftIds", publishUnpublishSingleShiftId);
				formObj.append("publichStatus", 1);
				formObj.append("sendEmail", 0);
				//
				processCallWithoutContentType(
					formObj,
					"",
					"settings/shifts/multishift/public-status",
					function (resp) {
						// show the message
						_success(resp.msg, function () {
							window.location.reload();
						});
					}
				);
			},
			onPublishAndSendEmails: function () {
				publishShiftsEmailOption();
			},
			oncancel: function () {},
			labels: {
				ok: "Publish",
			},
		});
	}

	//
	function publishShiftsEmailOption() {
		showShiftEmailOption();
	}

	//
	alertify.dialog(
		"ShiftEmailOption",
		function () {
			var settings;
		},
		false,
		"confirm"
	);

	function showShiftEmailOption() {
		let publishBox = "";

		publishBox += ' <div class="hr-fields-wrap">';
		publishBox += ' <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">';
		publishBox +=
			'<label class="control control--radio " style="margin-left: -20px;">Send Email To Effected Employees<input type="radio" name="shifts_email_selection" class="shiftemailselection" value="effected" checked>';
		publishBox += '    <div class="control__indicator"></div>';
		publishBox += "   </label>";
		publishBox += "</div>";

		publishBox += ' <div class="hr-fields-wrap">';
		publishBox += ' <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">';
		publishBox +=
			'<label class="control control--radio " style="margin-left: -20px;">Send Email To All Employees<input type="radio" name="shifts_email_selection" class="shiftemailselection" value="all">';
		publishBox += '    <div class="control__indicator"></div>';
		publishBox += "   </label>";
		publishBox += "</div>";
		publishBox += "<br> ";
		publishBox += "  </div>";
		publishBox += " <br>";

		alertify.ShiftEmailOption(publishBox).set({
			'title ': 'Info',
			onok: function () {
				let sendShiftsEmailOption = $(
					"input[name='shifts_email_selection']:checked"
				).val();

				const formObj = new FormData();

				formObj.append("shiftIds", publishUnpublishSingleShiftId);
				formObj.append("publichStatus", 1);
				formObj.append("sendEmail", 1);
				formObj.append("sendShiftsEmailOption", sendShiftsEmailOption);
				formObj.append("allShiftsId", allShiftsId);

				//
				processCallWithoutContentType(
					formObj,
					"",
					"settings/shifts/multishift/public-status",
					function (resp) {
						// show the message
						_success(resp.msg, function () {
							window.location.reload();
						});
					}
				);
			},
			oncancel: function () {},
			labels: {
				ok: "Send",
			},
		});
	}

	//
	alertify.dialog(
		"publishSingleShift",
		function () {
			var settings;

			return {
				setup: function () {
					var settings = alertify.confirm().settings;
					for (var prop in settings)
						this.settings[prop] = settings[prop];
					var setup = alertify.confirm().setup();

					setup.buttons.push({
						text: "Publish & Send Email",
						key: 67 /*c*/,
						scope: "auxiliary",
						className: "ajs-ok",
					});

					return setup;
				},
				settings: {
					onPublishAndSendEmails: null,
				},
				callback: function (closeEvent) {
					if (closeEvent.index == 2) {
						if (
							typeof this.get("onPublishAndSendEmails") ===
							"function"
						) {
							returnValue = this.get(
								"onPublishAndSendEmails"
							).call(this, closeEvent);
							if (typeof returnValue !== "undefined") {
								closeEvent.cancel = !returnValue;
							}
						}
					} else {
						alertify.confirm().callback.call(this, closeEvent);
					}
				},
			};
		},
		false,
		"confirm"
	);

	/// invoke the custom dialog
	function showConfirmSingleShift() {
		let msg = "";

		if (publishUnpublishSingleShiftStatus == 1) {
			publishUnpublishSingleShiftStatus = 0;
			msg = " Unpublish ";
		} else {
			publishUnpublishSingleShiftStatus = 1;
			msg = " Publish ";
		}

		alertify.publishSingleShift().setHeader("Confirmation");

		alertify
			.publishSingleShift()
			.set("labels", { ok: msg, PublishAndSendEmails: msg });

		alertify
			.publishSingleShift("Are you sure want to " + msg + "  this shift?")
			.set({
				onok: function () {
					publishShift(0);
				},
				onPublishAndSendEmails: function () {
					publishShift(1);
				},
				labels: {
					//'ok': 'Publish',
				},
			});
	}

	function publishShift(sendEmail) {
		const formObj = new FormData();

		formObj.append("shiftId", publishUnpublishSingleShiftId);
		formObj.append("publichStatus", publishUnpublishSingleShiftStatus);
		formObj.append("sendEmail", sendEmail);
		//
		processCallWithoutContentType(
			formObj,
			"",
			"settings/shifts/singleshift/public-status",
			function (resp) {
				// show the message
				_success(resp.msg, function () {
					window.location.reload();
				});
			}
		);
	}
	
	function unpublishShift(shiftId) {
		const formObj = new FormData();

		formObj.append("shiftId", shiftId);
		formObj.append("publichStatus", 0);
		formObj.append("sendEmail", 1);
		//
		processCallWithoutContentType(
			formObj,
			"",
			"settings/shifts/singleshift/public-status",
			function (resp) {
				// show the message
				_success(resp.msg, function () {
					window.location.reload();
				});
			}
		);
	}


	//
	$('body').on('click', '.jsPageCreateSingleShiftAndSendBtn', function () {
		$("<input>").attr({
			name: "create_and_send_shift",
			id: "hiddenId",
			type: "hidden",
			value: 1
		}).appendTo("form");

		$("#jsPageCreateSingleShiftForm").submit();

	});

	//
	function callToCreateBoxSendShifts(sendShiftId,shiftDate) {
		makePage("Send Shift", "send_shift", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			$(".jsSelect2").select2();
			$(".js-filter-jobtitle").select2();


			$("#jssendShiftId").val(sendShiftId);
			$("#jssendShiftDate").val(shiftDate);


			$(".jsSelectAll").click(function (event) {
				event.preventDefault();
				$(".jsPageApplyTemplateEmployees").prop("checked", true);
			});
			$(".jsRemoveAll").click(function (event) {
				event.preventDefault();
				$(".jsPageApplyTemplateEmployees").prop("checked", false);
			});

			validatorRef = $("#jsPageCreateSingleShiftForm").validate({
				rules: {
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
					$(form).serializeArray();

					const passObj = {
						start_date: $("#shift_date_from").val(),
						end_date: $("#shift_date_to").val(),
						employees: [],
					};

					//
					$(".jsPageApplyTemplateEmployees:checked").map(function () {
						passObj.employees.push($(this).val());
					});

					if (!passObj.start_date.length) {
						return _error("Please select From Date.");
					}
					if (!passObj.end_date.length) {
						return _error("Please select To Date.");
					}

					if (!passObj.employees.length) {
						return _error("Please select at least one employee.");
					}

					//
					processCallWithoutContentType(
						formArrayToObj($(form).serializeArray()),
						$(".jsPageCreateSingleShiftBtn"),
						"settings/shifts/multyshift/apply",
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
				},
			});
		});
	}

	//
	$('body').on('click', '.js-apply-filter', function (event) {
		event.preventDefault();

		let teamid = $("#teamIds").val();
		let jobtitle = $("#jobtitleIds").val();
		let shift_date = $("#jssendShiftDate").val();

		// hides the loader
		ml(false, modalLoader);
		event.preventDefault();

		const passObj = {
			departmentId: teamid,
			job_titles: jobtitle,
			shift_date: shift_date
		};

		//
		const btnRef = callButtonHook($(".js-apply-filter"), true);
		// make a new call

		url = 'settings/shifts/get_employee_list';
		XHR = $.ajax({
			url: baseUrl(url),
			method: "POST",
			data: passObj,
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

				let html = "";

				$.each(resp.list, function (i, v) {

					html += '<div class="col-sm-6">';
					html += '<label class="control control--checkbox">';
					html += '<input type="checkbox" class="jsPageApplyTemplateEmployees" value="' + v.userId + '" name="employees[]" />';
					html += v.employee_name;
					html += '<div class="control__indicator"></div>';
					html += '</label>';
					html += '</div>';
				});

				$('#employeeList').html(html);

			});

	});



	//
	$('body').on('click', '.js-reset-filter', function (event) {
		event.preventDefault();

		$("#teamIds").select2("val", "0");
		$("#jobtitleIds").select2("val", "all");
		$('#employeeList').html('');
	});


	//
	$('body').on('click', '.jsSendShiftNotification', function (event) {
		event.preventDefault();

		let shift_id = $("#jssendShiftId").val();
		const passObj = {
			shift_id: shift_id,
			employees: [],
		};


		//
		$(".jsPageApplyTemplateEmployees:checked").map(function () {
			passObj.employees.push($(this).val());
		});


		if (!passObj.employees.length) {
			return _error("Please select at least one employee.");
		}

		//		
		const btnRef = callButtonHook($(".jsSendShiftNotification"), true);
		// make a new call

		url = 'settings/shifts/send_shift';

		XHR = $.ajax({
			url: baseUrl(url),
			method: "POST",
			data: passObj,
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
				_success(resp.msg, function () {
					window.location.href = baseUrl(
						"settings/shifts/manage" +
						window.location.search
					);
				});

			});

	});



	$('body').on('click', '.jsPageSingleShifthistory', function (event) {
		event.preventDefault();

		let shiftId = $(".jsPageSingleShifthistory").data('shiftid');
		callToCreateBoxShiftsRequestHistroy(shiftId)
	});


	//
	function callToCreateBoxShiftsRequestHistroy(shiftId) {

		makePage("Shift Histroy", "shift_history", shiftId, function () {
			// hides the loader
			ml(false, modalLoader);

		});
	}
});