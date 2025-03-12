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
                    <div class="col-sm-10 jsCSPItemDescription">
                        <?= convertCSPTags($item["description"], $decodedJSON ?? []); ?>
                    </div>
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