<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF18 csB9">
                    Run Payroll
                </h1>
                <hr>
            </div>
        </div>
        <!-- Steps -->
        <div class="row">
            <div class="col-sm-4">
                <div class="progress mb0">
                    <div class="progress-bar csBG1" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                        aria-valuemax="100" style="width:100%">
                    </div>
                </div>
                <p class="csF14 csB7" style="margin-top: 5px;">1. Hours and earnings</p>
            </div>

            <div class="col-sm-4">
                <div class="progress mb0">
                    <div class="progress-bar csBG1" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100" style="width:0%">
                    </div>
                </div>
                <p class="csF14 csB7" style="margin-top: 5px;">2. Review and submit</p>
            </div>

            <div class="col-sm-4">
                <div class="progress mb0">
                    <div class="progress-bar csBG1" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100" style="width:0%">
                    </div>
                </div>
                <p class="csF14 csB7" style="margin-top: 5px;">3. Confirmation</p>
            </div>
        </div>
        <!-- Tabs -->
        <div class="jsPageTabContainer">
            <!-- Hours and earnings -->
            <div class="jsPageTab" data-page="hours">
                <!-- Info -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF16 csB7">
                            Hours and additional earnings
                        </h1>
                        <p class="csF16">
                            Update your employee's here, reimbursements, and additional settings below.
                        </p>
                        <p class="csF16">
                            To pay your employees with direct deposit on <span class="csFC1">Fri Sept, 5</span>, you'll need to run payroll by <span class="csFC1">04:00pm PST on Fri Sept, 1</span>. If you miss this deadline. your employees' direct deposit will be delayed.
                        </p>
                    </div>
                </div>
                <!-- filter -->
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="jsSelectAll" />
                                            <div class="control__indicator" style="margin-top: -12px"></div>
                                        </label>
                                    </th>
                                    <th scope="col">
                                        Employees (2)
                                    </th>
                                    <th scope="col">
                                        Regular Hours (RH)
                                    </th>
                                    <th scope="col">
                                        Additional Earnings
                                    </th>
                                    <th scope="col">
                                        Gross Pay (GP)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="jsSelectSingle" value="44" />
                                            <div class="control__indicator"  style="margin-top: -12px"></div>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="csF16">
                                            John Doe (QA) [Admin]
                                        </p>
                                    </td>
                                    <td>
                                        <p class="csF16">
                                            <i class="fa fa edit" aria-hidden="true"></i>&nbsp;80.00 hrs
                                        </p>
                                    </td>
                                    <td>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Bonus
                                        </a> <br>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Overtime
                                        </a> <br>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Commission
                                        </a> <br>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Cash Tips
                                        </a> <br>
                                    </td>
                                    <td>
                                        <p class="csF16">
                                            $2,346.15
                                        </p>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Reimbursement
                                        </a> <br>
                                        <p class="csF16">
                                            Pay By: Direct Deposit
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="jsSelectSingle" value="44" />
                                            <div class="control__indicator"  style="margin-top: -12px"></div>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="csF16">
                                            Sarah Jonas (HR) [Manager]
                                        </p>
                                    </td>
                                    <td>
                                        <!--  -->
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="40.00" />
                                            <span class="input-group-addon">RH</span>
                                        </div>
                                        <button class="btn btn-success">Update</button>
                                    </td>
                                    <td>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Bonus
                                        </a> <br>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Overtime
                                        </a> <br>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Commission
                                        </a> <br>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Cash Tips
                                        </a> <br>
                                    </td>
                                    <td>
                                        <p class="csF16">
                                            $2,346.15
                                        </p>
                                        <a class="csF16 csFC1 csCP">
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Reimbursement
                                        </a> <br> <br>
                                        <p class="csF16">
                                            Pay By: Direct Deposit
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>