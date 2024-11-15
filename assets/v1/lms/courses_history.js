$(function LMSEmployeeCoursesHistory() {
	// set the xhr
	let XHR = null;
	/**
	 * get LMS Specific course
	 */
	function getLMSCourseHistory() {
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
				"lms/history/" +
				courseId +
				"?_has=" +
				(window.location.host.indexOf("www.") !== -1 ? "y" : "n"),
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
				"lms/history/" +
				courseId +
				"/questions",
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
				$(".jsSaveQuestionResult").hide();
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
			});
	}

	function sendCourseToSave(CMIElements) {
	}
	//
	window.sendCourseToSave = sendCourseToSave;
	//

	getLMSCourseHistory();
	ml(false, "jsPageLoader");
});
