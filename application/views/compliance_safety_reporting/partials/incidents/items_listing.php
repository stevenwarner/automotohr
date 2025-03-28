<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Checklist</strong>
        </h1>
    </div>
    <div class="panel-body">
        <?php if ($report["incidentItemsSelected"]) : ?>
            <?php foreach ($report["incidentItemsSelected"] as $k0 => $item) : ?>
                <?php
                //
                $level = $severity_status[$item["severity_level_sid"]];
                //
                $decodedJSON = json_decode(
                    $item["answers_json"],
                    true
                );
                ?>
                <div class="row jsCSPItemListingRow" data-id="<?= $item["sid"]; ?>">
                    <div class="col-sm-2">
                        <div class="csLabelPill jsSelectedLabelPill text-center"
                            style="background-color: <?= $level["bg_color"]; ?>; 
                        color: <?= $level["txt_color"]; ?>;">Severity Level <?= $level["level"]; ?></div>
                    </div>
                    <div class="col-sm-9 jsCSPItemDescription">
                        <?= convertCSPTags($item["description"], $decodedJSON ?? []); ?>
                    </div>
                    
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-orange jsCSPItemAttachmentBtn" data-item_id="<?= $item["sid"]; ?>"  data-report_id="<?= $reportId; ?>" data-incident_id="<?= $incidentId; ?>">
                            <i class="fa fa-plus"></i>
                            Add File
                        </button>
                    </div>
                    <?php if ($item["attachments"]) { ?>
                        <div class="col-sm-12">
                            <div class="row jsFirst">
                                <?php foreach ($item["attachments"] as $attachFile) { ?>
                                    <div class="col-sm-3">
                                        <div class="widget-box">
                                            <div class="attachment-box full-width jsFileBox" data-id="<?= $attachFile["sid"]; ?>">
                                                <h4 style="padding: 5px;" class="text-white">
                                                    <?= $attachFile["title"]; ?>
                                                </h4>
                                                <p style=" margin-left: 5px;" class="text-white">
                                                    <small>

                                                        <?= formatDateToDB($attachFile['created_at'], DB_DATE_WITH_TIME, DATE); ?>
                                                        <br>

                                                        <?= getUserNameBySID($attachFile['created_by']); ?>
                                                    </small>
                                                </p>
                                                <div class="status-panel">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <button class="btn btn-info jsViewFile">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <?php
                                                            if ($file["file_type"] != "link"): ?>
                                                                <a target="_blank" href="<?= base_url("compliance_safety_reporting/file/download/" . $attachFile["sid"]); ?>" class="btn btn-info btn-info">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>  
                        </div>
                    <?php } ?>    
                </div>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="panel-footer text-right">
        <button type="button" class="btn btn-orange jsCSPItemListingBtn">
            <i class="fa fa-edit"></i>
            Update Incident Items
        </button>
    </div>
</div>