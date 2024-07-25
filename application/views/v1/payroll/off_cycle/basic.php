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
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1 class="csF16 csW" style="margin: 0">
                                <strong>Basics</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!-- Steps -->
                            <div class="row">
                                <!-- 1 -->
                                <div class="col-sm-3 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        <strong>1. Basics</strong>
                                    </p>
                                </div>
                                <!-- 1 -->
                                <div class="col-sm-3 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        2. Hours and earnings
                                    </p>
                                </div>

                                <!-- 2 -->
                                <div class="col-sm-3 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        3. Time off
                                    </p>
                                </div>

                                <!-- 3 -->
                                <div class="col-sm-3 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        4. Review and submit
                                    </p>
                                </div>
                            </div>

                            <br>
                            <br>
                            <!--  -->
                            <form action="">

                                <div class="form-group <?=$reason != 'off-cycle' ? 'hidden' : '';?>">
                                    <label class="csF16">
                                        Choose the type of off-cycle payroll
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <p class="csF12 text-danger">
                                        <strong>
                                            <em>
                                                Enter a work period to show on your employees' pay stubs.
                                            </em>
                                        </strong>
                                    </p>

                                    <label class="control control--radio">
                                        <input type="radio" name="payroll[off_cycle_reason]" class="" <?=$reason == 'corrections' ? 'checked' : '';?> value="Correction" />
                                        Correction
                                        <p class="csF12" style="font-weight: normal">
                                            Run a payroll outside of your regular pay schedule
                                        </p>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <br />
                                    <label class="control control--radio">
                                        <input type="radio" name="payroll[off_cycle_reason]" class="" <?=$reason == 'bonus' ? 'checked' : '';?> value="Bonus" />
                                        Bonus
                                        <p class="csF12" style="font-weight: normal">
                                            Reward a team member with a bonus, gift, or commission
                                        </p>
                                        <div class="control__indicator"></div>
                                    </label>

                                </div>

                                <div class="form-group">
                                    <label class="csF16">
                                        Work period
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <p class="csF12 text-danger">
                                        <strong>
                                            <em>
                                                Enter a work period to show on your employees' pay stubs.
                                            </em>
                                        </strong>
                                    </p>

                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar csF16" aria-hidden="true"></i>
                                                </div>
                                                <input type="text" name="payroll[start_date]" class="form-control jsDatePicker" placeholder="MM/DD/YYYY" readonly />
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar csF16" aria-hidden="true"></i>
                                                </div>
                                                <input type="text" name="payroll[end_date]" class="form-control jsDatePicker" placeholder="MM/DD/YYYY" readonly />
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="csF16">
                                        Payment date
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <p class="csF12 text-danger">
                                        <strong>
                                            <em>
                                                Enter the date you'd like your employees to receive payment.
                                            </em>
                                        </strong>
                                    </p>

                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar csF16" aria-hidden="true"></i>
                                                </div>
                                                <input type="text" name="payroll[check_date]" class="form-control jsDatePicker" placeholder=" MM/DD/YYYY" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="form-group">
                                    <h1 class="csF16">
                                        <strong>
                                            Deductions and contributions
                                        </strong>
                                    </h1>
                                    <p class="csF12">
                                        Employers often block deductions and contributions from certain checks (i.e. bonus checks), since they are calculated on a per-pay-period basis. Deductions and contributions include loan repayments, garnishments, benefits, etc. Taxes will be included regardless of what is chosen.
                                    </p>

                                    <label class="control control--radio">
                                        <input type="radio" name="payroll[skip_regular_deductions]" class="" value="false" />
                                        Make all the regular deductions and contributions.
                                        <div class="control__indicator"></div>
                                    </label>
                                    <br />
                                    <label class="control control--radio">
                                        <input type="radio" name="payroll[skip_regular_deductions]" class="" value="true" />
                                        Block all deductions and contributions, except 401(k). Taxes will be included.
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>

                                <hr />
                                <div class="form-group">
                                    <h1 class="csF16">
                                        <strong>
                                            Tax withholding rates
                                        </strong>
                                        <span class="pull-right">
                                            <button type="button" class="btn btn-warning csF16 jsToggleTaxView">
                                                <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                                &nbsp;Edit
                                            </button>
                                        </span>
                                    </h1>
                                    <br>
                                    <!--  -->
                                    <div class="panel panel-success jsTaxView hidden">
                                        <div class="panel-body">
                                            <h1 class="csF16">
                                                <strong>
                                                    Rate for regular wages and earnings
                                                </strong>
                                            </h1>
                                            <p class="csF12">
                                                Wage types: regular hours, regular wages, tips
                                            </p>
                                            <p class="csF12">
                                                Regular wages should be taxed at a rate matching your employees' regular pay schedule. Select the most accurate pay schedule below.
                                            </p>
                                            <!-- < -->
                                            <label class="csF16">
                                                Rate is based on payment schedule of
                                            </label>
                                            <select class="form-control" name="payroll[withholding_pay_period]">
                                                <option <?= $schedule == 'Every week' ? 'selected' : ''; ?> value="Every week">Every week</option>
                                                <option <?= $schedule == 'Every other week' ? 'selected' : ''; ?> value="Every other week">Every other week</option>
                                                <option <?= $schedule == 'Twice per month' ? 'selected' : ''; ?> value="Twice per month">Twice per month</option>
                                                <option <?= $schedule == 'Monthly' ? 'selected' : ''; ?> value="Monthly">Monthly</option>
                                                <option <?= $schedule == 'Quarterly' ? 'selected' : ''; ?> value="Quarterly">Quarterly</option>
                                                <option <?= $schedule == 'Semiannually' ? 'selected' : ''; ?> value="Semiannually">Semiannually</option>
                                                <option <?= $schedule == 'Annually' ? 'selected' : ''; ?> value="Annually">Annually</option>
                                            </select>

                                            <br />
                                            <h1 class="csF16">
                                                <strong>
                                                    Rate for regular wages and earnings
                                                </strong>
                                            </h1>
                                            <p class="csF12">
                                                We've set the recommended withholding rate for each earning type below. Regular wages should be taxed at a rate that matches your employees' normal pay schedule
                                            </p>
                                            <label class="control control--radio">
                                                <input type="radio" name="payroll[fixed_withholding_rate]" value="true" /> Use supplemental rate
                                                <p class="csF12" style="font-weight: normal">Taxes will be withheld at the IRS's required rate of 22% for federal income taxes. State income taxes will be taxed at the state's supplemental tax rate
                                                </p>
                                                <div class="control__indicator"></div>
                                            </label>
                                            <br>
                                            <label class="control control--radio">
                                                <input type="radio" name="payroll[fixed_withholding_rate]" value="false" checked /> Use rate for regular wages (based on payment schedule of <span class="jsShowSchedule"></span> )
                                                <p class="csF12" style="font-weight: normal">We'll sum the entirety of the employee's wages and withhold taxes on the entire amount at the rate for regular wages
                                                </p>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <caption></caption>
                                            <thead>
                                                <tr>
                                                    <th class="csW csBG4" scope="col">Wage types</th>
                                                    <th class="csW csBG4" scope="col">Taxed as</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="vam">
                                                        Regular hours, regular wages, tips
                                                    </td>
                                                    <td class="vam">
                                                        Regular wages, paid <span class="jsShowSchedule"></span>
                                                        &nbsp;
                                                        <i class="fa fa-info-circle" aria-hidden="true" title="These earnings should be taxed at a rate that matches your employees' regular pay schedule (e.g. bi-weekly, monthly, etc)." placement="top">
                                                        </i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="vam">
                                                        Supplemental wages, bonus wages, commission
                                                    </td>
                                                    <td class="vam">
                                                        <p class="jsSelectNonSupplementBox">
                                                            Regular wages, paid <span class="jsShowSchedule"></span>
                                                        </p>
                                                        <p class="jsSelectSupplementBox hidden">
                                                            Supplemental 22% for federal income taxes and at the state's supplemental tax rate for state income taxes.
                                                        </p>
                                                        &nbsp;
                                                        <i class="fa fa-info-circle" aria-hidden="true" title="Supplemental earnings are typically taxed at the rate required by the IRS for federal income taxes and by the state for state income taxes.You can, however, choose to add together supplemental and regular wages, and withhold taxes on the entire amount at the rate for regular wages." placement="top">
                                                        </i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="vam">
                                                        Reimbursements
                                                    </td>
                                                    <td class="vam">
                                                        Not taxed
                                                        &nbsp;
                                                        <i class="fa fa-info-circle csF16" aria-hidden="true" title="Reimbursements are typically untaxed, so we won't withhold any taxes from these wages." placement="top"></i>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <h1 class="csF16">
                                        <strong>
                                            Employees
                                        </strong>
                                    </h1>
                                    <!-- Employees -->
                                    <?php if ($payrollEmployees) { ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <caption></caption>
                                                <thead>
                                                    <tr>
                                                        <th class="csW csBG4 col-sm-1" scope="col">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" class="jsSelectAll" />
                                                                <div class="control__indicator" style="margin-top: -11px;"></div>
                                                            </label>
                                                        </th>
                                                        <th class="csW csBG4" scope="col">
                                                            Employee name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($payrollEmployees as $value) { ?>
                                                        <tr>
                                                            <td class="vam">
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" class="jsSelectSingle" name="payroll[employees]" value="<?= $value['id']; ?>" />
                                                                    <div class="control__indicator" style="margin-top: -11px;"></div>
                                                                </label>
                                                            </td>
                                                            <td class="vam">
                                                                <?= $value['name']; ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else { ?>
                                        <?php $this->load->view('v1/no_data', ['message' => 'No employees found.']); ?>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                        <div class="panel-footer text-right">
                            <!-- cancel -->
                            <a href="<?= base_url('payroll/dashboard'); ?>" class="btn csW csBG4 csF16">
                                <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                                &nbsp;Cancel
                            </a>
                            <?php if ($payrollEmployees) { ?>
                                <button class="btn csW csBG3 csF16 jsOffCycleSave">
                                    <i class="fa fa-save csF16" aria-hidden="true"></i>
                                    &nbsp;Continue
                                </button>
                            <?php } ?>
                        </div>
                        <!--  -->
                        <?php $this->load->view(
                            'v1/loader',
                            [
                                'id' => 'jsPageLoader'
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>