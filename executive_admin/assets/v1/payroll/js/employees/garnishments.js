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
		};
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
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}

		//
		updateGarnishment(obj, garnishmentId);
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
