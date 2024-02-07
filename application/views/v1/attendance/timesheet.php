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
</style>
<?php
$timeSheetName = "";
?>
<!-- Filter -->

<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="text-large">
            <strong>
                <i class="fa fa-filter text-orange" aria-hidden="true"></i>
                &nbsp;Filter
            </strong>
        </h2>
    </div>
    <div class="panel-body">
        <form action="<?= current_url(); ?>" method="get">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label>
                            Select Employee
                            <strong class="text-danger">*</strong>
                        </label>
                        <select name="employees" class="form-control">
                            <option value="0"></option>
                            <?php if ($employees) {

                                
                                foreach ($employees as $v0) {

                                    if ($v0["userId"] == $filter["employeeId"]) {
                                        $timeSheetName = remakeEmployeeName($v0);
                                    }
                            ?>
                                    <option value="<?= $v0["userId"]; ?>" <?= $v0["userId"] == $filter["employeeId"] ? "selected" : ""; ?>><?= remakeEmployeeName($v0); ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            Select date range
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" class="form-control jsDateRangePicker" readonly placeholder="MM/DD/YYYY - MM/DD/YYYY" name="date_range" value="<?= $filter["dateRange"] ?? ""; ?>" />
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn btn-orange">
                        <i class="fa fa-search"></i>
                        Apply filter
                    </button>
                    <a class="btn btn-black" href="<?= current_url(); ?>">
                        <i class="fa fa-times-circle"></i>
                        Clear filter
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($filter["employeeId"]) { ?>

    <!-- data -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="text-large">
                <strong>
                    <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                    &nbsp;Time Sheet <?= $records ? " of " . $timeSheetName : ""; ?>
                </strong>
                <p class="mt-5">
                    <?= formatDateToDB(
                        $filter["startDateDB"],
                        DB_DATE,
                        DATE
                    ); ?>
                    -
                    <?= formatDateToDB(
                        $filter["endDateDB"],
                        DB_DATE,
                        DATE
                    ); ?>
                </p>
            </h2>
        </div>

        <div class="panel-body">
            <?php $this->load->view('v1/users/payroll/partials/employee_payroll_detail'); ?>
        </div>
    </div>

<?php } ?>