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
			$("#jsPageJobWageForm").validate({
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
					return processCall(formObj, $(".jsPageJobWageBtn"));
				},
			});
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
