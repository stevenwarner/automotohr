$(function addQuestion() {
	let btnHTML;
	// set default question object
	let questionObj = {
		question_id: 0,
		question_required: false,
		is_required: false,
		question_title: "",
		question_content: "",
		question_type: "text",
		choice_list: {},
		video_type: "none",
		video_file_name: "",
	};

	//
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
		$(".jsAddQuestionPlace").addClass("hidden");
		//
		if (
			$(this).val() === "multiple_choice" ||
			$(this).val() === "single_choice"
		) {
			$(".jsAddQuestionChoice").removeClass("hidden");
		} else if ($(this).val() === "yes_no") {
			$(".jsAddQuestionYesNo").removeClass("hidden");
		} else if ($(this).val() === "rating") {
			$(".jsAddQuestionRating").removeClass("hidden");
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
		} else if ($(this).val() != "none") {
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
	 * add answer row
	 */
	$(document).on("click", ".jsAddChoiceAnswer", function (event) {
		// stop the default functionality
		event.preventDefault();
		//
		let choiceAnswer = `
		<!-- secondary question row -->
		<div class="csChoiceRow">
			<div class="row">
				<div class="col-xs-12 col-sm-5">
					<label>Answer Choice <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control jsAddQuestionChoiceAnswer" />
				</div>
				<div class="col-xs-12 col-sm-3">
					<label>Question Score <strong class="text-danger">*</strong></label>
					<select class="form-control jsAddQuestionChoiceAnswerScore">
						<option value="0">Not acceptable - 0</option>
						<option value="1">Acceptable - 1</option>
						<option value="2">Good - 2</option>
						<option value="3">Very Good - 3</option>
						<option value="4">Excellent - 4</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-2">
					<label>Status <strong class="text-danger">*</strong></label>
					<select class="form-control jsAddQuestionChoiceAnswerStatus">
						<option value="pass">Pass</option>
						<option value="fail">Fail</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-1">
					<p>&nbsp;</p>
					<button class="btn btn-danger jsDeleteChoiceAnswer" type="button" title="Remove answer" placement="top">
						<i class="fa fa-times-circle" aria-hidden="true"></i>
					</button>
				</div>
			</div>
			<br />
		</div>
				`;
		// call the function
		$(".csChoiceBox").append(choiceAnswer);
	});

	/**
	 * remove answer row
	 */
	$(document).on("click", ".jsDeleteChoiceAnswer", function (event) {
		// stop the default functionality
		event.preventDefault();
		// call the function
		$(this).closest(".csChoiceRow").remove();
	});

	/**
	 * load add question view
	 *
	 * @param {string} modalCode
	 * @param {reference} callback
	 */
	function loadAddQuestionView(callback) {
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
			fileLimit: "200mb",
			allowedTypes: ["mp4", "mov"],
		});
		$("#jsAddQuestionType").select2({
			minimumResultsForSearch: -1,
		});
		// reset the view
		$("#jsAddQuestionTitle").val("");
		$("#jsAddQuestionHelp").val("");
		$("#jsAddQuestionRequired").prop("checked", false);
		$("#jsAddQuestionType").select2("val", "text");
		$(".jsAddQuestionPlace").addClass("hidden");
		// yes and no
		$('#jsAddQuestionYesNoYSelect option[value="0"]').prop(
			"selected",
			true
		);
		$('#jsAddQuestionYesNoYStatus option[value="pass"]').prop(
			"selected",
			true
		);
		$('#jsAddcallbackReferenceQuestionYesNoNSelect option[value="0"]').prop(
			"selected",
			true
		);
		$('#jsAddQuestionYesNoNStatus option[value="pass"]').prop(
			"selected",
			true
		);
		// choice
		$(".jsAddQuestionChoiceAnswer").val("");
		$(".csChoiceBox").html("");
		$('#jsAddQuestionChoiceAnswerScore option[value="0"]').prop(
			"selected",
			true
		);
		$('#jsAddQuestionChoiceAnswerStatus option[value="pass"]').prop(
			"selected",
			true
		);
		//
		$(".jsAddQuestionVideoType").prop("checked", false);
		$(".jsAddQuestionUploadVideoBox").addClass("hidden");
		$(".jsAddQuestionRecordVideoBox").addClass("hidden");
		$(".jsAddQuestionLinkVideoBox").addClass("hidden");
		$("#jsAddQuestionLink").val("");
		// set default object
		questionObj = {
			question_id: 0,
			question_title: "",
			question_required: false,
			is_required: false,
			question_content: "",
			question_type: "text",
			choice_list: {},
			video_type: "upload",
			video_file_name: "",
		};
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
		questionObj.question_required = $("#jsAddQuestionRequired").prop(
			"checked"
		);
		questionObj.is_required = $("#jsAddQuestionRequired").prop(
			"checked"
		);
		questionObj.question_type = $("#jsAddQuestionType").select2("val");
		questionObj.choice_list = {};
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
		if (
			questionObj.question_type === "single_choice" ||
			questionObj.question_type === "multiple_choice"
		) {
			//
			$(".csChoiceRow").map(function (i) {
				//
				const obj = {
					answer_choice: $(this)
						.find(".jsAddQuestionChoiceAnswer")
						.val()
						.trim(),
					answer_score: $(this)
						.find(".jsAddQuestionChoiceAnswerScore")
						.val()
						.trim(),
					answer_status: $(this)
						.find(".jsAddQuestionChoiceAnswerStatus")
						.val()
						.trim(),
				};
				// validation
				if (!obj.answer_choice) {
					errorArray.push(
						'"Answer Choice" is missing for row ' + (i + 1) + "."
					);
				}
				//
				questionObj.choice_list[i] = obj;
			});
		} else if (questionObj.question_type === "yes_no") {
			//
			questionObj.choice_list["yes"] = {
				score: $("#jsAddQuestionYesNoYSelect option:checked").val(),
				status: $("#jsAddQuestionYesNoYStatus option:checked").val(),
			};
			//
			questionObj.choice_list["no"] = {
				score: $("#jsAddQuestionYesNoNSelect option:checked").val(),
				status: $("#jsAddQuestionYesNoNStatus option:checked").val(),
			};
		} else if (questionObj.question_type === "rating") {
			//
			questionObj.choice_list = {
				rating: $("#jsAddQuestionRatingScale option:checked").val(),
				min: $("#jsAddQuestionRatingMinText").val() || "Very poor",
				max: $("#jsAddQuestionRatingMaxText").val() || "Very good",
			};
		}
		//
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
			if (!questionObj.video_file_name) {
				errorArray.push("YouTube / Vimeo link is required.");
			} else if (
				!questionObj.video_file_name.isValidYoutubeLink() &&
				!questionObj.video_file_name.isValidVimeoLink()
			) {
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
		// TODO show loader
		btnHTML = callButtonHook(
			$(".jsAddQuestionSaveBtn"),
			true
		);
		//
		if (doUpdated) {
			//
			let uploadedFileObject = {};
			//
			if (questionObj.video_type === "upload") {
				uploadedFileObject = await uploadQuestionFile(fileObject);
				//
				if (typeof uploadedFileObject === "string") {
					// parse json
					uploadedFileObject = JSON.parse(uploadedFileObject);
				}
			} else {
				uploadedFileObject = await uploadQuestionStream(fileStream);
			}
			//
			//file upload failed
			if (!Object.keys(uploadedFileObject).length) {
				// hide the loader
				callButtonHook(btnHTML, false);
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
		callButtonHook(btnHTML, false);
		// pass the question to callback
		callbackReference(questionObj);
	}

	/**
	 * Upload file to the server
	 * @param {*} fileObject
	 * @returns
	 */
	function uploadQuestionFile(fileObject) {
		// create form instance
		const formData = new FormData();
		// set the file object
		formData.append("file", fileObject);

		return makeSecureCallToApiServer(
			"uploader",
			{
				method: "POST",
				timeout: 0,
				processData: false,
				mimeType: "multipart/form-data",
				contentType: false,
				data: formData,
			}
		);
	}

	/**
	 * Upload stream to the server
	 * @param {*} streamData
	 * @returns
	 */
	function uploadQuestionStream(streamData) {

		return makeSecureCallToApiServer(
			"uploader/stream",
			{
				method: "POST",
				timeout: 0,
				contentType: "application/json",
				data: JSON.stringify({ stream: streamData }),
			}
		);
	}

	// make the function available to window object
	window.loadAddQuestionView = loadAddQuestionView;
});
