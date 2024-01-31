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
                                    <a href="<?= base_url("sa/payrolls/" . $loggedInCompanyId); ?>" class="btn csW csBG4 csF16">
                                        <i class="fa fa-arrow-left csF16"></i>
                                        &nbsp;Back to Configuration
                                    </a>
                                    <!-- admins -->
                                    <a href="<?= base_url("sa/payrolls/company/" . $loggedInCompanyId . "/admins/manage"); ?>" class="btn btn-success csF16">
                                        <i class="fa fa-users csF16"></i>
                                        &nbsp;Manage Admins
                                    </a>

                                    <?php if ($payrollBlockers && $mode == 'Demo') { ?>
                                        <button class="btn btn-success jsVerifyCompany csF16" title="Verify Company" placement="top">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;
                                            <span>Verify Company</span>
                                        </button>
                                        <button class="btn btn-success jsVerifyBankAccount csF16" title="Verify bank account" placement="top">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;
                                            <span>Verify Bank Account</span>
                                        </button>
                                    <?php } ?>
                                    <button class="btn btn-success jsSyncCompanyData csF16" title="Sync data" placement="top">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;
                                        <span>Sync</span>
                                    </button>
                                    <?php if (isCompanyVerifiedForPayroll($loggedInCompanyId)) { ?>
                                        <a href="<?= base_url("sa/payrolls/clair/" . $loggedInCompanyId); ?>" class="btn btn-success csF16">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;
                                            <span>Clair</span>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr />

                            <?php if ($payrollBlockers) { ?>
                                <!-- payroll blockers -->
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <strong class="csF16 csW">Payroll Blockers</strong>
                                    </div>
                                    <div class="panel-body">
                                        <!--  -->
                                        <p class="text-danger csF16">
                                            <em>
                                                <strong>
                                                    Kindly ensure all the following points are addressed for payroll processing and successful completion of company onboarding.
                                                </strong>
                                            </em>
                                        </p>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <caption></caption>
                                                <tbody>
                                                    <?php foreach ($payrollBlockers as $payrollBlocker) { ?>
                                                        <tr>
                                                            <th class="vam" scope="col">
                                                                <?=
                                                                ucwords(
                                                                    str_replace(
                                                                        '_',
                                                                        ' ',
                                                                        $payrollBlocker['key']
                                                                    )
                                                                ); ?></th>
                                                            <td class="vam"><?= $payrollBlocker['message']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- company flow -->
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <strong class="csF16 csW">Company Flow</strong>
                                </div>
                                <div class="panel-body">
                                    <!--  -->
                                    <p class="text-danger csF16">
                                        <em>
                                            <strong>
                                                Kindly adhere to the provided flow to finalize the company's payroll setup.
                                            </strong>
                                        </em>
                                    </p>
                                    <br />
                                    <!--  -->
                                    <iframe src="<?= $flow; ?>" frameborder="0" style="height: 800px; width: 100%">
                                    </iframe>
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