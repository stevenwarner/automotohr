<?php
$filter_state = $this->uri->segment(3) != '' ? true : false;
?>
<style>
    .first_name_td {
        display: none;
    }

    .middle_name_td {
        display: none;
    }

    .last_name_td {
        display: none;
    }

    .job_title_td {
        display: none;
    }

    .department_td {
        display: none;
    }

    .team_td {
        display: none;
    }

    .ssn_td {
        display: none;
    }

    .employee_number_td {
        display: none;
    }

    .email_td {
        display: none;
    }

    .phone_number_td {
        display: none;
    }

    .employee_id_td {
        display: none;
    }

    .extra_td {
        display: none;
    }
</style>
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
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back
                                    </a>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="panel-group-wrp">
                                            <div class="panel-group" id="accordion">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                            <h4 class="panel-title" style="padding-left: 12px;">
                                                                Advanced Search Filters <span class="glyphicon glyphicon-plus"></span>
                                                            </h4>
                                                        </a>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse collapse <?php if (isset($filter_state) && $filter_state == true) {
                                                                                                                echo 'in';
                                                                                                            } ?>">
                                                        <form method="get" enctype="multipart/form-data">
                                                            <div class="panel-body">

                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <label>Employees</label>
                                                                        <select id="js-filter-employee" class="js-filter-employee" multiple="multiple">
                                                                            <option value="all">All</option>
                                                                            <?php foreach ($allemployees as $empRow) { ?>
                                                                                <option value="<?php echo $empRow['userId']; ?>" <?= in_array($empRow["userId"], $filter_employees) ? "selected" : ""; ?>>
                                                                                    <?= remakeEmployeeName($empRow); ?>
                                                                                </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <label>Departments / Teams</label>
                                                                        <br>
                                                                        <?= get_company_departments_teams_dropdown($company_sid, 'teamId', $filter_team ?? 0); ?>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <label>Job Title</label>
                                                                        <br>
                                                                        <?= get_jobTitle_dropdown_for_search($company_sid, 'jobtitleId') ?>
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label><strong>Search by</strong></label> <br>

                                                                        <label class="control control--radio">
                                                                            Transaction Date &nbsp;&nbsp;
                                                                            <input type="radio" name="dateselection" class="assignAndSendDocument" value="transaction" <?php echo $this->uri->segment(8) == 'transaction' || $this->uri->segment(8) == '' ? 'checked' : ''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio">
                                                                            Period Date &nbsp;&nbsp;
                                                                            <input type="radio" name="dateselection" class="assignAndSendDocument" value="period" <?php echo $this->uri->segment(8) == 'period' ? 'checked' : ''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div><br>
                                                                    <br><br>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label class="">Start Period</label>
                                                                        <?php $start_date = $this->uri->segment(3) != 'all' && $this->uri->segment(3) != '' ? urldecode($this->uri->segment(3)) : date('m-1-Y'); ?>
                                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label class="">End Period</label>
                                                                        <?php $end_date = $this->uri->segment(4) != 'all' && $this->uri->segment(4) != '' ? urldecode($this->uri->segment(4)) : date('m-t-Y'); ?>
                                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                        <div class="field-row autoheight text-right">

                                                                            <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                                            <a id="btn_reset_filters" class="btn btn-success" href="<?php echo base_url('payrolls/ledger'); ?>">Reset Filters</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bottom here-->


                                        <?php
                                        $header = [];
                                        foreach ($employeesLedger as $key1 => $row) {
                                            foreach (json_decode($row['extra'], true)[0] as $key2 => $value) {
                                                if (!in_array($key2, $header)) {
                                                    $header[] = $key2;
                                                }
                                                //
                                                $employeesLedger[$key1]['extra_1'][$key2] = $value;
                                            }
                                        }
                                        ?>

                                        <?php if (isset($employeesLedger) && sizeof($employeesLedger) > 0) { ?>
                                            <div class="box-view reports-filtering">
                                                <form method="post" id="export" name="export">
                                                    <div class="panel panel-default cs_margin_panel">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class="col-lg-9 col-xs-10 ">
                                                                    <label class="control control--checkbox">
                                                                        <input type="checkbox" name="" id="check_all" value="">
                                                                        <div class="control__indicator" style="background: #fff;"></div>
                                                                    </label>
                                                                    <p class="cs_line" style="padding-left:35px;margin-top: -12px;"><strong>Include columns in export file</strong></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="collapse1" class="panel-collapse ">
                                                            <div class="panel-body" style="min-height:100px;">
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;"> Employee Id <input type="checkbox" class="check_it" name="employee_sid" value="employeeId" id="employee_id">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">First Name<input type="checkbox" class="check_it" name="first_name" value="firstname" id="first_name">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Last Name<input type="checkbox" class="check_it" name="last_name" value="lastname" id="last_name">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Middle Name<input type="checkbox" class="check_it" name="middle_name" value="middlename" id="middle_name">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Employee Number<input type="checkbox" class="check_it" name="employee_number" value="employee_number" id="employee_number">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Job Title<input type="checkbox" class="check_it" name="job_title" value="jobtitle" id="job_title">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Email<input type="checkbox" class="check_it" name="email" value="email" id="email">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Description<input type="checkbox" class="check_it" name="description" value="description" id="description" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Imported At<input type="checkbox" class="check_it" name="imported_at" value="imported_at" id="imported_at" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Transaction Date<input type="checkbox" class="check_it" name="transaction_date	" value="transaction_date" id="transaction_date" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>



                                                                </div>



                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Department<input type="checkbox" class="check_it" name="department" value="department" id="department">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Team<input type="checkbox" class="check_it" name="team" value="team" id="team">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">SSN<input type="checkbox" class="check_it" name="ssn" value="ssn" id="ssn">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox showhidecol" style="padding-left:35px;">General Entry Number<input type="checkbox" class="check_it" name="general_entry_number" value="general_entry_number" id="general_entry_number" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Reference Number<input type="checkbox" class="check_it" name="reference_number" value="reference_number" id="reference_number" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Gross Pay<input type="checkbox" class="check_it" name="gross_pay" value="gross_pay" id="gross_pay" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Net Pay<input type="checkbox" class="check_it" name="net_pay" value="net_pay" id="net_pay" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Taxes<input type="checkbox" class="check_it" name="taxes" value="taxes" id="taxes" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Account Number<input type="checkbox" class="check_it" name="account_number" value="account_number" id="account_number" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Account Name<input type="checkbox" class="check_it" name="account_name" value="account_name" id="account_name" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Start Period<input type="checkbox" class="check_it" name="start_period" value="start_period" id="start_period" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">End Period<input type="checkbox" class="check_it" name="end_period" value="end_period" id="end_period" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Extra<input type="checkbox" class="check_it" name="extra" value="extra" id="extra" >
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Debit Amount<input type="checkbox" class="check_it" name="debit_amount" value="debit_amount" id="debit_amount" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Credit Amount<input type="checkbox" class="check_it" name="credit_amount" value="credit_amount" id="credit_amount" checked>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">Phone Number<input type="checkbox" class="check_it" name="phone_number" value="phone_number" id="phone_number">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <input type="submit" name="submit" class="submit-btn pull-right" value="Export">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php } ?>
                                        <!-- table -->
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="pull-left">
                                                    <h1 class="hr-registered">Ledger</h1>
                                                </span>
                                                <span class="pull-right">
                                                    <h1 class="hr-registered">Total Records Found : <?php echo $ledgerCount; ?></h1>
                                                </span>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <span class="pull-left">
                                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $ledgerCount ?></p>
                                                        </span>
                                                        <span class="pull-right" style="margin-top: 20px; margin-bottom: 20px;">
                                                            <?php echo $page_links ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="table-responsive" id="print_div">
                                                            <table class="table table-bordered horizontal-scroll" id="example">
                                                                <thead>


                                                                    <tr>
                                                                        <th class="employee_id_td">Employee Id</th>
                                                                        <th>Employee/Company</th>
                                                                        <th class="first_name_td">First Name</th>
                                                                        <th class="middle_name_td">Middle Name</th>
                                                                        <th class="last_name_td">Last Name</th>
                                                                        <th class="job_title_td">Job Title</th>
                                                                        <th class="department_td">Department</th>
                                                                        <th class="team_td">Team</th>
                                                                        <th class="employee_number_td">Employee Number</th>
                                                                        <th class="ssn_td">SSN</th>
                                                                        <th class="email_td">Email</th>
                                                                        <th class="phone_number_td">Phone Number</th>
                                                                        <th class="debit_amount_td">Debit Amount</th>
                                                                        <th class="credit_amount_td">Credit Amount</th>
                                                                        <th class="gross_pay_td">Gross Pay</th>
                                                                        <th class="net_pay_td">Net Pay</th>
                                                                        <th class="taxes_td">Taxes</th>
                                                                        <th class="description_td">Description</th>
                                                                        <th class="transaction_date_td">Transaction Date</th>
                                                                        <th class="start_period_td">Start Period</th>
                                                                        <th class="end_period_td">End Period</th>
                                                                        <th class="imported_at_td">Imported At</th>
                                                                        <th class="account_name_td">Account Name</th>
                                                                        <th class="account_number_td">Account Number</th>
                                                                        <th class="reference_number_td">Reference Number</th>
                                                                        <th class="general_entry_number_td">General Entry Number</th>

                                                                        <?php if (!empty($header)) {
                                                                            foreach ($header as $hdRow) {
                                                                        ?>
                                                                                <th class="extra_td"><?php echo $hdRow ?></th>
                                                                        <?php }
                                                                        } ?>


                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (isset($employeesLedger) && sizeof($employeesLedger) > 0) {

                                                                        $debitAmountTotal = 0;
                                                                        $creditAmountTotal = 0;
                                                                        $grossPayTotal = 0;
                                                                        $netPayTotal = 0;
                                                                        $taxesTotal = 0;
                                                                    ?>
                                                                        <?php foreach ($employeesLedger as $rowEmployee) {

                                                                        ?>

                                                                            <tr>
                                                                                <td class="employee_id_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['sid'];
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php

                                                                                    if ($rowEmployee['employee_sid'] !== null) {
                                                                                        echo remakeEmployeeName([
                                                                                            'first_name' => $rowEmployee['first_name'],
                                                                                            'last_name' => $rowEmployee['last_name'],
                                                                                            'access_level' => $rowEmployee['access_level'],
                                                                                            'timezone' => isset($rowEmployee['timezone']) ? $rowEmployee['timezone'] : '',
                                                                                            'access_level_plus' => $rowEmployee['access_level_plus'],
                                                                                            'is_executive_admin' => $rowEmployee['is_executive_admin'],
                                                                                            'pay_plan_flag' => $rowEmployee['pay_plan_flag'],
                                                                                            'job_title' => $rowEmployee['job_title'],
                                                                                        ]);
                                                                                    } else {
                                                                                        echo getCompanyNameBySid($rowEmployee['company_sid']);
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="first_name_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['first_name'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="middle_name_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['middle_name'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="last_name_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['last_name'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="job_title_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['job_title'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="department_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['department'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="team_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['team'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="employee_number_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['employee_number'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="ssn_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['ssn'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="email_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['email'];
                                                                                    ?>
                                                                                </td>

                                                                                <td class="phone_number_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['PhoneNumber'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-success debit_amount_td">
                                                                                    <?php
                                                                                    $debitAmountTotal = $debitAmountTotal + $rowEmployee['debit_amount'];
                                                                                    echo $rowEmployee['debit_amount'] != '' ? _a($rowEmployee['debit_amount']) : '-';
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-danger credit_amount_td">
                                                                                    <?php
                                                                                    $creditAmountTotal = $creditAmountTotal + $rowEmployee['credit_amount'];
                                                                                    echo  $rowEmployee['credit_amount'] != '' ? _a($rowEmployee['credit_amount']) : '-';
                                                                                    ?>
                                                                                </td>
                                                                                <td class="gross_pay_td">
                                                                                    <?php
                                                                                    $grossPayTotal = $grossPayTotal + $rowEmployee['gross_pay'];
                                                                                    echo  $rowEmployee['gross_pay'] != '' ? _a($rowEmployee['gross_pay']) : '-'; ?>
                                                                                </td>
                                                                                <td class="net_pay_td">
                                                                                    <?php
                                                                                    $netPayTotal = $netPayTotal + $rowEmployee['net_pay'];
                                                                                    echo  $rowEmployee['net_pay'] != '' ? _a($rowEmployee['net_pay']) : '-'; ?>
                                                                                </td>
                                                                                <td class="taxes_td">
                                                                                    <?php
                                                                                    $taxesTotal = $taxesTotal + $rowEmployee['taxes'];
                                                                                    echo  $rowEmployee['taxes'] != '' ? _a($rowEmployee['taxes']) : '-'; ?>
                                                                                </td>
                                                                                <td class="description_td">
                                                                                    <?php echo $rowEmployee['description']; ?>
                                                                                </td>
                                                                                <td class="transaction_date_td">
                                                                                    <?php
                                                                                    echo formatDateToDB($rowEmployee['transaction_date'], DB_DATE, DATE);
                                                                                    ?>
                                                                                </td>
                                                                                <td class="start_period_td">
                                                                                    <?php
                                                                                    echo formatDateToDB($rowEmployee['start_date'], DB_DATE, DATE);
                                                                                    ?>
                                                                                </td>
                                                                                <td class="end_period_td">
                                                                                    <?php
                                                                                    echo formatDateToDB($rowEmployee['end_date'], DB_DATE, DATE);
                                                                                    ?>
                                                                                </td>
                                                                                <td class="imported_at_td">
                                                                                    <?php
                                                                                    echo formatDateToDB($rowEmployee['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                    ?>
                                                                                </td>
                                                                                <td class="account_name_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['account_name'];
                                                                                    ?>
                                                                                </td>

                                                                                <td class="account_number_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['account_number'];
                                                                                    ?>
                                                                                </td>

                                                                                <td class="reference_number_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['reference_number'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="general_entry_number_td">
                                                                                    <?php
                                                                                    echo $rowEmployee['general_entry_number'];
                                                                                    ?>
                                                                                </td>

                                                                                <?php
                                                                                if (!empty($header)) {
                                                                                    if (!empty($rowEmployee['extra_1'])) {
                                                                                        foreach ($rowEmployee['extra_1'] as  $key => $val) {
                                                                                ?>
                                                                                            <td class="extra_td">
                                                                                                <?php echo $val; ?>
                                                                                            </td>
                                                                                        <?php
                                                                                        }
                                                                                    }

                                                                                    if (empty(json_decode($rowEmployee['extra']))) {

                                                                                        foreach ($header as $row) {
                                                                                        ?>
                                                                                            <td class="extra_td">
                                                                                            </td>
                                                                                <?php }
                                                                                    }
                                                                                }

                                                                                ?>
                                                                            </tr>
                                                                        <?php } ?>

                                                                        <tr class="bg-header-green">
                                                                            <td>
                                                                                Grand Total:
                                                                            </td>
                                                                            <td class="employ_id_td">
                                                                            </td>
                                                                            <td class="first_name_td">
                                                                            </td>
                                                                            <td class="middle_name_td">
                                                                            </td>
                                                                            <td class="last_name_td">
                                                                            </td>
                                                                            <td class="job_title_td">
                                                                            </td>
                                                                            <td class="department_td">
                                                                            </td>
                                                                            <td class="team_td">
                                                                            </td>
                                                                            <td class="employee_number_td">
                                                                            </td>
                                                                            <td class="ssn_td">
                                                                            </td>
                                                                            <td class="email_td">
                                                                            </td>
                                                                            <td class="phone_number_td">
                                                                            </td>
                                                                            <td class="text-success debit_amount_td">
                                                                                <?php echo $debitAmountTotal > 0 ? _a($debitAmountTotal) : '0'; ?>
                                                                            </td>
                                                                            <td class="text-danger credit_amount_td">
                                                                                <?php echo  $creditAmountTotal > 0 ? _a($creditAmountTotal) : '0'; ?>
                                                                            </td>
                                                                            <td class="gross_pay_td">
                                                                                <?php echo  $grossPayTotal > 0 ? _a($grossPayTotal) : '0'; ?>
                                                                            </td>
                                                                            <td class="net_pay_td">
                                                                                <?php echo  $netPayTotal > 0 ? _a($netPayTotal) : '0'; ?>
                                                                            </td>
                                                                            <td class="taxes_td">
                                                                                <?php echo  $taxesTotal > 0 ? _a($taxesTotal) : '0'; ?>
                                                                            </td>
                                                                            <td class="description_td"></td>
                                                                            <td class="transaction_date_td"> </td>
                                                                            <td class="start_period_td"> </td>
                                                                            <td class="end_period_td"></td>
                                                                            <td class="imported_at_td"> </td>
                                                                            <td class="account_name_td"> </td>
                                                                            <td class="account_number_td"> </td>
                                                                            <td class="reference_number_td"> </td>
                                                                            <td class="general_entry_number_td"> </td>
                                                                        </tr>

                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td class="text-center" colspan="11">
                                                                                <div class="no-data">No record found.</div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <span class="pull-right">
                                                            <?php echo $page_links ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- table -->
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {
        //
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var employees = $('#js-filter-employee').val();
        var departments = $('#teamId').val();
        var jobTitles = $('#jobtitleId').val();
        var dateSelection = $("input[name=dateselection]:checked").val();
        //
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';

        var url = '<?php echo base_url('payrolls/ledger'); ?>' + '/' + start_date_applied + '/' + end_date_applied + '/' + employees + '/' + departments + '/' + jobTitles + '/' + dateSelection;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function() {

        //
        $('#js-filter-employee').select2();
        $('#teamId').select2();
        $('#jobtitleId').select2();

        <?php if ($this->uri->segment(5) != '') { ?>
            let filteremployes = "<?php echo $this->uri->segment(5); ?>";
            let filteremployeesArray = filteremployes.split(',');
            $('#js-filter-employee').select2('val', filteremployeesArray);
        <?php } else { ?>
            $('#js-filter-employee').select2('val', 'all');
        <?php } ?>

        <?php if ($this->uri->segment(6) != '') { ?>
            let filterDepartment = "<?php echo $this->uri->segment(6); ?>";
            let filterDepartmentArray = filterDepartment.split(',');
            $('#teamId').select2('val', filterDepartmentArray);
        <?php } else { ?>
            $('#teamId').select2('val', '0');
        <?php } ?>

        <?php if ($this->uri->segment(7) != '') { ?>
            let filterJobtitle = "<?php echo urldecode($this->uri->segment(7)); ?>";
            let filterJobTitleArray = filterJobtitle.split(',');
            $('#jobtitleId').select2('val', filterJobTitleArray);
        <?php } else { ?>
            $('#jobtitleId').select2('val', 'all');
        <?php } ?>


        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

        // Search Area Toggle Function
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        }).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                $('#end_date_applied').datepicker('option', 'minDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                $('#start_date_applied').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());

        <?php if ($this->uri->segment(3) == '' || $this->uri->segment(4) == '') { ?>
            $('#btn_apply_filters').click();
        <?php  } ?>

    });

    $("#check_all").click(function() {
        $(".check_it").prop("checked", this.checked);
        if ($("#check_all").is(':checked')) {
            $(".general_entry_number_td").show();
            $(".reference_number_td").show();
            $(".account_number_td").show();
            $(".account_name_td").show();
            $(".imported_at_td").show();
            $(".end_period_td").show();
            $(".start_period_td").show();
            $(".transaction_date_td").show();
            $(".description_td").show();
            $(".taxes_td").show();
            $(".net_pay_td").show();
            $(".gross_pay_td").show();
            $(".debit_amount_td").show();
            $(".credit_amount_td").show();
            $(".first_name_td").show();
            $(".middle_name_td").show();
            $(".last_name_td").show();
            $(".job_title_td").show();
            $(".department_td").show();
            $(".team_td").show();
            $(".ssn_td").show();
            $(".employee_number_td").show();
            $(".phone_number_td").show();
            $(".email_td").show();
            $(".employee_id_td").show();

        } else {
            $(".general_entry_number_td").hide();
            $(".reference_number_td").hide();
            $(".account_number_td").hide();
            $(".account_name_td").hide();
            $(".imported_at_td").hide();
            $(".end_period_td").hide();
            $(".start_period_td").hide();
            $(".transaction_date_td").hide();
            $(".description_td").hide();
            $(".taxes_td").hide();
            $(".net_pay_td").hide();
            $(".gross_pay_td").hide();
            $(".debit_amount_td").hide();
            $(".credit_amount_td").hide();
            $(".first_name_td").hide();
            $(".middle_name_td").hide();
            $(".last_name_td").hide();
            $(".job_title_td").hide();
            $(".department_td").hide();
            $(".team_td").hide();
            $(".ssn_td").hide();
            $(".employee_number_td").hide();
            $(".phone_number_td").hide();
            $(".email_td").hide();
            $(".employee_id_td").hide();
        }

    });

    //
    $("#general_entry_number").click(function() {
        if ($("#general_entry_number").is(':checked')) {
            $(".general_entry_number_td").show();
        } else {
            $(".general_entry_number_td").hide();
        }
    });

    $("#reference_number").click(function() {
        if ($("#reference_number").is(':checked')) {
            $(".reference_number_td").show();
        } else {
            $(".reference_number_td").hide();
        }
    });

    $("#account_number").click(function() {
        if ($("#account_number").is(':checked')) {
            $(".account_number_td").show();
        } else {
            $(".account_number_td").hide();
        }
    });

    $("#account_name").click(function() {
        if ($("#account_name").is(':checked')) {
            $(".account_name_td").show();
        } else {
            $(".account_name_td").hide();
        }
    });
    $("#imported_at").click(function() {
        if ($("#imported_at").is(':checked')) {
            $(".imported_at_td").show();
        } else {
            $(".imported_at_td").hide();
        }
    });

    $("#end_period").click(function() {
        if ($("#end_period").is(':checked')) {
            $(".end_period_td").show();
        } else {
            $(".end_period_td").hide();
        }
    });

    $("#start_period").click(function() {
        if ($("#start_period").is(':checked')) {
            $(".start_period_td").show();
        } else {
            $(".start_period_td").hide();
        }
    });

    $("#transaction_date").click(function() {
        if ($("#transaction_date").is(':checked')) {
            $(".transaction_date_td").show();
        } else {
            $(".transaction_date_td").hide();
        }
    });

    $("#description").click(function() {
        if ($("#description").is(':checked')) {
            $(".description_td").show();
        } else {
            $(".description_td").hide();
        }
    });

    $("#taxes").click(function() {
        if ($("#taxes").is(':checked')) {
            $(".taxes_td").show();
        } else {
            $(".taxes_td").hide();
        }
    });

    $("#net_pay").click(function() {
        if ($("#net_pay").is(':checked')) {
            $(".net_pay_td").show();
        } else {
            $(".net_pay_td").hide();
        }
    });

    $("#gross_pay").click(function() {
        if ($("#gross_pay").is(':checked')) {
            $(".gross_pay_td").show();
        } else {
            $(".gross_pay_td").hide();
        }
    });

    $("#debit_amount").click(function() {
        if ($("#debit_amount").is(':checked')) {
            $(".debit_amount_td").show();
        } else {
            $(".debit_amount_td").hide();
        }
    });

    $("#credit_amount").click(function() {
        if ($("#credit_amount").is(':checked')) {
            $(".credit_amount_td").show();
        } else {
            $(".credit_amount_td").hide();
        }
    });

    $("#first_name").click(function() {
        if ($("#first_name").is(':checked')) {
            $(".first_name_td").show();
        } else {
            $(".first_name_td").hide();
        }
    });

    $("#middle_name").click(function() {
        if ($("#middle_name").is(':checked')) {
            $(".middle_name_td").show();
        } else {
            $(".middle_name_td").hide();
        }
    });

    $("#last_name").click(function() {
        if ($("#last_name").is(':checked')) {
            $(".last_name_td").show();
        } else {
            $(".last_name_td").hide();
        }
    });


    $("#job_title").click(function() {
        if ($("#job_title").is(':checked')) {
            $(".job_title_td").show();
        } else {
            $(".job_title_td").hide();
        }
    });
    $("#department").click(function() {
        if ($("#department").is(':checked')) {
            $(".department_td").show();
        } else {
            $(".department_td").hide();
        }
    });

    $("#team").click(function() {
        if ($("#team").is(':checked')) {
            $(".team_td").show();
        } else {
            $(".team_td").hide();
        }
    });

    $("#ssn").click(function() {
        if ($("#ssn").is(':checked')) {
            $(".ssn_td").show();
        } else {
            $(".ssn_td").hide();
        }
    });
    $("#employee_number").click(function() {
        if ($("#employee_number").is(':checked')) {
            $(".employee_number_td").show();
        } else {
            $(".employee_number_td").hide();
        }
    });
    $("#email").click(function() {
        if ($("#email").is(':checked')) {
            $(".email_td").show();
        } else {
            $(".email_td").hide();
        }
    });

    $("#phone_number").click(function() {
        if ($("#phone_number").is(':checked')) {
            $(".phone_number_td").show();
        } else {
            $(".phone_number_td").hide();
        }
    });

    $("#employee_id").click(function() {
        if ($("#employee_id").is(':checked')) {
            $(".employee_id_td").show();
        } else {
            $(".employee_id_td").hide();
        }
    });



    $("#extra").click(function() {
        if ($("#extra").is(':checked')) {
            $(".extra_td").show();
        } else {
            $(".extra_td").hide();
        }
    });




    function print_page(elem) {
        $("table").removeClass("horizontal-scroll");

        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/images/favi-icon.png'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
        $("table").addClass("horizontal-scroll");
    }
</script>