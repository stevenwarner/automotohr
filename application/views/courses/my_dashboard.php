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
                                    <?php if ($page == "my_courses") { ?>
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
                side bar
            </div>
            <!--  -->
            <div class="col-sm-9">
                <div class="row">
                    <br>
                    <div class="col-sm-12">
                        <div class="csTabContent">
                            <br />
                            <div class="csLisitingArea">
                                <div class="csBoxWrap jsBoxWrap">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-heading-text text-medium">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <strong>Inprogress Courses</strong>
                                                    </div>    
                                                    <div class="col-sm-6 text-right">
                                                        <a href="#" class="btn btn-info csRadius5" role="button">View Course</a> 
                                                    </div>    
                                                </div>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsInprogressCourses"></div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-heading-text text-medium">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <strong>Past Due</strong>
                                                    </div>    
                                                    <div class="col-sm-6 text-right">
                                                        <a href="#" class="btn btn-info csRadius5" role="button">View Course</a> 
                                                    </div>    
                                                </div>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsPastDueCourses"></div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-heading-text text-medium">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <strong>Due Soon</strong>
                                                    </div>    
                                                    <div class="col-sm-6 text-right">
                                                        <a href="#" class="btn btn-info csRadius5" role="button">View Course</a> 
                                                    </div>    
                                                </div>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsDueSoonCourses"></div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-heading-text text-medium">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <strong>Assigned Courses</strong>
                                                    </div>    
                                                    <div class="col-sm-6 text-right">
                                                        <a href="#" class="btn btn-info csRadius5" role="button">View Course</a> 
                                                    </div>    
                                                </div>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsAssignedCourses"></div>
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

<script>
    var page = "<?php echo $page; ?>";
    var subordinateId = "<?php echo $subordinate_sid; ?>";
    var subordinateName = "<?php echo $subordinateName; ?>";
    var reviewAs = "<?php echo $type; ?>";
</script>