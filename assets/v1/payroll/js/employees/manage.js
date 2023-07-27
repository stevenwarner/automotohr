/**
 * Manage employee onboard
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function manageEmployees() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * holds the employee id
	 */
	let employeeId = 49256;
	/**
	 * holds the modal id
	 */
	let modalId = "jsEmployeeFlowModal";
	/**
	 * capture the view admin event
	 */
	$(".jsPayrollEmployeeEdit").click(function (event) {
		//
		event.preventDefault();
		//
		employeeId = $(this).closest("tr").data("id");
		//
		employeeOnboardFlow();
	});

	/**
	 * starts the employee onboard flow
	 * @returns
	 */
	function employeeOnboardFlow() {
		// check the employee
		if (employeeId === 0) {
			return alertify.alert("Error!", "Please select an employee.");
		}
		// generate modal
		Modal(
			{
				Title: "Employee Onboard Flow",
				Id: modalId,
				Loader: `${modalId}Loader`,
				Body: `<div id="${modalId}Body"></div>`,
			},
			function () {
				// setInterval(function () {
					//
					XHR = $.ajax({
						url: baseUrl("payrolls/flow/employee/" + employeeId),
						method: "GET",
					})
						.success(function (response) {
							$(`#${modalId}Body`).html(response.view);
						})
						.fail(handleErrorResponse)
						.always(function () {
							XHR = null;
                            _ml(false, `${modalId}Loader`);
						});
				// }, 2000);
			}
		);
	}

	employeeOnboardFlow();
});
