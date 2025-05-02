<style>
    .issue-files-btn {
        border: 0;
        margin: 0 20px 0 0;
        margin-top: -10px;
        font-size: 32px;
        border-radius: 5px;
        cursor: pointer;
        color: #fff;
        height: 65px;
        background-color: transparent;
        position: relative;
    }

    .issue-files-count {
        position: absolute;
        top: 4px;
        right: -10px;
        font-size: 12px;
        color: #fff;
        padding: 5px;
        width: 25px;
        height: 25px;
        line-height: 15px;
        background-color: #b4052c;
        ;
        border-radius: 100%;
    }
</style>
<?php $progressGraphData = [
    "pending" => [
        "name" => "In Progress",
        "y" => 0,
    ],
    "completed" => [
        "name" => "Completed",
        "y" => 0,
    ],
    "on_hold" => [
        "name" => "On Hold",
        "y" => 0,
    ],
];
$severityLevelGraph = ["data" => [], "colors" => [], "categories" => []];

$severityLevelGraph["categories"] = array_column($severity_levels, "level");

foreach ($severity_levels as $ll) {
    $severityLevelGraph["data"][$ll["level"]] = 0;
}

$severityLevelGraph["colors"] = array_column($severity_levels, "bg_color");
?>

<div class="main csPageWrap">
    <div class="container-fluid">
        <!-- Buttons -->
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <a href="<?= base_url("/dashboard"); ?>" class="btn btn-black">
                    <i class="fa fa-arrow-left"></i> Dashboard
                </a>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?= base_url("compliance_safety_reporting/add/1") ?>" class="btn btn-orange">
                    <i class="fa fa-plus-circle"></i>
                    Add New Report
                </a>
            </div>
        </div>

        <br />


        <!-- Filter -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-heading-text text-medium">
                    <i class="fa fa-filter text-orange"></i>
                    Filter
                    <p class="text-danger text-small">
                        You can filter the compliance safety reports by severity level, incident type, and status.
                    </p>
                </h3>
            </div>
            <div class="panel-body">
                <form id="filterForm" method="get" action="<?= base_url("/compliance_safety_reporting/dashboard"); ?>">
                    <!--  -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="severityLevel">Severity Level</label>
                                <select id="severityLevel" name="severityLevel" class="form-control">
                                    <option <?= $filter["severity_level"] == "-1" ? "selected" : ""; ?> value="-1">All
                                    </option>
                                    <?php if ($severity_status) { ?>
                                        <?php foreach ($severity_status as $status) { ?>
                                            <option <?= $filter["severity_level"] == $status['sid'] ? "selected" : ""; ?>
                                                value="<?php echo $status['sid']; ?>">
                                                <?php echo is_numeric($status["level"]) ? " Severity Level " . $status["level"] : $status["level"]; ?>
                                            </option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="incidentType">Incident Type</label>
                                <select id="incidentType" name="incidentType" class="form-control">
                                    <option <?= $filter["incident"] == "-1" ? "selected" : ""; ?> value="-1">All
                                    </option>
                                    <?php if ($incidents): ?>
                                        <?php foreach ($incidents as $incident): ?>
                                            <optgroup
                                                label="<?= $incident["title"]; ?> (<?= formatDateToDB($incident["report_date"], DB_DATE, DATE); ?>)">
                                                <?php foreach ($incident["incidents"] as $sub): ?>
                                                    <option <?= $filter["incident"] == $sub["id"] ? "selected" : ""; ?>
                                                        value="<?= $sub["id"]; ?>">
                                                        <?= $sub["name"]; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control">
                                    <option <?= $filter["status"] == "-1" ? "selected" : ""; ?> value="-1">All</option>
                                    <option <?= $filter["status"] == "pending" ? "selected" : ""; ?> value="pending">
                                        Pending</option>
                                    <option <?= $filter["status"] == "on_hold" ? "selected" : ""; ?> value="on_hold">On
                                        Hold</option>
                                    <option <?= $filter["status"] == "pending" ? "selected" : ""; ?> value="pending">In
                                        Progress</option>
                                    <option <?= $filter["status"] == "completed" ? "selected" : ""; ?> value="completed">
                                        Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="severityLevel">Report Title</label>
                                <input type="text" class="form-control" id="incidentTitle" name="title"
                                    value="<?php echo $filter["title"]; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="incidentType">Reported Date</label>
                                <input type="text" class="form-control" id="jsDateRangePicker" name="date_range"
                                    value="<?php echo $filter["date_range"]; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-blue">
                                    <i class="fa fa-filter"></i> Apply Filter
                                </button>
                                <a href="<?= current_url(); ?>" class="btn btn-red">
                                    <i class="fa fa-times"></i> Clear Filter
                                </a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="jsProgressGraph"></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="jsSeverityGraph"></div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-heading-text text-medium" style="padding: 14px 0px;">
                    <i class="fa fa-table text-orange"></i> Compliance Safety Reporting
                    <span class="pull-right">

                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="control control--checkbox">
                            <input type="checkbox" name="" id="" class="js-check-all" />
                            Select All Issues
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button type="button" class="btn btn-orange jsSendReminderEmails">
                            <i class="fa fa-send"></i>
                            Send Email
                        </button>
                        <a class="btn btn-green" id="jsCSVButton" href="<?= $CSVUrl ?>">
                            <i class="fa fa-download"></i>
                            Export CSV
                        </a>
                    </div>
                </div>
                <br />

                <?php if ($reports): ?>
                    <?php foreach ($reports as $v0): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading jsToggle" data-target="jsChildRows<?= $v0["id"]; ?>"
                                style="cursor: pointer;">
                                <i class="fa fa-chevron-right jsToggleIcon"></i>
                                <?= $v0["title"]; ?>
                                (<?= count($v0["issues"]); ?> Issues)
                                <span class="pull-right">
                                    <?= formatDateToDB($v0["report_date"], DB_DATE, DATE); ?>
                                </span>
                            </div>
                            <div class="jsChildRows hidden jsChildRows<?= $v0["id"]; ?>">
                                <div class="panel-body js-tr">
                                    <div class="row">
                                        <?php foreach ($v0["issues"] as $record): ?>
                                            <?php
                                            $statusText = "Pending";
                                            $statusClass = "warning";
                                            //
                                            if ($record["completion_status"] == "on_hold") {
                                                $statusText = "On Hold";
                                                $statusClass = "danger";
                                                $progressGraphData["on_hold"]["y"]++;
                                            } else if ($record["completion_status"] == "completed") {
                                                $statusText = "Completed";
                                                $statusClass = "success";
                                                $progressGraphData["completed"]["y"]++;
                                            } else {
                                                $progressGraphData["pending"]["y"]++;
                                            }
                                            //
                                            $severityLevelGraph["data"][$record["level"]]++;
                                            $severityLevelGraph["colors"][$record["level"]] = $record["bg_color"];
                                            ?>
                                            <div class="col-md-12">
                                                <div
                                                    class="panel <?= $record["completion_status"] === "completed" ? "panel-success" : "panel-default " ?>">
                                                    <div class="panel-heading jsToggle2" data-target="issue<?= $record["sid"] ?>"
                                                        style="cursor: pointer;  <?= $record["completion_status"] === "completed" ? "background: rgba(92, 184, 92, .6);" : " " ?>">
                                                        <div class="row">
                                                            <div class="col-md-10 col-xs-12">
                                                                <h3 class="panel-heading-text text-small">
                                                                    <i class="fa fa-chevron-right jsToggleIcon"></i>
                                                                    <?= strip_tags(substr($record["description"], 0, 70)); ?>...
                                                                </h3>
                                                            </div>
                                                            <div class="col-md-2 col-xs-12 text-right">
                                                                <label class="btn form-control"
                                                                    style="background: <?= $record["bg_color"]; ?>; color: <?= $record["txt_color"]; ?>; border-radius: 5px;">
                                                                    <?= is_numeric($record["level"]) ? " Severity Level " . $record["level"] : $record["level"] ?>
                                                                </label>

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="panel-body hidden jsIssuesBody" id="issue<?= $record["sid"]; ?>">
                                                        <div class="row">
                                                            <div class="col-xs-1">
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="issues_ids[]" class="jsIssueIds"
                                                                        value="<?= $record["sid"] ?>" />
                                                                    <div class="control__indicator" style="margin-top: -10px;">
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="col-xs-11 text-right">
                                                                <?php if ($record["completion_status"] != "completed"): ?>
                                                                    <button class="btn btn-green jsMarkIssueDone"
                                                                        data-issue_id="<?= $record["sid"]; ?>"
                                                                        style="border-radius: 5px;" title="Mark As Done">
                                                                        <i class="fa fa-check-circle"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                                <button class="btn btn-black jsIssueUploadFileBtn"
                                                                    title="Upload files"
                                                                    data-report_id="<?= $record["csp_reports_sid"]; ?>"
                                                                    data-incident_id="<?= $record["csp_reports_incidents_sid"]; ?>"
                                                                    data-issue_id="<?= $record["sid"]; ?>">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                                <a href="<?= base_url("compliance_safety_reporting/incident_item_management/" . $record["csp_reports_sid"] . "/" . $record["csp_reports_incidents_sid"] . "/" . $record["sid"]); ?>"
                                                                    class="btn btn-orange">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>
                                                                    <small>
                                                                        Report
                                                                    </small>
                                                                </label>
                                                                <br>
                                                                <a
                                                                    href="<?= base_url("compliance_safety_reporting/edit/" . $record["csp_reports_sid"]); ?>">
                                                                    <?= $record["title"]; ?>
                                                                </a>
                                                                (<?= formatDateToDB($v0["report_date"], DB_DATE, DATE); ?>)
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>
                                                                    <small>
                                                                        Incident
                                                                    </small>
                                                                </label>
                                                                <br>
                                                                <?= $record["compliance_incident_type_name"]; ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>
                                                                    <small>
                                                                        Status
                                                                    </small>
                                                                </label>
                                                                <br>
                                                                <span class="jsStatusRow<?= $record["sid"]; ?>">
                                                                    <label class="btn btn-<?= $statusClass ?>"
                                                                        style="border-radius: 5px;">
                                                                        <?= $statusText; ?>
                                                                    </label>
                                                                    <?php if ($record["completion_status"] === "completed"): ?>
                                                                        <?= remakeEmployeeName($record); ?>
                                                                        <br>
                                                                        <?= formatDateToDB($record["completion_date"], DB_DATE, DATE); ?>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <br>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?= convertCSPTags(
                                                                    $record["description"],
                                                                    json_decode($record["answers_json"], true),
                                                                    true
                                                                ); ?>
                                                            </div>
                                                        </div>
                                                        <?php if ($record["files"]): ?>

                                                            <br>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="jsFilesArea<?= $record["sid"]; ?>">

                                                                        <?php $this->load->view("compliance_safety_reporting/files", $record); ?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?php $severityLevelGraph["data"] = array_values($severityLevelGraph["data"]); ?>
<script>
    progressGraphData = '<?= json_encode(array_values($progressGraphData)); ?>';
    severityLevelGraph = '<?= json_encode(($severityLevelGraph)); ?>';
    progressGraphColors = '<?= json_encode((["#f0ad4e", "#5cb85c", "#c10000"])); ?>';
</script>