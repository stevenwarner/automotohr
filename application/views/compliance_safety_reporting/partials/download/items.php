<!-- Incident Item Section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="3">
                <strong>Incident Item(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($incidentItemsSelected)) { _e($incidentItemsSelected,true); ?>
            <?php foreach ($incidentItemsSelected as $item) { ?>
                <tr>
                    <td>
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
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Incident Item Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>    
    </tbody>
</table>
<!-- Incident Item Section End -->