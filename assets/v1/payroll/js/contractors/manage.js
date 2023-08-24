/**
 * Manage contractors
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function manageContractors() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * holds the contractor id
	 */
	let contractorId = 0;
	/**
	 * holds the modal id
	 */
	let modalId = "jsContractorFlowModal";
	/**
	 * capture the view admin event
	 */
	$(".jsContractorAdd").click(function (event) {
		//
		event.preventDefault();
		//
		loadContractorAddView();
	});
	//
	$(".jsContractorEdit").click(function (event) {
		//
		event.preventDefault();
		//
		contractorId = $(this).closest("tr").data("id");
		//
		loadContractorEditView();
	});

	//
	$(document).on("click", ".jsContractorSingleForm", function (event) {
		//
		event.preventDefault();
		//
		loadEditView("single_form", $(this).closest("tr").data("id"));
	});
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
	 * capture the contractor type event
	 */
	$(document).on("change", "#jsContractorType", function () {
		//
		let selectedValue = $(this).val();
		//
		$(".jsContractorIndividual").closest(".form-group").addClass("hidden");
		$(".jsContractorBusiness").closest(".form-group").addClass("hidden");

		//
		$(".jsContractor" + selectedValue)
			.closest(".form-group")
			.removeClass("hidden");
	});

	/**
	 * capture the contractor type event
	 */
	$(document).on("change", "#jsContractorFileNewHireReport", function () {
		//
		if ($(this).val() == "1") {
			$(".jsContractorWorkState")
				.closest(".form-group")
				.removeClass("hidden");
		} else {
			$(".jsContractorWorkState")
				.closest(".form-group")
				.addClass("hidden");
		}
	});

	/**
	 * capture the contractor type event
	 */
	$(document).on("change", "#jsContractorWageType", function () {
		//
		if ($(this).val() == "Hourly") {
			$(".jsContractorHourlyRate")
				.closest(".form-group")
				.removeClass("hidden");
		} else {
			$(".jsContractorHourlyRate")
				.closest(".form-group")
				.addClass("hidden");
		}
	});

	/**
	 * capture the contractor type event
	 */
	$(document).on("click", ".jsContractorPaymentMethod", function () {
		//
		if ($(this).val() == "Check") {
			$(".jsCheck").removeClass("hidden");
			$(".jsDirectDeposit").addClass("hidden");
		} else {
			$(".jsCheck").addClass("hidden");
			$(".jsDirectDeposit").removeClass("hidden");
		}
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
	 * edit contractor
	 */
	$(document).on("click", ".jsContractorSaveEditBtn", function (e) {
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
			obj.ssn.replace(/\D/g, "").length !== 9 &&
			obj.ssn.match(/x/gi) === null
		) {
			errorArray.push('"Social Security Number (SSN)" is missing.');
		}
		// Business
		if (obj.type === "Business" && !obj.businessName) {
			errorArray.push('"Business name" is missing.');
		}

		if (
			obj.type === "Business" &&
			obj.ein.replace(/\D/g, "").length !== 9 &&
			obj.ein.match(/x/gi) === null
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
			url: baseUrl(
				"payrolls/flow/contractors/" +
					contractorId +
					"/personal_details"
			),
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
	 * edit home address
	 */
	$(document).on("click", ".jsContractorHomeAddressSaveBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			street_1: $("#jsContractorStreet1").val().trim(),
			street_2: $("#jsContractorStreet2").val().trim(),
			city: $("#jsContractorCity").val().trim(),
			state: $("#jsContractorState").val().trim(),
			zip: $("#jsContractorZip").val().trim(),
		};
		//
		let errorArray = [];
		// Business
		if (!obj.street_1) {
			errorArray.push('"Street 1" is missing.');
		}
		if (!obj.city) {
			errorArray.push('"City" is missing.');
		}
		if (!obj.state) {
			errorArray.push('"State" is missing.');
		}
		if (!obj.zip) {
			errorArray.push('"Zip" is missing.');
		}
		if (obj.zip.length !== 5) {
			errorArray.push('"Zip" must be 9 digits long.');
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
			url: baseUrl(
				"payrolls/flow/contractors/" + contractorId + "/home_address"
			),
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
	 * edit payment method
	 */
	$(document).on("click", ".jsContractorPaymentMethodSaveBtn", function (e) {
		//
		e.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		//
		let obj = {
			type: $(".jsContractorPaymentMethod:checked").val(),
			accountName: $("#jsContractorAccountName").val().trim(),
			routingNumber: $("#jsContractorRoutingNumber").val().trim(),
			accountNumber: $("#jsContractorAccountNumber").val().trim(),
			accountType: $(".jsContractorType:checked").val(),
		};
		//
		let errorArray = [];
		// Business
		if (!obj.type) {
			errorArray.push('"Type" is missing.');
		}
		if (obj.type === "Direct Deposit") {
			//
			if (!obj.accountName) {
				errorArray.push('"Account name" is missing.');
			}
			//
			if (obj.routingNumber.length !== 9) {
				errorArray.push('"Routing number" is missing.');
			}
			//
			if (obj.accountNumber.length !== 9) {
				errorArray.push('"Account number" is missing.');
			}
			//
			if (!obj.accountType) {
				errorArray.push('"Account type" is missing.');
			}
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
			url: baseUrl(
				"payrolls/flow/contractors/" + contractorId + "/payment_method"
			),
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
	 * loads contractor add view
	 * @returns
	 */
	function loadContractorAddView() {
		// generate modal
		Modal(
			{
				Title: "Contractor Flow",
				Id: modalId,
				Loader: `${modalId}Loader`,
				Body: `<div id="${modalId}Body"></div>`,
			},
			function () {
				loadView("add");
			}
		);
	}

	/**
	 * loads contractor add view
	 * @returns
	 */
	function loadContractorEditView() {
		// generate modal
		Modal(
			{
				Title: "Contractor Flow",
				Id: modalId,
				Loader: `${modalId}Loader`,
				Body: `<div id="${modalId}Body"></div>`,
			},
			function () {
				loadEditView("summary");
			}
		);
	}

	/**
	 * Load page
	 * @param {string} step
	 */
	function loadView(step) {
		//
		_ml(true, `${modalId}Loader`);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/flow/contractors/" + step),
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
});
