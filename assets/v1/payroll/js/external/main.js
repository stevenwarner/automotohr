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
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.message, function () {
					window.location.reload();
				});
			})
			;
	}

	//
	$(".jsDatePicker").daterangepicker({
		showDropdowns: true,
		singleDatePicker: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
		},
	});
	//
	ml(false, "jsPageLoader");
});
