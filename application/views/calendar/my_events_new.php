<?php if (!$load_view) { ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Calender / Events</span>
                        </div><?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="job-feature-main m_job">
                            <div class="portalmid">
                                <div id='calendar'></div>

                                <div id="my_loader" class="text-center my_loader">
                                    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                                    <div class="loader-icon-box">
                                        <i class="fa fa-refresh fa-spin my_spinner" aria-hidden="true" style="visibility: visible;"></i>
                                        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
                                        </div>
                                    </div>
                                </div>
                                <?php $this->load->view('calendar/popup_modal_partial'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('calendar/scripts_partial'); ?>
<?php } else { ?>
    <?php $this->load->view('onboarding/calendar'); ?>
<?php } ?>