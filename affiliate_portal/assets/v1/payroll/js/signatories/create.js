/**
 * Create signatory On Gusto
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function createSignatory() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;

	/**
	 * set the default admin object
	 */
	let dataOBJ = {
		first_name: "",
		middle_initial: "",
		last_name: "",
		ssn: "",
		email: "",
		title: "",
		phone: "",
		birthday: "",
		street1: "",
		street2: "",
		city: "",
		state: "",
		zip: "",
	};

	/**
	 * capture the view admin event
	 */
	$("#jsCreateForm").submit(function (event) {
		//
		event.preventDefault();
		//
		$(".jsErrorDiv").html("").addClass("hidden");
		//
		dataOBJ.first_name = $(".jsCreateFirstName").val().trim();
		dataOBJ.middle_initial = $(".jsCreateMiddleInitial").val().trim();
		dataOBJ.last_name = $(".jsCreateLastName").val().trim();
		dataOBJ.ssn = $(".jsCreateSocialSecurityNumber").val().trim();
		dataOBJ.email = $(".jsCreateEmail").val().trim();
		dataOBJ.title = $(".jsCreateTitle").val().trim();
		dataOBJ.phone = $(".jsCreatePhone").val().trim();
		dataOBJ.birthday = $(".jsCreateBirthday").val().trim();
		dataOBJ.street1 = $(".jsCreateStreet1").val().trim();
		dataOBJ.street2 = $(".jsCreateStreet2").val().trim();
		dataOBJ.city = $(".jsCreateCity").val().trim();
		dataOBJ.state = $(".jsCreateState").val().trim();
		dataOBJ.zip = $(".jsCreateZip").val().trim();
		//
		const errorArray = [];
		// validation
		if (!dataOBJ.first_name) {
			errorArray.push('"First Name" is required.');
		}
		if (!dataOBJ.last_name) {
			errorArray.push('"Last Name" is required.');
		}
		if (!dataOBJ.ssn) {
			errorArray.push('"Social Security Number" is required.');
		}
		if (!dataOBJ.email) {
			errorArray.push('"Email Address" is required.');
		}
		if (!dataOBJ.email.verifyEmail()) {
			errorArray.push('"Email Address" is invalid.');
		}
		if (!dataOBJ.title) {
			errorArray.push('"Title" is required.');
		}
		if (!dataOBJ.birthday) {
			errorArray.push('"Birthday" is required.');
		}
		if (!dataOBJ.street1) {
			errorArray.push('"Street 1" is required.');
		}
		if (!dataOBJ.city) {
			errorArray.push('"City" is required.');
		}
		if (!dataOBJ.state) {
			errorArray.push('"State" is required.');
		}
		if (!dataOBJ.zip) {
			errorArray.push('"Zip" is required.');
		}
		//
		if (errorArray.length) {
			window.scrollTo(0, 0);
			return $(".jsErrorDiv")
				.html(getErrorsStringFromArray(errorArray))
				.removeClass("hidden");
		}
		//
		createSignatoryProcess();
	});

	/**
	 * calls the save handler
	 * @returns
	 */
	function createSignatoryProcess() {
		//
		if (XHR !== null) {
			return false;
		}
		//
		$(".jsSubmitBTN span").text(
			"Please wait, while we are saving signatory..."
		);
		//
		XHR = $.ajax({
			url: window.location.origin + "/payrolls/signatories/create",
			method: "POST",
			data: dataOBJ,
		})
			.success(function () {
				//
				return alertify.alert(
					"Success!",
					"You have successfully created a signatory.",
					function () {
						window.location.href =
							window.location.origin + "/payrolls/signatories";
					}
				);
			})
			.fail(function (response) {
				window.scrollTo(0, 0);
				return $(".jsErrorDiv")
					.html(
						getErrorsStringFromArray(
							(
								response.responseJSON ||
								JSON.parse(response.responseText)
							).errors
						)
					)
					.removeClass("hidden");
			})
			.always(function () {
				// hide the loader
				ml(false, "jsCreateLoader");
				XHR = null;
				$(".jsSubmitBTN span").text("Save Signatory");
			});
	}
	// datepicker
	$("#jsBirthday").datepicker({
		changeYear: true,
		changeMonth: true,
		format: "mm/dd/yyyy",
	});
	// hides the loader
	ml(false, "jsCreateLoader");
});
