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
		$('#jsSubordinateDepartments').select2('val', departments.split(','));
	}
	// load select2 on teams
	$("#jsSubordinateTeams").select2({
		closeOnSelect: false,
	});
	//
	if (teams) {
		$('#jsSubordinateTeams').select2('val', teams.split(','));
	}
	// load select2 on employees
	$("#jsSubordinateEmployees").select2({
		closeOnSelect: false,
	});
	//
	if (employees) {
		$('#jsSubordinateEmployees').select2('val', employees.split(','));
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
						console.log(index+employee)
						var teamId = employee.team_sid;
						var departmentId = employee.department_sid;
						var assignCourses = employee.assign_courses ? employee.assign_courses.split(",") : [];
						var courseCount = assignCourses ? assignCourses.length : 0;
						var courseCountText = courseCount > 1 ? courseCount+" courses assign" : courseCount+" course assign";
						var departmentName =   "N/A";
						var teamName =  "N/A";
					
						html += `<tr>`;
						html += `<td class="_csVm"><b>${employee.full_name}</b></td>`;
						html += `<td class="_csVm">${employee.department_name}</td>`;
						html += `<td class="_csVm">${employee.team_name}</td>`;
						html += `<td class="_csVm">${courseCountText}</td>`;
						html += `<td class="_csVm"><a href="${baseURL}lms/subordinate/courses/${employee['employee_sid']}" class="btn btn-info btn-block csRadius5">View</a></td>`;
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
	/**
	 * Get Employee courses
	 */
	$(document).on("click", ".jsViewCourses", function (event) {
		// stop the default behavior
		event.preventDefault();
		var selectedEmployeeId = $(this).attr("data-employeeId");
		getLMSAssignCourses(selectedEmployeeId);
	});

	/**
	 * Back to report
	 */
	$(document).on("click", ".jsBackToReport", function (event) {
		// stop the default behavior
		event.preventDefault();
		//
		$("#jsAssignedCount").html(0);
		$("#jsPendingCount").html(0);
		$("#jsCompletedCount").html(0);
		$("#jsFailedCount").html(0);
		$("#jsEmployeeAssignedCourses").html("");
		//
		$("#jsEmployeeListing").show();
		$("#jsEmployeeCourses").hide();
	});

	/**
	 * Apply box filter
	 */
	$(".jsFilterBox").click(function (event) {
		// prevent default event
		event.preventDefault();
		//
		$(".jsCourseTitleMyCourse").val("");
		$('.jsCourseStatus option[value="' + $(this).data("key") + '"]').prop(
			"selected",
			true
		);
		//
		filterObj.title = $(".jsCourseTitleMyCourse").val() || "";
		filterObj.status = $(".jsCourseStatus").val();
		//
		getLMSAssignCourses();
	});

	function getLMSAssignCourses(selectedEmployeeId) {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url:
				apiURL +
				"lms/report/" +
				selectedEmployeeId +
				"/courses/?title=&status=all",
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
				let coursesHTML = "";
				let count = response.data.count;
				let courses = response.data.courses;
				let completedCourses = response.data.completedIds;
				//
				$("#jsAssignedCount").html(count.assigned);
				$("#jsPendingCount").html(count.pending);
				$("#jsCompletedCount").html(count.completed);
				$("#jsFailedCount").html(count.failed);
				//
				if (courses.length) {
					courses.map(function (course) {
						coursesHTML += `<article class="article-sec">`;

						if (course.course_status == "passed") {
							coursesHTML += `    <div class="row">`;
							coursesHTML += `        <div class="col-md-12 col-xs-12 text-right">`;
							coursesHTML += `        	<a href="${window.location.origin}/lms/courses/${course.sid}/certificate" class="btn btn-success btn-xs csRadius5 csF14" title="View Certificate" placement="top">
															<i class="fa fa-certificate csF14" aria-hidden="true"></i>
															&nbsp;Certificate
														</a>`;
							coursesHTML += `        </div>`;
							coursesHTML += `    </div>`;
						}

						coursesHTML += `    <h1>`;
						coursesHTML += course.course_title;
						coursesHTML += `    </h1>`;
						coursesHTML += `    <br>`;
						coursesHTML += `    <div class="row">`;
						coursesHTML += `        <div class="col-md-3 col-xs-12">`;
						coursesHTML += `            <p class="csColumSection"><strong>ASSIGNED DATE</strong></p>`;
						coursesHTML += `            <p>${moment(
							course.course_start_period
						).format(timeOffDateFormatWithTime)}</p>`;
						coursesHTML += `        </div>`;
						coursesHTML += `        <div class="col-md-3 col-xs-12">`;
						coursesHTML += `            <p class="csColumSection"><strong>DUE DATE</strong></p>`;
						coursesHTML += `            <p>${moment(
							course.course_end_period
						).format(timeOffDateFormatWithTime)}</p>`;
						coursesHTML += `        </div>`;
						coursesHTML += `        <div class="col-md-3 col-xs-12">`;
						coursesHTML += `            <p class="csColumSection"><strong>STATUS</strong></p>`;
						coursesHTML += `            <p>${
							course.course_status == "passed"
								? "COMPLETED"
								: "PENDING"
						}</p>`;
						coursesHTML += `        </div>`;
						coursesHTML += `        <div class="col-md-3 col-xs-12">`;
						coursesHTML += `            <p class="csColumSection"><strong>ASSIGNED TO</strong></p>`;
						coursesHTML += `            <p>${response.data.employeeName}</p>`;
						coursesHTML += `        </div>`;
						coursesHTML += `    </div>`;
						coursesHTML += `    <div class="row">`;
						coursesHTML += `        <div class="col-md-3 col-xs-12 hidden">`;
						coursesHTML += `            <p class="csColumSection"><strong>TIME REMAINING/TOTAL</strong></p>`;
						coursesHTML += `            <p>15 min / 15 min</p>`;
						coursesHTML += `        </div>`;
						coursesHTML += `        <div class="col-md-3 col-xs-12">`;
						coursesHTML += `            <p class="csColumSection"><strong>STARTED DATE </strong></p>`;
						coursesHTML += `            <p>${
							course.created_at
								? moment(course.created_at).format(
										timeOffDateFormatWithTime
								  )
								: "-"
						}</p>`;
						coursesHTML += `        </div>`;
						coursesHTML += `        <div class="col-md-3 col-xs-12">`;
						coursesHTML += `            <p class="csColumSection"><strong>LANGUAGE</strong></p>`;
						coursesHTML += `            <select class="form-control">`;
						coursesHTML += `                <option value="eng">English</option>`;
						coursesHTML += `            </select>`;
						coursesHTML += `        </div>`;
						coursesHTML += `        <div class="col-md-6 col-xs-12 text-right">`;
						coursesHTML += `            <p>&nbsp;</p>`;
						coursesHTML += `            <a class="btn btn-info csRadius5 csF16" href="${
							baseURI + "lms/employee/course/" + course.sid
						}" >${
							course.course_status == "passed"
								? "Retake Course"
								: "Launch Content"
						}</a>`;
						coursesHTML += `        </div>`;
						coursesHTML += `    </div>`;
						coursesHTML += `</article>`;
					});
				} else {
					coursesHTML =
						'<p class="alert alert-info text-center">No trainings found.</p>';
				}
				//
				$("#jsEmployeeAssignedCourses").html(coursesHTML);
				//
				$("#jsEmployeeListing").hide();
				$("#jsEmployeeCourses").show();
				// hide the loader
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.always(function () {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
			});
	}

	/**
	 * get LMS Specific course
	 */
	function getLMSAssignCourse() {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + "lms/trainings/" + employeeId + "/" + courseId,
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
				$("#jsPreviewCourse").html(response);
				//
				if (courseType === "manual") {
					getCourseQuestions();
				}
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
	}

	function getEmployeesReport () {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url:
				apiURL +
				"lms/report/" +
				uniqueKey +
				"?teams=" +
				filterObj.teams +
				"&employees=" +
				filterObj.employees +
                "&courses=" +
				filterObj.courses,
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				//
				if (response.status === "course_started") {
					$("#jsStartCourseDiv").hide();
					$(".jsSaveQuestionResult").show();
					getLMSAssignCourse();
				}
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
	}
	//

	ml(false, "jsPageLoader");
	// getEmployeesReport();
});
