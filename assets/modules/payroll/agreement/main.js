/**
 * Gusto service agreement
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function serviceAgreement() {
	/**
	 * set company id
	 */
	let companyId = 0;
	/**
	 * set modal reference
	 */
	let modalId = "jsAgreementModal";
	/**
	 * set XHR request holder
	 */
	let XHR = null;

	/**
	 * capture the modal close event
	 */
	$(document).on("click", ".jsModalCancel", function () {
		XHR = null;
	});

	/**
	 * capture the create partner company click
	 */
	$(".jsServiceAgreement").click(function (event) {
		// stop the default behavior
		event.preventDefault();
		//
		gustoServiceAgreement(parseInt($(this).data("cid")));
	});

	$(document).on("change", "#jsTermsOfServiceEmail", function () {
		//
		$("#jsTermsOfServiceReference").val(
			$("#jsTermsOfServiceEmail option:selected").data("id")
		);
	});

	/**
	 * capture the create partner company click
	 */
	$(document).on("click", ".jsPayrollAgreeServiceTerms", function (event) {
		// stop the default behavior
		event.preventDefault();
		//
		const obj = {
			email: $("#jsTermsOfServiceEmail option:selected").val(),
			userReference: $("#jsTermsOfServiceReference")
				.val()
				.replace(/[^0-9]/g, ""),
		};
		//
		let errorArray = [];
		// validation
		if (!obj.email) {
			errorArray.push('"Email" is required.');
		}
		if (!obj.email.verifyEmail()) {
			errorArray.push('"Email" is invalid.');
		}
		if (!obj.userReference) {
			errorArray.push('"System User Reference" is required.');
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
		agreeToTerms(obj);
	});

	/**
	 * starts the process
	 * @param {*} companyCode
	 * @returns
	 */
	function gustoServiceAgreement(companyCode) {
		//
		companyId = companyCode;
		// check if company id is not set
		if (companyId === 0) {
			return alertify.alert("ERROR", "Company code is missing.", CB);
		}
		// call the Modal
		Modal(
			{
				Id: modalId,
				Title: "Service Agreement",
				Loader: modalId + "Loader",
				Body: `<div id="${modalId}Body"></div>`,
			},
			getAgreement
		);
	}

	function getAgreement() {
		//
		XHR = $.ajax({
			url: baseUrl(`payroll/company/${companyId}/agreement/`),
			method: "GET",
		})
			.success(function (resp) {
				//
				XHR = null;
				// append tlo body
				$(`#${modalId}Body`).html(resp.view);
				// hides the loader
				_ml(false, `${modalId}Loader`);
			})
			.fail(failError);
	}

	function agreeToTerms(passOBJ) {
		//
		_ml(true, `${modalId}Loader`);
		//
		XHR = $.ajax({
			url: baseUrl(`payroll/company/${companyId}/agreement/sign`),
			method: "POST",
			data: passOBJ,
		})
			.success(function () {
				//
				XHR = null;
				// hides the loader
				_ml(false, `${modalId}Loader`);
				//
				return alertify.alert(
					"Success!",
					"You have successfully signed the agreement.",
					function () {
						//
						$(`#${modalId} .jsModalCancel`).trigger("click");
						//
						window.location.reload();
					}
				);
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

	//
	window.gustoServiceAgreement = gustoServiceAgreement;
});
