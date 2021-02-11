<!--<pre><?php //var_dump($questionnaire_information);  ?></pre>-->

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo $backUrl; ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back To References</a>
                                    <?php echo ucwords($questionnare_for) . ' ' . $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                <?php $this->load->view('reference_checks/reference_checks_questionnaire_form'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>

            </div>
        </div>
    </div>
</div>

