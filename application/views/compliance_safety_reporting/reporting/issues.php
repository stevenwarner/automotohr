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
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="bg-black">Severity<br />Level</th>
                            <th class="bg-black">Incident</th>
                            <th class="bg-black">Issue</th>
                            <th class="bg-black">Status</th>
                            <th class="bg-black">Completed<br />By</th>
                            <th class="bg-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                // $severityLevelGraph["data"][$record["level"]] = $record["bg_color"];
                                ?>
                                <tr data-id="<?= $record["sid"]; ?>">
                                    <td class="vam">
                                        <label class="btn form-control"
                                            style="background: <?= $record["bg_color"]; ?>; color: <?= $record["txt_color"]; ?>; border-radius: 5px;">
                                            Severity Level <?= $record["level"]; ?>
                                        </label>
                                    </td>
                                    <td class="vam">
                                        <?= $record["compliance_incident_type_name"]; ?>
                                    </td>
                                    <td class="vam">
                                        <?= $record["title"]; ?>
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
                                        <button class="btn btn-warning jsEditIssue" type="button">
                                            <i class="fa fa-edit"></i>
                                            Edit Issue
                                        </button>
                                        <a href="<?= base_url("compliance_safety_reporting/incident_item_management/" . $report["sid"] . "/" . $record["csp_reports_incidents_sid"] . "/" . $record["sid"]); ?>"
                                            class="btn btn-orange">
                                            <i class="fa fa-eye"></i>
                                            Manage Task
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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