<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Manage Audio/Video</strong>
        </h1>
    </div>
    <div class="panel-body jsAudioArea">
        <?php if ($report["audios"]) : ?>
            <div class="row jsFirst">
                <?php foreach ($report["audios"] as $file) : ?>
                    <div class="col-sm-3">
                        <div class="widget-box">
                            <div class="attachment-box full-width jsFileBox" data-id="<?= $file["sid"]; ?>">
                                <h4 style="padding: 5px;" class="text-white">
                                    <?= $file["title"]; ?>
                                </h4>
                                <p style=" margin-left: 5px;" class="text-white">
                                    <small>

                                        <?= formatDateToDB($file['created_at'], DB_DATE_WITH_TIME, DATE); ?>
                                        <br>

                                        <?php
                                            if ($file['manual_email']) {
                                                echo getManualUserNameByEmailId($reportId, $incidentId, $file['manual_email']);
                                            } else {
                                                echo remakeEmployeeName($file);
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
                                                <a target="_blank" href="<?= base_url("compliance_safety_reporting/file/download/" . $file["sid"]); ?>" class="btn btn-info btn-info">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class=" alert alert-info text-center">
                No files found.
            </div>
        <?php endif; ?>
    </div>
</div>