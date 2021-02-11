<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <a class="dashboard-link-btn" href="<?php echo base_url('my_listings')?>"><i class="fa fa-chevron-left"></i>My Jobs</a>
                                <?php echo $title; ?>
                            </span>
                        </div>
                        <div class="job_preview">
                            <?php   if($theme_name == 'theme-4') { ?>
                            <?php       echo $preview_content; ?>
                            <?php   } else { ?>
                                        <iframe class="job-preview-popup" src="<?php echo $preview_link; ?>"></iframe>
                            <?php   } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>