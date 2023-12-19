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
                                    <i class="fa fa-cogs"></i>
                                    <?php echo $page_title; ?>
                                </h1>
                            </div>
                            <!-- Main body -->
                            <br />
                            <br />
                            <br />
                            <!-- Content area -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12 text-right">
                                    <!--  -->
                                    <a href="<?= base_url("sa/payrolls/company/" . $loggedInCompanyId . "/setup_payroll"); ?>" class="btn btn-success csF16">
                                        <i class="fa fa-cog csF16"></i>
                                        &nbsp;Setup Payroll
                                    </a>
                                </div>
                            </div>
                            <hr />

                            <!--  -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong class="csF16 csW">Company Payment Configs</strong>
                                        </div>
                                        <div class="panel-body">
                                            <!--  -->
                                            <p class="text-danger csF16">
                                                <em>
                                                    <strong>
                                                        Configure 2-day & 4-day ACH, create company specific earnings, run off-cycle payroll, create historical payroll, and create pay schedules.
                                                    </strong>
                                                </em>
                                            </p>
                                            <form action="javascript:void(0)" id="jsPaymentConfigurationForm">
                                                <div class="form-group">
                                                    <label>Payment Speed <span class="text-danger">*</span></label>
                                                    <?php
                                                    $speed = '1-day';
                                                    //
                                                    if (!empty($companyPaymentConfiguration['payment_speed'])) {
                                                        $speed = $companyPaymentConfiguration['payment_speed'];
                                                    }
                                                    ?>
                                                    <select name="payment_speed" class="form-control" id="jsPaymentSpeed">
                                                        <option value="1-day" <?= $speed == '1-day' ? 'selected' : ''; ?>>1 Day</option>
                                                        <option value="2-day" <?= $speed == '2-day' ? 'selected' : ''; ?>>2 Day</option>
                                                        <option value="4-day" <?= $speed == '4-day' ? 'selected' : ''; ?>>4 Day</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Fast Payment Limit</label>
                                                    <input type="text" class="form-control" value="<?= !empty($companyPaymentConfiguration['fast_payment_limit']) ? $companyPaymentConfiguration['fast_payment_limit'] : 0; ?>" id="jsFastPaymentLimit" />
                                                </div>

                                                <div class="form-group text-right">
                                                    <button class="btn btn-success jsSaveConfiguration csF16">
                                                        <i class="fa fa-save csF16" aria-hidden="true"></i>
                                                        <span>Save Payment Configuration</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong class="csF16 csW">Company Primary Admin</strong>
                                        </div>
                                        <div class="panel-body">
                                            <!--  -->
                                            <p class="text-danger csF16">
                                                <em>
                                                    <strong>
                                                        Primary Admin for Gusto Company Onboarding
                                                    </strong>
                                                </em>
                                            </p>
                                            <form action="javascript:void(0)" id="jsPaymentConfigurationForm">
                                                <?php
                                                $adminStatus = "";
                                                if ($primaryAdmin['is_sync'] == 1) {
                                                    $adminStatus = "disabled";
                                                }
                                                ?>
                                                <div class="form-group">
                                                    <label>First Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $primaryAdmin['first_name'] ?>" <?= $adminStatus ?> id="jsFirstName" />
                                                </div>

                                                <div class="form-group">
                                                    <label>Last Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $primaryAdmin['last_name'] ?>" <?= $adminStatus ?> id="jsLastName" />
                                                </div>

                                                <div class="form-group">
                                                    <label>Email <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $primaryAdmin['email_address'] ?>" <?= $adminStatus ?> id="jsEmail" />
                                                </div>

                                                <?php if ($primaryAdmin['is_sync'] == 0) { ?>
                                                    <div class="form-group text-right">
                                                        <button class="btn btn-success jsSaveDefaultAdmin csF16">
                                                            <i class="fa fa-save csF16" aria-hidden="true"></i>
                                                            <span>Save Primary Admin</span>
                                                        </button>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- .row  -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong class="csF16 csW">Company Mode</strong>
                                        </div>
                                        <div class="panel-body">
                                            <form action="javascript:void(0)" id="jsCompanyModeForm">
                                                <div class="form-group">
                                                    <label>Mode <span class="text-danger">*</span></label>
                                                    <select name="company_mode" class="form-control" id="jsCompanyMode">
                                                        <option value="demo" <?= $mode == 'Demo' ? 'selected' : ''; ?>>Demo</option>
                                                        <option value="production" <?= $mode == 'Production' ? 'selected' : ''; ?>>Production</option>
                                                    </select>
                                                </div>

                                                <div class="form-group text-right">
                                                    <button class="btn btn-success jsCompanyModeBtn csF16" type="submit">
                                                        <i class="fa fa-save csF16" aria-hidden="true"></i>
                                                        <span>Update Company Mode</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main body ends -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>