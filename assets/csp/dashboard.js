$(function () {
    let XHR = null;
    let selectedIssues = [];

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

    $('#jsCSVButton').on('click', function(e) {
        e.preventDefault();
        generateCSVUrl();

        window.location = $(this).attr('href').toString();
    });

    function generateCSVUrl() {
        var severityLevel = $('#severityLevel').val();
        var incidentType = $('#incidentType').val();
        var status = $('#status').val();
        var incidentTitle = $('#applicant_status').val();
        var DateRange = $('#jsDateRangePicker').val();

        severityLevel = severityLevel != '' && severityLevel != null && severityLevel != undefined && severityLevel != 0 ? encodeURIComponent(severityLevel) : '-1';
        incidentType = incidentType != '' && incidentType != null && incidentType != undefined && incidentType != 0 ? encodeURIComponent(incidentType) : '-1';
        status = status != '' && status != null && status != undefined && status != 0 ? encodeURIComponent(status) : '-1';
        incidentTitle = incidentTitle != '' && incidentTitle != null && incidentTitle != undefined && incidentTitle != 0 ? encodeURIComponent(incidentTitle) : '-1';
        DateRange = DateRange != '' && DateRange != null && DateRange != undefined && DateRange != 0 ? encodeURIComponent(DateRange) : '';

        var url = baseUrl("compliance_safety_reporting/export_csv?")+ '/' + severityLevel + '/' + incidentType + '/' + status + '/' + incidentTitle + '/' + DateRange;

        $('#jsCSVButton').attr('href', url);
    }
});