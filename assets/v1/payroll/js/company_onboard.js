/**
 * Create Partner Company On Gusto
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function CreatePartnerCompany() {
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
		welcomeStep,
		employeeListingStep,
		adminStep,
		createPartnerCompany,
		gustoTerms,
		pushCompanyAdmin,
		pushCompanyLocation,
		pushSelectedEmployees,
	};
	/**
	 * selected employees
	 */
	let selectedEmployees = [49248];
	/**
	 * capture the modal close event
	 */
	$(document).on("click", ".jsModalCancel", function () {
		XHR = null;
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
			return alertify.alert("ERROR", "Company code is missing.", CB);
		}
		// call the Modal
		Modal(
			{
				Id: modalId,
				Title: "",
				Loader: modalId + "Loader",
				Body: `<div id="${modalId}Body"></div>`,
			},
			processQueue.pushSelectedEmployees
			// processQueue.welcomeStep
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
			url: window.location.origin + "/payroll/cpc/1/" + companyId,
			method: "GET",
		})
			.success(function (resp) {
				// flush XHR
				XHR = null;
				// load the view
				$(`#${modalId}Body`).html(resp.view);
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
			url: window.location.origin + "/payroll/cpc/2/" + companyId,
			method: "GET",
		})
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
			})
			.fail(failError);
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
			url: window.location.origin + "/payroll/cpc/3/" + companyId,
			method: "GET",
		})
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
			})
			.fail(failError);
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
			url: window.location.origin + "/payroll/cpc/4/" + companyId,
			method: "GET",
		})
			.success(function (resp) {
				// flush XHR
				XHR = null;
				// load the view
				$(`#${modalId}Body`).html(resp.view);
				// hide the loader
				_ml(false, modalId + "Loader");
			})
			.fail(failError);
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
			return alertify.alert(
				"ERROR",
				"Please select at least one employee.",
				CB
			);
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
			return alertify.alert(
				"ERROR",
				getErrorsStringFromArray(errors),
				CB
			);
		}
		// show the loader
		_ml(true, modalId + "Loader");
		//
		XHR = $.ajax({
			url: window.location.origin + "/payroll/cpc/5/" + companyId,
			method: "POST",
			data: adminObj,
		})
			.success(function () {
				//
				XHR = null;
				//
				return alertify.alert(
					"SUCCESS",
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
			url: window.location.origin + "/payroll/cpc/6/" + companyId,
			method: "GET",
		})
			.success(function (resp) {
				// flush XHR
				XHR = null;
				// load the view
				$(`#${modalId}Body`).html(resp.view);
				// hide the loader
				_ml(false, modalId + "Loader");
			})
			.fail(failError);
	}

	/**
	 * create partner company on Gusto
	 * step 7
	 */
	function createPartnerCompany() {
		//
		XHR = $.ajax({
			url: window.location.origin + "/payroll/cpc/7/" + companyId,
			method: "POST",
			data: {
				employees: selectedEmployees,
			},
		})
			.success(function () {
				// flush XHR
				XHR = null;
				//
				processQueue.pushCompanyAdmin();
			})
			.fail(saveErrorsList);
	}

	/**
	 * sync gusto admins
	 * step 8
	 */
	function pushCompanyAdmin() {
		// change the loader text
		_ml(
			true,
			modalId + "Loader",
			"Creating payroll admins. This procedure may take a few minutes."
		);
		//
		XHR = $.ajax({
			url: window.location.origin + "/payroll/cpc/8/" + companyId,
			method: "POST",
			data: {
				employees: selectedEmployees,
			},
		})
			.success(function () {
				// flush XHR
				XHR = null;
				processQueue.gustoTerms();
			})
			.fail(saveErrorsList);
	}

	/**
	 * show gusto terms
	 * step 9
	 */
	function gustoTerms() {
		// change the loader text
		_ml(true, modalId + "Loader");
		//
		XHR = $.ajax({
			url: window.location.origin + "/payroll/cpc/9/" + companyId,
			method: "GET",
		})
			.success(function (resp) {
				// flush XHR
				XHR = null;
				//
				$("#" + modalId + "Body").html(resp.view);
				//
				_ml(false, modalId + "Loader");

				// add save trigger
				$(".jsPayrollAgreeServiceTerms").on(
					"click",
					handleServiceAgreementConsent
				);
			})
			.fail(saveErrorsList);
	}

	/**
	 * handle Gusto service terms agreement
	 * step 10
	 */
	function handleServiceAgreementConsent(event) {
		event.preventDefault();
		//
		const obj = {
			email: $("#jsTermsOfServiceEmail").val().trim(),
			userCode: $("#jsTermsOfServiceReference").val().trim(),
		};
		// holds errors
		const errorArray = [];
		// validation
		if (!obj.email) {
			errorArray.push('"Email" is required.');
		}
		if (!obj.email.verifyEmail()) {
			errorArray.push('"Email" is invalid.');
		}
		if (!obj.userCode) {
			errorArray.push('"System User Reference" is required.');
		}
		// check and show errors
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		// show loader
		_ml(true, modalId + "Loader");
		//
		$.ajax({
			url: window.location.origin + "/payroll/cpc/10/" + companyId,
			method: "POST",
			data: obj,
		})
			.success(function () {
				// move to next part
				_ml(false, modalId + "Loader");
				// sync company location
				processQueue.pushCompanyLocation();
			})
			.fail(saveErrorsList);
	}

	/**
	 * sync gusto admins
	 * step 11
	 */
	function pushCompanyLocation() {
		// change the loader text
		_ml(
			true,
			modalId + "Loader",
			"Creating company location. This procedure may take a few minutes."
		);
		//
		XHR = $.ajax({
			url: window.location.origin + "/payroll/cpc/11/" + companyId,
			method: "POST",
			data: {},
		})
			.success(function () {
				// flush XHR
				XHR = null;
				processQueue.pushSelectedEmployees();
			})
			.fail(saveErrorsList);
	}
	
	/**
	 * sync gusto admins
	 * step 12
	 */
	function pushSelectedEmployees() {
		// change the loader text
		_ml(
			true,
			modalId + "Loader",
			"Onboarding selected employees. This procedure may take a few minutes."
		);
		//
		XHR = $.ajax({
			url: window.location.origin + "/payroll/cpc/12/" + companyId,
			method: "POST",
			data: {
				employees: selectedEmployees
			},
		})
			.success(function () {
				// flush XHR
				XHR = null;
			})
			.fail(saveErrorsList);
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

	companyId = 28684;
	$(".jsCreatePartnerCompanyBtn").trigger("click");
});
