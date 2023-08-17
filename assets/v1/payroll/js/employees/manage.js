/**
 * Manage employee onboard
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function manageEmployees() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * holds the employee id
	 */
	let employeeId = 16;
	/**
	 * holds the modal id
	 */
	let modalId = "jsEmployeeFlowModal";
	/**
	 * capture the view admin event
	 */
	$(".jsPayrollEmployeeEdit").click(function (event) {
		//
		event.preventDefault();
		//
		employeeId = $(this).closest("tr").data("id");
		//
		employeeOnboardFlow();
	});

	/**
	 * capture the view admin event
	 */
	$(document).on("click", ".jsMenuTrigger", function (event) {
		//
		event.preventDefault();
		//
		loadView($(this).data("step"));
	});

	/**
	 * Personal details triggers
	 */
	$(document).on(
		"click",
		".jsEmployeeFlowSavePersonalDetailsBtn",
		function (e) {
			//
			e.preventDefault();
			//
			if (XHR !== null) {
				return false;
			}
			//
			let obj = {
				first_name: $(".jsEmployeeFlowFirstName").val().trim(),
				middle_initial: $(".jsEmployeeFlowMiddleInitial").val().trim(),
				last_name: $(".jsEmployeeFlowLastName").val().trim(),
				location_uuid: $(
					".jsEmployeeFlowWorkAddress option:selected"
				).val(),
				start_date: $(".jsEmployeeFlowStartDate").val().trim(),
				email: $(".jsEmployeeFlowEmail").val().trim(),
				ssn: $(".jsEmployeeFlowSSN").val().trim(),
				date_of_birth: $(".jsEmployeeFlowDateOfBirth").val().trim(),
			};
			//
			let errorArray = [];
			// validation
			if (!obj.first_name) {
				errorArray.push('"First name" is missing.');
			}
			if (!obj.last_name) {
				errorArray.push('"Last name" is missing.');
			}
			if (!obj.location_uuid) {
				errorArray.push('"Work address" is missing.');
			}
			if (!obj.start_date) {
				errorArray.push('"Start date" is missing.');
			}
			if (!obj.email) {
				errorArray.push('"Email" is missing.');
			}
			if (!obj.email.verifyEmail()) {
				errorArray.push('"Email" is invalid.');
			}
			if (!obj.ssn) {
				errorArray.push('"Social Security number (SSN)" is missing.');
			}
			if (
				obj.ssn.replace(/-/g, "").length !== 9 ||
				obj.ssn.match(/([^0-9#-])/gi) !== null
			) {
				errorArray.push(
					'"Social Security number (SSN)" must be 9 digits long.'
				);
			}
			if (!obj.date_of_birth) {
				errorArray.push('"Date of birth" is missing.');
			}
			//
			if (errorArray.length) {
				return alertify.alert(
					"Error!",
					getErrorsStringFromArray(errorArray),
					CB
				);
			}
			//
			ml(
				true,
				`${modalId}Loader`,
				"Please wait, while we are processing your request."
			);
			//
			XHR = $.ajax({
				url: baseUrl(
					"payrolls/flow/employee/" + employeeId + "/personal_details"
				),
				method: "POST",
				data: obj,
			})
				.success(function (resp) {
					//
					return alertify.alert("Success!", resp.msg, CB);
				})
				.fail(handleErrorResponse)
				.always(function () {
					//
					XHR = null;
					//
					ml(false, `${modalId}Loader`);
				});
		}
	);

	/**
	 * Compensation triggers
	 */
	$(document).on("click", ".jsEmployeeFlowSaveCompensationBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			title: $(".jsEmployeeFlowJobTitle").val().trim(),
			amount: $(".jsEmployeeFlowAmount").val().trim(),
			classification: $(
				".jsEmployeeFlowEmployeeClassification option:selected"
			).val(),
			per: $(".jsEmployeeFlowPer option:selected").val(),
		};
		//
		let errorArray = [];
		// validation
		if (!obj.title) {
			errorArray.push('"Job title" is missing.');
		}
		if (!obj.amount || obj.amount < 0) {
			errorArray.push('"Amount" is missing.');
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		//
		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" + employeeId + "/compensation"
			),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, CB);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
			});
	});

	/**
	 * Home address triggers
	 */
	$(document).on("click", ".jsEmployeeFlowSaveHomeAddressBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			street_1: $(".jsEmployeeFlowStreet1").val().trim(),
			street_2: $(".jsEmployeeFlowStreet2").val().trim(),
			city: $(".jsEmployeeFlowCity").val().trim(),
			state: $(".jsEmployeeFlowState").val().trim(),
			zip: $(".jsEmployeeFlowZip").val().trim(),
		};
		//
		let errorArray = [];
		// validation
		if (!obj.street_1) {
			errorArray.push('"street 1" is missing.');
		}
		if (!obj.city) {
			errorArray.push('"City" is missing.');
		}
		if (!obj.state) {
			errorArray.push('"State" is missing.');
		}
		if (!obj.zip) {
			errorArray.push('"Zip" is missing.');
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		//
		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" + employeeId + "/home_address"
			),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, CB);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
			});
	});

	/**
	 * Federal tax triggers
	 */
	$(document).on("click", ".jsEmployeeFlowSaveFederalTaxBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			filing_status: $(
				".jsEmployeeFlowFilingStatus option:selected"
			).val(),
			two_jobs: $(".jsEmployeeFlowMultipleJobs:checked").val(),
			dependents_amount: $(".jsEmployeeFlowDependents").val().trim(),
			extra_withholding: $(".jsEmployeeFlowExtraWithholding")
				.val()
				.trim(),
			other_income: $(".jsEmployeeFlowOtherIncome").val().trim(),
			deductions: $(".jsEmployeeFlowDeductions").val().trim(),
			w4_data_type: "rev_2020_w4",
		};
		//
		let errorArray = [];
		// validation
		if (!obj.filing_status) {
			errorArray.push('"Filing status" is missing.');
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		//
		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" + employeeId + "/federal_tax"
			),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, CB);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
			});
	});

	/**
	 * starts the employee onboard flow
	 * @returns
	 */
	function employeeOnboardFlow() {
		// check the employee
		if (employeeId === 0) {
			return alertify.alert("Error!", "Please select an employee.");
		}
		// generate modal
		Modal(
			{
				Title: "Employee Onboard Flow",
				Id: modalId,
				Loader: `${modalId}Loader`,
				Body: `<div id="${modalId}Body"></div>`,
			},
			function () {
				loadView("federal_tax");
			}
		);
	}

	/**
	 * Load page
	 * @param {string} step
	 */
	function loadView(step) {
		//
		_ml(true, `${modalId}Loader`);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/flow/employee/" + employeeId + "/" + step),
			method: "GET",
			caches: false,
		})
			.success(function (response) {
				//
				$(`#${modalId}Body`).html(response.view);
				//
				loadEvents(step);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `${modalId}Loader`);
			});
	}

	/**
	 * load events
	 * @param {string} step
	 */
	function loadEvents(step) {
		if (step === "personal_details") {
			$(".jsEmployeeFlowDateOfBirth").datepicker({
				format: "mm/dd/yyyy",
				changeYear: true,
				changeMonth: true,
				yearRange: "-100:+0",
			});
			$(".jsEmployeeFlowStartDate").datepicker({
				format: "mm/dd/yyyy",
				changeYear: true,
				changeMonth: true,
				yearRange: "-100:+0",
			});
		}
	}

	employeeOnboardFlow();

	$.ajaxSetup({
		cache: false,
	});
});
