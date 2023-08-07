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
            <h2>You’re all set</h2>
            <p>This payroll successfully recorded and no bank withdrawals were required</p>
        </div>
        <div class="col-sm-12">
            <span class="pull-left">
                <button class="btn btn-success js-action-btn" data-step="step_0">Back To Dashboard</button>
                <button class="btn btn-success js-action-btn" data-step="step_3">Full Summary</button>
            </span>
        </div>
        <div class="col-sm-12">
            <span class="pull-left">
                Need to cancel payroll? It’s easy to re-run if needed. <a href="#" class="text-danger"><strong>Cancel This Payroll</strong></a>
            </span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div class="accordion-colored-header">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#driver_license" style="color:#FFF"><span class="glyphicon glyphicon-plus"></span>Employee hours and earnings <strong data-id="drivers_license" class="jsGeneralAssignDocument"></strong></a>
                            </h4>
                        </div>
                        <div id="driver_license" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="jsNoteArea"></div>
                                <div class="form-wrp">
                                    <table class="hr-doc-list-table">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col">Employees</th>
                                                <th scope="col">Compensation Type</th>
                                                <th scope="col">Regular</th>
                                                <th scope="col">Overtime (1.5x)</th>
                                                <th scope="col">Overtime (2x)</th>
                                                <th scope="col">Paid Time Off</th>
                                                <th scope="col">Emergency Leave</th>
                                                <th scope="col">Total Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr data-id="11">
                                                <td></td>
                                                <td> </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td> </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div>
                                        <a href="#" class="text-success pull-right"><strong>Download CSV</strong></a>
                                    </div>
                                    <div>
                                        <table class="hr-doc-list-table">
                                            <caption></caption>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Employees</th>
                                                    <th scope="col">Payment Type</th>
                                                    <th scope="col">Gross Pay</th>
                                                    <th scope="col">Deductions</th>
                                                    <th scope="col">Reimbursements</th>
                                                    <th scope="col">Employee Taxes</th>
                                                    <th scope="col">Employee Benefits</th>
                                                    <th scope="col">Payment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Arendt, Hannah</td>
                                                    <td>Direct</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Arendt, Hannah</td>
                                                    <td>Direct</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Arendt, Hannah</td>
                                                    <td>Direct</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Arendt, Hannah</td>
                                                    <td>Direct</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                    <td>$0.00</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>