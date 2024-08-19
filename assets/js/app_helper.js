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

/**
 * converts a string to a slug
 * @returns string
 */
String.prototype.toSlug = function () {
	return this.replace(/[^a-z0-9]/gi, "-")
		.replace(/-+/g, "-")
		.replace(/-$/, "")
		.toLowerCase();
};

if (typeof ml === "undefined") {
	/**
	 * Loader
	 *
	 * @param {bool}   action
	 * @param {string} id
	 * @param {string} msg
	 */
	function ml(action, id, msg) {
		//
		if (action) {
			$(".jsIPLoader[data-page='" + id + "']").show();
			$(".jsIPLoader[data-page='" + id + "']")
				.find(".jsIPLoaderText")
				.html(
					msg || "Please wait, while we are processing your request."
				);
		} else {
			$(".jsIPLoader[data-page='" + id + "']").hide();
			$(".jsIPLoader[data-page='" + id + "']")
				.find(".jsIPLoaderText")
				.html("Please wait, while we are processing your request.");
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
				url: window.location.origin + "uploader",
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
				url: window.location.origin + "uploader/stream",
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
		// Page not found
		if (response.status == 404) {
			//
			return _error("The requested route doesn't exists.");
		}
		//
		const parsedJSON =
			response.responseJSON || JSON.parse(response.responseText);
		//
		alertify.closeAll();
		// when error object came in
		return alertify
			.alert("Errors!", parsedJSON.errors.join("<br />"), CB)
			.setting({
				autoReset: true,
				frameless: false,
			});
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
				: "Please, provide the following question answer.") +
			"</p></strong><br >" +
			errorArray.join("<br />")
		);
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
		appendUrl = appendUrl.replace(/^\//g, "");
		// return the url
		return window.location.origin + "/" + appendUrl;
	}
}

if (typeof callButtonHook === "undefined") {
	/**
	 * button hook
	 *
	 * @param {object} reference
	 * @param {bool}   doShow
	 * @param {bool}   true
	 * @return
	 */
	function callButtonHook(reference, doShow = true, type = false) {
		//
		if (doShow) {
			const obj = {
				pointer: reference,
				html: reference.html(),
			};
			reference.html(
				type
					? '<i class="fa-solid fa-circle-notch fa-spin csW csF16" aria-hidden="true"></i>'
					: '<i class="fa fa-circle-o-notch fa-spin csW csF16" aria-hidden="true"></i>'
			);
			//
			reference.off("click");
			return obj;
		}
		//
		reference.pointer.html(reference.html);
	}
}

if (typeof _error === "undefined") {
	/**
	 * shows the error
	 *
	 * @param {string} msg
	 */
	function _error(msg, callback = CB) {
		alertify
			.alert("Error!", msg, callback)
			.setHeader("Error!")
			.set("labels", {
				ok: "Ok",
				cancel: "cancel",
			})
			.setting({
				autoReset: true,
				frameless: false,
			});
	}
}

if (typeof _success === "undefined") {
	/**
	 * shows the success
	 *
	 * @param {string} msg
	 */
	function _success(msg, callback = CB) {
		alertify
			.alert("Success!", msg, callback)
			.setHeader("Success!")
			.set("labels", {
				ok: "Ok",
				cancel: "cancel",
			})
			.setting({
				autoReset: true,
				frameless: false,
			});
	}
}

if (typeof _confirm === "undefined") {
	/**
	 * confirm message
	 *
	 * @param {string} msg
	 */
	function _confirm(msg, callback) {
		alertify
			.confirm(msg, callback, CB)
			.setHeader("Confirm")
			.set("labels", {
				ok: "Yes",
				cancel: "No",
			})
			.setting({
				autoReset: true,
				frameless: false,
			});
	}
}

if (typeof _showNotification === "undefined") {
	/**
	 * show message
	 *
	 * @param {string} msg
	 * @returns
	 */
	function _showNotification(msg) {
		return alertify
			.alert(
				'<div class="row"> \
					<div class="col-sm-12">\
						<p class="csF16 text-center" style="margin-top: 25px;">' +
					msg +
					"</p> \
					<div> \
				</div>"
			)
			.setting({
				closable: false,
				frameless: true,
				autoReset: true,
			});
	}
}

