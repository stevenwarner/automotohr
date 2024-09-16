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
                            Courses
                            <div style="float: right;">
                                <?php if ($haveSubordinate == "yes") { ?>
                                    <?php if ($page == "my_courses" || $page == "my_dashboard") { ?>
                                        <a href="<?php echo base_url('lms/courses/report'); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-pie-chart"></i> Subordinate Report</a>
                                    <?php } else { ?>
                                        <?php if ($type == "non_plus") { ?>
                                            <a href="<?php echo base_url('lms/courses/report'); ?>" class="btn btn-black csRadius5 csF16"><i class="fa fa-arrow-left"></i> Back to Subordinate Report</a>
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
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Inprogress Courses</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Courses that you have assigned and are starting to attempt.</p>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <a href="<?= base_url("lms/courses/my?type=inprogress") ?>" class="btn btn-info csRadius5" role="button">View Course</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsInprogressCourses"></div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Past Due</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Courses that you have assigned and are now expired.</p>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <a href="<?= base_url("lms/courses/my?type=past_due") ?>" class="btn btn-info csRadius5" role="button">View Course</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsPastDueCourses"></div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Due Soon</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Courses that you have assigned and are nearing expiration.</p>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <a href="<?= base_url("lms/courses/my?type=due_soon") ?>" class="btn btn-info csRadius5" role="button">View Course</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsDueSoonCourses"></div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Ready To Start</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;The total number of courses you have assigned.</p>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <a href="<?= base_url("lms/courses/my?type=assigned") ?>" class="btn btn-info csRadius5" role="button">View Course</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsAssignedCourses"></div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Passed Course(s)</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;The total number of courses you have assigned.</p>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <a href="<?= base_url("lms/courses/my?type=completed") ?>" class="btn btn-info csRadius5" role="button">View Course</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsPassedCourses"></div>
                                        </div>
                                    </div>
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