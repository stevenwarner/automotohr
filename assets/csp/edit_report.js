$(function Overview() {
	let externalPointer = $(".jsAddExternalBody .jsEER").length;
	let XHR = null;

	const filesObject = {
		document: {},
		audio: {},
	};
	const config = {
		document: {
			allowedTypes: [
				// images
				"jpg",
				"jpeg",
				"png",
				"gif",
				"webp",
				// documents
				"rtf",
				"pdf",
				"doc",
				"docx",
				"pptx",
				"ppt",
				"xls",
				"xlsx",
				"csv",
			],
			fileLimit: "200mb",
			onSuccess: function (file) {
				filesObject.document = file;
			},
		},
		audio: {
			allowedTypes: [
				// video
				"mov",
				"mp4",
				"webm",
				// audio
				"wav",
				"mp3",
			],
			fileLimit: "200mb",
			allowLinks: true,
			onSuccess: function (file) {
				filesObject.audio = file;
			},
		},
	};

	$("#report_documents").msFileUploader(
		config.document
	);
	// $("#report_video").msFileUploader(config.audio);

	if (externalPointer != 0) {
		$(".jsAddExternalBody .jsEER").each(function (i) {
			$(`input[name="external_employees[${i}]['name']"]`).rules("add", {
				required: true,
				messages: {
					required: "Please enter the name",
				},
			});
			$(`input[name="external_employees[${i}]['email']"]`).rules("add", {
				required: true,
				email: true,
				messages: {
					required: "Please enter the email",
					email: "Please enter a valid email address",
				},
			});
		});
	}
	//
	$("#report_status").select2({
		minimumResultsForSearch: -1,
	});
	$("#report_note_type").select2({
		minimumResultsForSearch: -1,
	});

	CKEDITOR.replace("report_note");

	$("#report_date").datetimepicker({
		format: "m/d/Y",
		datepicker: true,
		timepicker: false,
		changeYear: true,
		changeMonth: true,
	});

	$("#report_completion_date").datetimepicker({
		format: "m/d/Y",
		datepicker: true,
		timepicker: false,
		changeYear: true,
		changeMonth: true,
	});

	//
	$("#jsAddReportForm").validate({
		rules: {
			report_title: {
				required: true,
			},
			report_date: {
				required: true,
			},
		},
		messages: {
			report_title: {
				required: "Please enter report title",
			},
			report_date: {
				required: "Please select report date",
			},
		},
		submitHandler: function (form) {
			handleFormSubmission(form);
		},
	});

	$(".jsAddExternalEmployee").click(function (event) {
		event.preventDefault();
		$(".jsAddExternalBody .alert").remove();
		generateExternalEmployees();
	});

	$(document).on("click", ".jsRemoveExternalEmployee", function () {
		const external = $(this).closest(".row");
		if (external.data("id")) {
			return confirmAndDeleteFromServer(external);
		}
		alertify.confirm(
			"Are you sure you want to remove this external employee?",
			function () {
				$(
					`input[name="external_employees[${external.data(
						"external"
					)}]['name']"]`
				).rules("remove");
				$(
					`input[name="external_employees[${external.data(
						"external"
					)}]['email']"]`
				).rules("remove");
				external.remove();
				externalPointer--;

				if (externalPointer == 0) {
					$(".jsAddExternalBody").html(
						' <div class="alert alert-info text-center">No External employees found</div>'
					);
				}
			}
		);
	});

	$(".jsAddNote").click(function (event) {
		event.preventDefault();
		const obj = {
			type: $("#report_note_type").val(),
			content: CKEDITOR.instances["report_note"].getData(),
		};
		//
		if (obj.content.trim() === "") {
			_error("Please enter a note.");
			return;
		}
		//
		addNoteToReport(obj);
	});

	$(".jsAddDocument").click(function (event) {
		event.preventDefault();

		const obj = {
			title: $("#document_title").val(),
			file: filesObject.document,
		};
		//
		if (obj.title.trim() === "") {
			_error("Please enter a document title.");
			return;
		}
		if (!obj.file) {
			_error("Please select a document.");
			return;
		}
		if (Object.keys(obj.file).length === 0) {
			_error("Please select a document.");
			return;
		}
		if (obj.file.hasError) {
			_error(obj.file.errorCode);
			return;
		}
		//
		attachFileToReport(obj.file, obj.title, "document");
	});

	$(".jsAddVideo").click(function (event) {
		event.preventDefault();

		const obj = {
			title: $("#video_title").val(),
			file: filesObject.audio,
		};
		//
		if (obj.title.trim() === "") {
			_error("Please enter a document title.");
			return;
		}
		if (!obj.file) {
			_error("Please select a document.");
			return;
		}
		if (Object.keys(obj.file).length === 0) {
			_error("Please select a document.");
			return;
		}
		if (obj.file.hasError && obj.file.type === "vimeo") {
			_error("Vimeo link is invalid.");
			return;
		}
		if (obj.file.hasError && obj.file.type === "youtube") {
			_error("YouTube link is invalid.");
			return;
		}
		if (obj.file.hasError) {
			_error(obj.file.errorCode);
			return;
		}
		//
		attachFileToReport(obj.file, obj.title, "audio");
	});

	$(document).on("click", ".jsViewFile", function (event) {
		event.preventDefault();
		const fileId = $(this).closest(".jsFileBox").data("id");
		Modal(
			{
				Id: "jsFileViewModal",
				Loader: "jsFileViewModalLoader",
				Title: '<span id="jsFileViewModalTitle"></span>',
				Body: '<div id="jsFileViewModalBody"></body>',
			},
			function () {
				loadView(fileId);
			}
		);
	});

	function generateExternalEmployees() {
		let html = `
        <div class="row" data-external="${externalPointer}">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="external_employee_name">Name</label>
                    <input type="text" name="external_employees_names[${externalPointer}]['name']" class="form-control" required>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="external_employee_email">Email</label>
                    <input type="email" name="external_employees_emails[${externalPointer}]['email']" class="form-control" required>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-red btn-block jsRemoveExternalEmployee">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        `;

		externalPointer++;
		$(".jsAddExternalBody").append(html);
		$(
			`input[name="external_employees[${externalPointer - 1}]['name']"]`
		).rules("add", {
			required: true,
			messages: {
				required: "Please enter the name",
			},
		});
		$(
			`input[name="external_employees[${externalPointer - 1}]['email']"]`
		).rules("add", {
			required: true,
			email: true,
			messages: {
				required: "Please enter the email",
				email: "Please enter a valid email address",
			},
		});
		return html;
	}

	function confirmAndDeleteFromServer(external) {
		_confirm(
			"Are you sure you want to remove this external employee? It will be removed from this report permanently as well.",
			function () {
				deleteExternalEmployee(external);
			}
		);
	}

	function handleFormSubmission(form) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/edit/" + getSegment(2)
				),
				method: "POST",
				data: $(form).serializeArray(),
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
						window.location.refresh();
					});
				});
		}
	}

	function addNoteToReport(obj) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/notes/" + getSegment(2) + "/0"
				),
				method: "POST",
				data: obj,
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
						window.location.refresh();
					});
				});
		}
	}

	function loadView(fileId) {
		$.ajax({
			url: baseUrl("compliance_safety_reporting/file/view/" + fileId),
			method: "GET",
		})
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsFileViewModalBody").html(resp.view);
				$("#jsFileViewModalTitle").html(resp.data.title);
				ml(false, "jsFileViewModalLoader");
			});
	}

	function deleteExternalEmployee(external) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/" +
						getSegment(2) +
						"/" +
						external.data("id")
				),
				method: "DELETE",
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
						$(
							`input[name="external_employees[${external.data(
								"external"
							)}]['name']"]`
						).rules("remove");
						$(
							`input[name="external_employees[${external.data(
								"external"
							)}]['email']"]`
						).rules("remove");
						external.remove();
						externalPointer--;

						if (externalPointer == 0) {
							$(".jsAddExternalBody").html(
								' <div class="alert alert-info text-center">No External employees found</div>'
							);
						}
					});
				});
		}
	}

	async function attachFileToReport(file, title, type) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			const formData = new FormData();
			formData.append("title", title);
			formData.append("type", type);

			if (file.type === "youtube" || file.type === "vimeo") {
				formData.append("link", "link");
				formData.append("file_type", file.type);
			} else {
				formData.append("file", file);
			}
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/file/" +
						getSegment(2) +
						"/0/" +
						type
				),
				method: "POST",
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
						$("#document_title").val("");
						$("#report_documents").msFileUploader("clear");

						if (
							$(".jsDocumentsArea").find(".jsFirst").length != 0
						) {
							$(".jsDocumentsArea")
								.find(".jsFirst")
								.append(resp.view);
						} else {
							$(".jsDocumentsArea").html(
								`<div class="row jsFirst">${resp.view}</div>`
							);
						}
					});
				});
		}
	}

	ml(false, "jsPageLoader");
});
