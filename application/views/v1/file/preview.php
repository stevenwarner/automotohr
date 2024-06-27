<?php
$url = '';
// for images
if (in_array($ext, ["png", "jpg", "jpeg", "gif"])) {
    $url = '<img src="' . (AWS_S3_BUCKET_URL . $key) . '" style="width: 100%;"/>';
} elseif (in_array($ext, ["mp4", "mov"])) { // for videos
    $url = '<video style="width: 100%" controls>
                <source src="' . (AWS_S3_BUCKET_URL . $key) . '" type="video/mp4">
                Your browser does not support the video tag.
            </video>';
} elseif (in_array($ext, ["doc", "docx", "ppt", "pptx", "xls", "xlsx"])) {
    $url = '<iframe class="jsDocumentPreviewFrame" style="width: 100%; height: 700px;" src="https://view.officeapps.live.com/op/view.aspx?src=' . (AWS_S3_BUCKET_URL . $key) . '"></iframe>';
} else {
    $url = '<iframe class="jsDocumentPreviewFrame" style="width: 100%; height: 700px;" src="https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $key) . '&embedded=true"></iframe>';
}
?>

<br />
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?= $url; ?>
        </div>
    </div>

    <hr />
    <div class="row">
        <div class="col-sm-12 text-right">
            <a href="<?= base_url('download/file/' . ($key) . ''); ?>" class="btn btn-success csF16">
                <i class="fa fa-download csF16" aria-hidden="true"></i>
                &nbsp;Download
            </a>
        </div>
    </div>
</div>