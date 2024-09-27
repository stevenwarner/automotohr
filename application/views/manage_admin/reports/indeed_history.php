<div class="container">
    <?php if ($records) : ?>
        <div class="panel panel-success">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Job Title</th>
                                <th class="text-right" scope="col">Source Posting Id</th>
                                <th class="text-right" scope="col">Tracking Key</th>
                                <th class="text-right" scope="col">Type</th>
                                <th class="text-right" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $companyCache = []; ?>
                            <?php foreach ($records as $v0):
                                // check and add company name to cache
                                $companyCache[$v0["user_sid"]] =
                                    $companyCache[$v0["user_sid"]] ?? getCompanyColumnById($v0["user_sid"], "CompanyName")["CompanyName"];
                            ?>
                                <tr data-id="<?= $v0["sid"]; ?>" data-logid="<?= $v0["log_sid"]; ?>" data-jobid="<?= $v0["job_sid"]; ?>">
                                    <td>
                                        <strong>
                                            <?= $v0["Title"]; ?>
                                        </strong>
                                        <p>Company: <?= $companyCache[$v0["user_sid"]]; ?></p>
                                    </td>
                                    <td class="text-right">
                                        <?= $v0["indeed_posting_id"] ?? "-"; ?>
                                    </td>
                                    <td class="text-right">
                                        <?= $v0["tracking_key"] ?? "-"; ?>
                                    </td>
                                    <td class="text-right">
                                        <strong>
                                            <?= $v0["is_expired"] ? "EXPIRED" : "NEW"; ?>
                                        </strong>
                                    </td>
                                    <td class="text-right">
                                        <?php if ($v0["is_processed"]) : ?>
                                            <strong>PROCESSED</strong>
                                            <br>
                                            <?= formatDateToDB($v0["processed_at"], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                        <?php elseif ($v0["is_processing"] == 0) : ?>
                                            <strong>PENDING</strong>
                                        <?php elseif ($v0["is_processing"] == 1 && $v0["has_errors"]) : ?>
                                            <strong>ERRORS</strong>
                                        <?php elseif ($v0["is_processing"] == 1) : ?>
                                            <strong>PROCESSING</strong>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <strong>
                History not found.
            </strong>
        </div>
    <?php endif; ?>
</div>