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

                    <!--  -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-heading-text text-medium">
                                <strong>External Payrolls</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-8 col-xs-12">
                                    <p class="csF16">To make sure we file your taxes properly, we need to collect some info from your employees' previous payrolls.</p>
                                </div>
                                <div class="col-sm-4 col-xs-12 text-right">
                                    <?php if (!$processedPayrollCount && (!$hasExternalPayroll || $hasUnProcessedExternalPayroll)) { ?>
                                        <a href="<?= base_url('payrolls/external/create'); ?>" class="btn csW csBG3 csF16">
                                            <i class="fa fa-plus-circle csF16"></i>
                                            &nbsp;Create an external payroll
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr />
                            <?php $this->load->view('v1/payroll/historical_info'); ?>
                            <?php if (!$externalPayrolls) { ?>
                                <?php $this->load->view('v1/no_data', [
                                    'message' => 'Once added, your external payrolls will be listed here'
                                ]); ?>
                            <?php } else { ?>
                                <!--  -->
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="csBG4">Check date</th>
                                                <th scope="col" class="csBG4">Pay periods</th>
                                                <th scope="col" class="csBG4"># of employees</th>
                                                <th scope="col" class="text-right csBG4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($externalPayrolls as $value) { ?>
                                                <tr data-id="<?= $value['sid']; ?>">
                                                    <td class="vam">
                                                        <p class="csF16">
                                                            <?= formatDateToDB($value['check_date'], DB_DATE, DATE); ?>
                                                        </p>
                                                    </td>
                                                    <td class="vam">
                                                        <p class="csF16">
                                                            <?= formatDateToDB($value['payment_period_start_date'], DB_DATE, DATE); ?>
                                                            -
                                                            <?= formatDateToDB($value['payment_period_end_date'], DB_DATE, DATE); ?>
                                                        </p>
                                                    </td>
                                                    <td class="vam">
                                                        <p class="csF16">
                                                            <?= $value['employees_count']; ?>
                                                        </p>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?php if ($value['is_processed'] == 0) { ?>
                                                            <button class="btn btn-danger csF16 jsExternalPayrollDelete">
                                                                <i class="fa fa-times-circle csF16"></i>
                                                                &nbsp;Delete
                                                            </button>
                                                            <a href="<?= base_url('payrolls/external/' . ($value['sid']) . ''); ?>" class="btn btn-warning csF16">
                                                                <i class="fa fa-edit csF16"></i>
                                                                &nbsp;Update
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="<?= base_url('payrolls/external/' . ($value['sid']) . ''); ?>" class="btn csW csBG3 csF16">
                                                                <i class="fa fa-eye csF16"></i>
                                                                &nbsp;View
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <?php if (!$processedPayrollCount && $hasUnProcessedExternalPayroll) { ?>
                                            <tfoot>
                                                <tr>
                                                    <td class="vam text-right" colspan="3">
                                                        <a href="<?= base_url("payrolls/external/confirm-tax-liabilities"); ?>" class="btn csW csBG3 csF16">
                                                            <i class="fa fa-check-circle csF16"></i>
                                                            &nbsp;Confirm tax liability
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        <?php } ?>
                                    </table>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>