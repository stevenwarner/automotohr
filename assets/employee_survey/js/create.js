$(function (){
	var selectedTemplate = "";
	//
	var questionFile = null;
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
            	alertify.alert("NOTICE!", "Unable to load survey template detail</b>");
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
            	alertify.alert("NOTICE!", "Unable to save employee survey detail</b>");
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
	            	questionBox += '<div class="jsBox _csBox _csP10">';
			        questionBox += '    <div class="row">';
			        questionBox += '        <div class="col-md-6">';
			        questionBox += '            <label>Question '+questionNo+' </label>';
			        questionBox += '        </div>';
			        questionBox += '        <div class="col-md-6 text-right">';
			        questionBox += '            <button class="btn _csR5"> <i class="fa fa-long-arrow-up" aria-hidden="true"></i></button>';
			        questionBox += '            <button class="btn _csR5"> <i class="fa fa-long-arrow-down" aria-hidden="true"></i></button>';
			        questionBox += '            <button class="btn btn-warning _csR5"> <i class="fa fa-pencil" aria-hidden="true"></i></button>';
			        questionBox += '            <button class="btn btn-danger _csR5"> <i class="fa fa-trash" aria-hidden="true"></i></button>';
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
		        $("#jsSurveyQuestions").html(questionBox);
		        $("#jsQuestionCount").html("("+resp.questions_count+")");
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
            	alertify.alert("NOTICE!", "Unable to load survey detail</b>");
            	$('.jsESLoader').hide();
            }
        }); 
	}
	//
	function addQuestionTemplate () {
		let questionsection = '';
		//
		questionsection += '<div class="container">';
		questionsection += '	<div class="panel panel-default _csMt20 _csPR _csR5 ">';
		questionsection += '	    <div class="panel-heading">';
		questionsection += '	        <div class="row">';
		questionsection += '	            <div class="col-md-12 col-sm-12 ">';
		questionsection += '	                <b>Add Question</b>';
		questionsection += '	            </div>';
		questionsection += '	        </div>';
		questionsection += '	    </div>';
		questionsection += '	    <div class="panel-body">';
		questionsection += '	            <div class="row">';
		questionsection += '	                <div class="col-md-3 col-sm-12">';
		questionsection += '	                    <label>Question <span class="text-danger">*</span></label>';
		questionsection += '	                </div>';
		questionsection += '	                <div class="col-md-9 col-sm-12">';
		questionsection += '	                    <input type="text" class="form-control jsQuestionText" required />';
		questionsection += '	                </div>';
		questionsection += '	            </div>';
		questionsection += '	            <br>';
		questionsection += '	            <div class="row">';
		questionsection += '	                <div class="col-md-3 col-sm-12 _csMt10">';
		questionsection += '	                    <label>Description </label>';
		questionsection += '	                    <p>Explain to the employees what they need to add into the answer.</p>';
		questionsection += '	                </div>';
		questionsection += '	                <div class="col-md-9 col-sm-12 _csMt10">';
		questionsection += '	                    <textarea class="form-control _csHeight100 jsQuestionDescription"></textarea>';
		questionsection += '	                </div>';
		questionsection += '	            </div>';
		questionsection += '	            <br>';
		questionsection += '				<div class="row">';
		questionsection += '					<br>';
		questionsection += '					<div class="col-sm-4 col-xs-12">';
		questionsection += '						<label class="csF16 csB7">Video Help <i class="fa fa-question-circle-o jsHintBtn csCP" data-target="video_help" aria-hidden="true"></i></label>';
		questionsection += '						<p class="csF14 jsHintBody" data-hint="video_help">Record/Upload a video explaining the reviewer what to add into the answer.</p>';
		questionsection += '					</div>';
		questionsection += '					<div class="col-sm-8 col-xs-12">';
		questionsection += '						<label class="control control--radio csF16">';
		questionsection += '							<input type="radio" class="jsSurveyQuestionAddVideoType" name="jsSurveyQuestionAddVideoType" value="none" checked/> None';
		questionsection += '							<div class="control__indicator"></div>';
		questionsection += '						</label>';
		questionsection += '						<br>';
		questionsection += '						<label class="control control--radio csF16">';
		questionsection += '							<input type="radio" class="jsSurveyQuestionAddVideoType" name="jsSurveyQuestionAddVideoType" value="record" /> Record Video';
		questionsection += '							<div class="control__indicator"></div>';
		questionsection += '						</label>';
		questionsection += '						<br>';
		questionsection += '						<label class="control control--radio csF16">';
		questionsection += '							<input type="radio" class="jsSurveyQuestionAddVideoType" name="jsSurveyQuestionAddVideoType" value="upload" /> Upload Video';
		questionsection += '							<div class="control__indicator"></div>';
		questionsection += '						</label>';
		questionsection += '						<br>';
		questionsection += '						<div id="jsSurveyQuestionAddVideoUpload" class="dn">';
		questionsection += '							<input type="file" id="jsSurveyQuestionAddVideoUploadInp" class="hidden" />';
		questionsection += '						</div>';
		questionsection += '						<br>';
		questionsection += '						<div id="jsSurveyQuestionAddVideoRecord" class="dn">';
		questionsection += '							<div class="row">';
		questionsection += '								<div class="col-sm-12">';
		questionsection += '									<div class="jsVideoRecorderBox">';
		questionsection += '										<p class="csF16 csB7 csInfo"><i class="fa fa-info-circle csF18" aria-hidden="true"></i>&nbsp;To use this feature, please, make sure you have allowed microphone and camera access.</p>';
		questionsection += '								    </div>';
		questionsection += '								</div>';
		questionsection += '								<div class="col-sm-12 col-xs-12">';
		questionsection += '									<div class="jsVideoRecorderBox">';
		questionsection += '								    	<video id="jsVideoRecorder" width="100%"></video>';
		questionsection += '								        <button class="btn btn-orange btn-lg csF16 dn" id="jsVideoRecordButton"><i aria-hidden="true" class="fa fa-stop csF16"></i> Start Recording</button>';
		questionsection += '								        <button class="btn btn-black btn-lg csF16 dn" id="jsVideoPauseButton"><i aria-hidden="true" class="fa fa-pause-circle csF16"></i> Pause Recording</button>';
		questionsection += '								        <button class="btn btn-black btn-lg csF16 dn" id="jsVideoResumeButton"><i aria-hidden="true" class="fa fa-play-circle csF16"></i> Resume Recording</button>';
		questionsection += '								    </div>';
		questionsection += '								</div>';
		questionsection += '							 </div>';
		questionsection += '						</div>';
		questionsection += '					</div>';
		questionsection += '				</div>';
		questionsection += '	            <br>';
		questionsection += '	            <div class="row">';
		questionsection += '	                <div class="col-md-3 col-sm-12 _csMt10">';
		questionsection += '	                    <label>Question Type </label>';
		questionsection += '	                    <p>Select the type of the question.</p>';
		questionsection += '	                </div>';
		questionsection += '	                <div class="col-md-9 col-sm-12 _csMt10">';
		questionsection += '	                    <select name="" id="jsQuestionType">';
		questionsection += '	                        <option value="text">Text</option>';
		questionsection += '	                        <option value="rating">Rating</option>';
		questionsection += '	                    </select>';
		questionsection += '	                </div>';
		questionsection += '	            </div>';
		questionsection += '	            <br>';
		questionsection += '	            <div class="row">';
		questionsection += '	                <div class="col-md-12 text-right col-sm-12 _csMt10">';
		questionsection += '	                    <button class="btn _csB1 _csF2 _csR5 jsModalCancel">Cancel</button>';
		questionsection += '	                    <button class="btn _csB4 _csF2 _csR5">Save</button>';
		questionsection += '	                </div>';
		questionsection += '	            </div>';
		questionsection += '	        <hr>';
		questionsection += '	        <div class="jsBox _csBox _csP10">';
		questionsection += '	            <div class="row _csB4 _csF2">';
		questionsection += '	                <div class="col-md-12">';
		questionsection += '	                    <h4>Question 1</h4>';
		questionsection += '	                </div>';
		questionsection += '	            </div>';
		questionsection += '	            <div class="row">';
		questionsection += '	                <div class="col-md-12">';
		questionsection += '	                    <h4 class="_csF14"><strong>Overall, I am satisfied with the benefits package my organization offers.</strong></h4>';
		questionsection += '	                </div>';
		questionsection += '	            </div>';
		questionsection += '	            <div class="row">';
		questionsection += '	                <div class="col-md-12">';
		questionsection += '	                    <h4 class="_csF14">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quaerat, excepturi illo doloribus accusantium est blanditiis nobis voluptatum quidem fugit optio at, unde fuga debitis, earum incidunt odit quis magni ex.</h4>';
		questionsection += '	                </div>';
		questionsection += '	            </div>';
		questionsection += '	            <div>';
		questionsection += '	            </div>';
		questionsection += '	            <div>';
		questionsection += '	            </div>';
		questionsection += '	        </div>';
		questionsection += '	    </div>';
		questionsection += '	</div>';
		questionsection += '</div>';
		//
		return questionsection;
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
        console.log(question.type)
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
    function saveSurveyQuestion (surveyQuestion) {
		$.ajax({
            type: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: apiURI+'employee_survey/12/question',
            data: JSON.stringify(surveyQuestion),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(resp) {
            	//
                alertify.alert('SUCCESS!','Employee Survey Question Saved Sucessfully.',function () {
                	//
                	var URL = baseURI+'employee/surveys/create/12/questions';
                	window.location.href = URL; 
                	
                });
                //
                $('.jsESLoader').hide();
            },
            error: function() {
            	alertify.alert("NOTICE!", "Unable to save employee survey question</b>");
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
	            	alertify.alert("NOTICE!", "Unable to load survey template detail</b>");
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

    $(document).on('click', '.jsAddNewQuestion', function(event) {
    	//
    	Modal({
            Id: "jsEmployeeSurveyQuestionModal",
            Title: "Add Survey Question",
            Body:  addQuestionTemplate(),
            Loader: "jsEmployeeSurveyLoader",
        }, function(){
        	$('#jsSurveyAddQuestionType').select2({
		        closeOnSelect: false
		    });
		    //
		    $('.jsVideoRecorderBox').addClass('dn');
		    //
            ml(false, "jsEmployeeSurveyLoader")
        });
    });

    /**
     * 
     */
    $(document).on('click', '.jsSurveyQuestionAddVideoType', function(event) {
    	console.log("pakistan")
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
    //
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
    $('#jsServerQuestionSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        var question = {
            text: $('#jsSurveyQuestionAddTitle').val().trim(),
            description: $('#jsSurveyQuestionAddDescription').val().trim(),
            video_help: $('.jsSurveyQuestionAddVideoType:checked').val(),
            video: "",
            sort_order: "1",
            not_applicable: "0",
            type: $('#jsSurveyAddQuestionType').val()
        };
        //
        if (question.title == '') {
	        alertify.alert("WARNING!", "Please add the question title");
            return;
        }

        // 
        if (question.video_help == 'record') { // Upload Recorded Video
            //
            cp.getVideo()
            .then(
                function(video) {
                    //
                    if (video == 'data:') {
                        alertify.alert("WARNING!", "Please record a video.");
                        return;
                    }
                    
                    question.video = video;
                },
                function(error) {
                    alertify.alert("WARNING!", "Please record the video first.");
                }
            );
        }
        //
        if (question.video_help == 'upload') { // Upload video
            //
            if (questionFile == null || Object.keys(questionFile).length === 0 || questionFile.error) {
                alertify.alert("WARNING!", "Please upload a video.");
                return;
            }
            //
            ml(true, 'jsESLoader', 'Please wait, while we are uploading the video.');
            //
            question.file = questionFile;
            console.log(questionFile);
        }
        //
        saveSurveyQuestion(question);
    });

    
});	


