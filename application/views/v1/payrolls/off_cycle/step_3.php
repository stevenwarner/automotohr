<div class="csPageWrap">
    <!-- Page header row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-header-area">
                <span class="page-heading down-arrow">
                    <a href="<?= base_url('payrolls/dashboard') ?>" class="dashboard-link-btn">
                        Payroll Dashboard
                    </a>
                    <img src="<?php echo AWS_S3_BUCKET_URL . $session['company_detail']['Logo'] ?>" style="width: 75px; height: 75px;" class="img-rounded" />
                    <br />
                    <?php echo $session['company_detail']['CompanyName']; ?><br>
                    Run off cycle payroll
                </span>
            </div>
        </div>
    </div>
    <!-- main area -->
    <div class="row">
        <div class="col-sm-12">
            <h2>Off-cycle payroll</h2>
            <p>
            <h4> <strong> Review $0.00 withdrawal and submit payroll</h4> </strong>
            </p>
            <p> Here’s a quick summary to review—we’ll debit funds after you submit payroll. Or, download a full summary now. To pay your team on the pay date below, submit payroll by Wed Aug 2nd at 4pm PDT.</p>
        </div>
        <div class="col-sm-12">
            <div class="panel panel-success">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <h4><strong>Total payroll</strong></h4>
                                <p><strong>$0.00</strong></p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <h4><strong>Bank account</strong></h4>
                                <p><strong>XXXX4329</strong></p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <h4><strong>Withdrawal date</strong></h4>
                                <p><strong>Jan 01 2019, Tue</strong></p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <h4><strong>Employee payday</strong></h4>
                                <p><strong>Jan 01 2019, Tue</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <section class="alert alert-warning" role="alert">
                <h3 class="alert-title">You have $0 check amounts included on this payroll.</h3>
                <span> this was intentional, there’s nothing you need to do! Otherwise, you should skip the following employees on this payroll:</span><br><br>
                <span class="margin-top-2x">Isaiah Berlin, Patricia Churchland, Soren Kierkegaard, Hannah Arendt, Ludwig Wittgenstein, Regina Spektor, Immanuel Kant, Friedrich Nietzsche, Arthur Schopenhauer, Alexander Hamilton</span>
            </section>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <span class="pull-right">
            <button class="btn btn-success js-action-btn" data-step="step_2">Go Back</button>
            <button class="btn btn-success js-action-btn" data-step="step_4">Submit Payroll</button>
        </span>
    </div>
</div>