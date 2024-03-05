$(function myAvailability() {
	/**
	 * holds the XHR
	 */
	let XHR = null;

    	/**
	 * holds the modal page id
	 */
	const modalId = "jsModalPage";
	const modalLoader = modalId + "Loader";
	const modalBody = modalId + "Body";
    
	/**
	 * capture the mark attendance event
	 */
	$(document).on("click", ".jsEmployeeAvailability", function (event) {
		// prevent the event from happening
		event.preventDefault();
		//
		callToCreateBox(
			$(this).data("eid")
		);
	});

	$(document).on("click", ".jsUnavailableAllDay", function (event) {
		if($(".jsUnavailableAllDay").prop('checked') == false){
			$(".jsHoursRowCustom").remove();
			$("#jsAddUnavailableTime").removeClass("hidden");
		} else {
			$("#jsAddUnavailableTime").addClass("hidden");
		}
	});	

	$(document).on("click", ".jsRepeat", function (event) {
		if($(".jsRepeat").prop('checked') == true){
			$("#jsSelectedDate").html($(".jsUnavailableDate").val());
			$(".jsRepeatSection").removeClass("hidden");
		} else {
			$(".jsRepeatSection").addClass("hidden");
		}
	});	
	
	$(document).on("change", "#jsRepeatType", function (event) {
		$(".jsRepeatType").addClass("hidden");
		$(".jsWeeklyMonthlySection").addClass("hidden");
		$(".jsWeeklyMonthlySectionSeparator").addClass("hidden");
		//
		var type = $(this).val();
		//
		if (type == 1) {
			$(".jsDailyRepeat").removeClass("hidden");
		} else if (type == 2) {
			$(".jsWeeklyRepeat").removeClass("hidden");
			$(".jsWeeklySection").removeClass("hidden");
			$(".jsWeeklyMonthlySectionSeparator").removeClass("hidden");
		} else if (type == 3) {
			$(".jsMonthlyRepeat").removeClass("hidden");
			$(".jsMonthlySection").removeClass("hidden");
			$(".jsWeeklyMonthlySectionSeparator").removeClass("hidden");
		}
		//
	});

	/**
	 * add the hours
	 */
	$(document).on("click", ".jsAddHours", function (event) {
		event.preventDefault();
		//
		const uniqId = getRandomCode();
		// generate html
		$(".jsHoursContainer").append(generateBreakHtml(uniqId));
		//
		applyTimePicker();
	});	
	
	/**
	* remove the break
	*/
   	$(document).on("click", ".jsDeleteHourRow", function (event) {
		event.preventDefault();
		//
		const uniqId = $(this).closest(".jsHoursRow").data("key");
		$('.jsHoursRow[data-key="' + uniqId + '"]').remove();
   	});

	/**
	* remove the break
	*/
	$(document).on("click", ".jsPageCreateUnavailabilityBtn", function (event) {
		event.preventDefault();
		//
		alert("Please save unavilibility");
   	});

	/**
	 * Create my unavailability
	 * @param {int} employeeId
	 */
	function callToCreateBox(employeeId) {
		makePage(
			"Add unavailability",
			"add_unavailability_shift",
			function (resp) {
				// hides the loader
				ml(false, modalLoader);
				//
				applyTimePicker();
				applyDatePicker();

				//
				// validatorRef = $("#jsPageCreateSingleShiftForm").validate({
				// 	rules: {
				// 		shift_employee: { required: true },
				// 		shift_date: { required: true },
				// 		start_time: { required: true, timeIn12Format: true },
				// 		end_time: { required: true, timeIn12Format: true },
				// 	},
				// 	errorPlacement: function (error, element) {
				// 		if ($(element).parent().hasClass("input-group")) {
				// 			$(element).parent().after(error);
				// 		} else {
				// 			$(element).after(error);
				// 		}
				// 	},
				// 	submitHandler: function (form) {
				// 		return processCallWithoutContentType(
				// 			formArrayToObj($(form).serializeArray()),
				// 			$(".jsPageCreateSingleShiftBtn"),
				// 			"settings/shifts/single/create",
				// 			function (resp) {
				// 				_success(resp.msg, function () {
				// 					window.location.reload();
				// 				});
				// 			}
				// 		);
				// 	},
				// });
			}
		);
	}

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
			url: baseUrl("settings/page/" + pageSlug),
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
	 * generates break h*ml
	 * @param {number} uniqId
	 * @returns
	 */
	function generateBreakHtml(uniqId) {
		//
		let html = "";
		html += '<div class="row jsHoursRow jsHoursRowCustom" data-key="' + uniqId + '">';
		html += '	<div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
        html += '		<label class="text-medium">From<strong class="text-red">*</strong></label>';
        html += '	</div>';
		html += '	<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">';
        html += '		<div class="form-group">';
        html += '			<input type="text" class="form-control jsTimeField valid" name="start_time" placeholder="HH:MM" aria-invalid="false">';
        html += '		</div>';
        html += '	</div>';
        html += '	<div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
        html += '		<label class="text-medium">To<strong class="text-red">*</strong></label>';
        html += '	</div>';
        html += '	<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">';
        html += '		<div class="form-group">';
        html += '			<input type="text" class="form-control jsTimeField" name="end_time" placeholder="HH:MM">';
        html += '		</div>';
        html += '	</div>';
        html += '	<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
    	html += '		<button class="btn btn-orange jsAddHours">';
        html += '			<i class="fa fa-plus-circle" aria-hidden="true"></i>';
        html += '			Add Hours';
        html += '		</button>';
		html += '		<button class="btn btn-red jsDeleteHourRow" title="Delete this break" type="button">';
		html += '             <i class="fa fa-trash" style="margin-right: 0"></i>';
		html += '		</button>';
        html += '	</div>'; 
		html += "</div>";
		//
		return html;
	}

    /**
	 * apply time picker
	 */
	function applyDatePicker() {
		$(".jsUnavailableDate").daterangepicker({
			showDropdowns: true,
			singleDatePicker: true,
			autoApply: true,
			locale: {
				format: "MM/DD/YYYY",
			},
		});
	}

	function applyTimePicker() {
		$(".jsTimeField").timepicker({
			timeFormat: "h:mm p",
			dynamic: false,
			dropdown: false,
			scrollbar: false,
		});
	}
});
