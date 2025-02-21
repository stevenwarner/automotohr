<div class="container">
    <div class="row">
        <div class="col-sm-12 center">
            <?php if ($file["file_type"] === "image") : ?>
                <img src="<?= AWS_S3_BUCKET_URL . $file["s3_file_value"]; ?>" alt="<?= $file["file_value"] ?>" />
            <?php elseif ($file["file_type"] === "video"): ?>
                <video style="width: 100%;" controls>
                    <source src="<?= AWS_S3_BUCKET_URL . $file["s3_file_value"]; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php elseif ($file["file_type"] === "audio"): ?>
                <audio controls>
                    <source src="<?= AWS_S3_BUCKET_URL . $file["s3_file_value"]; ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            <?php elseif ($file["file_type"] === "document"): ?>
                <?php if (preg_match("/doc|docx|xls|xlsx|ppt|pptx/i", $file["s3_file_value"])) : ?>
                    <iframe style="width: 100%; height: 700px;" src="https://view.officeapps.live.com/op/embed.aspx?src=<?= urlencode(AWS_S3_BUCKET_URL . $file["s3_file_value"]); ?>" frameborder="0"></iframe>
                <?php else: ?>
                    <iframe style="width: 100%; height: 700px;" src="<?= (AWS_S3_BUCKET_URL . $file["s3_file_value"]); ?>" frameborder="0"></iframe>
                <?php endif; ?>
            <?php elseif ($file["file_type"] === "link"): ?>
                <iframe style="width: 100%; height: 700px;" src="<?= ($file["s3_file_value"]); ?>" frameborder="0"></iframe>
            <?php endif; ?>
        </div>
    </div>
</div>