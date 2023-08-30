<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view($left_navigation); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="table-responsive table-outer">
                        <div></div>
                        <div id="show_no_jobs">
                            <span class="applicant-not-found">Employee not found!</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>