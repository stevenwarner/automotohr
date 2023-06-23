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
        employeeListingStep
    ];

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
			url: window.location.origin + "/payroll/cpc/1",
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
			url: window.location.origin + "/payroll/cpc/2",
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
});
