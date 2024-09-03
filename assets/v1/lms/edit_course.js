$(function editCourse() {
	/**
	 * set the XHR
	 */
	let XHR = null;

	/**
	 * set the company id
	 */
	let companyCode = 0;

	/**
	 * set the course id
	 */
	let courseCode = 0;

	/**
	 * set the modal reference
	 */
	let modalId = "jsEditCourseModel";

	/**
	 * set the model loader
	 */
	let modalLoaderId = "jsEditCourseModelLoader";

	/**
	 * set the default questions array
	 */
	let questionsArray = [];

	/**
	 * set the default course file type
	 */
	let courseFileType = "file";

	/**
	 * set the default course link
	 */
	let courseFileLink = "";

	/**
	 * SCORM uploader options
	 */
	let scormOptions = {
		fileLimit: "100mb",
		allowedTypes: ["zip"],
	};

	/**
	 * Manual uploader options
	 */
	let manualOptions = {
		fileLimit: "100mb",
		allowedTypes: ["mp4", "ppt", "pptx", "mov"],
	};

	// set default course obj
	let courseObj = {
		course_title: "",
		course_content: "",
		job_titles: [],
		course_type: "",
		course_file_type: "",
		course_file_link: "",
		course_version: "",
		course_file_name: "",
		course_file: {},
		course_questions: [],
	};

	/**
	 * Create course save event
	 */
	$(document).on("click", ".jsEditCourseCreateBtn", function (event) {
		// stop the default event
		event.preventDefault();
		// create the course object
		courseObj = {
			course_title: $("#jsEditCourseTitle").val().trim(),
			course_content: $("#jsEditCourseAbout").val().trim(),
			course_start_period: $("#jsEditCourseStartPeriod").val().trim(),
			course_end_period: $("#jsEditCourseEndPeriod").val().trim(),
			job_titles: $("#jsEditCourseJobTitles").val() || [],
			course_type: $(".jsEditCourseType:checked").val(),
			course_recurring_in: $("#jsEditCourseReassignIn").val(),
			course_recurring_type: $("#jsEditCourseReassignType").val(),
			course_version: $("#jsEditCourseVersion").val(),
			course_file_name: courseObj.course_file_name,
			course_file_type: $(".jsEditCourseFileType:checked").val(),
			course_file_link: $("#jsEditCourseLink").val(),
			course_file:
				$(
					"#" +
						($(".jsEditCourseType:checked").val() === "scorm"
							? "jsEditCourseFile"
							: "jsEditCourseVideoFile") +
						""
				).msFileUploader("get") || {},
			course_questions: questionsArray,
		};
		//
		if ($(".jsEditIndefiniteCourse").is(":checked")) {
			courseObj.course_end_period = null;
		}
		//
		handleCourseUpdate(courseObj);
	});

	/**
	 * Toggle between manual and SCORM
	 */
	$(document).on("click", ".jsEditCourseType", function () {
		// set defaults
		$(".jsEditCourseScormBox").addClass("hidden");
		$(".jsEditManualCourseBox").addClass("hidden");
		// make view
		if ($(this).val() === "scorm") {
			$(".jsEditCourseScormBox").removeClass("hidden");
		} else {
			$(".jsEditManualCourseBox").removeClass("hidden");
			loadCourseFileView();
			loadQuestionsView();
		}
	});

	/**
	 * Toggle upload, youtube and vimeo
	 */
	$(document).on("click", ".jsEditCourseFileType", function () {
		// set defaults
		courseFileType = $(this).val();
		loadCourseFileView();
	});

	/**
	 * Add a question
	 */
	$(document).on("click", ".jsEditQuestionBtn", function (event) {
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
	$(document).on("click", ".jsDeleteQuestionEdit", function (event) {
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
	$(document).on("click", ".jsEditQuestionEdit", function (event) {
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
				// hides the loader
				ml(false, modalLoaderId);
			}
		);
	});

	/**
	 * Indefinite Course
	 */
	$(document).on("click", ".jsEditIndefiniteCourse", function (event) {
		if ($('.jsEditIndefiniteCourse').is(':checked')) {
			$("#jsEditCourseEndPeriod").val("");
			$(".jsRecurringCourses").hide();
		} else {
			$(".jsRecurringCourses").show();
		}
	});

	/**
	 * Create a course
	 *
	 * @param {int} companyId
	 */
	function startEditCourseProcess(companyId, courseId) {
		// set the company Id
		companyCode = companyId;
		// set course id
		courseCode = courseId;
		// load view
		Modal(
			{
				Id: modalId,
				Title: 'Update Course <span id="jsEditCourseTitleHeader"></span>',
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
			url: apiURL + "lms/course/view/edit",
			method: "GET",
		})
			.success(function (resp) {
				//
				XHR = null;
				// load the view
				$("#" + modalId + "Body").html(resp);
				// load select2 on course version
				$("#jsEditCourseVersion").select2({
					minimumResultsForSearch: -1,
				});
				// load select2 on course job titles
				$("#jsEditCourseJobTitles").select2({
					closeOnSelect: false,
				});
				//
				getCourseDetails();
			})
			.fail(handleErrorResponse)
			.done(function () {
				// empty the call
				XHR = null;
			});
	}

	/**
	 * Handle course update process
	 *
	 * @param {*} courseObj
	 */
	async function handleCourseUpdate(courseObj) {
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
		if (!courseObj.course_recurring_in) {
			errorArray.push("Course recurring number is required.");
		} else if (!courseObj.course_recurring_in.isValidInteger()) {
			errorArray.push("Please enter valid integer value.");
		}
		if (!courseObj.course_recurring_type.length) {
			errorArray.push("Course recurring type is required.");
		}
		// set default question array
		courseObj.course_questions = questionsArray;
		//
		let isCourseTypeFile = false;
		//
		if (
			courseObj.course_file_type === "link" &&
			courseObj.course_type === "manual"
		) {
			if (!courseObj.course_file_link) {
				errorArray.push("YouTube / Vimeo link is required.");
			} else if (
				!courseObj.course_file_link.isValidYoutubeLink() &&
				!courseObj.course_file_link.isValidVimeoLink()
			) {
				errorArray.push("Invalid YouTube / Vimeo link.");
			}
		} else {
			//
			if (
				Object.keys(courseObj.course_file).length &&
				courseObj.course_file.hasError
			) {
				// only when a file is uploaded
				// check for empty file
				errorArray.push(
					"Please upload the " +
						(courseObj.course_type === "manual"
							? "Course"
							: "SCORM") +
						" file."
				);
			} else if (!Object.keys(courseObj.course_file).length) {
				//
				if (
					courseObj.course_type === "scorm" &&
					courseObj.course_file_name.match(/.zip/gi) === null
				) {
					errorArray.push("Please use the right SCORM file.");
				}
				//
				if (
					courseObj.course_type === "manual" &&
					courseObj.course_file_name.match(/.zip/gi) !== null
				) {
					errorArray.push("Please use the right file.");
				}
			}
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
		//
		if (
			!courseObj.course_file.link &&
			Object.keys(courseObj.course_file).length &&
			courseObj.course_file_type !== "link"
		) {
			
			//
			if (!isCourseTypeFile) {
				isCourseTypeFile = true;
				// upload file
				let response = await uploadFile(courseObj.course_file);
				// parse the JSON
				response = JSON.parse(response);
				// if file was not uploaded successfully
				if (!response.data) {
					return alertify.alert(
						"ERROR",
						"Failed to upload the file.",
						function () {
							ml(false, modalLoaderId);
						}
					);
				}
				// set the file
				courseObj.course_file = response.data;
			} else {
				courseObj.course_file = courseObj.course_file_name;
			}
		} else {
			courseObj.course_file = courseObj.course_file_link;
		}
		//
		if (!courseObj.course_file) {
			courseObj.course_file = courseObj.course_file_name;
		}
		//
		delete courseObj.course_file_link;
		// add company code
		courseObj.company_code = companyCode;
		//
		try {
			//
			const updateCourseResponse = await updateCourseCall(courseObj);
			//
			if (
				courseObj.course_type === "scorm" &&
				isCourseTypeFile
			) {
				await updateScormCourseCall(courseObj.course_file);
			}
			//
			return alertify.alert(
				"SUCCESS!",
				updateCourseResponse.data,
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
	 * Read Scorm manifest file and update Course
	 *
	 * @param {*} filePath
	 * @returns
	 */
	function updateScormCourseCall(filePath) {
		return new Promise(function (resolve, reject) {
			const courseObj = {
				scorm_file: filePath,
			};
			//
			$.ajax({
				url: baseURI + "lms/course/scorm/parse/" + courseCode,
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
	 * Create the default course
	 *
	 * @param {*} courseObj
	 * @returns
	 */
	function updateCourseCall(courseObj) {
		return new Promise(function (resolve, reject) {
			//
			$.ajax({
				url: apiURL + "lms/course/" + courseCode,
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
	 * Load course file type view
	 *
	 * @returns
	 */
	function loadCourseFileView() {
		$(".jsEditUploadFile").addClass("hidden");
		$(".jsEditLinkFile").addClass("hidden");
		//
		if (courseFileType == "file") {
			$(".jsEditUploadFile").removeClass("hidden");
		} else {
			$(".jsEditLinkFile").removeClass("hidden");
			$("#jsEditCourseLink").val(courseFileLink);
		}
	}

	/**
	 * Load questions on view
	 *
	 * @returns
	 */
	function loadQuestionsView() {
		// check the length of questions
		if (!questionsArray.length) {
			return $("#jsEditCourseQuestionsList").html(
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
				'		<button type="button" class="btn btn-warning jsEditQuestionEdit" title="Edit question" placement="top">';
			tr += '			<i class="fa fa-edit" aria-hidden="true"></i>';
			tr += "		</button>";
			// remove button
			tr +=
				'		<button type="button" class="btn btn-danger jsDeleteQuestionEdit" title="Delete question" placement="top">';
			tr += '			<i class="fa fa-times-circle" aria-hidden="true"></i>';
			tr += "		</button>";
			tr += "	</td>";
			tr += "</tr>";
		});
		//
		$("#jsEditCourseQuestionsList").html(tr);
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

	/**
	 *
	 */
	function getCourseDetails() {
		XHR = $.ajax({
			url: apiURL + "lms/course/" + courseCode,
			method: "GET",
			headers: {
				accept: "application/json",
				"content-type": "application/json",
			},
		})
			.success(function (response) {
				//z
				XHR = null;
				//
				setEditView(response.data);
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
	 * Set the edit view
	 * @param {*} courseObj
	 */
	function setEditView(co) {
		//
		questionsArray = [];
		// set the title
		$("#jsEditCourseTitleHeader").html(" - " + co.course_title);
		$("#jsEditCourseTitle").val(co.course_title);
		// set the course description
		$("#jsEditCourseAbout").val(co.course_content);
		// set the course job_titles
		$("#jsEditCourseJobTitles").select2("val", co.job_titles);
		//
		$("#jsEditCourseStartPeriod").val(co.course_start_period);
		$("#jsEditCourseEndPeriod").val(co.course_end_period);
		//
		if (co.course_end_period == null) {
			$('.jsEditIndefiniteCourse').prop('checked', true);
			$(".jsRecurringCourses").hide();
		}
		//
		$("#jsEditCourseStartPeriod")
			.datepicker({
				dateFormat: "mm/dd/yy",
				changeYear: true,
				changeMonth: true,
				onSelect: function (value) {
					$("#jsEditCourseEndPeriod").datepicker(
						"option",
						"minDate",
						value
					);
				},
			})
			.datepicker("option", "maxDate", $("#jsEditCourseEndPeriod").val());

		$("#jsEditCourseEndPeriod")
			.datepicker({
				dateFormat: "mm/dd/yy",
				changeYear: true,
				changeMonth: true,
				onSelect: function (value) {
					$("#jsEditCourseStartPeriod").datepicker(
						"option",
						"maxDate",
						value
					);
					//
					$('.jsEditIndefiniteCourse').prop('checked', false);
					$(".jsRecurringCourses").show();
				},
			})
			.datepicker(
				"option",
				"minDate",
				$("#jsEditCourseStartPeriod").val()
			);
		//
		$("#jsEditCourseReassignIn").val(co.course_recurring_value);
		$("#jsEditCourseReassignType").val(co.course_recurring_type);
		// set the course course_type
		$('.jsEditCourseType[value="' + co.course_type + '"]').prop(
			"checked",
			true
		);
		$('.jsEditCourseFileType[value="' + co.course_file_type + '"]').trigger(
			"click"
		);
		$('.jsEditCourseType[value="' + co.course_type + '"]').trigger("click");

		// for SCORM
		if (co.course_type === "scorm") {
			// set uploader
			scormOptions["placeholderImage"] = co.course_file_name;
			// set scorm version
			$("#jsEditCourseVersion").select2("val", co.course_version);
		} else {
			manualOptions["placeholderImage"] = co.course_file_name;
			questionsArray = co.course_questions;
			courseFileType = co.course_file_type;
			courseFileLink = co.course_file_name;
			loadCourseFileView();
			loadQuestionsView();
		}
		// load SCORM uploader
		$("#jsEditCourseFile").msFileUploader(scormOptions);
		// load manual uploader
		$("#jsEditCourseVideoFile").msFileUploader(manualOptions);
		// set file name
		courseObj.course_file_name = co.course_file_name;
		// hide the modal
		ml(false, modalLoaderId);
	}

	// make the object available on window
	window.startEditCourseProcess = startEditCourseProcess;
});
