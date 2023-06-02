$(function LMSCourses() {
	// set the xhr
	let XHR = null;
	// set the default filter
	let filterObj = {
		title: "",
		status: "all",
		jobTitleIds: "all",
	};

	/**
	 * Apply filter
	 */
	$(".jsApplyFilterDefaultCourse").click(function (event) {
		// prevent default event
		event.preventDefault();
		//
		filterObj.title = $(".jsCourseTitleDefaultCourse").val() || "";
		filterObj.status = $(".jsCourseStatusDefaultCourse").val();
		filterObj.jobTitleIds = $(".jsCourseJobTitleDefaultCourse").select2(
			"val"
		);
		//
		getLMSDefaultCourses();
	});

	/**
	 * Toggle view
	 */
	$(document).on("click", ".jsToggleViewDefaultCourse", function (event) {
		// prevent default event
		event.preventDefault();
		//
		$(
			'[data-key="jsView' + $(this).closest("tr").data("id") + '"]'
		).toggleClass("hidden");
	});

	/**
	 * Enable course
	 */
	$(document).on("click", ".jsEnableDefaultCourse", function (event) {
		// prevent default event
		event.preventDefault();
		//
		let courseId = $(this).closest("tr").data("id");
		//
		return alertify
			.confirm(
				"Do you really want to enable this course?",
				function () {
					//
					updateDefaultCourseStatus(courseId, true);
				},
				CB
			)
			.set("labels", {
				ok: "Yes",
				cancel: "No",
			})
			.setHeader("Confirm");
	});

	/**
	 * Disable course
	 */
	$(document).on("click", ".jsDisableDefaultCourse", function (event) {
		// prevent default event
		event.preventDefault();
		//
		let courseId = $(this).closest("tr").data("id");
		//
		return alertify
			.confirm(
				"Do you really want to disable this course?",
				function () {
					//
					updateDefaultCourseStatus(courseId, false);
				},
				CB
			)
			.set("labels", {
				ok: "Yes",
				cancel: "No",
			})
			.setHeader("Confirm");
	});

	/**
	 * Add course
	 */
	$(document).on("click", ".jsAddCourse", function (event) {
		// stop the default functionality
		event.preventDefault();
		// call the function
		startCreateCourseProcess(0);
	});

	/**
	 * Edit course
	 */
	$(document).on("click", ".jsEditCourse", function (event) {
		// stop the default functionality
		event.preventDefault();
		// call the function
		startEditCourseProcess($(this).closest("tr").data("id"));
	});

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
		str += "&status=" + encodeURI(filterObj["status"]);
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
	function getLMSDefaultCourses() {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + "lms/course?" + getFilterAsString(),
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
				$("#jsDefaultCoursesView").html(response);
				// hide the loader
				ml(false, "jsPageLoader");
			})
			.fail(function (response) {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
				//
				return alertify.alert(
					"Errors!",
					response.responseJSON.errors.join("<br />"),
					CB
				);
			});
	}

	/**
	 * get job titles
	 */
	function getDefaultJobTitles() {
		// make the call
		$.ajax({
			url: apiURL + "jobs/titles/default",
			method: "GET",
		}).success(function (response) {
			//
			if (response.data) {
				//
				let options = '<option value="all">All</option>';
				//
				response.data.map(function (job) {
					options +=
						'<option value="' +
						job["sid"] +
						'">' +
						job["title"] +
						"</option>";
				});
				// set the view
				$(".jsCourseJobTitleDefaultCourse").html(options);
				$(".jsCourseJobTitleDefaultCourse").select2();
			}
		});
	}

	/**
	 * update course status
	 *
	 * @param {int} courseId
	 * @param {bool} status
	 */
	function updateDefaultCourseStatus(courseId, status) {
		// check if already a call is placed
		if (XHR !== null) {
			return;
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + "lms/course/" + courseId + "/status",
			method: "PUT",
			headers: {
				"content-type": "application/json",
			},
			data: JSON.stringify({ active: status }),
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				alertify.success(response.success);
				getLMSDefaultCourses();
			})
			.fail(function (response) {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
				//
				return alertify.alert(
					"Errors!",
					response.responseJSON.errors.join("<br />"),
					CB
				);
			});
	}
	// make it available to window
	window.getLMSDefaultCourses = getLMSDefaultCourses;
	//
	getDefaultJobTitles();
	getLMSDefaultCourses();
});
