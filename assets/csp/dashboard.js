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
            categories: ['Low', '2', '3', '4', '5'],
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
            data: JSON.parse(severityLevelGraph).data, // Use actual severity level data
            colors: JSON.parse(severityLevelGraph).colors // Apply colors for each severity level
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
					_success(resp.message);
				});
		}
	}
});