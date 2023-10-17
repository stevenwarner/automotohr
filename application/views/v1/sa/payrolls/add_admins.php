<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="heading-title page-title">
                                <h1 class="page-title">
                                    <i class="fa fa-users"></i>
                                    <?php echo $title; ?>
                                </h1>
                            </div>
                            <!-- Main body -->
                            <br />
                            <br />
                            <br />
                            <!-- Content area -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12 text-right">
                                    <!-- admins -->
                                    <a href="<?= base_url("sa/payrolls/" . $loggedInCompanyId); ?>" class="btn csW csBG4 csF16">
                                        <i class="fa fa-arrow-left csF16"></i>
                                        &nbsp;Back
                                    </a>
                                    <a href="<?= base_url("sa/payrolls/company/" . $loggedInCompanyId . "/admins/manage"); ?>" class="btn btn-success csF16" title="Add and admin" placement="top">
                                        <i class="fa fa-arrow-left csF16" aria-hidden="true"></i>&nbsp;
                                        Back To Manage Admins
                                    </a>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-sm-12">
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
            </div>
        </div>
    </div>
</div>