$(function (){
	var selectedTemplate = "";
	//
	function getTemplates () {
		$.ajax({
            type: 'GET',
            url: 'http://localhost:3000/employee_survey/templates',
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
            url: 'http://localhost:3000/employee_survey/'+ templateId +'/template',
            success: function(resp) {
                $("#jsSurveyTitle").val(resp.title);
	            $("#jsSurveyDescription").val(resp.description);
	            $('.jsESLoader').hide();
            },
            error: function() {
            	alertify.alert("Notice", "Unable to load survey template detail</b>");
            	$('.jsESLoader').hide();
            }
        }); 
	}
	//
	function createRationQuestion () {
		ratingQuestion = '';
		//
        ratingQuestion += '<div class="row"><br>';
        ratingQuestion += '		<ul class="_csRatingBar pl10 pr10">';
        ratingQuestion += '			<li data-id="1">';
        ratingQuestion += '         	<p class="_csF20 _csF2">1</p>';
        ratingQuestion += '             <p class="_csF14 _csF2">Strongly Agree</p>';
        ratingQuestion += '         </li>';
        ratingQuestion += '         <li data-id="2">';
        ratingQuestion += '         	<p class="_csF20 ">2</p>';
        ratingQuestion += '         	<p class="_csF14 ">Agree</p>';
        ratingQuestion += '         </li>';
        ratingQuestion += '         <li data-id="3">';
        ratingQuestion += '         	<p class="_csF20">3</p>';
        ratingQuestion += '             <p class="_csF14 ">Neutral</p>';
        ratingQuestion += '         </li>';
        ratingQuestion += '         <li data-id="4">';
        ratingQuestion += '         	<p class="_csF20 ">4</p>';
        ratingQuestion += '             <p class="_csF14 ">Disagree</p>';
        ratingQuestion += '         </li>';
        ratingQuestion += '         <li data-id="5">';
        ratingQuestion += '         	<p class="_csF20 ">5</p>';
        ratingQuestion += '             <p class="_csF14 ">Strongly Disagree</p>';
        ratingQuestion += '         </li>';
        ratingQuestion += '		</ul>';
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
        textQuestion += '		<textarea rows="5" class="form-control jsReviewText">' + templatequestion.type + '</textarea>';
        textQuestion += '	</div>';
        textQuestion += '</div>';
        //
        return textQuestion;
	}
	//
	function createTemplatePreview (template, ID) {
		//
		$qi = 1;
    	var questionBox = '';
    	const templateQuestions = JSON.parse(template.questions);
    	//
        templateQuestions.map(function(templatequestion) {
        	if (templatequestion.tag) {
        		console.log("its tag")
        	}
            questionBox += '<div class="row">';
            questionBox += '	<div class="col-xs-12">';
            questionBox += '		<div class="panel panel-theme">';
            questionBox += ' 				<div class="panel-heading _csB1">';
            questionBox += ' 					<p class="_csF14 _csF2">';
            questionBox += '  						<b> QUESTION ' + $qi + ' </b>';
            questionBox += '  					</p>';
            questionBox += '         		</div>';
            questionBox += '            	<div class="panel-body">';
            questionBox += '   					<div class="row">';
            questionBox += '                    	<div class="col-md-8 col-xs-12">';
            questionBox += '                        	<p class="_csF14">';
            questionBox += 									templatequestion.text;
            questionBox += '                            </p>';
            questionBox += '                       </div>';
            questionBox += '       				</div>';
            questionBox += '   				</div>';
            questionBox += '		</div>';
            questionBox += '	</div>';
            questionBox += '</div>';
            //
            if (templatequestion.type == 'rating') {
                questionBox += createRationQuestion();
            }
            //
            if (templatequestion.type == 'text') {
                questionBox += createTextQuestion();
            }    
            //
            questionBox += '   </div><!-- End Feedback --></div></div></div>';
            //
            $qi++;

        });
		//
    	let templateBox = '';
    	//
        templateBox += '<div class="panel panel-default _csMt10">';
        templateBox += '	<div class="panel-body">';
        templateBox += '		<div class="row">';
        templateBox += '			<div class="col-md-12 col-xs-12">';
        templateBox += '				<div>';
        templateBox += '        			<p class="_csF16 _csB2"><b>'+template.title+'</b></p>';
        templateBox += '					<p class="_csF14">'+template.description+'</p>';
        templateBox += '				</div>';
        templateBox += '    		</div>';
        templateBox += '		</div>';
        templateBox += '	</div>';
        templateBox += '</div>';
        templateBox += '<div>';
        templateBox += 		questionBox;
        templateBox += '</div>';
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
            url: 'http://localhost:3000/employee_survey/'+ cToken +'/survey',
            data: JSON.stringify(surveyDetails),
            dataType: 'json',
            beforeSend: function() {
                $('.jsESLoader').show();
            },
            success: function(res) {
            	console.log(res)
            	//
                alertify.alert('Success','Employee Survey Saved Sucessfully.');
                $('.jsESLoader').hide();

            },
            error: function() {
            	alertify.alert("Notice", "Unable to save employee survey detail</b>");
            	$('.jsESLoader').hide();
            }
        });
	}
	//
	getTemplates();
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
        	alertify.alert("Notice", "Please select any template or check <b>Start from scratch</b>");
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
        	alertify.alert("Notice", "Please Enter Survey Title");
            return false;
        }
        if (surveyDetails == '') {
        	alertify.alert("Notice", "Please Enter Survey Details");
            return false;
        }
        if (surveyStartDate == '') {
        	alertify.alert("Notice", "Please Enter Survey Start Date");
            return false;

        }
        if (surveyEndDate == '') {
        	alertify.alert("Notice", "Please Enter Survey End Date");
            return false;
        }

        var surveyBasicDetails = {
            'title': surveyTitle,
            'start_date': moment(surveyStartDate).format('YYYY-MM-DD'),
            'end_date': moment(surveyEndDate).format('YYYY-MM-DD'),
            'description': surveyDetails,
            'employee_code': eToken,
            'template_code': selectedTemplate
        };

        saveSurveyDetails(surveyBasicDetails);
    });

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
	            	alertify.alert("Notice", "Unable to load survey template detail</b>");
	            	$('.jsESLoader').hide();
	            }
	        }); 
        });
	});
});	


