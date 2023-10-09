$(function regularPayrollsTimeOff() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	/**
	 * get the payroll id from segment
	 */
	const payrollId = getSegment(2);
	/**
	 * get the payroll id from segment
	 */
	const payrollReason = getSegment(1);
	/**
	 * get the payroll id from segment
	 */
	let payroll = {};

	// save payroll
	$(document).on("click", ".jsRegularPayrollStep2Save", function (event) {
		//
		event.preventDefault();
		//
		$(".jsRegularPayrollEmployeeRowTimeOff").map(function () {
			//
			const employeeId = $(this).data("id");
			//
			$(this)
				.find(".jsTimeOffField")
				.map(function () {
					// set the proper amount
					payroll["employees"][employeeId]["fixed_compensations"][
						$(this).prop("name")
					]["amount"] = parseFloat($(this).val().trim() || 0);
				});
		});
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
				action: "Time Off",
				employees: payroll.employees,
			},
			cache: false,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					ml(
						true,
						"jsPageLoader",
						"Please wait while we are generating a view."
					);
					window.location.href = baseUrl(
						"payrolls/" +
							payrollReason +
							"/" +
							payrollId +
							"/review"
					);
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			});
	});

	/**
	 * get the regular payroll
	 */
	function getRegularPayrollTimeOff() {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(
			true,
			"jsPageLoader",
			"Please wait, while we are generating a view."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/regular/" + payrollId + "/view/2"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				payroll = resp.payroll;
				//
				$("tbody").html(resp.view);
				//
				let rows = "";
				rows += "<tr>";
				rows += '	<td colspan="2" class="vam text-right">';
				rows +=
					'		<a href="' +
					baseUrl(
						"payrolls/" + payrollReason + "/" + payrollId + "/hours_and_earnings"
					) +
					'" class="btn csW csBG4 csF16">';
				rows +=
					'			<i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>';
				rows += "			&nbsp;Back";
				rows += "		</a>";
				rows +=
					'		<button class="btn csW csBG3 csF16 jsRegularPayrollStep2Save">';
				rows += '			<i class="fa fa-save csF16" aria-hidden="true"></i>';
				rows += "			&nbsp;Save & continue";
				rows += "		</button>";
				rows += "	</td>";
				rows += "</tr>";
				//
				$("tfoot").html(rows);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			});
	}

	//
	getRegularPayrollTimeOff();
});
