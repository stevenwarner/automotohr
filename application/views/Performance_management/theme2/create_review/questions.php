<!-- Add/Edit Question -->
<!-- Add Question -->
 <?php
   if ($load_view) {

    $panelHeading = 'background-color: #3554DC';
} else {
    $panelHeading = 'background-color: #81b431';
}
 ?>
<div id="jsReviewQuestionAddBox" class="dn">
    <br>
    <!-- Title -->
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <label class="csF16 csB7">Question <span class="csRequired"></span></label>
        </div>
        <div class="col-sm-8 col-xs-12">
            <textarea id="jsReviewQuestionAddTitle" class="form-control" rows="5"
                placeholder="What progress have you made on your action plans or goals since we last met?"></textarea>
        </div>
    </div>
    <!-- Description -->
    <div class="row">
        <br>
        <div class="col-sm-4 col-xs-12">
            <label class="csF16 csB7">Description <i class="fa fa-question-circle-o jsHintBtn csCP" data-target="help"
                    aria-hidden="true"></i></label>
            <p class="csF14 jsHintBody" data-hint="help">Explain to the reviewer what they need to add into the answer.</p>
        </div>
        <div class="col-sm-8 col-xs-12">
            <textarea id="jsReviewQuestionAddDescription" class="form-control" rows="5"
                placeholder="Elaborate your answer"></textarea>
        </div>
    </div>
    <!-- Video Help -->
    <div class="row">
        <br>
        <div class="col-sm-4 col-xs-12">
            <label class="csF16 csB7">Video Help <i class="fa fa-question-circle-o jsHintBtn csCP"
                    data-target="video_help" aria-hidden="true"></i></label>
            <p class="csF14 jsHintBody" data-hint="video_help">Record/Upload a video explaining to the reviewer what to add into the answer.</p>
        </div>
        <div class="col-sm-8 col-xs-12">
            <label class="control control--radio csF16">
                <input type="radio" class="jsReviewQuestionAddVideoType" name="jsReviewQuestionAddVideoType"
                    value="none" checked/> None
                <div class="control__indicator"></div>
            </label> <br>
            <label class="control control--radio csF16">
                <input type="radio" class="jsReviewQuestionAddVideoType" name="jsReviewQuestionAddVideoType"
                    value="record" /> Record Video
                <div class="control__indicator"></div>
            </label> <br>
            <label class="control control--radio csF16">
                <input type="radio" class="jsReviewQuestionAddVideoType" name="jsReviewQuestionAddVideoType"
                    value="upload" /> Upload Video
                <div class="control__indicator"></div>
            </label>

            <!--  -->
            <br>
            <div id="jsReviewQuestionAddVideoUpload" class="dn">
                <input type="file" id="jsReviewQuestionAddVideoUploadInp" class="hidden" />
            </div>

            <!--  -->
            <br>
            <div id="jsReviewQuestionAddVideoRecord" class="dn">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="jsVideoRecorderBox">
                            <p class="csF16 csB7 csInfo"><i class="fa fa-info-circle csF18" aria-hidden="true"></i>&nbsp;To use this
                                    feature, please, make sure you have allowed
                                    microphone and camera access.</p>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <div class="jsVideoRecorderBox">
                            <video id="jsVideoRecorder" width="100%"></video>
                            <!--  -->
                            <button class="btn btn-orange btn-lg csF16 dn" id="jsVideoRecordButton"><i aria-hidden="true"
                                    class="fa fa-stop csF16"></i> Start
                                Recording</button>
                            <!--  -->
                            <button class="btn btn-black btn-lg  csF16 dn" id="jsVideoPauseButton"><i aria-hidden="true"
                                    class="fa fa-pause-circle csF16"></i> Pause Recording</button>
                            <!--  -->
                            <button class="btn btn-black btn-lg csF16 dn" id="jsVideoResumeButton"><i aria-hidden="true"
                                    class="fa fa-play-circle csF16"></i> Resume Recording</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Question Type -->
    <div class="row">
        <br>
        <div class="col-sm-4 col-xs-12">
            <label class="csF16 csB7">Question Type <span class="csRequired"></span> <i
                    class="fa fa-question-circle-o jsHintBtn csCP" data-target="question_type"
                    aria-hidden="true"></i></label>
            <p class="csF14 jsHintBody" data-hint="question_type">Select the type of the question.</p>
        </div>
        <div class="col-sm-8 col-xs-12">
            <select id="jsReviewQuestionAddQuestionType">
                <option value="text">Text</option>
                <option value="rating">Rating</option>
                <option value="multiple_choice">Multiple Choice</option>
                <option value="text_rating">Text & Rating</option>
                <option value="text_multiple_choice">Text & Multiple Choice</option>
            </select>
        </div>
    </div>
    <br>
    <!-- Question Preview -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="<?=$panelHeading?>">
            <p class="csF16 csB7 csW mb0">Question Preview</p>
        </div>
        <div class="panel-body pa0 pb0 pl0 pr0">
            <div class="csGB">
                <div class="row">
                    <div class="col-xs-12">
                        <!--  -->
                        <div class="p10">
                            <h5 class="csF14 csB7">
                                Q1: <span id="jsReviewQuestionAddPreviewTitle"></span>
                            </h5>
                            <!-- Description -->
                            <div class="row">
                                <div class="col-md-8 col-xs-12">
                                    <p class="csF14" id="jsReviewQuestionAddPreviewDescription"></p>
                                </div>
                                <div class="col-md-4 col-xs-12" id="jsReviewQuestionAddPreviewVideo">
                                    <div class="jsVideoPreviewBox">
                                        <video id="jsVideoPreview" width="100%"></video>
                                    </div>
                                </div>
                            </div>
                            <!-- Multiple Choice -->
                            <div class="row dn" id="jsReviewQuestionAddPreviewMultipleChoiceBox">
                                <br />
                                <div class="col-xs-12">
                                    <label class="control control--radio csF14">
                                        <input type="radio" class="jsReviewQuestionAddPreviewMultipleChoice"
                                            name="jsReviewQuestionAddPreviewMultipleChoice" value="yes" /> Yes
                                        <span class="control__indicator"></span>
                                    </label> <br />
                                    <label class="control control--radio csF14">
                                        <input type="radio" class="jsReviewQuestionAddPreviewMultipleChoice"
                                            name="jsReviewQuestionAddPreviewMultipleChoice" value="no" /> No
                                        <span class="control__indicator"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- Rating -->
                            <div class="row dn" id="jsReviewQuestionAddPreviewRatingBox">
                                <br />
                                <ul class="csRatingBar pl10 pr10">
                                    <li>
                                        <p class="csF20 csB9">1</p>
                                        <p class="csF14 csB6">Strongly Agree</p>
                                    </li>
                                    <li>
                                        <p class="csF20 csB9">2</p>
                                        <p class="csF14 csB6">Agree</p>
                                    </li>
                                    <li>
                                        <p class="csF20 csB9">3</p>
                                        <p class="csF14 csB6">Neutral</p>
                                    </li>
                                    <li>
                                        <p class="csF20 csB9">4</p>
                                        <p class="csF14 csB6">Disagree</p>
                                    </li>
                                    <li>
                                        <p class="csF20 csB9">5</p>
                                        <p class="csF14 csB6">Strongly Disagree</p>
                                    </li>
                                </ul>
                            </div>
                            <!-- Text -->
                            <div class="row dn" id="jsReviewQuestionAddPreviewTextBox">
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

    <!-- Add Question BTN -->
    <div class="row">
        <div class="col-sm-12">
            <div class="bbb"></div>
            <br />
            <span class="pull-right">
                <button class="btn btn-black csF16" id="jsReviewQuestionToList"><i class="fa fa-arrow-circle-o-left"
                        aria-hidden="true"></i>&nbsp; Back To Questions</button>
                <button class="btn btn-success csF16" id="jsReviewQuestionSaveBtn"><i class="fa fa-arrow-circle-o-right"
                    aria-hidden="true"></i>&nbsp; Save Question</button>
                <button class="btn btn-success csF16 dn" id="jsReviewQuestionEditBtn"><i class="fa fa-arrow-circle-o-right"
                    aria-hidden="true"></i>&nbsp; Update Question</button>
            </span>
        </div>
        <div class="clearfix"></div>
    </div>
