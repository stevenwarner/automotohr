$(function () {
    let XHR = null;
    let selectedIssues = [];
    let cspIssueId = 0;
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

let fileUploaderReference = {};

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

    $("#jsDateRangePicker").daterangepicker({
		showDropdowns: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
			separator: " - ",
		},
	});

    $(document).on('click', '.js-check-all', selectAllInputs);
    $(document).on('click', '.js-tr', selectSingleInput);

    // Select all input: checkbox
    function selectAllInputs() {
        $('.js-tr').find('input[name="issues_ids[]"]').prop('checked', $(this).prop('checked'));
    }

    // Select single input: checkbox
    function selectSingleInput() {
        $(this).find('input[name="issues_ids[]"]').prop('checked', !$(this).find('input[name="issues_ids[]"]').prop('checked'));
    }

    $(".jsSendReminderEmails").click(function (event) {
		event.preventDefault();
        //
        $.each($('input[name="issues_ids[]"]:checked'), function() {
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

    // Upload file
    $(".jsIssueUploadFileBtn").click(
        function (event) {
            event.preventDefault();

            cspIssueId = $(this).closest("tr").data("id");

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
            file: fileUploaderReference.getValue(),
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
            : getFileType(issueUploadObject.file.type).toLowerCase()
        //
        attachFileToIssue(issueUploadObject);
    });

    function attachFileToIssue(issueUploadObject) {
        // Toggle button state
        const toggleButton = $(".jsIssueSaveFileBtn");
        toggleButton.prop("disabled", true);
        //
        const formData = new FormData();
        formData.append("cspIssueId", cspIssueId);
        formData.append("title", issueUploadObject.title);
        formData.append("file", issueUploadObject.file);
        //
        XHR = $
            .ajax({
                url: baseUrl(`compliance_safety_reporting/issues/${cspIssueId}/attach-file`),
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
});