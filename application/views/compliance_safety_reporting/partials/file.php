<?php 
    $style = '';
    //
    if ($document['file_type'] == 'image') {
        $imageUrl = AWS_S3_BUCKET_URL . $document["s3_file_value"]; 
        $style = "background-image: url('".$imageUrl."'); background-size: cover; background-repeat: no-repeat; background-position: center;";
    }
    
?>
<div class="col-sm-3">
    <div class="widget-box">
        <div class="attachment-box full-width jsFileBox" style="<?= $style; ?>" data-id="<?= $document["sid"]; ?>">
            <h4 style="padding: 5px;" class="text-white">
                <?= $document["title"]; ?>
            </h4>
            <p style=" margin-left: 5px;" class="text-white">
                <small>

                    <?= formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE); ?>
                    <br>

                    <?= remakeEmployeeName($document); ?>
                </small>
            </p>
            <div class="status-panel">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <button class="btn btn-info jsViewFile">
                            <i class="fa fa-eye"></i>
                        </button>
                        <?php if ($document["file_type"] != "link"): ?>

                            <a target="_blank" href="<?= base_url(($public ? "csp" : "compliance_safety_reporting") ."/file/download/" . $document["sid"]); ?>" class="btn btn-info btn-info">
                                <i class="fa fa-download"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>