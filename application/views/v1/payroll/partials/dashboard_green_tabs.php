<?php if (checkIfAppIsEnabled(PAYROLL)) { ?>
    <?php
    $isCompanyOnPayroll = $payrollChecks["isCompanyOnPayroll"];
    $isTermsAgreed = $payrollChecks["isTermsAgreed"];
    $isSyncInProgress = $payrollChecks["isSyncInProgress"];
    ?>

    <?php if (!$isCompanyOnPayroll && isPayrollOrPlus()) { ?>
        <!-- Set up payroll -->
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
            <div class="dash-box">
                <div class="dashboard-widget-box">
                    <figure>
                        <i class="fa fa-dollar" aria-hidden="true"></i>
                    </figure>
                    <h2 class="post-title">
                        <a href="#" class="jsCreatePartnerCompanyBtn" data-cid="<?= $this->session->userdata('logged_in')['company_detail']['sid']; ?>">Payroll</a>
                    </h2>
                    <div class="count-box" style="font-size: 12px">
                        <small style="font-size: 12px"></small>
                    </div>
                    <div class="button-panel">
                        <a href="#" class="site-btn jsCreatePartnerCompanyBtn" data-cid="<?= $this->session->userdata('logged_in')['company_detail']['sid']; ?>">Set-up Payroll</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($isCompanyOnPayroll && !$isTermsAgreed) { ?>
        <!-- Service agreement -->
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
            <div class="dash-box">
                <div class="dashboard-widget-box">
                    <figure>
                        <i class="fa fa-dollar" aria-hidden="true"></i>
                    </figure>
                    <h2 class="post-title">
                        <a href="#" class="jsServiceAgreement" data-cid="<?= $this->session->userdata('logged_in')['company_detail']['sid']; ?>">Payroll</a>
                    </h2>
                    <div class="count-box" style="font-size: 12px">
                        <small style="font-size: 12px"></small>
                    </div>
                    <div class="button-panel">
                        <a href="#" class="site-btn jsServiceAgreement" data-cid="<?= $this->session->userdata('logged_in')['company_detail']['sid']; ?>">Payroll Service Agreement</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($isSyncInProgress && isPayrollOrPlus()) { ?>
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
            <div class="dash-box">
                <div class="dashboard-widget-box">
                    <figure>
                        <i class="fa fa-cog fa-spin" aria-hidden="true"></i>
                    </figure>
                    <h2 class="post-title">
                        <a href="javascript:void(0)">Payroll</a>
                    </h2>
                    <div class="count-box" style="font-size: 12px">
                        <small style="font-size: 12px">
                            Please wait, while we are setting up payroll. This may take several minutes.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($isCompanyOnPayroll && !$isSyncInProgress && $isTermsAgreed && isPayrollOrPlus()) { ?>
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
            <div class="dash-box">
                <div class="dashboard-widget-box">
                    <figure>
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                    </figure>
                    <h2 class="post-title">
                        <a href="<?= base_url('payroll/dashboard'); ?>">Payroll Dashboard</a>
                    </h2>
                    <div class="count-box" style="font-size: 12px">
                        <small style="font-size: 12px"></small>
                    </div>
                    <div class="button-panel">
                        <a href="<?= base_url('payroll/dashboard'); ?>" class="site-btn">View Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($isCompanyOnPayroll && !$isSyncInProgress && $isTermsAgreed && $payrollChecks["isEmployeeOnPayroll"]) { ?>
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
            <div class="dash-box">
                <div class="dashboard-widget-box">
                    <figure>
                        <i class="fa fa-dollar" aria-hidden="true"></i>
                    </figure>
                    <h2 class="post-title">
                        <a href="<?= base_url('payrolls/pay-stubs'); ?>">Pay Stubs</a>
                    </h2>
                    <div class="count-box" style="font-size: 12px">
                        <small style="font-size: 12px"><?= $employeePayStubsCount; ?> pay stubs</small>
                    </div>
                    <div class="button-panel">
                        <a href="<?= base_url('payrolls/pay-stubs'); ?>" class="site-btn">View Pay stubs</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>