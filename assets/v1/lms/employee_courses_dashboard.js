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
	let readyToStartCourses = [];
	let passedCourses = [];

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
			.success( function (response) {
				// empty the call
				XHR = null;
				// set the view
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
									// if(inprogressCourses.length < 3) {
										inprogressCourses.push(course);
									// }
								} else if (response.data.expiredIds.includes(course["sid"])) {	
									// if(pastDueCourses.length < 3) {
										pastDueCourses.push(course);
									// }
								} else if (response.data.expiredSoonIds.includes(course["sid"])) {
									// if(dueSoonCourses.length < 3) {
										dueSoonCourses.push(course);
									// }	
								} else if (response.data.readyToStartIds.includes(course["sid"])) {
									// if(readyToStartCourses.length < 3) {
										readyToStartCourses.push(course);
									// }
								} else if (response.data.passedIds.includes(course["sid"])) {
									// if(passedCourses.length < 3) {
										passedCourses.push(course);
									// }	
								} else {
									// if(assignedCourses.length < 3) {
										assignedCourses.push(course);
									// }	
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
					loadOverView(count);
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

	function setCourseBox (courses, ID) {
		//
		let coursesHTML = '';
		//
		if (courses.length) {
			courses.map(function (course) {
				//
				coursesHTML += `    <div class="rows">`;
				coursesHTML += `    <div class="col-sm-4">`;
				coursesHTML += `    <article class="article-sec">`;
				if (course.course_banner) {
					coursesHTML += `    <div class="row">
											<div class="col-md-12 col-xs-12">
												<img src="https://automotohrattachments.s3.amazonaws.com/${course.course_banner}" style="height: 214px !important;" />
											</div>
										</div>
												`;
				} else {
					coursesHTML += `    <div class="row">
											<div class="col-md-12 col-xs-12">
												<img src="https://automotohrattachments.s3.amazonaws.com/default_course_banner-Uk2W5O.jpg" style="height: 214px !important;" />
											</div>
										</div>
												`;
				}
				coursesHTML += `    <h1 style="height: 58px;">`;
				coursesHTML += course.course_title;
				coursesHTML += `    </h1>`;
				coursesHTML += `    <div class="row">`;
				coursesHTML += `        <div class="col-md-12 col-xs-12">`;
				coursesHTML += `            <p>${course.course_content.substr(0,50)}&nbsp;</p>`;
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
				coursesHTML += `    </div>`;

				coursesHTML += `    <div class="row">`;
				coursesHTML += `        <div class="col-md-12 col-xs-12 text-center">`;
				coursesHTML += `            <p>&nbsp;</p>`;

				if (course.course_status == "passed") {
					coursesHTML += `        <button class="btn btn-info csRadius5 csF16 jsStartCourse jsStartCourse${course.sid}" data-previous_language="${previousLanguage}" data-course_id="${course.sid}" href="${baseURI + "lms/courses/" + course.sid + "/" + defaultLanguage}">
												<i class="fa fa-eye"></i>
												View Content
											</button>`;
											
					coursesHTML += `        <a class="btn btn-info csRadius5 csF16" href="${window.location.origin}/lms/courses/${course.sid}/${employeeId}/my/certificate">
												<i class="fa fa-eye"></i>
												View Certificate
											</a>`;
				} else {
					coursesHTML += `        <button class="btn btn-info csRadius5 csF16 jsStartCourse jsStartCourse${course.sid}" data-previous_language="${previousLanguage}" data-course_id="${course.sid}" href="${baseURI + "lms/courses/" + course.sid + "/" + defaultLanguage}">
												<i class="fa fa-play"></i>
												Launch Content
											</button>`;
				}	

				coursesHTML += `        </div>`;
				coursesHTML += `    </div>`;
				coursesHTML += `</article>`;
				coursesHTML += `</div>`;
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
	function loadOverView (count) {
		var percentage = Math.round(((count.assigned - count.pending) / count.assigned) * 100);
		$("#jsOverViewTrainings").html(percentage+"%");
		$("#jsOverViewCourseDueSoon").html(count.pending);
		$("#jsOverViewCourseTotal").html(count.assigned);
	}
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
				pie: {
					dataLabels: {
						enabled: true,
						style: {
							fontSize: '12px' // Increase font size for all data labels here
						}
					}
				},
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
							url: baseURI + "lms/courses/my?type=ready_to_start"
						},
						{
							name: 'Passed',
							y: count.passed,
							color: '#00e272',
							url: baseURI + "lms/courses/my?type=completed"
						}
					]
				}
			],
			tooltip: {
				style: {
					fontSize: '14px' // Increase font size for the tooltip here
				}
			}
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
				type: 'category',
				labels: {
					style: {
						fontSize: '12px'  // Change this to your desired size
					}
				}
			},
			yAxis: {
				title: {
					text: 'Total number of assigned course(s)'
				},
				labels: {
					style: {
						fontSize: '12px'  // Change this to your desired size
					}
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
				headerFormat: '<span style="font-size:14px">{series.name}</span><br>',
				pointFormat: '<span style="font-size:12px; color:{point.color}">{point.name}:</span> <b style="font-size:12px">{point.y} course(s)</b>'
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
							color: '#6B8ABB',
							y: count.assigned,
							url: baseURI + "lms/courses/my?type=assigned",
						},
						{
							name: 'Pending',
							color: '#ff834e',
							y: count.pending,
							url: baseURI + "lms/courses/my?type=pending"
						},
						{
							name: 'Ready To Start',
							color: '#2caffe',
							y: count.readyToStart,
							url: baseURI + "lms/courses/my?type=ready_to_start"
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
							url: baseURI + "lms/courses/my?type=completed"
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
					],
					dataLabels: {
						style: {
							fontSize: '12px'  // Change this to your desired size
						}
					}
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
	//
	getLMSAssignCourses();
	
});
