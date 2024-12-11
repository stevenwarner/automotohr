$(function LMSEmployeeCourses() {
	// set the xhr
	let XHR = null;

	// set the default filter
	let filterObj = {
		teams: "all",
		employees: "all",
		courses: "all",
	};
	//
	let timeOffDateFormatWithTime = "MMM DD YYYY, ddd";
	//
	// load select2 on department
	$("#jsSubordinateDepartments").select2({
		closeOnSelect: false,
	});
	//
	if (departments !== undefined) {
		if ($("#jsSubordinateDepartments").length) {
			$("#jsSubordinateDepartments").select2(
				"val",
				departments.split(",")
			);
		}
	}
	// load select2 on teams
	$("#jsSubordinateTeams").select2({
		closeOnSelect: false,
	});
	//
	if (teams !== undefined) {
		if ($("#jsSubordinateTeams").length) {
			$("#jsSubordinateTeams").select2("val", teams.split(","));
		}
	}
	// load select2 on employees
	$("#jsSubordinateEmployees").select2({
		closeOnSelect: false,
	});
	//
	if (employees !== undefined) {
		if ($("#jsSubordinateEmployees").length) {
			$("#jsSubordinateEmployees").select2("val", employees.split(","));
		}
	}
	// load select2 on courses
	$("#jsSubordinateCourses").select2({
		closeOnSelect: false,
	});
	//
	if (courses !== undefined) {
		if ($("#jsSubordinateCourses").length) {
			$("#jsSubordinateCourses").select2("val", courses.split(","));
		}
	}
	//
	/**
	 * Get Employee courses
	 */
	$(document).on("click", ".jsSearchEmployees", function (event) {
		// stop the default behavior
		event.preventDefault();
		var selectedDepartments = $("#jsSubordinateDepartments").val();
		var selectedTeams = $("#jsSubordinateTeams").val();
		var selectedEmployees = $("#jsSubordinateEmployees").val();
		var selectedCourses = $("#jsSubordinateCourses").val();
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
				departments: selectedDepartments,
				teams: selectedTeams,
				employees: selectedEmployees,
				courses: selectedCourses,
			},
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				//
				var html = "";
				//
				$.each(response.employees, function (index, employee) {
					var teamId = employee.team_sid;
					var departmentId = employee.department_sid;
					var assignCourses = employee.assign_courses
						? employee.assign_courses.split(",")
						: [];
					var courseCount = assignCourses ? assignCourses.length : 0;
					var courseCountText =
						courseCount > 1
							? courseCount + " courses assign"
							: courseCount + " course assign";
					var departmentName = "N/A";
					var teamName = "N/A";

					html += `<tr class="js-tr">`;
					html += `<td>`;
					html += `	<label class="control control--checkbox">`;
					html += `		<input type="checkbox" name="employees_ids[]" value="${employee["employee_sid"]}" />`;
					html += `		<div class="control__indicator"></div>`;
					html += `	</label>`;
					html += `</td>`;
					html += `<td class="_csVm">`;
					html += `	<div class="row">`;
					html += `		<div class="col-sm-3">`;
					html += `			<img style="width: 80px; height: 80px; border-radius: 50% !important;" src="${employee['profile_picture_url']}" alt="" />`;
					html += `		</div>`;
					html += `		<div class="col-sm-9">`;
					html += `			<p class="text-small weight-6 myb-0" style="font-size: 20px;">`;
					html += 				employee['only_name']
					html += `			</p>`;
					html += `			<p class="text-small">`;
					html += 				employee['designation']
					html += `			</p>`;
					html += `			<p class="text-small">`;
					html += 				employee['email']
					html += `			</p>`;
					html += `		</div>`;
					html += `	</div>`; 

					html += `</td>`;
					html += `<td class="_csVm">${employee.department_name}</td>`;
					html += `<td class="_csVm">${employee.team_name}</td>`;
					html += `<td class="_csVm">${
						employee.coursesInfo
							? employee.coursesInfo.total_course
							: 0
					}</td>`;
					html += `<td class="_csVm">${
						employee.coursesInfo
							? employee.coursesInfo.completed
							: 0
					}</td>`;
					html += `<td class="_csVm">${
						employee.coursesInfo
							? employee.coursesInfo.expire_soon
							: 0
					}</td>`;
					html += `<td class="_csVm">${
						employee.coursesInfo ? employee.coursesInfo.expired : 0
					}</td>`;
					html += `<td class="_csVm">${
						employee.coursesInfo
							? employee.coursesInfo.ready_to_start
							: 0
					}</td>`;
					html += `<td class="_csVm">${
						employee.coursesInfo ? employee.coursesInfo.started : 0
					}</td>`;
					html += `<td class="_csVm"><a href="${baseURL}lms/subordinate/courses/${employee["employee_sid"]}" class="btn btn-info btn-block csRadius5 csF16">View</a></td>`;
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
	$(document).on("click", ".jsCheckAll", selectAllInputs);
	$(document).on("click", ".jsSelectSubordinate", selectSingleInput);

	// Select all input: checkbox
	function selectAllInputs() {
		$(".jsSelectSubordinate").prop("checked", $(this).prop("checked"));
	}

	// Select single input: checkbox
	function selectSingleInput() {
		$(this)
			.find('input[name="employees_ids[]"]')
			.prop(
				"checked",
				!$(this).find('input[name="employees_ids[]"]').prop("checked")
			);
		$(".jsCheckAll").prop(
			"checked",
			$(".jsSelectSubordinate").length ==
				$(".jsSelectSubordinate:checked").length
		);
	}

	function get_all_selected_employees() {
		var tmp = [];
		$.each($('input[name="employees_ids[]"]:checked'), function () {
			var obj = {};
			obj.employee_sid = parseInt($(this).val());
			obj.employee_name = $(this)
				.closest("tr")
				.find("td.js-employee-name")
				.text();

			tmp.push(obj);
		});
		return tmp;
	}

	$(document).on("click", ".jsSendReminderEmail", function (e) {
		e.preventDefault();
		//
		senderList = get_all_selected_employees();
		//
		if (senderList.length === 0) {
			alertify.alert(
				"ERROR!",
				"Please select at least one employee to start the process."
			);
			return;
		}
		//
		alertify.confirm(
			"Do you really want to send email reminders to the selected employees?",
			function () {
				//
				sendEmailToEmployees(senderList);
			}
		);
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

	function loadMyAssignedCoursesBarChart() {
		Highcharts.chart("container1", {
			chart: {
				type: "column",
			},
			title: {
				align: "left",
				text: "Assigned Course(s) Bar Chart",
			},
			accessibility: {
				announceNewData: {
					enabled: true,
				},
			},
			xAxis: {
				type: "category",
				labels: {
					style: {
						fontSize: "12px", // Change this to your desired size
					},
				},
			},
			yAxis: {
				title: {
					text: "Total number of assigned course(s)",
				},
				labels: {
					style: {
						fontSize: "12px", // Change this to your desired size
					},
				},
			},
			legend: {
				enabled: false,
			},
			plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
						format: "{point.y}",
					},
				},
			},

			tooltip: {
				headerFormat:
					'<span style="font-size:14px">{series.name}</span><br>',
				pointFormat:
					'<span style="font-size:12px; color:{point.color}">{point.name}:</span> <b style="font-size:12px">{point.y} course(s)</b>',
			},

			series: [
				{
					name: "Course(s)",
					colorByPoint: true,
					data: [
						{
							name: "Assigned ",
							color: "#6B8ABB",
							y: totalCourses,
						},
						{
							name: "Pending",
							color: "#ff834e",
							y: totalCourses - completedCourses,
						},
						{
							name: "Ready To Start",
							color: "#2caffe",
							y: readyToStart,
						},
						{
							name: "In Progress",
							color: "#544fc5",
							y: inProgressCourses,
						},
						{
							name: "Passed",
							color: "#00e272",
							y: completedCourses,
						},
						{
							name: "Past Due",
							color: "#fa4b42",
							y: pastDueCourses,
						},
						{
							name: "Due Soon",
							color: "#feb56a",
							y: dueSoonCourses,
						},
					],
					dataLabels: {
						style: {
							fontSize: "12px", // Change this to your desired size
						},
					},
				},
			],
		});
	}

	ml(false, "jsPageLoader");
	loadMyAssignedCoursesBarChart();
});
