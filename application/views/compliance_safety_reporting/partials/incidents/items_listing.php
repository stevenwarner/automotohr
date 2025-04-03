<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Checklist</strong>
        </h1>
    </div>
    <div class="panel-body">
        <?php if ($report["incidentItemsSelected"]) : ?>
            <?php $i = 1; ?>
            <?php foreach ($report["incidentItemsSelected"] as $k0 => $item) : ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium" style="padding: 14px 0px;">
                            <strong>Item <?php echo $i; $i++;?></strong>
                            <span class="pull-right">
                                <a type="button" class="btn btn-orange" href="<?= $manageItemUrl.'/'.$item["sid"]; ?>">
                                    <i class="fa fa-info"></i>
                                    Manage Item
                                </a>
                            </span>
                        </h1>
                    </div>
                    <div class="panel-body">
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
                            <div class="col-sm-3">
                                <div class="csLabelPill jsSelectedLabelPill text-center"
                                    style="background-color: <?= $level["bg_color"]; ?>; 
                                color: <?= $level["txt_color"]; ?>;">Severity Level <?= $level["level"]; ?></div>
                            </div>
                            <div class="col-sm-9 jsCSPItemDescription">
                                <?= convertCSPTags($item["description"], $decodedJSON ?? []); ?>
                            </div>
                            <?php if ($item["attachments"]) { ?>
                                <hr>
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

                                                                <?php
                                                                    if ($attachFile['manual_email'] && filter_var($attachFile['manual_email'], FILTER_VALIDATE_EMAIL)) {
                                                                        echo getManualUserNameByEmailId($reportId, $incidentId, $attachFile['manual_email']);
                                                                    } else {
                                                                        echo getUserNameBySID($attachFile['created_by']);
                                                                    }
                                                                ?>
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
                    </div>
                </div>        
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