if (typeof getSegment === "undefined") {
	/**
	 * Get the segment from the URL
	 * @param {int} segment
	 * @returns
	 */
	function getSegment(segment) {
		// get the segments
		let segmentArray = window.location.pathname.split("/");
		// clean the array
		segmentArray = segmentArray?.filter(function (single_segment) {
			//
			return single_segment?.trim();
		});
		// check if found
		return segmentArray[segment];
	}
}

if (typeof getRandomCode === "undefined") {
	/**
	 * Generates a random number
	 * @returns
	 */
	function getRandomCode() {
		// create a new date object
		const dt = new Date();
		// get the segments
		return dt.getTime() + Math.floor(Math.random() * 5000000);
	}
}

if (typeof closeAlert === "undefined") {
	/**
	 * Generates a random number
	 * @returns
	 */
	function closeAlert(reference) {
		reference.setting("closable", true).close();
	}
}

if (typeof formArrayToObj === "undefined") {
	/**
	 * Converts form array to object
	 * @param {array} formArray
	 * @param {bool} toPlainObject
	 * @returns
	 */
	function formArrayToObj(formArray, toPlainObject = false) {
		//
		if (toPlainObject) {
			let formObj = {};
			formArray.map(function (v) {
				formObj[v.name] = v.value;
			});
			return formObj;
		}
		//
		let formObj = new FormData();
		formArray.map(function (i) {
			formObj.append(i.name, i.value);
		});
		return formObj;
	}
}

if (typeof nl2br === "undefined") {
	function nl2br(str, is_xhtml) {
		if (typeof str === "undefined" || str === null) {
			return "";
		}
		str = str.replace(/<br(.*?)>/gi, "");
		let breakTag =
			is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
		return (str + "").replace(
			/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
			"$1" + breakTag + "$2"
		);
	}
}

if (typeof isValidFile === "undefined") {
	/**
	 * Check if the uploaded file is valid or not
	 * @returns
	 */
	function isValidFile(fileObj) {
		return Object.keys(fileObj).length !== 0 && fileObj.hasError === false;
	}
}

if (window.location.refresh === undefined) {
	/**
	 * Check if the uploaded file is valid or not
	 * @returns
	 */
	window.location.refresh = function () {
		window.location.href = window.location.href;
	};
}

/**
 * validation addons
 */
if (jQuery !== undefined && jQuery.validator !== undefined) {
	// check time in 12 hours format
	jQuery.validator.addMethod(
		"timeIn12Format",
		function (value) {
			return value.match(/^\d{1,2}:\d{1,2}\s(pm|am)$/gi);
		},
		"The time format is invalid."
	);
	// value should be greater than 0
	jQuery.validator.addMethod(
		"greaterThanZero",
		function (value) {
			return value > 0;
		},
		"The value must be greater than zero."
	);
}

if (typeof getSearchParam === "undefined") {
	/**
	 * get the search parameter
	 * @param {string} index
	 * @returns
	 */
	function getSearchParam(index) {
		//
		const param = new URLSearchParams(window.location.search);
		//
		return param.get(index) || false;
	}
}

if (typeof convertSecondsToTime === "undefined") {
	/**
	 * converts seconds to hours and minutes
	 * @param {int} durationInSeconds
	 */
	function convertSecondsToTime(durationInSeconds) {
		// Convert seconds to hours and minutes
		const hours = Math.floor(durationInSeconds / 3600);
		const minutes = Math.floor((durationInSeconds % 3600) / 60);
		//
		return hours + "h" + (minutes > 0 ? " " + minutes + "m" : "");
	}
}

if (typeof _cur === "undefined") {
	/**
	 * converts a number to currency
	 * @param {int} numberWithoutSymbol
	 */
	function _cur(numberWithoutSymbol, convertToNumber = false) {
		return numberWithoutSymbol
			? convertToNumber
				? numberWithCommas(parseFloat(numberWithoutSymbol).toFixed(2))
				: parseFloat(numberWithoutSymbol).toFixed(2)
			: numberWithoutSymbol;
	}
}

if (typeof numberWithCommas === "undefined") {
	/**
	 * converts a number to currency
	 * @param {int} numberWithoutSymbol
	 */
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
}
