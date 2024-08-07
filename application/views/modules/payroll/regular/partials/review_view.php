<?php //_e($payroll); ?>
<br />
<div class="row">
    <div class="col-sm-12">
        <h3 class="text-large">
            <strong>
                Review <?= _a($payroll['totals']['net_pay_debit']); ?> withdrawal and submit payroll.
            </strong>
        </h3>
        <p class="text-medium">
            Here's a quick summary to review—we'll debit funds after you submit payroll. We saved your progress so you can submit this later. To pay your team on the pay date below, submit payroll by <strong><?=
                                                                                                                                                                                                                reset_datetime(
                                                                                                                                                                                                                    [
                                                                                                                                                                                                                        "datetime" => $payroll['payroll_deadline'],
                                                                                                                                                                                                                        "from_timezone" => "UTC",
                                                                                                                                                                                                                        "timezone" => "PDT",
                                                                                                                                                                                                                        "from_format" => 'Y-m-d\TH:i:sZ',
                                                                                                                                                                                                                        "format" => DATE." h:i A T",
                                                                                                                                                                                                                        "_this" => $this
                                                                                                                                                                                                                    ]
                                                                                                                                                                                                                );
                                                                                                                                                                                                                ?></strong>.
        </p>
    </div>
</div>

<br />
<div class="row">
    <div class="col-sm-4 col-x-12">
        <div class="cs-box bg-black ">
            <p class="text-medium">
                <strong>
                    Total payroll
                </strong>
            </p>
            <p class="text-medium">
                <?= _a($payroll['totals']['company_debit']); ?>
            </p>
        </div>
    </div>

    <div class="col-sm-4 col-x-12">
        <div class="cs-box bg-black ">
            <p class="text-medium">
                <strong>
                    Withdrawal amount
                </strong>
            </p>
            <p class="text-medium">
                <?= _a($payroll['totals']['net_pay_debit']); ?>
            </p>
        </div>
    </div>

    <div class="col-sm-4 col-x-12">
        <div class="cs-box bg-black ">
            <p class="text-medium">
                <strong>
                    Employee payday date
                </strong>
            </p>
            <p class="text-medium">
                <?= formatDateToDB($payroll['check_date'], DB_DATE, DATE); ?>
            </p>
        </div>
    </div>
</div>

<!-- Tax breakdown -->
<?php $this->load->view('modules/payroll/regular/partials/tax_breakdown'); ?>
<!-- employee hours and earnings -->
<?php $this->load->view('modules/payroll/regular/partials/employee_hours_and_earnings'); ?>
<!-- Company costs -->
<?php $this->load->view('modules/payroll/regular/partials/company_costs'); ?>


<div class="row">
    <div class="col-sm-12 text-right">
        <a href="<?= base_url('payrolls/regular/' . ($payroll['sid']) . '/timeoff'); ?>" class="btn bg-black text-medium">
            <i class="fa fa-long-arrow-left text-medium" aria-hidden="true"></i>
            &nbsp;Back
        </a>
        <button class="btn csW csBG3 text-medium jsSubmitPayroll">
            <i class="fa fa-save text-medium" aria-hidden="true"></i>
            &nbsp;Submit payroll
        </button>
    </div>
</div>