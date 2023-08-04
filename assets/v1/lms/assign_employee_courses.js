$(function LMSEmployeeCourses() {
	// set the xhr
	let XHR = null;
	// set the default filter
	let filterObj = {
		title: "",
        status: "all"
	};
	//
    let timeOffDateFormatWithTime = 'MMM DD YYYY, ddd';

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
			url: apiURL + "lms/trainings/" + employeeId + "?title=" + filterObj.title + "&status=" + filterObj.status,
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
                var coursesHTML = '';
                var count = response.data.count;
                var courses = response.data.courses;
                var completedCourses = response.data.completedIds;
                //
				$("#jsAssignedCount").html(count.assigned);
                $("#jsPendingCount").html(count.pending);
                $("#jsCompletedCount").html(count.completed);
                $("#jsFailedCount").html(count.failed);
                //
                if (courses.length) {
                    courses.map(function (course) {
                        coursesHTML += `<article class="article-sec">`;

                        if (completedCourses.includes(course.sid)) {
                            coursesHTML += `    <div class="row">`;
                            coursesHTML += `        <div class="col-md-12 col-xs-12 text-right">`;
                            coursesHTML += `        <span class="csF16 text-success"><strong><i class="fa fa-trophy" aria-hidden="true"></i> PASSED</strong></span>`;
                            coursesHTML += `        </div>`;
                            coursesHTML += `    </div>`;
                        } 
                
                        coursesHTML += `    <h1>`;
                        coursesHTML +=          course.course_title;
                        coursesHTML += `    </h1>`;
                        coursesHTML += `    <br>`;
                        coursesHTML += `    <div class="row">`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12">`;
                        coursesHTML += `            <p class="csColumSection"><strong>ASSIGNED DATE</strong></p>`;
                        coursesHTML += `            <p>${moment(course.course_start_period).format(timeOffDateFormatWithTime)}</p>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12">`;
                        coursesHTML += `            <p class="csColumSection"><strong>DUE DATE</strong></p>`;
                        coursesHTML += `            <p>${moment(course.course_end_period).format(timeOffDateFormatWithTime)}</p>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12">`;
                        coursesHTML += `            <p class="csColumSection"><strong>STATUS</strong></p>`;
                        coursesHTML += `            <p>${course.course_status}</p>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12">`;
                        coursesHTML += `            <p class="csColumSection"><strong>ASSIGNED TO</strong></p>`;
                        coursesHTML += `            <p>puma pu</p>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `    </div>`;
                        coursesHTML += `    <div class="row">`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12">`;
                        coursesHTML += `            <p class="csColumSection"><strong>TIME REMAINING/TOTAL</strong></p>`;
                        coursesHTML += `            <p>15 min / 15 min</p>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12">`;
                        coursesHTML += `            <p class="csColumSection"><strong>STARTED DATE </strong></p>`;
                        coursesHTML += `            <p>5/2/2023</p>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12">`;
                        coursesHTML += `            <p class="csColumSection"><strong>LANGUAGE</strong></p>`;
                        coursesHTML += `            <select name="" id="" class="form-control">`;
                        coursesHTML += `                <option value="eng">English</option>`;
                        coursesHTML += `            </select>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12 text-right">`;
                        coursesHTML += `            <p>&nbsp;</p>`;
                        coursesHTML += `            <a class="btn btn-info csRadius5 csF16" href="${baseURI+"lms/courses/"+course.sid}" >${completedCourses.includes(course.sid) ? "Retake Course" : "Launch Content"}</a>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `    </div>`;
                        coursesHTML += `</article>`;
                    })
                } else {
                    coursesHTML = '<p class="alert alert-info text-center">No Courses Found</p>';
                }   
                //
                $("#jsMyAssignedCourses").html(coursesHTML);  
				// hide the loader
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
	getLMSAssignCourses();
});