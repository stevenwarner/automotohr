    <?php $firstSegment = $this->uri->segment(1); ?>

    <div class="col-sm-4">
        <article class="article-sec" style="padding: 0 10px 10px;overflow-x: hidden">
            <h1>
                <?= $display_box_data["title"]; ?>
                <br>
                <small>(<?= $display_box_data["compliance_report_name"]; ?>)</small>
            </h1>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <p class="csColumSection"><strong>Report Date</strong></p>
                    <p class="text-small">
                        <?= formatDateToDB(
                            $display_box_data["report_date"],
                            DB_DATE,
                            DATE
                        ); ?>
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
                <div class="col-md-12 col-xs-12 text-center">
                    <select class="form-control">
                        <?php if ($display_box_data["incidents"]): ?>
                            <?php foreach ($display_box_data["incidents"] as $v1): ?>
                                <option value=""><?= $v1["compliance_incident_type_name"]; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <hr>
                <div class="col-md-12 col-xs-12 text-center">
                    <a class="btn btn-orange" href="<?= base_url("{$firstSegment}/edit/" . $display_box_data["sid"]); ?>">
                        <i class="fa fa-pencil"></i>
                        Edit
                    </a>
                    <a class="btn btn-red" href="<?= base_url("{$firstSegment}/report/" . $display_box_data["sid"] . "/incidents"); ?>">
                        <i class="fa fa-eye"></i>
                        Incidents
                    </a>
                </div>
            </div>
        </article>
    </div>