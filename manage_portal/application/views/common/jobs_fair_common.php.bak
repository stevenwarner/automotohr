<div class="text-column">
    <div class="text-block">
        <h2 class="section-title color">
            <?php echo $heading_title; ?>
        </h2>
        <?php echo $content; ?>
    </div>
    <?php if ($picture_or_video == 'picture' && $banner_image != NULL) { ?>
    <div class="talent-network-image">
        <div class="img-thumbnail">
            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $banner_image; ?>" alt="">
        </div>
    </div>
    <?php } ?>
    <?php if ($picture_or_video == 'video' && $video_id != NULL) { ?>
    <div class="talent-network-video">
        <div class="embed-responsive embed-responsive-4by3">
            <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $video_id; ?>"></iframe>
        </div>
    </div>
    <?php } ?>
</div>