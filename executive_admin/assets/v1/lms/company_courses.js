$(function LMSCompanyCourses() {
	// set the xhr
	let XHR = null;
	// set the default filter
	let filterObj = {
		title: "all",
		status: "all",
	};
	//

	/**
	 * Apply filter
	 */
	$(".jsApplyFilterDefaultCourse").click(function (event) {
		// prevent default event
		event.preventDefault();
		//
		filterObj.title = $(".jsCourseTitleDefaultCourse").val() || "";
		filterObj.jobTitleIds = $(".jsCourseJobTitleDefaultCourse").select2(
			"val"
		);
		//
		getLMSCompanyCourses();
	});

	/**
	 * Toggle view
	 */
	$(document).on("click", ".jsToggleViewCompanyCourse", function (event) {
		console.log($(this).closest("tr").data("id"));
		// prevent default event
		event.preventDefault();
		//
		$(
			'[data-key="jsView' + $(this).closest("tr").data("id") + '"]'
		).toggleClass("hidden");
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

	function previewCourse(courseId) {
		//
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
		//
		return str;
	}

	/**
	 * get LMS default courses
	 */
	function getLMSCompanyCourses() {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + "lms/company?" + getFilterAsString(),
			method: "GET",
			headers: {
				Authorization: "Bearer " + apiAccessToken,
			},
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
	//
	getLMSCompanyCourses();
});
