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
            <h2>Off-cycle payroll - select employees</h2>
            <p>Select who will be on this payroll. You can only choose from employees at your company.</p>

            <div class="hr-document-list">
                <table class="hr-doc-list-table">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col" style="vertical-align: middle; width:8%">
                                <label class="control control--checkbox">
                                    <input type="checkbox" id="jsSelectAll" />
                                    <div class="control__indicator" style="top: -7px;"></div>
                                </label>
                            </th>
                            <th scope="col">Employee Name</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr data-id="11">
                            <td style="vertical-align: middle;">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsSelectSingle" />
                                    <div class="control__indicator" style="top: -7px;"></div>
                                </label>
                            </td>
                            <td>Wittgenstein, Ludwig</td>
                        </tr>
                        <tr data-id="11">
                            <td style="vertical-align: middle;">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsSelectSingle" />
                                    <div class="control__indicator" style="top: -7px;"></div>
                                </label>
                            </td>
                            <td>Arendt, Hannah</td>
                        </tr>
                        <tr data-id="11">
                            <td style="vertical-align: middle;">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsSelectSingle" />
                                    <div class="control__indicator" style="top: -7px;"></div>
                                </label>
                            </td>
                            <td>Berlin, Isaiah</td>
                        </tr>
                        <tr data-id="11">
                            <td style="vertical-align: middle;">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsSelectSingle" />
                                    <div class="control__indicator" style="top: -7px;"></div>
                                </label>
                            </td>
                            <td>Churchland, Patricia</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <span class="pull-right">
                <button class="btn btn-success js-action-btn" data-step="step_0">Cancel</button>
                <button class="btn btn-success js-action-btn" data-step="step_2">Continue</button>
            </span>
        </div>
    </div>
</div>