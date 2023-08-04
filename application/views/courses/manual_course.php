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
                        <h1 class="section-ttile">
                            <?php echo $courseInfo["course_title"]; ?> 
                            <div style="float: right;">
                                <button class="btn btn-info btn-orange csRadius5 jsSaveQuestionResult"><i class="fa fa-floppy-o"></i> Save</button>
                                <a href="<?php echo base_url('lms/courses/my'); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to Courses</a>
                            </div>
                        </h1>
                    </div>

                    <div class="section-inner">
                        <div class="heading-sec">
                            <div id="jsPreviewCourse"></div> 
                            <div id="jsPreviewCourseQuestion"></div> 
                        </div>
                    </div>

                    <div class="page-footer">
                        <h1 class="section-ttile">
                            <div style="float: right;">
                                <button class="btn btn-info btn-orange csRadius5 jsSaveQuestionResult"><i class="fa fa-floppy-o"></i> Save</button>
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

<script>
    var courseId = "<?php echo $course_sid; ?>";
    var courseType = "<?php echo $courseInfo['course_type']; ?>";
    var questions = <?= json_encode($questions); ?>;
    var mode = "<?php echo $viewMode; ?>";
</script>