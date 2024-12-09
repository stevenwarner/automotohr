$(function editQuestion() {
	// set default question object
	let questionObj = {
		question_id: 0,
		question_title: "",
		question_required: false,
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
		$(".jsEditQuestionPlace").addClass("hidden");
		//
		if (
			$(this).val() === "multiple_choice" ||
			$(this).val() === "single_choice"
		) {
			$(".jsEditQuestionChoice").removeClass("hidden");
		} else if ($(this).val() === "yes_no") {
			$(".jsEditQuestionYesNo").removeClass("hidden");
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
		} else if ($(this).val() === "link") {
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
	 * add answer row
	 */
	$(document).on("click", ".jsEditChoiceAnswer", function (event) {
		// stop the default functionality
		event.preventDefault();
		//
		let choiceAnswer = `
		<!-- secondary question row -->
		<div class="csEditChoiceRow">
			<div class="row">
				<div class="col-xs-12 col-sm-5">
					<label>Answer Choice <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control jsEditQuestionChoiceAnswer" />
				</div>
				<div class="col-xs-12 col-sm-3">
					<label>Question Score <strong class="text-danger">*</strong></label>
					<select class="form-control jsEditQuestionChoiceAnswerScore">
						<option value="0">Not acceptable - 0</option>
						<option value="1">Acceptable - 1</option>
						<option value="2">Good - 2</option>
						<option value="3">Very Good - 3</option>
						<option value="4">Excellent - 4</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-2">
					<label>Status <strong class="text-danger">*</strong></label>
					<select class="form-control jsEditQuestionChoiceAnswerStatus">
						<option value="pass">Pass</option>
						<option value="fail">Fail</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-1">
					<p>&nbsp;</p>
					<button class="btn btn-danger jsEditDeleteChoiceAnswer" type="button" title="Remove answer" placement="top">
						<i class="fa fa-times-circle" aria-hidden="true"></i>
					</button>
				</div>
			</div>
			<br />
		</div>
				`;
		// call the function
		$(".csEditChoiceBox").append(choiceAnswer);
	});

	/**
	 * remove answer row
	 */
	$(document).on("click", ".jsEditDeleteChoiceAnswer", function (event) {
		// stop the default functionality
		event.preventDefault();
		// call the function
		$(this).closest(".csChoiceRowEdit").remove();
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
			allowedTypes: ["mp4", "mov"],
			placeholderImage: questionObj.video_file_name,
		});

		$(
			'#jsEditQuestionType option[value="' +
				questionObj.question_type +
				'"]'
		).prop("selected", true);
		$("#jsEditQuestionType").select2({
			minimumResultsForSearch: -1,
		});
		$("#jsEditQuestionMultipleChoiceAnswer").select2({
			minimumResultsForSearch: -1,
		});
		// reset the view
		$("#jsEditQuestionTitle").val(questionObj.question_title);
		$("#jsEditQuestionRequired").prop(
			"checked",
			questionObj.question_required
		);
		$("#jsEditQuestionHelp").val(questionObj.question_content);
		//
		$(
			'.jsEditQuestionVideoType[value="' + questionObj.video_type + '"]'
		).prop("checked", true);

		//
		if (questionObj.video_type === "none") {
			$(
				'.jsEditQuestionVideoType[value="' +
					questionObj.video_type +
					'"]'
			).prop("checked", true);
			$(".jsEditQuestionUploadVideoBox").addClass("hidden");
			$(".jsEditQuestionRecordVideoBox").addClass("hidden");
			$(".jsEditQuestionLinkVideoBox").addClass("hidden");
			$("#jsEditQuestionLink").val(questionObj.video_file_name);
		} else if (questionObj.video_type === "link") {
			$(
				'.jsEditQuestionVideoType[value="' +
					questionObj.video_type +
					'"]'
			).prop("checked", true);
			$(".jsEditQuestionUploadVideoBox").addClass("hidden");
			$(".jsEditQuestionRecordVideoBox").addClass("hidden");
			$(".jsEditQuestionLinkVideoBox").removeClass("hidden");
			$("#jsEditQuestionLink").val(questionObj.video_file_name);
		} else {
			$('.jsEditQuestionVideoType[value="upload"]').prop("checked", true);
			$(".jsEditQuestionRecordVideoBox").addClass("hidden");
			$(".jsEditQuestionUploadVideoBox").removeClass("hidden");
		}
		// yes and no
		$('#jsEditQuestionYesNoYSelect option[value="0"]').prop(
			"selected",
			true
		);
		$('#jsEditQuestionYesNoYStatus option[value="pass"]').prop(
			"selected",
			true
		);
		$('#jsEditQuestionYesNoNSelect option[value="0"]').prop(
			"selected",
			true
		);
		$('#jsEditQuestionYesNoNStatus option[value="pass"]').prop(
			"selected",
			true
		);
		// choice
		$(".csEditChoiceBox").html("");
		$(".jsEditQuestionChoiceAnswer").val("");
		$('#jsEditQuestionChoiceAnswerScore option[value="0"]').prop(
			"selected",
			true
		);
		$('#jsEditQuestionChoiceAnswerStatus option[value="pass"]').prop(
			"selected",
			true
		);
		//
		$(".jsEditQuestionPlace").addClass("hidden");
		//
		if (questionObj.question_type === "yes_no") {
			$(".jsEditQuestionYesNo").removeClass("hidden");
			$(
				'#jsEditQuestionYesNoYSelect option[value="' +
					questionObj.choice_list["yes"]["score"] +
					'"]'
			).prop("selected", true);
			$(
				'#jsEditQuestionYesNoYStatus option[value="' +
					questionObj.choice_list["yes"]["status"] +
					'"]'
			).prop("selected", true);
			$(
				'#jsEditQuestionYesNoNSelect option[value="' +
					questionObj.choice_list["no"]["score"] +
					'"]'
			).prop("selected", true);
			$(
				'#jsEditQuestionYesNoNStatus option[value="' +
					questionObj.choice_list["no"]["status"] +
					'"]'
			).prop("selected", true);
		} else if (
			questionObj.question_type === "single_choice" ||
			questionObj.question_type === "multiple_choice"
		) {
			$(".jsEditQuestionChoice").removeClass("hidden");
			//
			$(".jsEditQuestionChoiceAnswerPrimary").val(
				questionObj.choice_list[0]["answer_choice"]
			);
			//
			$(
				'.jsEditQuestionChoiceAnswerPrimaryScore option[value="' +
					questionObj.choice_list[0]["answer_score"] +
					'"]'
			).prop("selected", true);
			//
			$(
				'.jsEditQuestionChoiceAnswerPrimaryStatus option[value="' +
					questionObj.choice_list[0]["answer_status"] +
					'"]'
			).prop("selected", true);

			//
			for (const index in questionObj.choice_list) {
				//
				if (index != 0) {
					$(".csEditChoiceBox").append(`
						<!-- secondary question row -->
						<div class="csEditChoiceRow">
							<div class="row">
								<div class="col-xs-12 col-sm-5">
									<label>Answer Choice <strong class="text-danger">*</strong></label>
									<input type="text" class="form-control jsEditQuestionChoiceAnswer" value="${
										questionObj.choice_list[index][
											"answer_choice"
										]
									}" />
								</div>
								<div class="col-xs-12 col-sm-3">
									<label>Question Score <strong class="text-danger">*</strong></label>
									<select class="form-control jsEditQuestionChoiceAnswerScore">
										<option ${
											questionObj.choice_list[index][
												"answer_score"
											] === "0"
												? "selected"
												: ""
										} value="0">Not acceptable - 0</option>
										<option ${
											questionObj.choice_list[index][
												"answer_score"
											] === "1"
												? "selected"
												: ""
										} value="1">Acceptable - 1</option>
										<option ${
											questionObj.choice_list[index][
												"answer_score"
											] === "2"
												? "selected"
												: ""
										} value="2">Good - 2</option>
										<option ${
											questionObj.choice_list[index][
												"answer_score"
											] === "3"
												? "selected"
												: ""
										} value="3">Very Good - 3</option>
										<option ${
											questionObj.choice_list[index][
												"answer_score"
											] === "4"
												? "selected"
												: ""
										} value="4">Excellent - 4</option>
									</select>
								</div>
								<div class="col-xs-12 col-sm-2">
									<label>Status <strong class="text-danger">*</strong></label>
									<select class="form-control jsEditQuestionChoiceAnswerStatus">
										<option ${
											questionObj.choice_list[index][
												"answer_status"
											] === "pass"
												? "selected"
												: ""
										} value="pass">Pass</option>
										<option ${
											questionObj.choice_list[index][
												"answer_status"
											] === "fail"
												? "selected"
												: ""
										} value="fail">Fail</option>
									</select>
								</div>
								<div class="col-xs-12 col-sm-1">
									<p>&nbsp;</p>
									<button class="btn btn-danger jsEditDeleteChoiceAnswer" type="button" title="Remove answer" placement="top">
										<i class="fa fa-times-circle" aria-hidden="true"></i>
									</button>
								</div>
							</div>
							<br />
						</div>
				`);
				}
			}
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
		questionObj.question_required = $("#jsEditQuestionRequired").prop(
			"checked"
		);
		questionObj.question_title = $("#jsEditQuestionTitle").val().trim();
		questionObj.question_content = $("#jsEditQuestionHelp").val().trim();
		questionObj.question_type = $("#jsEditQuestionType").select2("val");
		questionObj.choice_list = {};
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
		if (
			questionObj.question_type === "single_choice" ||
			questionObj.question_type === "multiple_choice"
		) {
			//
			$(".csEditChoiceRow").map(function (i) {
				const obj = {
					answer_choice: $(this)
						.find(".jsEditQuestionChoiceAnswer")
						.val()
						.trim(),
					answer_score: $(this)
						.find(".jsEditQuestionChoiceAnswerScore")
						.val()
						.trim(),
					answer_status: $(this)
						.find(".jsEditQuestionChoiceAnswerStatus")
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
				score: $("#jsEditQuestionYesNoYSelect option:checked").val(),
				status: $("#jsEditQuestionYesNoYStatus option:checked").val(),
			};
			//
			questionObj.choice_list["no"] = {
				score: $("#jsEditQuestionYesNoNSelect option:checked").val(),
				status: $("#jsEditQuestionYesNoNStatus option:checked").val(),
			};
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
			if (!questionObj.video_file_name) {
				errorArray.push("YouTube / Vimeo link is required.");
			} else if (
				!questionObj.video_file_name.isValidYoutubeLink() &&
				!questionObj.video_file_name.isValidVimeoLink()
			) {
				errorArray.push("Invalid YouTube / Vimeo link.");
			}
		} else {
			questionObj.video_file_name = "none";
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
			if (questionObj.video_type != "none") {
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
					return alertify.alert(
						"ERROR!",
						"Failed to upload file.",
						CB
					);
				}
				// saves the file name
				questionObj.video_file_name = uploadedFileObject.data;
			}
		}
		// close the connection
		videoFileRef.close();
		// clear the file
		$("#jsEditQuestionUploadVideo").msFileUploader("clear");
		// pass the question to callback
		callbackReference(questionObj);
	}

	// make the function available to window object
	window.loadEditQuestionView = loadEditQuestionView;
});
