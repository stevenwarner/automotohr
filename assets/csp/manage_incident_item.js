$(function Overview() {
	let externalPointer = $(".jsAddExternalBody .jsEER").length;
	let XHR = null;

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
	//
	$("#report_note_type").select2({
		minimumResultsForSearch: -1,
	});
	//
	CKEDITOR.replace("report_note");
	//
	$('#jsDepartments').select2({
		closeOnSelect: false
	});
	$('#jsTeams').select2({
		closeOnSelect: false
	});
	//
	$("#item_completion_date").datetimepicker({
		format: "m/d/Y",
		datepicker: true,
		timepicker: false,
		changeYear: true,
		changeMonth: true,
	});

	//
	$("#jsAddIncidentItemForm").validate({
		rules: {},
		messages: {},
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

	$(document).on("click", ".jsAddDepartmentsAndTeams", function () {
		//
		var obj = {};
		obj.departments = $('#jsDepartments').val() || '';
		obj.teams = $('#jsTeams').val() || '';
		//
		// if (obj.departments == "" && obj.teams == "") {
		// 	_error("Selection required: Choose at least a department or a team.");
		// 	return;
		// }
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/add_department_and_team/" +
					reportId +
					"/" +
					incidentId +
					"/" +
					itemId
				),
				method: "POST",
				data: $.param(obj),
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
	});

	$(document).on("click", ".jsRemoveDepartmentsAndTeams", function () {
		_confirm(
			"Are you sure you want to remove department(s) and team(s).",
			function () {
				deleteDepartmentsAndTeams();
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
		attachFileToItem(obj.file, obj.title, fileType);
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

	$(document).on("click", ".jsDeleteFile", function (event) {
		event.preventDefault();
		const fileId = $(this).data("file_id");
		const fileType = $(this).data("file_type");
		_confirm(
			"Are you sure you want to remove this " + fileType + "? It will be removed from this issue permanently as well.",
			function () {
				deleteFileFromIssue(fileId, fileType);
			}
		);
	});

	$(document).on("click", ".jsIssueProgressQuestionBtn", function (event) {
		var obj = {};
		obj.report_to_dashboard = $('.jsReportToDashboard:checked').val();
		obj.ongoing_issue = $('.jsOngoingIssue:checked').val();
		obj.reported_by = $('.jsReportedBy:checked').val();
		obj.category_of_issue = $('#jsCategoryOfIssue').val();
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl("compliance_safety_reporting/issue_questions/" + itemId),
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
	});

	function deleteDepartmentsAndTeams() {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/delete_department_and_team/" +
					itemId
				),
				method: "DELETE",
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success("Removed department and team successfully", function () {
						window.location.reload();
					});
				});
		}
	}

	function generateExternalEmployees() {
		let html = `
        <div class="row" data-external="${externalPointer}">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="external_employee_name">Name</label>
                    <input type="text" name="external_employees_names[${externalPointer}]['name']" class="form-control jsEPName${externalPointer}" required>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="external_employee_email">Email</label>
                    <input type="email" name="external_employees_emails[${externalPointer}]['email']" class="form-control jsEPEmail${externalPointer}" required>
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
			 <div class="col-md-1 jsAddExternalEmployeeRow">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-orange btn-block jsAddExternalEmployeeBtn">
                        <i class="fa fa-plus-circle"></i>
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
			let dynamicInputs = $('input[name="dynamicInput[]"]')
				.map(function () {
					return $(this).val();
				})
				.get();
			//
			let dynamicCheckboxes = $('input[name="dynamicCheckbox[]"]')
				.map(function () {
					return $(this).prop("checked") ? "on" : "off";
				})
				.get();
			//
			var data = $(form).serializeArray(); // convert form to array
			data.push({ name: 'itemInput', value: dynamicInputs });
			data.push({ name: 'itemCheckbox', value: dynamicCheckboxes });

			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/incident_item_management/" +
					reportId +
					"/" +
					incidentId +
					"/" +
					itemId
				),
				method: "POST",
				data: $.param(data),
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
					"compliance_safety_reporting/notes/" +
					reportId +
					"/" +
					incidentId +
					"/" +
					itemId
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

	function loadAttachmentView(itemId, reportId, incidentId) {
		$.ajax({
			url: baseUrl("compliance_safety_reporting/get_item_attachment_view"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsFileAttachModalBody").html(resp.view);
				$("#jsItemId").val(itemId);
				$("#jsReportId").val(reportId);
				$("#jsIncidentId").val(incidentId);
				$("#jsItemDocuments").msFileUploader(config.document);
				ml(false, "jsFileAttachModalLoader");
				//
			});
	}

	async function attachFileToItem(file, title, type) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			const formData = new FormData();
			formData.append("title", title);
			formData.append("type", type);
			formData.append("itemId", itemId);
			formData.append("reportId", reportId);
			formData.append("incidentId", incidentId);

			if (file.type === "youtube" || file.type === "vimeo") {
				formData.append("link", file.link);
			} else {
				formData.append("file", file);
			}
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/add_file_to_incident_item"
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
					alertify.alert(
						"You have successfully upload the file",
						function () {
							window.location.refresh();
						}
					);
				});
		}
	}

	function deleteFileFromIssue(fileId, fileType) {
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

	function deleteExternalEmployee(external) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/" +
					reportId +
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

	if ($(".jsDescriptionBody").length) {
		if (descriptionFieldsObj && descriptionFieldsObj.dynamicInput) {
			//
			descriptionFieldsObj.dynamicInput.map(function (v, i) {
				$('[name="dynamicInput[]"]').eq(i).val(v);
			});
		}
		if (descriptionFieldsObj && descriptionFieldsObj.dynamicCheckbox) {
			descriptionFieldsObj.dynamicCheckbox.map(function (v, i) {
				$('[name="dynamicCheckbox[]"]')
					.eq(i)
					.prop("checked", v === "on");
			});
		}
	}

	$(".show-status-box").click(function () {
		$(this).closest(".row").next().show();
	});

	$(".applicant").hover(
		function () {
			$(this).find(".jsSeverityLevelText").animate(
				{
					"padding-top": 0,
					"padding-right": 0,
					"padding-bottom": 0,
					"padding-left": 15,
				},
				"fast"
			);
		},
		function () {
			$(this).find(".jsSeverityLevelText").animate(
				{
					"padding-top": 0,
					"padding-right": 0,
					"padding-bottom": 0,
					"padding-left": 5,
				},
				"fast"
			);
		}
	);

	$(".applicant").click(function () {
		//
		const id = $(this).data("id");
		//
		$(this).parent().parent().parent().find(".jsSelectedPill").html(`
			<div data-id="${id}" class="csLabelPill jsSelectedLabelPill text-center" 
			style="
			background-color: ${$(this).css(
			"background-color"
		)}; color: ${$(this).css("color")} ;">${$(this).text()}</div>
		`);

		$(this)
			.parent()
			.parent()
			.parent()
			.parent()
			.find(".jsIncidentSeverityLevel")
			.val(id);
	});

	$(".cross").click(function () {
		$(this).parent().parent().css("display", "none");
	});

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
					"/emails/send/incidents"
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


	$("#jsIssueStatus").change(function () {
		$("#jsIssueCompletionDate").prop("disabled", $(this).val() != "completed");
	});

	$(".jsIssueProgressUpdateBtn").click(function (event) {
		event.preventDefault();
		const obj = {
			status: $("#jsIssueStatus").val(),
			completionDate: $("#jsIssueCompletionDate").val(),
			itemId: getSegment(4),
			reportId: getSegment(3),
			incidentId: getSegment(2),
		};
		//
		if (obj.status.trim() === "") {
			_error("Please select a status.");
			return;
		}
		if (obj.status === "completed" && obj.completionDate.trim() === "") {
			_error("Please select a completion date.");
			return;
		}

		const _html = callButtonHook($(this), true);

		updateProgress(obj)
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");

				callButtonHook(_html, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.message, function () {
					window.location.reload();
				});
			});
	});

	updateProgress = function (obj) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/issue/progress/update/main"
				),
				method: "POST",
				data: obj,
			});
		}
		return XHR;
	};

	ml(false, "jsPageLoader");


	//
	$(".jsUpdateItemBtn").click(
		function (event) {
			event.preventDefault();
			//
			const type = $(this).data("type");

			if (type === "internal") {
				processInternalEmployees();
			} else {
				processExternalEmployees();
			}
		}
	);


	function processInternalEmployees() {
		const selectedEmployeeIds = $(".jsInternalEmployees:checked").length > 0 ? $(".jsInternalEmployees:checked")
			.map(function () {
				return $(this).val();
			})
			.get() : [];
		//
		ml(true, "jsPageLoader")
		//
		$
			.ajax({
				url: baseUrl(`compliance_safety_reporting/issues/${getSegment(2)}/${getSegment(3)}/${getSegment(4)}/employees/internal`),
				method: "POST",
				data: {
					ids: selectedEmployeeIds,
				}
			})
			.always(function () {
				ml(false, "jsPageLoader")
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.message)
			});
	}

	//
	$(document).on(
		"click",
		".jsAddExternalEmployeeBtn",
		function (event) {
			event.preventDefault();
			processExternalEmployee(
				$(this).closest(".row").data("external")
			);
		}
	);

	function processExternalEmployee(id) {

		const obj = {
			name: $(`.jsEPName${id}`).val().trim(),
			email: $(`.jsEPEmail${id}`).val().trim(),
		}

		if (!obj.name) {
			_error("Employee name is required.");
			return;
		}

		if (!obj.email) {
			_error("Employee email is required.");
			return;
		}

		let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

		if (!regex.test(obj.email)) {
			_error("Employee email is invalid.");
			return;
		}

		ml(true, "jsPageLoader")
		//
		$
			.ajax({
				url: baseUrl(`compliance_safety_reporting/issues/${getSegment(2)}/${getSegment(3)}/${getSegment(4)}/employees/external`),
				method: "POST",
				data: {
					external: obj,
				}
			})
			.always(function () {
				ml(false, "jsPageLoader")
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.message)

				$(`.row [data-external="${id}"]`).data("id", resp.id);
				$(`.row [data-external="${id}"]`).find(".jsAddExternalEmployeeRow").remove();
			});
	}
});