</div>


<!-- Question Listing -->
<div id="jsReviewQuestionListBox">
    <div class="panel panel-theme">
        <div class="panel-heading" style="<?=$panelHeading?>">
            <div class="row">
                <div class="col-sm-9 col-xs-12">
                    <p class="csF16 csB7 csW mb0">
                        Questions
                    </p>
                </div>
                <div class="col-sm-3 col-xs-12">
                    <button class="btn btn-orange csF16 csB7 form-control" id="jsReviewQuestionAddBtn">
                        <i class="fa fa-plus-circle csF18" aria-hidden="true"></i>&nbsp;Add Question
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body jsPageBody pa0 pb0 pl0 pr0" id="jsReviewQuestionListArea" data-page="basic">
            
        </div>
    </div>

    <!-- Buttons -->
    <div class="row">
        <div class="col-sm-12">
            <div class="bbb"></div>
            <br />
            <button class="btn btn-black csF16 jsPageSectionBtn" data-to="reviewers"><i
                    class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp; Back To Reviewers</button>
            <span class="pull-right">
                <button class="btn btn-success csF16" id="jsReviewQuestionsSaveBtn"><i class="fa fa-arrow-circle-o-right"
                        aria-hidden="true"></i>&nbsp; Save & Next</button>
                <button class="btn btn-black csF16"><i class="fa fa-archive" aria-hidden="true"></i>&nbsp; Finish
                    Later</button>
            </span>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
