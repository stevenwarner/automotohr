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
	 * XHR validator
	 */
	let validatorRef = null;

	/**
	 * holds the modal page id
	 */
	const modalId = "jsModalPage";
	const modalLoader = modalId + "Loader";
	const modalBody = modalId + "Body";

	/**
	 * add
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
					rule_name: { required: true },
					overtime_multiplier: { required: true },
					double_overtime_multiplier: { required: true },
				},
				submitHandler: function (form) {
					// convert form to form object
					const formObj = formArrayToObj($(form).serializeArray());
					//
					formObj.append("weekly", JSON.stringify(getRule("weekly")));
					formObj.append(
						"consecutive",
						JSON.stringify(getRule("consecutive"))
					);
					formObj.append(
						"holiday",
						JSON.stringify(getRule("holidays"))
					);
					formObj.append("daily", JSON.stringify(getRule("daily")));
					//
					return processCall(
						formObj,
						$(".jsPageOvertimeRuleBtn"),
						"overtimerules"
					);
				},
			});
		});
	});

	/**
	 * edit
	 */
	$(".jsEditOvertimeRuleBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		// holds the reference
		const reference = $(this).closest(".jsBox");
		// holds the id event
		const id = reference.data("id");
		//
		makePage("Edi Overtime Rule", "overtime_rules", id, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			validatorRef = $("#jsPageOvertimeRuleForm").validate({
				rules: {
					rule_name: { required: true },
					overtime_multiplier: { required: true },
					double_overtime_multiplier: { required: true },
				},
				submitHandler: function (form) {
					// convert form to form object
					const formObj = formArrayToObj($(form).serializeArray());

					formObj.append("id", id);
					//
					formObj.append("weekly", JSON.stringify(getRule("weekly")));
					formObj.append(
						"consecutive",
						JSON.stringify(getRule("consecutive"))
					);
					formObj.append(
						"holiday",
						JSON.stringify(getRule("holidays"))
					);
					formObj.append("daily", JSON.stringify(getRule("daily")));
					//
					return processCall(
						formObj,
						$(".jsPageOvertimeRuleBtn"),
						"overtimerules"
					);
				},
			});
		});
	});

	/**
	 * delete
	 */
	$(".jsDeleteOvertimeRuleBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		// holds the reference
		const reference = $(this).closest(".jsBox");
		// holds the id event
		const id = reference.data("id");
		//
		return _confirm(
			"Are you sure you want to delete it? This action cannot be undone.",
			function () {
				return processDelete(reference, id);
			}
		);
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
	 * @param {string} url Optional
	 */
	function processCall(formObj, buttonRef, url) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			return;
		}
		//
		const btnRef = callButtonHook(buttonRef, true);
		// make a new call
		XHR = $.ajax({
			url: baseUrl(url || "payrolls/page/update"),
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
				//
				validatorRef?.destroy();
				return _success(resp.msg, function () {
					window.location.href = baseUrl("overtimerules");
				});
			});
	}

	/**
	 * process delete
	 * @param {object} reference
	 * @param {number} id
	 */
	function processDelete(reference, id) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			return;
		}
		//
		const btnRef = callButtonHook(
			reference.find(".jsDeleteOvertimeRuleBtn"),
			true
		);
		// make a new call
		XHR = $.ajax({
			url: baseUrl("overtimerules/" + id),
			method: "DELETE",
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
					window.location.href = baseUrl("overtimerules");
				});
			});
	}

	/**
	 * get the rules object
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
				// set the object
				obj[v0] = {
					overtime: {},
					double: {},
				};
				// set the overtime status
				obj[v0].overtime.status = $(
					'[name="' + v0 + '_overtime_status"]'
				).prop("checked");
				// set the overtime hours
				obj[v0].overtime.hours = obj[v0].overtime.status
					? parseFloat(
							$('[name="' + v0 + '_overtime_hours"]')
								.val()
								.trim()
					  )
					: "";

				// set the double status
				obj[v0].double.status = $(
					'[name="' + v0 + '_double_status"]'
				).prop("checked");
				// set the double hours
				obj[v0].double.hours = obj[v0].double.status
					? parseFloat(
							$('[name="' + v0 + '_double_hours"]')
								.val()
								.trim()
					  )
					: "";
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
				obj.overtime.hours = parseFloat(
					$('[name="' + type + '_overtime_hours"]')
						.val()
						.trim()
				);
			} else {
				obj.overtime.hours = "";
			}
			// double time
			obj.double.status = $('[name="' + type + '_double_status"]').prop(
				"checked"
			);
			if (obj.double.status) {
				obj.double.hours = parseFloat(
					$('[name="' + type + '_double_hours"]')
						.val()
						.trim()
				);
			} else {
				obj.double.hours = "";
			}
		}

		return obj;
	}
});
