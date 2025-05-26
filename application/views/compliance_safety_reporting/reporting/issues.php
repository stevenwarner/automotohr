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
<!-- Issues -->
<div class="tab-pane <?= $this->input->get("tab", true) == "issues" ? "active" : ""; ?>" id="tab-issues"
    role="tabpanel">

    <div class="row">
        <div class="col-md-4">
            <div id="jsProgressGraph"></div>
        </div>
        <div class="col-md-8">

            <div id="jsSeverityGraph"></div>
        </div>
    </div>
    <hr>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="panel-heading-text text-medium">
                        <i class="fa fa-plus-circle text-orange"></i>
                        <strong>Reported Issues</strong>
                    </h1>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-orange jsAddAddIssueBtn">
                        <i class="fa fa-plus-circle"></i>
                        Report a New Issue
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php if ($report["issuesWithIncident"]): ?>
        <?php foreach ($report["issuesWithIncident"] as $record): ?>
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
                <div class="panel <?= $record["completion_status"] === "completed" ? "panel-success" : "panel-default " ?>">
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

                            <div class="col-xs-12 text-right">
                                <?php if ($record["completion_status"] != "completed"): ?>
                                    <button class="btn btn-green jsMarkIssueDone" data-issue_id="<?= $record["sid"]; ?>"
                                        style="border-radius: 5px;" title="Mark As Done">
                                        <i class="fa fa-check-circle"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-black jsIssueUploadFileBtn" title="Upload files"
                                    data-report_id="<?= $report["sid"]; ?>"
                                    data-incident_id="<?= $record["csp_reports_incidents_sid"]; ?>"
                                    data-issue_id="<?= $record["sid"]; ?>">
                                    <i class="fa fa-upload"></i>
                                </button>
                                <a href="<?= base_url("compliance_safety_reporting/incident_item_management/" . $report["sid"] . "/" . $record["csp_reports_incidents_sid"] . "/" . $record["sid"]); ?>"
                                    class="btn btn-orange">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <button class="btn btn-warning jsEditIssue" data-id="<?= $record["sid"]; ?>" type="button"
                                    style="border-radius: 5px;">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-red jsDeleteIssue" type="button" data-issue_id="<?= $record['sid'] ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
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
                                <a href="<?= base_url("compliance_safety_reporting/edit/" . $report["sid"]); ?>">
                                    <?= $report["title"]; ?>
                                </a>
                                (<?= formatDateToDB($report["report_date"], DB_DATE, DATE); ?>)
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
                                    <label class="btn btn-<?= $statusClass ?>" style="border-radius: 5px;">
                                        <?= $statusText; ?>
                                    </label>
                                    <?php if ($record["completion_status"] === "completed"): ?>
                                        <?= checkAndShowUser($record["completed_by"], $record); ?>
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
                                    $record["answers_json"] ? json_decode($record["answers_json"], true) : [],
                                    true
                                ); ?>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="jsFilesArea<?= $record["sid"]; ?>">

                                    <?php $this->load->view("compliance_safety_reporting/files", $record); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>

    <?php endif; ?>
</div>
<?php $severityLevelGraph["data"] = array_values($severityLevelGraph["data"]); ?>
<script>
    progressGraphData = '<?= json_encode(array_values($progressGraphData)); ?>';
    severityLevelGraph = '<?= json_encode(($severityLevelGraph)); ?>';
    progressGraphColors = '<?= json_encode((["#f0ad4e", "#5cb85c", "#c10000"])); ?>';
</script>