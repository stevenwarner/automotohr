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
	$("#report_note_type").select2({
		minimumResultsForSearch: -1,
	});
	$("#jsReportIncidentType").select2({
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

	$(".start_date").datetimepicker({
		format: "m/d/Y",
		datepicker: true,
		timepicker: false,
		changeYear: true,
		changeMonth: true,
	});

	//
	$("#jsAddReportForm").validate({
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

	$(document).on("click", ".jsCSPItemAttachmentBtn", function (event) {
		event.preventDefault();
		const itemId = $(this).data("item_id");
		const reportId = $(this).data("report_id");
		const incidentId = $(this).data("incident_id");
		//
		Modal(
			{
				Id: "FileAttachModal",
				Loader: "jsFileAttachModalLoader",
				Title: '<span>Upload Item Attachment</span>',
				Body: '<div id="jsFileAttachModalBody"></div>',
			},
			function () {
				loadAttachmentView(itemId, reportId, incidentId);
			}
		);
	});

	$(document).on("click", ".jsAddItemDocument", function (event) {
		event.preventDefault();

		const obj = {
			title: $("#jsItemDocumentTitle").val(),
			file: $("#jsItemDocuments").msFileUploader("get"),
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
					"csp/report/" +
						getSegmentId(2) +
						"/incident/edit/" +
						getSegmentId(5)
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
					"csp/notes/" + getSegmentId(2) + "/" + getSegmentId(5)
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

	function loadAttachmentView (itemId, reportId, incidentId) {
		$.ajax({
			url: baseUrl("csp/get_item_attachment_view"),
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
			var itemId = $("#jsItemId").val();
			var reportId = $("#jsReportId").val();
			var incidentId =$("#jsIncidentId").val();
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
					"csp/add_file_to_incident_item"
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

	function loadView(fileId) {
		$.ajax({
			url: baseUrl("csp/file/view/" + fileId),
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
					"csp/" +
						getSegmentId(2) +
						"/" +
						getSegmentId(5) +
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
					"csp/file/" +
						getSegmentId(2) +
						"/" +
						getSegmentId(5) +
						"/" +
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

	//
	$(".jsCSPItemListingBtn").click(function (event) {
		//
		event.preventDefault();
		//
		let ids = [];
		//
		if ($(".jsCSPItemListingRow").length > 0) {
			$(".jsCSPItemListingRow").map(function () {
				//
				let dynamicInputs = $(this)
					.find('[name="dynamicInput[]"]')
					.map(function () {
						return $(this).val();
					})
					.get();
				//
				let dynamicCheckboxes = $(this)
					.find('[name="dynamicCheckbox[]"]')
					.map(function () {
						return $(this).prop("checked") ? "on" : "off";
					})
					.get();
				//
				const obj = {
					id: $(this).data("id"),
					dynamicInput: dynamicInputs,
					dynamicCheckbox: dynamicCheckboxes,
				};
				//
				ids.push($(this).val());
				//
				processIncidentItemListing(obj);
			});
			//
			alertify.alert(
				"You have successfully updated the checklist",
				function () {
					window.location.refresh();
				}
			);
			//
			ml(false, "jsPageLoader");
		}
	});

	function processIncidentItemListing(obj) {
		//
		XHR = $.ajax({
			url: baseUrl(
				"csp/report/" +
					getSegmentId(2) +
					"/incident/" +
					getSegmentId(5) +
					"/items/employees"
			),
			method: "POST",
			data: obj,
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {});
	}

	//
	// if (descriptionFieldsObj && descriptionFieldsObj.dynamicInput) {
	// 	descriptionFieldsObj.dynamicInput.map(function (v, i) {
	// 		$('[name="dynamicInput[]"]').eq(i).val(v);
	// 	});
	// }
	// if (descriptionFieldsObj && descriptionFieldsObj.dynamicCheckbox) {
	// 	descriptionFieldsObj.dynamicCheckbox.map(function (v, i) {
	// 		$('[name="dynamicCheckbox[]"]')
	// 			.eq(i)
	// 			.prop("checked", v === "on");
	// 	});
	// }

	function getSegmentId(segmentId) {
		if (segmentId === 2) {
			return segments.reportId;
		}
		return segments.incidentId;
	}

	ml(false, "jsPageLoader");
});
