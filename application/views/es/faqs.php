<script src="<?= base_url('assets/employee_panel/js/jquery-1.11.3.min.js') ?>"></script>
<script src="<?= base_url('assets/js/select2.js'); ?>"></script>
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
                <div class="col-md-12 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("employee/surveys/create"); ?>" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Survey</a>
                        </div>
                    </div>
                    <!--  Settings -->

                    <div class="panel panel-default _csMt10">
                        <div class="panel-heading">
                            <p class="_csF16 "><b>FAQs</b></p>
                        </div>
                        <div class="panel-body jsPageBody" data-page="visibility">
                            <!-- Roles -->
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">

                                    <p class="_csF14"> The methodology used for calculating the result of the survey depends on which survey template you're utilizing. Below, you'll be able to view the methodology used for each different survey template. They'll go over the core questions that must be used in order to calculate a score for the survey.</p>

                                    <a href="#" class="_csF4 _csF14"><b> Survey Methodology</b></a>
                                    <p class="_csF14">The employee Net Promoter Score (eNPS) value from the survey is calculated in the following manner.Identify promoters, passives and detractorsPromoters: respondents giving a 9 o... <a href="#" class="_csF4"><small>Learn more</small></a></p>

                                    <a href="#" class="_csF4 _csF14"><b>Engagement Survey Methodology</b></a>
                                    <p class="_csF14">For the Engagement survey, the engagement score is defined as the average of scores for the following questions which are part of the Engagement theme: I'm proud to be part of [... <a href="#" class="_csF4"><small>Learn more</small></a></p>


                                    <a href="#" class="_csF4 _csF14"><b>Pulse Check Survey Methodology</b></a>
                                    <p class="_csF14">For the Pulse Check survey, the engagement score is calculated based on the question: “I would recommend this company as a great place to work.” <a href="#" class="_csF4"><small>Learn more</small></a></p>


                                    <a href="#" class="_csF4 _csF14"><b>Customized Template Survey Methodology </b></a>
                                    <p class="_csF14">When calculating a score for a customized survey, it is simply an average of all the questions answered.The exact calculation of the score will vary based on the questions inclu... <a href="#" class="_csF4"><small>Learn more</small></a></p>

                                    <a href="#" class="_csF4 _csF14"><b>Post Open Enrollment Survey Methodology </b></a>
                                    <p class="_csF14">For the Post Open Enrollment survey, the engagement score is calculated based on the question: "Overall I am satisfied with the benefits package my organization offers." <a href="#" class="_csF4"><small>Learn more</small></a></p>
                                
                                    <a href="#" class="_csF4 _csF14"><b>Pre Open Enrollment Survey Methodology</b></a>
                                    <p class="_csF14">For the Pre Open Enrollment survey, the engagement score is calculated based on the question: "Overall I am satisfied with the benefits package my organization offers." <a href="#" class="_csF4"><small>Learn more</small></a></p>
                                
                                    <a href="#" class="_csF4 _csF14"><b>Work/Life Flexibility Methodology </b></a>
                                    <p class="_csF14">For the Work/Life Flexibility Survey, the engagement score is defined as the average of scores for the following questions: I am able to navigate my work responsibilities along... <a href="#" class="_csF4"><small>Learn more</small></a></p>


                                </div>

                            </div>


                        </div>
                        <div class="panel-footer">

                            <div class="clearfix"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //
    $('#jsReviewRolesInp').select2({
        closeOnSelect: false
    });
    $('#jsReviewDepartmentsInp').select2({
        closeOnSelect: false
    });
    $('#jsReviewTeamsInp').select2({
        closeOnSelect: false
    });
    $('#jsReviewEmployeesInp').select2({
        closeOnSelect: false
    });
</script>