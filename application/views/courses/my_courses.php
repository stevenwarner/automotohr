<div class="main jsmaincontent">
    <div class="container-fluid">
        <div style="position: relative">
            <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
            <?php $this->load->view('courses/partials/my_course_filter'); ?>
            <!--  -->
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h1 class="section-ttile">
                            Courses
                            <div style="float: right;">
                                <?php if ($haveSubordinate == "yes") { ?>
                                    <?php if ($page == "my_courses") { ?>
                                        <a href="<?php echo base_url('lms/courses/report'); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-pie-chart"></i> Team Report</a>
                                    <?php } else { ?>
                                        <?php if ($type == "non_plus") { ?>
                                            <a href="<?php echo base_url('lms/courses/report'); ?>" class="btn btn-black csRadius5 csF16"><i class="fa fa-arrow-left"></i> Back to Team Report</a>
                                        <?php } else if ($type == "plus") { ?>
                                            <a href="<?php echo base_url('lms/courses/company_report'); ?>" class="btn btn-black csRadius5 csF16"><i class="fa fa-arrow-left"></i> Back to Company Report</a>
                                        <?php } ?>    
                                    <?php } ?>
                                <?php } ?>     
                            </div>
                        </h1>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-lg-12">
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
                                <div class="col-xs-12 col-md-3 jsFilterBox" data-key="all">
                                    <div class="thumbnail black-block ">
                                        <div class="caption">
                                            <h3 id="jsAssignedCount">0</h3>
                                            <h4><strong>Assigned</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pending courses -->
                                <div class="col-xs-12 col-md-3 jsFilterBox" data-key="pending">
                                    <div class="thumbnail csPendingBlock">
                                        <div class="caption">
                                            <h3 id="jsPendingCount">0</h3>
                                            <h4><strong>Pending</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Completed courses -->
                                <div class="col-xs-12 col-md-3 jsFilterBox" data-key="completed">
                                    <div class="thumbnail success-block">
                                        <div class="caption">
                                            <h3 id="jsCompletedCount">0</h3>
                                            <h4><strong>Completed</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Failed courses -->
                                <div class="col-xs-12 col-md-3 jsFilterBox" data-key="due_soon">
                                    <div class="thumbnail error-block">
                                        <div class="caption">
                                            <h3 id="jsExpiredSoonCount">0</h3>
                                            <h4><strong>Due Soon</strong></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;"></div>


                        </div>
                    </div>
                </div>
            </div>
            
            <!--  -->
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                        <?php if ($viewMode == 'subordinate') { ?>
                            <button type="button" class="btn btn-info btn-orange csRadius5 csF16 jsSendReminderEmail">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                Send Reminder Email                                        
                            </button>
                        <?php } ?>
                    <button class="btn btn-black csRadius5 jsFilterSectionBtn csF16" data-key="jsPageLoader">
                        <i class="fa fa-filter" aria-hidden="true"></i>&nbsp;Filter
                    </button>
                </div>
            </div>
            <!--  -->

            <div class="row">
                <br>
                <div class="col-sm-12">
                    <div class="row" style="margin-bottom:10px;">
                        <div class="col-xs-9">
                            <div class="row" style="margin: 5px 5px;">
                                <div class="col-lg-2 bg-warning" style="padding: 16px;"></div>
                                <div class="col-lg-10" style="padding: 6px; font-weight: 700;">
                                    The course will soon expire, with less than 15 days remaining.
                                </div>
                            </div>
                            
                            <div class="row" style="margin: 5px 5px;">
                                <div class="col-lg-2 bg-danger" style="padding: 16px;"></div>
                                <div class="col-lg-10" style="padding: 6px; font-weight: 700;">
                                    The course has not been completed and has now expired.
                                </div>
                            </div>
                        </div>     
                    </div>
                    <div id="jsMyAssignedCourses">
                        
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>

<script>
    var page = "<?php echo $page; ?>";
    var subordinateId = "<?php echo $subordinate_sid; ?>";
    var subordinateName = "<?php echo $subordinateName; ?>";
    var reviewAs = "<?php echo $type; ?>";
    var search = "<?php echo $search; ?>";
</script>