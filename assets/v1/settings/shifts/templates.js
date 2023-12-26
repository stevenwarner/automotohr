/**
 * Shift templates
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Attendance
 */
$(function shiftTemplates() {
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
	$(".jsAddShiftTemplateBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		//
		makePage("New Shift Template", "shift_template", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			applyTimePicker();
			//
			validatorRef = $("#jsPageShiftBreakForm").validate({
				rules: {
					start_time: { required: true, timeIn12Format: true },
					end_time: { required: true, timeIn12Format: true },
				},
				errorPlacement: function (error, element) {
					if ($(element).parent().hasClass("input-group")) {
						$(element).parent().after(error);
					} else {
						$(element).after(error);
					}
				},
				submitHandler: function (form) {
					return processCall(
						formArrayToObj($(form).serializeArray()),
						$(".jsPageShiftBreakBtn"),
						"settings/shifts/templates"
					);
				},
			});
		});
	});

	/**
	 * edit
	 */
	$(".jsEditShiftTemplateBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		// holds the reference
		const reference = $(this).closest(".jsBox");
		// holds the id event
		const id = reference.data("id");
		//
		makePage("Edit Shift Template", "shift_template", id, function () {
			// hides the loader
			ml(false, modalLoader);

			//
			if (usedBreaksObject.length) {
				//
				usedBreaksObject.map(function (singleBreak, i) {
					$(".jsBreakContainer").append(
						generateBreakHtml(i, singleBreak)
					);
				});
			}
			//
			applyTimePicker();
			//
			validatorRef = $("#jsPageShiftBreakForm").validate({
				rules: {
					start_time: { required: true, timeIn12Format: true },
					end_time: { required: true, timeIn12Format: true },
				},
				errorPlacement: function (error, element) {
					if ($(element).parent().hasClass("input-group")) {
						$(element).parent().after(error);
					} else {
						$(element).after(error);
					}
				},
				submitHandler: function (form) {
					const formObj = formArrayToObj($(form).serializeArray());
					formObj.append("id", id);
					return processCall(
						formObj,
						$(".jsPageShiftBreakBtn"),
						"settings/shifts/templates"
					);
				},
			});

			//
			if (usedBreaksObject.length) {
				//
				usedBreaksObject.map(function (singleBreak, i) {
					//
					$('[name="breaks[' + i + '][break]"]').rules("add", {
						required: true,
					});
					$('[name="breaks[' + i + '][duration]"]').rules("add", {
						required: true,
						number: true,
						digits: true,
						greaterThanZero: true,
					});
				});
			}
		});
	});

	/**
	 * add the break
	 */
	$(document).on("click", ".jsAddBreak", function (event) {
		event.preventDefault();
		//
		const uniqId = getRandomCode();
		// generate html
		$(".jsBreakContainer").append(generateBreakHtml(uniqId));
		//
		$('[name="breaks[' + uniqId + '][break]"]').rules("add", {
			required: true,
		});
		$('[name="breaks[' + uniqId + '][duration]"]').rules("add", {
			required: true,
			number: true,
			digits: true,
			greaterThanZero: true,
		});
		//
		applyTimePicker();
	});

	/**
	 * remove the break
	 */
	$(document).on("click", ".jsDeleteBreakRow", function (event) {
		event.preventDefault();
		//
		const uniqId = $(this).closest(".jsBreakRow").data("key");
		$('.jsBreakRow[data-key="' + uniqId + '"]').remove();
		$('[name="breaks[' + uniqId + '][break]"]').rules("remove");
		$('[name="breaks[' + uniqId + '][duration]"]').rules("remove");
	});

	/**
	 * on break select
	 */
	$(document).on("change", ".jsBreakSelect", function () {
		//
		const uniqId = $(this).closest(".jsBreakRow").data("key");
		//
		$('[name="breaks[' + uniqId + '][duration]"]').val(
			$(this).find("option:selected").data("duration")
		);
	});

	/**
	 * delete
	 */
	$(".jsDeleteShiftTemplateBtn").click(function (event) {
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
					window.location.href = baseUrl("settings/shifts/templates");
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
			reference.find(".jsDeleteShiftTemplateBtn"),
			true
		);
		// make a new call
		XHR = $.ajax({
			url: baseUrl("settings/shifts/template/" + id),
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
					window.location.href = baseUrl("settings/shifts/templates");
				});
			});
	}

	/**
	 * generates break h*ml
	 * @param {number} uniqId
	 * @param {object|undefined} data
	 * @returns
	 */
	function generateBreakHtml(uniqId, data) {
		//
		let breakOptions = "";
		breakOptions += "<option></option>";
		//
		breaksObject.map(function (v) {
			breakOptions +=
				'<option value="' +
				v.break_name +
				'" data-duration="' +
				v.break_duration +
				'" ' +
				(data !== undefined && data.break === v.break_name
					? "selected"
					: "") +
				">" +
				v.break_name +
				" (" +
				v.break_type +
				")</option>";
		});

		//
		let html = "";
		html += '<div class="row jsBreakRow" data-key="' + uniqId + '">';
		html += "    <br> ";
		html += '     <div class="col-sm-5">';
		html += '        <label class="text-medium">';
		html += "            Break ";
		html += '            <strong class="text-red">*</strong>';
		html += "         </label>";
		html +=
			'         <select name="breaks[' +
			uniqId +
			'][break]" class="form-control jsBreakSelect">';
		html += breakOptions;
		html += "         </select>";
		html += "     </div>";
		html += '     <div class="col-sm-3">';
		html += '         <label class="text-medium">';
		html += "             Duration ";
		html += '             <strong class="text-red">*</strong>';
		html += "         </label>";
		html += '         <div class="input-group">';
		html +=
			'             <input type="number" class="form-control jsDuration" name="breaks[' +
			uniqId +
			'][duration]" value="' +
			(data?.duration || "") +
			'" />';
		html += '             <div class="input-group-addon">mins</div>';
		html += "         </div>";
		html += "     </div>";
		html += '     <div class="col-sm-3">';
		html += '         <label class="text-medium">';
		html += "             Start TIme ";
		html += "         </label>";
		html +=
			'         <input type="text" class="form-control jsTimeField jsStartTime" placeholder="HH:MM" name="breaks[' +
			uniqId +
			'][start_time]"value="' +
			(data?.start_time
				? moment(data.start_time, "HH:mm").format("h:mm a")
				: "") +
			'" />';
		html += "     </div>";
		html += '     <div class="col-sm-1">';
		html += "         <br>";
		html +=
			'         <button class="btn btn-red jsDeleteBreakRow" title="Delete this break" type="button">';
		html +=
			'             <i class="fa fa-trash" style="margin-right: 0"></i>';
		html += "         </button>";
		html += "     </div>";
		html += "</div>";
		//
		return html;
	}

	/**
	 * apply time picker
	 */
	function applyTimePicker() {
		$(".jsTimeField").timepicker({
			timeFormat: "h:mm p",
			dynamic: false,
			dropdown: false,
			scrollbar: false,
		});
	}
});
