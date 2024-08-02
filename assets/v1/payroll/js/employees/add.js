/**
 * Employee onboard to payroll
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function onboardEmployee() {
	/**
	 * set XHR request holder
	 */
	let current = 0;
	/**
	 * holds the employee ids
	 */
	let employeeIds = [];

	//
	$(document).on("click", ".jsPayrollAddEmployees", function (e) {
		//
		e.preventDefault();
		//
		Modal(
			{
				Id: "jsPayrollAddEmployeesModal",
				Loader: "jsPayrollAddEmployeesModalLoader",
				Title: "Employees List",
				Body: '<div id="jsPayrollAddEmployeesModalBody"></div>',
			},
			loadEmployees
		);
	});

	//
	$(document).on("click", ".jsPayrollOnboardEmployees", function (event) {
		//
		event.preventDefault();
		//
		employeeIds = [];
		//
		$(".jsEmployeesList:checked").map(function () {
			employeeIds.push($(this).val());
		});
		//
		if (employeeIds.length === 0) {
			return _error("Please select at least one employee.");
		}
		_ml(
			true,
			"jsPayrollAddEmployeesModalLoader",
			"Please wait, while we are processing your request."
		);
		//
		current = 0;
		//
		startOnboardingProcess();
	});

	function startOnboardingProcess() {
		//
		// check if all employees are onboard
		if (employeeIds[current] === undefined) {
			$("#jsPayrollAddEmployeesModal .jsModalCancel").trigger("click");
			return _success(
				"Hurray! You have successfully onboard the employees.",
				function () {
					window.location.reload();
				}
			);
		}
		//
		const employeeName = $(
			'.jsEmployeesList[value="' + employeeIds[current] + '"]'
		)
			.parent()
			.text()
			.trim();
		//
		_ml(
			true,
			"jsPayrollAddEmployeesModalLoader",
			'Please wait, while we are onboarding <strong>"' +
				employeeName +
				'"</strong>. <br/> This might take several minutes.'
		);
		//
		$.ajax({
			url: baseUrl("payrolls/onboard/employee/" + employeeIds[current]),
			method: "POST",
			data: {},
		})
			.success(function () {
				current++;
				startOnboardingProcess();
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
			});
	}

	//
	function loadEmployees() {
		//
		$.ajax({
			url: baseUrl("payrolls/" + companyId + "/employees/get"),
			method: "GET",
		})
			.success(function (resp) {
				$("#jsPayrollAddEmployeesModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				_ml(false, "jsPayrollAddEmployeesModalLoader");
			});
	}

	// cache off
	$.ajaxSetup({ cache: false });
	// hide the loader
	_ml(false, "pageLoader");
});
