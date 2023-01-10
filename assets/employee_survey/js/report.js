$(function (){
    var departments = {};
    var surveyScore = [];
    var HCCategories = ["Company"];
    var HCData = [];
    //
    function getCompanyDepartments () {
        return new Promise(resolve => {
            $.ajax({
                type: 'GET',
                url: apiURI+'employee/'+ cToken +'/departments',
                dataType: 'json',
                success: function(resp) {
                   resolve(resp);
                },
                error: function() {
                    resolve(false);
                }
            });
        });
    }
	//
    async function applyFilters () {
        departments = await getCompanyDepartments();
        //
        if ($('#jsDepartmentFilter').data('select2')) {
            $('#jsDepartmentFilter').data('select2').destroy()
            $('#jsDepartmentFilter').remove()
        }
        //
        if ($('#jsDepartmentFilter2').data('select2')) {
            $('#jsDepartmentFilter2').data('select2').destroy()
            $('#jsDepartmentFilter2').remove()
        }
        //
        var departmentOptions = "";
        //
        if (departments.length) {
            departments.map(function(department) {
                departmentOptions += '<option value="' + (department['sid']) + '">' + (department['name']) + '</option>';
            });
        }
        //
        $('#jsDepartmentFilter')
            .html(departmentOptions)
            .select2({
                closeOnSelect: false
            });
        //    
        $('#jsDepartmentFilter2')
            .html(departmentOptions)
            .select2({
                closeOnSelect: false
            });    
        //    
        $('#jsGenderFilter').select2({
            closeOnSelect: false
        });
        //
        $('#jsTenureFilter').select2({
            closeOnSelect: false
        });
    }

    function generateCompanySurveysReportPreview (result) {
        surveyScore.push(result.surveyScore.companyScore);
        HCData.push(result.surveyScore.companyScore);
        //
        drawSurveyGraph();
        //
        let overallScorePerQuestion = '';
        //
        result.surveyQuestion.map(function(question) {
            overallScorePerQuestion += '<div class="col-sm-12 col-sm-12 col-xs-12 csline">';
            overallScorePerQuestion +=      question.question_text;
            overallScorePerQuestion +=      '<span class="_csFloatRight">';
            overallScorePerQuestion +=          '16%';
            overallScorePerQuestion +=      '</span>';
            overallScorePerQuestion += '</div>';

        });
        //
        $("#jsOverallScorePerQuestion").html(overallScorePerQuestion);
    }

    function drawSurveyGraph () {
        var gaugeOptions = {
            chart: {
                type: 'solidgauge'
            },

            title: null,

            pane: {
                center: ['50%', '85%'],
                size: '140%',
                startAngle: -90,
                endAngle: 90,
                background: {
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
                    innerRadius: '60%',
                    outerRadius: '100%',
                    shape: 'arc'
                }
            },

            exporting: {
                enabled: false
            },

            tooltip: {
                enabled: false
            },

            // the value axis
            yAxis: {
                stops: [
                    [0.1, '#fd7a2a'], // green

                ],
                lineWidth: 0,
                tickWidth: 0,
                minorTickInterval: null,
                tickAmount: 2,
                title: {
                    y: -70
                },
                labels: {
                    y: 16
                }
            },

            plotOptions: {
                solidgauge: {
                    dataLabels: {
                        y: 5,
                        borderWidth: 0,
                        useHTML: true
                    }
                }
            }
        };

        // The speed gauge
        var chartSpeed = Highcharts.chart('surveyresults', Highcharts.merge(gaugeOptions, {
            yAxis: {
                min: 0,
                max: 100,
                title: {
                    text: ''
                }
            },

            credits: {
                enabled: false
            },

            series: [{
                name: 'Speed',
                data: surveyScore,
                dataLabels: {
                    format: '<div style="text-align:center">' +
                        '<span style="font-size:25px">{y}</span><br/>' +
                        '<span style="font-size:12px;opacity:0.4">Score</span>' +
                        '</div>'
                },
                tooltip: {
                    valueSuffix: 'Score'
                }
            }]

        }));

        //Response Rate 
        const chart = Highcharts.chart('container', {
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: HCCategories
            },
            series: [{
                type: 'column',
                name: '',
                colorByPoint: false,
                data: HCData,
                showInLegend: false
            }]
        });
        //
        return true;
    }

	function getCompanySurveysResult () {
        applyFilters();
        //
		$.ajax({
            type: 'GET',
            url: apiURI+'survey_report/'+ surveyToken+'?department=all&gender=all&tenure=all',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                generateCompanySurveysReportPreview(resp);
            },
            error: function() {
            	$("#surveysBoxContainer").html('<p class="_csF14">Unable to load survey Report</p>');
            	$('.jsESLoader').hide();
            }
        }); 
	}

    getCompanySurveysResult()
});

   