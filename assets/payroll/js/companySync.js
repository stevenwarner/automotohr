$(function companySync() {
	//
	let xhr = null;
	let xhr2 = null;
	let xhr3 = null;
	let xhr4 = null;
	//
	$(".jsSyncCompany").click(function (event) {
		//
		event.preventDefault();
		//
		startCompanySyncProcess();
	});
	//
	$(".jsFinishCompanyOnboard").click(function (event) {
		//
		event.preventDefault();
		//
		finishCompanyOnboard();
	});
	//
	$(".jsApproveCompany").click(function (event) {
		//
		event.preventDefault();
		//
		approveCompany();
	});
	//
	$(".jsSendTestDeposits").click(function (event) {
		//
		event.preventDefault();
		//
		sendTestDeposits();
	});

	function startCompanySyncProcess() {
		//
		if (xhr !== null) {
			return;
		}
		//
		$(".jsSyncCompany").html(
			'<i class="fa fa-spinner fa-spin"></i> Syncing'
		);
		$(".jsSyncCompany").addClass("disabled");
		//
		xhr = $.get(window.location.origin + "/gusto/sync/all/" + companyId)
			.success(function () {
				xhr = null;
				//
				$(".jsSyncCompany").html('<i class="fa fa-refresh"></i> Sync');
				$(".jsSyncCompany").removeClass("disabled");
			})
			.fail(function () {
				xhr = null;
				//
				$(".jsSyncCompany").html('<i class="fa fa-refresh"></i> Sync');
				$(".jsSyncCompany").removeClass("disabled");
			});
	}

	function finishCompanyOnboard() {
		//
		if (xhr2 !== null) {
			return;
		}
		//
		$(".jsFinishCompanyOnboard i").toggleClass("fa-spinner fa-spin");
		$(".jsFinishCompanyOnboard").addClass("disabled");
		//
		xhr2 = $.get(
			window.location.origin +
				"/gusto/company/" +
				companyId +
				"/onboard/finish"
		)
			.success(function (resp) {
				xhr2 = null;
				//
				$(".jsFinishCompanyOnboard i").removeClass(
					"fa-spinner fa-spin"
				);
				$(".jsFinishCompanyOnboard").removeClass("disabled");
				//
				if (resp.view) {
					return Modal(
						{
							Title: "Company Onboard Steps",
							Id: "jsStepModal",
							Loader: "jsStepModalLoader",
							Body: resp.view,
						},
						function () {
							ml(false, "jsStepModalLoader");
						}
					);
				}
				//
				if (resp.errors) {
					return alertify.alert("Error", resp.errors.join("<br />"));
				}
				//
				if (resp.success) {
					window.location.reload();
				}
			})
			.fail(function () {
				xhr2 = null;
				//
				$(".jsFinishCompanyOnboard i").removeClass(
					"fa-spinner fa-spin"
				);
				$(".jsFinishCompanyOnboard").removeClass("disabled");
			});
	}

	function sendTestDeposits() {
		//
		if (xhr3 !== null) {
			return;
		}
		//
		$(".jsSendTestDeposits i").toggleClass("fa-spinner fa-spin");
		$(".jsSendTestDeposits").addClass("disabled");
		//
		xhr3 = $.get(
			window.location.origin +
				"/gusto/company/" +
				companyId +
				"/send_test_deposits"
		)
			.success(function (resp) {
				xhr3 = null;
				//
				$(".jsSendTestDeposits i").removeClass("fa-spinner fa-spin");
				$(".jsSendTestDeposits").removeClass("disabled");
				//
				if (resp.errors) {
					return alertify.alert("Error", resp.errors.join("<br />"));
				}
				//
				return alertify.alert(
					"Success!",
					`
						<p>Please use the following deposits to verify bank account</p>
						<br />
						<p><strong>Deposit 1:</strong> ${resp.deposit_1}
						<p><strong>Deposit 2:</strong> ${resp.deposit_2}
					`
				);
			})
			.fail(function () {
				xhr3 = null;
				//
				$(".jsSendTestDeposits i").removeClass("fa-spinner fa-spin");
				$(".jsSendTestDeposits").removeClass("disabled");
			});
	}

	function approveCompany() {
		//
		if (xhr4 !== null) {
			return;
		}
		//
		$(".jsApproveCompany i").toggleClass("fa-spinner fa-spin");
		$(".jsApproveCompany").addClass("disabled");
		//
		xhr4 = $.get(
			window.location.origin + "/gusto/company/" + companyId + "/approve"
		)
			.success(function (resp) {
				xhr4 = null;
				//
				$(".jsApproveCompany i").removeClass("fa-spinner fa-spin");
				$(".jsApproveCompany").removeClass("disabled");
				//
				if (resp.errors) {
					return alertify.alert("Error", resp.errors.join("<br />"));
				}
				//
				if (resp.success) {
					return alertify.alert(
						"success",
						"Company approved successfully.",
						function () {
							window.location.reload();
						}
					);
				}
			})
			.fail(function () {
				xhr4 = null;
				//
				$(".jsApproveCompany i").removeClass("fa-spinner fa-spin");
				$(".jsApproveCompany").removeClass("disabled");
			});
	}
});
