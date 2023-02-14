<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5" id="jsChepterListSection">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3 class="_csF16">Chapters <span id="jsSurveyQuestionCount">(0)</span></h3>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button class="btn _csB4 _csF2 _csR5 _csMt10 _csF16" id="jsAddNewChapterBTN">
                    <i class="fa fa-plus-circle _csF16" aria-hidden="true"></i>&nbsp;Add Chapter
                </button>
                 <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/respondents"); ?>" class="btn _csB4 _csF2 _csR5 _csMt10 _csF16 jsAddNewRespondenBTN">
                    <i class="fa fa-long-arrow-right _csF16" aria-hidden="true"></i>&nbsp;Save & Next
                </a>
            </div>
        </div>
    </div>
    <div id="jsCourseChaptersList" class="panel-body _csP0">
       <p class="text-center"><strong>No chapter created yet.</strong></p>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/details"); ?>" class="btn _csB1 _csF2 _csR5 _csMt10 _csF16">
                    <i class="fa fa-long-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Go back to Detail
                </a>
                <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/respondents"); ?>" class="btn _csB4 _csF2 _csR5 _csMt10 _csF16 jsAddNewRespondenBTN" >
                    <i class="fa fa-long-arrow-right _csF16" aria-hidden="true"></i>&nbsp;Save & Next
                </a>
            </div>
        </div>
    </div>
</div>

<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5 dn" id="jsAddNewChapterSection" >
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12 col-sm-12 text-right">
                <button class="btn _csB1 _csF2 _csR5 _csMt10 _csF16" id="jsbackToQuestionsListBTN">
                    <i class="fa fa-long-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Back To Chapter List
                </button>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Video -->
        <div id="jsAddCourseVideo">
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Upload Learning Video<i class="fa fa-question-circle-o jsHintBtn csCP" data-target="video_help" aria-hidden="true"></i></label>
                    <p class="csF14 jsHintBody" data-hint="video_help">Upload a video explaining the chapter.</p>
                </div>
                <div class="col-sm-8 col-xs-12">  
                    <input type="file" name="course_video" id="jsUploadChapterVideoUpload" class="hidden" />
                </div>  
            </div>
            <div class="row">
                <br />
                <div class="col-sm-12">
                    <button class="btn _csB4 _csF2 _csR5 _csF16 pull-right" id="jsUploadCourseVideo">
                        <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Upload Video
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>  
        <div id="jsPreviewCchapterVideo">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <video controls style="width: 100%">
                        <source id="jsVideoPreview" width="100%" src="" type="video/mp4">
                    </video>
                </div>    
            </div>
        </div>    
        <!-- Question -->
        <br>
        <div id="jsAddCourseQueston" class="dn">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label>Question <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9 col-sm-12">
                    <input type="text" class="form-control" id="jsSurveyQuestionAddTitle" required />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-sm-12 _csMt10">
                    <label>Description </label>
                    <p>Explain to the employees what they need to add into the answer.</p>
                </div>
                <div class="col-md-9 col-sm-12 _csMt10">
                    <textarea class="form-control _csHeight100" id="jsSurveyQuestionAddDescription"></textarea>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-sm-12 _csMt10">
                    <label>Question Type </label>
                    <p>Select the type of the question.</p>
                </div>
                <div class="col-md-9 col-sm-12 _csMt10">
                    <select name="" id="jsSurveyAddQuestionType">
                        <option value="text">Text</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 text-right col-sm-12 _csMt10">
                    <button class="btn _csB1 _csF2 _csR5" id="jsResetQuestionSectionBTN">Cancel</button>
                    <button class="btn _csB4 _csF2 _csR5" id="jsServerQuestionSaveBTN">
                        <i class="fa fa-floppy-o _csF14" aria-hidden="true"></i>
                        Save
                    </button>
                    <button class="btn _csB4 _csF2 _csR5 dn" id="jsServerQuestionUpdateBTN">
                        <i class="fa fa-floppy-o _csF14" aria-hidden="true"></i>
                        Update
                    </button>
                </div>
            </div>
            <hr>
            <!-- Preview -->
            <!-- Question Preview -->
            <div class="panel panel-theme">
                
                <div class="panel-heading _csB4">
                    <p class="csF16 csB7 csW mb0 _csMZ">Question Preview</p>
                </div>
                <div class="panel-body pa0 pb0 pl0 pr0">
                    <div class="csGB">
                        <div class="row">
                            <div class="col-xs-12">
                                <!--  -->
                                <div class="p10">
                                    <h5 class="csF14 csB7">
                                        Q : <span id="jsSurveyQuestionAddPreviewTitle"></span>
                                    </h5>
                                    <!-- Description -->
                                    <div class="row">
                                        <div class="col-md-8 col-xs-12">
                                            <p class="csF14" id="jsSurveyQuestionAddPreviewDescription"></p>
                                        </div>
                                        <div class="col-md-4 col-xs-12" id="jsSurveyQuestionAddPreviewVideo">
                                            <div class="jsVideoPreviewBox">
                                                <video >
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Multiple Choice -->
                                    <div class="row dn" id="jsSurveyQuestionAddPreviewMultipleChoiceBox">
                                        <br />
                                        <div class="col-xs-12">
                                            <label class="control control--radio csF14">
                                                <input type="radio" class="jsSurveyQuestionAddPreviewMultipleChoice"
                                                    name="jsSurveyQuestionAddPreviewMultipleChoice" value="yes" /> Yes
                                                <span class="control__indicator"></span>
                                            </label> <br />
                                            <label class="control control--radio csF14">
                                                <input type="radio" class="jsSurveyQuestionAddPreviewMultipleChoice"
                                                    name="jsSurveyQuestionAddPreviewMultipleChoice" value="no" /> No
                                                <span class="control__indicator"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Rating -->
                                    <div class="row dn" id="jsSurveyQuestionAddPreviewRatingBox">
                                        <br />
                                        <div class="row _csMt10 _csRatingBox">
                                            <div class="col-md-2 text-center _csP10 _csRatingItem">
                                                 <p>1</p>
                                                 <p>Strongly Disagree</p>
                                             </div>
                                             <div class="col-md-2 text-center _csP10 _csRatingItem">
                                                 <p>2</p>
                                                 <p>Strongly Disagree</p>
                                             </div>
                                             <div class="col-md-2 text-center _csP10 _csRatingItem">
                                                 <p>3</p>
                                                 <p>Strongly Disagree</p>
                                             </div>
                                             <div class="col-md-2 text-center _csP10 _csRatingItem">
                                                 <p>4</p>
                                                 <p>Strongly Disagree</p>
                                             </div>
                                             <div class="col-md-2 text-center _csP10 _csRatingItem">
                                                 <p>5</p>
                                                 <p>Strongly Disagree</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Text -->
                                    <div class="row dn" id="jsSurveyQuestionAddPreviewTextBox">
                                        <br />
                                        <div class="col-xs-12">
                                            <p class="csF14 csB7">Feedback (Elaborate)</p>
                                            <textarea rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>