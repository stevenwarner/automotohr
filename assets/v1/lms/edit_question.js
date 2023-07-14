$(function editQuestion() {
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
	$(document).on("change", "#jsEditQuestionType", function () {
		//
		$(".jsEditQuestionMultipleChoiceBox").addClass("hidden");
		//
		if ($(this).val() === "multiple_choice") {
			$(".jsEditQuestionMultipleChoiceBox").removeClass("hidden");
		}
	});

	/**
	 * handle video toggle
	 */
	$(document).on("change", ".jsEditQuestionVideoType", function () {
		//
		$(".jsEditQuestionUploadVideoBox").addClass("hidden");
		$(".jsEditQuestionRecordVideoBox").addClass("hidden");
		$(".jsEditQuestionLinkVideoBox").addClass("hidden");
		//
		videoFileRef.close();
		//
		if ($(this).val() === "upload") {
			$(".jsEditQuestionUploadVideoBox").removeClass("hidden");
		} else if ($(this).val() === "record") {
			$(".jsEditQuestionRecordVideoBox").removeClass("hidden");
			videoFileRef.init();
		} else {
			$(".jsEditQuestionLinkVideoBox").removeClass("hidden");
		}
	});

	/**
	 * Handle save question
	 */
	$(document).on("click", ".jsEditQuestionSaveBtn", function (event) {
		// stop the default behavior
		event.preventDefault();
		//
		setQuestionObject();
	});

	/**
	 * load question view
	 *
	 * @param {object} question
	 * @param {string} modalCode
	 * @param {reference} callback
	 */
	function loadEditQuestionView(question, modalCode, callback) {
		// set the question
		questionObj = question;
		// save modal loader reference
		modalLoaderId = modalCode;
		// save callback reference
		callbackReference = callback;
		// initiate recorder
		videoFileRef = new msVideoRecorder({
			recorderPlayer: "jsEditQuestionRecordingLive",
			previewPlayer: "jsEditQuestionRecordingRecorded",
			recordButton: "jsEditQuestionRecordingStart",
			playRecordedVideoBTN: "jsEditQuestionRecordingPlay",
			pauseRecordedVideoBTN: "jsEditQuestionRecordingPause",
			resumeRecordedVideoBTN: "jsEditQuestionRecordingResume",
			removeRecordedVideoBTN: "jsEditQuestionRecordingRemove",
			errorSectionReference: "jsEditQuestionRecordingError",
		});
		//
		$("#jsEditQuestionUploadVideo").msFileUploader({
			fileLimit: "30mb",
			allowedTypes: ["mp4"],
			placeholderImage: questionObj.video_file_name,
		});
		$("#jsEditQuestionType").select2({
			minimumResultsForSearch: -1,
		});
		$("#jsEditQuestionMultipleChoiceAnswer").select2({
			minimumResultsForSearch: -1,
		});
		// reset the view
		$("#jsEditQuestionTitle").val(questionObj.question_title);
		$("#jsEditQuestionHelp").val(questionObj.question_content);
		$("#jsEditQuestionType").select("val", questionObj.question_type);
		$('.jsEditQuestionVideoType[value="'+questionObj.video_type+'"]').prop("checked", true);
		//
		if (questionObj.video_type === 'link') {
			$('.jsEditQuestionVideoType[value="'+questionObj.video_type+'"]').prop("checked", true);
			$(".jsEditQuestionUploadVideoBox").addClass("hidden");
			$(".jsEditQuestionRecordVideoBox").addClass("hidden");
			$(".jsEditQuestionLinkVideoBox").removeClass("hidden");
			$("#jsEditQuestionLink").val(questionObj.video_file_name);
		} else {
			$('.jsEditQuestionVideoType[value="upload"]').prop("checked", true);
			$(".jsEditQuestionRecordVideoBox").addClass("hidden");
			$(".jsEditQuestionUploadVideoBox").removeClass("hidden");
		}
		//
		$(".jsEditQuestionMultipleChoiceBox").addClass("hidden");
		$("#jsEditQuestionMultipleChoiceAnswer").select("val", "choice_1");
		$("#jsEditQuestionMultipleChoice1").val("");
		$("#jsEditQuestionMultipleChoice2").val("");
		$("#jsEditQuestionMultipleChoice3").val("");
		$("#jsEditQuestionMultipleChoice4").val("");
		//
		if (questionObj.question_type === "multiple_choice") {
			$(".jsEditQuestionMultipleChoiceBox").removeClass("hidden");
			$("#jsEditQuestionMultipleChoiceAnswer").select(
				"val",
				questionObj.choice_list.rightChoice
			);
			$("#jsEditQuestionMultipleChoice1").val(
				questionObj.choice_list.choice1
			);
			$("#jsEditQuestionMultipleChoice2").val(
				questionObj.choice_list.choice2
			);
			$("#jsEditQuestionMultipleChoice3").val(
				questionObj.choice_list.choice3
			);
			$("#jsEditQuestionMultipleChoice4").val(
				questionObj.choice_list.choice4
			);
		}
		//
		$(".jsPageBox").addClass("hidden");
		// make the view visible
		$('.jsPageBox[data-id="edit_question"]').removeClass("hidden");
	}

	/**
	 *
	 * @returns
	 */
	async function setQuestionObject() {
		// set default array
		const errorArray = [];
		// set data
		questionObj.question_title = $("#jsEditQuestionTitle").val().trim();
		questionObj.question_content = $("#jsEditQuestionHelp").val().trim();
		questionObj.question_type = $("#jsEditQuestionType").select2("val");
		questionObj.choice_list = [];
		questionObj.video_type =
			$(".jsEditQuestionVideoType:checked").val() || null;
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
			let choice1 = $("#jsEditQuestionMultipleChoice1").val().trim();
			let choice2 = $("#jsEditQuestionMultipleChoice2").val().trim();
			let choice3 = $("#jsEditQuestionMultipleChoice3").val().trim();
			let choice4 = $("#jsEditQuestionMultipleChoice4").val().trim();
			let rightChoice = $("#jsEditQuestionMultipleChoiceAnswer").select2(
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
			fileObject = $("#jsEditQuestionUploadVideo").msFileUploader("get");
			//
			if (!Object.keys(fileObject).length) {
				if (!questionObj.video_file_name) {
					errorArray.push(
						'"Video file" is mandatory. Please upload a file.'
					);
				}
			} else {
				if (fileObject.errorCode) {
					errorArray.push(fileObject.errorCode);
				}
				doUpdated = true;
			}
		} else if (questionObj.video_type === "record") {
			// get the recorded stream
			fileStream = await videoFileRef.getVideo();
			// no recording found
			if (fileStream.length === 5 && !questionObj.video_file_name) {
				errorArray.push(
					'"Video Recording" is mandatory. Please record a video.'
				);
			} else {
				doUpdated = true;
			}
		} else if (questionObj.video_type === "link") {
			questionObj.video_file_name = $("#jsEditQuestionLink").val().trim();
			if (!questionObj.video_file_name ) {
				errorArray.push("YouTube / Vimeo link is required.");
			} else if (!questionObj.video_file_name .isValidYoutubeLink() && !questionObj.video_file_name .isValidVimeoLink()) {
				errorArray.push("Invalid YouTube / Vimeo link.");
			}
			//
			doUpdated = false;
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
		// close the connection
		videoFileRef.close();
		// clear the file
		$("#jsEditQuestionUploadVideo").msFileUploader("clear");
		// pass the question to callback
		console.log(questionObj)
		callbackReference(questionObj);
	}

	// make the function available to window object
	window.loadEditQuestionView = loadEditQuestionView;
});
