$(function LMSEmployeeDashboard() {
	// set the xhr
	let XHR = null;
	// set the default filter
	let filterObj = {
		title: "",
		status: "all",
	};
	//
	let inprogressCourses = [];
	let pastDueCourses = [];
	let dueSoonCourses = [];
	let assignedCourses = [];
	//
	let timeOffDateFormatWithTime = "MMM DD YYYY, ddd";

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

	/**
	 * Apply filter
	 */
	$(".jsApplyFilterMyCourse").click(function (event) {
		// prevent default event
		event.preventDefault();
		//
		filterObj.title = $(".jsCourseTitleMyCourse").val() || "";
		filterObj.status = $(".jsCourseStatus").val();
		//
		getLMSAssignCourses();
	});

	/**
	 * Clear filter
	 */
	$(".jsClearFilterMyCourse").click(function (event) {
		// prevent default event
		event.preventDefault();
		//
		$(".jsCourseTitleMyCourse").val("");
		$('.jsCourseStatus option[value="all"]').prop("selected", true);
		//
		filterObj.title = $(".jsCourseTitleMyCourse").val() || "";
		filterObj.status = $(".jsCourseStatus").val();
		//
		getLMSAssignCourses();
	});

	/**
	 * get LMS default courses
	 */
	function getLMSAssignCourses() {
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
				"lms/trainings/" +
				employeeId +
				"?title=" +
				filterObj.title +
				"&status=" +
				filterObj.status,
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
				let coursesHTML = "";
				let count = response.data.count;
				let courses = response.data.courses;
				//
				$("#jsAssignedCount").html(count.assigned);
				$("#jsPendingCount").html(count.pending);
				$("#jsCompletedCount").html(count.completed);
				$("#jsExpiredSoonCount").html(count.expire_soon);
				//
				if (!response.data.assignedIds.length) {
					$(".jsFilterSectionBtn").hide();
					$(".jsSendReminderEmail").hide();
				}
				//
				if (courses.length) {
					courses.map(function (course) {
						if (course["employee_sid"] == null || course["employee_sid"] == employeeId) {
							if (response.data.assignedIds.includes(course["sid"])) {
								//
								if (response.data.inprogressIds.includes(course["sid"])) {
									if(inprogressCourses.length < 3) {
										inprogressCourses.push(course);
										setCourseBox(course, 'jsInprogressCourses');
									}
								} else if (response.data.expiredIds.includes(course["sid"])) {	
									if(pastDueCourses.length < 3) {
										pastDueCourses.push(course);
										setCourseBox(course, 'jsPastDueCourses');
									}
								} else if (response.data.expiredSoonIds.includes(course["sid"])) {
									if(dueSoonCourses.length < 3) {
										dueSoonCourses.push(course);
										setCourseBox(course, 'jsDueSoonCourses');
									}	
								} else {
									if(assignedCourses.length < 3) {
										assignedCourses.push(course);
										setCourseBox(course, 'jsAssignedCourses');
									}	
								}
							}	
						}	
					});
				} 
				//
				setCourseBox(inprogressCourses, 'jsInprogressCourses');
				setCourseBox(pastDueCourses, 'jsPastDueCourses');
				setCourseBox(dueSoonCourses, 'jsDueSoonCourses');
				setCourseBox(assignedCourses, 'jsAssignedCourses');
				//
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

	function setCourseBox (courses, ID) {
		if (courses.length) {
			courses.map(function (course) {
			
						//
						coursesHTML += `    <div class="col-sm-4">`;
						coursesHTML += `    <article class="article-sec">`;
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

						if (course.course_end_period === null) {
							coursesHTML += `--`;
						} else {
							coursesHTML += `            <p>${moment(
								course.course_end_period
							).format(timeOffDateFormatWithTime)}</p>`;
						}
						
						coursesHTML += `        </div>`;
						coursesHTML += `        <div class="col-md-3 col-xs-12">`;
						coursesHTML += `            <p class="csColumSection"><strong>STATUS</strong></p>`;
						coursesHTML += `            <p>${
							course.course_status == "passed"
								? "COMPLETED"
								: "PENDING"
						}</p>`;
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
					
						if (course.course_status == "passed") {
							coursesHTML += `            <a class="btn btn-info csRadius5 csF16" href="${baseURI + "lms/courses/" + course.sid}">
														<i class="fa fa-eye"></i>
														View Content
													</a>`;
													
							coursesHTML += `        <a class="btn btn-info csRadius5 csF16" href="${window.location.origin}/lms/courses/${course.sid}/${employeeId}/my/certificate">
														<i class="fa fa-eye"></i>
														View Certificate
													</a>`;
						} else {
							coursesHTML += `            <a class="btn btn-info csRadius5 csF16" href="${baseURI + "lms/courses/" + course.sid}">
														<i class="fa fa-play"></i>
														Launch Content
													</a>`;
						}	

						coursesHTML += `        </div>`;
						coursesHTML += `    </div>`;
						coursesHTML += `</article>`;
						coursesHTML += `</div>`;
						
			});
		} else {
			coursesHTML =
				'<p class="alert alert-info text-center">No Courses found.</p>';
		}	
		//
		$("#"+ID).html(coursesHTML);
	}
	//
	getLMSAssignCourses();
	
});
