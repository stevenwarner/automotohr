/**
 * Payroll dashboard
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @package Time & Attendance
 * @version 1.0
 */
$(function payrollDashboard() {
	/**
	 * XHR holder
	 */
	let XHR = null;

	/**
	 * holds the modal page id
	 */
	const modalId = "jsModalPage";
	const modalLoader = modalId + "Loader";
	const modalBody = modalId + "Body";

	/**
	 * minimum wages show and hide
	 */
	$(document).on('click', '.jsAdjustForMinimumWage', function(e) {	
		//
		if($(this).is(':checked')) {
			$(".jsMinimumWagesBox").removeClass('hidden');
		} else {
			$(".jsMinimumWagesBox").addClass('hidden');
			$('.jsMinimumWages').select2("val", "")
		}
	});

	/**
	 * edit a pay schedule
	 */
	$(".jsEditPaySchedule").click(function (event) {
		// stop the event
		event.preventDefault();
		//
		makePage(profileUserInfo.nameWithRole, "pay_schedule", function () {
			// hides the loader
			ml(false, modalLoader);
			//
			$("#jsPagePayScheduleForm").validate({
				rules: {
					pay_schedule: { required: true },
				},
				messages: {
					pay_schedule: { required: "Please select a pay schedule." },
				},
				submitHandler: function (form) {
					// convert form to form object
					const formObj = formArrayToObj($(form).serializeArray());
					//
					formObj.append("page", "pay_schedule");
					formObj.append("userId", profileUserInfo.userId);
					formObj.append("userType", profileUserInfo.userType);
					//
					return processCall(formObj, $(".jsPagePayScheduleBtn"));
				},
			});
		});
	});

	$(document).on('click', '.jsPagePayScheduleBtn', function(event) {	
		// stop the event
		event.preventDefault();
		//
		// reset the array
		let jobObj = {
			employeeType: $(".jsEmploymentType option:selected").val(),
			classification: $(".jsFLSAStatus option:selected").val().trim(),
			per: $(".jsPayType option:selected").val().trim(),
			hireDate: $(".jsHireDate").val().trim(),
			overTimeRule: $(".jsOvertimeRule option:selected").val().trim(),
			amount: $(".jsEmployeeRate").val().trim(),
			minimumWage: $('input[name="adjust_for_minimum_wage"]:checked').val() == 'on' ? 1 : 0,
			wagesId: $(".jsMinimumWages").select2("val"),
			guaranteeRate: $(".jsEmployeeGuaranteeRate").val().trim(),
			guaranteePer: $(".jsEmployeeGuaranteePayType option:selected").val().trim(),
			guaranteeTime: $(".jsEmployeeGuaranteeTimes").val().trim(),
		};
		//
		// set default error array
		let errors = [];
		// validate
		if (!jobObj.employeeType) {
			errors.push('"Employment type" is missing.');
		}
		if (!jobObj.classification) {
			errors.push('"FLSA status" is missing.');
		}
		if (!jobObj.per) {
			errors.push('"Pay type" is missing.');
		}
		if (!jobObj.hireDate) {
			errors.push('"Hire date" is missing.');
		}
		if (!jobObj.amount) {
			errors.push('"Rate" is missing.');
		}
		if (!jobObj.overTimeRule) {
			errors.push('"Overtime rule" is missing.');
		}
		if (jobObj.guaranteeRate) {
			if (!jobObj.guaranteeTime) {
				errors.push('"Guarantee time" is missing.');
			}
		}
		// check and show errors
		if (errors.length) {
			return alertify.alert(
				"ERROR",
				getErrorsStringFromArray(errors),
				CB
			);
		}
		// show the loader
		ml(true, modalLoader)
		// //
		XHR = $.ajax({
			url: window.location.origin + "/payrolls/employee_job_compensation/"+ profileUserInfo.userId + "/" + profileUserInfo.userType,
			method: "POST",
			data: jobObj,
		})
			.always(function () {
				//
				ml(false, modalLoader);
				//
				XHR = null;
			})
			.fail(function (resp) {
				console.log()
				return alertify.alert(
					"ERROR",
					getErrorsStringFromArray(resp.responseJSON.errors)
				);
			})
			.done(function (resp) {
				return alertify.alert(
					"SUCCESS",
					resp.msg
				);
			});
	});

	/**
	 * edit job & wage
	 */
	$(".jsEditJobWage").click(function (event) {
		// stop the event
		event.preventDefault();
		//
		makePage(profileUserInfo.nameWithRole, "job_and_wage", function () {
			// hides the loader
			ml(false, modalLoader);
			//
			$(".jsMinimumWages").select2({
				closeOnSelect: false,
			});
			$(".jsHireDate").daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
				autoApply: true,
				locale: {
					format: "MM/DD/YYYY",
				},
			});
			//
		});
	});

	/**
	 * generates the modal
	 * @param {string} pageTitle
	 * @param {string} pageSlug
	 * @param {function} cb
	 */
	function makePage(pageTitle, pageSlug, cb) {
		Modal(
			{
				Id: modalId,
				Title: pageTitle,
				Body: '<div id="' + modalBody + '"></div>',
				Loader: modalLoader,
			},
			function () {
				fetchPage(pageSlug, cb);
			}
		);
	}

	/**
	 * fetch page from server
	 * @param {string} pageSlug
	 * @param {function} cb
	 */
	function fetchPage(pageSlug, cb) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			XHR.abort();
		}
		// make a new call
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/page/" +
					pageSlug +
					"/" +
					profileUserInfo.userId +
					"/" +
					profileUserInfo.userType
			),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				// load the new body
				$("#" + modalBody).html(resp.view);
				// call the callback
				cb(resp);
			});
	}

	/**
	 * process the call
	 * @param {object} formObj
	 * @param {string} buttonRef
	 */
	function processCall(formObj, buttonRef) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			return;
		}
		//
		const btnRef = callButtonHook(buttonRef, true);
		// make a new call
		XHR = $.ajax({
			url: baseUrl("payrolls/page/update"),
			method: "POST",
			data: formObj,
			processData: false,
			contentType: false,
		})
			.always(function () {
				//
				callButtonHook(btnRef, false);
				//
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(
						"payrolls/dashboard/" +
							profileUserInfo.userType +
							"/" +
							profileUserInfo.userId
					);
				});
			});
	}
});
