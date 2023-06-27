$(function createCourse() {
	/**
	 * set the XHR
	 */
	let XHR = null;

	/**
	 * set the company id
	 */
	let companyCode = 0;

	/**
	 * set the modal reference
	 */
	let modalId = "jsCreateCourseModel";

	/**
	 * set the model loader
	 */
	let modalLoaderId = "jsCreateCourseModelLoader";

	/**
	 * set the default questions array
	 */
	let questionsArray = [];

	/**
	 * Create course save event
	 */
	//
	$(document).on("click", ".jsAddCourseCreateBtn", function (event) {
		// stop the default event
		event.preventDefault();
		// create the course object
		const courseObj = {
			course_title: $("#jsAddCourseTitle").val().trim(),
			course_content: $("#jsAddCourseAbout").val().trim(),
			job_titles: $("#jsAddCourseJobTitles").val() || [],
			course_type: $(".jsAddCourseType:checked").val(),
			course_version: $("#jsAddCourseVersion").val(),
			course_file:
				$(
					"#" +
						($(".jsAddCourseType:checked").val() === "scorm"
							? "jsAddCourseFile"
							: "jsAddCourseVideoFile") +
						""
				).msFileUploader("get") || {},
			course_questions: questionsArray,
		};
		//
		handleCourseCreation(courseObj);
	});

	/**
	 * Toggle between manual and SCORM
	 */
	$(document).on("click", ".jsAddCourseType", function () {
		// set defaults
		$(".jsAddCourseScormBox").addClass("hidden");
		$(".jsAddManualCourseBox").addClass("hidden");
		// make view
		if ($(this).val() === "scorm") {
			$(".jsAddCourseScormBox").removeClass("hidden");
		} else {
			$(".jsAddManualCourseBox").removeClass("hidden");
			loadQuestionsView();
		}
	});

	/**
	 * Add a question
	 */
	$(document).on("click", ".jsAddQuestionBtn", function (event) {
		// stop the default behavior
		event.preventDefault();
		// handle add event
		loadAddQuestionView(modalLoaderId, saveQuestionToQuestions);
	});

	/**
	 * From question to main screen
	 */
	$(document).on("click", ".jsBackToAddCoursePage", function (event) {
		// stop the default behavior
		event.preventDefault();
		//
		$(".jsPageBox").addClass("hidden");
		$('.jsPageBox[data-id="main"]').removeClass("hidden");
	});

	/**
	 * Delete question
	 */
	$(document).on("click", ".jsDeleteQuestion", function (event) {
		// stop the default behavior
		event.preventDefault();
		//
		let questionId = $(this).closest("tr").data("key");
		return alertify.confirm(
			"Are you certain about deleting the chosen question?",
			function () {
				deleteQuestion(questionId);
			},
			CB
		);
	});

	/**
	 * Edit question
	 */
	$(document).on("click", ".jsEditQuestion", function (event) {
		// stop the default behavior
		event.preventDefault();
		//
		let questionId = $(this).closest("tr").data("key");
		//
		loadEditQuestionView(
			getTheQuestionObjById(questionId),
			modalLoaderId,
			function (questionObj) {
				//
				$(".jsPageBox").addClass("hidden");
				// make the view visible
				$('.jsPageBox[data-id="main"]').removeClass("hidden");
				// set tmp array
				let tmpArray = [];
				// loop through questions
				questionsArray.map(function (q) {
					if (q.question_id === questionId) {
						tmpArray.push(questionObj);
					} else {
						tmpArray.push(q);
					}
				});
				//
				questionsArray = tmpArray;
				// regenerate view
				loadQuestionsView();
				// hide the loader
				ml(false, modalLoaderId);
			}
		);
	});

	/**
	 * Create a course
	 *
	 * @param {int} companyId
	 */
	function startCreateCourseProcess(companyId) {
		// set the company Id
		companyCode = companyId;
		// load view
		Modal(
			{
				Id: modalId,
				Title: "Create Course",
				Loader: modalLoaderId,
				Cl: "container",
				Ask: true,
				Body: '<div id="' + modalId + 'Body"></div>',
			},
			loadView
		);
	}

	/**
	 * get the view from server
	 */
	function loadView() {
		// check the call
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: apiURL + "lms/course/view",
			method: "GET",
		})
			.success(function (resp) {
				//
				XHR = null;
				// load the view
				$("#" + modalId + "Body").html(resp);
				// load select2 on course version
				$("#jsAddCourseVersion").select2({
					minimumResultsForSearch: -1,
				});
				// load select2 on course job titles
				$("#jsAddCourseJobTitles").select2({
					closeOnSelect: false,
				});
				// load image
				$("#jsAddCourseFile").msFileUploader({
					fileLimit: "50mb",
					allowedTypes: ["zip"],
				});
				//
				$("#jsAddCourseVideoFile").msFileUploader({
					fileLimit: "50mb",
					allowedTypes: ["mp4", "ppt", "pptx"],
				});

				// hide the loader
				ml(false, modalLoaderId);
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
	 * Handle course creation process
	 *
	 * @param {*} courseObj
	 */
	async function handleCourseCreation(courseObj) {
		//
		const errorArray = [];
		// validate
		if (!courseObj.course_title) {
			errorArray.push("Course title is required.");
		}
		if (!courseObj.job_titles.length) {
			errorArray.push("Select at least one job title.");
		}
		if (!courseObj.course_type) {
			errorArray.push("Course type is required.");
		}
		// set default question array
		courseObj.course_questions = questionsArray;
		// only when a file is uploaded
		// check for empty file
		if (!Object.keys(courseObj.course_file).length) {
			errorArray.push(
				"Please upload the " +
					(courseObj.course_type === "manual" ? "Course" : "SCORM") +
					" file."
			);
		} else if (courseObj.course_file.errorCode) {
			errorArray.push(courseObj.course_file.errorCode);
		}
		// for manual course
		if (courseObj.course_type === "manual" && !questionsArray.length) {
			errorArray.push(
				"At least one question is required for manual course."
			);
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
		// upload file
		let response = await uploadFile(courseObj.course_file);
		// parse the JSON
		response = JSON.parse(response);
		// if file was not uploaded successfully
		if (!response.data) {
			return alertify.alert("ERROR", "Failed to upload the file.", CB);
		}
		// set the file
		courseObj.course_file = response.data;
		// add company code
		courseObj.company_code = companyCode;
		//
		try {
			//
			const createCourseResponse = await createCourseCall(courseObj);
			//
			if (courseObj.course_type === "scorm") {
				await updateScormCourseCall(
					createCourseResponse.courseId,
					courseObj.course_file
				);
			}
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
	}

	/**
	 * Create the default course
	 *
	 * @param {*} courseObj
	 * @returns
	 */
	function createCourseCall(courseObj) {
		return new Promise(function (resolve, reject) {
			//
			$.ajax({
				url: apiURL + "lms/course",
				method: "POST",
				headers: {
					"Content-Type": "application/json",
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
	 * Read Scorm manifest file and update Course
	 *
	 * @param {*} courseId
	 * @returns
	 */
	function updateScormCourseCall(courseId, filePath) {
		return new Promise(function (resolve, reject) {
			const courseObj = {
				scorm_file: filePath,
			};
			//
			$.ajax({
				url: baseURI + "lms/course/scorm/parse/" + courseId,
				method: "POST",
				data: courseObj,
			})
				.success(resolve)
				.fail(function (response) {
					reject(response.responseJSON);
				});
		});
	}

	/**
	 * Callback for add question
	 *
	 * @param {*} questionObj
	 */
	function saveQuestionToQuestions(questionObj) {
		// hide the loader
		ml(false, modalLoaderId);
		// push the question to questions array
		questionsArray.push(questionObj);
		// load questions view
		loadQuestionsView();
		//
		$(".jsPageBox").addClass("hidden");
		$('.jsPageBox[data-id="main"]').removeClass("hidden");
	}

	/**
	 * Load questions on view
	 *
	 * @returns
	 */
	function loadQuestionsView() {
		// check the length of questions
		if (!questionsArray.length) {
			return $("#jsAddCourseQuestionsList").html(
				'<tr><th colspan="3"><p class="alert alert-info text-center">No questions found yet.</p></th></tr>'
			);
		}
		// set default trs
		let tr = "";
		//
		questionsArray.map(function (questionObj) {
			//
			tr += '<tr data-key="' + questionObj.question_id + '">';
			tr += '	<td class="vam">' + questionObj.question_title + "</td>";
			tr +=
				'	<td class="vam text-center">' +
				questionObj.question_type.replace(/_/gi, " ").toUpperCase() +
				"</td>";
			tr += '	<td class="vam text-center">';
			// Edit button
			tr +=
				'		<button type="button" class="btn btn-warning jsEditQuestion" title="Edit question" placement="top">';
			tr += '			<i class="fa fa-edit" aria-hidden="true"></i>';
			tr += "		</button>";
			// remove button
			tr +=
				'		<button type="button" class="btn btn-danger jsDeleteQuestion" title="Delete question" placement="top">';
			tr += '			<i class="fa fa-times-circle" aria-hidden="true"></i>';
			tr += "		</button>";
			tr += "	</td>";
			tr += "</tr>";
		});
		//
		$("#jsAddCourseQuestionsList").html(tr);
	}

	/**
	 * Delete question
	 * @param {int} questionId
	 */
	function deleteQuestion(questionId) {
		//
		questionsArray = questionsArray.filter(function (questionObj) {
			return questionObj.question_id == questionId ? false : true;
		});
		//
		loadQuestionsView();
	}

	/**
	 * get question
	 * @param {int} questionId
	 */
	function getTheQuestionObjById(questionId) {
		//
		let question = questionsArray.filter(function (questionObj) {
			return questionObj.question_id == questionId ? true : false;
		});
		//
		return question[0];
	}

	// make the object available on window
	window.startCreateCourseProcess = startCreateCourseProcess;
});
