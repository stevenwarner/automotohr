$('#schedule-free-demo-form-submit1').click(function () {
	$("#schedule-free-demo-form").validate({
		ignore: [],
		rules: {
			name: {
				required: true,
			},
			email: {
				required: true,
			},
			phone_number: {
				required: true,
			},
			company_name: {
				required: true,
			}
			,
			title: {
				required: true,
			}

		},
		messages: {
			name: {
				required: 'Please provide user name.',
			},
			email: {
				required: 'Please provide valid email.',
			},
			phone_number: {
				required: 'Please provide valid phone number',
			},
			company_name: {
				required: 'Please provide company name.',
			}
			,
			title: {
				required: 'Please provide title.',
			}

		},
		submitHandler: function (form) {
			//

			
						if ($('#g-recaptcha-response').val() == '') {
							alertify.alert('Captcha is required.');
							return;
						}
		

			var myurl = BASEURL + "demo/check_already_applied";
			$.ajax({
				type: "POST",
				url: myurl,
				data: {
					email: $('#email_id1').val()
				},
				dataType: "json",
				success: function (data) {
					var obj = jQuery.parseJSON(data);
					if (obj == 0) {
						$('#schedule-free-demo-form-submit1').buttonLoader('start');
						schedule_your_free_demo_ajax_form(1);
					} else {
						$('#schedule-free-demo-form-submit').buttonLoader('start');
						schedule_your_free_demo_ajax_form(1);

					}
				},
				error: function (data) {
					alertify.error('Sorry we will fix that issue');
				}
			});
		}
	});

});

//
$('#schedule-free-demo-form-submit2').click(function () {
	$("#schedule-free-demo-form2").validate({
		ignore: [],
		rules: {
			name: {
				required: true,
			},
			email: {
				required: true,
			},
			phone_number: {
				required: true,
			},
			company_name: {
				required: true,
			},
			title: {
				required: true,
			}
		},
		messages: {
			name: {
				required: 'Please provide user name.',
			},
			email: {
				required: 'Please provide valid email.',
			},
			phone_number: {
				required: 'Please provide valid phone number',
			},
			company_name: {
				required: 'Please provide company name.',
			},
			title: {
				required: 'Please provide title.',
			}

		},
		submitHandler: function (form) {
			//

			if ($('#g-recaptcha-response').val() == '') {
				alertify.alert('Captcha is required.');
				return;
			}

			var myurl = BASEURL + "demo/check_already_applied";
			$.ajax({
				type: "POST",
				url: myurl,
				data: {
					email: $('#email_id2').val()
				},
				dataType: "json",
				success: function (data) {
					var obj = jQuery.parseJSON(data);
					if (obj == 0) {
						$('#schedule-free-demo-form-submit2').buttonLoader('start');
						schedule_your_free_demo_ajax_form(2);
					} else {
						$('#schedule-free-demo-form-submit2').buttonLoader('start');
						schedule_your_free_demo_ajax_form(2);
					}
				},
				error: function (data) {
					alertify.error('Sorry we will fix that issue');
				}
			});
		}
	});

});



//
function schedule_your_free_demo_ajax_form(formId) {
	var myformurl = BASEURL + "demo/schedule_your_free_demo_ajax";

	$.ajax({
		type: "POST",
		url: myformurl,
		data: {
			email: $('#email_id' + formId).val(),
			name: $('#name' + formId).val(),
			phone_number: $('#phone_number' + formId).val(),
			company_name: $('#company_name' + formId).val(),
			title: $('#title' + formId).val(),
			response: $('#g-recaptcha-response').val()
		},
		dataType: "json",
		success: function (data) {

			if (data.error == true) {
				const errorArray = [];
				//
				if (data.name_error != '') {
					errorArray.push(data.name_error);
				}
				//
				if (data.email_error != '') {
					errorArray.push(data.email_error);
				}
				//
				if (data.phone_number_error != '') {
					errorArray.push(data.phone_number_error);
				}
				//
				if (data.company_name_error != '') {
					errorArray.push(data.company_name_error);
				}
				//
				if (data.title_error != '') {
					errorArray.push(data.title_error);
				}
				//
				if (data.company_size_error != '') {
					errorArray.push(data.company_size_error);
				}
				//

				if (data.g_recaptcha_response_error != '') {
					errorArray.push(data.g_recaptcha_response_error);
				}

				//
				if (errorArray.length) {
					$('#schedule-free-demo-form-submit' + formId).buttonLoader('stop');
					return alertify.alert(
						"ERROR!",
						getErrorsStringFromArray(errorArray),
						CB
					);
				}

			} else {
				$('#schedule-free-demo-form-submit' + formId).buttonLoader('stop');
				return alertify.success('Schedule Successfully Saved');

			}

		},
		error: function (data) {
			alertify.error('Sorry we will fix that issue');
		}
	});
	return;

}



if (typeof CB === "undefined") {
	/**
	 * Empty callback
	 */
	function CB() { }
}

if (typeof getErrorsStringFromArray === "undefined") {
	/**
	 * Error message
	 *
	 * @param {*} errorArray
	 * @param {*} errorMessage
	 * @returns
	 */
	function getErrorsStringFromArray(errorArray, errorMessage) {
		return (
			"<strong><p>" +
			(errorMessage ?
				errorMessage :
				"Please, resolve the following errors") +
			"</p></strong><br >" +
			errorArray.join("<br />")
		);
	}
}


//
(function ($) {
	$.fn.buttonLoader = function (action) {
		var self = $(this);
		//start loading animation
		if (action == 'start') {
			if ($(self).attr("disabled") == "disabled") {
				e.preventDefault();
			}
			//disable buttons when loading state
			$('.has-spinner').attr("disabled", "disabled");
			$(self).attr('data-btn-text', $(self).text());
			//binding spinner element to button and changing button text
			$(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>Please Wait');
			$(self).addClass('active');
		}
		//stop loading animation
		if (action == 'stop') {
			$(self).html($(self).attr('data-btn-text'));
			$(self).removeClass('active');
			//enable buttons after finish loading
			$('.has-spinner').removeAttr("disabled");
		}
	}
})(jQuery);