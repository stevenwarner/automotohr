<!-- <div class="main jsmaincontent">
    <div class="container"> -->
<div class="main csPageWrap">
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
                            My Courses
                            <div style="float: right;">
                                <?php if ($page == "my_courses" || $page == "my_dashboard") { ?>
                                        <a href="<?php echo base_url('lms/courses/my_courses_history'); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-history"></i> My Course History</a>
                                <?php } ?>
                                <?php if ($haveSubordinate == "yes") { ?>
                                    <?php if ($page == "my_courses" || $page == "my_dashboard") { ?>
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

            <!--  -->
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-body" style="background: #e6e7ff; border-radius: 4px;">
                        <h2 style="">Completed Courses: <span style="color: #ef6c34;" id="jsOverViewTrainings">0%</span></h2>
                        <h3 style="margin-bottom: 0px;"><span id="jsOverViewCourseDueSoon">0</span> Courses Due Soon</h3>
                        <h3 style="margin-top: 0px;"><span id="jsOverViewCourseTotal">0</span> Courses Total</h3>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Course(s) Progress</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div id="container2"></div>
                        
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Assign Course(s)</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div id="container"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Assign Course(s)</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div id="container1"></div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="csTabContent">
                            <div class="csLisitingArea">
                                <div class="csBoxWrap jsBoxWrap">

                                    <!-- Courses in Progress Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Courses in Progress</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        &nbsp;Courses that you have started but have not completed.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsInprogressCourses"></div>
                                        </div>
                                    </div>
                                    <!-- Courses in Progress End -->

                                    <!-- Ready To Start Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Ready To Start</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        &nbsp;Courses that have been assigned to you, but have not been started.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsAssignedCourses"></div>
                                        </div>
                                    </div>
                                    <!-- Ready To Start End -->

                                    <!-- Past Due Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Past Due</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        &nbsp;Courses that have been assigned to you and are currently Past Due.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsPastDueCourses"></div>
                                        </div>
                                    </div>
                                    <!-- Past Due End -->

                                    <!-- Due Soon Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Due Soon</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        &nbsp;Courses that have been assigned to you and are nearing expiration.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsDueSoonCourses"></div>
                                        </div>
                                    </div>
                                    <!-- Due Soon End -->

                                    <!-- Passed Courses Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Passed Course(s)</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        &nbsp;Congratulations! These are the Courses that you have Completed Successfully.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsPassedCourses"></div>
                                        </div>
                                    </div>
                                    <!-- Passed Courses End -->

                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>
</div>


<script src="https://code.highcharts.com/highcharts.js"></script> 
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    var page = "<?php echo $page; ?>";
    var subordinateId = "<?php echo $subordinate_sid; ?>";
    var subordinateName = "<?php echo $subordinateName; ?>";
    var reviewAs = "<?php echo $type; ?>";
</script>