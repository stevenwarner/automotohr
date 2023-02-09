<!-- Main page -->
<div class="csPage">
    
    <!--  -->
    <?php $this->load->view('courses/partials/navbar'); ?>
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
                                <p class="_csF14">We tailored some surveys to understand better your employee's engagement.</p>
                                <a href="<?php echo base_url("lms_courses/create"); ?>" class="btn _csB4 _csF2 _csR5 _csMt20"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Cource</a>
                            </div>
                        </div>
                    </div>
                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">Courses List (0)</h3>
                        </div>
                        <div class="panel-body">
                            <!-- Row 1 -->
                            <div class="csSurveyRow">
                                <div class="row">
                                    <div class="col-md-10 col-sm-12">
                                        <div>
                                            <span>Employee Net Promoter Score (eNPS)</span>
                                            <span class="pull-right">Oct 28, 2022</span>
                                        </div>
                                        <div class="progress _csMt10">
                                            <div class="progress-bar _csB4" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: 0%;"></div>
                                        </div>
                                        <p>Active</p>
                                    </div>
                                    <div class="col-md-2 col-sm-12 text-left">
                                        <a href="" class="btn _csB4 _csF2 _csMt20 _csR5">Results</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <div class="csPageSideBar _csR5 _csPb10">
                        <h3 class="_csM0 _csB4 _csP10 _csF2 _csF16">Statistics</h3>
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>