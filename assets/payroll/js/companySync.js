$(function companySync() {
	//
	var xhr = null;
	//
	$(".jsSyncCompany").click(function (event) {
		//
		event.preventDefault();
		//
		startCompanySyncProcess();
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
				//
				$(".jsSyncCompany").html('<i class="fa fa-refresh"></i> Sync');
				$(".jsSyncCompany").removeClass("disabled");
			})
			.fail(function () {
				//
				$(".jsSyncCompany").html('<i class="fa fa-refresh"></i> Sync');
				$(".jsSyncCompany").removeClass("disabled");
			});
	}
});
