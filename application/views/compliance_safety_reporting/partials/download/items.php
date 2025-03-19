<!-- Incident Item Section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="2">
                <strong>Incident Item(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($incidentItemsSelected)) { ?>
            <tr>
                <th class="text-center">Severity Level</th>
                <th class="text-center">Description</th>
            </tr>
            <?php foreach ($incidentItemsSelected as $item) { ?>
                <tr>
                    <?php
                        //
                        $level = $severityStatus[$item["severity_level_sid"]];
                        //
                        $decodedJSON = json_decode(
                            $item["answers_json"],
                            true
                        );
                    ?>
                    <td>
                        <div class="csLabelPill jsSelectedLabelPill text-center"
                            style="background-color: <?= $level["bg_color"]; ?>; 
                        color: <?= $level["txt_color"]; ?>;">Severity Level <?= $level["level"]; ?></div>
                    </td>
                    <td>
                        <div class="col-sm-10 jsCSPItemDescription">
                            <?= convertCSPTags($item["description"], $decodedJSON ?? []); ?>
                        </div>
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