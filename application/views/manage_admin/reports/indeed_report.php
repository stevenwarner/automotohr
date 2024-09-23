<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
    .caption h3 {
        margin-top: 0px !important;
        color: #fff !important;
    }

    .caption h4 {
        margin-bottom: 0px !important;
        color: #fff !important;
    }

    .success-block {
        background: #28a745 !important;
    }

    .error-block {
        background: #dc3545 !important;
    }

    .post-block {
        background: #007bff !important;
    }

    .put-block {
        background: #674ead !important;
    }

    pre {
        background: #000 !important;
        color: #fff !important;
    }

    .vam {
        vertical-align: middle !important;
    }

    .thumbnail {
        border-radius: 5px;
        box-shadow: 0 0 5px 1px #eee;
    }
</style>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title">
                                            <i class="fa fa-users"></i>
                                            <?= $page_title; ?>
                                        </h1>
                                    </div>
                                    <div class="hr-search-criteria <?= $flag ? "opened" : ""; ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main search-collapse-area" <?= $flag ? "style='display:block'" : ""; ?>>
                                        <!--  -->
                                        <form action="<?= current_url(); ?>" type="GET">
                                            <div class="row">
                                                <div class="col-sm-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Companies</label>
                                                        <select name="companies[]" id="jsCompanies" multiple style="width: 100%;">
                                                            <option value="All" <?= in_array("All", $filter["companies"]) ? "selected" : ""; ?>>All</option>
                                                            <?php if ($companies): ?>
                                                                <?php foreach ($companies as $v0): ?>
                                                                    <option value="<?= $v0["sid"]; ?>" <?= in_array($v0["sid"], $filter["companies"]) ? "selected" : ""; ?>><?= $v0["CompanyName"]; ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select name="status[]" id="status" multiple style="width: 100%;">
                                                            <option <?= in_array("All", $filter["status"]) ? "selected" : ""; ?> value="All">All</option>
                                                            <option <?= in_array("Pending", $filter["status"]) ? "selected" : ""; ?> value="Pending">Pending</option>
                                                            <option <?= in_array("Processing", $filter["status"]) ? "selected" : ""; ?> value="Processing">Processing</option>
                                                            <option <?= in_array("Processed", $filter["status"]) ? "selected" : ""; ?> value="Processed">Processed</option>
                                                            <option <?= in_array("Expired", $filter["status"]) ? "selected" : ""; ?> value="Expired">Expired</option>
                                                            <option <?= in_array("Errors", $filter["status"]) ? "selected" : ""; ?> value="Errors">Errors</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Period</label>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <input type="text" readonly name="start" id="jsPeriodStart" class="form-control" placeholder="MM/DD/YYYY" value="<?= $filter["startDate"]; ?>" />
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <input type="text" readonly name="end" id="jsPeriodEnd" class="form-control" placeholder="MM/DD/YYYY" value="<?= $filter["endDate"]; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-sm-12 text-right">
                                                    <button class="btn btn-success">
                                                        <i class="fa fa-search"></i>
                                                        &nbsp;Apply
                                                    </button>

                                                    <a class="btn btn-default" href="<?= base_url("manage_admin/reports/indeed"); ?>">
                                                        <i class="fa fa-refresh"></i>
                                                        &nbsp;Reset
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Thumbnails -->
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <div class="thumbnail put-block">
                                                <div class="caption">
                                                    <h3 id="jsSuccessCalls"><?= $counts['records']; ?></h3>
                                                    <h4><strong>Total Jobs</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail success-block">
                                                <div class="caption">
                                                    <h3 id="jsSuccessCalls"><?= $counts['pending']; ?></h3>
                                                    <h4><strong>Pending Jobs</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail post-block">
                                                <div class="caption">
                                                    <h3 id="jsSuccessCalls"><?= $counts['processing']; ?></h3>
                                                    <h4><strong>Processing Jobs</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail success-block">
                                                <div class="caption">
                                                    <h3 id="jsSuccessCalls"><?= $counts['processed']; ?></h3>
                                                    <h4><strong>Processed Jobs</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail error-block">
                                                <div class="caption">
                                                    <h3 id="jsSuccessCalls"><?= $counts['expired']; ?></h3>
                                                    <h4><strong>Expired Jobs</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail error-block">
                                                <div class="caption">
                                                    <h3 id="jsSuccessCalls"><?= $counts['errors']; ?></h3>
                                                    <h4><strong>Jobs With Errors</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($records) : ?>
                                <!-- Showing -->
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo $page_links ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Job<br />Title</th>
                                                        <th class="text-right" scope="col">Source<br />Posting<br />Id</th>
                                                        <th class="text-right" scope="col">Tracking<br />Key</th>
                                                        <th class="text-right" scope="col">Type</th>
                                                        <th class="text-right" scope="col">Status</th>
                                                        <th class="text-right" scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $companyCache = []; ?>
                                                    <?php foreach ($records as $v0):
                                                        // check and add company name to cache
                                                        $companyCache[$v0["user_sid"]] =
                                                            $companyCache[$v0["user_sid"]] ?? getCompanyColumnById($v0["user_sid"], "CompanyName")["CompanyName"];
                                                    ?>
                                                        <tr data-id="<?=$v0["sid"];?>">
                                                            <td>
                                                                <strong>
                                                                    <?= $v0["Title"]; ?>
                                                                </strong>
                                                                <p>Company: <?= $companyCache[$v0["user_sid"]]; ?></p>
                                                            </td>
                                                            <td class="text-right">
                                                                <?= $v0["indeed_posting_id"] ?? "-"; ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <?= $v0["tracking_key"] ?? "-"; ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>
                                                                    <?= $v0["is_expired"] ? "EXPIRED" : "NEW"; ?>
                                                                </strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <?php if ($v0["is_processed"]) : ?>
                                                                    <strong>PROCESSED</strong>
                                                                    <br>
                                                                    <?= formatDateToDB($v0["processed_at"], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                <?php elseif ($v0["is_processing"] == 0) : ?>
                                                                    <strong>PENDING</strong>
                                                                <?php elseif ($v0["is_processing"] == 1 && $v0["has_errors"]) : ?>
                                                                    <strong>ERRORS</strong>
                                                                <?php elseif ($v0["is_processing"] == 1) : ?>
                                                                    <strong>PROCESSING</strong>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <?php if ($v0["log_sid"]): ?>
                                                                    <!-- <button class="btn btn-success">
                                                                        <i class="fa fa-eye"></i>
                                                                        Log
                                                                    </button> -->
                                                                <?php endif; ?>
                                                                <!-- <button class="btn btn-success">
                                                                    <i class="fa fa-eye"></i>
                                                                    History
                                                                </button>
                                                                <button class="btn btn-success">
                                                                    <i class="fa fa-eye"></i>
                                                                    Job
                                                                </button> -->
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <?php echo $page_links ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info text-center">
                                    <strong>
                                        No Jobs found.
                                    </strong>
                                </div>
                            <?php endif; ?>
                            <!-- Main body -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {
        $("#jsCompanies").select2({
            closeOnSelection: false
        });
        $("#status").select2({
            closeOnSelection: false
        });

        var dateFormat = "mm/dd/yy",
            from = $("#jsPeriodStart")
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1
            })
            .on("change", function() {
                to.datepicker("option", "minDate", getDate(this));
                to.datepicker.click(function() {
                    $(this).datepicker('show');
                });
            }),
            to = $("#jsPeriodEnd").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1
            })
            .on("change", function() {
                from.datepicker("option", "maxDate", getDate(this));
            });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }

            return date;
        }
    })
</script>