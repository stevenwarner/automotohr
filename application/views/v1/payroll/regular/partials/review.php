<br />
<div class="row">
    <div class="col-sm-12">
        <h3 class="csF18">
            <strong>
                Review <?= _a($payroll['totals']['net_pay_debit']); ?> withdrawal and submit payroll.
            </strong>
        </h3>
        <p class="csF16">
            Here's a quick summary to review—we'll debit funds after you submit payroll. We saved your progress so you can submit this later. To pay your team on the pay date below, submit payroll by <strong><?= formatDateToDB($payroll['payroll_deadline'], 'Y-m-d\TH:i:sZ', DATE); ?></strong> at <strong>4pm PDT</strong>.
        </p>
    </div>
</div>


<br />
<div class="row">
    <div class="col-sm-4 col-x-12">
        <div class="csW csBG4 p10 csRadius5">
            <p class="csF16">
                <strong>
                    Total payroll
                </strong>
            </p>
            <p class="csF16">
                <?= _a($payroll['totals']['company_debit']); ?>
            </p>
        </div>
    </div>

    <div class="col-sm-4 col-x-12">
        <div class="csW csBG4 p10 csRadius5">
            <p class="csF16">
                <strong>
                    Withdrawal amount
                </strong>
            </p>
            <p class="csF16">
                <?= _a($payroll['totals']['net_pay_debit']); ?>
            </p>
        </div>
    </div>

    <div class="col-sm-4 col-x-12">
        <div class="csW csBG4 p10 csRadius5">
            <p class="csF16">
                <strong>
                    Employee payday date
                </strong>
            </p>
            <p class="csF16">
                <?= formatDateToDB($payroll['check_date'], DB_DATE, DATE); ?>
            </p>
        </div>
    </div>
</div>

<!-- Tax breakdown -->
<?php $this->load->view('v1/payroll/regular/partials/tax_breakdown'); ?>
<!-- employee hours and earnings -->
<?php $this->load->view('v1/payroll/regular/partials/employee_hours_and_earnings'); ?>
<!-- Company costs -->
<?php $this->load->view('v1/payroll/regular/partials/company_costs'); ?>


<div class="row">
    <div class="col-sm-12 text-right">
        <a href="<?= base_url('payrolls/regular/' . ($payroll['sid']) . '/timeoff'); ?>" class="btn csW csBG4 csF16">
            <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
            &nbsp;Back
        </a>
        <button class="btn csW csBG3 csF16 jsSubmitPayroll">
            <i class="fa fa-save csF16" aria-hidden="true"></i>
            &nbsp;Submit payroll
        </button>
    </div>
</div>