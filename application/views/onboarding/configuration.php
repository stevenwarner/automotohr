<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <!-- <?php //$this->load->view('main/employer_column_left_view'); ?> -->
<!--                    --><?php //$this->load->view('manage_employer/settings_left_menu_administration'); ?>
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <a href="<?php echo base_url('manage_ems'); ?>" class="dashboard-link-btn">
                                    <i class="fa fa-chevron-left"></i>Employee Management System
                                </a>
                                Onboarding Configuration
                            </span>
                        </div>
                        <div class="accordion-colored-header header-bg-gray">
                            <div class="panel-group" id="onboarding-configuration-accordion">
<!--                                <div class="panel panel-default">
                                    <div class="panel-heading">                                        
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                               href="#getting-started"><span class="glyphicon glyphicon-plus"></span>
                                                   <?php /*if (empty($getting_started_sections)) { ?>
                                                    <div class="btn btn-xs btn-danger pull-right">
                                                        <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="btn btn-xs btn-success pull-right">
                                                        <i class="fa fa-check-circle"></i>&nbsp;Configured
                                                    </div>
                                                <?php } */?>
                                                Getting Started
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="getting-started" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <?php //$this->load->view('onboarding/configuration_getting_started'); ?>
                                        </div>
                                    </div>
                                </div>-->
                                <?php if (check_access_permissions_for_view($security_details, 'send_reminder')) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion" href="#welcome_video">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                    <?php if (empty($onboarding_welcome_video)) { ?>
                                                        <div class="btn btn-xs btn-danger pull-right">
                                                            <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="btn btn-xs btn-success pull-right">
                                                            <i class="fa fa-check-circle"></i>&nbsp;Configured
                                                        </div>
                                                    <?php } ?>
                                                    Welcome Video for Onboarding
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="welcome_video" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <?php $this->load->view('onboarding/configuration_welcome_videos'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (check_access_permissions_for_view($security_details, 'onboarding_instructions')) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion" href="#getting-started">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                    <?php if (empty($onboarding_instructions_data)) { ?>
                                                        <div class="btn btn-xs btn-danger pull-right">
                                                            <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="btn btn-xs btn-success pull-right">
                                                            <i class="fa fa-check-circle"></i>&nbsp;Configured
                                                        </div>
                                                    <?php } ?>
                                                    On-boarding Instructions
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="getting-started" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <?php $this->load->view('onboarding/configuration_getting_started_instructions'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (check_access_permissions_for_view($security_details, 'office_location')) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                   href="#office-locations"><span class="glyphicon glyphicon-plus"></span>
                                                    <?php if (empty($office_locations)) { ?>
                                                        <div class="btn btn-xs btn-danger pull-right">
                                                            <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="btn btn-xs btn-success pull-right">
                                                            <i class="fa fa-check-circle"></i>&nbsp;Configured
                                                        </div>
                                                    <?php } ?>
                                                    Office Locations
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="office-locations" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <?php $this->load->view('onboarding/configuration_office_locations'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (check_access_permissions_for_view($security_details, 'office_hours')) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                   href="#office-timings"><span class="glyphicon glyphicon-plus"></span>
                                                       <?php if (empty($office_timings)) { ?>
                                                        <div class="btn btn-xs btn-danger pull-right">
                                                            <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="btn btn-xs btn-success pull-right">
                                                            <i class="fa fa-check-circle"></i>&nbsp;Configured
                                                        </div>
                                                    <?php } ?>
                                                    Office Hours
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="office-timings" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <?php $this->load->view('onboarding/configuration_office_timings'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (check_access_permissions_for_view($security_details, 'people_to_meet')) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                   href="#people-to-meet"><span class="glyphicon glyphicon-plus"></span>
                                                       <?php if (empty($people)) { ?>
                                                        <div class="btn btn-xs btn-danger pull-right">
                                                            <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="btn btn-xs btn-success pull-right">
                                                            <i class="fa fa-check-circle"></i>&nbsp;Configured
                                                        </div>
                                                    <?php } ?>
                                                    People To Meet
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="people-to-meet" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <?php $this->load->view('onboarding/configuration_people_to_meet'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (check_access_permissions_for_view($security_details, 'what_to_bring')) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                   href="#what-to-bring"><span class="glyphicon glyphicon-plus"></span>
                                                       <?php if (empty($what_to_bring_items)) { ?>
                                                        <div class="btn btn-xs btn-danger pull-right">
                                                            <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="btn btn-xs btn-success pull-right">
                                                            <i class="fa fa-check-circle"></i>&nbsp;Configured
                                                        </div>
                                                    <?php } ?>
                                                    What To Bring
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="what-to-bring" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <?php $this->load->view('onboarding/configuration_what_to_bring'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (check_access_permissions_for_view($security_details, 'useful_links')) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                   href="#useful-link"><span class="glyphicon glyphicon-plus"></span>
                                                       <?php if (empty($useful_links)) { ?>
                                                        <div class="btn btn-xs btn-danger pull-right">
                                                            <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="btn btn-xs btn-success pull-right">
                                                            <i class="fa fa-check-circle"></i>&nbsp;Configured
                                                        </div>
                                                    <?php } ?>
                                                    Useful Links
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="useful-link" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <?php $this->load->view('onboarding/configuration_useful_links'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

<!--                                <div class="panel panel-default">-->
<!--                                    <div class="panel-heading">-->
<!--                                        <h4 class="panel-title">-->
<!--                                            <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"-->
<!--                                               href="#dash-noti-link"><span class="glyphicon glyphicon-plus"></span>-->
<!--                                                   --><?php //if (empty($useful_links)) { ?>
<!--                                                    <div class="btn btn-xs btn-danger pull-right">-->
<!--                                                        <i class="fa fa-arrow-circle-left"></i>&nbsp;Please Configure This Section-->
<!--                                                    </div>-->
<!--                                                --><?php //} else { ?>
<!--                                                    <div class="btn btn-xs btn-success pull-right">-->
<!--                                                        <i class="fa fa-check-circle"></i>&nbsp;Configured-->
<!--                                                    </div>-->
<!--                                                --><?php //} ?>
<!--                                                Employee EMS Notification-->
<!--                                            </a>-->
<!--                                        </h4>-->
<!--                                    </div>-->
<!--                                    <div id="dash-noti-link" class="panel-collapse collapse">-->
<!--                                        <div class="panel-body">-->
<!--                                            --><?php //$this->load->view('onboarding/configuration_dashboard_notification'); ?>
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
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
