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

if (typeof callButtonHook === "undefined") {
	/**
	 * button hook
	 *
	 * @param {object} appendUrl
	 * @param {bool}   doShow
	 * @return
	 */
	function callButtonHook(reference, doShow = true) {
		//
		if (doShow) {
			const obj = {
				pointer: reference,
				html: reference.html(),
			};
			reference.html(
				'<i class="fa fa-circle-o-notch fa-spin csW csF16" aria-hidden="true"></i>'
			);
			//
			reference.off('click');
			return obj;
		}
		//
		reference.pointer.html(reference.html);
	}
}


(function () {
	let modelId;
	let additionalHeight = 0;
	// Modal
	function Modal(options, cb) {
		//
		let html = `
			<!-- Custom Modal -->
			<div class="csModal jsMsModal" id="${options.Id}">
				<div class="${options.Cl ? options.Cl : "container"}">
					<div class="csModalHeader">
						<h3 class="csModalHeaderTitle">
							<span>${options.Title}</span>
							<span class="csModalButtonWrap">
							${
								options.Buttons !== undefined &&
								options.Buttons.length !== 0
									? options.Buttons.join("")
									: ""
							}
								<button class="btn btn-black btn-cancel csW jsModalCancel" ${
									options.Ask === undefined
										? ""
										: 'data-ask="no"'
								} title="Close this window">Cancel</button>
							</span>
							<div class="clearfix"></div>
						</h3>
					</div>
					<div class="csModalBody">
						<div class="csIPLoader jsIPLoader" data-page="${options.Loader}">
							<div class="csIPLoaderBox">
								<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i><br><br>
								<br>
								<span class="jsIPLoaderText">Please wait while we process your request.</span>
							</div>
						</div>
						${options.Body}
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			`;
		// save the current modal reference
		modelId = options.Id;
		// save the header height
		additionalHeight = $(`#${options.Id} .csModalHeader`).height() + 50;
		// remove the modals
		$(`.jsMsModal`).remove();
		// remove specific modal
		$(`#${options.Id}`).remove();
		// append the modal to body
		$("body").append(html);
		// show the modal
		$(`#${options.Id}`).fadeIn(300);
		// set overflow of body to none
		$("body").css("overflow-y", "hidden");
		// set the header height
		$(`#${options.Id} .csModalBody`).css("top", additionalHeight);
		// call the CallBack if set
		if (cb !== undefined) {
			cb();
		}
	}

	/**
	 * Captures the modal close event
	 */
	$(document).on("click", ".jsModalCancel", (e) => {
		//
		e.preventDefault();
		//
		if ($(e.target).data("ask") == "yes") {
			//
			alertify
				.confirm("Any unsaved changes will be lost.", () => {
					//
					$(e.target).closest(".csModal").fadeOut(300);
					//
					$("body").css("overflow-y", "auto");
					//
					$("#ui-datepicker-div").remove();
					$(`.jsMsModal`).remove();
				})
				.set("labels", {
					ok: "LEAVE",
					cancel: "NO, I WILL STAY",
				})
				.set("title", "Notice!");
		} else {
			//
			$(e.target).closest(".csModal").fadeOut(300);
			//
			$("body").css("overflow-y", "auto");
			$(`.jsMsModal`).remove();
			//
			$("#ui-datepicker-div").remove();
		}
	});

	/**
	 * Handles loader show and hide
	 *
	 * @param {bool} doShow
	 * @param {string} p
	 * @param {string} msg
	 */
	function ml(doShow, p, msg) {
		// set the loader reference
		p = p === undefined ? `.jsIPLoader` : `.jsIPLoader[data-page="${p}"]`;
		// when modal is set
		if (modelId !== undefined) {
			// always scroll to top when loader appear
			if (document.getElementsByClassName("csModalBody").length) {
				document.getElementsByClassName("csModalBody")[0].scrollTop = 0;
			}
		}
		// only appears when loader is shown
		if (modelId !== undefined && doShow) {
			// set the loader height to body height
			$(".jsIPLoader").height(
				$(`#${modelId}Body`).height() + additionalHeight
			);
		}
		// hide the modal
		if (doShow === undefined || doShow === false) $(p).hide();
		else $(p).show();
		// place text
		if (msg !== undefined) {
			$(".jsIPLoaderText").text(msg);
		}
		// set to default text
		if (!doShow) {
			//
			$(".jsIPLoaderText").text(
				"Please wait, while we are generating a preview."
			);
		}
	}
	// set reference to window
	window._ml = window.ml = ml;
	window.Model = window.Modal = Modal;
})();


