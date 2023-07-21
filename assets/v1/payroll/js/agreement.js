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
			() => {
				alert("happening");
			}
		);
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
