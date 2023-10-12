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

                    <?php $this->load->view('loader_new', ['id' => 'jsDashboard']); ?>
                    <!-- Content area -->
                    <div class="row">
                        <div class="col-sm-12 col-md-12 text-right">
                            <?php if ($payrollBlockers) { ?>
                                <button class="btn btn-success jsVerifyCompany csF16" title="Verify Company" placement="top">
                                    <i class="fa fa-shield csF16" aria-hidden="true"></i>&nbsp;
                                    <span>Verify Company</span>
                                </button>
                                <button class="btn btn-success jsVerifyBankAccount csF16" title="Verify bank account" placement="top">
                                    <i class="fa fa-check-circle csF16" aria-hidden="true"></i>&nbsp;
                                    <span>Verify Bank Account</span>
                                </button>
                            <?php } ?>
                            <button class="btn btn-success jsSyncCompanyData csF16" title="Sync data" placement="top">
                                <i class="fa fa-refresh csF16" aria-hidden="true"></i>&nbsp;
                                <span>Sync</span>
                            </button>
                        </div>
                    </div>
                    <hr />
                    <?php if (!$payrollBlockers && !$companyGustoDetails['added_historical_payrolls']) {
                        $this->load->view('v1/payroll/historical_info');
                    } ?>

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


                </div>
            </div>
        </div>
    </div>
</div>