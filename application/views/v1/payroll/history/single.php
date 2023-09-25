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

                    <!-- br -->
                    <br />

                    <div class="row">
                        <div class="col-sm-8 col-xs-12"></div>
                        <div class="col-sm-4 col-xs-12 text-right">
                            <a href="<?= base_url('payrolls/history') ?>" class="btn csW csBG4 csF16">
                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                &nbsp;Back to payroll history
                            </a>
                        </div>
                    </div>

                    <br />

                    <div class="row">
                        <div class="col-sm-8 col-xs-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="csF16 csW m0">
                                        <strong>
                                            Payroll receipt
                                        </strong>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <p class="csF16">
                                                From:
                                            </p>
                                        </div>
                                        <div class="col-sm-11">
                                            <p class="csF16">
                                                <?= $payroll['payroll_receipt']['name_of_sender']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <p class="csF16">
                                                To:
                                            </p>
                                        </div>
                                        <div class="col-sm-11">
                                            <p class="csF16">
                                                <?= $payroll['payroll_receipt']['name_of_recipient']; ?>
                                                &nbsp;<i class="fa fa-info-circle csF16" aria-hidden="true" title="<?= $payroll['payroll_receipt']['recipient_notice']; ?>" placement="top"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="csF16">
                                                For funds debited on <strong><?= formatDateToDB($payroll['payroll_receipt']['debit_date'], DB_DATE, DATE); ?></strong>.
                                            </p>
                                        </div>
                                    </div>

                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <p class="csF16">
                                                <strong>
                                                    Total
                                                </strong>
                                            </p>
                                        </div>
                                        <div class="col-sm-11 text-right">
                                            <p class="csF16">
                                                <strong>
                                                    <?= _a($payroll['payroll_receipt']['totals']['net_pay_debit']); ?>
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="csF12">
                                                These amounts may not represent all monies due to government tax authorities from you, and do not include any amounts transmitted outside the Gusto platform.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- right side -->
                        <div class="col-sm-4 col-xs-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="csF16 csW m0">
                                        <strong>
                                            Payroll details
                                        </strong>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3 class="csF16">
                                                <strong>Pay date</strong>
                                            </h3>
                                            <p class="csF16">
                                                <?= formatDateToDB($payroll['check_date'], DB_DATE, DATE); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3 class="csF16">
                                                <strong>Pay period</strong>
                                            </h3>
                                            <p class="csF16">
                                                <?= formatDateToDB($payroll['start_date'], DB_DATE, DATE); ?>
                                                -
                                                <?= formatDateToDB($payroll['end_date'], DB_DATE, DATE); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3 class="csF16">
                                                <strong>Processed on</strong>
                                            </h3>
                                            <p class="csF16">
                                                <?= formatDateToDB($payroll['processed_date'], DB_DATE_WITH_TIME, DATE); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $payroll['payroll_deadline'] = formatDateToDB($payroll['payroll_deadline'], 'Y-m-d\TH:i:sZ', DB_DATE) . ' 16:00:00'; ?>
                            <?php if ($payroll['payroll_deadline'] >= getSystemDate()) { ?>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h3 class="csF16 csW m0">
                                            <strong>
                                                Need to cancel?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <p class="csF16">
                                            If you cancel this payroll, we'll save all the info you entered as a draft, in case you need to re-run it.
                                        </p>
                                        <button class="btn csW csF16 csBG4 jsCancelPayroll" data-span="<?= formatDateToDB($payroll['start_date'], DB_DATE, DATE); ?> - <?= formatDateToDB($payroll['end_date'], DB_DATE, DATE); ?>" data-deadline="<?= formatDateToDB($payroll['payroll_deadline'], DB_DATE_WITH_TIME, DATE); ?>" data-key="<?= $payroll['sid']; ?>">
                                            <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                                            &nbsp;Cancel this payroll
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Tax breakdown -->
                    <?php $this->load->view('v1/payroll/regular/partials/tax_breakdown'); ?>
                    <!-- employee hours and earnings -->
                    <?php $this->load->view('v1/payroll/regular/partials/employee_hours_and_earnings'); ?>
                    <!-- Company costs -->
                    <?php $this->load->view('v1/payroll/regular/partials/company_costs'); ?>

                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                </div>
            </div>
        </div>
    </div>
</div>