/**
 * Manage employee onboard
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function manageEmployees() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * holds the employee id
	 */
	let employeeId = 16;

	//
	let callQueue = {};
	/**
	 * holds the modal id
	 */
	let modalId = "jsEmployeeFlowModal";
	/**
	 * capture the view admin event
	 */
	$(".jsPayrollEmployeeEdit").click(function (event) {
		//
		event.preventDefault();
		//
		employeeId = $(this).closest("tr").data("id");
		//
		employeeOnboardFlow();
	});

	/**
	 * capture the view admin event
	 */
	$(document).on("click", ".jsMenuTrigger", function (event) {
		//
		event.preventDefault();
		//
		loadView($(this).data("step"));
	});

	/**
	 * Personal details triggers
	 */
	$(document).on(
		"click",
		".jsEmployeeFlowSavePersonalDetailsBtn",
		function (e) {
			//
			e.preventDefault();
			//
			if (XHR !== null) {
				return false;
			}
			//
			let obj = {
				first_name: $(".jsEmployeeFlowFirstName").val().trim(),
				middle_initial: $(".jsEmployeeFlowMiddleInitial").val().trim(),
				last_name: $(".jsEmployeeFlowLastName").val().trim(),
				location_uuid: $(
					".jsEmployeeFlowWorkAddress option:selected"
				).val(),
				start_date: $(".jsEmployeeFlowStartDate").val().trim(),
				email: $(".jsEmployeeFlowEmail").val().trim(),
				ssn: $(".jsEmployeeFlowSSN").val().trim(),
				date_of_birth: $(".jsEmployeeFlowDateOfBirth").val().trim(),
			};
			//
			let errorArray = [];
			// validation
			if (!obj.first_name) {
				errorArray.push('"First name" is missing.');
			}
			if (!obj.last_name) {
				errorArray.push('"Last name" is missing.');
			}
			if (!obj.location_uuid) {
				errorArray.push('"Work address" is missing.');
			}
			if (!obj.start_date) {
				errorArray.push('"Start date" is missing.');
			}
			if (!obj.email) {
				errorArray.push('"Email" is missing.');
			}
			if (!obj.email.verifyEmail()) {
				errorArray.push('"Email" is invalid.');
			}
			if (!obj.ssn) {
				errorArray.push('"Social Security number (SSN)" is missing.');
			}
			if (
				obj.ssn.replace(/-/g, "").length !== 9 ||
				obj.ssn.match(/([^0-9#-])/gi) !== null
			) {
				errorArray.push(
					'"Social Security number (SSN)" must be 9 digits long.'
				);
			}
			if (!obj.date_of_birth) {
				errorArray.push('"Date of birth" is missing.');
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
			ml(
				true,
				`${modalId}Loader`,
				"Please wait, while we are processing your request."
			);
			//
			XHR = $.ajax({
				url: baseUrl(
					"payrolls/flow/employee/" + employeeId + "/personal_details"
				),
				method: "POST",
				data: obj,
			})
				.success(function (resp) {
					//
					return alertify.alert("Success!", resp.msg, CB);
				})
				.fail(handleErrorResponse)
				.always(function () {
					//
					XHR = null;
					//
					ml(false, `${modalId}Loader`);
				});
		}
	);

	/**
	 * Compensation triggers
	 */
	$(document).on("click", ".jsEmployeeFlowSaveCompensationBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			title: $(".jsEmployeeFlowJobTitle").val().trim(),
			amount: $(".jsEmployeeFlowAmount").val().trim(),
			classification: $(
				".jsEmployeeFlowEmployeeClassification option:selected"
			).val(),
			per: $(".jsEmployeeFlowPer option:selected").val(),
		};
		//
		let errorArray = [];
		// validation
		if (!obj.title) {
			errorArray.push('"Job title" is missing.');
		}
		if (!obj.amount || obj.amount < 0) {
			errorArray.push('"Amount" is missing.');
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
		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" + employeeId + "/compensation"
			),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, CB);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
			});
	});

	/**
	 * Home address triggers
	 */
	$(document).on("click", ".jsEmployeeFlowSaveHomeAddressBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			street_1: $(".jsEmployeeFlowStreet1").val().trim(),
			street_2: $(".jsEmployeeFlowStreet2").val().trim(),
			city: $(".jsEmployeeFlowCity").val().trim(),
			state: $(".jsEmployeeFlowState").val().trim(),
			zip: $(".jsEmployeeFlowZip").val().trim(),
		};
		//
		let errorArray = [];
		// validation
		if (!obj.street_1) {
			errorArray.push('"street 1" is missing.');
		}
		if (!obj.city) {
			errorArray.push('"City" is missing.');
		}
		if (!obj.state) {
			errorArray.push('"State" is missing.');
		}
		if (!obj.zip) {
			errorArray.push('"Zip" is missing.');
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
		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" + employeeId + "/home_address"
			),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, CB);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
			});
	});

	/**
	 * Federal tax triggers
	 */
	$(document).on("click", ".jsEmployeeFlowSaveFederalTaxBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			filing_status: $(
				".jsEmployeeFlowFilingStatus option:selected"
			).val(),
			two_jobs: $(".jsEmployeeFlowMultipleJobs:checked").val(),
			dependents_amount: $(".jsEmployeeFlowDependents").val().trim(),
			extra_withholding: $(".jsEmployeeFlowExtraWithholding")
				.val()
				.trim(),
			other_income: $(".jsEmployeeFlowOtherIncome").val().trim(),
			deductions: $(".jsEmployeeFlowDeductions").val().trim(),
			w4_data_type: "rev_2020_w4",
		};
		//
		let errorArray = [];
		// validation
		if (!obj.filing_status) {
			errorArray.push('"Filing status" is missing.');
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
		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" + employeeId + "/federal_tax"
			),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, CB);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
			});
	});

	/**
	 * State tax triggers
	 */
	$(document).on("click", ".jsEmployeeFlowSaveStateTaxBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {};
		//
		let errorArray = [];
		//
		$(".jsEmployeeFlowStateTax").map(function () {
			//
			if (
				$(this).prop("tagName") === "INPUT" &&
				$(this).prop("type") === "number"
			) {
				//
				obj[$(this).prop("name")] = $(this).val().trim();
				//
				if ($(this).val().trim() < 0) {
					errorArray.push(
						'"' +
							$(this).prop("name").replace(/_/gi, " ") +
							'" can not be less than 0.'
					);
				}
			} else if ($(this).prop("tagName") === "SELECT") {
				obj[$(this).prop("name")] = $(
					'select[name="' +
						$(this).prop("name") +
						'"] option:selected'
				).val();
				//
				if (!$(this).val()) {
					errorArray.push(
						'"' +
							$(this).prop("name").replace(/_/gi, " ") +
							'" is missing.'
					);
				}
			}
		});
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}

		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/flow/employee/" + employeeId + "/state_tax"),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, CB);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
			});
	});

	/**
	 * Add bank account
	 */
	$(document).on("click", ".jsEmployeeFlowSaveBankAccountBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			accountTitle: $(".jsEmployeeFlowBankAccountTitle").val().trim(),
			routingNumber: $(".jsEmployeeFlowBankAccountRoutingNumber")
				.val()
				.replace(/[^0-9]/gi, ""),
			accountNumber: $(".jsEmployeeFlowBankAccountAccountNumber").val(),
			accountType: $(
				".jsEmployeeFlowBankAccountType option:selected"
			).val(),
		};
		//
		let errorArray = [];
		// validation
		if (!obj.accountTitle) {
			errorArray.push('"Account title" is missing.');
		}
		if (!obj.routingNumber) {
			errorArray.push('"Routing number" is missing.');
		}
		if (obj.routingNumber.length != 9) {
			errorArray.push('"Routing number" must be 9 digits long.');
		}
		if (!obj.accountNumber) {
			errorArray.push('"Routing number" is missing.');
		}
		if (!obj.accountType) {
			errorArray.push('"Account type" is missing.');
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
		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" + employeeId + "/bank_account"
			),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, function () {
					loadView("payment_method");
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
			});
	});

	/**
	 * Payment method
	 */
	$(document).on(
		"click",
		".jsEmployeeFlowPaymentMethodSaveBtn",
		function (e) {
			//
			e.preventDefault();
			//
			if (XHR !== null) {
				return false;
			}
			//
			let obj = {
				paymentType: $(
					".jsEmployeeFlowPaymentMethodType:checked"
				).val(),
			};
			//
			let errorArray = [];
			// validation
			if (!obj.paymentType) {
				errorArray.push('"Payment type" is missing.');
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
			ml(
				true,
				`${modalId}Loader`,
				"Please wait, while we are processing your request."
			);
			//
			XHR = $.ajax({
				url: baseUrl(
					"payrolls/flow/employee/" + employeeId + "/payment_method"
				),
				method: "POST",
				data: obj,
			})
				.success(function (resp) {
					//
					return alertify.alert("Success!", resp.msg, function () {
						loadView("payment_method");
					});
				})
				.fail(handleErrorResponse)
				.always(function () {
					//
					XHR = null;
					//
					ml(false, `${modalId}Loader`);
				});
		}
	);

	/**
	 * load add bank page
	 */
	$(document).on(
		"click",
		".jsEmployeeFlowPaymentMethodAddBankAccountBtn",
		function (e) {
			//
			e.preventDefault();
			loadView("bank_account_add");
		}
	);

	/**
	 * load add bank page
	 */
	$(document).on("click", ".jsEmployeeFlowPaymentMethodToBtn", function (e) {
		//
		e.preventDefault();
		loadView("payment_method");
	});

	/**
	 * load add bank page
	 */
	$(document).on("click", ".jsEmployeeFlowDeleteBankAccount", function (e) {
		//
		e.preventDefault();
		//
		const id = $(this)
			.closest(".jsEmployeeFlowDeleteBankAccountRow")
			.data("id");
		//
		return alertify.confirm(
			"Do you really want to delete the bank account?",
			function () {
				deleteBankAccount(id);
			}
		);
	});

	/**
	 * load add bank page
	 */
	$(document).on("click", ".jsEmployeeFlowUseBankAccount", function (e) {
		//
		e.preventDefault();
		//
		const id = $(this)
			.closest(".jsEmployeeFlowDeleteBankAccountRow")
			.data("id");
		//
		useBankAccount(id);
	});

	/**
	 * Toggle between check and direct deposit
	 */
	$(document).on("click", ".jsEmployeeFlowPaymentMethodType", function () {
		//
		if ($(this).val() === "Check") {
			$(".jsEmployeeFlowPaymentMethodAccountBox").addClass("hidden");
		} else {
			$(".jsEmployeeFlowPaymentMethodAccountBox").removeClass("hidden");
		}
	});

	$(document).on("click", ".jsAssignDocument", function (e) {
		//
		e.preventDefault();
		//
		const formObj = $(this).closest("tr").data();
		//
		return alertify
			.confirm(
				`Do you really want to assign this document?`,
				function () {
					//
					if (formObj.type === "direct_deposit") {
						assignGeneralDocument(formObj.type, formObj.did);
					} else if (formObj.type === "w4") {
						assignW4Document();
					}
				}
			)
			.set("labels", {
				ok: "YES",
				cancel: "No",
			})
			.set("title", "CONFIRM!");
	});

	$(document).on("click", ".jsRevokeDocument", function (e) {
		//
		e.preventDefault();
		//
		const formObj = $(this).closest("tr").data();
		//
		return alertify
			.confirm(
				`Do you really want to revoke this document?`,
				function () {
					//
					if (formObj.type === "direct_deposit") {
						revokeGeneralDocument(formObj.type, formObj.did);
					} else if (formObj.type === "w4") {
						revokeW4Document();
					}
				}
			)
			.set("labels", {
				ok: "YES",
				cancel: "No",
			})
			.set("title", "CONFIRM!");
	});

	//
	$(document).on("click", ".jsPayrollEmployeeFinishOnboard", function (e) {
		//
		e.preventDefault();
		//
		const employeeCode = $(this).closest("tr").data("id");
		//
		return alertify
			.confirm(
				"Are you certain about proceeding with the completion of the selected employee's payroll onboarding? <br />Once an employee is onboarded, you will be unable to delete them, but you will still have the option to modify their details.",
				function () {
					//
					finishEmployeeOnboard(employeeCode);
				}
			)
			.setHeader("Confirm");
	});

	//
	$(document).on("click", ".jsPayrollAddEmployees", function (e) {
		//
		e.preventDefault();
		//
		Modal(
			{
				Id: "jsPayrollAddEmployeesModal",
				Loader: "jsPayrollAddEmployeesModalLoader",
				Title: "Employees List",
				Body: '<div id="jsPayrollAddEmployeesModalBody"></div>',
			},
			loadEmployees
		);
	});

	//
	function loadEmployees() {
		//
		$.ajax({
			url: baseUrl("payrolls/" + companyId + "/employees/get"),
			method: "GET",
		})
			.success(function (resp) {
				$("#jsPayrollAddEmployeesModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				_ml(false, "jsPayrollAddEmployeesModalLoader");
			});
	}

	//
	function finishEmployeeOnboard(employeeCode) {
		//
		_ml(true, "pageLoader");
		//
		$.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" + employeeCode + "/finish_onboard"
			),
			method: "POST",
			data: {},
		})
			.success(function (resp) {
				//
				if (resp.view) {
					return Modal(
						{
							Id: "jsSummaryModel",
							Loader: "jsSummaryModelLoader",
							Body:
								'<div id="jsSummaryModelBody">' +
								resp.view +
								"</div>",
							Title: "Summary",
						},
						function () {
							_ml(false, "jsSummaryModelLoader");
						}
					);
				}
				//
				alertify.alert("Success!", resp.msg, function () {
					window.location.reload();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				_ml(false, "pageLoader");
			});
	}

	//
	function assignW4Document() {
		//
		_ml(true, `${modalId}Loader`);
		//
		$.ajax({
			url: baseUrl("forms/w4/employee/" + employeeId + "/assign"),
			method: "POST",
			data: {},
		})
			.success(function (resp) {
				//
				if (resp.Status === false) {
					alertify.alert("Error!", resp.message, CB);
					return;
				}
				//
				alertify.alert("Success!", resp.message, function () {
					loadView("documents");
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				_ml(false, `${modalId}Loader`);
			});
	}

	//
	function revokeW4Document() {
		//
		_ml(true, `${modalId}Loader`);
		//
		$.ajax({
			url: baseUrl("forms/w4/employee/" + employeeId + "/revoke"),
			method: "DELETE",
			data: {},
		})
			.success(function (resp) {
				//
				if (resp.Status === false) {
					alertify.alert("Error!", resp.message, CB);
					return;
				}
				//
				alertify.alert("Success!", resp.message, function () {
					loadView("documents");
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				_ml(false, `${modalId}Loader`);
			});
	}

	//
	function assignGeneralDocument(type, documentId) {
		//
		_ml(true, `${modalId}Loader`);
		//
		let obj = {};
		obj.action = "assign_document";
		obj.documentType = type;
		obj.companySid = companyId;
		obj.companyName = window.company.Name;
		obj.sid = documentId;
		obj.userSid = employeeId;
		obj.userType = "employee";
		//
		$.post(baseUrl("hr_documents_management/handler"), obj, (resp) => {
			//
			_ml(false, `${modalId}Loader`);
			//
			if (resp.Status === false) {
				alertify.alert("WARNING!", resp.Response, CB);
				return;
			}
			//
			alertify.alert("SUCCESS!", resp.Response, function () {
				loadView("documents");
			});
		});
	}

	//
	function revokeGeneralDocument(type, documentId) {
		//
		_ml(true, `${modalId}Loader`);
		//
		let obj = {};
		obj.action = "revoke_document";
		obj.documentType = type;
		obj.companySid = companyId;
		obj.companyName = window.company.Name;
		obj.sid = documentId;
		obj.userSid = employeeId;
		obj.userType = "employee";
		//
		$.post(baseUrl("hr_documents_management/handler"), obj, (resp) => {
			//
			_ml(false, `${modalId}Loader`);
			//
			if (resp.Status === false) {
				alertify.alert("WARNING!", resp.Response, CB);
				return;
			}
			//
			alertify.alert("SUCCESS!", resp.Response, function () {
				loadView("documents");
			});
		});
	}

	/**
	 * starts the employee onboard flow
	 * @returns
	 */
	function employeeOnboardFlow() {
		// check the employee
		if (employeeId === 0) {
			return alertify.alert("Error!", "Please select an employee.");
		}
		// generate modal
		Modal(
			{
				Title: "Employee Onboard Flow",
				Id: modalId,
				Loader: `${modalId}Loader`,
				Body: `<div id="${modalId}Body"></div>`,
			},
			function () {
				loadView("summary");
			}
		);
	}

	/**
	 * Load page
	 * @param {string} step
	 */
	function loadView(step) {
		//
		_ml(true, `${modalId}Loader`);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/flow/employee/" + employeeId + "/" + step),
			method: "GET",
			caches: false,
		})
			.success(function (response) {
				//
				$(`#${modalId}Body`).html(response.view);
				//
				loadEvents(step);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `${modalId}Loader`);
			});
	}

	/**
	 * Load page
	 * @param {string} step
	 */
	function deleteBankAccount(bankAccountId) {
		//
		_ml(true, `${modalId}Loader`);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" +
					employeeId +
					"/bank_account/" +
					bankAccountId
			),
			method: "DELETE",
			caches: false,
		})
			.success(function () {
				loadView("payment_method");
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `${modalId}Loader`);
			});
	}

	/**
	 * Load page
	 * @param {string} step
	 */
	function useBankAccount(bankAccountId) {
		//
		_ml(true, `${modalId}Loader`);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/flow/employee/" +
					employeeId +
					"/bank_account/" +
					bankAccountId
			),
			method: "PUT",
			caches: false,
		})
			.success(function () {
				loadView("payment_method");
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `${modalId}Loader`);
			});
	}

	/**
	 * load events
	 * @param {string} step
	 */
	function loadEvents(step) {
		if (step === "personal_details") {
			$(".jsEmployeeFlowDateOfBirth").datepicker({
				format: "mm/dd/yyyy",
				changeYear: true,
				changeMonth: true,
				yearRange: "-100:+0",
			});
			$(".jsEmployeeFlowStartDate").datepicker({
				format: "mm/dd/yyyy",
				changeYear: true,
				changeMonth: true,
				yearRange: "-100:+10",
			});
		}
	}

	$.ajaxSetup({
		cache: false,
	});

	_ml(false, "pageLoader");
});


