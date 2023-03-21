<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                <a href="<?php echo base_url('manage_ems'); ?>" class="dashboard-link-btn">
                                    <i class="fa fa-chevron-left"></i>Employee Management System
                                </a>
                                Employee EMS Configuration
                            </span>
                        </div>
                        <div class="accordion-colored-header header-bg-gray">
                            <div class="panel-group" id="onboarding-configuration-accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <b>Employee EMS Notification</b>
                                        </h4>
                                    </div>
                                    <div id="dash-noti-link" class="panel-collapse collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <?php $this->load->view('onboarding/configuration_dashboard_notification'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });
</script>
