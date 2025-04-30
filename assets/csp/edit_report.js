$(function Overview() {
	let externalPointer = $(".jsAddExternalBody .jsEER").length;
	let XHR = null;
	let issue = 0;
	let cspReportId = 0;
    let cspIncidentId = 0;
    let cspIssueId = 0;
    let fileUploaderReference = {};

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
			allowCapture: true,
		},
	};

	$("#report_documents").msFileUploader(config.document);

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
	$("#jsReportIncidentType").select2({});

	CKEDITOR.replace("report_note");

	$("#report_date").datetimepicker({
		format: "m/d/Y",
		datepicker: true,
		timepicker: false,
		changeYear: true,
		changeMonth: true,
	});

	$(".start_date").datetimepicker({
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

	$(document).on("click", ".jsDeleteReportIncident", function () {
		const id = $(this).closest("tr").data("id");
		alertify.confirm(
			"Are you sure you want to remove this incident type?",
			function () {
				removeReportIncidentById(id);
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

	$(".jsAddIncident").click(function (event) {
		event.preventDefault();
		const obj = {
			type: $("#jsReportIncidentType").val(),
		};
		//
		if (obj.type == "0") {
			_error("Please select an incident type.");
			return;
		}
		//
		addIncidentToReport(obj.type);
	});

	$(".jsAddDocument").click(function (event) {
		event.preventDefault();

		const obj = {
			title: $("#document_title").val(),
			file: $("#report_documents").msFileUploader("get"),
		};
		//
		if (obj.title.trim() === "") {
			_error("Please enter a file title.");
			return;
		}
		if (!obj.file) {
			_error("Please select a file.");
			return;
		}
		if (Object.keys(obj.file).length === 0) {
			_error("Please select a file.");
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

		let fileType =
			obj.file.type === "vimeo" || obj.file.type === "youtube"
				? "link"
				: getFileType(obj.file).toLowerCase();
		//
		attachFileToReport(obj.file, obj.title, fileType);
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

	function addIncidentToReport(incidentId) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/report/" +
						getSegment(2) +
						"/incident"
				),
				method: "POST",
				data: {
					incidentId: incidentId,
				},
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

	function removeReportIncidentById(incidentId) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/incident/" + incidentId
				),
				method: "delete",
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
				formData.append("link", file.link);
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

						//
						if (
							type === "link" ||
							type === "audio" ||
							type === "video"
						) {
							if (
								$(".jsAudioArea").find(".jsFirst").length != 0
							) {
								$(".jsAudioArea")
									.find(".jsFirst")
									.append(resp.view);
							} else {
								$(".jsAudioArea").html(
									`<div class="row jsFirst">${resp.view}</div>`
								);
							}
						} else {
							if (
								$(".jsDocumentsArea").find(".jsFirst").length !=
								0
							) {
								$(".jsDocumentsArea")
									.find(".jsFirst")
									.append(resp.view);
							} else {
								$(".jsDocumentsArea").html(
									`<div class="row jsFirst">${resp.view}</div>`
								);
							}
						}
					});
				});
		}
	}

	function getFileType(file) {
		if (!file) return "Unknown"; // If no file is provided

		const fileName = file.name.toLowerCase();
		const fileType = file.type;

		let fileCategory = "file";

		// Checking based on MIME type
		if (fileType.startsWith("audio/")) {
			fileCategory = "audio";
		} else if (fileType.startsWith("video/")) {
			fileCategory = "video";
		} else if (fileType.startsWith("image/")) {
			fileCategory = "image";
		} else if (fileType.startsWith("application/")) {
			// Check for common document types
			if (
				fileType === "application/pdf" ||
				fileType.startsWith("application/msword") ||
				fileType ===
					"application/vnd.openxmlformats-officedocument.wordprocessingml.document"
			) {
				fileCategory = "document";
			}
		}

		// If MIME type is not clear, checking based on file extension
		if (fileCategory === "file") {
			if (fileName.match(/\.(pdf|doc|docx|txt|xls|xlsx|ppt|pptx)$/)) {
				fileCategory = "document";
			} else if (fileName.match(/\.(mp3|wav|ogg|flac|aac|m4a)$/)) {
				fileCategory = "audio";
			} else if (fileName.match(/\.(mp4|mkv|avi|mov|wmv|flv|webm)$/)) {
				fileCategory = "video";
			}
		}

		return fileCategory;
	}

	$(".jsSendReminderEmails").click(function (event) {
		event.preventDefault();
		_confirm(
			"Are you sure you want to send emails to the selected employees and external recipients?",
			sendEmailsForReport
		);
	});

	function sendEmailsForReport() {
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/" +
						getSegment(2) +
						"/emails/send"
				),
				method: "POST",
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message);
				});
		}
	}

	//
	$(".jsAddAddIssueBtn").click(
		function (event) {
			event.preventDefault();
			generateAndAddModalToBody();
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/issues/all"
				),
				method: "GET",
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					$("#jsAddIssueModal .modal-body").html(resp.view);
					loadViewOfIssueToProcess(
						$("#jsNewItemSelect").val()
					)
				});
		}
	);

	$(document).on("change", "#jsNewItemSelect", function () {
		loadViewOfIssueToProcess($(this).val());
	});

	$(document).on("click", ".jsAddIssue", function () {
		// set the object
		const addIssueObject = {
			reportId: getSegment(2),
			incidentId: $("#jsNewItemIncidentId").val(),
			issueId: $("#jsNewItemId").val(),
			severityLevelId: $("#jsNewItemSeverityLevel").val(),
			dynamicInputs: [],
			dynamicCheckbox: [],
		};
		//
		addIssueObject["dynamicInputs"] =  $("#jsAddIssuePanelRef")
			.find('[name="dynamicInput[]"]').length > 0 ? $("#jsAddIssuePanelRef")
			.find('[name="dynamicInput[]"]')
			.map(function () {
				return $(this).val();
			})
			.get() : [];
		//
		addIssueObject["dynamicCheckbox"] =  $("#jsAddIssuePanelRef")
			.find('[name="dynamicCheckbox[]"]') > 0 ? $("#jsAddIssuePanelRef")
			.find('[name="dynamicCheckbox[]"]')
			.map(function () {
				return $(this).val();
			})
			.get() : [];

		if (!addIssueObject.reportId) {
			_error("Please provide a report id.")
			return;
		}
		if (!addIssueObject.incidentId) {
			_error("Please provide a incident id.")
			return;
		}
		if (!addIssueObject.issueId) {
			_error("Please provide a issue id.")
			return;
		}
		if (!addIssueObject.severityLevelId) {
			_error("Please select a severity level.")
			return;
		}
		//
		addIssueToIncident(addIssueObject);
		
	});

	function loadViewOfIssueToProcess(issueId)
	{
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $
			.ajax({
				url: baseUrl("compliance_safety_reporting/issues/" + issueId),
				method: "GET",
			})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {				
				$("#jsAddIssueBox").html(resp.view)
				$("#jsAddIssueModal .jsAddIssue").removeClass("hidden");
			});
	}


	function addIssueToIncident(issueObject) {
		//
		const button = $(".jsAddIssue");
		button.prop("disabled", true).text("Adding...");

		if (XHR === null) {
			XHR = $.ajax({
				url: baseUrl(
					`compliance_safety_reporting/issue/add`
				),
				method: "POST",
				data: issueObject,
			})
				.always(function () {
					XHR = null;
					button.prop("disabled", false).text("Add Issue");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
						window.location.refresh();
					});
				});
		}
	};

	function editIssueToIncident(issueObject) {
		//
		const button = $(".jsEditModalIssue");
		button.prop("disabled", true).text("Updating...");

		if (XHR === null) {
			XHR = $.ajax({
				url: baseUrl(
					`compliance_safety_reporting/issue/edit`
				),
				method: "POST",
				data: issueObject,
			})
				.always(function () {
					XHR = null;
					button.prop("disabled", false).text("Save changes");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
						window.location.refresh();
					});
				});
		}
	};

	//
	$(".jsEditIssue").click(
		function (event) {
			event.preventDefault();

			const issueId = $(this).closest("tr").data("id");

			generateAndEditModalToBody();
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/issues/edit/"+issueId
				),
				method: "GET",
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					$("#jsEditIssueModal .modal-body").html(resp.view);
					$(".jsEditModalIssue").removeClass("hidden");
				});
		}
	);

	$(document).on("click", ".jsEditModalIssue", function () {
		// set the object
		const editIssueObject = {
			issueId: $("#jsNewItemId").val(),
			severityLevelId: $("#jsNewItemSeverityLevel").val(),
			dynamicInputs: [],
			dynamicCheckbox: [],
		};
		//
		editIssueObject["dynamicInputs"] =  $("#jsAddIssuePanelRef")
			.find('[name="dynamicInput[]"]').length > 0 ? $("#jsAddIssuePanelRef")
			.find('[name="dynamicInput[]"]')
			.map(function () {
				return $(this).val();
			})
			.get() : [];
		//
		editIssueObject["dynamicCheckbox"] =  $("#jsAddIssuePanelRef")
			.find('[name="dynamicCheckbox[]"]') > 0 ? $("#jsAddIssuePanelRef")
			.find('[name="dynamicCheckbox[]"]')
			.map(function () {
				return $(this).val();
			})
			.get() : [];

		if (!editIssueObject.issueId) {
			_error("Please provide a issue id.")
			return;
		}
		if (!editIssueObject.severityLevelId) {
			_error("Please select a severity level.")
			return;
		}
		//
		editIssueToIncident(editIssueObject);
		
	});


	$(document).on("click", ".show-status-box", function () {
		$(this).closest(".row").next().show();
	});

	$(document).on("click", ".applicant", function () {
		//
		const id = $(this).data("id");
		//
		$(this).parent().parent().parent().find(".jsSelectedPill").html(`
			<div id="jsNewItemSelectedSeverity" data-id="${id}" class="csLabelPill jsSelectedLabelPill text-center" 
			style="
			background-color: ${$(this).css(
				"background-color"
			)}; color: ${$(this).css("color")} ;">${$(this).text()}</div>
		`);

		$("#jsNewItemSeverityLevel").val(id);
	});

	$(document).on("click", ".cross", function () {
		$(this).parent().parent().css("display", "none");
	});


	function generateAndAddModalToBody()
	{
		if ($("#jsAddIssueModal").length <= 0) {
			const modal = `
			<div class=modal fade" id="jsAddIssueModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Report a new Issue</h5>
						</div>
						<div class="modal-body">
							<div class="alert alert-info text-center">Generating view please wait.</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary jsAddIssue hidden">Add Issue</button>
						</div>
					</div>
				</div>
			</div>
			`;
			$("body").append(modal);
		}
		$("#jsAddIssueModal").modal({
			backdrop: "static",
			keyboard: false,
		});
		$("#jsAddIssueModal").modal("show");
	}

	function generateAndEditModalToBody()
	{
		if ($("#jsEditIssueModal").length <= 0) {
			const modal = `
			<div class=modal fade" id="jsEditIssueModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Modify an Issue</h5>
						</div>
						<div class="modal-body">
							<div class="alert alert-info text-center">Generating view please wait.</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary jsEditModalIssue hidden">Save changes</button>
						</div>
					</div>
				</div>
			</div>
			`;
			$("body").append(modal);
		}
		$("#jsEditIssueModal").modal({
			backdrop: "static",
			keyboard: false,
		});
		$("#jsEditIssueModal").modal("show");
	}


	// Report Save
	$(".jsReportBasicUpdateBtn").click(function (event) {
		event.preventDefault();
		const reportObject = {
			title: $(".report_title").val().trim(),
			report_date: $(".report_date").val(),
			report_completion_date: $(".report_completion_date").val(),
			report_status: $(".report_status option:selected").val(),
			report_id: getSegment(2)
		};

		if (!reportObject.title) {
			_error("Please add a report title.");
			return;
		}

		if (!reportObject.report_date) {
			_error("Please add a report date.");
			return;
		}
		
		if (reportObject.status == "completed" && !reportObject.date) {
			_error("Please add a report completion date.");
			return;
		}
		
		//
		updateReportBasicInfo(reportObject);
	});

	function updateReportBasicInfo(reportObject) {
		//
		const button = $(".jsReportBasicUpdateBtn");
		button.prop("disabled", true).text("Updating...");

		if (XHR === null) {
			XHR = $.ajax({
				url: baseUrl(
					`compliance_safety_reporting/report/update/`+reportObject.report_id
				),
				method: "POST",
				data: reportObject,
			})
				.always(function () {
					XHR = null;
					button.prop("disabled", false).text("Save Changes");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
					});
				});
		}
	};

	$(".jsDeleteIssue").click(function (event) {
		event.preventDefault();
		issueId = $(this).data('issue_id');
        //
		_confirm(
			"Are you sure you want to delete this issue?",
			deleteIssueFromReport
		);
		
	});

	function deleteIssueFromReport() {
        //
        //
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/delete_issue_from_report/"+ issueId
				),
				method: "Delete"
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
                        window.location.reload();
                    });
				});
		}
	}

	// viewIssues Files
    $(".jsViewIssuesFiles").click(
        function (event) {
            event.preventDefault();

            loadViewModal({
                title: "View File(s)",
                reportId: $(this).data("report_id"),
                incidentId: $(this).data("incident_id"),
                issueId: $(this).data("issue_id"),
                count: $(this).data("files_count")
            });
        }
    );   
    
    function loadViewModal(options)
    {
        if ($("#jsIssueModalCommon").length <= 0) {
			const modal = `
			<div class=modal fade" id="jsIssueModalCommon" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
						</div>
						<div class="modal-body">
							<div class="alert alert-info text-center">Generating a view please wait.</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			`;
			$("body").append(modal);
        }
        // let's add options
        $("#jsIssueModalCommon").find(".modal-title").html(options.title);
        if (options.count == 0) {
            $("#jsIssueModalCommon").find(".modal-body").html('<div class="alert alert-info text-center">No file has been attached to this issue.</div>');
        } else {
            $("#jsIssueModalCommon").find(".modal-body").html('<div class="alert alert-info text-center">Generating a view please wait.</div>');
            //
            if (XHR === null) {
                //
                ml(true, "jsPageLoader");
                //
                XHR = $.ajax({
                    url: baseUrl(
                        "compliance_safety_reporting/get_attached_files/"+options.reportId+"/"+options.incidentId+"/"+options.issueId
                    ),
                    method: "GET"
                })
                    .always(function () {
                        XHR = null;
                        ml(false, "jsPageLoader");
                    })
                    .fail(handleErrorResponse)
                    .done(function (resp) {
                        $("#jsIssueModalCommon").find(".modal-body").html(resp.view);
                        _success(resp.message);
                    });
            }
        }
        
        //
		$("#jsIssueModalCommon").modal({
			backdrop: "static",
			keyboard: false,
        });
        //
		$("#jsIssueModalCommon").modal("show");
    }

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
                $("#jsIssueModalCommon").modal("hide");
				loadView(fileId);
			}
		);
	});

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

	$(document).on("click", ".jsDeleteFile", function (event) {
		event.preventDefault();
		const fileId = $(this).data("file_id");
		const fileType = $(this).data("file_type");
		_confirm(
			"Are you sure you want to remove this "+fileType+"? It will be removed from this issue permanently as well.",
			function () {
				deleteFileFromIssue(fileId, fileType);
			}
		);
	});

	function deleteFileFromIssue (fileId, fileType) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/delete_file/" +
						fileId 
				),
				method: "DELETE",
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success("Removed " + fileType + " successfully", function () {
						window.location.reload();
					});
				});
		}
	}

	// Upload file
    $(".jsIssueUploadFileBtn").click(
        function (event) {
            event.preventDefault();

            cspReportId = $(this).data("report_id");
            cspIncidentId = $(this).data("incident_id");
            cspIssueId = $(this).data("issue_id");

            loadModal({
                title: "Attach file",
                save: {
                    text: "Upload file",
                    cl: "jsIssueSaveFileBtn"
                }
            });
            //
            const uploadFileHtml = `
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="jsIssueFileUploadTitle">Title <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" id="jsIssueFileUploadTitle" name="jsIssueFileUploadTitle" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="file" class="hidden" id="jsIssueFileUploadFile" name="jsIssueFileUploadFile" />
                            </div>
                        </div>
                    </div>
                </div>
            `;
            //
            $("#jsIssueModalCommon").find(".modal-body").html(uploadFileHtml);
            //
            fileUploaderReference = $("#jsIssueFileUploadFile").msFileUploader(config.document);
            //
            $("#jsIssueModalCommon").find(".jsIssueSaveFileBtn").removeClass("hidden");
        }
    );

	$(document).on("click", ".jsIssueSaveFileBtn", function (event) {
        event.preventDefault();
        if (XHR !== null) {
            return;
        }
        //
        const issueUploadObject = {
            title: $("#jsIssueFileUploadTitle").val().trim(),
            file: $("#jsIssueFileUploadFile").msFileUploader("get"),
        };
        //
        if (!issueUploadObject.title) {
            _error("Please add a title of the file.")
            return;
        }
        if (Object.keys(issueUploadObject.file).length === 0) {
            _error("Please select a valid file.")
            return;
        }
        if (issueUploadObject.hasError && issueUploadObject.type === "vimeo") {
			_error("Vimeo link is invalid.");
			return;
		}
		if (issueUploadObject.hasError && issueUploadObject.type === "youtube") {
			_error("YouTube link is invalid.");
			return;
        }
        if (issueUploadObject.file.hasError) {
            _error("Please select a valid file.")
            return;
        }
        issueUploadObject.fileType = 
            issueUploadObject.file.type === "vimeo" || issueUploadObject.file.type === "youtube"
            ? "link"
            : getFileType(issueUploadObject.file).toLowerCase()
        //
        attachFileToIssue(issueUploadObject);
    });

    function attachFileToIssue(issueUploadObject) {
        // Toggle button state
        const toggleButton = $(".jsIssueSaveFileBtn");
        toggleButton.prop("disabled", true);
        //
        const formData = new FormData();
        //
        formData.append("reportId", cspReportId);
        formData.append("incidentId", cspIncidentId);
        formData.append("itemId", cspIssueId);
        formData.append("title", issueUploadObject.title);
        formData.append("type", issueUploadObject.fileType);
        //
        if (issueUploadObject.file.type === "youtube" || issueUploadObject.file.type === "vimeo") {
            formData.append("link", issueUploadObject.file.link);
        } else {
            formData.append("file", issueUploadObject.file);
        }
        //
        XHR = $
            .ajax({
                url: baseUrl(`compliance_safety_reporting/add_file_to_incident_item`),
                method: "POST",
                data: formData,
                processData: false,
                contentType: false
            })
            .always(function () {
                XHR = null;
                toggleButton.prop("disabled", false);
            })
            .fail(handleErrorResponse)
            .done(function (resp) {
                _success("File attached successfully.");
                $("#jsIssueModalCommon").modal("hide");
            });
    }


    function loadModal(options)
    {
        if ($("#jsIssueModalCommon").length <= 0) {
			const modal = `
			<div class=modal fade" id="jsIssueModalCommon" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"></h5>
						</div>
						<div class="modal-body">
							<div class="alert alert-info text-center">Generating a view please wait.</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-orange hidden"></button>
						</div>
					</div>
				</div>
			</div>
			`;
			$("body").append(modal);
        }
        // let's add options
        $("#jsIssueModalCommon").find(".modal-title").html(options.title);
        $("#jsIssueModalCommon").find(".btn-orange").html(options.save.text);
        $("#jsIssueModalCommon").find(".btn-orange").addClass(options.save.cl);
        $("#jsIssueModalCommon").find(".btn-orange").addClass("hidden");
        //
		$("#jsIssueModalCommon").modal({
			backdrop: "static",
			keyboard: false,
        });
        //
		$("#jsIssueModalCommon").modal("show");
    }

    function getFileType(file) {
		if (!file) return "Unknown"; // If no file is provided

		const fileName = file.name.toLowerCase();
		const fileType = file.type;

		let fileCategory = "file";

		// Checking based on MIME type
		if (fileType.startsWith("audio/")) {
			fileCategory = "audio";
		} else if (fileType.startsWith("video/")) {
			fileCategory = "video";
		} else if (fileType.startsWith("image/")) {
			fileCategory = "image";
		} else if (fileType.startsWith("application/")) {
			// Check for common document types
			if (
				fileType === "application/pdf" ||
				fileType.startsWith("application/msword") ||
				fileType ===
					"application/vnd.openxmlformats-officedocument.wordprocessingml.document"
			) {
				fileCategory = "document";
			}
		}

		// If MIME type is not clear, checking based on file extension
		if (fileCategory === "file") {
			if (fileName.match(/\.(pdf|doc|docx|txt|xls|xlsx|ppt|pptx)$/)) {
				fileCategory = "document";
			} else if (fileName.match(/\.(mp3|wav|ogg|flac|aac|m4a)$/)) {
				fileCategory = "audio";
			} else if (fileName.match(/\.(mp4|mkv|avi|mov|wmv|flv|webm)$/)) {
				fileCategory = "video";
			}
		}

		return fileCategory;
	}

	ml(false, "jsPageLoader");
});
0