$(function regularPayrolls() {
	/**
	 * XHR holder
	 */
	let XHR = null;

	//
	$(document).on("click", ".jsDiscardPayrollChanges", function (event) {
		//
		event.preventDefault();
		//
		const regularPayrollId = $(this).data("key");
		const ref = $(this);
		//
		return _confirm(
			"<p>Do you really want to discard changes?<p><br /><p>This action will flush all the employees data set for this payroll and is not revertible. However, you can re-add this payroll.</p>",
			function () {
				discardPayrollChanges(regularPayrollId, ref);
			}
		);
	});

	/**
	 * start the process of regular payroll revert
	 * @param {int} regularPayrollId
	 */
	function discardPayrollChanges(regularPayrollId, btnRef) {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		const _btn = callButtonHook(btnRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/regular/discard/" + regularPayrollId + ""),
			method: "POST",
		})
			.always(function () {
				//
				XHR = null;
				//
				callButtonHook(_btn, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.message, function () {
					window.location.reload();
				});
			});
	}
});
