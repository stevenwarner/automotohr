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
                            <h1 class="panel-heading-text text-medium" style="margin: 0">
                                <strong>Payroll history</strong>
                            </h1>
                        </div>
                        <div class="panel-body">

                            <?php if (!$payrolls) { ?>
                                <?php $this->load->view('v1/no_data', [
                                    'message' => 'No payroll history found.'
                                ]); ?>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="csW csBG4">Check<br />date</th>
                                                <th scope="col" class="csW csBG4 text-right">Type</th>
                                                <th scope="col" class="csW csBG4 text-right">Description</th>
                                                <th scope="col" class="csW csBG4 text-right">Total<br />debited</th>
                                                <th scope="col" class="csW csBG4 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payrolls as $payroll) {

                                                $payroll['payroll_deadline'] = reset_datetime(
                                                    [
                                                        "datetime" => $payroll['payroll_deadline'],
                                                        "from_timezone" => "UTC",
                                                        "timezone" => "PDT",
                                                        "from_format" => 'Y-m-d\TH:i:sZ',
                                                        "format" => DB_DATE . " H:i:s",
                                                        "_this" => $this
                                                    ]
                                                );
                                            ?>
                                                <tr>
                                                    <td class="vam">
                                                        <?= formatDateToDB($payroll['check_date'], DB_DATE, DATE); ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?= !$payroll['off_cycle'] ? 'Regular' : $payroll['off_cycle_reason']; ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?= formatDateToDB($payroll['start_date'], DB_DATE, DATE); ?>
                                                        -
                                                        <?= formatDateToDB($payroll['end_date'], DB_DATE, DATE); ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?= _a($payroll['totals']['net_pay_debit']); ?>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <?php if ($payroll['payroll_deadline'] >= getSystemDate()) { ?>
                                                            <button class="btn btn-black jsCancelPayroll" data-span="<?= formatDateToDB($payroll['start_date'], DB_DATE, DATE); ?> - <?= formatDateToDB($payroll['end_date'], DB_DATE, DATE); ?>" data-deadline="<?= formatDateToDB($payroll['payroll_deadline'], DB_DATE_WITH_TIME, DATE. " h:i A T"); ?>" data-key="<?= $payroll['sid']; ?>" title="This payroll can still be cancelled." placement="top">
                                                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                                                                &nbsp;Cancel this payroll
                                                            </button>
                                                        <?php } ?>
                                                        <a href="<?= base_url('payrolls/history/' . $payroll['sid'] . ''); ?>" class="btn btn-orange">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                            &nbsp;View details
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                </div>
            </div>
        </div>
    </div>
</div>