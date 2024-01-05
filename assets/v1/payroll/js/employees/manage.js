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
	let employeeId = 0;

	//
	let callQueue = {};
	/**
	 * holds the modal id
	 */

	let employeeName = "";

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
		employeeName = $(this).closest("tr").data("employee");
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
	 * Compensation triggers
	 */
	$(document).on("click", ".package-info-box", function (e) {
		$(".select-package:not(:checked)")
			.parent()
			.removeClass("selected-package");
		$(".select-package:checked").parent().addClass("selected-package");
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
		let checkedWages = $(".select-package:checkbox:checked")
			.map(function () {
				return this.value;
			})
			.get();
		//
		let obj = {
			title: $(".jsEmployeeFlowJobTitle").val().trim(),
			amount: $(".jsEmployeeFlowAmount").val().trim(),
			classification: $(
				".jsEmployeeFlowEmployeeClassification option:selected"
			).val(),
			per: $(".jsEmployeeFlowPer option:selected").val(),
			minimumWage: $(".jsEmployeeFlowMinimumWage option:selected").val(),
			wagesId: checkedWages,
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
	$(document).on("click", ".jsPayrollEmployeeDelete", function (e) {
		//
		e.preventDefault();
		//
		const employeeCode = $(this).closest("tr").data("id");
		//
		return _confirm(
			"Do you really want to remove the selected employee from Payroll?<br /> This action is not-revertible. However, you can re-add the selected employee for payroll.",
			function () {
				deleteEmployeeFromPayroll(employeeCode);
			}
		);
	});

	//
	function deleteEmployeeFromPayroll(employeeCode) {
		//
		_ml(true, "pageLoader");
		//
		$.ajax({
			url: baseUrl("payrolls/flow/employee/" + employeeCode + "/delete"),
			method: "DELETE",
		})
			.success(function (resp) {
				//
				_success(resp.msg, function () {
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
				Title: "Onboard Flow for " + employeeName,
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
