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
	// holds company id
	const companyId = getSegment(2);
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
	 * save company payment configuration
	 */
	$(".jsSaveConfiguration").click(function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			fast_speed_limit: $("#jsFastPaymentLimit").val().trim() || 0,
			payment_speed: $("#jsPaymentSpeed option:selected").val(),
		};
		//
		updateCompanyPaymentConfiguration(obj);
	});

	/**
	 * update mode
	 */
	$("#jsCompanyModeForm").submit(function (event) {
		event.preventDefault();
		//
		const obj = {
			mode: $("#jsCompanyMode option:selected").val(),
		};
		//
		updateCompanyMode(obj);
	});

	/**
	 *
	 * @returns
	 */
	function updateCompanyPaymentConfiguration(dataOBJ) {
		//
		if (XHR !== null) {
			return false;
		}
		//
		$(".jsSaveConfiguration span").html("Syncing...");
		//
		XHR = $.ajax({
			url: baseUrl(
				"sa/payrolls/company/" + companyId + "/payment/configuration"
			),
			method: "POST",
			data: dataOBJ,
		})
			.success(function () {
				return alertify.alert(
					"Success!",
					"Company payment configuration successfully updated.",
					CB
				);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				$(".jsSaveConfiguration span").html(
					"Save Payment Configuration"
				);
			});
	}

	/**
	 *
	 * @returns
	 */
	function updateCompanyMode(dataOBJ) {
		//
		if (XHR !== null) {
			return false;
		}

		const btnHook = callButtonHook($(".jsCompanyModeBtn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("sa/payrolls/company/" + companyId + "/mode"),
			method: "POST",
			data: dataOBJ,
		})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				callButtonHook(btnHook, false);
			})
			.success(function (resp) {
				console.log(resp)
				return _success(resp.msg, function () {
					window.location.reload();
				});
			});
	}

	/**
	 * save company configuration
	 */
	$(".jsSaveDefaultAdmin").click(function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			first_name: $("#jsFirstName").val().trim(),
			last_name: $("#jsLastName").val().trim(),
			email_address: $("#jsEmail").val().trim(),
		};
		const errorsArray = [];
		// validation
		if (!obj.first_name) {
			errorsArray.push('"First name" is required.');
		}
		if (!obj.last_name) {
			errorsArray.push('"Last name" is required.');
		}
		if (!obj.email_address) {
			errorsArray.push('"Email" is required.');
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		saveCompanyPrimaryAdmin(obj);
	});

	/**
	 *
	 * @returns
	 */
	function saveCompanyPrimaryAdmin(dataOBJ) {
		//
		if (XHR !== null) {
			return false;
		}
		//
		$(".jsSaveDefaultAdmin span").html("Saving...");
		//
		XHR = $.ajax({
			url: baseUrl("sa/payrolls/company/" + companyId + "/primary/admin"),
			method: "POST",
			data: dataOBJ,
		})
			.success(function () {
				return alertify.alert(
					"Success!",
					"Company primary admin save successfully.",
					CB
				);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				$(".jsSaveDefaultAdmin span").html("Save Primary Admin");
			});
	}

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
			url: baseUrl("sa/payrolls/company/" + companyId + "/sync"),
			method: "GET",
		})
			.success(function () {
				return alertify.alert(
					"Success!",
					"Company is synced with Gusto",
					CB
				);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
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
			url: baseUrl("sa/payrolls/company/" + companyId + "/bank/verify"),
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
			url: baseUrl("sa/payrolls/company/" + companyId + "/verify"),
			method: "POST",
		})
			.success(function () {
				return alertify.alert("Success!", "Company is verified.", CB);
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
