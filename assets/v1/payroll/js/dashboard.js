/**
 * Dashboard
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function dashboard() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * capture the view admin event
	 */
	$(".jsSyncCompanyData").click(function (event) {
		//
		event.preventDefault();
		//
		syncCompanyWithGusto();
	});
	/**
	 * capture the view admin event
	 */
	$("#jsVerifyBankAccount").click(function (event) {
		//
		event.preventDefault();
		//
	});

	/**
	 *
	 * @returns
	 */
	function syncCompanyWithGusto() {
		//
		if (XHR !== null) {
			return false;
		}
		//
		$("#jsSyncCompanyData span").html("Syncing...");
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/company/sync"),
			method: "GET",
		})
			.success(function () {
				return alertify.alert(
					"Success!",
					"Company is synced with Gusto",
					CB
				);
			})
			.fail(function (response) {
				return alertify.alert(
					"Error!",
					getErrorsStringFromArray(
						(
							response.responseJSON ||
							JSON.parse(response.responseText)
						).errors
					)
				);
			})
			.always(function () {
				XHR = null;
				ml(false, "jsDashboard");
				$("#jsSyncCompanyData span").html("Sync");
			});
	}

	// hides the loader
	ml(false, "jsDashboard");
});
