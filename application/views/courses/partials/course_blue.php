<?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6" id="js-to-box">
        <a href="<?= base_url('lms_courses/my_courses'); ?>">
            <div class="widget-box">
                <div class="link-box bg-redish full-width">
                    <h2 class="text-blue">Courses Library</h2>
                    <div><span>&nbsp;</span></div>
                    <div class="current-date">
                        <span><?php echo $my_course_count; ?><sub>Pending</sub></span>
                    </div>
                    <div class="status-panel">
                        <h3>Company Courses</h3>
                        <span>Assigned to You</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
<?php } ?>