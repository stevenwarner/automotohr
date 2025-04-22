<div class="main jsmaincontents" style=" position:relative">
    <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-right">
                <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('employee_management_system') : base_url('dashboard'); ?>"
                    class="btn btn-black">
                    <i class="fa fa-arrow-left"></i>
                    Dashboard
                </a>
                <a href="<?= base_url('compliance_safety_reporting/overview') ?>" class="btn btn-blue">
                    <i class="fa fa-pie-chart"></i>
                    Compliance Safety Reporting
                </a>

            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile">
                        <?= $type["compliance_report_name"]; ?>
                    </h2>
                </div>
                <!--  -->
                <form method="post" enctype="multipart/form-data" autocomplete="off" id="jsAddReportForm">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label for="report_title">Report Title <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" id="report_title" name="report_title"
                                    value="<?= $type['report_title'] ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="form-group">
                                <label for="report_date">Report Date <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" id="report_date" name="report_date"
                                    value="<?= $type['report_date'] ?>" />
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="form-group">
                                <label for="report_completion_date">Completion Date</label>
                                <input type="text" class="form-control" id="report_completion_date"
                                    name="report_completion_date" value="<?= $type['report_completion_date'] ?>" />
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="form-group">
                                <label for="report_status">Status</label>
                                <select name="report_status" id="report_status" style="width: 100%;">
                                    <option value="pending">Pending</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <?php $this->load->view("compliance_safety_reporting/partials/incidents/questions"); ?>

                    <!-- Employees -->
                    <div class="panel panel-default hidden">
                        <div class="panel-heading">
                            <h1 class="panel-heading-text text-medium">
                                <strong>Internal Employees</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <?php if ($employees): ?>
                                <div class="row">
                                    <?php foreach ($employees as $employee): ?>
                                        <div class="col-lg-4">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" name="report_employees[]"
                                                    value="<?= $employee["sid"]; ?>" />
                                                <div class="control__indicator"></div>
                                                <span><?= remakeEmployeeName($employee); ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p class="text-danger">No employees found.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Employees -->
                    <div class="panel panel-default hidden">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h1 class="panel-heading-text text-medium">
                                        <strong>
                                            External Employees
                                        </strong>
                                    </h1>

                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-orange jsAddExternalEmployee">
                                        <i class="fa fa-plus"></i>
                                        Add External Employee
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body jsAddExternalBody">
                            <div class="alert alert-info text-center">
                                No External employees found
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-orange">
                                <i class="fa fa-save jsCreateReportBtn"></i>
                                Create Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>