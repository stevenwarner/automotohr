$(function payrollHistory() {
	/**
	 * XHR holder
	 */
	let XHR = null;

	// submit payroll
	$(document).on("click", ".jsCancelPayroll", function (event) {
		//
		event.preventDefault();
		//
		const payrollId = $(this).data("key");
		const payrollDeadline = $(this).data("deadline");
		const span = $(this).data("span");
		//
		let rows = "";

		rows +=
			"You may cancel the <strong>" +
			span +
			"</strong> payroll now and run it again later. Just note that your employees will be paid late if you don't run it by <strong>" +
			payrollDeadline +
			"</strong>.<br /><br />";
		rows +=
			'<h4 class="csF16"><strong>Did you initiate a wire transfer?</strong></h4>';
		rows +=
			'<p class="csF16">If you have wired funds to Gusto, you must cancel the wire with your bank.</p><br />';
		rows +=
			'<h4 class="csF16"><strong>Don\'t want to lose all your data?</strong></h4>';
		rows +=
			'<p class="csF16">Rest assured—we\'ll save all the info you entered for this payroll, in case you need to re-run it.</p>';
		//
		return _confirm(rows, function () {
			cancelPayroll(payrollId);
		});
	});

	/**
	 * cancels a payroll
	 * @param {int} payrollId
	 * @returns
	 */
	function cancelPayroll(payrollId) {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(
			true,
			"jsPageLoader",
			"Please wait, while we are cancelling the payroll. This might takes a few minutes."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/regular/" + payrollId + "/cancel"),
			method: "PUT",
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
				return _success(resp.msg, function () {
					window.location.refresh();
				});
			});
	}
	//
	ml(false, "jsPageLoader");
});
