<div class="row">
    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
        <?php if(!empty($section) && isset($section)) { ?>
            <div id="<?php echo $section['section_unique_id']; ?>" class="section welcone-video-box full-width">
                <h1 class="text-blue"><?php echo $section['section_title']; ?></h1>
                <?php if(!empty($section['section_video'])) { ?>
                    <?php if($section['section_video_source'] == 'youtube') { ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $section['section_video']; ?>"></iframe>
                        </div>
                    <?php } else { ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe src="https://player.vimeo.com/video/<?php echo $section['section_video']; ?>" width="640" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                        </div>
                    <?php } ?>
                <?php } else if(!empty($section['section_image'])) { ?>
                    <div class="img-thumbnail">
                        <img src="<?php echo AWS_S3_BUCKET_URL . $section['section_image']; ?>" class="img-responsive" />
                    </div>
                <?php } ?>
                <?php if(!empty($section['section_content'])) { ?>
                    <div class="text-justify">
                        <?php echo html_entity_decode($section['section_content']); ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
</div>