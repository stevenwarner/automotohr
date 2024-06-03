<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <?php $this->load->view('manage_employer/company_logo_name'); ?>
                            </span>
                        </div>
                        <div class="setting-grid">
                            <div class="alert alert-info text-center text-large">
                                Please wait, while we are setting up payroll. This might take a few minutes.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>