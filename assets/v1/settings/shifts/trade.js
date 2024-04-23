/**
 * Shift Trade
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Shift Trade
 */


/**
	 * holds the modal page id
	 */
const modalId = "jsModalPage";
const modalLoader = modalId + "Loader";
const modalBody = modalId + "Body";

let shiftsIds = [];


$(function shiftsTrade() {
	//
	let XHR = null;
	let validatorRef = null;


	let obj = {};
	//
	$(".jsDateRangePicker").daterangepicker({
		showDropdowns: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
			separator: " - ",
		},
	});

	//
	$('#check_all').click(function () {
		if ($('#check_all').is(":checked")) {
			$('.my_checkbox:checkbox').each(function () {
				this.checked = true;
			});
		} else {
			$('.my_checkbox:checkbox').each(function () {
				this.checked = false;
			});
		}
	});

	$(".jsTradeShifts").click(function (event) {
		// prevent the event from happening
		event.preventDefault();

		let shiftsArrayIds = [];
		var status = '';
		$(".my_checkbox:checked").each(function () {

			status = $(this).data('status');
			if (status == '' || status == 'rejected' || status == 'canceled' ) {
				shiftsArrayIds.push($(this).val());
			}

		});

		if (shiftsArrayIds.length > 0) {
			callToShiftsTrade(shiftsArrayIds);
		} else {
			alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
		}

	});


	$(".jsCancelTradeShifts").click(function (event) {
		// prevent the event from happening
		event.preventDefault();

		let shiftsArrayIds = [];
		var status = '';
		$(".my_checkbox:checked").each(function () {
			status = $(this).data('status');
			if (status == 'awaiting confirmation') {
				shiftsArrayIds.push($(this).val());
			}

		});

		if (shiftsArrayIds.length > 0) {

			alertify.confirm(
				'Are You Sure?',
				'Are you sure want to cancel shifts swap?',
				function () {
					//
					const formObj = new FormData();
					// set the file object
					formObj.append("shiftids", shiftsArrayIds);
					// 
					processCallWithoutContentType(
						formObj,
						'',
						"settings/shifts/tradeshiftscancel",
						function (resp) {
							// show the message
							_success(resp.msg, function () {
								window.location.reload();
							});
						}
					);
				},
				function () {

				}
			)

		} else {
			alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
		}

	});

	//
	$(".jsTradeShift").click(function (event) {
		// prevent the event from happening
		event.preventDefault();
		//
		let shiftsArrayIds = [];

		shiftsArrayIds.push($(this).data('shiftid'));
		callToShiftsTrade(shiftsArrayIds);
	});

	//
	$(".jsCancelTradeShift").click(function (event) {
		// prevent the event from happening
		event.preventDefault();

		let shiftsArrayIds = [];
		let shiftId = $(this).data('shiftid');

		alertify.confirm(
			'Are You Sure?',
			'Are you sure want to cancel shifts swap?',
			function () {
				//
				const formObj = new FormData();
				// set the file object
				shiftsArrayIds.push(shiftId);
				formObj.append("shiftids", shiftsArrayIds);
				// 
				processCallWithoutContentType(
					formObj,
					'',
					"settings/shifts/tradeshiftscancel",
					function (resp) {
						// show the message
						_success(resp.msg, function () {
							window.location.reload();
						});
					}
				);
			},
			function () {

			}
		)
	});

	//
	function callToShiftsTrade(shiftIds) {

		shiftsIds = shiftIds;
		makePage("Swap Shifts", "trade_shift", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			validatorRef = $("#jsPageTradeShiftForm").validate({
				rules: {
					employees: { required: true },
				},
				errorPlacement: function (error, element) {
					if ($(element).parent().hasClass("input-group")) {
						$(element).parent().after(error);
					} else {
						$(element).after(error);
					}
				},
				submitHandler: function (form) {
					$(form).serializeArray();
					//

					if ($('#employees :selected').val() == '0') {
						return _error("Please select an employee.");
					} else {
						employeeId = $('#employees :selected').val();
					}

					//
					alertify.confirm(
						'Are You Sure?',
						'Are you sure want to shifts swap?',
						function () {
							const formObj = new FormData();
							// set the file object
							formObj.append("employeeid", employeeId);
							formObj.append("shiftids", shiftIds);
							//
							processCallWithoutContentType(
								formObj,
								'',
								"settings/shifts/tradeshifts",
								function (resp) {
									// show the message
									_success(resp.msg, function () {
										window.location.reload();
									});
								}
							);

						},
						function () {

						}
					)

				},
			});
		});
	}

	//
	$(".jsConfirmTradeShifts").click(function (event) {
		// prevent the event from happening
		event.preventDefault();

		let shiftsArrayIds = [];
		var status = '';
		$(".my_checkbox:checked").each(function () {
			status = $(this).data('status');
			if (status == 'awaiting confirmation') {
				shiftsArrayIds.push($(this).val());
			}

		});

		if (shiftsArrayIds.length > 0) {

			alertify.confirm(
				'Are You Sure?',
				'Are you sure want to Confirm shifts?',
				function () {
					//
					const formObj = new FormData();
					// set the file object
					formObj.append("shiftids", shiftsArrayIds);
					formObj.append("shiftstatus", 'confirmed');
					// 
					processCallWithoutContentType(
						formObj,
						'',
						"settings/shifts/my_trade_change_status",
						function (resp) {
							// show the message
							_success(resp.msg, function () {
								window.location.reload();
							});
						}
					);
				},
				function () {

				}
			)

		} else {
			alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
		}

	});

	//
	$(".jsRejectTradeShifts").click(function (event) {
		// prevent the event from happening
		event.preventDefault();

		let shiftsArrayIds = [];
		var status = '';
		$(".my_checkbox:checked").each(function () {
			status = $(this).data('status');
			if (status == 'awaiting confirmation' || status == 'confirmed') {
				shiftsArrayIds.push($(this).val());
			}
		});

		if (shiftsArrayIds.length > 0) {
			alertify.confirm(
				'Are You Sure?',
				'Are you sure want to Reject shifts?',
				function () {
					//
					const formObj = new FormData();
					// set the file object
					formObj.append("shiftids", shiftsArrayIds);
					formObj.append("shiftstatus", 'rejected');
					// 
					processCallWithoutContentType(
						formObj,
						'',
						"settings/shifts/my_trade_change_status",
						function (resp) {
							// show the message
							_success(resp.msg, function () {
								window.location.reload();
							});
						}
					);
				},
				function () {
				}
			)

		} else {
			alertify.alert('Error! Not selected', 'Please Select at-least one shift ');
		}

	});

	//
	$(".jsRejectTradeShift").click(function (event) {
		// prevent the event from happening
		event.preventDefault();

		let shiftsArrayIds = [];
		shiftsArrayIds.push($(this).data('shiftid'));

		alertify.confirm(
			'Are You Sure?',
			'Are you sure want to Reject shifts?',
			function () {
				//
				const formObj = new FormData();
				// set the file object
				formObj.append("shiftids", shiftsArrayIds);
				formObj.append("shiftstatus", 'rejected');
				// 
				processCallWithoutContentType(
					formObj,
					'',
					"settings/shifts/my_trade_change_status",
					function (resp) {
						// show the message
						_success(resp.msg, function () {
							window.location.reload();
						});
					}
				);

			},
			function () {
			}
		)
	});

	//
	$(".jsConfirmTradeShift").click(function (event) {
		// prevent the event from happening
		event.preventDefault();

		let shiftsArrayIds = [];
		shiftsArrayIds.push($(this).data('shiftid'));

		alertify.confirm(
			'Are You Sure?',
			'Are you sure want to Confirm shifts?',
			function () {
				//
				const formObj = new FormData();
				// set the file object
				formObj.append("shiftids", shiftsArrayIds);
				formObj.append("shiftstatus", 'confirmed');
				// 
				processCallWithoutContentType(
					formObj,
					'',
					"settings/shifts/my_trade_change_status",
					function (resp) {
						// show the message
						_success(resp.msg, function () {
							window.location.reload();
						});
					}
				);
			},
			function () {
			}
		)
	});


	/**
	 * process the call
	 * @param {object} formObj
	 * @param {string} buttonRef
	 * @param {string} url
	 * @param {Object} cb
	 */
	function processCallWithoutContentType(formObj, buttonRef, url, cb) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			return;
		}
		let btnRef;
		//
		if (buttonRef) {
			btnRef = callButtonHook(buttonRef, true);
		}

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
				if (buttonRef) {
					callButtonHook(btnRef, false);
				}
				//
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				//
				validatorRef?.destroy();
				return cb(resp);
			});
	}

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

	     params = "params?shiftsIds=" + shiftsIds;
	
		if (shiftsIds != '') {
			callUrl = baseUrl("settings/page/" + pageSlug + "/" + pageId + "/" + params);
		} else {
			callUrl = baseUrl("settings/page/" + pageSlug + "/" + pageId);
		}

		// check if call is already made
		if (XHR !== null) {
			// abort the call
			XHR.abort();
		}
		// make a new call
		XHR = $.ajax({
			url: callUrl,
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

});