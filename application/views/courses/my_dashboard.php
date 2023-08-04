<?php if ($load_view) { ?>
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                        <h1 class="section-ttile">Courses <div style="float: right;"></div>
                        </h1>
                    </div>

                    <?php $this->load->view('courses/partials/my_course_filter'); ?>

                    <div class="section-inner">
                        <div class="heading-sec">
                            <div class="row hidden">
                                <div class="col-md-6 col-xs-12">
                                    <h1>Employee Health Score: <span class="healthscore" alt="" title="">0</span>
                                        <div class="progress-meter-detail-button" alt="Show Score Details" title="Show Score Details" onclick="$('#heathpopup').modal('show');setTimeout(calculatechsdata, 500);" style="display: inline; position: absolute; "><img src="/assets/img/tooltip.svg" alt=""></div>
                                    </h1>
                                </div>

                                <div class="col-md-6 col-xs-12 ma30">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                            60%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <!-- Assign courses -->
                                <div class="col-xs-12 col-md-3">
                                    <div class="thumbnail black-block ">
                                        <div class="caption">
                                            <h3 id="jsAssignedCount">0</h3>
                                            <h4><strong>Assigned</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pending courses -->
                                <div class="col-xs-12 col-md-3">
                                    <div class="thumbnail csPendingBlock">
                                        <div class="caption">
                                            <h3 id="jsPendingCount">0</h3>
                                            <h4><strong>Pending</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Completed courses -->
                                <div class="col-xs-12 col-md-3">
                                    <div class="thumbnail success-block">
                                        <div class="caption">
                                            <h3 id="jsCompletedCount">0</h3>
                                            <h4><strong>Completed</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Failed courses -->
                                <div class="col-xs-12 col-md-3 hidden">
                                    <div class="thumbnail error-block">
                                        <div class="caption">
                                            <h3 id="jsFailedCount">0</h3>
                                            <h4><strong>Failed</strong></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;"></div>

                            <div class="row mb10">
                                <div class="col-xs-12 col-md-12 text-right">
                                    <button class="btn btn-black csRadius5 jsFilterSectionBtn" data-key="jsPageLoader">
                                        <i class="fa fa-filter" aria-hidden="true"></i>&nbsp;Filter
                                    </button>
                                </div>    
                            </div>

                            <div id="jsMyAssignedCourses"></div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<?php } else {
    $this->load->view('learning_center/my_courses_blue');
}
