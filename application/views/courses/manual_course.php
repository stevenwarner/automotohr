<?php if ($load_view) { ?>
    <div class="main">
        <div class="container-fluid">
        <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
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
                        <h1 class="section-ttile">
                            <?php echo $courseInfo["course_title"]; ?> 
                            <div style="float: right;">
                                <?php if ($viewMode == "preview_my_history") { ?>
                                    <a href="<?php echo base_url('lms/courses/my_courses_history'); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to History</a>
                                <?php } else if ($viewMode == "preview_subordinate_history") { ?>
                                    <a href="<?php echo base_url('lms/courses/employee_courses_history/'.$reviewAs.'/'.$subordinate_sid); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i>  Back to History</a>
                                <?php } else if ($viewMode == "preview_only") { ?>
                                    <?php if ($reviewAs == "plus") { ?>
                                        <a href="<?php echo base_url('lms/employee/courses/dashboard/'.$subordinate_sid); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to Courses</a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url('lms/subordinate/courses/dashboard/'.$subordinate_sid); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to Courses</a>
                                    <?php } ?>
                                <?php } else { ?> 
                                    <a href="<?php echo base_url('lms/courses/my_lms_dashboard'); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to Courses</a>
                                <?php } ?>
                            </div>
                        </h1>
                    </div>

                    <div class="section-inner">
                        <div class="heading-sec">
                            <div id="jsPreviewCourse"></div> 
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right csRP">
                                <button class="btn btn-info btn-orange csRadius5 jsSaveQuestionResult"><i class="fa fa-floppy-o"></i> Submit Answers</button>
                            </div>
                            <br>
                            <div id="jsPreviewCourseQuestion"></div> 
                        </div>
                    </div>

                    <div class="section-inner dn" id="jsStartCourseDiv">
                        <button class="btn btn-lg btn-orange csRadius5 jsStartCourseButton"><i class="fa fa-play"></i> Start Course</button>
                    </div>

                    <div class="page-footer">
                        <h1 class="section-ttile">
                            <div style="float: right;">
                                <button class="btn btn-info btn-orange csRadius5 jsSaveQuestionResult"><i class="fa fa-floppy-o"></i> Submit Answers</button>
                            </div>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <?php echo $this->load->view('learning_center/my_courses_blue'); ?>
<?php } ?>

<style>
    #jsPreviewCourseQuestion {
        margin-top: 28px;
    }
</style>

<script>
    var courseId = "<?php echo $course_sid; ?>";
    var courseType = "<?php echo $courseInfo['course_type']; ?>";
    var courseLanguage = "<?php echo $language; ?>";
    var questions = <?= json_encode($questions); ?>;
    var mode = "<?php echo $viewMode; ?>";
    var lessonStatus = "<?php echo $lessonStatus; ?>";
    var page = "<?php echo $page; ?>";
    var subordinateId = "<?php echo $subordinate_sid; ?>";
    var search = "<?php echo $search; ?>";
    console.log(mode)
</script>