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
	 * Add contractor
	 */
	$(document).on("click", ".jsContractorSaveBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			type: $("#jsContractorType option:selected").val(),
			wageType: $("#jsContractorWageType option:selected").val(),
			hourlyRate: $("#jsContractorHourlyRate").val().trim(),
			startDate: $("#jsContractorStartDate").val().trim(),
			email: $("#jsContractorEmail").val().trim(),
			firstName: $("#jsContractorFirstName").val().trim(),
			middleInitial: $("#jsContractorMiddleInitial").val().trim(),
			lastName: $("#jsContractorLastName").val().trim(),
			fileNewHireReport: $(
				"#jsContractorFileNewHireReport option:selected"
			).val(),
			workState: $("#jsContractorWorkState").val().trim(),
			ssn: $("#jsContractorSSN").val().trim(),
			isActive: $("#jsContractorIsActive option:selected").val(),
			businessName: $("#jsContractorBusinessName").val().trim(),
			ein: $("#jsContractorEIN").val().trim(),
		};
		//
		let errorArray = [];
		// validation
		if (obj.wageType === "Hourly" && !obj.hourlyRate) {
			errorArray.push('"Hourly rate" is missing.');
		}
		if (
			obj.wageType === "Hourly" &&
			obj.hourlyRate.match(/[^0-9.]/gi) !== null
		) {
			errorArray.push('"Hourly rate" is invalid.');
		}
		if (!obj.startDate) {
			errorArray.push('"Start date" is missing.');
		}
		if (obj.email && !obj.email.verifyEmail()) {
			errorArray.push('"Email" is missing.');
		}
		// for individual
		if (obj.type === "Individual" && !obj.firstName) {
			errorArray.push('"First name" is missing.');
		}
		if (obj.type === "Individual" && !obj.lastName) {
			errorArray.push('"Last name" is missing.');
		}
		if (
			obj.type === "Individual" &&
			obj.fileNewHireReport === "1" &&
			!obj.workState
		) {
			errorArray.push('"Work state" is missing.');
		}

		if (
			obj.type === "Individual" &&
			obj.ssn.replace(/\D/g, "").length !== 9
		) {
			errorArray.push('"Social Security Number (SSN)" is missing.');
		}
		// Business
		if (obj.type === "Business" && !obj.businessName) {
			errorArray.push('"Business name" is missing.');
		}

		if (
			obj.type === "Business" &&
			obj.ein.replace(/\D/g, "").length !== 9
		) {
			errorArray.push('"EIN" is missing.');
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		//
		ml(
			true,
			`${modalId}Loader`,
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/flow/contractors/add"),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				return alertify.alert("Success!", resp.msg, CB);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `${modalId}Loader`);
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
					CB
				);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, pageLoader);
			});
	}

	/**
	 * Load edit page
	 * @param {string} step
	 * @param {int} id
	 */
	function loadEditView(step, id) {
		//
		_ml(true, `${modalId}Loader`);
		//
		let url = "payrolls/flow/contractors/" + contractorId + "/" + step;
		if (id) {
			url += "/" + id;
		}
		//
		XHR = $.ajax({
			url: baseUrl(url),
			method: "GET",
			caches: false,
		})
			.success(function (response) {
				//
				$(`#${modalId}Body`).html(response.view);
				//
				loadEvents(step);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `${modalId}Loader`);
			});
	}

	/**
	 * load events
	 * @param {string} step
	 */
	function loadEvents(step) {
		//
		if (step === "add" || step === "personal_details") {
			$(".jsContractorStartDate").datepicker({
				format: "mm/dd/yyyy",
				changeYear: true,
				changeMonth: true,
				yearRange: "-100:+0",
			});
			//
			$("#jsContractorType").trigger("change");
			$("#jsContractorWageType").trigger("change");
			$("#jsContractorFileNewHireReport").trigger("change");
		} else if (step === "home_address") {
			//
			$(".jsContractorPaymentMethod").trigger("click");
		} else if (step === "payment_method") {
			//
			$(".jsContractorPaymentMethod:checked").trigger("click");
		}
	}

	$.ajaxSetup({
		cache: false,
	});

	//
	ml(false, pageLoader);
});
