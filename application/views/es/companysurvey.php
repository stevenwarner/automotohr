<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
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

                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("employee/surveys/create"); ?>" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Survey</a>
                        </div>
                    </div>

                    <div class="panel panel panel-default _csMt10">
                        <!--  -->
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5 class=" _csF1 _csF16">
                                        <b id="jsSurveyTitle"> Title </b> &nbsp;
                                        <a class="btn _csB3 _csF2 _csF16 _csR5" href="#">Started</a>
                                    </h5>
                                </div>
                            </div>
                            <hr>
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <h5 class=" _csF1 _csF16">
                                        <b><span id="jsSurveyPercentage">10</span>% Completed</b>
                                    </h5>
                                    <h5 class=" _csF1 _csF14">
                                        <span id="jsSurveyCompletedRespondents">10</span> out of <span id="jsSurveyTotalRespondents">10</span> survey(s) submitted their feedback.
                                    </h5>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <h5 class="_csF1 _csF16">
                                        <b> 5% Completed </b>
                                    </h5>
                                    <h5 class="_csF1 _csF14">
                                        3 out of 20 reporting manager(s) submitted their feedback.
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-theme">
                        <div class="panel-body">
                            <table class="table table-striped table-condensed">
                                <caption></caption>
                                <thead>
                                    <tr>
                                        <!-- <th scope="col" class="_csF16 _csB1 _csF2">Employee</th> -->
                                        <th scope="col" class="_csF16 _csB1 _csF2">Created By</th>
                                        <!-- <th scope="col" class="_csF16 _csB1 _csF2">Progress</th> -->
                                        <th scope="col" class="_csF16 _csB1 _csF2">Question(s)</th>
                                        <th scope="col" class="_csF16 _csB1 _csF2">Respondent(s)</th>
                                        <th scope="col" class="_csF16 _csB1 _csF2">Period Cycle</th>
                                        <th scope="col" class="_csF16 _csB1 _csF2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-review_id="<?= $review['sid']; ?>" data-id="<?= $reviewee['reviewee_sid']; ?>">
                                        <td style="vertical-align: middle">
                                            <p class="_csF14" id="jsSurveyCreaterName"> Jhon Doe </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="_csF14 jsSurveyDetail" data-type="questions" title="Click to view questions" placement="top">
                                                <b><span id="jsSurveyQuestionsCount">1</span> Question(s) Added </b>
                                            </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="_csF14 jsSurveyDetail" data-type="respondents" title="Click to view respondents" placement="top">
                                                <b> <span id="jsSurveyRespondentsCount">1</span> Respondent(s) Added </b>
                                            </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="_csF14" id="jsSurveyTimePeriod">  </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <a class="btn _csB4 _csF2 _csR5  _csF16" title="View Survey Report" placement="top" href="<?= base_url("employee/surveys/reports/1"); ?>">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            
                                            <button class="btn _csB4 _csF2 _csR5  _csF16 jsActionButton dn" data-survey_status="stop" id="jsStopSurvey" title="Stop Survey" placement="top">
                                                <i class="fa fa-stop" aria-hidden="true"></i>
                                            </button>
                                       
                                            <button class="btn _csB4 _csF2 _csR5  _csF16 jsActionButton dn" data-survey_status="start" id="jsStartSurvey" title="Start Survey" placement="top">
                                                <i class="fa fa-play" aria-hidden="true"></i>
                                            </button>
                                            
                                            <a class="btn _csB4 _csF2 _csR5  _csF16" title="Manage Survey" placement="top" href="<?= base_url("employee/surveys/create/".$survey_id."/details"); ?>">
                                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>