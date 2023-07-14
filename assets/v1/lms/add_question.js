$(function addQuestion() {
	// set default question object
	let questionObj = {
		question_id: 0,
		question_title: "",
		question_content: "",
		question_type: "text",
		choice_list: {},
		video_type: "upload",
		video_file_name: "",
	};

	//
	let modalLoaderId;
	//
	let callbackReference;

	// set video file reference
	let videoFileRef;

	/**
	 * Stop the stream on close modal
	 */
	$(document).on("click", ".jsModalCancel", function () {
		videoFileRef.close();
	});

	/**
	 * toggle multiple choice box
	 */
	$(document).on("change", "#jsAddQuestionType", function () {
		//
		$(".jsAddQuestionMultipleChoiceBox").addClass("hidden");
		//
		if ($(this).val() === "multiple_choice") {
			$(".jsAddQuestionMultipleChoiceBox").removeClass("hidden");
		}
	});

	/**
	 * handle video toggle
	 */
	$(document).on("change", ".jsAddQuestionVideoType", function () {
		//
		$(".jsAddQuestionUploadVideoBox").addClass("hidden");
		$(".jsAddQuestionRecordVideoBox").addClass("hidden");
		$(".jsAddQuestionLinkVideoBox").addClass("hidden");
		//
		videoFileRef.close();
		//
		if ($(this).val() === "upload") {
			$(".jsAddQuestionUploadVideoBox").removeClass("hidden");
		} else if ($(this).val() === "record") {
			$(".jsAddQuestionRecordVideoBox").removeClass("hidden");
			videoFileRef.init();
		} else {
			$(".jsAddQuestionLinkVideoBox").removeClass("hidden");
		}
	});

	/**
	 * Handle save question
	 */
	$(document).on("click", ".jsAddQuestionSaveBtn", function (event) {
		// stop the default behavior
		event.preventDefault();
		//
		setQuestionObject();
	});

	/**
	 * load add question view
	 *
	 * @param {string} modalCode
	 * @param {reference} callback
	 */
	function loadAddQuestionView(modalCode, callback) {
		// save modal loader reference
		modalLoaderId = modalCode;
		// save callback reference
		callbackReference = callback;
		// initiate recorder
		videoFileRef = new msVideoRecorder({
			recorderPlayer: "jsAddQuestionRecordingLive",
			previewPlayer: "jsAddQuestionRecordingRecorded",
			recordButton: "jsAddQuestionRecordingStart",
			playRecordedVideoBTN: "jsAddQuestionRecordingPlay",
			pauseRecordedVideoBTN: "jsAddQuestionRecordingPause",
			resumeRecordedVideoBTN: "jsAddQuestionRecordingResume",
			removeRecordedVideoBTN: "jsAddQuestionRecordingRemove",
			errorSectionReference: "jsAddQuestionRecordingError",
		});
		//
		$("#jsAddQuestionUploadVideo").msFileUploader({
			fileLimit: "30mb",
			allowedTypes: ["mp4"],
		});
		$("#jsAddQuestionType").select2({
			minimumResultsForSearch: -1,
		});
		$("#jsAddQuestionMultipleChoiceAnswer").select2({
			minimumResultsForSearch: -1,
		});
		// reset the view
		$("#jsAddQuestionTitle").val("");
		$("#jsAddQuestionHelp").val("");
		$("#jsAddQuestionType").select("val", "text");
		$(".jsAddQuestionMultipleChoiceBox").addClass("hidden");
		$("#jsAddQuestionMultipleChoiceAnswer").select("val", "choice_1");
		$(".jsAddQuestionVideoType").prop("checked", false);
		$(".jsAddQuestionUploadVideoBox").addClass("hidden");
		$(".jsAddQuestionRecordVideoBox").addClass("hidden");
		$("#jsAddQuestionMultipleChoice1").val("");
		$("#jsAddQuestionMultipleChoice2").val("");
		$("#jsAddQuestionMultipleChoice3").val("");
		$("#jsAddQuestionMultipleChoice4").val("");
		// set default object
		questionObj = {
			question_id: 0,
			question_title: "",
			question_content: "",
			question_type: "text",
			choice_list: {},
			video_type: "upload",
			video_file_name: "",
		};
		//
		$(".jsPageBox").addClass("hidden");
		// make the view visible
		$('.jsPageBox[data-id="add_question"]').removeClass("hidden");
	}

	/**
	 *
	 * @returns
	 */
	async function setQuestionObject() {
		// set default array
		const errorArray = [];
		// set data
		questionObj.question_title = $("#jsAddQuestionTitle").val().trim();
		questionObj.question_content = $("#jsAddQuestionHelp").val().trim();
		questionObj.question_type = $("#jsAddQuestionType").select2("val");
		questionObj.choice_list = [];
		questionObj.video_type =
			$(".jsAddQuestionVideoType:checked").val() || null;
		questionObj.video_file_name = "";
		// validation
		if (!questionObj.question_title) {
			errorArray.push('"Question" field is mandatory.');
		}
		if (!questionObj.question_type) {
			errorArray.push('"Question type" field is mandatory.');
		}
		// for multiple choice
		if (questionObj.question_type === "multiple_choice") {
			//
			let choice1 = $("#jsAddQuestionMultipleChoice1").val().trim();
			let choice2 = $("#jsAddQuestionMultipleChoice2").val().trim();
			let choice3 = $("#jsAddQuestionMultipleChoice3").val().trim();
			let choice4 = $("#jsAddQuestionMultipleChoice4").val().trim();
			let rightChoice = $("#jsAddQuestionMultipleChoiceAnswer").select2(
				"val"
			);
			questionObj.choice_list = {
				choice1,
				choice2,
				choice3,
				choice4,
				rightChoice,
			};

			//
			if (!choice1) {
				errorArray.push('"Choice one" is mandatory.');
			}
			//
			if (!choice2) {
				errorArray.push('"Choice two" is mandatory.');
			}
			//
			if (!choice3) {
				errorArray.push('"Choice three" is mandatory.');
			}
			//
			if (!choice4) {
				errorArray.push('"Choice four" is mandatory.');
			}
			//
			if (!rightChoice) {
				errorArray.push('"Correct choice" is mandatory.');
			}
		}
		if (!questionObj.video_type) {
			errorArray.push('"Video type" field is mandatory.');
		}
		let fileObject = {};
		let fileStream = {};
		let doUpdated = false;
		// check for video type
		if (questionObj.video_type === "upload") {
			// get the uploaded file
			fileObject = $("#jsAddQuestionUploadVideo").msFileUploader("get");
			//
			if (!Object.keys(fileObject).length) {
				errorArray.push(
					'"Video file" is mandatory. Please upload a file.'
				);
			} else if (fileObject.errorCode) {
				errorArray.push(fileObject.errorCode);
			}
			//
			doUpdated = true;
		} else if (questionObj.video_type === "record") {
			// get the recorded stream
			fileStream = await videoFileRef.getVideo();
			// no recording found
			if (fileStream.length === 5) {
				errorArray.push(
					'"Video Recording" is mandatory. Please record a video.'
				);
			}
			//
			doUpdated = true;
		} else if (questionObj.video_type === "link") {
			questionObj.video_file_name = $("#jsAddQuestionLink").val().trim();
			if (!questionObj.video_file_name ) {
				errorArray.push("YouTube / Vimeo link is required.");
			} else if (!questionObj.video_file_name .isValidYoutubeLink() && !questionObj.video_file_name .isValidVimeoLink()) {
				errorArray.push("Invalid YouTube / Vimeo link.");
			}
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"ERROR!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		ml(true, modalLoaderId);
		//
		if (doUpdated) {
			//
			let uploadedFileObject = {};
			//
			if (questionObj.video_type === "upload") {
				uploadedFileObject = await uploadFile(fileObject);
				//
				if (typeof uploadedFileObject === "string") {
					// parse json
					uploadedFileObject = JSON.parse(uploadedFileObject);
				}
			} else {
				uploadedFileObject = await uploadStream(fileStream);
			}
			//
			//file upload failed
			if (!Object.keys(uploadedFileObject).length) {
				// hide the loader
				ml(false, modalLoaderId);
				// show error
				return alertify.alert("ERROR!", "Failed to upload file.", CB);
			}
			// saves the file name
			questionObj.video_file_name = uploadedFileObject.data;
		}	
		questionObj.question_id = Date.now();
		// close the connection
		videoFileRef.close();
		// clear the file
		$("#jsAddQuestionUploadVideo").msFileUploader("clear");
		// pass the question to callback
		callbackReference(questionObj);
	}

	// make the function available to window object
	window.loadAddQuestionView = loadAddQuestionView;
});
