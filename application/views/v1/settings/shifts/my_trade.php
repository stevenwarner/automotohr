<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                    <!-- Page header -->
                    <div class="page-header-area"> </div>
                    <!-- Page title -->
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><br>
                    </div>
                    <div class="row"><br>
                        <div class="col-sm-4 text-left">
                            <a href="<?= base_url("dashboard"); ?>" class="btn btn-blue ">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Dashboard
                            </a>
                            <?php if (isPayrollOrPlus(true) && checkIfAppIsEnabled(SCHEDULE_MODULE)) { ?>
                                <a href="<?= base_url("settings/shifts/manage"); ?>" class="btn btn-orange">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    &nbsp;Shifts
                                </a>

                            <?php } ?>
                        </div>

                        <div class="col-sm-8 text-right">
                           
                            <a href="<?= base_url("shifts/my/subordinates"); ?>" class="btn btn-orange">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                &nbsp;My Team Shifts
                            </a>
                            <a href="<?= base_url("shifts/trade"); ?>" class="btn btn-orange">
                                <i class="fa fa-exchange" aria-hidden="true"></i>
                                &nbsp;Swap Shifts
                            </a>
                        </div>
                    </div><br>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div role="tabpanel">
                        <!-- Page content -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="text-medium panel-heading-text">
                                    <strong>
                                        <i class="fa fa-filter text-orange" aria-hidden="true"></i>
                                        Filter
                                    </strong>
                                </h2>
                            </div>
                            <div class="panel-body" style="min-height:20px;">
                                <form action="<?= current_url(); ?>" method="get">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>
                                                    Select date range
                                                    <strong class="text-danger">*</strong>
                                                </label>
                                                <input type="text" class="form-control jsDateRangePicker" readonly placeholder="MM/DD/YYYY - MM/DD/YYYY" name="date_range" value="<?= $filter["dateRange"] ?? ""; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-8 text-right"> <br>
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

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h2 class="text-medium panel-heading-text">
                                            &nbsp;
                                            <strong>
                                                Shift Swap Requests
                                            </strong>
                                        </h2>
                                    </div>

                                    <?php if ($employeeShifts) { ?>
                                        <div class="col-sm-6 text-right">
                                            <button class="btn btn-red jsRejectTradeShifts">
                                                Reject requests
                                            </button>
                                            <button class="btn btn-orange jsConfirmTradeShifts">
                                                Confirm Requests
                                            </button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php if ($employeeShifts) { ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead style="background-color: #fd7a2a;">
                                                <tr>
                                                    <th style="width:5%">
                                                        <label class="control control--checkbox" style="margin-bottom: 20px;">
                                                            <input type="checkbox" name="checkit[]" id="check_all">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </th>
                                                    <th style="width:20%">Shift</th>
                                                    <th style="width:20%">Status</th>
                                                    <th style="width:10%">Requested At</th>
                                                    <th style="width:25%">Employee</th>
                                                    <th style="width:20%;padding-right: 30px;" class="text-right">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($employeeShifts as $rowShift) {
                                                    $disabled = "";
                                                    if ($rowShift["request_status"] == 'awaiting confirmation') {
                                                        // $disabled = "disabled";
                                                    }
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="checkit[]" value="<?php echo $rowShift["sid"]; ?>" class="my_checkbox" data-status='<?php echo $rowShift["request_status"] ?>' <?php echo $disabled ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </td>

                                                        <td style="vertical-align: middle;">
                                                            <?php echo date_with_time($rowShift['shift_date']); ?><br>
                                                            <?php echo formatDateToDB($rowShift["start_time"], "H:i:s", "h:i a") . ' - ' . formatDateToDB($rowShift["end_time"], "H:i:s", "h:i a"); ?>
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <?php echo $rowShift["request_status"] != '' ? ucwords($rowShift["request_status"]) : ' - '; ?><br>
                                                            <?php echo $rowShift['updated_at'] != '' ? date_with_time($rowShift['updated_at']) : ' - '; ?>
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <?php echo $rowShift['created_at'] != '' ? date_with_time($rowShift['created_at']) : ' - '; ?>
                                                        </td>

                                                        <td style="vertical-align: middle;">
                                                            <?
                                                            echo $rowShift["to_employee_sid"] != '' ? getUserNameBySID($rowShift["from_employee_sid"]) : ' - ';
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <div class="col-sm-12 text-right">
                                                                <?php if ($rowShift["request_status"] == 'awaiting confirmation' || $rowShift["request_status"] == 'confirmed') {
                                                                ?>
                                                                    <button class="btn btn-red jsRejectTradeShift" data-shiftid="<?php echo $rowShift["sid"]; ?>">
                                                                        Reject
                                                                    </button>
                                                                <?php } ?>

                                                                <?php if ($rowShift["request_status"] == 'awaiting confirmation' || $rowShift["request_status"] == 'rejected') { ?>

                                                                    <button class="btn btn-orange btn btn-orange jsConfirmTradeShift" data-shiftid="<?php echo $rowShift["sid"]; ?>">
                                                                        Confirm
                                                                    </button>
                                                                <?php  }
                                                                ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } else {
                                    $this->load->view("v1/no_data");
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>