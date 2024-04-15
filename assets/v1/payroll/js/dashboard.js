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
	let XHR2 = null;
	let XHR3 = null;
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
	$(".jsVerifyBankAccount").click(function (event) {
		//
		event.preventDefault();
		//
		verifyCompanyBankAccount();
	});

	/**
	 * capture the view admin event
	 */
	$(".jsVerifyCompany").click(function (event) {
		//
		event.preventDefault();
		//
		verifyCompany();
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
		$(".jsSyncCompanyData span").html("Syncing...");
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
				$(".jsSyncCompanyData span").html("Sync");
			});
	}

	/**
	 *
	 * @returns
	 */
	function verifyCompanyBankAccount() {
		//
		if (XHR2 !== null) {
			return false;
		}
		//
		$(".jsVerifyBankAccount span").html("Verifying...");
		//
		XHR2 = $.ajax({
			url: baseUrl("payrolls/company/bank/verify"),
			method: "GET",
		})
			.success(function () {
				return alertify.alert(
					"Success!",
					"Company bank accounts are verified.",
					CB
				);
			})
			.fail(function (response) {
				console.log(response.responseJSON.errors)
				return alertify.alert(
					"Error!",
					response.responseJSON.errors
				);
			})
			.always(function () {
				XHR2 = null;
				ml(false, "jsDashboard");
				$(".jsVerifyBankAccount span").html("Verify");
			});
	}
	
	/**
	 *
	 * @returns
	 */
	function verifyCompany() {
		//
		if (XHR3 !== null) {
			return false;
		}
		//
		$(".jsVerifyCompany span").html("Verifying...");
		//
		XHR3 = $.ajax({
			url: baseUrl("payrolls/company/verify"),
			method: "POST",
		})
			.success(function () {
				return alertify.alert(
					"Success!",
					"Company is verified.",
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
				XHR3 = null;
				ml(false, "jsDashboard");
				$(".jsVerifyCompany span").html("Verify");
			});
	}

	// hides the loader
	ml(false, "jsDashboard");
});
