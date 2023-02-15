<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5" id="jsChepterListSection">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3 class="_csF16">Chapters ( <span id="jsSurveyQuestionCount">0</span> )</h3>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button class="btn _csB4 _csF2 _csR5 _csMt10 _csF16" id="jsAddNewChapterBTN">
                    <i class="fa fa-plus-circle _csF16" aria-hidden="true"></i>&nbsp;Add Chapter
                </button>
                 <a href="javascript:;" class="btn _csB4 _csF2 _csR5 _csMt10 _csF16 jsAssignEmployeesToCourseBTN">
                    <i class="fa fa-long-arrow-right _csF16" aria-hidden="true"></i>&nbsp;Save & Next
                </a>
            </div>
        </div>
    </div>
    <div id="jsCourseChaptersList" class="panel-body _csP0">
       
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="javascript:;" class="btn _csB4 _csF2 _csR5 _csMt10 _csF16 jsAssignEmployeesToCourseBTN" >
                    <i class="fa fa-long-arrow-right _csF16" aria-hidden="true"></i>&nbsp;Save & Next
                </a>
            </div>
        </div>
    </div>
</div>

<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5" id="jsAddNewChapterSection" >
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12 col-sm-12 text-right">
                <button class="btn _csB1 _csF2 _csR5 _csMt10 _csF16" id="jsbackToChapterListBTN">
                    <i class="fa fa-long-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Back To Chapter List
                </button>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Preview -->
        <div id="jsChapterPreviewSection">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-right">
                    <button class="btn _csB4 _csF2 _csR5 _csF16" id="jsAddQuestionBTN">
                        <i class="fa fa-plus-circle _csF16" aria-hidden="true"></i>&nbsp;Add Question
                    </button>
                </div>
                <br>
                <div class="col-md-12 col-xs-12" id="jsVideoPreviewSection">
                    <video autoplay controls style="width:100%; height:auto;" preload="metadata">
                        <source id ="jsVideoPreview" src="" type="video/webm">
                        </source>
                        <track label="English" kind="captions" srclang="en" default />
                    </video>
                </div> 
                <div class="col-md-12 col-xs-12" id="jsCourseQuestionSection">
                </div>
            </div>       
        </div>

        <!-- Video -->
        <div id="jsAddCourseVideo">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <label>Title <span class="text-danger">*</span></label>
                    <p>What the chapter will be called. e.g. "Paper Flow"</p>
                </div>
                <div class="col-md-8 col-sm-12">
                    <input type="text" name="chapter_title" class="form-control" id="jsChapterTitle" required />
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12 _csMt10">
                    <label>Description </label>
                    <p>What this course is about.</p>
                </div>
                <div class="col-md-8 col-sm-12 _csMt10">
                    <textarea class="form-control" name="chapter_description" row="30" id="jsChapterDescription"></textarea>
                </div>
            </div>
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

        <!-- Question -->
        <br>
        <div id="jsAddCourseQueston">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label>Question <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9 col-sm-12">
                    <input type="text" class="form-control" id="jsQuestionTitle" required />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-sm-12 _csMt10">
                    <label>Question Type </label>
                    <p>Select the type of the question.</p>
                </div>
                <div class="col-md-9 col-sm-12 _csMt10">
                    <select name="" id="jsQuestionType">
                        <option value="text">Text</option>
                        <option value="boolean">Yes / No</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label>Answer <span class="text-danger">*</span></label>
                    <p>Answer must be Yes / No or any integer value.</p>
                </div>
                <div class="col-md-9 col-sm-12">
                    <input type="text" class="form-control" id="jsQuestionAnswer" required />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 text-right col-sm-12 _csMt10">
                    <button class="btn _csB1 _csF2 _csR5" id="jsResetQuestionSectionBTN">Cancel</button>
                    <button class="btn _csB4 _csF2 _csR5" id="jsChapterQuestionSaveBTN">
                        <i class="fa fa-floppy-o _csF14" aria-hidden="true"></i>
                        Save
                    </button>
                    <button class="btn _csB4 _csF2 _csR5" id="jsChapterQuestionUpdateBTN">
                        <i class="fa fa-floppy-o _csF14" aria-hidden="true"></i>
                        update
                    </button>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div>