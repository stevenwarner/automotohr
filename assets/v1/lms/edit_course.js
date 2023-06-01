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
	 * Create course save event
	 */
	$(document).on("click", ".jsEditCourseCreateBtn", function (event) {
		// stop the default event
		event.preventDefault();
		// create the course object
		const courseObj = {
			course_title: $("#jsEditCourseTitle").val().trim(),
			course_content: $("#jsEditCourseAbout").val().trim(),
			job_titles: $("#jsEditCourseJobTitles").val() || [],
			course_type: $(".jsEditCourseType:checked").val(),
			course_file: $("#jsEditCourseFile").msFileUploader("get") || {},
		};
		//
		handleCourseUpdate(courseObj);
	});

	/**
	 * Edit a course
	 *
	 * @param {int} courseId
	 */
	function startEditCourseProcess(courseId) {
		// set the company Id
		courseCode = courseId;
		// load view
		Modal(
			{
				Id: modalId,
				Title: 'Edit Course <span id="jsEditCourseTtile"></span>',
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
			url: apiURL + "lms/course/view/" + courseCode,
			method: "GET",
		})
			.success(function (resp) {
				//
				XHR = null;
				// load the view
				$("#" + modalId + "Body").html(resp);
				//
				$("#jsEditCourseJobTitles").select2({
					closeOnSelect: false,
				});
				$("#jsEditCourseFile").msFileUploader({
					fileLimit: "30mb",
					allowedTypes: ["zip"],
				});
				// hide the loader
				ml(false, modalLoaderId);
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
	 * Handle course creation process
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

		// only when a file is uploaded
		if (courseObj.course_type === "scorm") {
			// check for empty file
			if (!Object.keys(courseObj.course_file).length) {
				errorArray.push("Please upload the SCORM file.");
			} else if (courseObj.course_file.errorCode) {
				errorArray.push(courseObj.course_file.errorCode);
			}
		}

		// make the user notify of errors
		if (errorArray.length) {
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
			return alertify.alert("ERROR", "Failed to upload file.", CB);
		}
		// set the file
		courseObj.course_file = response.data;
		// add company code
		courseObj.company_code = companyCode;
		try {
			//
			const createCourseResponse = await updateCourseCall(courseObj);
			//
			return alertify.alert(
				"SUCCESS!",
				createCourseResponse.data,
				function () {
					$("#" + modalId).remove();
					//
					getLMSDefaultCourses();
				}
			);
		} catch (err) {
			return alertify.alert(
				"ERROR!",
				getErrorsStringFromArray(err.errors, "Errors!!"),
				CB
			);
		}
	}

	/**
	 * Update the default course
	 *
	 * @param {*} courseObj
	 * @returns
	 */
	function updateCourseCall(courseObj) {
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

	// make the object available on window
	window.startEditCourseProcess = startEditCourseProcess;
});
