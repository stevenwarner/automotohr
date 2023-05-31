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
	 * Create course save event
	 */
	$(document).on("click", ".jsAddCourseCreateBtn", function (event) {
		// stop the default event
		event.preventDefault();
		// create the course object
		const courseObj = {
			course_title: $("#jsAddCourseTitle").val().trim(),
			course_content: $("#jsAddCourseAbout").val().trim(),
			job_titles: $("#jsAddCourseJobTitles").val() || [],
			course_type: $(".jsAddCourseType:checked").val(),
			course_file: $("#jsAddCourseFile").msFileUploader("get") || {},
		};

		//
		handleCourseCreation(courseObj);
	});

	/**
	 *
	 */
	$(document).on("click", ".jsModalCancel", function () {
		if (XHR !== null) {
			XHR.abort();
		}
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
			url: apiURL + "lms/course/add/view",
			method: "GET",
		})
			.success(function (resp) {
				//
				XHR = null;
				// load the view
				$("#" + modalId + "Body").html(resp);
				//
				$("#jsAddCourseJobTitles").select2({
					closeOnSelect: false,
				});
				$("#jsAddCourseFile").msFileUploader({
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

		// only when a file is uploaded
		// if (courseObj.course_type === "scorm") {
		// 	// check for empty file
		// 	if (!Object.keys(courseObj.course_file).length) {
		// 		errorArray.push("Please upload the SCORM file.");
		// 	} else if (courseObj.course_file.errorCode) {
		// 		errorArray.push(courseObj.course_file.errorCode);
		// 	}
		// }

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
		// courseObj.course_file = "ns_1685528224_0_1_docker.zip";
		// add company code
		courseObj.company_code = companyCode;
		try {
			//
			const createCourseResponse = await createCourseCall(courseObj);
		} catch (err) {
			return alertify.alert(
				"ERROR!",
				getErrorsStringFromArray(
					err.errors,
					'Errors!!'
				),
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
			$.post(apiURL + "lms/course", courseObj)
				.success(resolve)
				.fail(function (response) {
					reject(response.responseJSON);
				});
		});
	}

	// make the object available on window
	window.startCreateCourseProcess = startCreateCourseProcess;

	// startCreateCourseProcess(0);

	// handleCourseCreation({
	// 	course_title: "Harassment 101",
	// 	course_content: "Some helping material",
	// 	job_titles: [71],
	// 	course_type: "scorm",
	// 	course_file: {},
	// });
	// check if the browser version is old
	generateBrowserAlert();
});
