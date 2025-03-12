<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Manage Incident Checklist</strong>
        </h1>
    </div>
    <div class="panel-body">
        <p class="alert alert-danger">
            <strong>
                Instructions: To add items to the incident, click the checkbox.
            </strong>
        </p>
        <?php if ($report["incident_items"]) : ?>
            <?php $selectedIds = array_column($report["incidentItemsSelected"], "compliance_report_incident_types_sid"); ?>
            <?php foreach ($report["incident_items"] as $k0 => $item) : ?>
                <?php $cspRecord = $report["incidentItemsSelected"][$item["sid"]] ?? []; ?>
                <?php if ($cspRecord) {
                    $decodedJSON = json_decode(
                        $cspRecord["answers_json"],
                        true
                    );
                } ?>
                <div class="row jsIncidentItemRow" style="padding-top: 10px; padding-bottom: 10px;background-color: <?= $k0 % 2 == 0 ? '#f2f2f2' : '#f1f1f1'; ?>">
                    <div class="col-md-1">
                        <label class="control control--checkbox">
                            <input type="checkbox" name="jsIncidentType" class="jsIncidentType" <?= in_array($item["sid"], $selectedIds) ? "checked" : ""; ?> value="<?= $item["sid"]; ?>" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-md-7">
                        <?= $cspRecord["description"]
                            ? convertCSPTags($cspRecord["description"], $decodedJSON ?? [])
                            : $item["description"];
                        ?>
                    </div>
                    <div class="col-sm-4" style="vertical-align: middle">
                        <div class="candidate-status">
                            <input type="hidden" name="jsIncidentSeverityLevel" class="jsIncidentSeverityLevel" value="<?= $cspRecord["severity_level_sid"] ? $cspRecord["severity_level_sid"] : $severity_status[1]["sid"]; ?>" />
                            <div class="label-wrapper-outer">
                                <div class="row">
                                    <div class="col-xs-10 jsSelectedPill">
                                        <?php if ($cspRecord["severity_level_sid"]): ?>
                                            <div data-id="<?= $cspRecord["severity_level_sid"]; ?>" class="csLabelPill jsSelectedLabelPill text-center" style="background-color: <?= $severity_status[$cspRecord["severity_level_sid"]]["bg_color"]; ?>; color: <?= $severity_status[$cspRecord["severity_level_sid"]]["txt_color"]; ?>;">Severity Level <?= $severity_status[$cspRecord["severity_level_sid"]]["level"]; ?></div>
                                        <?php else: ?>
                                            <div data-id="<?= $severity_status[1]["sid"]; ?>" class="csLabelPill jsSelectedLabelPill text-center" style="background-color: <?= $severity_status[1]["bg_color"]; ?>; color: <?= $severity_status[1]["txt_color"]; ?>;">Severity Level <?= $severity_status[1]["level"]; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-xs-2 text-left">
                                        <div class="btn btn-orange show-status-box">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Selected one -->
                                <div class="lable-wrapper">
                                    <div style="height:20px;">
                                        <i class="fa fa-times cross"></i>
                                    </div>

                                    <?php if ($severity_status) : ?>
                                        <?php foreach ($severity_status as $v1): ?>
                                            <div class="row">
                                                <div data-id="<?= $v1["sid"]; ?>" class="col-sm-12 label applicant csLabelPill" style="background-color:<?= $v1["bg_color"]; ?>; color:<?= $v1["txt_color"]; ?>;">
                                                    <div class="jsSeverityLevelText">Severity Level <?= $v1["level"]; ?></div>
                                                    <i class="fa fa-check-square check"></i>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="panel-footer text-right">
        <button type="button" class="btn btn-orange jsUpdateItems">
            <i class="fa fa-edit"></i>
            Update Checklist
        </button>
    </div>
</div>


<style>
    .candidate-status {
        width: 100%;
        height: 50px;
    }

    .label-wrapper-outer {
        width: 100%;
        position: relative;
    }

    .candidate-status .lable-wrapper {
        top: 40px;
        border-radius: 5px;
        padding: 20px;
        width: 100%;
    }

    .lable-wrapper {
        display: none;
        background-color: white;
        padding: 20px;
        padding-top: 0;
        box-shadow: 0px 0px 6px #888888;
        right: 0;
        position: absolute;
        top: 30px;
        z-index: 999;
    }

    .label.csLabelPill {
        display: block !important;
    }

    .csLabelPill {
        font-family: arial;
        font-weight: bold;
        padding: 6px;
        font-size: 13px;
        margin-bottom: 3px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    .candidate-status .label {
        height: 30px;
        line-height: 24px;
        font-style: italic;
        font-size: 13px;
        font-weight: 600;
    }

    .candidate-status .fa.fa-times.cross {
        background-color: #000;
        border-radius: 100%;
        color: #fff;
        font-size: 9px;
        height: 20px;
        line-height: 19px;
        padding: 0;
        position: absolute;
        right: 20px;
        text-align: center;
        top: 10px;
        width: 20px;
    }
</style>