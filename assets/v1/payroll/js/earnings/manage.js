/**
 *  Earning types
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function manageEarningTypes() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * holds the modal id
	 */
	let modalId = "jsEarningTypesFlowModal";
	/**
	 * holds the earning id
	 */
	let earningId = 0;
	/**
	 * holds the page loader
	 */
	let pageLoader = "jsPageLoader";

	/**
	 * capture the view admin event
	 */
	$(document).on("click", ".jsMenuTrigger", function (event) {
		//
		event.preventDefault();
		//
		loadEditView($(this).data("step"));
	});

	/**
	 * deactivate
	 */
	$(".jsDeactivateEarningType").click(function (event) {
		//
		event.preventDefault();
		//
		const earningTypeId = $(this).closest("tr").data("id");
		//
		return alertify
			.confirm(
				"Do you really want to deactivate this earning type?<br /> This action is not revertible.",
				function () {
					startDeactivateLink(earningTypeId);
				}
			)
			.setHeader("Confirm!");
	});

	/**
	 * add an earning type
	 */
	$(".jsAddEarningType").click(function (event) {
		//
		event.preventDefault();
		//
		Modal(
			{
				Id: "jsAddEarningType",
				Title: "Add an Earning Type",
				Loader: "jsAddEarningTypeLoader",
				Body: '<div id="jsAddEarningTypeBody"></div>',
			},
			function () {
				loadView("add");
			}
		);
	});

	/**
	 * edit an earning type
	 */
	$(".jsUpdateEarningType").click(function (event) {
		//
		event.preventDefault();
		//
		earningId = $(this).closest("tr").data("id");
		//
		Modal(
			{
				Id: "jsEditEarningType",
				Title: "Edit an Earning Type",
				Loader: "jsEditEarningTypeLoader",
				Body: '<div id="jsEditEarningTypeBody"></div>',
			},
			function () {
				loadEditView("edit");
			}
		);
	});

	/**
	 * add an earning type
	 */
	$(document).on("submit", "#jsEarningForm", function (event) {
		//
		event.preventDefault();
		//
		const obj = formArrayToObj($(this).serializeArray(), true);
		//
		let errorArray = [];
		// validation
		if (!obj.name) {
			errorArray.push('"Name" is missing.');
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		const btn = callButtonHook($(".jsAddBtn"), true);

		//
		ml(
			true,
			`jsAddEarningTypeLoader`,
			"Please wait, while we are creating earning type."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/earnings/add"),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				$(".jsModalCancel").trigger("click");
				//
				return alertify.alert("Success!", resp.msg, function () {
					window.location.reload();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				callButtonHook(btn);
				//
				ml(false, `jsAddEarningTypeLoader`);
			});
	});

	/**
	 * edi an earning type
	 */
	$(document).on("submit", "#jsEarningEditForm", function (event) {
		//
		event.preventDefault();
		//
		const obj = formArrayToObj($(this).serializeArray(), true);
		//
		let errorArray = [];
		// validation
		if (!obj.name) {
			errorArray.push('"Name" is missing.');
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		const btn = callButtonHook($(".jsEditBtn"), true);
		//
		ml(
			true,
			`jsEditEarningTypeLoader`,
			"Please wait, while we are updating earning type."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/earnings/edit/" + earningId + ""),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				$(".jsModalCancel").trigger("click");
				//
				return alertify.alert("Success!", resp.msg, function () {
					window.location.reload();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				callButtonHook(btn);
				//
				ml(false, `jsEditEarningTypeLoader`);
			});
	});

	/**
	 * deactivates an earning type
	 *
	 * @param {number} earningTypeId The id of the earning type
	 */
	function startDeactivateLink(earningTypeId) {
		// show the loader
		ml(true, pageLoader);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/earnings/deactivate/" + earningTypeId),
			method: "DELETE",
		})
			.success(function () {
				return alertify.alert(
					"Success!",
					"You have successfully deactivated the earning type.",
					function () {
						window.location.reload();
					}
				);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, pageLoader);
			});
	}

	/**
	 * Load add page
	 * @param {string} step
	 */
	function loadView(step) {
		//
		_ml(true, `jsAddEarningTypeLoader`);
		//
		let url = "payrolls/earnings/" + step;
		//
		XHR = $.ajax({
			url: baseUrl(url),
			method: "GET",
			caches: false,
		})
			.success(function (response) {
				//
				$(`#jsAddEarningTypeBody`).html(response.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `jsAddEarningTypeLoader`);
			});
	}

	/**
	 * Load edit page
	 * @param {string} step
	 */
	function loadEditView(step) {
		//
		_ml(true, `jsEditEarningTypeLoader`);
		//
		let url = "payrolls/earnings/" + step + "/" + earningId;
		//
		XHR = $.ajax({
			url: baseUrl(url),
			method: "GET",
			caches: false,
		})
			.success(function (response) {
				//
				$(`#jsEditEarningTypeBody`).html(response.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `jsEditEarningTypeLoader`);
			});
	}

	//
	ml(false, pageLoader);
});
