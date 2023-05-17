$(function companySync() {
	//
	var xhr = null;
	var xhr2 = null;
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
});
