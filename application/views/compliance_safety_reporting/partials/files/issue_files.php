<!-- Manage Documents -->
<div class="wrapper-outer">
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Attached Issue Files</strong>
        </h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php if ($files) { ?>
                <hr>
                <div class="col-sm-12">
                    <div class="row jsFirst">
                        <?php foreach ($files as $attachFile) { ?>
                            <?php
                            $style = '';
                            //
                            if ($attachFile['file_type'] == 'image') {
                                $imageUrl = AWS_S3_BUCKET_URL . $attachFile["s3_file_value"];
                                $style = "background-image: url('" . $imageUrl . "'); background-size: cover; background-repeat: no-repeat; background-position: center;";
                            }

                            ?>
                            <div class="col-sm-4">
                                <div class="widget-box">
                                    <div class="attachment-box full-width jsFileBox" style="<?= $style; ?>" data-id="<?= $attachFile["sid"]; ?>">
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

                                                    <button class="btn btn-info jsDeleteFile" data-file_id="<?php echo $attachFile["sid"]; ?>" data-file_type="<?php echo $attachFile['file_type']; ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
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
</div>