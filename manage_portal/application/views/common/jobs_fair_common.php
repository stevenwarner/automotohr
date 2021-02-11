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
            <?php if ($video_type ==  'youtube') { ?>
                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $video_id; ?>"></iframe>
            <?php } else if ($video_type == 'vimeo') { ?>
                <iframe src="https://player.vimeo.com/video/<?php echo $video_id; ?>?title=0&byline=0&portrait=0&loop=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                <script src="https://player.vimeo.com/api/player.js"></script>
            <?php } elseif ($video_type == 'upload') { ?>
                <video controls>
                   <source src="<?php echo STORE_FULL_URL.'assets/uploaded_videos/'.$video_id; ?>" type='video/mp4'> 
                </video>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>