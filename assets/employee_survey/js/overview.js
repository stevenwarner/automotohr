$(function (){
    var graphData = [];
	//
    function generateSurveyOverviewSection (surveys, type) {
        //
        var surveyBox = '';
        var surveyCount = 0
        //
        if (surveys.length > 0) {
            surveys.map(function(survey) {
                surveyBox += surveyCount == 0 ? '<div class="csSurveyRow">' : '<div class="csSurveyRow _csBDt _csBD6 _csPt20">';
                surveyBox += '    <div class="row">';
                if (type == "Finished") {
                   surveyBox += '        <div class="col-md-10 col-sm-12">';
                } else {
                    surveyBox += '        <div class="col-md-12 col-sm-12">';
                }
                
                surveyBox += '            <div>';
                surveyBox += '                <span>'+survey.title+'</span>';
                //
                if (type == "Finished") {
                   surveyBox += '                <span class="pull-right"> Finishad at: '+survey.display_end_date+'</span>'; 
                }
                //
                if (type == "Running") {
                   surveyBox += '                <span class="pull-right">Running between: '+survey.display_start_date+' to '+ survey.display_end_date +'</span>'; 
                }
                //
                if (type == "Assigned") {
                   surveyBox += '                <span class="pull-right">Starting on: '+survey.display_start_date+'</span>'; 
                }
                //
                surveyBox += '            </div>';
                surveyBox += '            <div class="progress _csMt10">';
                surveyBox += '                <div class="progress-bar _csB4" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: '+survey.surveyCompletedRespondentsPercentage+'%;"></div>';
                surveyBox += '            </div>';
                surveyBox += '            <p>'+survey.surveyCompletedRespondentsPercentage+'% Completed</p>';
                surveyBox += '        </div>';
                //
                if (type == "Finished") {
                    surveyBox += '        <div class="col-md-2 col-sm-12 text-left">';
                    surveyBox += '            <a href="'+baseURI+'/employee/surveys/reports/'+survey.sid+'" class="btn _csB4 _csF2 _csMt20 _csR5">Results</a>';
                    surveyBox += '        </div>';
                }
                //
                surveyBox += '    </div>';
                surveyBox += '</div>';
                //
                surveyCount++;
            });
        } else {

            surveyBox += '<p class="text-center _csPt20 _csPb20">';
            surveyBox += '    <i class="fa fa-info-circle _csF40" aria-hidden="true"></i> <br>';
            surveyBox += '    <span class="_csF16">No engagement '+type.toLowerCase()+' yet.</span>';
            surveyBox += '</p>';
        }
        //
        $("#js"+type+"SurveysSection").html(surveyBox);
        $("#js"+type+"SurveysCount").html(surveys.length);
        //
        return true;
    }
    //
    function drawSurveyGraph () {
        const data = {
            labels: [
                'Finished',
                'Running',
                'Assigned'
            ],
            datasets: [{
                label: 'Engagement(s)',
                data: graphData,
                backgroundColor: [
                    'rgb(129, 180, 49)',
                    'rgb(53, 84, 220)',
                    'rgb(253, 122, 42)',
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
        //
        return true;
    }
    //
    async function generateCompanySurveysOverviewPreview (resp) {

        await generateSurveyOverviewSection(resp.finishedSurveys, "Finished");
        graphData.push(resp.finishedSurveys.length);
        //
        await generateSurveyOverviewSection(resp.runningSurveys, "Running");
        graphData.push(resp.runningSurveys.length);
        //
        await generateSurveyOverviewSection(resp.assignedSurveys, "Assigned");
        graphData.push(resp.assignedSurveys.length);
        //
        drawSurveyGraph();
        //
        $('.jsESLoader').hide();
    }
	//
	function getCompanySurveysOverviewInfo () {
        //
		$.ajax({
            type: 'GET',
            url: apiURI+'survey_overview/'+ cToken,
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                generateCompanySurveysOverviewPreview(resp);
            },
            error: function() {
            	$("#surveysBoxContainer").html('<p class="_csF14">Unable to load survey templates</p>');
            	$('.jsESLoader').hide();
            }
        }); 
	}

    getCompanySurveysOverviewInfo();
});

   