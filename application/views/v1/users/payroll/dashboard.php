<style>
    .attendance_reports_main
    {
        display: flex;
        gap: 10px;
        padding: 0 10px;
        transition: 0.4s;
    }

    .attendance_report_item
    {
        width: 221px;
        height: 112px;
        padding: 15px;
        border-radius: 10px;
        cursor: pointer;
    } 


    
    .attendance_report_line
    {
        width: 20px;
        border: 1px solid #007B55;
        margin: 0;
    }

    .attendance_report_details
    {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
        gap: 10px;
    }

    .attendance_reports_main li
    {
        list-style-type: none;
    }

    .attendance_report_content h3
    {
        color: #000;
        text-transform: capitalize;
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .attendance_report_content p
    {
        color: #000;
        font-size: 16px;
        font-weight: 600;
    }

    .regular_time 
    {
        background-color: #00AB551F;
    }

    .paid_break_time 
    {
        background-color: #7A091614;
    }

    .over_time 
    {
        background-color: #FFAB0014;
    }

    .double_over_time 
    {
        background-color: #00B8D91F;
    }

    .paid_time_off 
    {
        background-color: #919EAB14; 
    }

    .total_paid 
    {
        background-color: #8224E314;
    }

    .popover-content p{
        padding: 10px;
    }

    .popover-content p:nth-child(odd){
        background: #eee;
    }
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <!-- page header -->
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                </span>
                            </div>
                            <!-- main buttons area -->
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a class="btn btn-black" href="<?= $return_title_heading_link; ?>">
                                        <i class="fa fa-arrow-left"></i>
                                        <?= $return_title_heading; ?>
                                    </a>
                                </div>
                            </div>
                            <hr />
                            <!-- main content area -->
                            <div class="row">
                                <!-- pay schedule -->
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-calendar text-orange" aria-hidden="true"></i>
                                                Pay schedule
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <span class="text-small">Name</span>
                                                <br />
                                                <strong><?= GetVal($paySchedule["custom_name"]); ?></strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Pay frequency</span>
                                                <br />
                                                <strong><?= GetVal($paySchedule["frequency"]); ?></strong>
                                            </p>
                                            <?php if(in_array($paySchedule["frequency"], ["monthly"])) {?>
                                                <?php if ($paySchedule["day_1"] && $paySchedule["day_2"]) { ?>
                                                    <p class="text-medium">
                                                        <span class="text-small">First day of payment</span>
                                                        <br />
                                                        <strong>"<?= GetVal($paySchedule["day_1"]); ?>" of the month</strong>
                                                    </p>
                                                    <p class="text-medium">
                                                        <span class="text-small">Second day of payment</span>
                                                        <br />
                                                        <strong>"<?= GetVal($paySchedule["day_2"]); ?>" of the month</strong>
                                                    </p>
                                                <?php } ?>
                                            <?php }?>
                                            <p class="text-medium">
                                                <span class="text-small">Pay period start date</span>
                                                <br />
                                                <strong><?= $paySchedule["anchor_pay_date"] ? formatDateToDB($paySchedule["anchor_pay_date"], DB_DATE, DATE) : "Not specified"; ?></strong>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow jsEditPaySchedule">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit Pay schedule
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- job & wage -->
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-money text-orange" aria-hidden="true"></i>
                                                Job & wage
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <span class="text-small">Position</span>
                                                <br />
                                                <strong>
                                                    <?php echo $jobWageData['title']; ?>
                                                </strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Employment type</span>
                                                <br />
                                                <strong>
                                                    <?php
                                                    if ($jobWageData['employmentType'] == 'fulltime') {
                                                        echo "Full Time";
                                                    } else if ($jobWageData['employmentType'] == 'parttime') {
                                                        echo "Part Time";
                                                    } else if ($jobWageData['employmentType'] == 'contractual') {
                                                        echo "Contractual";
                                                    } else {
                                                        echo "N/A";
                                                    }
                                                    ?>
                                                </strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Hire date</span>
                                                <br />
                                                <strong>
                                                    <?php echo formatDateToDB($jobWageData['hireDate'], DB_DATE, DATE); ?>
                                                </strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Wage</span>
                                                <br />
                                                <strong>$<?php echo $jobWageData['rate']; ?> /<?php echo $jobWageData['paymentUnit']; ?></strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Overtime Rule</span>
                                                <br />
                                                <strong>
                                                    <?php echo $jobWageData['overtimeRule']; ?>
                                                </strong>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow jsEditJobWage">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit wage
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- custom earning -->
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-money text-orange" aria-hidden="true"></i>
                                                Custom Earnings
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php if (!$jobWageData['earnings']) { ?>
                                                <a href="#" style="padding: 8px; margin-bottom: 5px;" class="badge badge-secondary">No Earning Type assign yet.</a>
                                            <?php } else { ?>
                                                <?php foreach ($jobWageData['earnings'] as $earningType) { ?>
                                                    <a href="#" style="padding: 8px; margin-bottom: 5px;" class="badge badge-secondary"><?php echo $earningType['title']; ?></a>
                                                <?php } ?>   
                                            <?php } ?>      
                                           
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow jsEditCustomEarning">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit earning Types
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h2 class="text-large">
                                                <strong>
                                                    <i class="fa fa fa-money text-orange" aria-hidden="true"></i>
                                                    &nbsp;Payroll Break Down
                                                </strong>
                                                <p class="mt-5">
                                                    <?= formatDateToDB(
                                                        $startDate,
                                                        DB_DATE,
                                                        DATE
                                                    ); ?>
                                                    -
                                                    <?= formatDateToDB(
                                                        $endDate,
                                                        DB_DATE,
                                                        DATE
                                                    ); ?>
                                                </p>
                                            </h2>
                                        </div>
                                        <div class="panel-body">

                                            <!-- filter -->
                                            <div class="row ">
                                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                    <label>Start Date</label>
                                                    <input type="text" name="startDate" id="jsStartDate" class="form-control jsStartDatePicker" readonly value="<?php echo formatDateToDB($startDate, DB_DATE, SITE_DATE); ?>" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                    <label>End Date</label>
                                                    <input type="text" name="endDate" id="jsEndDate" class="form-control jsEndDatePicker" readonly value="<?php echo formatDateToDB($endDate, DB_DATE, SITE_DATE); ?>" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                    <label>&nbsp;</label>
                                                    <button class="btn btn-green btn-block jsApplyFilter">
                                                        Apply Filter
                                                    </button>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                    <label>&nbsp;</label>
                                                    <a href="<?php echo base_url("payrolls/dashboard/".$userType."/".$userId); ?>" class="btn btn-black btn-block">
                                                        Clear Filter
                                                    </a>
                                                </div>
                                            </div>

                                            <hr>

                                            <?php $this->load->view('v1/users/payroll/partials/employee_payroll_detail'); ?>
                                        </div>
                                    </div>
                                </div>            
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<script>
    const profileUserInfo = {
        userId: <?= $userId; ?>,
        userType: "<?= $userType; ?>",
        nameWithRole: "<?= remakeEmployeeName($employer); ?>"
    };
</script>