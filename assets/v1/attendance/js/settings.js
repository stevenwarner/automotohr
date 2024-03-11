/**
 * Attendance settings controller
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 * @package Attendance
 */
$(function attendanceSetting() {
	let XHR = null;
	// set the form reference
	// so later dynamically add rules
	$("#jsAttendanceSettingsForm").validate({
		submitHandler: function (form) {
			// convert the array to object
			const dataObject = {
				general: {
					daily_limit: {
						status: "0",
						value: "2",
					},
					auto_clock_out: {
						status: "0",
						value: "2",
					},
				},
				controls: {
					employee_can_clock_in: "0",
					employee_can_manipulate_time_sheet: "0",
				},
				reminders: {
					days: [],
					remind_employee_to_clock_in: {
						status: "0",
						value: "",
					},
					remind_employee_to_clock_out: {
						status: "0",
						value: "",
					},
					daily_limit: {
						status: "0",
						value: "2",
					},
				},
			};
			// set the general
			dataObject.general.daily_limit.status = $(form)
				.find('[name="general_daily_limit_status"]')
				.prop("checked")
				? "1"
				: "0";
			dataObject.general.daily_limit.value = $(form)
				.find('[name="general_daily_limit_value"]')
				.val()
				.trim();
			dataObject.general.auto_clock_out.status = $(form)
				.find('[name="general_auto_clock_out_status"]')
				.prop("checked")
				? "1"
				: "0";
			dataObject.general.auto_clock_out.value = $(form)
				.find('[name="general_auto_clock_out_value"]')
				.val()
				.trim();
			// set controls
			dataObject.controls.employee_can_clock_in = $(form)
				.find('[name="employee_can_clock_in"]')
				.prop("checked")
				? "1"
				: "0";
			dataObject.controls.employee_can_manipulate_time_sheet = $(form)
				.find('[name="employee_can_manipulate_time_sheet"]')
				.prop("checked")
				? "1"
				: "0";
			// set reminders
			$(form)
				.find('[name="reminder_days"]:checked')
				.map(function () {
					dataObject.reminders.days.push($(this).val());
				});
			dataObject.reminders.remind_employee_to_clock_in.status = $(form)
				.find('[name="remind_employee_to_clock_in_status"]')
				.prop("checked")
				? "1"
				: "0";
			dataObject.reminders.remind_employee_to_clock_in.value = $(form)
				.find('[name="remind_employee_to_clock_in_value"]')
				.val()
				.trim();
			dataObject.reminders.remind_employee_to_clock_out.status = $(form)
				.find('[name="remind_employee_to_clock_out_status"]')
				.prop("checked")
				? "1"
				: "0";
			dataObject.reminders.remind_employee_to_clock_out.value = $(form)
				.find('[name="remind_employee_to_clock_out_value"]')
				.val()
				.trim();
			dataObject.reminders.daily_limit.status = $(form)
				.find('[name="reminders_daily_limit_status"]')
				.prop("checked")
				? "1"
				: "0";
			dataObject.reminders.daily_limit.value = $(form)
				.find('[name="reminders_daily_limit_value"]')
				.val()
				.trim();

			return updateCompanyAttendanceSettings(dataObject);
		},
	});

	$('[name="general_daily_limit_status"]').click(function () {
		$('[name="general_daily_limit_value"]').prop(
			"disabled",
			!$(this).prop("checked")
		);
	});
	$('[name="general_auto_clock_out_status"]').click(function () {
		$('[name="general_auto_clock_out_value"]').prop(
			"disabled",
			!$(this).prop("checked")
		);
	});
	$('[name="remind_employee_to_clock_in_status"]').click(function () {
		$('[name="remind_employee_to_clock_in_value"]').prop(
			"disabled",
			!$(this).prop("checked")
		);
		// add the validation and apply timer
		if ($(this).prop("checked")) {
			$('[name="remind_employee_to_clock_in_value"]').rules("add", {
				required: true,
			});
		} else {
			$('[name="remind_employee_to_clock_in_value"]').rules("remove");
		}
	});
	$('[name="remind_employee_to_clock_out_status"]').click(function () {
		$('[name="remind_employee_to_clock_out_value"]').prop(
			"disabled",
			!$(this).prop("checked")
		);
		// add the validation and apply timer
		if ($(this).prop("checked")) {
			$('[name="remind_employee_to_clock_out_value"]').rules("add", {
				required: true,
			});
		} else {
			$('[name="remind_employee_to_clock_out_value"]').rules("remove");
		}
	});
	$('[name="reminders_daily_limit_status"]').click(function () {
		$('[name="reminders_daily_limit_value"]').prop(
			"disabled",
			!$(this).prop("checked")
		);
	});
	$("label.jsCircleCheckBox").click(function (event) {
		if ($(this).hasClass("active")) {
			$(this).removeClass("active");
		} else {
			$(this).addClass("active");
		}
	});

	/**
	 * updates the attendance settings
	 * @param {object} dataObject
	 * @returns
	 */
	function updateCompanyAttendanceSettings(dataObject) {
		if (XHR !== null) {
			return;
		}
		const btnREF = callButtonHook($(".jsSubmitButton"), true);
		//
		XHR = $.ajax({
			url: baseUrl("attendance/settings"),
			method: "POST",
			data: dataObject,
		})
			.always(function () {
				callButtonHook(btnREF, false);
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.msg, window.location.refresh);
			});
	}
});
