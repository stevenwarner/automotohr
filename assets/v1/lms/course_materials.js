$(function courseMaterials() {
	/**
	 * set the XHR
	 */
	let XHR;

	let courseId, courseName;

	$(document).on("click", ".jsManageMaterials", function (event) {
		//
		event.preventDefault();
		//
		courseId = $(this).closest("tr.jsCourseRowDashboard").data("id");
		courseName = $(this)
			.closest("tr.jsCourseRowDashboard")
			.children("td:first")
			.text();
		//
		loadModel();
	});

	$(document).on("click", ".jsToggleCourseMaterialRow", function (event) {
		//
		event.preventDefault();
		const rowId = $(this).data("key");
		$(".jsCourseMaterialViewRow" + rowId).toggleClass("hidden");
	});

	$(document).on("click", ".jsCourseMaterialAddBtn", function (event) {
		//
		event.preventDefault();
		if (XHR !== undefined) {
			return;
		}
		//
		const formObject = {
			title: $("#jsCourseMaterialTitle").val().trim(),
			language: $("#jsCourseMaterialLanguage").val(),
			material: $("#jsCourseMaterialFile").msFileUploader("get"),
		};

		if (!formObject.title) {
			return _error("Please add a title for course material.");
		}

		const fileErrors = checkFileForError(formObject.material);
		//
		if (fileErrors.error) {
			return;
		}
		//
		processMaterial(formObject);
	});

	$(document).on("click", ".jsCourseMaterialDeleteBtn", function (event) {
		//
		event.preventDefault();

		if (XHR !== undefined) {
			return;
		}
		//
		let courseMaterialId = $(this).closest("tr").data("key");
		let _this = $(this);

		_confirm(
			"<p>This process is not revertible!</p><br/><p>Do you really want to delete the selected course material?</p>",
			function () {
				processDelete(courseMaterialId, _this);
			}
		);
	});

	$(document).on("click", ".jsModalCancel", function () {
		courseId = undefined;
		courseName = undefined;
	});

	function checkFileForError(fileToBeChecked, returnError) {
		//
		const errorsList = {
			required: "Please select a file.",
		};
		//
		let errorString;
		// check for file
		if (Object.keys(fileToBeChecked).length === 0) {
			errorString = errorsList.required;
		} else if (fileToBeChecked.errorCode) {
			errorString = fileToBeChecked.errorCode;
		}

		if (errorString) {
			if (returnError === undefined) {
				_error(errorString);
			}
			return { error: errorString };
		}

		return true;
	}

	function loadModel() {
		Modal(
			{
				Id: "jsManageMaterialsModal",
				Title: "Manage Course Materials -  " + courseName,
				Loader: "jsManageMaterialsLoader",
				Body: '<div id="jsManageMaterialsBody"></div>',
			},
			getTheView
		);

		$('.csModalBody').css('top', '100px');
	}

	function getTheView() {
		if (XHR !== undefined) {
			XHR.abort();
		}

		XHR = $.ajax({
			url: apiURL + "lms/course/" + courseId + "/materials",
			method: "GET",
		})
			.always(function () {
				XHR = undefined;
				ml(false, "jsManageMaterialsLoader");
			})
			.fail(function (err) {
				console.log(err);
				$(".jsModalCancel").trigger("click");
			})
			.done(function (resp) {
				//
				$("#jsManageMaterialsBody").html(resp);
				//
				$("#jsCourseMaterialFile").msFileUploader({
					fileLimit: "200mb",
					allowedTypes: [
						"jpg",
						"jpeg",
						"png",
						"gif",
						"pdf",
						"rtf",
						"doc",
						"docx",
						"ppt",
						"pptx",
						"xls",
						"xlsx",
						"mov",
						"mp4",
						"wav",
						"mp3",
					],
				});

				$("#jsCourseMaterialLanguage").select2({
					minimumResultsForSearch: -1,
				});
			});
	}

	async function processMaterial(formObject) {
		XHR = null;
		const buttonRef = callButtonHook($(".jsCourseMaterialAddBtn"), true);

		// // lets upload the file
		let uploadedFiled = await uploadFile(formObject.material);
		if (uploadedFiled) {
			uploadedFiled = JSON.parse(uploadedFiled);
		}
		if (!uploadedFiled.data) {
			XHR = undefined;
			callButtonHook(buttonRef, false);
			return _error("Something went wrong while uploading the file.");
		}
		// replace the uploaded file name
		formObject.material = uploadedFiled.data;
		//
		XHR = $.ajax({
			url: apiURL + "lms/course/" + courseId + "/materials",
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				Accept: "application/json",
			},
			data: JSON.stringify(formObject),
		})
			.always(function () {
				XHR = undefined;
				callButtonHook(buttonRef, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.message, getTheView);
			});
	}

	function processDelete(courseMaterialId, _this) {
		//
		XHR = null;
		//
		const buttonRef = callButtonHook(_this, true);
		//
		XHR = $.ajax({
			url:
				apiURL +
				"lms/course/" +
				courseId +
				"/materials/" +
				courseMaterialId,
			method: "DELETE",
			headers: {
				"Content-Type": "application/json",
				Accept: "application/json",
			},
		})
			.always(function () {
				XHR = undefined;
				callButtonHook(buttonRef, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.message, getTheView);
			});
	}
});
