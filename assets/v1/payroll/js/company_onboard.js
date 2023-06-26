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
	let processQueue = [
		welcomeStep,
		employeeListingStep,
		adminStep,
		createPartnerCompany,
		pushCompanyAdmin,
	];
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
			processQueue[0]
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
		processQueue[2]();
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
			processQueue[3]();
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
					processQueue[1]();
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
					processQueue[0]();
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
					processQueue[1]();
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
		// if (!selectedEmployees.length) {
		// 	return alertify.alert(
		// 		"ERROR",
		// 		"Please select at least one employee.",
		// 		CB
		// 	);
		// }
		// show the loader
		_ml(true, modalId + "Loader");
		//
		processQueue[2]();
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
						processQueue[2]();
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
				processQueue[4]();
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
				// processQueue[5]();
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
	// companyId = 28684;
	// processQueue[4]();
});
