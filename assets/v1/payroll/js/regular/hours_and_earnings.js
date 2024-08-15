$(function regularPayrollsHoursAndEarnings() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	/**
	 * get the payroll id from segment
	 */
	const payrollId = getSegment(2);
	/**
	 * holds the payroll employees
	 */
	let payrollEmployees = {};
	/**
	 * holds the payroll
	 */
	let payroll = {};

	$(document).on("click", ".jsBoxSwitch", function (event) {
		//
		event.preventDefault();
		//
		$(this).addClass("hidden");
		$(this).parent().find(".jsBoxField").removeClass("hidden");
	});
	/**
	 * Additional earning
	 */
	$(document).on("click", ".jsAdditionalEarningBtn", function (event) {
		//
		event.preventDefault();
		//
		$(this).find("button").addClass("hidden");
		$(this).find(".jsBoxField").removeClass("hidden");
		//
		showAdditionalEarningBox($(this).closest("tr").data("id"));
	});

	// Regular hours
	$(document).on("click", ".jsCancelRH", function () {
		//
		const employeeId = $(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.data("id");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsRegularHoursBox")
			.find(".jsBoxSwitch")
			.removeClass("hidden");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsRegularHoursBox")
			.find(".jsBoxField")
			.addClass("hidden");
	});

	// Regular hours
	$(document).on("click", ".jsCancelOT", function () {
		//
		const employeeId = $(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.data("id");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsOvertimeBox")
			.find(".jsBoxSwitch")
			.removeClass("hidden");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsOvertimeBox")
			.find(".jsBoxField")
			.addClass("hidden");
	});

	// Regular hours
	$(document).on("click", ".jsCancelDOT", function () {
		//
		const employeeId = $(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.data("id");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsDoubleOvertimeBox")
			.find(".jsBoxSwitch")
			.removeClass("hidden");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsDoubleOvertimeBox")
			.find(".jsBoxField")
			.addClass("hidden");
	});

	// Regular hours
	$(document).on("click", ".jsCancelBonus", function () {
		//
		const employeeId = $(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.data("id");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsBonusBox")
			.find(".jsBoxSwitch")
			.removeClass("hidden");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsBonusBox")
			.find(".jsBoxField")
			.addClass("hidden");
	});

	// Regular hours
	$(document).on("keyup", ".jsRegularHoursValue", function () {
		//
		const val = $(this).val().trim() || 0;
		//
		const employeeId = $(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.data("id");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsRegularHoursText")
			.text(`${val} hrs`);
		//
		payroll["employees"][employeeId]["hourly_compensations"][
			"regular_hours"
		]["hours"] = parseFloat(val);
		//
		setSingleView(employeeId);
	});

	// Overtime
	$(document).on("keyup", ".jsOvertimeValue", function () {
		//
		const val = $(this).val().trim() || 0;
		//
		const employeeId = $(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.data("id");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsOvertimeText")
			.text(`${val} hrs`);
		//
		payroll["employees"][employeeId]["hourly_compensations"]["overtime"][
			"hours"
		] = parseFloat(val);
		//
		setSingleView(employeeId);
	});

	// double overtime
	$(document).on("keyup", ".jsDoubleOvertimeValue", function () {
		//
		const val = $(this).val().trim() || 0;
		//
		const employeeId = $(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.data("id");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsDoubleOvertimeText")
			.text(`${val} hrs`);
		//
		payroll["employees"][employeeId]["hourly_compensations"][
			"double_overtime"
		]["hours"] = parseFloat(val);
		//
		setSingleView(employeeId);
	});

	// bonus
	$(document).on("keyup", ".jsBonusValue", function () {
		//
		const val = $(this).val().trim() || 0;
		//
		const employeeId = $(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.data("id");
		//
		$(this)
			.closest("tr.jsRegularPayrollEmployeeRow")
			.find(".jsBonusText")
			.text(`$${val}`);
		//
		payroll["employees"][employeeId]["fixed_compensations"]["bonus"][
			"amount"
		] = parseFloat(val);
		//
		setSingleView(employeeId);
	});

	// additional earnings
	$(document).on("click", ".jsSaveAdditionalEarningsBtn", function (event) {
		//
		event.preventDefault();
		//
		const employeeId = $(this).data("id");
		//
		let total = 0;
		//
		$(".jsAdditionalEarningFields").map(function () {
			//
			const val = parseFloat($(this).val().trim() || 0);
			//
			payroll["employees"][employeeId]["fixed_compensations"][
				$(this).data("key")
			]["amount"] = parseFloat(val);
			//
			total += val;
		});
		//
		$('tr.jsRegularPayrollEmployeeRow[data-id="' + employeeId + '"]')
			.find(".jsAdditionalEarningValue")
			.val(total);
		//
		setSingleView(employeeId);
		//
		$(".jsModalCancel").trigger("click");
	});

	// reimbursement
	$(document).on("click", ".jsReimbursementBtn", function (event) {
		//
		event.preventDefault();
		//
		showReimbursementBox($(this).closest("tr").data("id"));
	});

	// add reimbursement
	$(document).on("click", ".jsAddReimbursement", function (event) {
		//
		event.preventDefault();
		//
		$(".csReimbursementBox").append(
			generateReimbursementRow({
				description: "Additional amount",
				amount: 0.0,
			})
		);
	});

	// add reimbursement
	$(document).on("keyup", ".jsReimbursementAmount", function () {
		//
		let val = $(this).val().trim() || 0;
		//
		if (val <= 0) {
			//
			val = 0;
			//
			$(this).val(val);
		}
		//
		let total = 0;
		$(".jsReimbursementAmount").map(function () {
			total += parseFloat($(this).val().trim() || 0);
		});
		//
		$(".jsReimbursementTotal").text("$" + _cur(total, true));
	});

	// remove reimbursement
	$(document).on("click", ".jsReimbursementDeleteBtn", function (event) {
		//
		event.preventDefault();
		//
		$(this).closest(".jsReimbursementRow").remove();
		//
		let total = 0;
		$(".jsReimbursementAmount").map(function () {
			total += parseFloat($(this).val().trim() || 0);
		});
		//
		$(".jsReimbursementTotal").text("$" + total);
	});

	// save reimbursement
	$(document).on("click", ".jsSaveReimbursementBtn", function (event) {
		//
		event.preventDefault();
		///
		const employeeId = $(this).data("id");
		//
		_ml(true, "jsReimbursementLoader");
		//
		const obj = {
			total: 0,
			data: {},
		};
		//
		$(".jsReimbursementRow").map(function () {
			//
			obj.total += parseFloat(
				$(this).find(".jsReimbursementAmount").val().trim() || 0
			);
			//
			obj.data[$(this).data("id")] = {
				id: $(this).data("id"),
				description:
					$(this).find(".jsReimbursementDescription").val().trim() ||
					"Additional amount",
				amount: parseFloat(
					$(this).find(".jsReimbursementAmount").val().trim() || 0
				),
			};
		});
		//
		payroll["employees"][employeeId]["fixed_compensations"][
			"reimbursement"
		]["amount"] = parseFloat(obj.total);
		//
		payroll["employees"][employeeId]["data"]["reimbursement_json"] = obj;
		//
		$(".jsModalCancel").trigger("click");
		//
		if (obj.total != 0) {
			$('.jsRegularPayrollEmployeeRow[data-id="' + employeeId + '"]')
				.find(".jsReimburmentTotal")
				.text("R $" + _cur(obj.total, true));
			$('.jsRegularPayrollEmployeeRow[data-id="' + employeeId + '"]')
				.find(".jsReimbursementTotalBox")
				.removeClass("hidden");
			$('.jsRegularPayrollEmployeeRow[data-id="' + employeeId + '"]')
				.find(".jsReimbursementTotalBoxBtn")
				.addClass("hidden");
		} else {
			$('.jsRegularPayrollEmployeeRow[data-id="' + employeeId + '"]')
				.find(".jsReimbursementTotalBox")
				.addClass("hidden");
			$('.jsRegularPayrollEmployeeRow[data-id="' + employeeId + '"]')
				.find(".jsReimbursementTotalBoxBtn")
				.removeClass("hidden");
		}
		//
		setSingleView(employeeId);
	});

	// payment method
	$(document).on("change", ".jsPaymentMethod", function () {
		///
		const employeeId = $(this)
			.closest(".jsRegularPayrollEmployeeRow")
			.data("id");

		payroll["employees"][employeeId]["payment_method"] = $(this).val();
	});

	// skip payroll
	$(document).on("click", ".jsSkipEmployeeFromPayroll", function (event) {
		//
		event.preventDefault();
		//
		const ref = $(this).closest(".jsRegularPayrollEmployeeRow");
		///
		const employeeId = ref.data("id");
		//
		$(this).addClass("hidden");
		ref.find(".jsAddEmployeeFromPayroll").removeClass("hidden");
		ref.find(".jsSkipPayroll").addClass("hidden");

		payroll["employees"][employeeId]["excluded"] = true;
	});

	// add payroll
	$(document).on("click", ".jsAddEmployeeFromPayroll", function (event) {
		//
		event.preventDefault();
		//
		const ref = $(this).closest(".jsRegularPayrollEmployeeRow");
		///
		const employeeId = ref.data("id");
		//
		$(this).addClass("hidden");
		ref.find(".jsSkipEmployeeFromPayroll").removeClass("hidden");
		ref.find(".jsSkipPayroll").removeClass("hidden");

		payroll["employees"][employeeId]["excluded"] = false;
	});

	// save payroll
	$(document).on("click", ".jsRegularPayrollStep1Save", function (event) {
		//
		event.preventDefault();
		//
		ml(true, "jsPageLoader", "Please wait, while we are saving data.");
		//
		if (XHR !== null) {
			return;
		}
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/regular/" + payrollId + "/save/1"),
			method: "POST",
			data: {
				payrollId,
				employees: payroll.employees,
			},
			cache: false,
		})
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.message, function () {
					ml(
						true,
						"jsPageLoader",
						"Please wait while we are processing your request."
					);
					window.location.href = baseUrl(
						"payrolls/regular/" + payrollId
					);
				});
			});
	});

	/**
	 * get the regular payroll
	 */
	function getRegularPayroll() {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(true, "jsPageLoader", "Please wait, while we are generating view.");
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/regular/" + payrollId + "/view/1"),
			method: "GET",
			cache: false,
		})
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				//
				payrollEmployees = resp.employees;
				payroll = resp.payroll;
				//
				$("tbody").html(resp.view);
				//
				if (payroll) {
					//
					let rows = "";
					rows +=
						'		<a href="' +
						baseUrl("payrolls/regular") +
						'" class="btn btn-black">';
					rows +=
						'			<i class="fa fa-times-circle " aria-hidden="true"></i>';
					rows += "			&nbsp;Cancel";
					rows += "		</a>";
					rows +=
						'		<button class="btn btn-orange jsRegularPayrollStep1Save">';
					rows +=
						'			<i class="fa fa-save csF16" aria-hidden="true"></i>';
					rows += "			&nbsp;Save & Calculate";
					rows += "		</button>";
					//
					$(".jsRegularPayrollPanel")
						.html(rows)
						.removeClass("hidden");
					//
					setView();
				}
			});
	}

	/**
	 * set the calculations
	 */
	function setView() {
		// loop through the rows
		$(".jsRegularPayrollEmployeeRow").map(function () {
			// get employee id
			const employeeId = $(this).data("id");
			// get the employee
			const payrollEmployee = payroll["employees"][employeeId];
			// check if employee exists
			if (payrollEmployee) {
				// set regular hours
				setEmployeeRowSegment(
					$(this),
					"jsRegularHoursText",
					"jsRegularHoursValue",
					payrollEmployee.hourly_compensations.regular_hours.hours
				);
				// check and set overtime
				if (payrollEmployee.hourly_compensations.overtime) {
					setEmployeeRowSegment(
						$(this),
						"jsOvertimeText",
						"jsOvertimeValue",
						payrollEmployee.hourly_compensations.overtime.hours
					);
				}
				// check and set double overtime
				if (payrollEmployee.hourly_compensations.double_overtime) {
					setEmployeeRowSegment(
						$(this),
						"jsDoubleOvertimeText",
						"jsDoubleOvertimeValue",
						payrollEmployee.hourly_compensations.double_overtime
							.hours
					);
				}
				// check and set bonus
				if (payrollEmployee.fixed_compensations.bonus) {
					// set regular hours
					setEmployeeRowSegment(
						$(this),
						"jsBonusText",
						"jsBonusValue",
						payrollEmployee.fixed_compensations.bonus.amount,
						"Bonus $"
					);
				}
				// set payment method
				$(this)
					.find(
						'.jsPaymentMethod option[value="' +
							payrollEmployee.payment_method +
							'"]'
					)
					.prop("selected", true);
				// fix compensations
				let fixedCompensationTotal = 0;
				// for fixed compensations
				$.each(payrollEmployee["fixed_compensations"], function (i, v) {
					if (i != "bonus" && i != "reimbursement") {
						fixedCompensationTotal += parseFloat(v.amount);
					}
				});
				// set additional earnings
				setEmployeeRowSegment(
					$(this),
					"jsAdditionalEarningText",
					"jsAdditionalEarningValue",
					fixedCompensationTotal,
					"AE $"
				);
				//
				if (
					payrollEmployee.data &&
					payrollEmployee.data.reimbursement_json &&
					payrollEmployee.data.reimbursement_json.total != 0
				) {
					// set additional earnings
					setEmployeeRowSegment(
						$(this),
						"jsReimburmentTotal",
						"",
						payrollEmployee.data.reimbursement_json.total,
						"R $"
					);
					$(this)
						.find(".jsReimbursementTotalBox")
						.removeClass("hidden");
					$(this)
						.find(".jsReimbursementTotalBoxBtn")
						.addClass("hidden");
				} else {
					$(this).find(".jsReimbursementTotalBox").addClass("hidden");
					$(this)
						.find(".jsReimbursementTotalBoxBtn")
						.removeClass("hidden");
				}
				// skip payroll
				if (
					payrollEmployee.excluded &&
					payrollEmployee.excluded !== "false"
				) {
					$(this)
						.find(".jsSkipEmployeeFromPayroll")
						.addClass("hidden");
					$(this)
						.find(".jsAddEmployeeFromPayroll")
						.removeClass("hidden");
					$(this).find(".jsSkipPayroll").addClass("hidden");
				} else {
					$(this)
						.find(".jsSkipEmployeeFromPayroll")
						.removeClass("hidden");
					$(this)
						.find(".jsAddEmployeeFromPayroll")
						.addClass("hidden");
					$(this).find(".jsSkipPayroll").removeClass("hidden");
				}
				//
				setSingleView(employeeId);
			}
		});
	}

	/**
	 * populate data on segment
	 * @param {*} pointer
	 * @param {*} pointer2
	 * @param {*} value
	 * @param {*} prefix
	 */
	function setEmployeeRowSegment(_this, pointer, pointer2, value, prefix) {
		if (pointer && _this.find(`.${pointer}`).length) {
			// for regular hours
			if (prefix) {
				_this.find(`.${pointer}`).text(`${prefix}${_cur(value)}`);
			} else {
				_this.find(`.${pointer}`).text(`${_cur(value)} hrs`);
			}
		}
		if (pointer2 && _this.find(`.${pointer2}`).length) {
			//
			_this.find(`.${pointer2}`).val(value);
		}
	}

	/**
	 * set calculation for specific person
	 */
	function setSingleView(employeeId) {
		//
		const payrollOBJ = payroll["employees"][employeeId];
		const employeeOBJ = payrollEmployees[employeeId];
		// set employee rate per hour
		let ratePerHour = getRatePerHour(
			employeeOBJ["compensation"]["rate"],
			employeeOBJ["compensation"]["payment_unit"]
		);
		//
		const ref = $('tr[data-id="' + employeeId + '"]');
		//
		let amountToShow = 0;
		// calculate regular hours
		amountToShow += parseFloat(
			payrollOBJ["hourly_compensations"]["regular_hours"]["hours"] *
				ratePerHour *
				payrollOBJ["hourly_compensations"]["regular_hours"][
					"compensation_multiplier"
				]
		);
		// calculate overtime
		if (ref.find(".jsOvertimeText").length) {
			// calculate overtime
			amountToShow += parseFloat(
				payrollOBJ["hourly_compensations"]["overtime"]["hours"] *
					ratePerHour *
					payrollOBJ["hourly_compensations"]["overtime"][
						"compensation_multiplier"
					]
			);
		}
		// calculate double overtime
		if (ref.find(".jsDoubleOvertimeText").length) {
			// calculate double overtime
			amountToShow += parseFloat(
				payrollOBJ["hourly_compensations"]["double_overtime"]["hours"] *
					ratePerHour *
					payrollOBJ["hourly_compensations"]["double_overtime"][
						"compensation_multiplier"
					]
			);
		}
		// fix compensations
		let fixedCompensationTotal = 0;
		// for fixed compensations
		$.each(
			payroll["employees"][employeeId]["fixed_compensations"],
			function (i, v) {
				fixedCompensationTotal += parseFloat(v.amount);
			}
		);
		//
		amountToShow += parseFloat(fixedCompensationTotal);
		// limit to 2 digits
		amountToShow = _cur(amountToShow, true);
		// append time
		ref.find(".jsShowTotal").text(`$${amountToShow}`);
	}

	/**
	 * handled additional earnings
	 * @param {int} employeeId
	 */
	function showAdditionalEarningBox(employeeId) {
		Modal(
			{
				Id: "jsAdditionalEarning",
				Title: "Other Earnings",
				Body: `<div class="container"><div id="jsAdditionalEarningBody">${generateAdditionalEarningBody(
					employeeId
				)}</div></div>`,
				Loader: "jsAdditionalEarningLoader",
			},
			function () {
				_ml(false, "jsAdditionalEarningLoader");
			}
		);
	}

	/**
	 * generates the fixed compensations rows
	 * @param {int} employeeId
	 * @returns
	 */
	function generateAdditionalEarningBody(employeeId) {
		// get employee OBJ
		const employeeOBJ = payrollEmployees[employeeId];
		const employeePayrollOBJ = payroll["employees"][employeeId];
		//
		let rows = "";
		// name text
		rows += '<br /><p class="csF16 text-danger">';
		rows += "<strong>";
		rows += "<em>";
		rows +=
			"" +
			employeeOBJ.name +
			" will be paid the amounts below in addition to their regular wages.";
		rows += "</em>";
		rows += "</strong>";
		rows += "</em>";

		// lets loop through fixed compensations
		$.each(employeePayrollOBJ["fixed_compensations"], function (i, v) {
			//
			if (
				i != "reimbursement" &&
				i != "bonus" &&
				i.match("time_off") === null
			) {
				//
				rows += '<div class="form-group">';
				rows += '<label class="csF16">' + v.name + "</label>";
				rows += '<div class="input-group">';
				rows += '<div class="input-group-addon">$</div>';
				rows +=
					'<input type="number" class="form-control jsAdditionalEarningFields" data-key="' +
					i +
					'" placeholder="0.0" value="' +
					v.amount +
					'" />';
				rows += "</div>";
				rows += "</div>";
			}
		});
		// save and close button
		rows += '<div class="form-group text-right">';
		rows +=
			'<button class="btn btn-black jsModalCancel">Cancel</button>&nbsp;';
		rows +=
			'<button class="btn btn-orange jsSaveAdditionalEarningsBtn" data-id="' +
			employeeId +
			'">Save</button>';
		rows += "</div>";
		return rows;
	}

	/**
	 * get employee rate per hour
	 *
	 * @param {int} rate
	 * @param {string} payment_unit
	 * @returns
	 */
	function getRatePerHour(rate, payment_unit) {
		//
		let newRate = rate;
		//
		payment_unit = payment_unit.toLowerCase();
		//
		if (payment_unit == "year") {
			newRate = rate / 52 / 40;
		} else if (payment_unit == "month") {
			newRate = (rate * 12) / 52 / 40;
		} else if (payment_unit == "week") {
			newRate = rate / 40;
		}
		//
		return newRate;
	}

	/**
	 * handled reimbursement
	 * @param {int} employeeId
	 */
	function showReimbursementBox(employeeId) {
		//
		Modal(
			{
				Id: "jsReimbursement",
				Title: "Employee Reimbursement",
				Body: `<div class="container"><div id="jsReimbursementBody">${generateReimbursementBody(
					employeeId
				)}</div></div>`,
				Loader: "jsReimbursementLoader",
			},
			function () {
				//
				$(".jsReimbursementTotal").text(
					`$${_cur(
						(payroll["employees"][employeeId]["data"][
							"reimbursement_json"
						] &&
							payroll["employees"][employeeId]["data"][
								"reimbursement_json"
							]["total"]) ||
							0,
						true
					)}`
				);
				//
				_ml(false, "jsReimbursementLoader");
			}
		);
	}

	/**
	 * generates reimbursement body
	 * @param {int} employeeId
	 * @returns
	 */
	function generateReimbursementBody(employeeId) {
		//
		let rows = "";
		// get employee reimbursements
		const reimbursements =
			payroll["employees"][employeeId]["data"]["reimbursement_json"] ||
			[];

		// helping material
		rows += '<br /><p class="csF16 text-danger">';
		rows += "	<strong>";
		rows += "		<em>";
		rows +=
			"Below are all reimbursements to be paid to this employee this pay period. You can edit recurring reimbursements by going to the People tab and selecting this employee.";
		rows += "		</em>";
		rows += "	</strong>";
		rows += "</p>";

		// accordion
		rows += '<div class="panel panel-default">';
		rows += '	<div class="panel-heading">';
		rows += '		<div class="row">';
		rows += '			<div class="col-sm-8 col-xs-12">';
		rows += '				<p class="csF16"><strong>One-time reimbursements</strong></p>';
		rows += "			</div>";
		rows += '			<div class="col-sm-4 col-xs-12 text-right">';
		rows +=
			'				<p class="csF16"><strong class="jsReimbursementTotal">$0.00</strong></p>';
		rows += "			</div>";
		rows += "		</div>";
		rows += "	</div>";
		rows += '	<div class="panel-body">';
		rows += '		<div class="csReimbursementBox">';
		if (reimbursements.data) {
			$.each(reimbursements.data, function (i, v) {
				rows += generateReimbursementRow(v);
			});
		}
		rows += "		</div>";
		rows += '		<div class="row">';
		rows += '			<div class="col-sm-12 col-xs-12 text-center">';
		rows +=
			'				<button class="btn btn-orange jsAddReimbursement" data-id="' +
			employeeId +
			'">Add reimbursement</button>';
		rows += "			</div>";
		rows += "		</div>";
		rows += "	</div>";
		rows += "</div>";

		// total
		rows += '<div class="panel panel-default">';
		rows += '	<div class="panel-heading">';
		rows += '		<div class="row">';
		rows += '			<div class="col-sm-8 col-xs-12">';
		rows += '				<p class="csF16"><strong>Total</strong></p>';
		rows += "			</div>";
		rows += '			<div class="col-sm-4 col-xs-12 text-right">';
		rows +=
			'				<p class="csF16"><strong class="jsReimbursementTotal">$0.00</strong></p>';
		rows += "			</div>";
		rows += "		</div>";
		rows += "	</div>";
		rows += "</div>";
		// button
		rows += '		<div class="row">';
		rows += '			<div class="col-sm-12 col-xs-12 text-right">';
		rows +=
			'<button class="btn btn-black jsModalCancel">Cancel</button>&nbsp;';
		rows +=
			'<button class="btn btn-orange jsSaveReimbursementBtn" data-id="' +
			employeeId +
			'">Save</button>';
		rows += "			</div>";
		rows += "		</div>";
		rows += "	</div>";

		//
		return rows;
	}

	/**
	 * generates reimbursement row
	 * @param {object} data
	 * @returns
	 */
	function generateReimbursementRow(data) {
		//
		let rows = "";
		//
		let code = data.id || getRandomCode();
		//
		rows += '<div class="jsReimbursementRow" data-id="' + code + '">';
		rows += '<div class="row">';
		rows += '	<div class="col-sm-6 col-xs-12">';
		rows += '		<label class="csF16">Description</label>';
		rows +=
			'<input type="text" class="form-control jsReimbursementDescription csF16" placeholder="Additional amount" value="' +
			data.description +
			'" />';
		rows += "	</div>";
		rows += '	<div class="col-sm-5 col-xs-12">';
		rows += '		<label class="csF16">Amount</label>';
		rows += '		<div class="input-group">';
		rows += '			<div class="input-group-addon">$</div>';
		rows +=
			'			<input type="number" class="form-control csF1 jsReimbursementAmount" placeholder="0.0" value="' +
			parseFloat(data.amount) +
			'" />';
		rows += "		</div>";
		rows += "	</div>";
		rows += '	<div class="col-sm-1 col-xs-12 text-right">';
		rows += '		<label class="csF16">Action</label>';
		rows +=
			'		<button class="btn btn-danger csF16 jsReimbursementDeleteBtn">';
		rows += '			<i class="fa fa-times-circle csF16"></i>';
		rows += "		</button>";
		rows += "	</div>";
		rows += "</div>";
		rows += "	<hr />";
		rows += "</div>";

		return rows;
	}

	//
	getRegularPayroll();
});
