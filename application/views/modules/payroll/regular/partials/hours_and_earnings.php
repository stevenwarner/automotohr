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
                            <h1 class="panel-heading-text text-large">
                                <strong>Regular Payroll</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="csF16">
                                        <strong>Pay period: </strong>
                                        <?= formatDateToDB($regularPayroll['start_date'], DB_DATE, DATE); ?> -
                                        <?= formatDateToDB($regularPayroll['end_date'], DB_DATE, DATE); ?>
                                    </p>
                                </div>
                            </div>
                            <br />
                            <br />
                            <!-- Steps -->
                            <div class="row">
                                <!-- 1 -->
                                <div class="col-sm-6 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        <strong>1. Hours and earnings</strong>
                                    </p>
                                </div>

                                <!-- 3 -->
                                <div class="col-sm-6 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        2. Review and submit
                                    </p>
                                </div>
                            </div>
                            <br />
                            <!-- Text -->
                            <h1 class="text-large">
                                <strong>
                                    Hours and additional earnings
                                </strong>
                            </h1>
                            <p class="text-small">Update your employees' hours, reimbursements, and additional earnings below.</p>
                            <p class="text-small">To pay your employees with direct deposit on <strong><?= formatDateToDB($regularPayroll['check_date'], DB_DATE, DATE); ?></strong>, you'll need to run payroll by <strong>4:00pm PDT</strong> on <strong><?= formatDateToDB($regularPayroll['payroll_deadline'], 'Y-m-d\TH:i:sZ', DATE); ?></strong>. If you miss this deadline, your employees' direct deposit will be delayed.</p>

                            <hr />
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col" class="">
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" name="jsSelectAll" class="jsSelectAll" value="all" />
                                                    <div class="control__indicator" style="margin-top: -11px;"></div>
                                                </label>
                                            </th>
                                            <th scope="col" class="">
                                                Employees
                                            </th>
                                            <th scope="col" class="">
                                                Regular Hours (RH)<br />
                                                Overtime (OT/DOT)
                                            </th>
                                            <th scope="col" class="">
                                                Additional<br />
                                                Earnings
                                            </th>
                                            <th scope="col" class="">
                                                Gross Pay (GP)<br />
                                                Reimbursement (R)<br />
                                                Payment Method
                                            </th>
                                            <th scope="col" class="">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>

                        </div>
                        <div class="panel-footer hidden jsRegularPayrollPanel text-right"></div>
                    </div>
                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                </div>
            </div>
        </div>
    </div>
</div>