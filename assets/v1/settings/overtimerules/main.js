/**
 * Payroll dashboard
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @package Time & Attendance
 * @version 1.0
 */
$(function overtimeRules() {
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
	$(".jsAddOvertimeRuleBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		//
		makePage("New Overtime Rule", "overtime_rules", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			const validatorRef = $("#jsPageOvertimeRuleForm").validate({
				rules: {
					// rule_name: { required: true },
					// overtime_multiplier: { required: true },
					// double_overtime_multiplier: { required: true },
				},
				submitHandler: function (form) {
					// convert form to form object
					const formObj = formArrayToObj($(form).serializeArray());
					//
					formObj.append("page", "overtime_rules");
					const weeklyObject = getRule("weekly");
					const dailyObject = getRule("daily");
					console.log(dailyObject);
					//
					// return processCall(formObj, $(".jsPageOvertimeRuleBtn"));
				},
			});
		});
	});

	/**
	 * dynamic disable the fields
	 */
	$(document).on("click", ".jsAddChangeStatus", function () {
		// get the target value
		const target = $(this).data("target");
		// enable and disable the input
		$('[name="' + target + '"]').prop("disabled", !$(this).prop("checked"));
		// remove all rules by default
		$('[name="' + target + '"]').rules("remove");
		// if enabled
		if ($(this).prop("checked")) {
			// apply rules
			$('[name="' + target + '"]').rules("add", {
				required: true,
				number: true,
			});
		}
	});

	$(".jsAddOvertimeRuleBtn").trigger("click");

	/**
	 * generates the modal
	 * @param {string} pageTitle
	 * @param {string} pageSlug
	 * @param {number} pageId
	 * @param {function} cb
	 */
	function makePage(pageTitle, pageSlug, pageId, cb) {
		Modal(
			{
				Id: modalId,
				Title: pageTitle,
				Body: '<div id="' + modalBody + '"></div>',
				Loader: modalLoader,
			},
			function () {
				fetchPage(pageSlug, pageId, cb);
			}
		);
	}

	/**
	 * fetch page from server
	 * @param {string} pageSlug
	 * @param {function} cb
	 */
	function fetchPage(pageSlug, pageId, cb) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			XHR.abort();
		}
		// make a new call
		XHR = $.ajax({
			url: baseUrl("settings/page/" + pageSlug + "/" + pageId),
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

	/**
	 * 
	 * @param {string} type 
	 * @returns 
	 */
	function getRule(type) {
		const obj = {};
		//
		if (type === "daily") {
			[
				"monday",
				"tuesday",
				"wednesday",
				"thursday",
				"friday",
				"saturday",
				"sunday",
			].forEach(function (v0) {
				//
				obj[v0] = {
					overtime: {},
					double: {},
				};
                // get the status
				obj[v0].overtime.status = $(
					'[name="' + v0 + '_overtime_status"]'
				).prop("checked");
                // get the hours
				obj[v0].overtime.hours = obj[v0].overtime.status
					? $('[name="' + v0 + "_overtime_hours]").val()
					: "";

                    console.log(obj[v0]["overtime"])
			});
		} else {
			//
			obj["overtime"] = { status: "off", hours: "" };
			obj["double"] = { status: "off", hours: "" };
			// overtime
			obj.overtime.status = $(
				'[name="' + type + '_overtime_status"]'
			).prop("checked");
			if (obj.overtime.status) {
				obj.overtime.hours = $(
					'[name="' + type + '_overtime_hours"]'
				).val();
			} else {
				obj.overtime.hours = "";
			}
			// double time
			obj.double.status = $('[name="' + type + '_double_status"]').prop(
				"checked"
			);
			if (obj.double.status) {
				obj.double.hours = $(
					'[name="' + type + '_double_hours"]'
				).val();
			} else {
				obj.double.hours = "";
			}
		}

		return obj;
	}
});
