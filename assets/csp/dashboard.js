$(function () {
    //
    // Add type in issue to search by adding the select2.
    // attach files when reporting an issue.
    // Add notes to the issue as well.
    // Replace the title with the description.
    let XHR = null;
    let selectedIssues = [];
    let cspReportId = 0;
    let cspIncidentId = 0;
    let cspIssueId = 0;
    let fileUploaderReference = {};
    let cspPublic = 0;
    //
    $('#jsDepartments').select2({
		closeOnSelect: false
	});
    //
	$('#jsTeams').select2({
		closeOnSelect: false
	});
    //
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

    Highcharts.chart('jsProgressGraph', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Compliance Progress',
            style: {
                fontSize: '14px' // Increased title font size
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        fontSize: '14px' // Increased font size
                    }
                }
            }
        },
        legend: {
            itemStyle: {
                fontSize: '14px' // Increased legend font size
            }
        },
        series: [{
            name: 'Tasks',
            colorByPoint: true,
            data: JSON.parse(progressGraphData).map((item, index) => ({
                ...item,
                color: JSON.parse(progressGraphColors)[index] // Assign colors from progressGraphColors
            })),
        }]
    });

    Highcharts.chart('jsSeverityGraph', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Compliance Progress by Severity Levels',
            style: {
                fontSize: '14px' // Adjusted title font size
            }
        },
        xAxis: {
            categories: JSON.parse(severityLevelGraph).categories, // Reverse categories to show high to low
            title: {
                text: 'Severity Level',
                style: {
                    fontSize: '14px' // Adjusted x-axis title font size
                }
            },
            labels: {
                style: {
                    fontSize: '14px' // Adjusted x-axis label font size
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number of Issues',
                style: {
                    fontSize: '14px' // Adjusted y-axis title font size
                }
            },
            labels: {
                style: {
                    fontSize: '14px' // Adjusted y-axis label font size
                }
            }
        },
        tooltip: {
            pointFormat: '<b>{point.y}</b> issues at Severity Level {point.category}'
        },
        plotOptions: {
            column: {
                colorByPoint: true,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}',
                    style: {
                        fontSize: '14px' // Adjusted label font size
                    }
                }
            }
        },
        legend: {
            itemStyle: {
                fontSize: '14px' // Adjusted legend font size
            }
        },
        series: [{
            name: 'Issues',
            data: JSON.parse(severityLevelGraph).data, //  data to match high to low order
            colors: JSON.parse(severityLevelGraph).colors // Reverse colors to match high to low order
        }]
    });

    if (typeof daterangepicker !== "undefined") {

        $("#jsDateRangePicker").daterangepicker({
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: "MM/DD/YYYY",
                separator: " - ",
                cancelLabel: "clear"
            },
        });

        $("#jsDateRangePicker").on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $("#jsDateRangePicker").on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    }

    $(document).on('click', '.js-check-all', selectAllInputs);
    // $(document).on('click', '.jsIssueIds', selectSingleInput);

    // Select all input: checkbox
    function selectAllInputs() {
        $('.jsIssueIds').prop('checked', $(this).prop('checked'));
    }

    // Select single input: checkbox
    // function selectSingleInput() {
    //     $(this).find('input[name="issues_ids[]"]').prop('checked', !$(this).find('input[name="issues_ids[]"]').prop('checked'));
    // }

    $(".jsSendReminderEmails").click(function (event) {
        event.preventDefault();
        //
        $.each($('.jsIssueIds:checked'), function () {
            selectedIssues.push(parseInt($(this).val()));
        });
        //
        if (selectedIssues.length) {
            _confirm(
                "Are you sure you want to send emails to the selected employees and external recipients?",
                sendEmailsForReport
            );
        } else {
            alertify.alert("Please select an issue to send reminder email.");
            return false;
        }
        //

    });

    function sendEmailsForReport() {
        //
        //
        if (XHR === null) {
            //
            ml(true, "jsPageLoader");
            //
            XHR = $.ajax({
                url: baseUrl(
                    "compliance_safety_reporting/send_issue_reminder_email"
                ),
                method: "POST",
                data: {
                    issuesIds: selectedIssues
                },
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


    $(".jsMarkIssueDone").click(
        function (event) {
            event.preventDefault();
            const issueId = $(this).data("issue_id");
            const btnRef = $(this)
            //
            _confirm(
                "Do you really want to mark this issue completed?",
                function () {
                    markTheIssueDone(issueId, btnRef);
                }
            );
        }
    );
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

    function loadViewModal(options) {
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
                        "compliance_safety_reporting/get_attached_files/" + options.reportId + "/" + options.incidentId + "/" + options.issueId
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

    function markTheIssueDone(issueId, btnRef) {
        const _html = callButtonHook(btnRef, true);
        $.ajax({
            url: baseUrl("compliance_safety_reporting/issues/" + issueId + "/mark/done"),
            method: "POST",
            data: {
                status: "completed"
            }
        })
            .always(function () {
                callButtonHook(_html, false)
            })
            .fail(handleErrorResponse)
            .done(function (resp) {
                btnRef.remove();
                $(`.jsStatusRow${issueId}`).html(resp.view);
            });
    }

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

    function deleteFileFromIssue(fileId, fileType) {
        //
        if (XHR === null) {
            //
            ml(true, "jsPageLoader");
            //
            XHR = $.ajax({
                url: baseUrl(
                    (getSegment(0) === "csp" ? "csp" : "compliance_safety_reporting") + "/delete_file/" +
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
                        // window.location.reload();
                        $(`button.jsDeleteFile[data-file_id="${fileId}"]`).closest(".jsFileBox").remove();
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
            cspPublic = $(this).data("public");

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
                url: baseUrl(`${cspPublic ? "csp/" : "compliance_safety_reporting"}/add_file_to_incident_item`),
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
                $("#jsIssueModalCommon").modal("hide");
                alertify.success("File attached successfully.")

                if ($(`.jsFilesArea${cspIssueId} .jsDocumentsArea .jsFirst`).length === 0) {
                    $(`.jsFilesArea${cspIssueId}`).html('<div class="jsDocumentsArea"><div class="jsFirst"></div></div>');
                }
                // ;
                $(`.jsFilesArea${cspIssueId} .jsDocumentsArea .jsFirst`).prepend(resp.view);
            });
    }


    function loadModal(options) {
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


    $(".jsToggle").click(
        function (event) {
            event.preventDefault();
            //
            $(".jsChildRows").addClass("hidden");

            if ($(this).find(".jsToggleIcon").hasClass("fa-chevron-right")) {
                $("." + $(this).data('target')).removeClass("hidden");
                $(this).find(".jsToggleIcon").removeClass("fa-chevron-right");
                $(this).find(".jsToggleIcon").addClass("fa-chevron-down");
            } else {
                $(this).find(".jsToggleIcon").removeClass("fa-chevron-down");
                $(this).find(".jsToggleIcon").addClass("fa-chevron-right");
            }
        }
    );

    $(".jsToggle2").click(
        function (event) {
            event.preventDefault();
            //
            $(".jsIssuesBody").addClass("hidden");

            if ($(this).find(".jsToggleIcon").hasClass("fa-chevron-right")) {
                $("#" + $(this).data('target')).removeClass("hidden");
                $(this).find(".jsToggleIcon").removeClass("fa-chevron-right");
                $(this).find(".jsToggleIcon").addClass("fa-chevron-down");
            } else {
                $(this).find(".jsToggleIcon").removeClass("fa-chevron-down");
                $(this).find(".jsToggleIcon").addClass("fa-chevron-right");
            }
        }
    );

    $(".jsMarkIssueDonePublic").click(
        function (event) {
            event.preventDefault();
            const issueId = $(this).data("issue_id");
            const btnRef = $(this)
            //
            _confirm(
                "Do you really want to mark this issue completed?",
                function () {
                    markTheIssueDonePublic(issueId, btnRef);
                }
            );
        }
    );

    function markTheIssueDonePublic(issueId, btnRef) {
        const _html = callButtonHook(btnRef, true);
        $.ajax({
            url: baseUrl("csp/issues/" + issueId + "/mark/done"),
            method: "POST",
            data: {
                status: "completed"
            }
        })
            .always(function () {
                callButtonHook(_html, false)
            })
            .fail(handleErrorResponse)
            .done(function (resp) {
                btnRef.remove();
                $(`.jsStatusRow${issueId}`).html(resp.view);
            });
    }

    $(".jsReportEditBtn").click(
        function (event) {
            event.preventDefault();
            event.stopPropagation();

            window.location.href = $(this).data("href")
        }
    );
});