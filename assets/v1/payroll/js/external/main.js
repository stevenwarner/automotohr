$(function externalPayrolls() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	//
	$(".jsExternalPayrollDelete").click(function (event) {
		//
		event.preventDefault();
		//
		const externalPayrollId = $(this).closest("tr").data("id");
		//
		return _confirm(
			"<p>Do you really want to delete this external payroll?<p><br /><p>This action is not revertible. However, you can re-add this external payroll.</p>",
			function () {
				startProcessOfExternalPayrollDeletion(externalPayrollId);
			}
		);
	});

	/**
	 * start the process of external payroll deletion
	 * @param {int} dataOBJ
	 */
	function startProcessOfExternalPayrollDeletion(externalPayrollId) {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(true, "jsPageLoader");
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/external/" + externalPayrollId + ""),
			method: "DELETE",
		})
			.success(function (resp) {
				return _success(resp.message, function () {
					window.location.reload();
				});
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
	$(".jsDatePicker").datepicker({
		format: "mm/dd/yyyy",
		changeYear: true,
		changeMonth: true,
	});
	//
	ml(false, "jsPageLoader");
});
