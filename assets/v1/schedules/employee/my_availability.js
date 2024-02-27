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
				$('[name="shift_date"]').val(
					moment(date, "YYYY-MM-DD").format("MM/DD/YYYY")
				);

				applyTimePicker();

				//
				validatorRef = $("#jsPageCreateSingleShiftForm").validate({
					rules: {
						shift_employee: { required: true },
						shift_date: { required: true },
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
						return processCallWithoutContentType(
							formArrayToObj($(form).serializeArray()),
							$(".jsPageCreateSingleShiftBtn"),
							"settings/shifts/single/create",
							function (resp) {
								_success(resp.msg, function () {
									window.location.reload();
								});
							}
						);
					},
				});
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
