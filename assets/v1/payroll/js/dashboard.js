/**
 * Dashboard
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function dashboard() {
	$(document).ready(function () {
		updateCompanySyncProgress();
	});
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
		ml(true, "jsDashboard");
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/company/sync"),
			method: "GET",
		})
			.success(function () {
				updateCompanySyncProgress()
				// return alertify.alert(
				// 	"Success!",
				// 	"Company is synced with Gusto",
				// 	CB
				// );
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
	function updateCompanySyncProgress() {
		//
		if (XHR !== null) {
			return false;
		}
		//
		ml(true, "jsDashboard");
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/company/get_company_sync_progress"),
			method: "GET",
		})
			.success(function (resp) {
				if (resp.status == 'completed') {
					ml(false, "jsDashboard");
				} else if (resp.status == 'error') {
					return alertify.alert(
						"Error!",
						resp.message
					);
				} else if (resp.status == 'processing') {
					$('.jsIPLoaderText').text(resp.message);
					//
					setTimeout(() => {
						updateCompanySyncProgress();
					}, 4000);
				}
				
			})
			.fail(function (response) {
				return alertify.alert(
					"Error!",
					"Something went wrong."
				);
			})
			.always(function () {
				XHR = null;
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
