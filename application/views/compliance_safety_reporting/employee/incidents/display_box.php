<div class="col-sm-4">
    <article class="article-sec" style="padding: 0 10px 10px;overflow-x: hidden">
        <h1>
            <?= $display_box_data["compliance_incident_type_name"]; ?>
            <br>
            <small>(Incident)</small>
        </h1>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <p class="csColumSection"><strong>Status</strong></p>
                <p class="text-small">
                    <?= ucwords($display_box_data["status"]); ?>
                </p>
            </div>
            <div class="col-md-6 col-xs-12">
                <p class="csColumSection"><strong>Completion Date</strong></p>
                <p>
                    <?= $display_box_data["completion_date"]
                        ? formatDateToDB(
                            $display_box_data["completion_date"],
                            DB_DATE,
                            DATE
                        )
                        : "-"; ?>
                </p>
            </div>
        </div>
        <div class="row">
            <hr>
            <div class="col-md-12 col-xs-12 text-center">
                <a class="btn btn-orange" href="<?= base_url("compliance_safety_reporting/report/" . $display_box_data["reportId"]) . "/incident/edit/" . $display_box_data["sid"]; ?>">
                    <i class="fa fa-pencil"></i>
                    Edit
                </a>
                <a class="btn btn-black" target="_blank" href="<?= base_url("compliance_safety_reporting/download_incident/" . $display_box_data["reportId"] . '/' .$display_box_data["sid"]); ?>">
                    <i class="fa fa-download"></i>
                    Download
                </a>
            </div>
        </div>
    </article>
</div>