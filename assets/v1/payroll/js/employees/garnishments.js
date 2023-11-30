/**
 * employee garnishments
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @package Payroll
 * @version 1.0
 */
$(function manageEmployeesGarnishments() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * holds the employee id
	 */
	let employeeId = 0;

	/**
	 * load garnishments
	 */
	$(".jsPayrollEmployeeGarnishments").click(function (event) {
		//
		event.preventDefault();
		//
		employeeId = $(this).data("key");
		//
		Modal(
			{
				Id: "jsGarnishmentModal",
				Loader: "jsGarnishmentModalLoader",
				Title: 'Garnishments<span id="jsGarnishmentModalSpan"></span>',
				Body: '<div id="jsGarnishmentModalBody"></div>',
			},
			function () {
				//
				loadGarnishmentView();
			}
		);
	});

	/**
	 * add garnishment
	 */
	$(document).on("click", ".jsAddGarnishment", function (event) {
		//
		event.preventDefault();
		//
		loadAddGarnishmentView();
	});

	/**
	 * view garnishments
	 */
	$(document).on("click", ".jsViewGarnishment", function (event) {
		//
		event.preventDefault();
		//
		loadGarnishmentView();
	});

	/**
	 * amount symbol change
	 */
	$(document).on("change", ".jsGarnishmentDeductAsPercentage", function () {
		//
		$(".jsGarnishmentAmountSymbol").text(
			$(this).val() == "yes" ? "%" : "$"
		);
	});

	/**
	 * saves garnishments
	 */
	$(document).on("click", ".jsSaveGarnishment", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			active: $(".jsGarnishmentActive option:selected").val(),
			amount: $(".jsGarnishmentAmount").val().trim() || 0,
			description: $(".jsGarnishmentDescription").val().trim(),
			court_ordered: $(
				".jsGarnishmentCourtOrdered option:selected"
			).val(),
			times: $(".jsGarnishmentTimes").val().trim() || null,
			recurring: $(".jsGarnishmentRecurring option:selected").val(),
			annual_maximum:
				$(".jsGarnishmentAnnualMaximum").val().trim() || null,
			pay_period_maximum:
				$(".jsGarnishmentPayPeriodMaximum").val().trim() || null,
			deduct_as_percentage: $(
				".jsGarnishmentDeductAsPercentage option:selected"
			).val(),
			beneficiaryName: $(".jsBeneficiaryName").val(),
			beneficiaryAddress: $(".jsBeneficiaryAddress").val(),
			beneficiaryPhone: $(".jsBeneficiaryPhone").val(),
			beneficiaryPaymentType: $('input[name="payment_type"]:checked').val(),
		};
		//
		const errorsArray = [];
		// validation
		if (!obj.amount) {
			errorsArray.push('"Amount" is required.');
		}
		if (!obj.description) {
			errorsArray.push('"Description" is required.');
		}
		if (!obj.court_ordered) {
			errorsArray.push('"Court ordered" is required.');
		}
		//
		if (!obj.beneficiaryName) {
			errorsArray.push('"Contact name" is required.');
		}
		// if (!obj.beneficiaryAddress) {
		// 	errorsArray.push('"Contact Address" is required.');
		// }
		// if (!obj.beneficiaryPhone) {
		// 	errorsArray.push('"Contact Phone" is required.');
		// }
		//
		if (obj.beneficiaryPaymentType == 'bank') {
			obj.bankAccountTitle = $(".jsBankAccountTitle").val();
			obj.bankAccountType = $('input[name="beneficiary_banking_type"]:checked').val();
			obj.bankName = $(".jsBankName").val();
			obj.bankRoutingNumber = $(".jsBankRoutingNumber").val().replace(/[^\d]/g, "");
			obj.bankAccountNumber = $(".jsBankAccountNumber").val().replace(/[^\d]/g, "");
			//
			if (!obj.bankAccountTitle) {
				errorsArray.push('"Bank account title" is required.');
			}
			if (!obj.bankName) {
				errorsArray.push('"Bank name" is required.');
			}
			if (!obj.bankRoutingNumber) {
				errorsArray.push('"Routing number" is required.');
			}
			if (obj.bankRoutingNumber.length !== 9) {
				errorsArray.push('Routing number must be of 9 digits.');
			}
			if (!obj.bankAccountNumber) {
				errorsArray.push('"Account number" is required.');
			}
			if (obj.bankAccountNumber.length !== 9) {
				errorsArray.push('Account number must be of 9 digits.');
			}
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}

		//
		saveGarnishment(obj);
	});

	/**
	 * edit garnishment event
	 */
	$(document).on("click", ".jsEditGarnishment", function (event) {
		//
		event.preventDefault();
		//
		loadEditGarnishmentView($(this).closest("tr").data("id"));
	});

	/**
	 * updates garnishments
	 */
	$(document).on("click", ".jsUpdateGarnishment", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			active: $(".jsGarnishmentActive option:selected").val(),
			amount: $(".jsGarnishmentAmount").val().trim() || 0,
			description: $(".jsGarnishmentDescription").val().trim(),
			court_ordered: $(
				".jsGarnishmentCourtOrdered option:selected"
			).val(),
			times: $(".jsGarnishmentTimes").val().trim() || null,
			recurring: $(".jsGarnishmentRecurring option:selected").val(),
			annual_maximum:
				$(".jsGarnishmentAnnualMaximum").val().trim() || null,
			pay_period_maximum:
				$(".jsGarnishmentPayPeriodMaximum").val().trim() || null,
			deduct_as_percentage: $(
				".jsGarnishmentDeductAsPercentage option:selected"
			).val(),
			beneficiaryName: $(".jsBeneficiaryName").val(),
			beneficiaryAddress: $(".jsBeneficiaryAddress").val(),
			beneficiaryPhone: $(".jsBeneficiaryPhone").val(),
			beneficiaryPaymentType: $('input[name="payment_type"]:checked').val(),
		};
		//
		const garnishmentId = $(".jsGarnishmentKey").val();
		//
		const errorsArray = [];
		// validation
		if (!obj.amount) {
			errorsArray.push('"Amount" is required.');
		}
		if (!obj.description) {
			errorsArray.push('"Description" is required.');
		}
		if (!obj.court_ordered) {
			errorsArray.push('"Court ordered" is required.');
		}
		if (!garnishmentId) {
			errorsArray.push('"Key" is required.');
		}
		//
		if (!obj.beneficiaryName) {
			errorsArray.push('"Contact name" is required.');
		}
		if (obj.beneficiaryPaymentType == 'bank') {
			obj.bankAccountTitle = $(".jsBankAccountTitle").val();
			obj.bankAccountType = $('input[name="beneficiary_banking_type"]:checked').val();
			obj.bankName = $(".jsBankName").val();
			obj.bankRoutingNumber = $(".jsBankRoutingNumber").val().replace(/[^\d]/g, "");
			obj.bankAccountNumber = $(".jsBankAccountNumber").val().replace(/[^\d]/g, "");
			//
			if (!obj.bankAccountTitle) {
				errorsArray.push('"Bank account title" is required.');
			}
			if (!obj.bankName) {
				errorsArray.push('"Bank name" is required.');
			}
			if (!obj.bankRoutingNumber) {
				errorsArray.push('"Routing number" is required.');
			}
			if (obj.bankRoutingNumber.length !== 9) {
				errorsArray.push('Routing number must be of 9 digits.');
			}
			if (!obj.bankAccountNumber) {
				errorsArray.push('"Account number" is required.');
			}
			if (obj.bankAccountNumber.length !== 9) {
				errorsArray.push('Account number must be of 9 digits.');
			}
		}
		//
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		updateGarnishment(obj, garnishmentId);
	});

	$(document).on("change", ".jsPaymentType", function (event) {
		//
		event.preventDefault();
		//
		var type = $('input[name="payment_type"]:checked').val();
		//
		if (type == 'bank') {
			$(".jsBankInfoSection").removeClass("dn")
		} else if (type == 'cheque') {
			$(".jsBankInfoSection").addClass("dn")
		}
	});

	/**
	 * load garnishments
	 */
	function loadGarnishmentView() {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/employees/" + employeeId + "/garnishments"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsGarnishmentModalBody").html(resp.view);
				$("#jsGarnishmentModalSpan").text(` of ${resp.employee.name}`);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsGarnishmentModalLoader");
			});
	}

	/**
	 * add garnishments
	 */
	function loadAddGarnishmentView() {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		$("#jsGarnishmentModalBody").html("");
		//
		ml(
			false,
			"jsGarnishmentModalLoader",
			"Please wait while we are generating a preview."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/employees/" + employeeId + "/garnishments/add"
			),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsGarnishmentModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsGarnishmentModalLoader");
			});
	}

	/**
	 * edit garnishment
	 */
	function loadEditGarnishmentView(garnishmentId) {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		$("#jsGarnishmentModalBody").html("");
		//
		ml(
			false,
			"jsGarnishmentModalLoader",
			"Please wait while we are generating a preview."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/employees/" +
					employeeId +
					"/garnishments/" +
					garnishmentId
			),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsGarnishmentModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsGarnishmentModalLoader");
			});
	}

	/**
	 * save garnishments
	 */
	function saveGarnishment(obj) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsGarnishmentModalLoader",
			"Please wait while we are saving garnishment."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/employees/" + employeeId + "/garnishment"),
			method: "POST",
			cache: false,
			data: obj,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					loadGarnishmentView();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsGarnishmentModalLoader");
			});
	}

	/**
	 * update garnishment
	 * @param {object} obj
	 * @param {number} garnishmentId
	 * @returns
	 */
	function updateGarnishment(obj, garnishmentId) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsGarnishmentModalLoader",
			"Please wait while we are updating garnishment."
		);
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/employees/" +
					employeeId +
					"/garnishments/" +
					garnishmentId
			),
			method: "POST",
			cache: false,
			data: obj,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					loadGarnishmentView();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsGarnishmentModalLoader");
			});
	}
	//
	_ml(false, "pageLoader");
});
