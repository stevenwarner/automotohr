$(function LMSEmployeeCourses() {
	// set the xhr
	let XHR = null;
    //
    let timeOffDateFormatWithTime = "MMM DD YYYY, ddd";
	//
	let filterObj = {
		title: "",
		status: "all",
	};
	//
	if (search.length) {
		filterObj.status = search;
	}
	//
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
		getLMSAssignCourses(subordinateId);
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
		getLMSAssignCourses(subordinateId);
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
		getLMSAssignCourses(subordinateId);
	});

	// set the default filter
    if (page === "subordinate_course") {
        if (courseType === "scorm") {
            function sendCourseToSave(CMIElements) {
            }
            //
            window.sendCourseToSave = sendCourseToSave;
        }
    }    

	$(document).on("click", ".jsStartCourse", function (event) {
		event.preventDefault();
		//
        var courseId = $(this).data("course_id");
		var language = $('.jsCourseLanguage'+courseId).val();
        var url = baseURI + "lms/subordinate/course/" + courseId + '/' + subordinateId + '/' + reviewAs + '/' + language;
		//
        $(this).attr('href', url);
		//
		window.location = $(this).attr('href').toString();
	});

	$(document).on("change", ".jsSelectCourseLanguage", function (event) {
		event.preventDefault();
		//
        var courseId = $(this).data("course_id");
		var language = $(this).val();
        var url = baseURI + "lms/subordinate/course/" + courseId + '/' + subordinateId + '/' + reviewAs + '/' + language;
		//
        $('.jsStartCourse'+courseId).attr('href', url);
	});

	$(document).on("change", ".jsChangeScormLanguage", function (event) {
		event.preventDefault();
		//
        var courseId = $(this).data("course_id");
		var language = $(this).val();
		var url = baseURI + "lms/subordinate/course/" + courseId + '/' + subordinateId + '/' + reviewAs + '/' + language;
		//
		alertify
			.confirm(
				"Are you sure you want to change course language?",
				function () {
					window.location = url;
				},
				CB
			)
			.setHeader("Confirm");
		
	});

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
		var courseUrl = apiURL + "lms/report/" + subordinateId + "/" + courseId;
		//
		console.log(courseLanguage)
		if (courseType == "scorm") {
			courseUrl += "/" + courseLanguage
		}
		XHR = $.ajax({
			url: courseUrl,
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

	/**
	 * get LMS course questions
	 */
	function getCourseQuestions() {
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
				subordinateId +
				"/" +
				courseId +
				"/questions/attempt",
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
				$("#jsPreviewCourseQuestion").html(response);
				//
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function () {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
			});
	}

    //
    function getLMSAssignCourses(subordinateId) {
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
				subordinateId +
				"/courses" +
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
										coursesHTML += `        	<a href="javascript:;" class="btn btn-warning btn-xs csRadius5 csF14" title="This course will expired in ${end_diff} days" placement="top">
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
									if (course['course_type'] == 'scorm') {
										course['course_languages'].map(function (language) {
											coursesHTML += `            <option value="${language}">${language.charAt(0).toUpperCase() + language.slice(1)}</option>`;
										});
									} else {
										coursesHTML += `                <option value="english">English</option>`;
									}
									//
									coursesHTML += `            </select>`;
									coursesHTML += `        </div>`;
									coursesHTML += `        <div class="col-md-6 col-xs-12 text-right">`;
									coursesHTML += `            <p>&nbsp;</p>`;

									var defaultLanguage = '';
									if (course['course_type'] == 'scorm') {
										defaultLanguage = course['course_languages'][0];
									} else {
										defaultLanguage = 'english';
									}
								
									if (course.course_status == "passed") {
										coursesHTML += `            <a class="btn btn-info csRadius5 csF16 jsStartCourse jsStartCourse${course.sid}" data-course_id="${course.sid}" href="${baseURI}lms/subordinate/course/${course.sid}/${subordinateId}/${reviewAs}/${defaultLanguage}">
																	<i class="fa fa-eye"></i>
																	View Content
																</a>`;
																
										coursesHTML += `        <a class="btn btn-info csRadius5 csF16" href="${baseURI}lms/courses/${course.sid}/${subordinateId}/subordinate/certificate}">
																	<i class="fa fa-eye"></i>
																	View Certificate
																</a>`;
									} else {
										coursesHTML += `            <a class="btn btn-info csRadius5 csF16 jsStartCourse jsStartCourse${course.sid}" data-course_id="${course.sid}" href="${baseURI}lms/subordinate/course/${course.sid}/${subordinateId}/${reviewAs}/${defaultLanguage}">
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
						'<p class="alert alert-info text-center">No course(s) found.</p>';
				}
				//
				$("#jsMyAssignedCourses").html(coursesHTML);
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
    //
    if (page === "subordinate_courses") {
        getLMSAssignCourses(subordinateId);
    } else {
        $(".jsSaveQuestionResult").hide();
        getLMSAssignCourse();
    }


	function getEmployeeInfo() {
		var tmp = [];
		var obj = {};
		//
		obj.employee_sid = subordinateId;
		obj.employee_name = subordinateName;
		//
		tmp.push(obj);
		return tmp;
	}

	$(document).on('click', '.jsSendReminderEmail', function(e) {
		e.preventDefault();
		//
		//
		alertify.confirm('Do you really want to send email reminder to <b>'+subordinateName+'</b>?', function(){
			//
			alertify.prompt('Please Enter a Note', '', '', function(evt, value) {
				//
			}, function() {
				alertify.error('Cancel')
			}).setContent('<textarea style="resize: none;" rows="5" cols="50"> </textarea>').set('onok', function(closeEvent) {
				employeeInfo = getEmployeeInfo();
				sendEmailToEmployees(employeeInfo, this.elements.content.querySelector('textarea').value);
			});
		})
		//
	});

	function sendEmailToEmployees(employeeInfo, employeeNote) {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		//
		XHR = $.ajax({
			url: baseURI + "lms/courses/emailReminder/single",
			method: "POST",
			data: {
				employeeList: employeeInfo,
				note: employeeNote
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
					"You have successfully sent an email reminder to <b>"+subordinateName+"</b>."
				);
			})
			.fail(handleErrorResponse)
			.done(function (response) {
				// empty the call
				XHR = null;
			});
	}
    
});
