$(function payrollSettings() {
	//
	$(".jsPayrollSettingUpdateBtn").click(function (event) {
		//
		event.preventDefault();
		//
		updateSettings(
			$("#jsPayrollSettingPaymentSpeed option:selected").val()
		);
	});

	/**
	 * update the paymentSpeed
	 *
	 * @param {string} paymentSpeed
	 */
	function updateSettings(paymentSpeed) {
		//
		ml(true, "jsPageLoader");
		//
		$.ajax({
			url: baseUrl("payrolls/settings"),
			method: "post",
			data: {
				paymentSpeed,
			},
		})
			.success(function (resp) {
				_success(resp.msg, function () {
					window.location.reload();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsPageLoader");
			});
	}
	// hides the loader
	ml(false, "jsPageLoader");
});
