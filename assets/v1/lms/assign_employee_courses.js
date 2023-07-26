$(function LMSEmployeeCourses() {
	// set the xhr
	let XHR = null;
	// set the default filter
	let filterObj = {
		title: "",
	};
	//
    let timeoffDateFormatWithTime = 'MMM DD YYYY, ddd';
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
			url: apiURL + "lms/trainings/" + employeeId,
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
                var coursesHTML = '';
                var count = response.data.count;
                var courses = response.data.courses;
                //
				$("#jsAssignedCount").html(count.assigned);
                $("#jsPendingCount").html(count.pending);
                $("#jsCompletedCount").html(count.completed);
                $("#jsFailedCount").html(count.failed);
                //
                if (courses.length) {
                    courses.map(function (course) {
                        coursesHTML += `<article class="article-sec">`;

                        if (course.result_status === "PASSED") {
                            coursesHTML += `    <div class="row">`;
                            coursesHTML += `        <div class="col-md-12 col-xs-12 text-right">`;
                            coursesHTML += `        <span class="csF16 text-success"><strong><i class="fa fa-trophy" aria-hidden="true"></i> PASSED</strong></span>`;
                            coursesHTML += `        </div>`;
                            coursesHTML += `    </div>`;
                        } else if (course.result_status === "FAILED") {
                            coursesHTML += `    <div class="row">`;
                            coursesHTML += `        <div class="col-md-12 col-xs-12 text-right">`;
                            coursesHTML += `        <span class="csF16 text-danger"><strong><i class="fa fa-ban" aria-hidden="true"></i> FAILED</strong></span>`;
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
                        coursesHTML += `            <p>${moment(course.course_start_period).format(timeoffDateFormatWithTime)}</p>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `        <div class="col-md-3 col-xs-12">`;
                        coursesHTML += `            <p class="csColumSection"><strong>DUE DATE</strong></p>`;
                        coursesHTML += `            <p>${moment(course.course_end_period).format(timeoffDateFormatWithTime)}</p>`;
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
                        coursesHTML += `            <button class="btn btn-info csRadius5 csF16" onclick="">Launch Content</button>`;
                        coursesHTML += `        </div>`;
                        coursesHTML += `    </div>`;
                        coursesHTML += `</article>`;
                    })
                } else {

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