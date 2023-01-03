<!-- Main page -->
<div class="csPage">
    
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt20">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <!-- Sidebar -->
                    <?php $this->load->view('2022/sidebar'); ?>
                </div>
                <!--  -->
                <div class="col-md-6 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="csPageBox _csB2 _csR5 _csPt20 _csPb20">
                                <h2 class="_csF28"><strong>Are your employees engaged at work?</strong></h2>
                                <p class="_csF14">We have tailor made these surveys to better understand your employee engagement and workplace satisfaction.</p>
                                <a href="<?php echo base_url("employee/surveys/create"); ?>" class="btn _csB4 _csF2 _csR5 _csMt20"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Engagement</a>
                            </div>
                        </div>
                    </div>
                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">
                                Finished Engagements (<span id="jsFinishedSurveysCount">0</span>) 
                                <span class="pull-right">
                                    <a href="javascript:;" data-type="finished" id="jsSeeAllFinished" class="_csF14 _csF3 jsSeeAllEngagement dn">See All</a>
                                </span>
                            </h3>
                            <p class="_csF12 _csF4 _csMt10">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> The engagements whose end date passes away.
                            </p>    
                        </div>
                        <div class="panel-body" id="jsFinishedSurveysSection">
                            
                        </div>
                    </div>

                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">
                                Running Engagements (<span id="jsRunningSurveysCount">0</span>)
                                <span class="pull-right">
                                    <a href="javascript:;" data-type="running" id="jsSeeAllRunning" class="_csF14 _csF3 jsSeeAllEngagement dn ">See All</a>
                                </span>
                            </h3>
                            <p class="_csF12 _csF4 _csMt10">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> The Engagements whose end date is greater than today.
                            </p>
                        </div>
                        <div class="panel-body" id="jsRunningSurveysSection">

                        </div>
                    </div>

                    <!-- Assigned surveys -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">
                                Assigned Engagements (<span id="jsAssignedSurveysCount">0</span>)
                                <span class="pull-right">
                                    <a href="javascript:;" data-type="assigneded" id="jsSeeAllAssigneded" class="_csF14 _csF3 jsSeeAllEngagement dn">See All</a>
                                </span>
                            </h3>
                            <p class="_csF12 _csF4 _csMt10">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> The Engagements whose start date is greater than today.
                            </p>
                        </div>
                        <div class="panel-body" id="jsAssignedSurveysSection">
                            
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <div class="csPageSideBar _csR5 _csPb10">
                        <h3 class="_csM0 _csB4 _csP10 _csF2 _csF16">Statistics</h3>
                        <canvas id="myChart"></canvas>
                    </div>
                    <?php $this->load->view('es/partials/faqs'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('es/partials/loader'); ?>

<!--  -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>