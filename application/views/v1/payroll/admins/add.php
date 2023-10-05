<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('v1/payroll/sidebar'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Top bar -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <!-- Company details header -->
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <!--  -->
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Dashboard
                                    </a>
                                    Manage Admins
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url('payrolls/admins'); ?>" class="btn btn-success csF16">
                                <i class="fa fa-chevron-left csF16" aria-hidden="true"></i>&nbsp;
                                Manage Admins
                            </a>
                        </div>
                    </div>

                    <br>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h2 class="csF16 csW"><strong>Add Admin</strong></h2>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <?php $this->load->view('loader_new', ['id' => 'jsAddAdminLoader']); ?>
                            <p class="text-danger csF16">
                                <em>
                                    <strong>
                                        Please note that all fields with "*" must be completed.
                                    </strong>
                                </em>
                            </p>
                            <br>
                            <div class="alert alert-danger jsErrorDiv hidden"></div>
                            <br>
                            <!--  -->
                            <form action="javascript:void(0)" id="jsAdminForm">
                                <div class="form-group">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <p class="text-danger"><strong><em>The first name of the admin.</em></strong></p>
                                    <input type="text" class="form-control jsAdminFirstName" />
                                </div>
                                <div class="form-group">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <p class="text-danger"><strong><em>The last name of the admin.</em></strong></p>
                                    <input type="text" class="form-control jsAdminLastName" />
                                </div>
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <p class="text-danger">
                                        <strong>
                                            <em>
                                                The email of the admin.
                                            </em>
                                        </strong>
                                    </p>
                                    <input type="text" class="form-control jsAdminEmailAddress" />
                                </div>
                                <div class="form-group text-right">
                                    <button class="btn btn-success jsSubmitBTN csF16">
                                        <i class="fa fa-save csF16" aria-hidden="true"></i>
                                        <span>Save Admin</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>