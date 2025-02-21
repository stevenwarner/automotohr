<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-right">
                <a href="<?php echo $employee['access_level'] == 'Employee' ?  base_url('employee_management_system') : base_url('dashboard'); ?>" class="btn btn-black">
                    <i class="fa fa-arrow-left"></i>
                    Dashboard
                </a>
                <a href="<?= base_url('compliance_safety_reporting/dashboard') ?>" class="btn btn-blue">
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
                <form method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label for="report_title">Report Title <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" id="report_title" name="report_title" value="<?= $type['report_title'] ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                            <div class="form-group">
                                <label for="report_date">Report Date <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" id="report_date" name="report_date" value="<?= $type['report_date'] ?>" />
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                            <div class="form-group">
                                <label for="report_completion_date">Completion Date</label>
                                <input type="text" class="form-control" id="report_completion_date" name="report_completion_date" value="<?= $type['report_completion_date'] ?>" />
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Files -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Manage Files</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="file" class="form-control hidden" id="report_files" name="report_files" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="widget-box">
                                    <div class="attachment-box full-width">
                                        <h2>Document</h2>
                                        <div></div>
                                        <div class="attach-title">
                                            <span></span>
                                        </div>
                                        <div class="status-panel">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                    <a href="javascript:;" class="btn btn-block btn-info" onclick="view_attach_item(this);" item-category="Document" item-title="dasd" item-type="document" item-url="https://view.officeapps.live.com/op/embed.aspx?src=https%3A%2F%2Fautomotohrattachments.s3.amazonaws.com%2Ftest_file_01.doc"><i class="fa fa-eye"></i></a>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                    <a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2Ftest_file_01%2Edoc&amp;wdAccPdf=0" class="btn btn-block btn-info"><i class="fa fa-print"></i></a>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                    <a target="_blank" href="http://automotohr.local/incident_reporting_system/download_incident_document/test_file_01.doc" class="btn btn-block btn-info"><i class="fa fa-download"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Add New Note</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="report_note">Type <strong class="text-danger">*</strong></label>
                                    <select name="report_note" id="report_note">
                                        <option value="personal_note">Personal Note</option>
                                        <option value="employee_note">Employee Note</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="report_note">Note <strong class="text-danger">*</strong></label>
                                    <textarea class="form-control" id="report_note" name="report_note" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employees -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Employees</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="control control--checkbox">
                                    <input type="checkbox" name="report_employees" />
                                    <div class="control__indicator"></div>
                                    <span>Employee 1</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>