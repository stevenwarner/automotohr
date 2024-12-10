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
	$(document).on("click", ".jsEnableCourse", function (event) {
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
	$(document).on("click", ".jsDisableCourse", function (event) {
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
				if (getSegment(3) !== undefined) {
					$(".jsEditCourse").remove();
					$(".jsDisableDefaultCourse").remove();
					$(".jsToggleViewDefaultCourse").remove();
				}
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


	//
	$(document).on("click", ".jsEditCourseMaterial", function (event) {
		// stop the default functionality
		event.preventDefault();
		// call the function
		editCourseMaterial($(this).closest("tr").data("id"), $(this).closest("tr").data("title"));
	});


	//
	function editCourseMaterial(courseId, courseTitle) {
		window.previewCourseId = courseId;
		// create modal
		Modal(
			{
				Id: "jsLMSCourseMaterialModal",
				Title: "Helping Material - " + courseTitle,
				Loader: "jsLMSCourseMaterialModalLoader",
				Cl: "container",
				Body: '<div id="jsLMSCourseMaterialModalBody"></div>',
			},

			loadCourseMaterialView(courseId)
		);
	}


	function loadCourseMaterialView(courseId) {
		// check the call
		if (XHR !== null) {
			XHR.abort();
		}

		//
		XHR = $.ajax({
			url: apiURL + "lms/course/view/editmaterial/" + courseId,
			method: "GET",
		})
			.success(function (resp) {
				//
				XHR = null;
				// load the view
				$(jsLMSCourseMaterialModalBody).html(resp);

				$("#jsEditCourseMaterialLanguage").select2({
					closeOnSelect: false,
				});

				$("#jsEditCourseMaterialFile").msFileUploader({
					fileLimit: "200mb",
					allowedTypes: ["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx", "rtf", "ppt", "xls", "xlsx", "csv"],
				});

				ml(false, "jsLMSCourseMaterialModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function () {
				// empty the call
				XHR = null;
			});
	}


	//Add Course Material
	$(document).on("click", ".jsAddCourseMaterialBtn", function (event) {
		// stop the default event
		event.preventDefault();
		// create the course object
		const courseObj = {
			course_id: window.previewCourseId,
			material_language: $("#jsEditCourseMaterialLanguage").val().trim(),
		};
		//
		handleCourseMaterialCreation(courseObj);
	});


	async function handleCourseMaterialCreation(courseObj) {
		//
		const errorArray = [];
		// validate	

		var course_file = $("#jsEditCourseMaterialFile").msFileUploader("get");

		if (!Object.keys(course_file).length) {
			errorArray.push(
				"Please upload the material file."
			);
		} else if (course_file.errorCode) {
			errorArray.push(course_file.errorCode);
		} else { }

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
		ml(true, "jsLMSCourseMaterialModalLoader");
		// upload file
		let response = await uploadFile(course_file);
		// parse the JSON
		response = JSON.parse(response);
		// if file was not uploaded successfully
		if (!response.data) {
			return alertify.alert(
				"ERROR",
				"Failed to upload the file.",
				function () {
					//
					ml(false, "jsLMSCourseMaterialModalLoader");
				}
			);
		}

		// set the file
		courseObj.material_file = response.data;

		try {
			const createCourseResponse = await createCoursMaterialeCall(courseObj);

		//	console.log(createCourseResponse.materialdata);
			let actionButtons = '';
			actionButtons += `<button class="btn btn-success jsview" title="View" placement="top">`;
			actionButtons += `<i class="fa fa-eye" aria-hidden="true"></i>`;
			actionButtons += `</button>`;
			actionButtons += `<button class="btn btn-danger jsDisableCourse" title="Delete Material" placement="top">`;
			actionButtons += `<i class="fa fa-ban"></i>`;
			actionButtons += `</button>`;

			$('#coursematerialtbl').append('<tr data-id="' + createCourseResponse.materialdata[0].sid + '" id="' + createCourseResponse.materialdata[0].sid + '"><td><strong>' + createCourseResponse.materialdata[0].material_language + '</strong></td><td><a href="#" class="btn btn-link">' + createCourseResponse.materialdata[0].material_file + '</a></td><td>' + actionButtons + '</td></tr>');
			ml(false, "jsLMSCourseMaterialModalLoader");

			alertify.success('Course Material Successful Created ');		

		//	$('#jsEditCourseMaterialFile').trigger('click');

			//

		} catch (err) {
			ml(false, "jsLMSCourseMaterialModalLoader");
			return alertify.alert(
				"ERROR!",
				getErrorsStringFromArray(err.errors, "Errors!!"),
				CB
			);
		}
	}

	//
	function createCoursMaterialeCall(courseObj) {

		return new Promise(function (resolve, reject) {
			//
			$.ajax({
				url: apiURL + "lms/course/creatematerial",
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

});

//
$(document).on("click", ".jsDeleteCourseMaterial", function (event) {
	// prevent default event
	event.preventDefault();
	//
	let materialId = $(this).closest("tr").data("id");
	//
	return alertify
		.confirm(
			"Do you really want to delete this mterial?",
			function () {

				deleteCourseMaterial(materialId)
			},
			CB
		)
		.set("labels", {
			ok: "Yes",
			cancel: "No",
		})
		.setHeader("Confirm");
});



//
function deleteCourseMaterial(materialId) {
	// show the loader
	ml(true, "jsLMSCourseMaterialModalLoader");
	// make the call
	XHR = $.ajax({
		url: apiURL + "lms/course/deletematerial/" + materialId,
		method: "DELETE",
	})
		.success(function (response) {
			// empty the call
			XHR = null;
			alertify.success('Course Material Successful Deleted ');
			$("#row" + materialId).remove();
		})
		.fail(handleErrorResponse)
		.done(function () {
			// empty the call
			XHR = null;
			// hide the loader
			ml(false, "jsLMSCourseMaterialModalLoader");
		});
}