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
	 * set delete languages
	 */
	let deleteLanguages = [];

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
		fileLimit: "200mb",
		allowedTypes: ["zip"],
	};

	/**
	 * Manual uploader options
	 */
	let manualOptions = {
		fileLimit: "200mb",
		allowedTypes: ["mp4", "ppt", "pptx", "mov", "wav"],
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
			course_sort_order: $("#jsEditCourseSortOrder").val().trim(),
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

			course_banner: $("#jsEditCourseBanner").msFileUploader("get"),
			course_secondary_logo_type: $(".jsEditSecondaryLogo:checked").val(),
			course_secondary_logo: $("#jsPartnershipLogo").msFileUploader("get"),
			course_questions: questionsArray,
		};
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
	 * Toggle between secondary logo required or not
	 */
		$(document).on("click", ".jsEditSecondaryLogo", function () {
			// make view
			if ($(this).val() === "yes") {
				$(".jsPartnershipLogoSection").removeClass("hidden");
			} else {
				// set defaults
				$(".jsPartnershipLogoSection").addClass("hidden");
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
		if ($(".jsEditIndefiniteCourse").is(":checked")) {
			$("#jsEditCourseEndPeriod").val("");
			$(".jsEditCourseEndPeriodLabel").hide();
		} else {
			$(".jsEditCourseEndPeriodLabel").show();
		}
	});

	/**
	 * Add Scorm Courses
	 */
	$(document).on("click", ".jsEditNewScormCourse", function (event) {
		var totalLanguages = $(this).data("language_count");
		var languages = $(this).data("languages").split(",");
		var totalItems = $(".jsScormCourseItem").length;
		var selectedLanguages = [];
		//
		$(".jsScormCourseItem").each(function (i) {
			//
			let rowNo = $(this).data("row_no");
			//
			if (i == 0) {
				selectedLanguages.push($("#jsEditCourseLanguage").val());
			} else {
				selectedLanguages.push(
					$("#jsEditCourseLanguage" + rowNo).val()
				);
			}
		});
		//
		if (totalLanguages > totalItems) {
			//
			let id = getRandomNumber();
			let html = getUploadScormHTML(languages, selectedLanguages, id);

			//
			$("#jsScormLanguageCourses").append(html);
			//
			$("#jsEditCourseLanguage" + id).select2({
				closeOnSelect: false,
			});
			//
			$("#jsEditCourseFile" + id).msFileUploader({
				fileLimit: "200mb",
				allowedTypes: ["zip"],
			});
			//
			disableSelectedLanguage(languages);
		}

		if (totalLanguages == totalItems + 1) {
			$(".jsEditNewScormCourse").prop("disabled", true);
		}
	});

	/**
	 * Add Scorm Courses
	 */
	$(document).on("click", ".jsEditRemoveLanguageSection", function (event) {
		var sectionId = $(this).data("section_id");
		var rowNo = $(this).data("row_no");
		var languageCount = $(this).data("language_count");
		var totalItems = $(".jsScormCourseItem").length;

		let pointerToParent = $(this).closest(".jsScormCourseItem");
		let isRandom = pointerToParent.data("israndom");
		//
		if (languageCount == totalItems) {
			$(".jsEditNewScormCourse").prop("disabled", false);
		}
		//
		disableSelectedLanguage(
			$(".jsEditNewScormCourse").data("languages").split(",")
		);
		//
		if (isRandom === 0) {
			return _confirm(
				"<p>This action will permanently delete the SCORM course, and it cannot be undone. However, you can re-upload the SCORM file if needed.</p><br /><p>Are you sure you want to delete it?</p>",
				function () {
					deleteTheSCORMAttachedCourse(rowNo);
					$("#" + sectionId).remove();
				}
			);
		} else {
			$("#" + sectionId).remove();
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
		if (!courseObj.course_sort_order) {
			errorArray.push("Course sort order is required.");
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
		//
		if ($(".jsEditIndefiniteCourse").is(":checked")) {
			courseObj.course_end_period = null;
		} else {
			if (!courseObj.course_end_period.length) {
				errorArray.push("Course end date is required.");
			}
		}
		// handle banner
		if (typeof courseObj.course_banner.link === "undefined") {
			if (!Object.keys(courseObj.course_banner).length) {
				errorArray.push("Please upload the Course banner.");
			} else if (courseObj.course_banner.errorCode) {
				errorArray.push(courseObj.course_banner.errorCode);
			}
		}
		//	
		if (courseObj.course_type == "scorm" && courseObj.course_secondary_logo_type == "yes") {
			// handle logo
			if (!Object.keys(courseObj.course_secondary_logo).length) {
				errorArray.push("Please upload the Course Secondary Logo.");
			} else if (courseObj.course_secondary_logo.errorCode) {
				errorArray.push(courseObj.course_secondary_logo.errorCode);
			}
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
		} else if (courseObj.course_type === "manual") {
			if (!Object.keys(courseObj.course_file).length) {
				errorArray.push("Please upload the Course file.");
			} else if (courseObj.course_file.errorCode) {
				errorArray.push(courseObj.course_file.errorCode);
			}
		}
		//
		let scorm_course_files = [];
		//
		if (courseObj.course_type == "scorm") {
			///
			$(".jsScormCourseItem").each(function (i) {
				//
				var id = "";
				var languageId = "";
				if (i == 0) {
					id = "jsEditCourseFile";
					languageId = "jsEditCourseLanguage";
				} else {
					var rowNo = $(this).data("row_no");
					//
					id = "jsEditCourseFile" + rowNo;
					languageId = "jsEditCourseLanguage" + rowNo;
				}
				//
				var course_file = $("#" + id).msFileUploader("get");
				var language = $("#" + languageId).val();
				//
				if (!Object.keys(course_file).length) {
					errorArray.push(
						"Please upload the scorm " + language + " file."
					);
				} else if (course_file.errorCode) {
					errorArray.push(course_file.errorCode);
				} else {
					scorm_course_files.push({
						key: language,
						value: course_file,
					});
				}
			});
			//
		}
		//
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
			Object.keys(courseObj.course_file).length &&
			courseObj.course_type === "manual"
		) {
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
						//
						ml(false, modalLoaderId);
					}
				);
			}
			// set the file
			courseObj.course_file = response.data;
		} else if (
			Object.keys(scorm_course_files).length &&
			courseObj.course_type === "scorm"
		) {
			//
			await Promise.all(
				scorm_course_files.map(async (item, index) => {
					//
					if (item.value.size && item.value.type) {
						let response = await uploadFile(item.value);
						response = JSON.parse(response);

						if (response.data) {
							scorm_course_files[index] = {
								filePath: response.data,
								key: item.key,
								value: {
									hasErrors: false,
									link: response.data,
									type: "upload",
								},
							};
						}
					} else {
						scorm_course_files[index]["filePath"] = item.value.link;
						// scorm_course_files.splice(index, 1);
					}
				})
			);
			//
			delete courseObj.course_file;
		} else {
			courseObj.course_file = courseObj.course_file_link;
		}
		//
		if (!courseObj.course_file) {
			courseObj.course_file = courseObj.course_file_name;
		}
		//
		delete courseObj.course_file_link;
		//
		if (typeof courseObj.course_banner.link === "undefined") {
			// upload file
			let response = await uploadFile(courseObj.course_banner);
			// parse the JSON
			response = JSON.parse(response);
			// if file was not uploaded successfully
			if (!response.data) {
				return alertify.alert(
					"ERROR",
					"Failed to upload the course banner.",
					function () {
						//
						ml(false, modalLoaderId);
					}
				);
			}
			// set the file
			courseObj.course_banner = {};
			courseObj.course_banner.link = response.data;
		}
		//
		if (courseObj.course_secondary_logo_type == "yes") {
			if (typeof courseObj.course_secondary_logo.link === "undefined") {
				// upload file
				let responseLogo = await uploadFile(courseObj.course_secondary_logo);
				// parse the JSON
				responseLogo = JSON.parse(responseLogo);
				// if file was not uploaded successfully
				if (!responseLogo.data) {
					return alertify.alert(
						"ERROR",
						"Failed to upload the course secondary logo.",
						function () {
							//
							ml(false, modalLoaderId);
						}
					);
				}
				// set the file
				courseObj.course_secondary_logo = responseLogo.data;
			} else {
				// set the file
				courseObj.course_secondary_logo = courseObj.course_secondary_logo.link;
			}
		}
		// add company code
		courseObj.company_code = companyCode;
		//
		try {
			//
			const updateCourseResponse = await updateCourseCall(courseObj);
			//
			if (courseObj.course_type === "scorm") {
				//
				if (scorm_course_files.length) {
					//
					let tmp = [];
					tmp[0] = {};

					scorm_course_files.map((item) => {
						if (item.key === "english") {
							tmp[0] = item;
						} else {
							tmp.push(item);
						}
					});

					scorm_course_files = tmp;
					//
					await Promise.all(
						scorm_course_files.map(async (item) => {
							await updateScormCourseCall(
								courseCode,
								item.filePath,
								item.key
							);
						})
					);
				}
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
	 * Read Scorm manifest file and update Course
	 *
	 * @param {*} courseId
	 * @returns
	 */
	function updateScormCourseCall(courseId, filePath, language) {
		return new Promise(function (resolve, reject) {
			const courseObj = {
				scorm_file: filePath,
				scorm_language: language,
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
				//
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
		// set the course sort order
		$("#jsEditCourseSortOrder").val(co.course_sort_order);
		//
		$("#jsEditCourseStartPeriod").val(co.course_start_period);
		$("#jsEditCourseEndPeriod").val(co.course_end_period);
		//
		if (co.course_end_period == null) {
			$(".jsEditIndefiniteCourse").prop("checked", true);
			$(".jsEditCourseEndPeriodLabel").hide();
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
					$(".jsEditIndefiniteCourse").prop("checked", false);
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
		//
		if (co.secondary_logo) {
			//
			$("input[name=jsEditSecondaryLogo][value=yes]").attr(
				"checked",
				"checked"
			);	
			//
			$("#jsPartnershipLogo").msFileUploader({
				fileLimit: "15mb",
				allowedTypes: ["jpg", "jpeg", "png", "webp"],
				placeholderImage: co.secondary_logo,
			});
		} else {
			//
			$(".jsPartnershipLogoSection").addClass("hidden");
			//
			$("input[name=jsEditSecondaryLogo][value=no]").attr(
				"checked",
				"checked"
			);	
			//
			$("#jsPartnershipLogo").msFileUploader({
				fileLimit: "15mb",
				allowedTypes: ["jpg", "jpeg", "png", "webp"]
			});
		}
		//
		$('.jsEditCourseFileType[value="' + co.course_file_type + '"]').trigger(
			"click"
		);
		$('.jsEditCourseType[value="' + co.course_type + '"]').trigger("click");

		$("#jsEditCourseBanner").msFileUploader({
			fileLimit: "15mb",
			allowedTypes: ["jpg", "jpeg", "png", "webp"],
			placeholderImage: co.course_banner,
		});

		// for SCORM
		if (co.course_type === "scorm") {
			// set uploader
			var languages = $(".jsEditNewScormCourse")
				.data("languages")
				.split(",");
			//
			co.courseLanguages.map(function (item, index) {
				//
				if (index == 0) {
					//
					scormOptions["placeholderImage"] = item.course_file_name;
					//
					// load SCORM uploader
					$("#jsEditCourseFile").msFileUploader(scormOptions);
					//
					$("#jsEditCourseLanguage").select2({
						closeOnSelect: false,
					});
					//
					$("#jsEditCourseLanguage").select2(
						"val",
						item.course_file_language
					);
				} else {
					// let id = getRandomNumber();
					let id = item.sid;
					//
					var selectedLanguages = [];
					//
					$(".jsScormCourseItem").each(function (i) {
						//
						let rowNo = $(this).data("row_no");

						if (i == 0) {
							selectedLanguages.push(
								$("#jsEditCourseLanguage").val()
							);
						} else {
							selectedLanguages.push(
								$("#jsEditCourseLanguage" + rowNo).val()
							);
						}
					});
					//
					let html = getUploadScormHTML(
						languages,
						selectedLanguages,
						id,
						0,
						item.updated_at
					);
					//
					$("#jsScormLanguageCourses").append(html);
					//
					$("#jsEditCourseLanguage" + id).select2({
						closeOnSelect: false,
					});
					//
					$("#jsEditCourseLanguage" + id).select2(
						"val",
						item.course_file_language
					);
					//
					scormOptions["placeholderImage"] = item.course_file_name;
					$("#jsEditCourseFile" + id).msFileUploader(scormOptions);
				}
				//
			});
			//
			disableSelectedLanguage(languages);
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
		//
		// load manual uploader
		$("#jsEditCourseVideoFile").msFileUploader(manualOptions);
		// set file name
		courseObj.course_file_name = co.course_file_name;
		//
		// hide the modal
		ml(false, modalLoaderId);
	}

	function getUploadScormHTML(
		languages,
		selectedLanguages,
		id,
		isRandomNumber = 1,
		updatedAt
	) {
		//
		html = ``;
		html += `<article class="article-sec jsScormCourseItem" id="jsScormCourseItem${id}" data-row_no="${id}" data-israndom="${isRandomNumber}">`;
		html += `<div class="row">`;
		html += `<div class="col-md-12">`;
		html += `<button class="btn btn-danger js-dropzone-delete-btn pull-right jsEditRemoveLanguageSection" data-section_id="jsScormCourseItem${id}" data-row_no="${id}" data-language_count="${languages.length}"><i class="fa fa-trash"></i></button>`;
		html += `</div>`;
		html += `</div>`;
		html += `<div class="form-group">`;
		html += `<label>SCORM Language <strong class="text-danger">*</strong>`;
		html += `</label>`;
		if (typeof updatedAt !== undefined) {
			html += `<p>Uploaded at: ${moment(updatedAt).format(
				"MMM DD YYYY, ddd hh:mm a"
			)}</p>`;
		}
		html += `<p class="text-danger"><strong><em>The language of the SCORM.</strong></em></p>`;
		html += `<select style="width: 100%" class="jsScormLanguage" id="jsEditCourseLanguage${id}">`;
		languages.map(function (language) {
			if (selectedLanguages.includes(language)) {
				html += `<option disabled="disabled" value="${language}">${
					language.charAt(0).toUpperCase() + language.slice(1)
				}</option>`;
			} else {
				html += `<option value="${language}">${
					language.charAt(0).toUpperCase() + language.slice(1)
				}</option>`;
			}
		});
		html += `</select>`;
		html += `</div>`;
		html += `<br>`;
		html += `<div class="form-group">`;
		html += `<label>Course <strong class="text-danger">*</strong></label>`;
		html += `<p class="text-danger"><strong><em>Upload the "SCORM" course.</strong></em></p>`;
		html += `<input type="file" class="hidden" id="jsEditCourseFile${id}" />`;
		html += `</div>`;
		html += `</article>`;
		return html;
	}

	function disableSelectedLanguage(languages) {
		//
		let selectedLanguages = [];
		//
		$(".jsScormCourseItem").each(function (i) {
			//
			let rowNo = $(this).data("row_no");
			//
			if (i == 0) {
				selectedLanguages.push($("#jsEditCourseLanguage").val());
			} else {
				selectedLanguages.push(
					$("#jsEditCourseLanguage" + rowNo).val()
				);
			}
		});

		//
		$(".jsScormCourseItem").each(function (i) {
			//
			let rowNo = $(this).data("row_no");
			var language1 = "";
			var selectId = "";
			//
			if (i == 0) {
				language1 = $("#jsEditCourseLanguage").val();
			} else {
				language1 = $("#jsEditCourseLanguage" + rowNo).val();
				selectId = rowNo;
			}

			//
			var html = "";
			//
			languages.map(function (language) {
				if (
					selectedLanguages.includes(language) &&
					language != language1
				) {
					html += `<option disabled="disabled" value="${language}">${
						language.charAt(0).toUpperCase() + language.slice(1)
					}</option>`;
				} else if (language == language1) {
					html += `<option selected="selected" value="${language}">${
						language.charAt(0).toUpperCase() + language.slice(1)
					}</option>`;
				} else {
					html += `<option value="${language}">${
						language.charAt(0).toUpperCase() + language.slice(1)
					}</option>`;
				}
			});
			//
			$("#jsEditCourseLanguage" + selectId).html(html);
			//
		});
	}

	function deleteTheSCORMAttachedCourse(fileCourseId) {
		$.ajax({
			url: apiURL + "lms/course/language/" + fileCourseId,
			method: "DELETE",
		})
			.always(function () {})
			.fail(handleErrorResponse)
			.success(function (resp) {
				console.log(resp);
			});
	}

	function getRandomNumber() {
		var min = 10000;
		var max = 99999;
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	// make the object available on window
	window.startEditCourseProcess = startEditCourseProcess;
});
