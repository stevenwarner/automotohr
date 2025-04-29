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
            <div class="col-sm-12 text-right">
                <a href="<?= base_url("/dashboard"); ?>" class="btn btn-black">
                    <i class="fa fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="<?= base_url("/compliance_safety_reporting/overview"); ?>" class="btn btn-blue">
                    <i class="fa fa-pie-chart"></i>
                    Overview
                </a>
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
                                            <option <?= $filter["severity_level"] == $status['sid'] ? "selected" : ""; ?> value="<?php echo $status['sid']; ?>">
                                                <?php echo is_numeric($status["level"]) ? " Severity Level ".$status["level"] : $status["level"];  ?>
                                            </option>
                                        <?php } ?>
                                    <?php } ?>    
                                    <!-- <option <?= $filter["severity_level"] == "1" ? "selected" : ""; ?> value="1">Severity
                                        Level 1</option>
                                    <option <?= $filter["severity_level"] == "2" ? "selected" : ""; ?> value="2">Severity
                                        Level 2</option>
                                    <option <?= $filter["severity_level"] == "3" ? "selected" : ""; ?> value="3">Severity
                                        Level 3</option>
                                    <option <?= $filter["severity_level"] == "4" ? "selected" : ""; ?> value="4">Severity
                                        Level 4</option>
                                    <option <?= $filter["severity_level"] == "5" ? "selected" : ""; ?> value="5">Severity
                                        Level 5</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="incidentType">Incident Type</label>
                                <select id="incidentType" name="incidentType" class="form-control">
                                    <option <?= $filter["incident"] == "-1" ? "selected" : ""; ?> value="-1">All</option>
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
                                <input type="text" class="form-control" id="incidentTitle" name="title" value="<?php echo $filter["title"]; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="incidentType">Reported Date</label>
                                <input type="text" class="form-control" id="jsDateRangePicker" name="date_range" value="<?php echo $filter["date_range"]; ?>">
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
                        <a type="button" class="btn btn-orange jsSendReminderEmails" href="javascript:;">
                            <i class="fa fa-send"></i>
                            Send Email 
                        </a>
                        <a type="button" class="btn btn-green" target="_blank" id="jsCSVButton" href="<?=$CSVUrl?>">
                            <i class="fa fa-download"></i>
                            Export CSV
                        </a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="bg-black" style="vertical-align: middle;">
                                    <input type="checkbox" class="js-check-all" />
                                </th>
                                <th class="bg-black">Severity<br />Level</th>
                                <th class="bg-black">Report</th>
                                <th class="bg-black">Incident</th>
                                <th class="bg-black">Issue</th>
                                <th class="bg-black">Status</th>
                                <th class="bg-black">Completed<br />By</th>
                                <th class="bg-black text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($reports): ?>
                                <?php foreach ($reports as $record): ?>
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
                                    <tr class="js-tr">
                                        <td style="vertical-align: middle;">
                                            <input type="checkbox" name="issues_ids[]" value="<?=$record["sid"]?>" />
                                        </td>
                                        <td class="vam">
                                            <label class="btn form-control"
                                                style="background: <?= $record["bg_color"]; ?>; color: <?= $record["txt_color"]; ?>; border-radius: 5px;">
                                                <?= is_numeric($record["level"]) ? " Severity Level ".$record["level"] : $record["level"] ?>
                                            </label>
                                        </td>
                                        <td class="vam">
                                            <a
                                                href="<?= base_url("compliance_safety_reporting/edit/" . $record["csp_reports_sid"]); ?>">
                                                <?= $record["title"]; ?>
                                            </a>
                                            <p class="text-small">
                                                Report Date: <?= formatDateToDB($record["report_date"], DB_DATE, DATE); ?>
                                            </p>
                                        </td>
                                        <td class="vam">
                                            <?= $record["compliance_incident_type_name"]; ?>
                                        </td>
                                        <td class="vam">
                                            <?= $record["item_title"]; ?>
                                        </td>
                                        <td class="vam">
                                            <label class="btn btn-<?= $statusClass ?> form-control" style="border-radius: 5px;">
                                                <?= $statusText; ?>
                                            </label>

                                        </td>
                                        <td class="vam">
                                            <?php if ($record["completion_status"] === "completed"): ?>
                                                <p class=""><?= remakeEmployeeName($record); ?></p>
                                                <p class="">
                                                    <?= formatDateToDB($record["completion_date"], DB_DATE, DATE); ?>
                                                </p>
                                            <?php endif; ?>
                                        </td>
                                        <td class="vam text-right">
                                            <a href="<?= base_url("compliance_safety_reporting/incident_item_management/" . $record["csp_reports_sid"] . "/" . $record["csp_reports_incidents_sid"] . "/" . $record["sid"]); ?>"
                                                class="btn btn-orange">
                                                <i class="fa fa-eye"></i>
                                                View Task
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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