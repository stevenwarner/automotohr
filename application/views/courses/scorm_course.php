<div class="main jsmaincontent">
    <div class="container">
        <div style="position: relative; min-height: 500px;">
        
            <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>

            <div class="row">
                <div class="col-sm-12">
                    <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    <a href="<?php echo base_url('lms/courses/my'); ?>" class="btn btn-black csRadius5">
                        <i class="fa fa-arrow-left"></i>
                        &nbsp;Back to Courses
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                        <h1 class="section-ttile">
                            <?php echo $courseInfo["course_title"]; ?>
                        </h1>
                    </div>

                    <div class="section-inner">
                        <div class="heading-sec">
                            <div id="jsPreviewCourse"></div>
                        </div>
                    </div>
                    <div class="section-inner" id="jsStartCourseDiv">
                        <button class="btn btn-lg btn-orange csRadius5 jsStartCourseButton">Start Course</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var courseId = "<?php echo $course_sid; ?>";
    var courseType = "<?php echo $courseInfo['course_type']; ?>";
    var scormVersion = "<?php echo $courseInfo['version']; ?>";
    var mode = "<?php echo $viewMode; ?>";
    var CMIElementsObj = <?= json_encode($CMIObject); ?>;
    var lessonStatus = "<?php echo $lessonStatus; ?>";
</script>