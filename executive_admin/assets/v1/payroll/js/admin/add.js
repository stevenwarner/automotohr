/**
 * Add admin On Gusto
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function addAdmin() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;

	/**
	 * set the default admin object
	 */
	let dataOBJ = {
		first_name: "",
		last_name: "",
		email_address: "",
	};

	/**
	 * capture the view admin event
	 */
	$("#jsAdminForm").submit(function (event) {
		//
		event.preventDefault();
		//
		$(".jsErrorDiv").html("").addClass("hidden");
		//
		dataOBJ.first_name = $(".jsAdminFirstName").val().trim();
		dataOBJ.last_name = $(".jsAdminLastName").val().trim();
		dataOBJ.email_address = $(".jsAdminEmailAddress").val().trim();
		//
		const errorArray = [];
		// validation
		if (!dataOBJ.first_name) {
			errorArray.push('"First Name" is required.');
		}
		if (!dataOBJ.last_name) {
			errorArray.push('"Last Name" is required.');
		}
		if (!dataOBJ.email_address) {
			errorArray.push('"Email Address" is required.');
		}
		if (!dataOBJ.email_address.verifyEmail()) {
			errorArray.push('"Email Address" is invalid.');
		}
		//
		if (errorArray.length) {
			return $(".jsErrorDiv")
				.html(getErrorsStringFromArray(errorArray))
				.removeClass("hidden");
		}
		//
		addAdmin();
	});

	/**
	 * calls the save handler
	 * @returns
	 */
	function addAdmin() {
		//
		if (XHR !== null) {
			return false;
		}
		//
		$(".jsSubmitBTN span").text(
			"Please wait, while we are saving admin..."
		);
		//
		XHR = $.ajax({
			url: window.location.origin + "/payrolls/admin/create",
			method: "POST",
			data: dataOBJ,
		})
			.success(function () {
				//
				return alertify.alert(
					"Success!",
					"You have successfully created an admin.",
					function () {
						window.location.href =
							window.location.origin + "/payrolls/admins";
					}
				);
			})
			.fail(function (response) {
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
				ml(false, "jsAddAdminLoader");
				XHR = null;
				$(".jsSubmitBTN span").text("Save Admin");
			});
	}
	// hides the loader
	ml(false, "jsAddAdminLoader");
});
