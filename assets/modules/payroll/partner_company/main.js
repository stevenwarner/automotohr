/**
 * Create Partner Company On Gusto
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function createPartnerCompany() {
	/**
	 * set company id
	 */
	let companyId = 0;
	/**
	 * set modal reference
	 */
	let modalId = "jsCreatePartnerCompanyModal";
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * set the process handler
	 */
	let processQueue = {
		checkRequirements,
		welcomeStep,
		employeeListingStep,
		adminStep,
		createPartnerCompany,
	};
	/**
	 * selected employees
	 */
	let selectedEmployees = [];
	/**
	 * capture the modal close event
	 */
	$(document).on("click", ".jsModalCancel", function () {
		XHR = null;
	});

	$(document).on("click", ".jsSelectAll", function () {
		$(".jsSingleEmployee").prop("checked", true);
	});
	$(document).on("click", ".jsUnSelectAll", function () {
		$(".jsSingleEmployee").prop("checked", false);
	});

	/**
	 * capture the create partner company click
	 */
	$(".jsCreatePartnerCompanyBtn").click(function (event) {
		// stop the default behavior
		event.preventDefault();
		// set the company id
		companyId = parseInt($(this).data("cid"));
		// check if company id is not set
		if (companyId === 0) {
			return _error("ERROR", "Company code is missing.");
		}
		// call the Modal
		Modal(
			{
				Id: modalId,
				Title: "",
				Loader: modalId + "Loader",
				Body: `<div id="${modalId}Body"></div>`,
			},
			processQueue.checkRequirements
		);
	});

	/**
	 * capture the view admin event
	 */
	$(document).on("click", ".jsPayrollViewAdminDetails", function (event) {
		//
		event.preventDefault();
		//
		viewAdmin();
	});

	/**
	 * capture the save admin event
	 */
	$(document).on("click", ".jsPayrollSaveAdmin", function (event) {
		//
		event.preventDefault();
		//
		saveAdmin();
	});

	/**
	 * capture the view/add admin back step
	 */
	$(document).on("click", ".jsBackToStep3", function (event) {
		//
		event.preventDefault();
		//
		_ml(true, modalId + "Loader");
		// call the next
		processQueue.adminStep();
	});

	/**
	 * capture the create partner company process
	 */
	$(document).on(
		"click",
		".jsCreatePartnerCompanyProcessBtn",
		function (event) {
			//
			event.preventDefault();
			//
			_ml(
				true,
				modalId + "Loader",
				"Please wait patiently while we set up the payroll system. This procedure may take a few minutes."
			);
			// call the next
			processQueue.createPartnerCompany();
		}
	);

	/**
	 *
	 */
	$(document).on("change", "#jsEmployeeChoose", function () {
		//
		const id = $(this).val();
		//
		if (id != 0) {
			// hides the loader
			ml(true, "jsCreateLoader");
			//
			getAndSetView(id);
		}
	});

	/**
	 * check company requirements
	 */
	function checkRequirements() {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("payroll/company/" + companyId + "/requirements"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(failError)
			.success(function () {
				XHR = null;
				//
				processQueue.welcomeStep();
			});
	}

	/**
	 * Welcome page
	 * step 1
	 */
	function welcomeStep() {
		// stop the execution
		if (XHR !== null) {
			return false;
		}
		//
		XHR = $.ajax({
			url: baseUrl("payroll/company/" + companyId + "/partner/welcome"),
			method: "GET",
		})
			.success(function (resp) {
				// flush XHR
				XHR = null;
				// load the view
				$(`#${modalId}Body`).html(resp.view);
				//
				if (resp.onboard !== undefined) {
					if (resp.onboard === "terms") {
						return processQueue.gustoTerms();
					}
					window.location.refresh();
				}
				// hide the loader
				_ml(false, modalId + "Loader");
				// attach trigger
				$(".jsPayrollLoadSelectEmployees").click(function (event) {
					//
					event.preventDefault();
					// show the loader
					_ml(true, modalId + "Loader");
					// call the next
					processQueue.employeeListingStep();
				});
			})
			.fail(failError);
	}

	/**
	 * Employee listing page
	 * step 2
	 */
	function employeeListingStep() {
		// stop the execution
		if (XHR !== null) {
			return false;
		}
		//
		XHR = $.ajax({
			url: baseUrl("payroll/company/" + companyId + "/partner/employees"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(failError)
			.success(function (resp) {
				// flush XHR
				XHR = null;
				// load the view
				$(`#${modalId}Body`).html(resp.view);
				// hide the loader
				_ml(false, modalId + "Loader");
				// attach trigger
				$(".jsPayrollLoadOnboard").click(function (event) {
					//
					event.preventDefault();
					//
					handleEmployeeSelection();
				});
				// attach trigger
				$(".jsBackToStep1").click(function (event) {
					//
					event.preventDefault();
					_ml(true, modalId + "Loader");
					// call the next
					processQueue.welcomeStep();
				});
			});
	}

	/**
	 * admin step
	 * step 3
	 */
	function adminStep() {
		// stop the execution
		if (XHR !== null) {
			return false;
		}
		//
		XHR = $.ajax({
			url: baseUrl("payroll/company/" + companyId + "/partner/admin"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(failError)
			.success(function (resp) {
				// flush XHR
				XHR = null;
				// load the view
				$(`#${modalId}Body`).html(resp.view);
				// hide the loader
				_ml(false, modalId + "Loader");
				// attach trigger
				$(".jsPayrollSetAdmin").click(function (event) {
					//
					event.preventDefault();
					//
					setAdminStep();
				});
				// attach trigger
				$(".jsBackToStep2").click(function (event) {
					//
					event.preventDefault();
					//
					_ml(true, modalId + "Loader");
					// call the next
					processQueue.employeeListingStep();
				});
			});
	}

	/**
	 * set admin step
	 * step 4
	 */
	function setAdminStep() {
		// stop the execution
		if (XHR !== null) {
			return false;
		}
		//
		XHR = $.ajax({
			url: baseUrl("payroll/company/" + companyId + "/partner/admin/add"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(failError)
			.success(function (resp) {
				// flush XHR
				XHR = null;
				// load the view
				$(`#${modalId}Body`).html(resp.view);
				// hide the loader
				_ml(false, modalId + "Loader");
			});
	}

	/**
	 * set the selected employees
	 * @returns
	 */
	function handleEmployeeSelection() {
		// reset the array
		selectedEmployees = [];
		// get the selected employees
		$(".jsEmployeesList:checked").map(function () {
			selectedEmployees.push($(this).val());
		});
		// validate
		if (!selectedEmployees.length) {
			return _error("Please select at least one employee.");
		}
		// show the loader
		_ml(true, modalId + "Loader");
		//
		processQueue.adminStep();
	}

	/**
	 * set the admin
	 *  step 5
	 * @returns
	 */
	function saveAdmin() {
		// reset the array
		let adminObj = {
			firstName: $(".jsAdminFirstName").val().trim(),
			lastName: $(".jsAdminLastName").val().trim(),
			email: $(".jsAdminEmailAddress").val().trim(),
			id: $(".jsAdminUserId").val().trim(),
		};
		// set default error array
		let errors = [];
		// validate
		if (!adminObj.firstName) {
			errors.push('"First name" is missing.');
		}
		if (!adminObj.lastName) {
			errors.push('"Last name" is missing.');
		}
		if (!adminObj.email) {
			errors.push('"Email" is missing.');
		}
		if (!adminObj.email.verifyEmail()) {
			errors.push('"Email" is invalid.');
		}
		// check and show errors
		if (errors.length) {
			return _error(getErrorsStringFromArray(errors));
		}
		// show the loader
		_ml(true, modalId + "Loader");
		//
		XHR = $.ajax({
			url: baseUrl("payroll/company/" + companyId + "/partner/admin/add"),
			method: "POST",
			data: adminObj,
		})
			.success(function () {
				//
				XHR = null;
				_ml(false, modalId + "Loader");
				//
				return _success(
					"Congratulations! You have successfully added a payroll admin for the company.",
					function () {
						processQueue.adminStep();
					}
				);
			})
			.fail(saveErrorsList);
	}

	/**
	 * view the admin
	 * step 6
	 * @returns
	 */
	function viewAdmin() {
		// show the loader
		_ml(true, modalId + "Loader");
		//
		XHR = $.ajax({
			url: baseUrl(`payroll/company/${companyId}/partner/admin/view`),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(failError)
			.success(function (resp) {
				// flush XHR
				XHR = null;
				// load the view
				$(`#${modalId}Body`).html(resp.view);
				// hide the loader
				_ml(false, modalId + "Loader");
			});
	}

	/**
	 * create partner company on Gusto
	 * step 7
	 */
	function createPartnerCompany() {
		//
		XHR = $.ajax({
			url: baseUrl(`payroll/company/${companyId}/partner/create`),
			method: "POST",
			data: {
				employees: selectedEmployees,
			},
		})
			.always(function () {
				XHR = null;
			})
			.fail(saveErrorsList)
			.success(function () {
				// flush XHR
				XHR = null;
				//
				if (typeof gustoServiceAgreement === "undefined") {
					return window.location.reload();
				}
				gustoServiceAgreement(companyId);
			});
	}

	/**
	 * show POST errors
	 * @param {object} err
	 * @returns
	 */
	function saveErrorsList(err) {
		// hide the loader
		_ml(false, modalId + "Loader");
		// flush XHR
		XHR = null;
		// show errors
		return handleErrorResponse(err);
	}

	/**
	 * handled error
	 * @param {object} err
	 * @returns
	 */
	function failError(err) {
		//
		$(`#${modalId} .jsModalCancel`).trigger("click");
		// show error
		return handleErrorResponse(err);
	}

	/**
	 * get the view
	 * @param {number} id
	 */
	function getAndSetView(id) {
		//
		ml(
			true,
			"jsCreateLoader",
			"Please wait, while we are generating view."
		);
		//
		$.ajax({
			url: baseUrl("/payrolls/employees/" + id + "/get"),
			method: "GET",
		})
			.success(function (resp) {
				//
				$(".jsAdminFirstName").val(resp.first_name || "");
				$(".jsAdminLastName").val(resp.last_name || "");
				$(".jsAdminEmailAddress").val(resp.email || "");
				$(".jsAdminUserId").val(resp.id || 0);
			})
			.fail(function (response) {
				window.scrollTo(0, 0);
				return $(".jsErrorDiv")
					.html(
						getErrorsStringFromArray(
							(
								response.responseJSON ||
								JSON.parse(response.responseText)
							).errors
						)
					)
					.removeClass("hidden");
			})
			.always(function () {
				// hide the loader
				ml(false, "jsCreateLoader");
			});
	}
});
