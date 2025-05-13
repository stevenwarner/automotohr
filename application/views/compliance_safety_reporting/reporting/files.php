<div class="tab-pane <?= $this->input->get("tab", true) == "files" ? "active" : ""; ?>" id="tab-files" role="tabpanel">
    <!-- Documents -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>
                    <i class="fa fa-file-text text-orange"></i>
                    Manage Files
                </strong>
            </h1>
        </div>
        <div class="" id="images">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="document_title">Title <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control" id="document_title" name="document_title" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="file" class="hidden" id="report_documents" name="report_documents" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-orange jsAddDocument" type="button">
                    <i class="fa fa-plus"></i>
                    Add File
                </button>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>
                    <i class="fa fa-file-text text-orange"></i>
                    Attached Files
                </strong>
            </h1>
        </div>
        <div class="" id="manage-documents">
            <div class="panel-body jsDocumentsArea">
                <?php if ($report["documents"]): ?>
                    <div class="row jsFirst">
                        <?php foreach ($report["documents"] as $document): ?>
                            <?php
                            $style = '';
                            //
                            if ($document['file_type'] == 'image') {
                                $imageUrl = AWS_S3_BUCKET_URL . $document["s3_file_value"];
                                $style = "background-image: url('" . $imageUrl . "'); background-size: cover; background-repeat: no-repeat; background-position: center;";
                            }

                            ?>
                            <div class="col-sm-3">
                                <div class="widget-box">
                                    <div class="attachment-box full-width jsFileBox" style="<?= $style; ?>"
                                        data-id="<?= $document["sid"]; ?>">
                                        <h4 style="padding: 5px;" class="text-white">
                                            <?= $document["title"]; ?>
                                        </h4>
                                        <p style=" margin-left: 5px;" class="text-white">
                                            <small>

                                                <?= formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE); ?>
                                                <br>

                                                <?php
                                                if ($document['manual_email']) {
                                                    echo getManualUserNameByEmailId($reportId, $incidentId, $document['manual_email']);
                                                } else {
                                                    echo remakeEmployeeName($document);
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
                                                    <a target="_blank"
                                                        href="<?= base_url("compliance_safety_reporting/file/download/" . $document["sid"]); ?>"
                                                        class="btn btn-info btn-info">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    <?php if ($document["file_type"] != "link"): ?>
                                                        <button class="btn btn-danger jsDeleteFile"
                                                            data-file_id="<?= $document["sid"]; ?>"
                                                            data-file_type="<?= $document["file_type"]; ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($report["audios"]): ?>
                    <div class="row jsFirst">
                        <?php foreach ($report["audios"] as $file): ?>
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
                                                        <a target="_blank"
                                                            href="<?= base_url("compliance_safety_reporting/file/download/" . $file["sid"]); ?>"
                                                            class="btn btn-info btn-info">
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>