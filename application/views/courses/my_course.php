
<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('courses/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <!-- Sidebar -->
                    <?php $this->load->view('2022/sidebar'); ?>
                </div>
                <!--  -->
                <div class="col-md-9 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <span class="col-sm-12 text-right">
                            <a class="btn _csB1 _csF2 _csR5 _csF16 _csMr10" href="<?=base_url('lms_courses/my_courses');?>">
                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back to Courses 
                            </a>
                        </span>
                    </div>

                    <div class="panel panel-default _csMt10">
                        <div class="panel-body">
                            <!-- Basic -->
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div>
                                        <p class="_csF16 _csB2">
                                            <b id="jsCourseTitle"> Title </b>
                                        </p>
                                        <p class="_csF14" id="jsCourseTimePeriods">
                                            Nov 01 2022, Tue - Nov 30 2022, Wed
                                        
                                            <br />Due in 2 weeks, 1 day
                                        </p>
                                        <p class="_csF14 _csB2" id="jsCourseDescription">
                                            <b>Alexandar Virch Test Dasd Ad Reviewing Alexandar Virch Test Dasd Ad</b>
                                        </p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-md-6 col-xs-12">
                                    <span class="pull-right _csF16 _csB2">
                                        <strong class="._csF1" id="jsSurveyStatus">PENDING</strong>
                                    </span>
                                </div>
                            </div>
                            <div id="jsChapterList">
                                <hr>
                                <!-- Basic -->
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div>
                                            <p class="_csF16 _csB2" id="jsRemainingQuestions">
                                                <b> Completed 3 out of 5 Question(s) </b>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Basic -->
                                <div class="row">
                                    <br>
                                    <div class="col-md-12 col-xs-12">
                                        <div>
                                            <ul class="_csPaginationMenu text-left" id="jsQuestionMenu">
                                                <li class="active">
                                                    <a href="#" title="Pending" placement="top">1</a>
                                                </li>
                                                <li class="">
                                                    <a href="#" title="Pending" placement="top">2</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>       
                        </div>
                    </div>

                    <!-- Scorm Screen -->
                    <div class="row" id="jsScormSection">
                        <br />
                        <div class="col-xs-12">
                            <div class="panel panel-theme">
                                <!-- Scorm -->
                                <div class="panel-heading _csB1">
                                    <p class="_csF14 _csF2">
                                        <b id="jsScromTitle"> </b>
                                    </p>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-3 tutorial-nav">
                                        <ul id="jsScormChapterLinks">       
                                        </ul>   
                                    </div> 
                                    <div class="col-md-9">
                                        <iframe id="jsScormCourse" src="" width="100%" height="100%"></iframe>
                                    </div>
                                </div>
                                <!-- End Scorm -->
                            </div>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div Manual="row" id="jsManualSection">
                        <br />
                        <div class="col-xs-12">
                            <div class="panel panel-theme">
                                <!-- feedback -->
                                <div class="panel-heading _csB1">
                                    <p class="_csF14 _csF2">
                                        <b id="jsScromTitle"> Q1: Title </b>
                                    </p>
                                </div>
                                <div class="panel-body">
                                    <!-- Description -->
                                    <div class="row">
                                        <div class="col-md-8 col-xs-12">
                                            <p class="_csF14" id="jsQuestionDescription">
                                                Question description
                                            </p>
                                        </div>
                                        <div class="col-md-4 col-xs-12" id ="jsQuestionHelpSection">
                                            <video autoplay controls style="width: 100%;" preload="metadata">
                                                <source id ="jsVideoQuestionHelp" src="" type="video/webm">
                                                </source>
                                                <track label="English" kind="captions" srclang="en" default />
                                            </video>
                                        </div>
                                    </div>

                                    <!-- Rating -->
                                    <div class="row dn" id="jsRatingQuestion">
                                        <br />
                                        <ul class="_csRatingBar pl10 pr10">
                                            <li data-id="1" class="surveyRating surveyRatingDefault">
                                                <p class="_csF20 _csF2">1</p>
                                                <p class="_csF14 _csF2">Strongly Agree</p>
                                            </li>
                                            <li data-id="2" class="surveyRating">
                                                <p class="_csF20 ">2</p>
                                                <p class="_csF14 ">Agree</p>
                                            </li>
                                            <li data-id="3" class="surveyRating">
                                                <p class="_csF20">3</p>
                                                <p class="_csF14 ">Neutral</p>
                                            </li>
                                            <li data-id="4" class="surveyRating">
                                                <p class="_csF20 ">4</p>
                                                <p class="_csF14 ">Disagree</p>
                                            </li>
                                            <li data-id="5" class="surveyRating">
                                                <p class="_csF20 ">5</p>
                                                <p class="_csF14 ">Strongly Disagree</p>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Text -->
                                    <div class="row dn" id="jsTextQuestion">
                                        <br />
                                        <div class="col-xs-12">
                                            <p class="_csF14 _csB2"><b>Feedback (Elaborate)</b></p>
                                            <textarea rows="5" class="form-control jsRespondentText" placeholder="Enter text here"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Feedback -->

                                <div class="panel-footer">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <span class="pull-right">
                                                <button class="btn _csB4 _csF2 _csR5 _csF16" id="jsSaveSurveyQuestionAnswer">
                                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp; Save & Next 
                                                </button>
                                            </span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <span class="col-sm-12 text-right">
                            <a class="btn _csB1 _csF2 _csR5 _csF16 _csMr10 _csMb10" href="<?=base_url('lms_courses/my_courses');?>">
                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back to Courses 
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>