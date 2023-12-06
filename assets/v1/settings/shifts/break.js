/**
 * Shift breaks
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Attendance
 */
$(function shiftBreaks() {
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
	$(".jsAddBreakBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		//
		makePage("New Break", "shifts", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			const validatorRef = $("#jsPageShiftBreakForm").validate({
				rules: {
					break_name: { required: true },
					break_duration: { required: true, number: true },
					break_type: { required: true },
				},
				submitHandler: function (form) {
					//
					return processCall(
						formArrayToObj($(form).serializeArray()),
						$(".jsPageShiftBreakBtn"),
						"settings/shifts/breaks"
					);
				},
			});
		});
	});

	/**
	 * edit
	 */
	$(".jsEditShiftBreakBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		// holds the reference
		const reference = $(this).closest(".jsBox");
		// holds the id event
		const id = reference.data("id");
		//
		makePage("Edit Break", "shifts", id, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			validatorRef = $("#jsPageShiftBreakForm").validate({
				rules: {
					rule_name: { required: true },
					overtime_multiplier: { required: true },
					double_overtime_multiplier: { required: true },
				},
				submitHandler: function (form) {
					// convert form to form object
					const formObj = formArrayToObj($(form).serializeArray());
					// add id
					formObj.append("id", id);
					//
					return processCall(
						formObj,
						$(".jsPageShiftBreakBtn"),
						"settings/shifts/breaks"
					);
				},
			});
		});
	});

	/**
	 * delete
	 */
	$(".jsDeleteShiftBreakBtn").click(function (event) {
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
			url: baseUrl(url),
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
					window.location.href = baseUrl("settings/shifts/breaks");
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
			reference.find(".jsDeleteShiftBreakBtn"),
			true
		);
		// make a new call
		XHR = $.ajax({
			url: baseUrl("settings/shifts/breaks/" + id),
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
					window.location.href = baseUrl("settings/shifts/breaks");
				});
			});
	}
});
