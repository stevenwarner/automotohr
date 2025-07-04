<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('manage_ems'); ?>">
                                        <i class="fa fa-chevron-left"></i>Employee Management System
                                    </a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
<!--                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2"></div>-->
                        <?php if(check_access_permissions_for_view($security_details, 'online_video')) { ?>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <a href="<?php echo base_url('learning_center/online_videos'); ?>">
                                <div class="dash-box">
                                    <div class="dashboard-widget-box" style="margin-top: 50px; margin-bottom: 50px;">
                                        <figure>
                                            <i class="fa fa-youtube-square" style="font-size: 60px; color:#81b431;  transform: rotate(-30deg);"></i>
                                            <i class="fa fa-vimeo-square" style="font-size: 60px; color:#81b431; transform: rotate(30deg);"></i>
                                        </figure>
                                        <br />
                                        <br />
                                        <h2 class="post-title">
                                            <span>Online Videos</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } if(check_access_permissions_for_view($security_details, 'training_sessions')) { ?>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <a href="<?php echo base_url('learning_center/training_sessions'); ?>">
                                <div class="dash-box">
                                    <div class="dashboard-widget-box" style="margin-top: 50px; margin-bottom: 50px;">
                                        <figure>
                                            <i class="fa fa-user" style="font-size: 60px; color:#81b431;"></i>
                                            <i class="fa fa-users" style="font-size: 60px; color:#81b431;"></i>
                                        </figure>
                                        <br />
                                        <br />
                                        <h2 class="post-title">
                                            <span>Training Sessions</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php }?>
<!--                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2"></div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
