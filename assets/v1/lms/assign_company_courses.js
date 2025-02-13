$(function LMSStoreCourses() {
	// set the xhr
	let XHR = null;
	/**
	 * set the course id
	 */
	let courseCode = 0;
	/**
	 * set the modal reference
	 */
	let modalId = "jsEditCourseJobRoleModel";

	/**
	 * set the model loader
	 */
	let modalLoaderId = "jsEditCourseJobRoleModelLoader";
	// set the default filter
	let filterObj = {
		title: "",
		jobTitleIds: "all",
	};
	//
	let selected_courses = [];
	let copy_course_count = 0;
	let coped_course = 0;
	let current_course = 0;
	//
	$(".jsCourseJobTitleStoreCourse").select2();

	/**
	 * Apply filter
	 */
	$(".jsApplyFilterStoreCourse").click(function (event) {
		// prevent default event
		event.preventDefault();
		//
		filterObj.title = $(".jsCourseTitleStoreCourse").val() || "";
		filterObj.jobTitleIds = $(".jsCourseJobTitleStoreCourse").select2(
			"val"
		);
		//
		getLMSStoreCourses();
	});

	/**
	 * Toggle view
	 */
	$(document).on(
		"click",
		".jsToggleViewAssignCompanyCourse",
		function (event) {
			// prevent default event
			event.preventDefault();
			("");
			//
			$(
				'[data-key="jsView' + $(this).closest("tr").data("id") + '"]'
			).toggleClass("hidden");
		}
	);

	$(document).on("click", ".jsCheckAll", selectAllInputs);
	$(document).on("click", ".jsAssignCourses", selectSingleInput);
	$(document).on("click", ".jsCopyCoursesBtn", start_copy_process);

	/**
	 * Disabl company course
	 */
	$(document).on("click", ".jsDisableCourse", function (event) {
		// stop the default functionality
		// event.preveentDefault();
		var sid = $(this).closest("tr").data("id");
		//
		alertify.confirm(
			'Are You Sure?',
			'Are you sure want to disable this course?',
			function () {
				// call the function
				changeCourseStatus(sid, 'disable');
			},
			function () {

			}
		)
	});

	/**
	 * Enable company course
	 */
	$(document).on("click", ".jsEnableCourse", function (event) {
		// stop the default functionality
		// event.preventDefault();
		var sid = $(this).closest("tr").data("id");
		//
		alertify.confirm(
			'Are You Sure?',
			'Are you sure want to enable this course?',
			function () {
				// call the function
				changeCourseStatus(sid, 'enable');
			},
			function () {

			}
		)
	});

	/**
	 * Edit company course jobRole
	 */
	$(document).on("click", ".jsManageJobRole", function (event) {
		// stop the default functionality
		// event.preveentDefault();
		var sid = $(this).closest("tr").data("id");
		var courseId = $(this).data("course_id");
		var courseTitle = $(this).data("course_title");
		//
		startEditCourseJobRoleProcess(courseId, courseTitle)
		
	});

	/**
	 * Update save event
	 */
	$(document).on("click", ".jsEditCourseJobRoleBtn", function (event) {
		// stop the default event
		event.preventDefault();
		// create the course object
		courseObj = {
			job_titles: $("#jsEditCourseJobTitles").val() || [],
		};
		//
		handleCourseJobRoleUpdate(courseObj);
	});

	/**
	 * Handle course update process
	 *
	 * @param {*} courseObj
	 */
	async function handleCourseJobRoleUpdate(courseObj) {
		//
		const errorArray = [];
		//
		if (!courseObj.job_titles.length) {
			errorArray.push("Select at least one job title.");
		}
		//
		if (errorArray.length) {
			// make the user notify of errors
			return alertify.alert(
				"ERROR!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		// start the loader and upload the file
		ml(true, modalLoaderId);
		//
		try {
			//
			console.log("please call update call")
			const updateCourseResponse = await updateCourseJobRollCall(courseObj);
			//
			return alertify.alert(
				"SUCCESS!",
				updateCourseResponse.data,
				function () {
					$("#" + modalId).remove();
				}
			);
		} catch (err) {
			//
			ml(false, modalLoaderId);
			return alertify.alert(
				"ERROR!",
				getErrorsStringFromArray(err.errors, "Errors!!"),
				CB
			);
		}
	}

	/**
	 * Update company job role call
	 *
	 * @param {*} courseObj
	 * @returns
	 */
	function updateCourseJobRollCall(courseObj) {
		console.log("update call")
		return new Promise(function (resolve, reject) {
			//
			$.ajax({
				url: apiURL + "lms/company/update_job_role/" + courseCode,
				method: "PUT",
				headers: {
					"Content-Type": "application/json",
					Accept: "application/json",
				},
				data: JSON.stringify(courseObj),
			})
				.success(resolve)
				.fail(function (response) {
					reject(response.responseJSON);
				});
		});
	}

	/**
	 * Create a course
	 *
	 * @param {int} companyId
	 */
	function startEditCourseJobRoleProcess(courseId, courseTitle) {
		// set course id
		courseCode = courseId;
		// load view
		Modal(
			{
				Id: modalId,
				Title: 'Update Course <span>'+courseTitle+'</span>',
				Loader: modalLoaderId,
				Cl: "container",
				Ask: true,
				Body: '<div id="' + modalId + 'Body"></div>',
			},
			loadJobRoleView
		);
	}

	/**
	 * get the view from server
	 */
	function loadJobRoleView() {
		// check the call
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: apiURL + "lms/company/view/edit_job_role/"+courseCode,
			method: "GET",
		})
			.success(function (resp) {
				//
				XHR = null;
				// load the view
				$("#" + modalId + "Body").html(resp);
				//
				$("#jsEditCourseJobTitles").select2({
					closeOnSelect: false,
				});
				//
			})
			.fail(handleErrorResponse)
			.done(function () {
				// empty the call
				XHR = null;
				//
				ml(false, modalLoaderId);
			});
	}

	// Change course status
	function changeCourseStatus (id, status) {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + `lms/course/store/course/${id}/${status}`,
			method: "PUT",
			headers: {
				Authorization: "Bearer " + apiAccessTokenStore,
			},
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
				window.location.reload();
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

	// Select all input: checkbox
	function selectAllInputs() {
		$(".jsStoreCourseRow")
			.find('input[name="courses_ids[]"]')
			.prop("checked", $(this).prop("checked"));
	}

	// Select single input: checkbox
	function selectSingleInput() {
		$(".jsCheckAll").prop(
			"checked",
			$(".jsAssignCourses").length == $(".jsAssignCourses:checked").length
		);
	}

	function start_copy_process(e) {
		e.preventDefault();

		selected_courses = get_all_selected_courses();

		if (selected_courses.length === 0) {
			return alertify.alert(
				"ERROR!",
				"Please select at least one course to start the process."
			);
		}

		current_course = 0;
		copy_course_count = selected_courses.length;
		ml(true, "jsPageLoader");
		$("#js-loader-text").html("Please wait, we are copying employee");
		copy_courses();
	}

	function copy_courses() {
		if (
			selected_courses.length > 0 &&
			selected_courses[current_course] === undefined
		) {
			ml(false, "jsPageLoader");
			$("#js-loader-text").html("");
			return alertify.alert(
				"Course Copy process is completed successfully!",
				function () {
					selected_courses = [];
					copy_course_count = 0;
					coped_course = 0;
					current_course = 0;
					getLMSStoreCourses();
					getLMSDefaultCourses();
				}
			);
		}
		if (selected_courses[current_course] === undefined) {
			ml(false, "jsPageLoader");
			$("#js-loader-text").html("");
			return;
		}
		//
		var course = selected_courses[current_course];
		//
		// make the call
		XHR = $.ajax({
			url: apiURL + "lms/course/assign",
			method: "POST",
			headers: {
				"content-type": "application/json",
			},
			data: JSON.stringify({
				courseID: course.course_sid,
			}),
		})
			.fail(handleErrorResponse)
			.done(function () {
				if (current_course <= copy_course_count) {
					current_course++;
					setTimeout(function () {
						copy_courses();
					}, 1000);
				} else {
					ml(false, "jsPageLoader");
					//
					alertify
						.alert("Course copy process is completed successfully!")
						.set("onok", function (closeEvent) {
							selected_courses = [];
							copy_course_count = 0;
							coped_course = 0;
							current_course = 0;
						});
				}
			});
	}

	function get_all_selected_courses() {
		var tmp = [];
		$.each($('input[name="courses_ids[]"]:checked'), function () {
			var obj = {};
			obj.course_sid = parseInt($(this).val());
			obj.course_name = $(this)
				.closest("tr")
				.find("td.js-course-name")
				.text();

			tmp.push(obj);
		});
		return tmp;
	}

	/**
	 * convert filter object to string
	 * @returns
	 */
	function getFilterAsString() {
		//
		let str = "";
		// set title
		str += "title=" + encodeURI(filterObj["title"]);
		// set status
		str += "&status=" + encodeURI("active");
		// set company id
		str += "&companyId=" + encodeURI(companySid);
		// set jobTitleIds
		str +=
			"&job_titles=" +
			(filterObj["jobTitleIds"] != "all"
				? filterObj["jobTitleIds"].join(",")
				: []);
		//
		return str;
	}

	/**
	 * get LMS default courses
	 */
	function getLMSStoreCourses() {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + "lms/course/store/courses?" + getFilterAsString(),
			method: "GET",
			headers: {
				Authorization: "Bearer " + apiAccessTokenStore,
			},
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
				$("#jsStoreCoursesView").html(response);
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
	function sendCourseToSave(CMIElements) {
	}
	//
	window.sendCourseToSave = sendCourseToSave;
	//
	getLMSStoreCourses();
});
