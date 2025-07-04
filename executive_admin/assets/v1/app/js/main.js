/**
 * Validate email address
 * @returns
 */
String.prototype.verifyEmail = function () {
	return this.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g) === null
		? false
		: true;
};

String.prototype.isValidYoutubeLink = function () {
	return this.match(
		/^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.be)\/.+$/g
	) === null
		? false
		: true;
};

String.prototype.isValidVimeoLink = function () {
	return this.match(
		/^(http|https)?:\/\/(www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)$/gim
	) === null
		? false
		: true;
};

String.prototype.isValidInteger = function () {
	return this.match(/^[1-9]\d*$/g) === null ? false : true;
};

if (typeof ml === "undefined") {
	/**
	 * Loader
	 *
	 * @param {bool}   action
	 * @param {string} id
	 */
	function ml(action, id) {
		//
		if (action) {
			$(".jsIPLoader[data-page='" + id + "']").show();
		} else {
			$(".jsIPLoader[data-page='" + id + "']").hide();
		}
	}
}

if (typeof CB === "undefined") {
	/**
	 * Empty callback
	 */
	function CB() {}
}

if (typeof getErrorsStringFromArray === "undefined") {
	/**
	 * Error message
	 *
	 * @param {*} errorArray
	 * @param {*} errorMessage
	 * @returns
	 */
	function getErrorsStringFromArray(errorArray, errorMessage) {
		return (
			"<strong><p>" +
			(errorMessage
				? errorMessage
				: "Please, resolve the following errors") +
			"</p></strong><br >" +
			errorArray.join("<br />")
		);
	}
}

if (typeof getBrowserVersion === "undefined") {
	function getBrowserVersion() {
		var ua = navigator.userAgent;
		var tem;
		var M =
			ua.match(
				/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i
			) || [];
		if (/trident/i.test(M[1])) {
			tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
			return "IE " + (tem[1] || "");
		}
		if (M[1] === "Chrome") {
			tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
			if (tem != null)
				return tem.slice(1).join(" ").replace("OPR", "Opera");
		}
		M = M[2]
			? [M[1], M[2]]
			: [navigator.appName, navigator.appVersion, "-?"];
		if ((tem = ua.match(/version\/(\d+)/i)) != null) M.splice(1, 1, tem[1]);
		return M;
	}
}

if (typeof generateBrowserAlert === "undefined") {
	function generateBrowserAlert() {
		//
		const compatibleListObj = {
			chrome: 55,
			edge: 14,
			firefox: 52,
			opera: 42,
			safari: 10.1,
			chromeandroid: 55,
			firefoxandroid: 52,
			operaandroid: 42,
			safariios: 10.3,
			samsumginternet: 6.0,
		};
		// get browser version
		const browserVersion = getBrowserVersion();
		//
		if (
			compatibleListObj[browserVersion[0].toLowerCase()] &&
			browserVersion[1] <
				compatibleListObj[browserVersion[0].toLowerCase()]
		) {
			return alert(
				"This module require '" +
					browserVersion[0] +
					"' with version greater or equal then '" +
					browserVersion[1] +
					"'."
			);
		}
	}
}

if (typeof uploadFile === "undefined") {
	/**
	 * Upload file to the server
	 * @param {*} fileObject
	 * @returns
	 */
	function uploadFile(fileObject) {
		return new Promise(function (resolve) {
			// create form instance
			const formData = new FormData();
			// set the file object
			formData.append("file", fileObject);
			// push the file to server
			$.ajax({
				url: apiURL + "uploader",
				method: "POST",
				timeout: 0,
				processData: false,
				mimeType: "multipart/form-data",
				contentType: false,
				data: formData,
			})
				.success(function (response) {
					resolve(response);
				})
				.fail(function (error) {
					resolve(error.responseText);
				});
		});
	}
}

if (typeof uploadStream === "undefined") {
	/**
	 * Upload stream to the server
	 * @param {*} streamData
	 * @returns
	 */
	function uploadStream(streamData) {
		return new Promise(function (resolve) {
			// push the file to server
			$.ajax({
				url: apiURL + "uploader/stream",
				method: "POST",
				timeout: 0,
				contentType: "application/json",
				data: JSON.stringify({ stream: streamData }),
			})
				.success(function (response) {
					resolve(response);
				})
				.fail(function () {
					resolve({});
				});
		});
	}
}

if (typeof $ !== "undefined") {
	// set filter height
	$(".jsFilterPanel").height($(document).height());
	//
	$(document).on("click", ".jsFilterSectionBtn", function (event) {
		//
		event.preventDefault();
		//
		let key = $(this).data("key");
		//
		$(".jsFilterSection[data-key='" + key + "']").removeClass("hidden");
	});

	//
	$(document).on("click", ".jsFilterSectionHideBtn", function (event) {
		//
		event.preventDefault();
		//
		let key = $(this).data("key");
		//
		$(".jsFilterSection[data-key='" + key + "']").addClass("hidden");
	});

	//
	$(document).on("click", ".jsExpandAdminView", function (event) {
		//
		event.preventDefault();
		//
		$(this).toggleClass("btn-success");
		$(".jsExpandContent").toggleClass(
			"col-sm-12 col-md-12 col-lg-12 col-xs-12 col-lg-9 col-md-9 col-sm-9"
		);
		$(".jsExpandSideBar").toggleClass("hidden");
	});
}

if (typeof handleErrorResponse === "undefined") {
	/**
	 * Handle AJAX errors
	 * @param {*} response
	 * @returns
	 */
	function handleErrorResponse(response) {
		// when connection is lost
		if (response.status == 0) {
			//
			return alertify.alert(
				"Errors!",
				"The connection to the server has been lost. Kindly reach out to the system administrator for assistance.",
				CB
			);
		}
		//
		const parsedJSON =
			response.responseJSON || JSON.parse(response.responseText);
		// when error object came in
		return alertify.alert("Errors!", parsedJSON.errors.join("<br />"), CB);
	}
}

if (typeof getQuestionsFromArray === "undefined") {
	/**
	 * Error message
	 *
	 * @param {*} errorArray
	 * @param {*} errorMessage
	 * @returns
	 */
	function getQuestionsFromArray(errorArray, errorMessage) {
		return (
			"<strong><p>" +
			(errorMessage
				? errorMessage
				: "Please, provide the following question answer") +
			"</p></strong><br >" +
			errorArray.join("<br />")
		);
		let json = response.responseJSON || JSON.parse(response.responseText);
		// when error object came in
		return alertify.alert("Errors!", json.errors.join("<br />"), CB);
	}
}

if (typeof baseUrl === "undefined") {
	/**
	 * get the base url
	 *
	 * @param {string} appendUrl
	 * @returns
	 */
	function baseUrl(appendUrl = "") {
		// return the url
		return window.location.origin + "/" + appendUrl;
	}
}


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

	// hides the loader
	ml(false, "jsDashboard");
});


