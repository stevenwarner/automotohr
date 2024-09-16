$(function LMSEmployeeCourses() {
	// set the xhr
	let XHR = null;
    //
	let inprogressCourses = [];
	let pastDueCourses = [];
	let dueSoonCourses = [];
	let assignedCourses = [];
	let readyToStartCourses = [];
	let passedCourses = [];
    //
    let timeOffDateFormatWithTime = "MMM DD YYYY, ddd";
	//
	let filterObj = {
		title: "",
		status: "all",
	};
    //
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
			url: apiURL + "lms/report/" + subordinateId + "/" + courseId,
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
				//
				if (courses.length) {
					courses.map(function (course) {
						if (course["employee_sid"] == null || course["employee_sid"] == employeeId) {
							if (response.data.assignedIds.includes(course["sid"])) {
								//
								if (response.data.inprogressIds.includes(course["sid"])) {
									if(inprogressCourses.length < 3) {
										inprogressCourses.push(course);
									}
								} else if (response.data.expiredIds.includes(course["sid"])) {	
									if(pastDueCourses.length < 3) {
										pastDueCourses.push(course);
									}
								} else if (response.data.expiredSoonIds.includes(course["sid"])) {
									if(dueSoonCourses.length < 3) {
										dueSoonCourses.push(course);
									}	
								} else if (response.data.readyToStartIds.includes(course["sid"])) {
									if(readyToStartCourses.length < 3) {
										readyToStartCourses.push(course);
									}
								} else if (response.data.passedIds.includes(course["sid"])) {
									if(passedCourses.length < 3) {
										passedCourses.push(course);
									}	
								} else {
									if(assignedCourses.length < 3) {
										assignedCourses.push(course);
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
				setCourseBox(readyToStartCourses, 'jsAssignedCourses');
				setCourseBox(passedCourses, 'jsPassedCourses');
				//
				if (count.assigned) {
					//
					loadMyAssignedCoursesPaiChart(count);
					//
					loadMyAssignedCoursesBarChart(count);
					//
					loadMyAssignedCoursesPassingChart(count);
				}
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

    function setCourseBox (courses, ID) {
		//
		let coursesHTML = '';
		//
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
				coursesHTML += `        <div class="col-md-12 col-xs-12">`;
				coursesHTML += `            <p>${course.course_content.substr(0,50)}</p>`;
				coursesHTML += `        </div>`;
				coursesHTML += `    </div>`;
				coursesHTML += `    <div class="row">`;
				coursesHTML += `        <div class="col-md-6 col-xs-12">`;
				coursesHTML += `            <p class="csColumSection"><strong>ASSIGNED DATE</strong></p>`;
				coursesHTML += `            <p>${moment(
					course.course_start_period
				).format(timeOffDateFormatWithTime)}</p>`;
				coursesHTML += `        </div>`;
				coursesHTML += `        <div class="col-md-6 col-xs-12">`;
				coursesHTML += `            <p class="csColumSection"><strong>DUE DATE</strong></p>`;

				if (course.course_end_period === null) {
					coursesHTML += `--`;
				} else {
					coursesHTML += `            <p>${moment(
						course.course_end_period
					).format(timeOffDateFormatWithTime)}</p>`;
				}
				
				coursesHTML += `        </div>`;
				coursesHTML += `    </div>`;
				coursesHTML += `    <div class="row">`;
			
				coursesHTML += `        <div class="col-md-12 col-xs-12 text-center">`;
				coursesHTML += `            <p>&nbsp;</p>`;
			
				if (course.course_status == "passed") {
					coursesHTML += `            <a class="btn btn-info csRadius5 csF16 btn-block" href="${baseURI + "lms/courses/" + course.sid}">
												<i class="fa fa-eye"></i>
												View Content
											</a>`;
											
					coursesHTML += `        <a class="btn btn-info csRadius5 csF16 btn-block" href="${window.location.origin}/lms/courses/${course.sid}/${employeeId}/my/certificate">
												<i class="fa fa-eye"></i>
												View Certificate
											</a>`;
				} else {
					coursesHTML += `            <a class="btn btn-info csRadius5 csF16 btn-block" href="${baseURI + "lms/courses/" + course.sid}">
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
            //
            var message = "Apologies, but no assigned course was found.";
            //
            if (ID == 'jsInprogressCourses') {
                message = "Apologies, but there are currently no courses in progress.";
            } else if (ID == 'jsPastDueCourses') {
                message = "Apologies, but no past due courses were found.";
            } else if (ID == 'jsDueSoonCourses') {
                message = "Apologies, but no courses with upcoming expiration dates were found.";
            }

            coursesHTML += '<div class="col-sm-12">';
			coursesHTML += ' <p class="alert alert-info text-center">';
            coursesHTML += message;
            coursesHTML += ' </p>';
            coursesHTML += '</div>';
		}	
		//
		$("#"+ID).html(coursesHTML);
		
		
	}
	//
	//
	function loadMyAssignedCoursesPaiChart(
        count
    ) {
		Highcharts.chart('container', {
			chart: {
				type: 'pie'
			},
			title: {
				text: 'Assigned Course(s)'
			},
			tooltip: {
				valueSuffix: ''
			},
			plotOptions: {
				series: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: [{
						enabled: true,
						distance: 20
					}, {
						enabled: true,
						distance: -40,
						format: '{point.percentage:.1f}%',
						style: {
							fontSize: '1.2em',
							textOutline: 'none',
							opacity: 0.7
						},
						filter: {
							operator: '>',
							property: 'percentage',
							value: 10
						}
					}]
				}
			},
			series: [
				{
					name: 'Course(s)',
					colorByPoint: true,
					point: {
						events: {
							click: function(e) {
								window.open(e.point.url, '_blank');
								e.preventDefault();
							}
						}
					},
					data: [
						{
							name: 'Due Soon',
							y: count.expire_soon,
							color: '#feb56a',
							url: baseURI + "lms/courses/my?type=due_soon"
						},
						{
							name: 'Past Due',
							sliced: true,
							selected: true,
							y: count.expired,
							color: '#fa4b42',
							url: baseURI + "lms/courses/my?type=past_due"
						},
						{
							name: 'In Progress',
							y: count.inprogress,
							color: '#544fc5',
							url: baseURI + "lms/courses/my?type=inprogress"
						},
						{
							name: 'Ready To Start',
							y: count.readyToStart,
							color: '#2caffe',
							url: baseURI + "lms/courses/my?type=assigned"
						},
						{
							name: 'Passed',
							y: count.passed,
							color: '#00e272',
							url: baseURI + "lms/courses/my?type=assigned"
						}
					]
				}
			]
		});
    }
	//
	function loadMyAssignedCoursesBarChart (count) {
		Highcharts.chart('container1', {
			chart: {
				type: 'column'
			},
			title: {
				align: 'left',
				text: 'Assigned Course(s) Bar Chart'
			},
			accessibility: {
				announceNewData: {
					enabled: true
				}
			},
			xAxis: {
				type: 'category'
			},
			yAxis: {
				title: {
					text: 'Total number of assigned course(s)'
				}
		
			},
			legend: {
				enabled: false
			},
			plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
						format: '{point.y}'
					}
				}
			},
		
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
					'<b>{point.y}</b> course(s)<br/>'
			},
		
			series: [
				{
					name: 'Course(s)',
					colorByPoint: true,
					point: {
						events: {
							click: function(e) {
								window.open(e.point.url, '_blank');
								e.preventDefault();
							}
						}
					},
					data: [
						{
							name: 'Assigned ',
							color: '#2caffe',
							y: count.assigned,
							url: baseURI + "lms/courses/my?type=assigned"
						},
						{
							name: 'Pending',
							color: '#ff834e',
							y: count.pending,
							url: baseURI + "lms/courses/my?type=pending"
						},
						{
							name: 'In Progress',
							color: '#544fc5',
							y: count.inprogress,
							url: baseURI + "lms/courses/my?type=inprogress"
						},
						{
							name: 'Passed',
							color: '#00e272',
							y: count.passed,
							url: baseURI + "lms/courses/my?type=inprogress"
						},
						{
							name: 'Past Due',
							color: '#fa4b42',
							y: count.expired,
							url: baseURI + "lms/courses/my?type=past_due"
						},
						{
							name: 'Due Soon',
							color: '#feb56a',
							y: count.expire_soon,
							url: baseURI + "lms/courses/my?type=due_soon"
						}
					]
				}
			]
		});
	}
	//
	function loadMyAssignedCoursesPassingChart (count) {
		Highcharts.chart('container2', {

			chart: {
				type: 'gauge',
				plotBackgroundColor: null,
				plotBackgroundImage: null,
				plotBorderWidth: 0,
				plotShadow: false,
				height: '80%'
			},
		
			title: {
				text: 'Course(s) Passing Rate'
			},
		
			pane: {
				startAngle: -90,
				endAngle: 89.9,
				background: null,
				center: ['50%', '75%'],
				size: '110%'
			},
		
			// the value axis
			yAxis: {
				min: 0,
				max: count.assigned,
				tickPixelInterval:100,
				tickPosition: 'inside',
				tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
				tickLength: 20,
				tickWidth: 2,
				minorTickInterval: null,
				labels: {
					distance: 20,
					style: {
						fontSize: '14px'
					}
				},
				lineWidth: 0,
				plotBands: [{
					from: count.assigned,
					to: (count.assigned/2),
					color: '#55BF3B', // green
					thickness: 20,
					borderRadius: '50%'
				}, {
					from: (count.assigned/3),
					to: 0,
					color: '#DF5353', // red
					thickness: 20,
					borderRadius: '50%'
				}, {
					from: count.assigned/1.9,
					to: count.assigned/3.1,
					color: '#DDDF0D', // yellow
					thickness: 20
				}]
			},
		
			series: [{
				name: 'Speed',
				data: [count.passed],
				tooltip: {
					valueSuffix: ' km/h'
				},
				dataLabels: {
					format: '{y} Passed',
					borderWidth: 0,
					color: (
						Highcharts.defaultOptions.title &&
						Highcharts.defaultOptions.title.style &&
						Highcharts.defaultOptions.title.style.color
					) || '#333333',
					style: {
						fontSize: '16px'
					}
				},
				dial: {
					radius: '80%',
					backgroundColor: 'gray',
					baseWidth: 12,
					baseLength: '0%',
					rearLength: '0%'
				},
				pivot: {
					backgroundColor: 'gray',
					radius: 6
				}
		
			}]
		
		});
		
	}
    
});
