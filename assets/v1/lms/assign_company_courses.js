$(function LMSStoreCourses() {
	// set the xhr
	let XHR = null;
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
