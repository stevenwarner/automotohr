$(function (){
	//
    var employees = {};
    var departments = {};
	var surveyType = "running";
    var surveyQuestions = {};
    var surveyRespondents = {};
    var publishSurveyID = 0;
	//
	function getCompanySurveys () {
        //
		$.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/'+ cToken +'/surveys?type='+surveyType+'&questions=true&respondents=true',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                //
                $("#jsAssignedTabCount").html("("+resp.assignedCount+")");
                $("#jsDraftTabCount").html("("+resp.draftCount+")");
                $("#jsRunningTabCount").html("("+resp.runningCount+")");
                $("#jsFinishedTabCount").html("("+resp.completedCount+")");
                //
                var surveyBox = '';
                //
                resp.surveys.map(function(survey) {
                	surveyBox += '<div class="col-md-4 col-xs-12">';
                    surveyBox += '    <div class="panel panel-default " data-id="1" data-title="'+survey.title+'">';
                    surveyBox += '        <div class="panel-heading  _csB4 _csF2">';
                    surveyBox += '            <b class="">'+survey.title+'</b>';
                    surveyBox += '            <span class="pull-right">';
                    //
                    if (surveyType == "assigned" || surveyType == "draft") {
                        surveyBox += '                <a class="btn _csB4 _csF2 _csR5 _csF16 jsManageSurveyPeriod" data-toggle="tooltip" data-placement="top" data-sid="'+survey.sid+'" data-start_date="'+survey.start_date+'" data-end_date="'+survey.end_date+'" title="Manage Engagement Dates" href="javascript:;">';
                        surveyBox += '                    <i class="fa fa-cogs csF16" aria-hidden="true"></i>';
                        surveyBox += '                </a>';
                    }
                    //
                    if (surveyType == "draft") {
                        surveyBox += '                <a class="btn _csB4 _csF2 _csR5 _csF16" data-toggle="tooltip" data-placement="top" title="Edit Engagement" href="'+baseURI+'employee/surveys/create/'+ survey.sid +'/details">';
                        surveyBox += '                    <i class="fa fa-pencil csF16" aria-hidden="true"></i>';
                        surveyBox += '                </a>';
                        if (survey.surveyRespondentsCount > 0 && survey.surveyQuestionCount > 0) {
                            surveyBox += '                <a class="btn _csB4 _csF2 _csR5 _csF16 jsReadyToPublish" data-toggle="tooltip" data-placement="top" data-sid="'+survey.sid+'" title="Publish Engagement" href="javascript:;">';
                            surveyBox += '                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>';
                            surveyBox += '                </a>'; 
                        }
                        
                    }
                    //
                    surveyBox += '            </span>';
                    surveyBox += '            <div class="clearfix"></div>';
                    surveyBox += '        </div>';
                    surveyBox += '        <div class="panel-body">';
                    surveyBox += '            <p class="_csF14"><b>Title</b></p>';
                    surveyBox += '            <p class="_csF18 _csFb6">'+survey.title+'</p>';
                    surveyBox += '            <hr />';
                    surveyBox += '            <p class="_csF14"><b>Cycle Period</b></p>';
                    surveyBox += '            <p class="_csF14">'+survey.display_start_date+' <b>to</b> '+survey.display_end_date+' </p>';
                    surveyBox += '            <hr />';
                    surveyBox += '            <p class="_csF14">';
                    surveyBox += '                  <b>Question(s): </b>('+survey.surveyQuestionCount+')';
                    surveyBox += '                  <i class="fa fa-eye csF16 jsSurveyQuestionDetail" data-type="questions" data-sid="'+survey.sid+'" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="View Questions"></i>';
                    surveyBox += '            </p>';
                    surveyBox += '            <p class="_csF14"><b>Respondent(s): </b>('+survey.surveyRespondentsCount+')</p>';
                    surveyBox += '            <p class="_csF14">'+survey.surveyCompletedRespondentsCount+' out of '+survey.surveyRespondentsCount+' respondent(s) submitted their feedback.</p>';
                    surveyBox += '            <p class="_csF3"><b>'+survey.surveyCompletedRespondentsPercentage+'% Completed</b></p>';
                    surveyBox += '        </div>';
                    surveyBox += '    </div>';
                    surveyBox += '</div>';
                });
                //
                $("#jsCompanySurveysSection").html(surveyBox);
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
    async function getCompanySpecificSurvey (surveyId) {
        var departmentInfo = [];
        //
        employees = await getCompanyEmployees("all", "all", "all", "all", "all");
        departments = await getCompanyDepartments();
        //
        if (departments.length) {
            departments.map(function(department) {
                departmentInfo[department['sid']] = department['name'];
            });
        }
        //
        departments = departmentInfo;
        //
        $.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/'+ surveyId +'/survey',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                surveyQuestions = resp.surveyQuestion;
                surveyRespondents = resp.surveyRespondents;
                //
                if (resp.surveyInfo.is_draft == 0) {
                    if (resp.surveyInfo.is_archived == 0) {
                        $("#jsStopSurvey").removeClass("dn");
                        $("#jsStartSurvey").addClass("dn");
                    }
                    //
                    if (resp.surveyInfo.is_archived == 1) {
                        $("#jsStartSurvey").removeClass("dn");
                        $("#jsStopSurvey").addClass("dn");
                    }
                }
                //
                $("#jsSurveyTitle").html(resp.surveyInfo.title);
                $("#jsSurveyPercentage").html(resp.surveyCompletedRespondentsPercentage);
                $("#jsSurveyCompletedRespondents").html(resp.surveyCompletedRespondentsCount);
                $("#jsSurveyTotalRespondents").html(resp.surveyRespondentsCount);
                $("#jsSurveyCreaterName").html(resp.surveyCreatorName);
                $("#jsSurveyQuestionsCount").html(resp.surveyQuestionCount);
                $("#jsSurveyRespondentsCount").html(resp.surveyRespondentsCount);
                $("#jsSurveyTimePeriod").html(resp.surveyInfo.display_start_date+" <b>to</b> "+resp.surveyInfo.display_end_date);
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
    function createRationQuestion () {
        ratingQuestion = '';
        //
        ratingQuestion += '<div class="row _csMt10 _csRatingBox">';
        ratingQuestion += '    <div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '         <p>1</p>';
        ratingQuestion += '         <p>Strongly Disagree</p>';
        ratingQuestion += '     </div>';
        ratingQuestion += '     <div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '         <p>2</p>';
        ratingQuestion += '         <p>Strongly Disagree</p>';
        ratingQuestion += '     </div>';
        ratingQuestion += '     <div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '         <p>3</p>';
        ratingQuestion += '         <p>Strongly Disagree</p>';
        ratingQuestion += '     </div>';
        ratingQuestion += '     <div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '         <p>4</p>';
        ratingQuestion += '         <p>Strongly Disagree</p>';
        ratingQuestion += '     </div>';
        ratingQuestion += '     <div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '         <p>5</p>';
        ratingQuestion += '         <p>Strongly Disagree</p>';
        ratingQuestion += '    </div>';
        ratingQuestion += '</div>';
        //
        return ratingQuestion;
    }
    //
    function createTextQuestion () {
        textQuestion = '';
        //
        textQuestion += '<div class="row"><br>';
        textQuestion += '   <div class="col-xs-12">';
        textQuestion += '       <p class="_csF14 _csB2"><b>Feedback (Elaborate)</b></p>';
        textQuestion += '       <textarea rows="5" class="form-control"></textarea>';
        textQuestion += '   </div>';
        textQuestion += '</div>';
        //
        return textQuestion;
    }
    //
    function createQuestionBlock (number, question) {
        questionBlock = '';
        //
        questionBlock += '<div class="row">';
        questionBlock += '  <div class="col-xs-12">';
        questionBlock += '      <div class="panel panel-theme">';
        questionBlock += '          <div class="panel-heading _csB4">';
        questionBlock += '              <p class="_csF14 _csF2 _csMZ">';
        questionBlock += '                  <b> QUESTION ' + number + ' </b>';
        questionBlock += '              </p>';
        questionBlock += '          </div>';
        questionBlock += '          <div class="panel-body">';
        questionBlock += '              <div class="row">';
        questionBlock += '                  <div class="col-md-8 col-xs-12">';
        questionBlock += '                      <p class="_csF16 _csFb6">';
        questionBlock +=                            question.question_text;
        questionBlock += '                      </p>';
        questionBlock += '                  </div>';
        questionBlock += '              </div>';

        questionBlock += '    <div class="row">';
        questionBlock += '    <div class="col-md-8 col-xs-12">';
        
        if (question.question_description && question.question_description.length > 0) {
            questionBlock += '        <p class="_csF14">';
            questionBlock +=              question.question_description;
            questionBlock += '        </p>';
        }    
        questionBlock += '    </div>';
        questionBlock += '    <div class="col-md-4 col-xs-12 jsQuestionHelpVideo">';
        if (question.question_video && question.question_video.length > 0) {
            var videoURL = baseURI+"uploads/"+question.question_video;
            questionBlock += '        <video autoplay controls style="width: 100%;" preload="metadata">';
            questionBlock += '            <source src="'+videoURL+'" type="video/webm">';
            questionBlock += '            </source>';
            questionBlock += '            <track label="English" kind="captions" srclang="en" default />';
            questionBlock += '        </video>';
        } 
        
        questionBlock += '    </div>';
        questionBlock += '</div>';

        questionBlock +=                question.question_type == 'rating' ? createRationQuestion() : createTextQuestion();
        questionBlock += '          </div>';
        questionBlock += '      </div>';
        questionBlock += '  </div>';
        questionBlock += '</div>';
        //
        return questionBlock;
    }
    //
    function createSurveyQuestionPreview () {
        var returnQuestions = '';
        var questionNo = 1;
        var questionBox = '';
        var tagTemplate = 0;
        var questionCount = 0;
        //
        if (surveyQuestions.length) {
            surveyQuestions.map(function(surveyQuestion, index) {
                //
                if (surveyQuestion.question_tag) {
                    tagTemplate = 1;
                    var tagQuestionNo = 1;
                    //
                    questionBox += '<div class="panel panel-default _csMt10">';
                    questionBox += '    <div class="panel-heading _csF16 _csFb6">';
                    questionBox +=          surveyQuestion.question_tag;
                    questionBox += '    </div>';
                    questionBox += '    <div class="panel-body">';
                    questionBox += '        <div class="row">';
                    questionBox += '            <div class="col-md-12 col-xs-12">';
                    
                    surveyQuestion.questions.map(function(question) {
                        questionBox += createQuestionBlock(tagQuestionNo, question);
                        tagQuestionNo++;
                    });
                    questionBox += '            </div>';
                    questionBox += '        </div>';
                    questionBox += '    </div>';
                    questionBox += '</div>';

                    questionCount = tagQuestionNo;
                } else {
                    
                    questionBox += createQuestionBlock(questionNo, surveyQuestion);
                    //
                    questionNo++;
                    questionCount = surveyQuestions.length;
                }
            });
        } else {
            questionBox += '<div class="text-center" style="font-size:22px;"><b>No Questions Added Yet!</b></div>';
        }
        //
        if (tagTemplate == 0) {
            //
            returnQuestions += '<div class="panel panel-default _csMt10">';
            returnQuestions += '    <div class="panel-body">';
            returnQuestions += '        <div class="row">';
            returnQuestions += '            <div class="col-md-12 col-xs-12">';
            returnQuestions +=                  questionBox;
            returnQuestions += '            </div>';
            returnQuestions += '        </div>';
            returnQuestions += '    </div>';
            returnQuestions += '</div>';
        } else {
            returnQuestions = questionBox;
        }
        //
        return {"html": returnQuestions,"count": questionCount}
    }
    //
    function getCompanyEmployees(departments, included, excluded, type, title) {
        
        return new Promise(resolve => {
            $.ajax({
                type: 'GET',
                url: apiURI+'employee/'+ cToken +'?columns=sid,fullName,role,department&department_sids='+departments+'&included_sids='+included+'&excluded_sids='+excluded+'&employee_types='+type+'&job_titles='+title,
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
    function createSurveyRespondentPreview () {
        //
        var employeeRow = "";
        var employeeNo = 0;
        //
        employeeRow += '<div class="table-responsive">';
        employeeRow += '    <table class="table table-striped">';
        employeeRow += '        <caption></caption>';
        employeeRow += '        <thead>';
        employeeRow += '            <tr class="_csB4 _csF2">';
        employeeRow += '                <th scope="col">Employee</th>';
        employeeRow += '                <th scope="col">Department/Team</th>';
        employeeRow += '            </tr>';
        employeeRow += '        </thead>';
        employeeRow += '        <tbody>';
        //
        if (employees.length) {
            if (surveyRespondents.length) {
                employees.map(function(employee) {
                    if($.inArray(employee.sid, surveyRespondents) !== -1) {
                        employeeRow += '<tr class="jsSelectedEmployees" data-employee_sid="'+employee.sid+'">';
                        employeeRow += '<th scope="col">'+remakeEmployeeName(employee)+'</th>';

                        if (departments.length) {
                            employeeRow += employee.department_sid != 0 ? '<td>'+departments[employee.department_sid]+'</td>' :  '<td>No Department</td>';
                        } else {
                            employeeRow += '<td>No Department</td>';
                        }
                        employeeRow += '</tr>';
                        //
                        employeeNo++; 
                    }
                });
            } else {
               employeeRow += '<tr><td colspan="2" class="text-center" style="font-size:22px;"><b>No Respondents Assign Yet!</b></td></tr>'; 
            }    
        } else {
            employeeRow += '<tr><td colspan="2" class="text-center" style="font-size:22px;"><b>No Employee Found!</b></td></tr>';
        } 
        //
        employeeRow += '        </tbody>';
        employeeRow += '    </table>';
        employeeRow += '</div>';
        //
        return {"html": employeeRow,"count": employeeNo}
    }
    //
    function remakeEmployeeName(o, i) {
        //
        var r = '';
        //
        if (i == undefined) r += o.first_name + ' ' + o.last_name;
        //
        if (o.job_title != '' && o.job_title != null) r += ' (' + (o.job_title) + ')';
        //
        r += ' [';
        //
        if (typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
        //
        if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
        else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
        else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
        else r += o['access_level'];
        //
        r += ']';
        //
        return r;
    }
    //
    function publishCompanySurvey (surveyInfo) {
        publishSurveyID = surveyInfo.survey_id;
        //
        $('#jsSurveyPublishModal').show();
        var rows = '';
        rows += '    <div class="row">';
        rows += '        <div class="col-sm-12">';
        rows += '            <div class="responsive">';
        rows += '               <p>Are you sure you want to send the survey to all the <b>' +surveyInfo.respondents+ ' workers</b> starting on <b>'+ surveyInfo.start_date + '</b> ?</p>';
        rows += '               <p>Notifications will be sent out as follows:</p>';
        rows += '                   <table class="table table-striped">';
        rows += '                         <thead>';
        rows += '                           <tr>';
        rows += '                              <th>Notification</th>';
        rows += '                              <th class="text-right">When it will be sent?</th>';
        rows += '                           </tr>';
        rows += '                         </thead>';
        rows += '                         <tbody>';
        rows += '                           <tr>';
        rows += '                              <td>Start date</td>';
        rows += '                              <td class="text-right">'+surveyInfo.start_date+'</td>';
        rows += '                           </tr>';
        rows += '                           <tr>';
        rows += '                              <td>Halfway reminder</td>';
        rows += '                              <td class="text-right">'+surveyInfo.halfway_date+'</td>';
        rows += '                           </tr>';
        rows += '                           <tr>';
        rows += '                              <td>Final reminder</td>';
        rows += '                              <td class="text-right">'+surveyInfo.final_date+'</td>';
        rows += '                           </tr>';
        rows += '                         </tbody>';
        rows += '                   </table>';
        rows += '            </div>';
        rows += '        </div>';
        //
        $('#jsSurveyConfirmationHeading').html(rows);
    }
    //
	surveyToken == 0 ? getCompanySurveys() : getCompanySpecificSurvey(surveyToken);
    //
    /**
     * 
     */
    $(document).on('click', '.jsSurveyTab', function(event) {
        //
        surveyType = $(this).data("survey_type");
        //
        if (surveyType == "assigned") {
            $(".jsSurveyTab").removeClass('active');
            $("#jsAssignedTab").addClass('active');
            // $("#jsDraftTab").removeClass('active');
            // $("#jsFinishedTab").removeClass('active');
        }

        if (surveyType == "draft") {
            $(".jsSurveyTab").removeClass('active');
            $("#jsDraftTab").addClass('active');
            // $("#jsActiveTab").removeClass('active');
            // $("#jsFinishedTab").removeClass('active');
        }

        if (surveyType == "finished") {
            $(".jsSurveyTab").removeClass('active');
            // $("#jsActiveTab").removeClass('active');
            // $("#jsDraftTab").removeClass('active');
            $("#jsFinishedTab").addClass('active');
        }
        //
        if (surveyType == "running") {
            $(".jsSurveyTab").removeClass('active');
            // $("#jsActiveTab").removeClass('active');
            // $("#jsDraftTab").removeClass('active');
            $("#jsRunningTab").addClass('active');
        }
        //
        getCompanySurveys()
    });
    //
    $(document).on('click', '.jsSurveyDetail', function(event) {
        var type = $(this).data("type");
        //
        var modalTitle = "";
        var modalBody = "";
        var modalBodyCount = 0;
        //
        if (type == "questions") {
            var questionsData = createSurveyQuestionPreview();
            //
            modalTitle = "Survey Questions ("+ questionsData.count +")";
            modalBody = questionsData.html; 
        } else {
            var respondantsData = createSurveyRespondentPreview();
            //
            modalTitle = "Survey Respondents ("+ respondantsData.count +")";
            modalBody = respondantsData.html; 
        }

        Modal({
            Id: "jsEmployeeSurveyModal",
            Title: modalTitle,
            Body:  "<div class='container'> <div id=\"jsEmployeeSurveyModalBody\"></div> </div>",
            Loader: "jsEmployeeSurveyLoader",
        }, function(){
            $('#jsEmployeeSurveyModalBody').html(modalBody); 
            ml(false, "jsEmployeeSurveyLoader")
        });
    });

    $(document).on('click', '.jsSurveyQuestionDetail', function(event) {
        var surveyId = $(this).data("sid");
        //
        $.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/'+ surveyId +'/questions',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                surveyQuestions = resp;
                //
                var questionsData = createSurveyQuestionPreview();
                //
                Modal({
                    Id: "jsEmployeeSurveyModal",
                    Title: "Survey Questions ("+ questionsData.count +")",
                    Body:  "<div class='container'> <div id=\"jsEmployeeSurveyModalBody\"></div> </div>",
                    Loader: "jsEmployeeSurveyLoader",
                }, function(){
                    $('#jsEmployeeSurveyModalBody').html(questionsData.html); 
                    ml(false, "jsEmployeeSurveyLoader");
                    $('.jsESLoader').hide();
                });
            },
            error: function() {
                $("#surveysBoxContainer").html('<p class="_csF14">Unable to load survey questions</p>');
                $('.jsESLoader').hide();
            }
        }); 
    });

    $(document).on('click', '.jsActionButton', function(event) {
        var state = $(this).data("survey_status");
        //
        $.ajax({
            type: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/'+ surveyToken +'/start_stop_survey',
            data: JSON.stringify({"survey_state": state}),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                var message = "Employee survey stop successfully.";
                //
                if (state == "start") {
                    message = "Employee survey start successfully.";
                    $("#jsStopSurvey").removeClass("dn");
                    $("#jsStartSurvey").addClass("dn");
                }
                //
                if (state == "stop") {
                    message = "Employee survey stop successfully.";
                    $("#jsStartSurvey").removeClass("dn");
                    $("#jsStopSurvey").addClass("dn")
                }
                //
                alertify.alert('SUCCESS!', message);
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to change survey status");
                $('.jsESLoader').hide();
            }
        });
    });

    $(document).on('click', '.jsManageSurveyPeriod', function(event) {
        var surveyID = $(this).data("sid");
        var startDate = $(this).data("start_date");
        var endDate = $(this).data("end_date");
        //
        $('#jsSurveyManageModal').show();
        $('#jsStartDate').val(startDate);
        $('#jsEndDate').val(endDate);
        $('#jsSurveyId').val(surveyID);
    });

    $(document).on('click', '.jsCancelManageDate', function(event) {
        $("#jsSurveyManageModal").hide();
    });

    $(document).on('click', '.jsSaveSurveyDates', function(event) {
        var surveyID = $('#jsSurveyId').val();
        var startDate = $('#jsStartDate').val();
        var endDate = $('#jsEndDate').val();

       

        if (moment(startDate).isSameOrAfter(endDate)) {
            alertify.alert("WARNING!", "Please select end date greater then start date");
            return false;
        }

        $.ajax({
            type: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/'+ surveyID +'/manage_survey_dates',
            data: JSON.stringify({
                'start_date': moment(startDate).format('YYYY-MM-DD'),
                'end_date': moment(endDate).format('YYYY-MM-DD'),
            }),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                alertify.alert('SUCCESS!', "Engagement period is update successfully",function () {
                    $("#jsSurveyManageModal").hide();
                    getCompanySurveys();
                });
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to change survey status");
                $('.jsESLoader').hide();
            }
        });
    });

    $('#jsStartDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-0:+3',
        minDate: 0,
        onSelect: function (value) {
            $('#jsEndDate').datepicker('option', 'minDate', value);
        }
    }).datepicker('option', 'maxDate', $('#jsEndDate').val());

    $('#jsEndDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-0:+3',
        minDate: 0,
        onSelect: function (value) {
            $('#jsStartDate').datepicker('option', 'maxDate', value);
        }
    }).datepicker('option', 'minDate', $('#jsStartDate').val());

    $(document).on('click', '.jsReadyToPublish', function(event) {
        var surveyID = $(this).data("sid");

        $.ajax({
            type: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/'+ surveyID +'/publish_info',
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                publishCompanySurvey(resp)
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to change survey status");
                $('.jsESLoader').hide();
            }
        });
    });

    $(document).on('click', '.jsPublishSurvey', function(event) {
        $.ajax({
            type: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/'+ publishSurveyID +'/publish',
            data: JSON.stringify({"employee_code": eToken}),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                alertify.alert('SUCCESS!','Employee survey Publish sucessfully.',function () {
                    var URL = baseURI+'employee/surveys/surveys';
                    window.location.href = URL;  
                });
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to publish survey");
                $('.jsESLoader').hide();
            }
        });
    });

    $(document).on('click', '.jsCancelConfermation', function(event) {
        $("#jsSurveyPublishModal").hide();
    });
});	
