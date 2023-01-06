$(function (){
	//
	var surveyType = "pending";
    var surveyQuestions = {};
    var surveyQuestionType = "";
    var surveyQuestionSid = 0;
    var nextQuestionSid = 0;
    var nextQuestionNo = 0;
    var totalQuestions = 0;
    var completedQuestions = 0;
	//
	function getAssignedSurveys () {
        //
		$.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/'+ eToken +'/my_surveys?type='+surveyType,
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                //
                $("#jsPendingTabCount").html("("+resp.pendingCount+")");
                $("#jsCompletedTabCount").html("("+resp.completedCount+")");
                //
                var surveyBox = '';
                //
                resp.surveys.map(function(survey) {
                	surveyBox += '<div class="col-md-4 col-xs-12">';
                    surveyBox += '    <div class="panel panel-default " data-id="1" data-title="'+survey.title+'">';
                    surveyBox += '        <div class="panel-heading  _csB4 _csF2">';
                    surveyBox += '            <b>'+survey.title+'</b>';
                    surveyBox += '            <span class="pull-right">';
                    surveyBox += '                <a class="btn _csB4 _csF2 _csR5  _csF16 " title="Start the engagement" placement="top" href="'+baseURI+'employee/surveys/assigned_survey/'+ survey.sid +'">';
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
                    surveyBox += '            <p class="_csF14">'+survey.display_start_date+' <b>to</b> '+survey.display_end_date+' </p>';
                    surveyBox += '            <hr />';
                    surveyBox += '            <p class="_csF14"><b>Reviewer(s) Progress ?</b></p>';
                    surveyBox += '            <p class="_csF14">The percentage of reviewers who have submitted the review. Click to view details.</p>';
                    surveyBox += '            <p class="_csF3"><b>10% Completed</b></p>';
                    surveyBox += '        </div>';
                    surveyBox += '    </div>';
                    surveyBox += '</div>';
                });
                //
                $("#jsAssignedSurveysSection").html(surveyBox);
                //
                $('.jsESLoader').hide();
            },
            error: function() {
            	$("#surveysBoxContainer").html('<p class="_csF14">Unable to load survey templates</p>');
            	$('.jsESLoader').hide();
            }
        });
	}
    //
    function resetQuestionRating () {
        removePreviousRating();
        //
        // $('.surveyRatingDefault').addClass('active')
        // $('.surveyRatingDefault').find("p").addClass('_csF2')
    }
    //
    function removePreviousRating () {
        $('.surveyRating').removeClass("active");
        $('.surveyRating p').removeClass("_csF2");
    }
    //
    function createQuestionPreview (question, questionNo) {
        $("#jsQuestionTitle").html("Q"+questionNo+": "+question.question_text);
        $("#jsQuestionDescription").html(question.question_description);
        //
        if (question.question_video != undefined && question.question_video.length > 0) {
            var videoURL = baseURI+"uploads/"+question.question_video;
            //
            $("#jsQuestionHelpSection").show();
            $("#jsVideoQuestionHelp").attr('src', videoURL);
            $("#jsQuestionHelpSection video")[0].load();
        } else {
            $("#jsQuestionHelpSection").hide();
        }
        //
        if (question.question_type == "rating") {
            $("#jsRatingQuestion").removeClass("dn");
            $("#jsTextQuestion").addClass("dn");
            //
            
            //
            if (question.answerStatus == 1) {
                removePreviousRating()
                //
                $('.surveyRating').map( function() {
                    if ($(this).data("id") == question.answer) {
                        $(this).addClass('active')
                        $(this).find("p").addClass('_csF2')
                    }
                });
            } else {
                resetQuestionRating();
            }
        } else {
            $("#jsRatingQuestion").addClass("dn");
            $("#jsTextQuestion").removeClass("dn");
            //
            $('.jsRespondentText').val("");
            //
            if (question.answerStatus == 1) {
                $('.jsRespondentText').val(question.answer); 
            }
        }
        
        //
        surveyQuestionType = question.question_type;
        surveyQuestionSid = question.sid;
    }
    //
    function createQuestionsLink () {
        var questionList = '';
        var questionNo = 1;
        var activeQuestion = 0;
        //
        surveyQuestions.map(function(question, index) {
            //
            var btnTitle = question.answerStatus == 0 ? "pending" : "completed";
            var btnActive =  "";
            //
            if (completedQuestions < totalQuestions) {
                if (activeQuestion == 0 && question.answerStatus == 0) {
                    //
                    activeQuestion = 1;
                    btnActive =  "active";
                    createQuestionPreview(question, questionNo);
                    nextQuestionNo = (questionNo+1);
                    // 
                }
            } 

            if (completedQuestions == totalQuestions) { 
                if (activeQuestion == 0) {
                    //
                    activeQuestion = 1;
                    btnActive =  "active";
                    createQuestionPreview(question, questionNo);
                    nextQuestionNo = (questionNo+1);
                    // 
                }
            }
            //
            questionList += '<li class="'+btnActive+' jsQuestionMenuLinks" id="QLink'+question.sid+'" style="margin-right: 4px;">';
            questionList += '    <a href="javascript:;" class="jsQuestionMenuTab" data-question_sid="'+question.sid+'" title="'+ btnTitle +'" placement="top">'+ questionNo +'</a>';
            questionList += '</li>';
            //
            questionNo++;
            //
        });
        //
        
        $("#jsQuestionMenu").html(questionList);
        //
        if (nextQuestionNo < totalQuestions) {
            $('.jsQuestionMenuTab').map( function() {
                if ($(this).text() == nextQuestionNo) {
                    nextQuestionSid = $(this).data('question_sid')
                }
            });
        }
    }
    //
    function manageQuestionCount () {
        $("#jsRemainingQuestions").html("Completed " +completedQuestions + " out of " + totalQuestions + " Question(s)");
    }
    //
    async function getSpecificAssignedSurvey () {
        //
        $.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/'+ surveyToken +'/'+ eToken +'/survey',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                surveyQuestions = resp.questions;
                totalQuestions = resp.questions.length;
                completedQuestions = resp.completedQuestion;
                //
                $("#jsSurveyTitle").html(resp.survey.title);
                $("#jsSurveyStatus").html(resp.status);
                $("#jsSurveyTimePeriod").html(resp.survey.display_start_date+" <b>-</b>"+ resp.survey.display_end_date + "<br /> Due " +resp.daysLeft);
                //
                if (completedQuestions == totalQuestions) {
                    $(".jsFinisyMySurvey").removeClass("dn");
                }
                //
                if (resp.is_finished == 1) {
                    $("#jsSaveSurveyQuestionAnswer").addClass("dn");
                    $(".jsFinisyMySurvey").addClass("dn");
                }
                //
                manageQuestionCount();
                createQuestionsLink();
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                $("#surveysBoxContainer").html('<p class="_csF14">Unable to load survey</p>');
                $('.jsESLoader').hide();
            }
        });
    }
    //
    function getNewQuestion (questionSid, questionNo) {
        //
        surveyQuestions.map( function(question) {
            if (question.sid == questionSid) {
                createQuestionPreview(question, questionNo);
            }
        });
    }
    //
    function saveSurveyQuestionAnswer (answer) {
        var saveObj = {
            "survey_sid": surveyToken,
            "respondent_sid": eToken,
            "question_sid": surveyQuestionSid,
            "answer": answer
        }
        //
        $.ajax({
            type: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/answer',
            data: JSON.stringify(saveObj),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                surveyQuestions.map( function(question) {
                    if (question.sid == surveyQuestionSid) {
                        //
                        question.answer = answer;
                        //
                        if (question.answerStatus == 0) {
                            question.answerStatus = 1;
                            completedQuestions++;
                        }
                    }
                });
                //
                manageQuestionCount();
                //
                if (completedQuestions == totalQuestions) {
                    alertify.alert('SUCCESS!','Answer save sucessfully. If you want to submitted this survey then click on "Finish Survey" button',function () {
                        $(".jsFinisyMySurvey").removeClass("dn");
                        //
                        $("#jsSurveyStatus").html("COMPLETED");
                    });
                } else {
                    alertify.alert('SUCCESS!','Answer save sucessfully.',function () {
                        //
                        getNewQuestion (nextQuestionSid, nextQuestionNo);
                        $(".jsQuestionMenuLinks").removeClass("active");
                        $("#QLink"+nextQuestionSid).addClass("active");
                        //
                        nextQuestionNo++;
                        //
                        $('.jsQuestionMenuTab').map( function() {
                            if ($(this).text() == nextQuestionNo) {
                                nextQuestionSid = $(this).data('question_sid')
                            }
                        });
                    });
                }
                //
                
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to save your answer");
                $('.jsESLoader').hide();
            }
        });
    } 
    //
    surveyToken == 0 ? getAssignedSurveys() : getSpecificAssignedSurvey();
    //
     /**
     * 
     */
    $(document).on('click', '.jsSurveyTab', function(event) {
        //
        surveyType = $(this).data("survey_type");
        //
        if (surveyType == "pending") {
            $("#jsPendingTab").addClass('active');
            $("#jsCompletedTab").removeClass('active');
        }

        if (surveyType == "completed") {
            $("#jsPendingTab").removeClass('active');
            $("#jsCompletedTab").addClass('active');
        }
        //
        getAssignedSurveys()
    });

    $(document).on('click', '.jsQuestionMenuTab', function(event) {
        //
        var questionSid = $(this).data("question_sid");
        var questionNo = $(this).text();
        //
        //
        if (questionNo < totalQuestions) {
            nextQuestionNo = ++questionNo;
            $('.jsQuestionMenuTab').map( function() {
                if ($(this).text() == nextQuestionNo) {
                    nextQuestionSid = $(this).data('question_sid')
                }
            });
        }
        //
        $(".jsQuestionMenuLinks").removeClass("active");
        $("#QLink"+questionSid).addClass("active");
        //
        getNewQuestion(questionSid, questionNo);

    });

    $(document).on('click', '.surveyRating', function(event) {
        //
        $(".surveyRating").removeClass("active");
        $('.surveyRating p').removeClass("_csF2");
        //
        $(this).addClass("active");
        $(this).find("p").addClass('_csF2')
        //
    });

    $(document).on('click', '#jsSaveSurveyQuestionAnswer', function(event) {
        //
        var answer = '';
        //
        if (surveyQuestionType == "rating") {
            answer = $('._csRatingBar').find('li.active').data('id');  
        } else {
            answer = $('.jsRespondentText').val(); 
        }
        //
        saveSurveyQuestionAnswer(answer);
    });  

    $(document).on('click', '.jsFinisyMySurvey', function(event) {
        var obj = {
            "survey_sid": surveyToken,
            "respondent_sid": eToken
        }
        //
        $.ajax({
            type: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/finish',
            data: JSON.stringify(obj),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                alertify.alert('SUCCESS!','Survey submitted Successfully',function () {
                    $("#jsSaveSurveyQuestionAnswer").addClass("dn");
                    $(".jsFinisyMySurvey").addClass("dn");
                });
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to save your answer");
                $('.jsESLoader').hide();
            }
        });
    });  
});    