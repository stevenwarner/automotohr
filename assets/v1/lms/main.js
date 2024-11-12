$(function LMSCourses() {
	// set the xhr
	let XHR = null;
	// set the default filter
	let filterObj = {
		title: "",
		status: "active",
		jobTitleIds: "all",
	};

	// attach select2 to status filter
	$(".jsCourseStatusDefaultCourse")
		.select2({
			minimumResultsForSearch: -1,
		})
		.select2("val", filterObj.status);

	/**
	 * Apply filter
	 */
	$(".jsApplyFilterDefaultCourse").click(function (event) {
		// prevent default event
		event.preventDefault();
		//
		filterObj.title = $(".jsCourseTitleDefaultCourse").val() || "";
		filterObj.status = $(".jsCourseStatusDefaultCourse").select2("val");
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
		startEditCourseProcess(0, $(this).closest("tr").data("id"));
	});

	/**
	 * Preview course
	 */
	$(document).on("click", ".jsPreviewCourse", function (event) {
		// stop the default functionality
		event.preventDefault();
		// call the function
		previewCourse($(this).closest("tr").data("id"));
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
			.fail(handleErrorResponse)
			.done(function () {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
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
			.fail(handleErrorResponse)
			.done(function () {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
			});
	}

	function previewCourse(courseId) {
		window.previewCourseId = courseId;
		// create modal
		Modal(
			{
				Id: "jsLMSPreviewCourseModal",
				Title: "Preview Course",
				Loader: "jsLMSPreviewCourseModalLoader",
				Cl: "container",
				Body: '<div id="jsLMSPreviewCourseModalBody"></div>',
			},
			function () {
				// show the loader
				ml(true, "jsLMSPreviewCourseModalLoader");
				// setInterval(() => {
				XHR = $.ajax({
					url:
						apiURL +
						"lms/course/" +
						courseId +
						"/preview?_has=" +
						(window.location.host.indexOf("www.") !== -1
							? "y"
							: "n"),
					method: "GET",
				})
					.success(function (response) {
						// empty the call
						XHR = null;
						$("#jsLMSPreviewCourseModalBody").html(response);
					})
					.fail(handleErrorResponse)
					.done(function () {
						// empty the call
						XHR = null;
						// hide the loader
						ml(false, "jsLMSPreviewCourseModalLoader");
					});
				// }, 225000);
				// make the call
			}
		);
	}
	// make it available to window
	window.getLMSDefaultCourses = getLMSDefaultCourses;
	//
	getDefaultJobTitles();
	getLMSDefaultCourses();





	$(document).on("click", ".jsDraggable", function (event) {
		// stop the default functionality
		event.preventDefault();
		// call the function

		//	alert('sdfsdf');
		$(".jsDraggable").sortable({
			//	$(".jsDraggable").sortable({
			update: function (event, ui) {
				//
				var orderList = [];
				//
				$(".jsCourseSortOrder").map(function (i) {
					orderList.push($(this).data("key"));
				});
				// 
				var obj = {};
				obj.sortOrders = orderList;
				//
				updateCourseSortOrder(obj);
			}
		});
	});


	//
	function updateCourseSortOrder(data) {
		// check if XHR already in progress
		if (XHR !== null) {
			return;
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + "lms/course/updateOrder",
			method: "PUT",
			headers: {
				"content-type": "application/json",
			},
			data: JSON.stringify({ active: data }),
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				//getLMSDefaultCourses();
			})
			.fail(handleErrorResponse)
			.done(function () {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
			});

	}


});
