$(function LMSEmployeeCourses() {
	// set the xhr
	let XHR = null;
	// set the default filter
	let filterObj = {
		title: "",
		status: "all",
	};
	//
	if (search.length) {
		filterObj.status = search;
	}
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

	$(document).on("click", ".jsStartCourse", function (event) {
		event.preventDefault();
		//
        var courseId = $(this).data("course_id");
		var language = $('.jsCourseLanguage'+courseId).val();
        var url = baseURI + "lms/courses/" + courseId + '/' + language;
		var previousLanguage = $(this).data("previous_language");
		//
        if (previousLanguage.length && previousLanguage != language) {
			alertify
				.confirm(
					"Are you sure you want to change course language?",
					function () {
						changeScormLanguage(language, courseId)
					},
					CB
				)
				.setHeader("Confirm");
			
		} else {
			//
			$(this).attr('href', url);
			//
			window.location = $(this).attr('href').toString();
		}
	});

	$(document).on("change", ".jsSelectCourseLanguage", function (event) {
		event.preventDefault();
		//
        var courseId = $(this).data("course_id");
		var language = $(this).val();
        var url = baseURI + "lms/courses/" + courseId + '/' + language;
		//
        $('.jsStartCourse'+courseId).attr('href', url);
	});

	function changeScormLanguage (language, courseId) {
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
				"/" +
				courseId +
				"/" +
				language ,
			method: "PUT",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				//
				if (response.status === "language_changed") {
					window.location = baseURI + "lms/courses/" + courseId + '/' + language;
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
				var htmlCheck = []
				if (courses.length) {
					courses.map(function (course) {
						if (course["employee_sid"] == null || course["employee_sid"] == employeeId) {
							if (response.data.assignedIds.includes(course["sid"])) {
								if (!htmlCheck.includes(course["sid"])) {
									htmlCheck.push(course["sid"]);
									if (response.data.expiredSoonIds.includes(course["sid"])) {
										coursesHTML += `<article class="article-sec" style="background: #fcf8e3 !important">`;
									} else if (response.data.expiredIds.includes(course["sid"])) {
										coursesHTML += `<article class="article-sec" style="background: #f2dede !important">`;
									} else {
										coursesHTML += `<article class="article-sec">`;
									}

									if (response.data.expiredSoonIds.includes(course["sid"])) {
										const end = moment(course["course_end_period"],"YYYY-MM-DD");
										const now = moment();
										const end_diff = end.diff(now, 'days');
										//
										var expiredSoonText = '';
										//
										if (end_diff == 0) {
											expiredSoonText = `Expiring today`;
										} else if (end_diff == 1) {
											expiredSoonText = `Expiring in ${end_diff} day`;
										} else {
											expiredSoonText = `Expiring in ${end_diff} days`;
										}
										//
										//
										coursesHTML += `    <div class="row">`;
										coursesHTML += `        <div class="col-md-12 col-xs-12 text-right">`;
										coursesHTML += `        	<a href="javascript:;" class="btn btn-warning btn-xs csRadius5 csF14" title="This course will expire in ${end_diff} days" placement="top">
																		<i class="fa fa-info-circle csF14" aria-hidden="true"></i>
																		&nbsp;${expiredSoonText}
																	</a>`;
										coursesHTML += `        </div>`;
										coursesHTML += `    </div>`;
									}

									if (response.data.expiredIds.includes(course["sid"])) {
										const end = moment(course["course_end_period"],"YYYY-MM-DD");
										const now = moment();
										const end_diff = Math.abs(end.diff(now, 'days'));
										//
										var expiredText = '';
										//
										if (end_diff == 0) {
											expiredText = `Expired today`;
										} else if (end_diff == 1) {
											expiredText = `Expired ${end_diff} day ago`;
										} else {
											expiredText = `Expired ${end_diff} days ago`;
										}
										//
										coursesHTML += `    <div class="row">`;
										coursesHTML += `        <div class="col-md-12 col-xs-12 text-right">`;
										coursesHTML += `        	<a href="javascript:;" class="btn btn-danger btn-xs csRadius5 csF14" title="This course was expired ${end_diff} days ago." placement="top">
																		<i class="fa fa-info-circle csF14" aria-hidden="true"></i>
																		&nbsp;${expiredText}
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
									coursesHTML += `            <select class="form-control jsSelectCourseLanguage jsCourseLanguage${course.sid}" data-course_id="${course.sid}">`;
									//
									var defaultLanguage = '';
									var previousLanguage = '';
									if (course['course_type'] == 'scorm') {
										defaultLanguage = course['course_languages'][0];
									} else {
										defaultLanguage = 'english';
									}
									//
									if (course['course_type'] == 'scorm') {
										course['course_languages'].map(function (language) {
											if (course['selected_language'] && course['selected_language'] == language) {
												defaultLanguage = course['selected_language'];
												previousLanguage = course['selected_language'];
												coursesHTML += `            <option value="${language}" selected="selected">${language.charAt(0).toUpperCase() + language.slice(1)}</option>`;
											} else {
												coursesHTML += `            <option value="${language}">${language.charAt(0).toUpperCase() + language.slice(1)}</option>`;
											}
										});
									} else {
										coursesHTML += `                <option value="english">English</option>`;
									}
									//
									coursesHTML += `            </select>`;
									coursesHTML += `        </div>`;
									coursesHTML += `        <div class="col-md-6 col-xs-12 text-right">`;
									coursesHTML += `            <p>&nbsp;</p>`;
								
									if (course.course_status == "passed") {
										coursesHTML += `            <a class="btn btn-info csRadius5 csF16 jsStartCourse jsStartCourse${course.sid}" data-previous_language="${previousLanguage}" data-course_id="${course.sid}" href="${baseURI + "lms/courses/" + course.sid + "/" + defaultLanguage}">
																	<i class="fa fa-eye"></i>
																	View Content
																</a>`;
																
										coursesHTML += `        <a class="btn btn-info csRadius5 csF16" href="${window.location.origin}/lms/courses/${course.sid}/${employeeId}/my/certificate">
																	<i class="fa fa-eye"></i>
																	View Certificate
																</a>`;
									} else {
										coursesHTML += `            <a class="btn btn-info csRadius5 csF16 jsStartCourse jsStartCourse${course.sid}" data-previous_language="${previousLanguage}" data-course_id="${course.sid}" href="${baseURI + "lms/courses/" + course.sid + "/" + defaultLanguage}">
																	<i class="fa fa-play"></i>
																	Launch Content
																</a>`;
									}	

									coursesHTML += `        </div>`;
									coursesHTML += `    </div>`;
									coursesHTML += `</article>`;
								}	
							}
						}	
					});
				} else {
					coursesHTML =
						'<p class="alert alert-info text-center">No Courses found.</p>';
				}
				//
				$("#jsMyAssignedCourses").html(coursesHTML);
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
	//
	//
	getLMSAssignCourses();
	
});
