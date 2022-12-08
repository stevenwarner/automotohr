$(function (){
	var selectedTemplate = "";
	//
	var questionFile = null;
	//
	var questionCount = 0;
	//
	var survey = "";
    //
    var questionSid = 0;
    //
    var peviousVideo = "";
	//
    window.questionFile = questionFile;
    //
    // window.REVIEW = obj;
    //
    var cp = new mVideoRecorder({
        recorderPlayer: 'jsVideoRecorder',
        previewPlayer: 'jsVideoPreview',
        recordButton: 'jsVideoRecordButton',
        playRecordedVideoBTN: 'jsVideoPlayVideo',
        removeRecordedVideoBTN: 'jsVideoRemoveButton',
        pauseRecordedVideoBTN: 'jsVideoPauseButton',
        resumeRecordedVideoBTN: 'jsVideoResumeButton',
    });
	//
	function getTemplates () {
		$.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/templates',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {

                let templateBox = '';

                resp.map(function(template) {

                    templateBox += '<div class="col-md-4 col-sm-12">';
                    templateBox += '    <div id="EST_'+template.sid+'"class="csESBox _csBD _csBD6 _csR5 _csP10 _csMt10 jsTemplateSeleceted" data-sid="' +template.sid+ '">';
                    templateBox += '        <i class="fa fa-tachometer"></i>';
                    templateBox += '        <p class="_csF16">' + template.title + '</p>';
                    templateBox += '        <br>';
                    templateBox += '        <dl>';
                    templateBox += '            <dt>Length</dt>';
                    templateBox += '            <dd>' + template.questions_count + ' Questions</dd>';
                    templateBox += '            <br>';
                    templateBox += '            <dt>Suggested frequency</dt>';
                    templateBox += '            <dd>' + template.frequency + '</dd>';
                    templateBox += '        </dl>';
                    templateBox += '        <hr>';
                    templateBox += '        <div class="text-center">';
                    templateBox += '            <a href="javascript:;" data-sid="' +template.sid+ '" class="btn _csR5 _csB3 _csF2 jsTemplatePreview">Preview</a>';
                    templateBox += '        </div>';
                    templateBox += '    </div>';
                    templateBox += '</div>';

                });

                $("#surveysBoxContainer").html(templateBox);
                $('.jsESLoader').hide();
            },
            error: function() {
            	$("#surveysBoxContainer").html('<p class="_csF14">Unable to load survey templates</p>');
            	$('.jsESLoader').hide();
            }
        });
	}
	//
	function getTemplateDetails (templateId) {
		$.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/'+ templateId +'/template',
            success: function(resp) {
                $("#jsSurveyTitle").val(resp.title);
	            $("#jsSurveyDescription").val(resp.description);
	            $('.jsESLoader').hide();
            },
            error: function() {
            	alertify.alert("NOTICE!", "Unable to load survey template detail");
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
        ratingQuestion += '			<p>1</p>';
        ratingQuestion += '			<p>Strongly Disagree</p>';
        ratingQuestion += '    	</div>';
        ratingQuestion += '		<div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '        	<p>2</p>';
        ratingQuestion += '        	<p>Strongly Disagree</p>';
        ratingQuestion += '		</div>';
        ratingQuestion += '		<div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '			<p>3</p>';
        ratingQuestion += '			<p>Strongly Disagree</p>';
        ratingQuestion += '		</div>';
        ratingQuestion += '		<div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '			<p>4</p>';
        ratingQuestion += '			<p>Strongly Disagree</p>';
        ratingQuestion += '		</div>';
        ratingQuestion += '		<div class="col-md-2 text-center _csP10 _csRatingItem">';
        ratingQuestion += '        	<p>5</p>';
        ratingQuestion += '			<p>Strongly Disagree</p>';
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
        textQuestion += '	<div class="col-xs-12">';
        textQuestion += '		<p class="_csF14 _csB2"><b>Feedback (Elaborate)</b></p>';
        textQuestion += '		<textarea rows="5" class="form-control"></textarea>';
        textQuestion += '	</div>';
        textQuestion += '</div>';
        //
        return textQuestion;
	}
	//
	function createQuestionBlock (number, text, type) {
		questionBlock = '';
		//
        questionBlock += '<div class="row">';
        questionBlock += '	<div class="col-xs-12">';
        questionBlock += '		<div class="panel panel-theme">';
        questionBlock += '			<div class="panel-heading _csB4">';
        questionBlock += ' 				<p class="_csF14 _csF2 _csMZ">';
        questionBlock += '  				<b> QUESTION ' + number + ' </b>';
        questionBlock += '  			</p>';
        questionBlock += '         	</div>';
        questionBlock += '          <div class="panel-body">';
        questionBlock += '   			<div class="row">';
        questionBlock += '                	<div class="col-md-8 col-xs-12">';
        questionBlock += '                    	<p class="_csF14">';
        questionBlock += 							text;
        questionBlock += '                      </p>';
        questionBlock += '                  </div>';
        questionBlock += '       		</div>';
        questionBlock += 				type == 'rating' ? createRationQuestion() : createTextQuestion();
        questionBlock += '   		</div>';
        questionBlock += '		</div>';
        questionBlock += '	</div>';
        questionBlock += '</div>';
        //
        return questionBlock;
	}
	//
	function createTemplateQuestion (questions) {
		var questionNo = 1;
    	var returnQuestions = '';
    	var questionBox = '';
    	const templateQuestions = JSON.parse(questions);
    	//
        templateQuestions.map(function(templatequestion) {
        	//
        	if (templatequestion.tag) {
        		var tagQuestionNo = 1;
		    	//
		        questionBox += '<div class="panel panel-default _csMt10">';
		        questionBox += '	<div class="panel-body">';
		        questionBox += '		<div class="row">';
		        questionBox += '			<div class="col-md-12 col-xs-12">';
		        questionBox += '        		<p class="_csF16 _csB2"><b>'+templatequestion.tag+'</b></p>';
		        templatequestion.questions.map(function(question) {
		        	questionBox += createQuestionBlock(tagQuestionNo, question.text, question.type);
            		tagQuestionNo++;
		        });
		        questionBox += '    		</div>';
		        questionBox += '		</div>';
		        questionBox += '	</div>';
		        questionBox += '</div>';
        	} else {
        		questionBox += createQuestionBlock(questionNo, templatequestion.text, templatequestion.type);
            	//
            	questionNo++;
        	}
            

        });
        //
		returnQuestions += '<div>';
        returnQuestions += 		questionBox;
        returnQuestions += '</div>';
        //
        return returnQuestions;
	}
	//
	function createTemplatePreview (template, ID) {
		//
    	let templateBox = '';
    	//
        templateBox += '<div class="panel panel-default _csMt10">';
        templateBox += '	<div class="panel-body">';
        templateBox += '		<div class="row">';
        templateBox += '			<div class="col-md-12 col-xs-12">';
        templateBox += '        		<p class="_csF16 _csB2"><b>'+template.title+'</b></p>';
        templateBox += '				<p class="_csF14">'+template.description+'</p>';
        templateBox += '    		</div>';
        templateBox += '		</div>';
        templateBox += '	</div>';
        templateBox += '</div>';
        templateBox += createTemplateQuestion(template.questions);
        ;
        //
        $('#'+ID).html(templateBox);
        ml(false, "jsEmployeeSurveyLoader")
	}
	//
	function saveSurveyDetails (surveyDetails) {
		$.ajax({
            type: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/'+ cToken +'/survey',
            data: JSON.stringify(surveyDetails),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
            	//
                alertify.alert('SUCCESS!','Employee Survey Saved Sucessfully.',function () {
                	//
                	var URL = baseURI+'employee/surveys/create/'+resp.id+'/questions';
                	window.location.href = URL; 
                	
                });
                //
                $('.jsESLoader').hide();
            },
            error: function() {
            	alertify.alert("NOTICE!", "Unable to save employee survey detail");
            	$('.jsESLoader').hide();
            }
        });
	}
	//
	function getCompanySurvey (surveyId, step) {
		$.ajax({
            type: 'GET',
            url: apiURI+'employee_survey/'+ surveyId +'/survey',
            success: function(resp) {
            	//
                $("#jsSurveyTitle").val(resp.title);
	            $("#jsSurveyDescription").val(resp.description);
	            $("#jsStartDate").val(moment(resp.start_date).utc().format("MM-DD-YYYY"));
	            $("#jsEndDate").val(moment(resp.end_date).utc().format("MM-DD-YYYY"));
	            //
	            let questionBox = "";
	            let questionNo = 1;
	            //
	            resp.questions.map(function(question) {
	            	questionBox += '<div class="jsBox _csBox _csP10 jsSurveyQuestionSort" id="div_'+question.sid+'" data-question_sid="'+question.sid+'">';
			        questionBox += '    <div class="row">';
			        questionBox += '        <div class="col-md-6">';
			        questionBox += '            <label>Question '+questionNo+' </label>';
			        questionBox += '        </div>';
			        questionBox += '        <div class="col-md-6 text-right">';
			        questionBox += '            <button class="btn _csR5 jsRearrangeUpQuestion" data-question_sid="'+question.sid+'" data-sort_order="'+question.sort_order+'"> <i class="fa fa-long-arrow-up" aria-hidden="true"></i></button>';
			        questionBox += '            <button class="btn _csR5 jsRearrangeDownQuestion" data-question_sid="'+question.sid+'" data-sort_order="'+question.sort_order+'"> <i class="fa fa-long-arrow-down" aria-hidden="true"></i></button>';
			        questionBox += '            <button class="btn btn-warning _csR5 jsEditQuestion" data-question_sid="'+question.sid+'"> <i class="fa fa-pencil" aria-hidden="true"></i></button>';
			        questionBox += '            <button class="btn btn-danger _csR5 jsDeleteQuestion" data-question_sid="'+question.sid+'"> <i class="fa fa-trash" aria-hidden="true"></i></button>';
			        questionBox += '        </div>';
			        questionBox += '    </div>';
					questionBox += '	<div class="row">';
					questionBox += '		<div class="col-md-12">';
					questionBox += '			<h4 class="_csF14">'+question.question_text+'</h4>';
					questionBox += '		</div>';
					questionBox += '	</div>';
					questionBox += 		question.question_type == 'rating' ? createRationQuestion() : createTextQuestion();
					questionBox += '</div>';
					//
            		questionNo++;
		        });
		        //
		        $("#jsSurveyQuestionsList").html(questionBox);
		        $("#jsSurveyQuestionCount").html("("+resp.questions_count+")");
		        questionCount = resp.questions_count
	            //
            	if (step == "details") {
            		$("#show_detail_section").show();
		            $(".step").removeClass("_csactive");
        			$(".step2").addClass("_csactive");
            	}

            	if (step == "questions") {
            		$("#show_questions_section").show();
		            $(".step").removeClass("_csactive");
        			$(".step3").addClass("_csactive");
        			//
        			$('#jsSurveyAddQuestionType').select2({
				        closeOnSelect: false
				    });
				    //
				    $('.jsVideoRecorderBox').addClass('dn');
				    //
				    $('#jsSurveyQuestionAddVideoUploadInp').mFileUploader({
			            allowedTypes: ['mp4', 'webm'],
			            fileLimit: '2mb',
			            onSuccess: function(o) {
			                questionFile = o;
			                updatePreview();
			            },
			            onClear: function(e) {
			                questionFile = null;
			                updatePreview();
			            },
			        });
            	}
            	//
            	if (step == "respondents") {
            		$("#show_respondants_section").show();
		            $(".step").removeClass("_csactive");
        			$(".step4").addClass("_csactive");
            	}
                
            },
            error: function() {
            	alertify.alert("NOTICE!", "Unable to load survey detail");
            	$('.jsESLoader').hide();
            }
        }); 
	}
	//
	function updatePreview() {
        //
        var question = {
            title: $('#jsSurveyQuestionAddTitle').val().trim(),
            description: $('#jsSurveyQuestionAddDescription').val().trim(),
            video_help: $('.jsSurveyQuestionAddVideoType:checked').val(),
            type: $('#jsSurveyAddQuestionType').val(),
            file: questionFile
        };
        //
        $('#jsSurveyQuestionAddPreviewTextBox').addClass('dn');
        $('#jsSurveyQuestionAddPreviewRatingBox').addClass('dn');
        //
        $('#jsSurveyQuestionAddPreviewVideo').addClass('dn');
        //
        $('#jsSurveyQuestionAddPreviewTitle').text(question.title);
        $('#jsSurveyQuestionAddPreviewDescription').text(question.description);
        //
        if (question.file != null) {
            $('#jsSurveyQuestionAddPreviewVideo').removeClass('dn');
            //
            var
                videoURL,
                videoType;
            //
            if (typeof(question.file) === 'object') {
                videoURL = URL.createObjectURL(question.file);
                videoType = question.type;
            } else {
                videoURL = pm.urls.base + 'assets/performance_management/videos/' + (obj.Id) + '/' + question.file;
                videoType = getVideoType(question.file);
            }
            //
            var video = '';
            video += '<video controls style="width: 100%">';
            video += '  <source src="' + (videoURL) + '" type="' + (videoType) + '"></source>';
            video += '</video>';
            $('#jsSurveyQuestionAddPreviewVideo').append(video);
        }
        //
        if (question.type.match(/rating/ig) !== null) {
            $('#jsSurveyQuestionAddPreviewRatingBox').removeClass('dn');
        }
        //
        if (question.type.match(/text/ig) !== null) {
            $('#jsSurveyQuestionAddPreviewTextBox').removeClass('dn');
        }
    }
    //
    function uploadVideo(video, questionInfo, type = "insert") {
    	var fd = new FormData();
        fd.append('upload_video', video);
        //
        $.ajax({
            type: 'POST',
            url: apiURI+'employee_survey/upload_video',
            data: fd,
            mimeType: "multipart/form-data",
		  	contentType: false,
		  	cache: false,
		  	processData: false,
		  	dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
            	//
            	console.log(resp)
            	if (!resp.file) {
            		alertify.alert('NOTICE!','Unable to upload video.',function () {
                		$('.jsESLoader').hide();
                		return;
	                });
            	} 
            	//
            	questionInfo.video = resp.file;
                //
            	type == "insert" ? saveSurveyQuestion(questionInfo) : updateSurveyQuestion(questionInfo);
            },
            error: function() {
            	alertify.alert("NOTICE!", "Unable to upload video");
            	$('.jsESLoader').hide();
            }
        });
    }
    //
    function uploadRecordedVideo(video_base64, questionInfo, type = "insert") {
        //
        $.ajax({
            type: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/upload_video',
            data: JSON.stringify({
            	upload_video_base64: video_base64
        	}),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
            	//
            	if (!resp.file) {
            		alertify.alert('NOTICE!','Unable to upload video.',function () {
                		$('.jsESLoader').hide();
                		return;
	                });
            	} 
            	//
            	questionInfo.video = resp.file;
                //
            	type == "insert" ? saveSurveyQuestion(questionInfo) : updateSurveyQuestion(questionInfo);
            },
            error: function() {
            	alertify.alert("NOTICE!", "Unable to upload video");
            	$('.jsESLoader').hide();
            }
        });
    }
    //
    function saveSurveyQuestion (surveyQuestion) {
		$.ajax({
            type: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/'+ surveyToken +'/question',
            data: JSON.stringify(surveyQuestion),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
            	//
                alertify.alert('SUCCESS!','Employee Survey Question Saved Sucessfully.',function () {
                	//
                	resetAddQuestionSection();
                	//
			    	$('#jsAddNewQuestionSection').addClass('dn');
			    	$('#jsSurveyQuestionListSection').removeClass('dn');
			    	//
			   		getCompanySurvey(surveyToken, "questions")
                	
                });
                //
                $('.jsESLoader').hide();
            },
            error: function() {
            	alertify.alert("NOTICE!", "Unable to save employee survey question");
            	$('.jsESLoader').hide();
            }
        });
	}
    //
    function updateSurveyQuestion (surveyQuestion) {
        //
        $.ajax({
            type: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/'+ questionSid +'/question',
            data: JSON.stringify(surveyQuestion),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                alertify.alert('SUCCESS!','Employee Survey Question Update Sucessfully.',function () {
                    //
                    resetAddQuestionSection();
                    //
                    $('#jsAddNewQuestionSection').addClass('dn');
                    $('#jsSurveyQuestionListSection').removeClass('dn');
                    //
                    getCompanySurvey(surveyToken, "questions");
                    //
                });
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to update employee survey question");
                $('.jsESLoader').hide();
            }
        });
    }
	//
	function resetAddQuestionSection () {
		$('#jsSurveyQuestionAddTitle').val("");
        $('#jsSurveyQuestionAddDescription').val("");
        $("input[name=jsSurveyQuestionAddVideoType][value='none']").prop("checked",true);
        $('#jsSurveyAddQuestionType').select2('val', "text");
        $('#jsSurveyQuestionAddVideoRecord').addClass('dn');
        $('#jsSurveyQuestionAddVideoUpload').addClass('dn');
        $('#jsSurveyQuestionAddPreviewTextBox').removeClass('dn');

        cp.close();
	}
	//
    function resetSortQuestions (sortOrder) {
        //
        var obj = {
            "sortOrder" : sortOrder
        }
        //
        console.log(obj)
        //
        $.ajax({
            type: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/'+ surveyToken +'/sort_question',
            data: JSON.stringify(sortOrder),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
                //
                alertify.alert('SUCCESS!','Question Sort Update Sucessfully.');
                //
                $('.jsESLoader').hide();
            },
            error: function(resp) {
                alertify.alert("NOTICE!", "Unable to update employee survey question");
                $('.jsESLoader').hide();
            }
        });
    }
	//
	surveyToken == 0 ? getTemplates() : getCompanySurvey(surveyToken, stepToken);
	//
	$(document).on('click', '.jsTemplatePreview', function(event) {
    	//
    	var templateId = $(this).data("sid");
    	//
    	Modal({
            Id: "jsEmployeeSurveyModal",
            Title: "Survey Template",
            Body:  "<div class='container'> <div id=\"jsEmployeeSurveyModalBody\"></div> </div>",
            Loader: "jsEmployeeSurveyLoader",
        }, function(){
            $.ajax({
	            type: 'GET',
	            url: apiURI+'employee_survey/'+ templateId +'/template',
	            success: function(resp) {
	            	createTemplatePreview(resp, "jsEmployeeSurveyModalBody", "jsEmployeeSurveyLoader");
	            },
	            error: function() {
	            	alertify.alert("NOTICE!", "Unable to load survey template detail");
	            	$('.jsESLoader').hide();
	            }
	        }); 
        });
	});

	/**
     * Go to template section
     */
    $(document).on('click', '.jsBackToTemplates', function(event) {
        //
        $('.jsESLoader').show();
        $(".step").removeClass("_csactive");
        $(".step1").addClass("_csactive");
        $("#show_detail_section").hide();
        $("#show_default_templates_section").show();
        $('.jsESLoader').hide();
    });

	/**
     * Select Default Template
     */
    $(document).on('click', '.jsTemplateSeleceted', function(event) {
        //
        event.preventDefault();
        var templateSid = $(this).data("sid");
        //

        //
  		$(".jsTemplateSeleceted").removeClass("active");
        $("#EST_"+templateSid).addClass("active");
        $('.jsCreateFormScratch').prop('checked', false);
        selectedTemplate = templateSid;
    });  

    $(document).on('change', '.jsCreateFormScratch', function () {
   		if ($('.jsCreateFormScratch').is(":checked")) {
   			$(".jsTemplateSeleceted").removeClass("active");
   			selectedTemplate = 0;
   		} else {
   			selectedTemplate = "";
   		}
   	});  

   	/**
     * Select Default Template
     */
    $(document).on('click', '.jsCreateSurvey', function(event) {
        if (selectedTemplate == undefined || typeof selectedTemplate == "string") {
        	alertify.alert("NOTICE!", "Please select any template or check <b>Start from scratch</b>");
        	return;
        }
        //
        $('.jsESLoader').show();
        $(".step").removeClass("_csactive");
        $(".step2").addClass("_csactive");
        $("#show_default_templates_section").hide();
        $("#show_detail_section").show();

        if (selectedTemplate == 0) {
        	$('.jsESLoader').hide();
        }

        if (selectedTemplate != 0) {
        	getTemplateDetails(selectedTemplate);;
        }
    }); 

 	$('#jsStartDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-0:+3',
        onSelect: function (value) {
            $('#jsEndDate').datepicker('option', 'minDate', value);
        }
    }).datepicker('option', 'maxDate', $('#jsEndDate').val());

    $('#jsEndDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-0:+3',
        onSelect: function (value) {
            $('#jsStartDate').datepicker('option', 'maxDate', value);
        }
    }).datepicker('option', 'minDate', $('#jsStartDate').val());

	$(document).on('click', '.jsSaveSurveyDetails', function(event) {
        //
        var surveyTitle = $("#jsSurveyTitle").val();
        var surveyDetails = $("#jsSurveyDescription").val();
        var surveyStartDate = $("#jsStartDate").val();
        var surveyEndDate = $("#jsEndDate").val();
        //
        if (surveyTitle == '') {
        	alertify.alert("WARNING!", "Please Enter Survey Title");
            return false;
        }
        //
        if (surveyDetails == '') {
        	alertify.alert("WARNING!", "Please Enter Survey Details");
            return false;
        }
        //
        if (surveyStartDate == '') {
        	alertify.alert("WARNING!", "Please Enter Survey Start Date");
            return false;

        }
        //
        if (surveyEndDate == '') {
        	alertify.alert("WARNING!", "Please Enter Survey End Date");
            return false;
        }
        //
        var surveyBasicDetails = {
            'title': surveyTitle,
            'start_date': moment(surveyStartDate).format('YYYY-MM-DD'),
            'end_date': moment(surveyEndDate).format('YYYY-MM-DD'),
            'description': surveyDetails,
            'employee_code': eToken,
            'template_code': selectedTemplate
        };
        //
        saveSurveyDetails(surveyBasicDetails);
    });

    $(document).on('click', '#jsAddNewQuestionBTN', function(event) {
    	//
    	$('#jsSurveyQuestionListSection').addClass('dn');
    	$('#jsSurveyQuestionAddPreviewVideo').addClass('dn');
    	$('#jsAddNewQuestionSection').removeClass('dn');
    	$('#jsSurveyQuestionAddPreviewTextBox').removeClass('dn');
    	$('#jsServerQuestionSaveBTN').addClass('dn');
		$('#jsServerQuestionUpdateBTN').removeClass('dn');
        //
        $('#jsServerQuestionSaveBTN').removeClass('dn');
        $('#jsServerQuestionUpdateBTN').addClass('dn');
    });

    $(document).on('click', '#jsbackToQuestionsListBTN', function(event) {
    	//
    	$('#jsAddNewQuestionSection').addClass('dn');
    	$('#jsSurveyQuestionListSection').removeClass('dn');
    	//
   		resetAddQuestionSection();
    });

    $(document).on('click', '#jsResetQuestionSectionBTN', function(event) {
   		resetAddQuestionSection();
    });

    /**
     * 
     */
    $(document).on('click', '.jsSurveyQuestionAddVideoType', function(event) {
        //
        $('#jsSurveyQuestionAddVideoRecord').addClass('dn');
        $('#jsSurveyQuestionAddVideoUpload').addClass('dn');
        //
        $('.jsVideoRecorderBox').addClass('dn');
        //
        cp.close();
        //
        switch ($(this).val()) {
            case "record":
                $('#jsSurveyQuestionAddVideoRecord').removeClass('dn');
                $('.jsVideoRecorderBox').removeClass('dn');
                cp.init();
                break;
            case "upload":
                $('#jsSurveyQuestionAddVideoUpload').removeClass('dn');
                break;
        }
        //
        updatePreview();
    });

    /**
     * 
     */
    $('#jsSurveyQuestionAddTitle, #jsSurveyQuestionAddDescription').keyup(function() {
        updatePreview();
    });

    /**
     * 
     */
    $('#jsSurveyAddQuestionType').change(function() {
        updatePreview();
    });

    /**
     * 
     */
    $('#jsServerQuestionSaveBTN').click(function(event) {
        //
        event.preventDefault();
        //
        var question = {
            text: $('#jsSurveyQuestionAddTitle').val().trim(),
            description: $('#jsSurveyQuestionAddDescription').val().trim(),
            video: "",
            video_type: $('.jsSurveyQuestionAddVideoType:checked').val(),
            sort_order: questionCount,
            type: $('#jsSurveyAddQuestionType').val()
        };
        //
        if (question.title == '') {
	        alertify.alert("WARNING!", "Please add the question title");
            return;
        }
        //
        if (question.type == 'rating') {
        	question['limit'] = 5;
        }
        // 
        if (question.video_type == 'record') {
            //
            cp.getVideo()
            .then(
                function(video) {
                    //
                    if (video == 'data:') {
                        alertify.alert("WARNING!", "Please record a video.");
                        return;
                    }
      				//
                    uploadRecordedVideo(video, question);
                },
                function(error) {
                    alertify.alert("WARNING!", "Please record the video first.");
                }
            );
        }
        //
        if (question.video_type == 'upload') {
            //
            if (questionFile == null || Object.keys(questionFile).length === 0 || questionFile.error) {
                alertify.alert("WARNING!", "Please upload a video.");
                return;
            }
            //
            uploadVideo(questionFile, question);
        }
        //
        if (question.video_type == 'none') {
            saveSurveyQuestion(question);
        }
    });

    /**
     * 
     */
    $(document).on('click', '.jsDeleteQuestion', function(event) {
        alertify.confirm(
			'Are you sure you want to delete this question?',
			() => {
				var questionSid = $(this).data("question_sid");
				//
				$.ajax({
				    url: apiURI+'employee_survey/'+ questionSid ,
				    type: 'DELETE',
				    headers: {
		                'Accept': 'application/json',
		                'Content-Type': 'application/json'
		            },
				    dataType: 'json',
		            beforeSend: function() {
		                $('.jsESLoader').show();
		            },
				    success: function(result) {
				    	alertify.alert("SUCCESS!", "Successfully delete survey question");
            			$('.jsESLoader').hide();
				    },
				    error: function(result){
				    	alertify.alert("NOTICE!", "Unable to delete survey question");
            			$('.jsESLoader').hide();
				    }
				});
			}
		).set('labels', {
			ok: 'Yes',
			cancel: 'No'
		});
    });

    /**
     * 
     */
    $(document).on('click', '.jsEditQuestion', function(event) {
    	//
    	questionSid = $(this).data("question_sid");
    	//
        $.ajax({
		    url: apiURI+'employee_survey/'+ questionSid +'/question' ,
		    type: 'GET',
		    headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
		    dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
		    success: function(resp) {
		    	$('#jsSurveyQuestionListSection').addClass('dn');
		    	$('#jsAddNewQuestionSection').removeClass('dn');
		    	$('#jsSurveyQuestionAddPreviewTextBox').removeClass('dn');
		    	$('#jsServerQuestionSaveBTN').addClass('dn');
		    	$('#jsServerQuestionUpdateBTN').removeClass('dn');
		    	//
		    	$('#jsSurveyQuestionAddTitle').val(resp.question_text);
		        $('#jsSurveyQuestionAddDescription').val(resp.question_description);
		        //
		        if (resp.question_type == "text") {
		        	$('#jsSurveyAddQuestionType').select2('val', "text");
		        } else {
		        	$('#jsSurveyAddQuestionType').select2('val', "rating");
		        }
                //
                
                console.log(questionSid)
		        //
		        updatePreview();
		        //
		        if(resp.question_video){
		        	$("#jsSurveyQuestionAddPreviewVideo").removeClass('dn');
		        	$("input[name=jsSurveyQuestionAddVideoType][value='upload']").prop("checked",true);
		        	var videoURL = baseURI+"uploads/"+resp.question_video 
		        	$("#jsVideoPreview").attr('src', videoURL);
                    peviousVideo = resp.question_video; 
		        }
		        //
		        $('.jsESLoader').hide();
		    },
		    error: function(resp){
		    	alertify.alert("NOTICE!", "Unable to delete survey question");
    			$('.jsESLoader').hide();
		    }
		});
    });

    /**
     * 
     */
    $('#jsServerQuestionUpdateBTN').click(function(event) {
        //
        event.preventDefault();
        //
        var question = {
            text: $('#jsSurveyQuestionAddTitle').val().trim(),
            description: $('#jsSurveyQuestionAddDescription').val().trim(),
            video: "",
            video_type: $('.jsSurveyQuestionAddVideoType:checked').val(),
            sort_order: questionCount,
            type: $('#jsSurveyAddQuestionType').val()
        };
        //
        if (question.title == '') {
            alertify.alert("WARNING!", "Please add the question title");
            return;
        }
        //
        if (question.type == 'rating') {
            question['limit'] = 5;
        }
        // 
        if (question.video_type == 'record') { 
            //
            cp.getVideo()
            .then(
                function(video) {
                    //
                    if (video == 'data:') {
                        alertify.alert("WARNING!", "Please record a video.");
                        return;
                    }
                    //
                    uploadRecordedVideo(video, question, "update");
                },
                function(error) {
                    alertify.alert("WARNING!", "Please record the video first.");
                }
            );
        }
        //
        if (question.video_type == 'upload') { // Upload video
            //
            if (questionFile == null || Object.keys(questionFile).length === 0 || questionFile.error) {
                if (peviousVideo.length == 0){
                    alertify.alert("WARNING!", "Please upload a video.");
                    return;
                } 

                if (peviousVideo.length > 0){
                    question.video = peviousVideo;
                    updateSurveyQuestion(question);
                } 
            } else {
                uploadVideo(questionFile, question, "update");
            }
            //
            
        }
        //
        if (question.video_type == 'none') {
            updateSurveyQuestion(question);
        }
    });
    
    /**
     * move up:
     */
    $(document).on('click', '.jsRearrangeUpQuestion', function(event) {
        //
        var sortorder = {};
        var sid = $(this).data("question_sid");
        var div = $("#div_"+sid);
        // 
        div.prev().insertAfter(div);
        //
        $('.jsSurveyQuestionSort').each(function(index,item){
            sortorder[++index] = parseInt($(item).data('question_sid'));
        });
        //
        resetSortQuestions(sortorder)
    });
    
    /**
     * move down:
     */
    $(document).on('click', '.jsRearrangeDownQuestion', function(event) {
        //
        var sortorder = {};
        var sid = $(this).data("question_sid");
        var div = $("#div_"+sid);
        //
        div.next().insertBefore(div);
        //
        $('.jsSurveyQuestionSort').each(function(index,item){
            sortorder[++index] = parseInt($(item).data('question_sid'));
        });
        //
        resetSortQuestions(sortorder)
    });



});	


