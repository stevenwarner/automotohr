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
                                </span>
                            </div>
                        </div>
                    </div>
                    <div style="position: relative">
                        <?php
                        $this
                            ->load
                            ->view('loader_new', [
                                'id' => 'jsDashboard',
                                "message" => "Please wait, while we are generating view."
                            ]); ?>
                        <!-- Content area -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 text-right">
                                <?php if ($payrollBlockers && !$companyOnProduction) { ?>
                                    <button class="btn btn-success jsVerifyCompany csF16" title="Verify Company" placement="top">
                                        <i class="fa fa-shield csF16" aria-hidden="true"></i>&nbsp;
                                        <span>Verify Company</span>
                                    </button>
                                    <button class="btn btn-success jsVerifyBankAccount csF16" title="Verify bank account" placement="top">
                                        <i class="fa fa-check-circle csF16" aria-hidden="true"></i>&nbsp;
                                        <span>Verify Bank Account</span>
                                    </button>
                                <?php } ?>
                                <?php if (!$companyOnProduction) { ?>
                                    <button class="btn btn-success jsSyncCompanyData csF16" title="Sync data" placement="top">
                                        <i class="fa fa-refresh csF16" aria-hidden="true"></i>&nbsp;
                                        <span>Sync</span>
                                    </button>
                                <?php } ?>
                                <?php if (isCompanyVerifiedForPayroll()) { ?>
                                    <a class="btn btn-success csF16" href="<?= base_url("payrolls/clair/company"); ?>">
                                        <i class="fa fa-cogs csF16" aria-hidden="true"></i>&nbsp;
                                        <span>Set up Clair</span>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <hr />
                        <?php if ($payrollBlockers["blocker_json"]) { ?>

                            <!-- payroll blockers -->
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong class="text-medium">
                                                Payroll Blockers
                                            </strong>
                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <strong class="text-medium">
                                                last updated on:
                                                <?= formatDateToDB(
                                                    $payrollBlockers["updated_at"],
                                                    DB_DATE_WITH_TIME,
                                                    DATE_WITH_TIME
                                                ); ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <!--  -->
                                    <p class="text-danger text-medium">
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
                                                <?php foreach ($payrollBlockers["blocker_json"] as $v0) { ?>
                                                    <tr>
                                                        <th class="vam" scope="col">
                                                            <?=
                                                            ucwords(
                                                                str_replace(
                                                                    '_',
                                                                    ' ',
                                                                    $v0['key']
                                                                )
                                                            ); ?></th>
                                                        <td class="vam"><?= $v0['message']; ?></td>
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
                                <strong class="text-medium">Company Flow</strong>
                            </div>
                            <div class="panel-body">
                                <!--  -->
                                <p class="text-danger text-medium">
                                    <em>
                                        <strong>
                                            Kindly adhere to the provided flow to finalize the company's payroll setup.
                                        </strong>
                                    </em>
                                </p>
                                <br />
                                <?php if ($flow["errors"]) { ?>
                                    <div class="alert alert-danger">
                                        <strong class="text-medium">
                                            Errors!
                                        </strong>
                                        <hr />
                                        <?php foreach ($flow["errors"] as $v0) {
                                        ?>
                                            <p class="text-medium">
                                                <?= $v0; ?> <br />
                                            </p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                <?php } else { ?>
                                    <iframe src="<?= $flow["url"]; ?>" frameborder="0" style="height: 800px; width: 100%"></iframe>
                                <?php } ?>
                                <!--  -->
                            </div>
                        </div>


                    </div>


                </div>
            </div>
        </div>
    </div>
</div>