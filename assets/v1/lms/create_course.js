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
	 * set the default course file type
	 */
	let courseFileType = "file";

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
			course_recurring_in: $("#jsAddCourseReassignIn").val(),
			course_recurring_type: $("#jsAddCourseReassignType").val(),
			course_start_period: $("#jsAddCourseStartPeriod").val().trim(),
			course_end_period: $("#jsAddCourseEndPeriod").val().trim(),
			course_sort_order: $("#jsAddCourseSortOrder").val().trim(),
			course_version: $("#jsAddCourseVersion").val(),
			course_file_type: $(".jsAddCourseFileType:checked").val(),
			course_secondary_logo_type: $(".jsAddSecondaryLogo:checked").val(),
			course_secondary_logo: $("#jsPartnershipLogo").msFileUploader("get"),
			course_file_link: $("#jsAddCourseLink").val(),
			course_file:
				$(
					"#" +
						($(".jsAddCourseType:checked").val() === "scorm"
							? "jsAddCourseFile"
							: "jsAddCourseVideoFile") +
						""
				).msFileUploader("get") || {},
			course_banner: $("#jsAddCourseBanner").msFileUploader("get"),
			course_questions: questionsArray,
		};
		//
		if ($(".jsAddIndefiniteCourse").is(":checked")) {
			courseObj.course_end_period = null;
		}
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
			loadCourseFileView();
			loadQuestionsView();
		}
	});

	/**
	 * Toggle between secondary logo required or not
	 */
	$(document).on("click", ".jsAddSecondaryLogo", function () {
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
	$(document).on("click", ".jsAddCourseFileType", function () {
		// set defaults
		courseFileType = $(this).val();
		loadCourseFileView();
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
	 * Indefinite Course
	 */
	$(document).on("click", ".jsAddIndefiniteCourse", function (event) {
		if ($(".jsAddIndefiniteCourse").is(":checked")) {
			$("#jsAddCourseEndPeriod").val("");
			$(".jsRecurringCourses").hide();
		} else {
			$(".jsRecurringCourses").show();
		}
	});

	/**
	 * Add Scorm Courses
	 */
	$(document).on("click", ".jsAddNewScormCourse", function (event) {
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
				selectedLanguages.push($("#jsAddCourseLanguage").val());
			} else {
				selectedLanguages.push($("#jsAddCourseLanguage" + rowNo).val());
			}
		});
		//
		if (totalLanguages > totalItems) {
			//
			let html = "";
			let id = getRandomNumber();
			//
			html += `<article class="article-sec jsScormCourseItem" id="jsScormCourseItem${id}" data-row_no="${id}">`;
			html += `<div class="row">`;
			html += `<div class="col-md-12">`;
			html += `<button class="btn btn-danger js-dropzone-delete-btn pull-right jsRemoveLanguageSection" data-section_id="jsScormCourseItem${id}" data-language_count="${totalLanguages}"><i class="fa fa-trash"></i></button>`;
			html += `</div>`;
			html += `</div>`;
			html += `<div class="form-group">`;
			html += `<label>SCORM Language <strong class="text-danger">*</strong></label>`;
			html += `<p class="text-danger"><strong><em>The language of the SCORM.</strong></em></p>`;
			html += `<select style="width: 100%" class="jsScormLanguage" id="jsAddCourseLanguage${id}">`;
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
			html += `<input type="file" class="hidden" id="jsAddCourseFile${id}" />`;
			html += `</div>`;
			html += `</article>`;
			//
			$("#jsScormLanguageCourses").append(html);
			//
			$("#jsAddCourseLanguage" + id).select2({
				closeOnSelect: false,
			});
			//
			$("#jsAddCourseFile" + id).msFileUploader({
				fileLimit: "200mb",
				allowedTypes: ["zip"],
			});
			//
			disableSelectedLanguage(languages);
		}

		if (totalLanguages == totalItems + 1) {
			$(".jsAddNewScormCourse").prop("disabled", true);
		}
	});

	function disableSelectedLanguage(languages) {
		//
		let selectedLanguages = [];
		//
		$(".jsScormCourseItem").each(function (i) {
			//
			let rowNo = $(this).data("row_no");
			//
			if (i == 0) {
				selectedLanguages.push($("#jsAddCourseLanguage").val());
			} else {
				selectedLanguages.push($("#jsAddCourseLanguage" + rowNo).val());
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
				language1 = $("#jsAddCourseLanguage").val();
			} else {
				language1 = $("#jsAddCourseLanguage" + rowNo).val();
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
				} else {
					html += `<option value="${language}">${
						language.charAt(0).toUpperCase() + language.slice(1)
					}</option>`;
				}
			});
			//
			$("#jsAddCourseLanguage" + selectId).html(html);
			//
		});
	}

	/**
	 * Add Scorm Courses
	 */
	$(document).on("click", ".jsRemoveLanguageSection", function (event) {
		var sectionId = $(this).data("section_id");
		var languageCount = $(this).data("language_count");
		var totalItems = $(".jsScormCourseItem").length;
		//
		if (languageCount == totalItems) {
			$(".jsAddNewScormCourse").prop("disabled", false);
		}
		//
		$("#" + sectionId).remove();
		//
		disableSelectedLanguage(
			$(".jsAddNewScormCourse").data("languages").split(",")
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
		//
		questionsArray = [];
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
				// load select2 on course language
				$("#jsAddCourseLanguage").select2({
					minimumResultsForSearch: -1,
				});
				// load select2 on course version
				$("#jsAddCourseVersion").select2({
					minimumResultsForSearch: -1,
				});
				// load select2 on course job titles
				$("#jsAddCourseJobTitles").select2({
					closeOnSelect: false,
				});
				///
				$("#jsAddCourseReassignType").val("year");
				$("#jsAddCourseBanner").msFileUploader({
					fileLimit: "15mb",
					allowedTypes: ["jpg", "jpeg", "png", "webp"],
				});
				// load image
				$("#jsAddCourseFile").msFileUploader({
					fileLimit: "200mb",
					allowedTypes: ["zip"],
				});
				//
				$("#jsAddCourseVideoFile").msFileUploader({
					fileLimit: "200mb",
					allowedTypes: ["mp4", "ppt", "pptx", "mov"],
				});
				//
				$("input[name=jsAddCourseType][value=scorm]").attr(
					"checked",
					"checked"
				);
				$("#jsAddCourseLanguage").select2("val", "english");
				$(".jsAddCourseScormBox").removeClass("hidden");		
				//
				$(".jsPartnershipLogoSection").addClass("hidden");
				$("input[name=jsAddSecondaryLogo][value=no]").attr(
					"checked",
					"checked"
				);	
				$("#jsPartnershipLogo").msFileUploader({
					fileLimit: "15mb",
					allowedTypes: ["jpg", "jpeg", "png", "webp"],
				});
				//
				$("#jsAddCourseStartPeriod")
					.datepicker({
						dateFormat: "mm/dd/yy",
						changeYear: true,
						changeMonth: true,
						onSelect: function (value) {
							$("#jsAddCourseEndPeriod").datepicker(
								"option",
								"minDate",
								value
							);
						},
					})
					.datepicker(
						"option",
						"maxDate",
						$("#jsAddCourseEndPeriod").val()
					);

				$("#jsAddCourseEndPeriod")
					.datepicker({
						dateFormat: "mm/dd/yy",
						changeYear: true,
						changeMonth: true,
						onSelect: function (value) {
							$("#jsAddCourseStartPeriod").datepicker(
								"option",
								"maxDate",
								value
							);
							//
							$(".jsAddIndefiniteCourse").prop("checked", false);
							$(".jsRecurringCourses").show();
						},
					})
					.datepicker(
						"option",
						"minDate",
						$("#jsAddCourseStartPeriod").val()
					);
				//
				$(
					'.jsAddCourseFileType[value="' + courseFileType + '"]'
				).trigger("click");

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
		if (!courseObj.course_recurring_in) {
			errorArray.push("Course recurring number is required.");
		} else if (!courseObj.course_recurring_in.isValidInteger()) {
			errorArray.push("Please enter valid integer value.");
		}
		if (!courseObj.course_start_period) {
			errorArray.push("Course start date is required.");
		}
		if (!courseObj.course_sort_order) {
			errorArray.push("Course sort order is required.");
		}
		if (!courseObj.course_recurring_type.length) {
			errorArray.push("Course recurring type is required.");
		}
		if (!courseObj.course_type) {
			errorArray.push("Course type is required.");
		}
		//
		// handle banner
		if (!Object.keys(courseObj.course_banner).length) {
			errorArray.push("Please upload the Course banner.");
		} else if (courseObj.course_banner.errorCode) {
			errorArray.push(courseObj.course_banner.errorCode);
		}
		//
		if (courseObj.course_secondary_logo_type == "yes") {
			// handle logo
			if (!Object.keys(courseObj.course_secondary_logo).length) {
				errorArray.push("Please upload the Course Secondary Logo.");
			} else if (courseObj.course_secondary_logo.errorCode) {
				errorArray.push(courseObj.course_secondary_logo.errorCode);
			}
		}
		//
		// set default question array
		courseObj.course_questions = questionsArray;
		// only when a file is uploaded
		// check for empty file
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
				var rowNo = $(this).data("row_no");
				//
				var id = "";
				var languageId = "";
				if (i == 0) {
					id = "jsAddCourseFile";
					languageId = "jsAddCourseLanguage";
				} else {
					id = "jsAddCourseFile" + rowNo;
					languageId = "jsAddCourseLanguage" + rowNo;
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
				scorm_course_files.map(async (item) => {
					let response = await uploadFile(item.value);
					response = JSON.parse(response);
					//
					item.filePath = response.data;
					return item;
				})
			);
			//
			delete courseObj.course_file;
		} else {
			courseObj.course_file = courseObj.course_file_link;
		}
		//
		delete courseObj.course_file_link;
		// add company code
		courseObj.company_code = companyCode;

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
		//
		courseObj.course_banner = response.data;
		//
		if (courseObj.course_secondary_logo_type == "yes") {
			// upload secondary logo
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
			//
			// set the logo file
			courseObj.course_secondary_logo = responseLogo.data;
		}	


		
		//
		try {
			//
			const createCourseResponse = await createCourseCall(courseObj);
			//
			if (courseObj.course_type === "scorm") {
				//
				await Promise.all(
					scorm_course_files.map(async (item) => {
						await updateScormCourseCall(
							createCourseResponse.courseId,
							item.filePath,
							item.key
						);
					})
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
		$(".jsAddUploadFile").addClass("hidden");
		$(".jsAddLinkFile").addClass("hidden");
		//
		if (courseFileType == "file") {
			$(".jsAddUploadFile").removeClass("hidden");
		} else {
			$(".jsAddLinkFile").removeClass("hidden");
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

	function getRandomNumber() {
		var min = 10000;
		var max = 99999;
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	// make the object available on window
	window.startCreateCourseProcess = startCreateCourseProcess;
});
