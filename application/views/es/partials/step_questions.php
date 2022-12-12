<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5" id="jsSurveyQuestionListSection">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3 class="_csF16">Survey Questions <span id="jsSurveyQuestionCount">(0)</span></h3>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button class="btn _csB4 _csF2 _csR5 _csMt10 _csF16" id="jsAddNewQuestionBTN">
                    <i class="fa fa-plus-circle _csF16" aria-hidden="true"></i>&nbsp;Add Question
                </button>
                <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/details"); ?>" class="btn _csB3 _csF2 _csR5 _csMt10 _csF16">
                    <i class="fa fa-long-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Go back to Detail
                </a>
                 <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/respondents"); ?>" class="btn _csB3 _csF2 _csR5 _csMt10 _csF16 jsAddNewRespondenBTN">
                    <i class="fa fa-long-arrow-right _csF16" aria-hidden="true"></i>&nbsp;Next
                </a>
            </div>
        </div>
    </div>
    <div id="jsSurveyQuestionsList" class="panel-body _csP0">
       
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/details"); ?>" class="btn _csB3 _csF2 _csR5 _csMt10 _csF16">
                    <i class="fa fa-long-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Go back to Detail
                </a>
                <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/respondents"); ?>" class="btn _csB3 _csF2 _csR5 _csMt10 _csF16 jsAddNewRespondenBTN" >
                    <i class="fa fa-long-arrow-right _csF16" aria-hidden="true"></i>&nbsp;Next
                </a>
            </div>
        </div>
    </div>
</div>

<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5 dn" id="jsAddNewQuestionSection" >
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12 col-sm-12 text-right">
                <button class="btn _csB1 _csF2 _csR5 _csMt10 _csF16" id="jsbackToQuestionsListBTN">
                    <i class="fa fa-long-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Back To Questions
                </button>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Question -->
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
            <br>
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Video Help <i class="fa fa-question-circle-o jsHintBtn csCP" data-target="video_help" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="video_help">Record/Upload a video explaining the reviewer what to add into the answer.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <label class="control control--radio csF16">
                    <input type="radio" class="jsSurveyQuestionAddVideoType" name="jsSurveyQuestionAddVideoType" value="none" checked/> None
                    <div class="control__indicator"></div>
                </label>
                <br>
                <label class="control control--radio csF16">
                    <input type="radio" class="jsSurveyQuestionAddVideoType" name="jsSurveyQuestionAddVideoType" value="record" /> Record Video
                    <div class="control__indicator"></div>
                </label>
                <br>
                <label class="control control--radio csF16">
                    <input type="radio" class="jsSurveyQuestionAddVideoType" name="jsSurveyQuestionAddVideoType" value="upload" /> Upload Video
                    <div class="control__indicator"></div>
                </label>
                <br>
                <div id="jsSurveyQuestionAddVideoUpload" class="dn">
                    <input type="file" id="jsSurveyQuestionAddVideoUploadInp" class="hidden" />
                </div>
                <br>
                <div id="jsSurveyQuestionAddVideoRecord" class="dn">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="jsVideoRecorderBox">
                                <p class="csF16 csB7 csInfo"><i class="fa fa-info-circle csF18" aria-hidden="true"></i>&nbsp;To use this feature, please, make sure you have allowed microphone and camera access.</p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="jsVideoRecorderBox">
                                <video id="jsVideoRecorder" width="100%"></video>
                                <button class="btn btn-orange btn-lg csF16 dn" id="jsVideoRecordButton"><i aria-hidden="true" class="fa fa-stop csF16"></i> Start Recording</button>
                                <button class="btn btn-black btn-lg csF16 dn" id="jsVideoPauseButton"><i aria-hidden="true" class="fa fa-pause-circle csF16"></i> Pause Recording</button>
                                <button class="btn btn-black btn-lg csF16 dn" id="jsVideoResumeButton"><i aria-hidden="true" class="fa fa-play-circle csF16"></i> Resume Recording</button>
                            </div>
                        </div>
                     </div>
                </div>
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
                                            <video id="jsVideoPreview" width="100%">
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