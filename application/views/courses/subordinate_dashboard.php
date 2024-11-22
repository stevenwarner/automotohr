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
                            <?php if ($page == "my_courses" || $page == "my_dashboard") { ?>
                                <?php echo "My"; ?>
                            <?php } else { ?>
                                <?php echo $type == "non_plus" ? "Subordinate" : "Employee"; ?>
                            <?php } ?>
                            Courses
                            <div style="float: right;">
                                <?php if ($haveSubordinate == "yes") { ?>
                                    <a href="<?php echo base_url('lms/courses/report'); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-pie-chart"></i> Team Report</a>
                                    <a href="<?php echo base_url('lms/courses/employee_courses_history/'.$type.'/'.$subordinate_sid); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-history"></i> Employee Course History</a>
                                    <?php if ($page == "my_courses" || $page == "my_dashboard") { ?>
                                        
                                    <?php } else { ?>
                                        <?php if ($type == "non_plus") { ?>
                                            <a href="<?php echo base_url('lms/courses/my_lms_dashboard'); ?>" class="btn btn-black csRadius5 csF16"><i class="fa fa-arrow-left"></i> Back to My Courses</a>
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
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong><?php echo $type == "non_plus" ? "Subordinate" : "Employee"; ?> Info</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <img style="width: 90px; height: 90px; border-radius: 50% !important;" src="<?= getImageURL($subordinateInfo["profile_picture"]); ?>" alt="" />
                            </div>
                            <div class="col-sm-9">
                                <p class="text-small weight-6 myb-0" style="font-size: 20px;">
                                    <?= remakeEmployeeName($subordinateInfo, true, true); ?>
                                </p>
                                <p class="text-small">
                                    <?= remakeEmployeeName($subordinateInfo, false); ?>
                                </p>
                                <p class="text-small">
                                    <?= $subordinateInfo['email']; ?>
                                </p>
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead style="background-color: #fd7a2a;">
                                        <tr>
                                            <th>Courses Catagories</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="jsCategoryCourses" style="background-color: #544fc5; color:#fff; cursor: pointer;" data-url="<?php echo base_url("lms/subordinate/courses/".($subordinate_sid)."?type=inprogress"); ?>">
                                            <th class="col-xs-10" style="font-size: 14px !important;">Courses in Progress</th>
                                            <td class="col-xs-2 text-center" id="jsInProgressCount"></td>
                                        </tr>
                                        <tr class="jsCategoryCourses" style="background-color: #ff834e; color:#fff; cursor: pointer;" data-url="<?php echo base_url("lms/subordinate/courses/".($subordinate_sid)."?type=ready_to_start"); ?>">
                                            <th class="col-xs-10" style="font-size: 14px !important;">Ready To Start</th>
                                            <td class="col-xs-2 text-center" id="jsReadyToStartCount"></td>
                                        </tr>
                                        <tr class="jsCategoryCourses" style="background-color: #fa4b42; color:#fff; cursor: pointer;" data-url="<?php echo base_url("lms/subordinate/courses/".($subordinate_sid)."?type=past_due"); ?>">
                                            <th class="col-xs-10" style="font-size: 14px !important;">Past Due</th>
                                            <td class="col-xs-2 text-center" id="jsPastDueCount"></td>
                                        </tr>
                                        <tr class="jsCategoryCourses" style="background-color: #feb56a; color:#fff; cursor: pointer;" data-url="<?php echo base_url("lms/subordinate/courses/".($subordinate_sid)."?type=due_soon"); ?>">
                                            <th class="col-xs-10" style="font-size: 14px !important;">Due Soon</th>
                                            <td class="col-xs-2 text-center" id="jsDueSoon"></td>
                                        </tr>
                                        <tr class="jsCategoryCourses" style="background-color: #00e272; color:#fff; cursor: pointer;" data-url="<?php echo base_url("lms/subordinate/courses/".($subordinate_sid)."?type=completed"); ?>">
                                            <th class="col-xs-10" style="font-size: 14px !important;">Passed Courses</th>
                                            <td class="col-xs-2 text-center" id="jsCompletedCount"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Sidebar head -->

                    </div>
                </div>

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
                                            <h1 class="panel-heading-text text-medium">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <strong>Courses in Progress</strong>
                                                    </div>
                                                </div>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <p class="csF14 csInfo csB7" style="font-size: 14px !important">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    &nbsp;Courses that you have started but have not completed.
                                                </p>
                                            </div>
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
                                            <h1 class="panel-heading-text text-medium">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <strong>Past Due</strong>
                                                    </div>
                                                </div>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <p class="csF14 csInfo csB7" style="font-size: 14px !important">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    &nbsp;Courses that have been assigned to you and are currently Past Due.
                                                </p>
                                            </div>
                                            <div class="row" id="jsPastDueCourses"></div>
                                        </div>
                                    </div>
                                    <!-- Past Due End -->

                                    <!-- Due Soon Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-heading-text text-medium">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <strong>Due Soon</strong>
                                                    </div>
                                                </div>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <p class="csF14 csInfo csB7" style="font-size: 14px !important">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                    &nbsp;Courses that have been assigned to you and are nearing expiration.
                                                </p>
                                            </div>
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
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    var page = "<?php echo $page; ?>";
    var subordinateId = "<?php echo $subordinate_sid; ?>";
    var subordinateName = "<?php echo $subordinateName; ?>";
    var reviewAs = "<?php echo $type; ?>";
</script>