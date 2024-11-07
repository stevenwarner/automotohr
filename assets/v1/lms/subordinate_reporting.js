$(function LMSEmployeeCourses() {
	// set the xhr
	let XHR = null;

	// set the default filter
	let filterObj = {
		teams: "all",
		employees: "all",
        courses: "all"
	};
	//
	let timeOffDateFormatWithTime = "MMM DD YYYY, ddd";
	//
	// load select2 on department
	$("#jsSubordinateDepartments").select2({
		closeOnSelect: false,
	});
	//
	if (departments) {
		if ($('#jsSubordinateDepartments').length) {
			$('#jsSubordinateDepartments').select2('val', departments.split(','));
		}	
		
	}
	// load select2 on teams
	$("#jsSubordinateTeams").select2({
		closeOnSelect: false,
	});
	//
	if (teams) {
		if ($('#jsSubordinateTeams').length) {
			$('#jsSubordinateTeams').select2('val', teams.split(','));
		}
	}
	// load select2 on employees
	$("#jsSubordinateEmployees").select2({
		closeOnSelect: false,
	});
	//
	if (employees) {
		if ($('#jsSubordinateEmployees').length) {
			$('#jsSubordinateEmployees').select2('val', employees.split(','));
		}		
	}
	//
	/**
	 * Get Employee courses
	 */
	$(document).on("click", ".jsSearchEmployees", function (event) {
		// stop the default behavior
		event.preventDefault();
		var SelectedDepartments = $("#jsSubordinateDepartments").val();
		var SelectedTeams = $("#jsSubordinateTeams").val();
		var SelectedEmployees = $("#jsSubordinateEmployees").val();
		//
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: baseURL + "lms/courses/report",
			method: "GET",
			data: {
				departments: SelectedDepartments,
				teams: SelectedTeams,
				employees: SelectedEmployees
			}
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				//
				var html = '';
				//
				$.each(response, function (index,employee) {
					var teamId = employee.team_sid;
					var departmentId = employee.department_sid;
					var assignCourses = employee.assign_courses ? employee.assign_courses.split(",") : [];
					var courseCount = assignCourses ? assignCourses.length : 0;
					var courseCountText = courseCount > 1 ? courseCount+" courses assign" : courseCount+" course assign";
					var departmentName =   "N/A";
					var teamName =  "N/A";

					html += `<tr class="js-tr">`;
					html += `<td>`;
					html += `	<label class="control control--checkbox">`;
					html += `		<input type="checkbox" name="employees_ids[]" value="${employee['employee_sid']}" />`;
					html += `		<div class="control__indicator"></div>`;
					html += `	</label>`;
					html += `</td>`;
					html += `<td class="_csVm"><b>${employee.full_name}</b></td>`;
					html += `<td class="_csVm">${employee.department_name}</td>`;
					html += `<td class="_csVm">${employee.team_name}</td>`;
					html += `<td class="_csVm">${courseCountText}</td>`;
					html += `<td class="_csVm">${employee.in_progress}</td>`;
					html += `<td class="_csVm">${employee.ready_to_start}</td>`;
					html += `<td class="_csVm">${employee.past_due}</td>`;
					html += `<td class="_csVm">${employee.expire_soon}</td>`;
					html += `<td class="_csVm">${employee.passed}</td>`;
					html += `<td class="_csVm"><a href="${baseURL}lms/subordinate/courses/${employee['employee_sid']}" class="btn btn-info btn-block csRadius5 csF16">View</a></td>`;
					html += `</tr>`;
				});
				//
				$("#jsSubordinateList").html(html);
				//
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.always(function () {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
			});
	});
	//
	$(document).on('click', '.jsCheckAll', selectAllInputs);
    $(document).on('click', '.jsSelectSubordinate', selectSingleInput);

	// Select all input: checkbox
	function selectAllInputs() {
		$(".jsSelectSubordinate")
			.prop("checked", $(this).prop("checked"));
	}

	// Select single input: checkbox
	function selectSingleInput() {
		$(this).find('input[name="employees_ids[]"]').prop('checked', !$(this).find('input[name="employees_ids[]"]').prop('checked'));
		$(".jsCheckAll")
			.prop(
				"checked",
				$(".jsSelectSubordinate").length == $(".jsSelectSubordinate:checked").length
			);
	}

	function get_all_selected_employees() {
		var tmp = [];
		$.each($('input[name="employees_ids[]"]:checked'), function() {
			var obj = {};
			obj.employee_sid = parseInt($(this).val());
			obj.employee_name = $(this).closest('tr').find('td.js-employee-name').text();

			tmp.push(obj);
		});
		return tmp;
	}

	$(document).on('click', '.jsSendReminderEmail', function(e) {
		e.preventDefault();
		//
		senderList = get_all_selected_employees();
		//
		if (senderList.length === 0) {
			alertify.alert('ERROR!', 'Please select at least one employee to start the process.');
			return;
		}
		//
		alertify.confirm('Do you really want to send email reminders to the selected employees?', function(){
			//
			sendEmailToEmployees(senderList);
		});
	});

	function sendEmailToEmployees(senderList) {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		//
		XHR = $.ajax({
			url: baseURI + "lms/courses/emailReminder/bulk",
			method: "POST",
			data: {
				employeeList: senderList,
			},
		})
		.success(function (response) {
			// empty the call
			XHR = null;
			//
			ml(false, "jsPageLoader");
			//
			return alertify.alert(
				"SUCCESS!",
				"You have successfully sent an email reminder to selected employees."
			);
		})
		.fail(handleErrorResponse)
		.done(function (response) {
			// empty the call
			XHR = null;
		});
	}


	ml(false, "jsPageLoader");
	// getEmployeesReport();
});
