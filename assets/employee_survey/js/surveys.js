$(function (){
	//
	var surveyType = "active";
	//
	function getCompanySurveys () {
		$.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/'+ cToken +'/surveys?type='+surveyType,
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {

                let surveyBox = '';

                resp.map(function(survey) {
                	surveyBox += '<div class="col-md-4 col-xs-12">';
                    surveyBox += '    <div class="panel panel-default " data-id="1" data-title="'+survey.title+'">';
                    surveyBox += '        <div class="panel-heading  _csB4 _csF2">';
                    surveyBox += '            <b>'+survey.title+'</b>';
                    surveyBox += '            <span class="pull-right">';
                    surveyBox += '                <a class="btn _csB4 _csF2 _csR5  _csF16 " title="Start the review" placement="top" href="'+apiURI+'employee/surveys/surveys/'+ survey.sid +'">';
                    surveyBox += '                    <i class="fa fa-eye csF16" aria-hidden="true"></i>';
                    surveyBox += '                </a>';
                    surveyBox += '            </span>';
                    surveyBox += '            <div class="clearfix"></div>';
                    surveyBox += '        </div>';
                    surveyBox += '        <div class="panel-body">';
                    surveyBox += '            <p class="_csF14"><b>Title</b></p>';
                    surveyBox += '            <p class="_csF14">'+survey.title+'</p>';
                    surveyBox += '            <hr />';
                    surveyBox += '            <p class="_csF14"><b>Cycle Period</b></p>';
                    surveyBox += '            <p class="_csF14">'+survey.start_date+' to '+survey.end_date+' </p>';
                    surveyBox += '            <hr />';
                    surveyBox += '            <p class="_csF14"><b>Reviewer(s) Progress ?</b></p>';
                    surveyBox += '            <p class="_csF14">The percentage of reviewers who have submitted the review. Click to view details.</p>';
                    surveyBox += '            <p class="_csF3"><b>10% Completed</b></p>';
                    surveyBox += '        </div>';
                    surveyBox += '    </div>';
                    surveyBox += '</div>';

                });

                $("#jsCompanySurveysSection").html(surveyBox);
                $('.jsESLoader').hide();
            },
            error: function() {
            	$("#surveysBoxContainer").html('<p class="_csF14">Unable to load survey templates</p>');
            	$('.jsESLoader').hide();
            }
        });
	}

	getCompanySurveys();
});	
