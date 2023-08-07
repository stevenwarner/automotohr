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
            <h2>Time off</h2>
            <p>With your time off policies set up, you can track time off for this pay period below. <br>
                You have no company holidays set up at the moment, but it’s easy to create a paid holiday policy in Time Off.
            </p>
            <div class="hr-document-list">
                <table class="hr-doc-list-table">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">Employees</th>
                            <th scope="col">Paid Time Off Hours (PTO)</th>
                            <th scope="col">Sick Hours (S)</th>
                            <th scope="col">Additional Time Off</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr data-id="11">
                            <td>Churchland, Patricia</td>
                            <td>
                                <div>
                                    <div><label class="sr-only">Hours used</label></div>
                                    <div class="gwb_2816k" style="--beforeWidth: 0px; --afterWidth: 17px;"><input placeholder="0" inputmode="numeric" id="" class="gwb_jsuEB gwb_23CWS" value="">
                                        <div class="gwb_2SlKx gwb_HebOV">hr</div>
                                        <div aria-hidden="true" class="gwb_18B7c"></div>
                                    </div>
                                </div>

                                <div>76 hrs remaining</div>
                            </td>
                            <td>
                                <div>
                                    <div><label class="sr-only">Hours used</label></div>
                                    <div class="gwb_2816k" style="--beforeWidth: 0px; --afterWidth: 17px;"><input placeholder="0" inputmode="numeric" id="" class="gwb_jsuEB gwb_23CWS" value="">
                                        <div class="gwb_2SlKx gwb_HebOV">hr</div>
                                        <div aria-hidden="true" class="gwb_18B7c"></div>
                                    </div>
                                </div>
                                <div>76 hrs remaining</div>
                            </td>
                            <td>
                                <div class="additional-time-off-column">
                                    <div class="cell">0 hrs</div>
                                    <div class="cell"><a class="text-success" href="">View details</a></div>
                                </div>
                            </td>
                        </tr>

                        <tr data-id="11">
                            <td>Churchland, Patricia</td>
                            <td>
                                <div>
                                    <div><label class="sr-only">Hours used</label></div>
                                    <div class="gwb_2816k" style="--beforeWidth: 0px; --afterWidth: 17px;"><input placeholder="0" inputmode="numeric" id="" class="gwb_jsuEB gwb_23CWS" value="">
                                        <div class="gwb_2SlKx gwb_HebOV">hr</div>
                                        <div aria-hidden="true" class="gwb_18B7c"></div>
                                    </div>
                                </div>
                                <div>76 hrs remaining</div>
                            </td>
                            <td>
                                <div>
                                    <div><label class="sr-only">Hours used</label></div>
                                    <div class="gwb_2816k" style="--beforeWidth: 0px; --afterWidth: 17px;"><input placeholder="0" inputmode="numeric" id="" class="gwb_jsuEB gwb_23CWS" value="">
                                        <div class="gwb_2SlKx gwb_HebOV">hr</div>
                                        <div aria-hidden="true" class="gwb_18B7c"></div>
                                    </div>
                                </div>
                                <div>76 hrs remaining</div>
                            </td>
                            <td>
                                <div class="additional-time-off-column">
                                    <div class="cell">0 hrs</div>
                                    <div class="cell"><a class="text-success" href="">View details</a></div>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <span class="pull-right">
                <button class="btn btn-success js-action-btn" data-step="step_1">Go Back</button>
                <button class="btn btn-success js-action-btn" data-step="step_3">Save & Continue</button>
            </span>
        </div>
    </div>
</div>