<div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('Performance_management/theme2//left_menu'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?></span>
                        </div>

                        <div style="position: relative;">
                        <?php $this->load->view("{$pp}settings_blue"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>