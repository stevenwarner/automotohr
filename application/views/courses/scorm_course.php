<div class="main jsmaincontent">
    <div class="container-fluid">
        <div style="position: relative; min-height: 500px;">

            <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>

            <div class="row">
                <div class="col-lg-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo $employee['access_level'] == 'Employee' ?  base_url('employee_management_system') : base_url('dashboard'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    </div>
                </div>

                <br>

                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                        <h1 class="section-ttile">
                            <?php echo $courseInfo["course_title"]; ?>
                            <div style="float: right;">
                                <?php if ($viewMode == "preview_my_history") { ?>
                                    <a href="<?php echo base_url('lms/courses/my_courses_history'); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to History</a>
                                <?php } else if ($viewMode == "preview_subordinate_history") { ?>
                                    <a href="<?php echo base_url('lms/courses/employee_courses_history/' . $reviewAs . '/' . $subordinate_sid); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to History</a>
                                <?php } else if ($viewMode == "preview_only") { ?>
                                    <?php if ($reviewAs == "plus") { ?>
                                        <a href="<?php echo base_url('lms/employee/courses/dashboard/' . $subordinate_sid); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to Courses</a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url('lms/subordinate/courses/dashboard/' . $subordinate_sid); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to Courses</a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a href="<?php echo base_url('lms/courses/my_lms_dashboard'); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to Courses</a>
                                <?php } ?>
                            </div>
                        </h1>
                    </div>
                    <br>
                    <div class="section-inner">
                        <div class="heading-sec">
                            <div id="jsPreviewCourse"></div>
                        </div>
                    </div>
                    <div class="section-inner dn" id="jsStartCourseDiv">
                        <button class="btn btn-lg btn-orange csRadius5 jsStartCourseButton"><i class="fa fa-play"></i> Start Course</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var courseId = "<?php echo $course_sid; ?>";
    var courseType = "<?php echo $courseInfo['course_type']; ?>";
    var courseLanguage = "<?php echo $language; ?>";
    var scormVersion = "<?php echo $courseInfo['version']; ?>";
    var mode = "<?php echo $viewMode; ?>";
    var CMIElementsObj = <?= json_encode($CMIObject); ?>;
    var lessonStatus = "<?php echo $lessonStatus; ?>";
    var page = "<?php echo $page; ?>";
    var subordinateId = "<?php echo $subordinate_sid; ?>";
    var reviewAs = "<?php echo $reviewAs; ?>";
    var search = "<?php echo $search; ?>";
</script>