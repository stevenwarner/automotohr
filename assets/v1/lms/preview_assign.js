$(function LMSEmployeeCourses() {
	// set the xhr
	let XHR = null;

	/**
	 * start course
	 */
	$(".jsStartCourseButton").click(function (event) {
		// prevent default event
		event.preventDefault();
		//
		alertify
			.confirm(
				"Are you sure you want to start this course?",
				function () {
					startCourse();
				},
				CB
			)
			.setHeader("Confirm");
	});

	$(document).on("change", ".jsChangeScormLanguage", function (event) {
		event.preventDefault();
		//
		// var courseId = $(this).data("course_id");
		var language = $(this).val();
		//
		alertify
			.confirm(
				"Are you sure you want to change course language?",
				function () {
					changeScormLanguage(language);
				},
				CB
			)
			.setHeader("Confirm");
	});

	function changeScormLanguage(language) {
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
				language,
			method: "PUT",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				//
				if (response.status === "language_changed") {
					window.location =
						baseURI + "lms/courses/" + courseId + "/" + language;
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

	function startCourse() {
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
				courseLanguage +
				"/start?_has=" +
				(window.location.host.indexOf("www.") !== -1 ? "y" : "n"),
			method: "GET",
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				//
				if (response.status === "course_started") {
					$("#jsStartCourseDiv").hide();
					$(".jsSaveQuestionResult").show();
					getLMSAssignCourse();
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

	// set the default filter
	if (courseType === "scorm") {
		function sendCourseToSave(CMIElements) {
			if (lessonStatus != "completed") {
				// make the call
				XHR = $.ajax({
					url: apiURL + "lms/trainings/save_scorm/" + employeeId,
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					data: JSON.stringify({
						courseId: courseId,
						cmiElement: CMIElements,
						type: "scorm",
						version: scormVersion,
					}),
				})
					.success(function (response) {
						if (response.status === "failed") {
							// return alertify.alert(
							// 	"WARNING!",
							// 	"Apologies for not passing this course. We highly encourage you to consider giving it another attempt.",
							// 	function () {
							// 		window.location =
							// 			baseURI + "lms/courses/" + courseId + "/" + courseLanguage;
							// 	}
							// );
							return;
						} else if (response.status === "passed") {
							return alertify.alert(
								"SUCCESS!",
								"Congratulations on successfully passing this course!",
								function () {
									window.location =
										baseURI +
										"lms/courses/my_lms_dashboard";
								}
							);
						}
					})
					.fail(handleErrorResponse)
					.done(function (response) {
						// empty the call
						XHR = null;
					});
				//
			}
		}
		//
		window.sendCourseToSave = sendCourseToSave;
	}

	/**
	 * From question to main screen
	 */
	$(document).on("click", ".jsSaveQuestionResult", function (event) {
		// stop the default behavior
		event.preventDefault();
		var errorArray = [];
		var answerObj = {};
		//
		JSON.parse(questions).map(function (question) {
			//
			var name = question.question_type + "_" + question.question_id;
			//
			if (
				question.question_type === "yes_no" ||
				question.question_type === "single_choice"
			) {
				//
				var value = $("input[name=" + name + "]:checked").val();
				//
				answerObj[question.question_id] = value;
				//
				if (!value || value === undefined) {
					if (question.question_required == true) {
						errorArray.push(question.question_title);
					}
				}
			} else if (question.question_type === "multiple_choice") {
				//
				var list = [];
				//
				$("input[name='" + name + "']:checked").each(function () {
					list.push($(this).val());
				});
				//
				answerObj[question.question_id] = list;
				//
				if (list.length == 0) {
					if (question.question_required == true) {
						errorArray.push(question.question_title);
					}
				}
			} else if (question.question_type === "text") {
				var text = $(
					'textarea[name="textarea_' + question.question_id + '"]'
				).val();
				//
				answerObj[question.question_id] = text;
				//
				if (!text || text === undefined) {
					if (question.question_required == true) {
						errorArray.push(question.question_title);
					}
				}
			}
		});
		//
		if (errorArray.length) {
			// make the user notify of errors
			return alertify.alert(
				"WARNING!",
				getQuestionsFromArray(errorArray),
				CB
			);
		}
		//
		try {
			//
			saveCourseAnswers(answerObj);
			//
			return alertify.alert(
				"SUCCESS!",
				createCourseResponse.data,
				function () {
					//
					getLMSDefaultCourses();
					$("#" + modalId).remove();
				}
			);
		} catch (err) {
			ml(false, modalLoaderId);
			return alertify.alert(
				"ERROR!",
				getErrorsStringFromArray(err.errors, "Errors!!"),
				CB
			);
		}
	});

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
			url:
				apiURL +
				"lms/trainings/" +
				employeeId +
				"/" +
				courseId +
				"/" +
				courseLanguage +
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
				"lms/trainings/" +
				employeeId +
				"/" +
				courseId +
				"/questions/" +
				mode,
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
	/**
	 * Save the manual questions result
	 *
	 * @param {*} answerObj
	 * @returns
	 */
	function saveCourseAnswers(answerObj) {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + "lms/trainings/" + employeeId,
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			data: JSON.stringify({
				courseId: courseId,
				answers: answerObj,
			}),
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view

				if (response.status === "failed") {
					// return alertify.alert(
					// 	"WARNING!",
					// 	"Apologies for not passing this course. We highly encourage you to consider giving it another attempt.",
					// 	function () {
					// 		window.location =
					// 			baseURI + "lms/courses/" + courseId;
					// 	}
					// );
					return;
				} else if (response.status === "passed") {
					return alertify.alert(
						"SUCCESS!",
						"Congratulations! you have successfully passed this course!",
						function () {
							window.location = baseURI + "lms/courses/my";
						}
					);
				}
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
	if (lessonStatus === "not_started") {
		ml(false, "jsPageLoader");
		$("#jsStartCourseDiv").show();
		$(".jsSaveQuestionResult").hide();
	} else if (lessonStatus === "started" || lessonStatus === "completed") {
		getLMSAssignCourse();
		//
		if (lessonStatus === "completed") {
			$(".jsSaveQuestionResult").hide();
		}
	}
});
