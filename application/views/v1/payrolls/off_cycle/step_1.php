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
            <h3>Off-cycle payroll - select employees</h3>
            <p>Select who will be on this payroll. You can only choose from employees at your company.</p>
        </div>
    </div>
</div>