<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">



                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <?php $this->load->view('manage_employer/company_logo_name'); ?>
                        </span>
                    </div>
                    <!-- Page title -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("dashboard"); ?>" class="btn btn-black">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Dashboard
                            </a>
                            <a href="<?= base_url("settings/shifts/breaks"); ?>" class="btn btn-orange">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                &nbsp;Manage Breaks
                            </a>
                            <a href="<?= base_url("settings/shifts/templates"); ?>" class="btn btn-orange">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                &nbsp;Manage Shift Templates
                            </a>


                            <?php if (checkIfAppIsEnabled(SCHEDULE_MODULE)) { ?>
                                <a href="<?= base_url("shifts/my"); ?>" class="btn btn-orange" >
                                <i class="fa fa-calendar" aria-hidden="true"></i>    
                                &nbsp;My Shifts
                                </a>

                                <a href="<?= base_url("shifts/my/subordinates"); ?>" class="btn btn-orange" >
                                <i class="fa fa-users" aria-hidden="true"></i>     
                                &nbsp;My Team Shifts
                                </a>

                                <a href="<?= base_url("shifts/myTrade"); ?>" class="btn btn-orange" >
                                <i class="fa fa-exchange" aria-hidden="true"></i>                                
                                &nbsp;Shift Swap Requests
                                </a>

                                <?php if (isPayrollOrPlus()) { ?>
                                    <a href="<?= base_url("settings/shifts/trade"); ?>" class="btn btn-orange">
                                    <i class="fa fa-exchange" aria-hidden="true"></i>
                                        &nbsp;Shift Swap Approvals
                                    </a>
                                <?php } ?>

                                <?php if (isPayrollOrPlus()) { ?>
                                    <!--
                                    <a href="<?= base_url("settings/openshifts/approval"); ?>" class="btn btn-orange">
                                        <i class="fa fa-exchange" aria-hidden="true"></i>
                                        &nbsp;Open Shift Approvals
                                    </a> -->
                                <?php } ?>
                            <?php } ?>



                        </div>
                    </div>
                    <br />

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
                                            <button class="btn btn-orange" type="button" id="jsApplyFilter">
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

                        <!-- View Page -->
                        <div class="js-page p10" id="js-page-view">
                            <!-- Requests status -->

                            <div class="row">
                                <div class="pto-tabs col-lg-12">
                                    <ul class="nav nav-tabs nav_report_status">
                                        <li class="active-tab active">
                                            <a href="javascript:void(0)" data-key="all" class="jsReportTab">All <br><span id="jsAllRequests"></span></a>
                                        </li>

                                        <li class="active-tab">
                                            <a href="javascript:void(0)" data-key="confirmed" class="jsReportTab">Pending <br><span id="jsPendingRequests"></span></a>
                                        </li>
                                        <li class="active-tab">
                                            <a href="javascript:void(0)" data-key="approved" class="jsReportTab">Approved <br><span id="jsApprovedRequests"></span></a>
                                        </li>
                                        <li class="active-tab">
                                            <a href="javascript:void(0)" data-key="admin rejected" class="jsReportTab">Rejected <br><span id="jsRejectedRequests"></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h2 class="text-medium panel-heading-text">
                                                &nbsp;
                                                <strong id="jsTableHeading">
                                                    Shifts
                                                </strong>
                                            </h2>
                                        </div>

                                        <div class="col-sm-6 text-right" id="jsApprovedRejectBtn">
                                            <button class="btn btn-red jsAdminRejectTradeShifts">
                                                Reject requests
                                            </button>
                                            <button class="btn btn-orange jsAdminApproveTradeShifts">
                                                Approve Requests
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <!-- <table class="table table-striped table-condensed pto-policy-table csCustomTableHeader"> -->
                                            <thead style="background-color: #fd7a2a;" class="js-table-head">
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
                                                    <th style="width:25%">From Employee</th>
                                                    <th style="width:25%">To Employee</th>
                                                    <th style="width:20%;padding-right: 30px;" class="text-right">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="js-data-area">
                                                <tr class="js-error-row"></tr>
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