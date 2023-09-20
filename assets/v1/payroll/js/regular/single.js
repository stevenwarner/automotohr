$(function regularPayrolls() {
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
		$(this).addClass("dn");
		$(this).parent().find(".jsBoxField").removeClass("dn");
	});
	/**
	 * Additional earning
	 */
	$(document).on("click", ".jsAdditionalEarningBtn", function (event) {
		//
		event.preventDefault();
		//
		$(this).find("button").addClass("dn");
		$(this).find(".jsBoxField").removeClass("dn");
		//
		showAdditionalEarningBox($(this).closest("tr").data("id"));
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
		$(".jsRegularHoursText").text(`${val} hrs`);
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
		$(".jsOvertimeText").text(`${val} hrs`);
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
		$(".jsDoubleOvertimeText").text(`${val} hrs`);
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
		$(".jsBonusText").text(`$${val}`);
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
		$(".jsAdditionalEarningValue").val(total);
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
		$(".jsReimbursementTotal").text("$" + total);
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
	$(document).on("click", ".jsSaveAdditionalEarningsBtn", function (event) {
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
		console.log('Clicked', $(".jsReimbursementRow").length);
		//
		// $(".jsReimbursementRow").map(function () {
		// 	//
		// 	obj.total += parseFloat(
		// 		$(this).find("jsReimbursementAmount").val().trim() || 0
		// 	);
		// 	//
		// 	const id = $(this).data("id");
		// 	//
		// 	obj.data[$(this).data("id")] = {
		// 		id: $(this).data("id"),
		// 		description:
		// 			$(this).find(".jsReimbursementDescription").val().trim() ||
		// 			"Additional amount",
		// 		amount: parseFloat(
		// 			$(this).find("jsReimbursementAmount").val().trim() || 0
		// 		),
		// 	};
		// });
		// //
		// payroll["employees"][employeeId]["fixed_compensations"][
		// 	"reimbursement"
		// ]["amount"] = obj.total;
		// //
		// payroll["employees"][employeeId]["reimbursements"] = obj;
		console.log(payroll["employees"][employeeId]);
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
			.success(function (resp) {
				//
				payrollEmployees = resp.employees;
				payroll = resp.payroll;
				//
				$("tbody").html(resp.view);
				//
				setView();
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			});
	}

	/**
	 * set the calculations
	 */
	function setView() {
		// loop through the rows
		$(".jsRegularPayrollEmployeeRow").map(function () {
			//
			const employeeId = $(this).data("id");
			//
			const employee = payrollEmployees[employeeId];
			//
			const payrollEmployee = payroll["employees"][employeeId];
			// for regular hours
			$(this)
				.find(".jsRegularHoursText")
				.text(
					`${payrollEmployee.hourly_compensations.regular_hours.hours} hrs`
				);
			//
			$(this)
				.find(".jsRegularHoursVal")
				.val(
					`${payrollEmployee.hourly_compensations.regular_hours.hours}`
				);

			// for overtime
			$(this)
				.find(".jsOvertimeText")
				.text(
					`${payrollEmployee.hourly_compensations.overtime.hours} hrs`
				);
			//
			$(this)
				.find(".jsOvertimeVal")
				.val(`${payrollEmployee.hourly_compensations.overtime.hours}`);
			// for double overtime
			$(this)
				.find(".jsDoubleOvertimeText")
				.text(
					`${payrollEmployee.hourly_compensations.double_overtime.hours} hrs`
				);
			//
			$(this)
				.find(".jsDoubleOvertimeVal")
				.val(
					`${payrollEmployee.hourly_compensations.double_overtime.hours}`
				);
			// for bonus
			$(this)
				.find(".jsBonusText")
				.text(`$${payrollEmployee.fixed_compensations.bonus.amount}`);
			//
			$(this)
				.find(".jsBonusValue")
				.val(`${payrollEmployee.fixed_compensations.bonus.amount}`);
			// payment method
			$(this)
				.find(
					'.jsPaymentMethod option[value="' +
						payrollEmployee.payment_method +
						'"]'
				)
				.prop("selected", true);
			//
			setSingleView(employeeId);
		});
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
		//
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
		//
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
		//
		if (ref.find(".jsBonusText").length) {
			// calculate double overtime
			amountToShow += parseFloat(
				payrollOBJ["fixed_compensations"]["bonus"]["amount"]
			);
		}
		//
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
		console.log(amountToShow);
		// limit to 2 digits
		amountToShow = amountToShow.toFixed(2);
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
			'<button class="btn csW csBG4 csF16 jsModalCancel">Cancel</button>&nbsp;';
		rows +=
			'<button class="btn csW csBG3 csF16 jsSaveAdditionalEarningsBtn" data-id="' +
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
					`$${parseFloat(
						payroll["employees"][employeeId]["reimbursements"][
							"total"
						] || 0
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
			payroll["employees"][employeeId]["reimbursements"];

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
		if (reimbursements) {
			$.each(reimbursements.data, function (i, v) {
				rows += generateReimbursementRow(v);
			});
		}
		rows += "		</div>";
		rows += '		<div class="row">';
		rows += '			<div class="col-sm-12 col-xs-12 text-center">';
		rows +=
			'				<button class="btn csW csBG3 jsAddReimbursement" data-id="' +
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
			'<button class="btn csW csBG4 csF16 jsModalCancel">Cancel</button>&nbsp;';
		rows +=
			'<button class="btn csW csBG3 csF16 jsSaveAdditionalEarningsBtn" data-id="' +
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
