<div class="text-column">
    <div class="text-block">
        <h2 class="section-title color">
            <?php   if (isset($talent_data['title']) && $talent_data['title'] != '' && $talent_data['title'] != NULL) {
                        echo $talent_data['title'];
                    } else { ?>
                Why Join Our Talent Network?
            <?php } ?>
        </h2>
        <?php   if (isset($talent_data['content']) && $talent_data['content'] != '' && $talent_data['content'] != NULL) {
                    echo $talent_data['content'];
                } else { ?>
                    <p>Joining our Talent Network will enhance your job search and application process.  Whether you choose to apply or just leave your information, we look forward to staying connected with you. </p>
                    <ul>
                        <li>Receive alerts with new job opportunities that match your interests</li>
                        <li>Receive relevant communications and updates from our organization</li>
                        <li>Share job opportunities with family and friends through Social Media or email</li>
                    </ul>
        <?php   } ?>
    </div>
    <?php if (isset($talent_data['picture_or_video']) && $talent_data['picture_or_video'] == 'picture') { ?>
    <div class="talent-network-image">
        <div class="img-thumbnail">
            <?php if (isset($talent_data['picture'])) { ?>
                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $talent_data['picture']; ?>" alt="">
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <?php if (isset($talent_data['picture_or_video']) && $talent_data['picture_or_video'] == 'video') { ?>
    <div class="talent-network-video">
        <div class="embed-responsive embed-responsive-4by3">
            <?php if (isset($talent_data['youtube_link'])) { ?>
                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $talent_data['youtube_link']; ?>"></iframe>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>