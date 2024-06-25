<!-- Video Section -->
<section class="video-section">
    <div class="embed-responsive embed-responsive-16by9">
        <?php if ($section_04_meta['video']) { ?>
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $section_04_meta['video']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <?php } else if ($section_04_meta['vimeo_video']) { ?>
            <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $section_04_meta['vimeo_video']; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <?php } else if ($section_04_meta['uploaded_video'] != '') { ?>
            <video controls width="100%">
                <source src="<?php echo UPLOADED_VIDEO_PATH . $section_04_meta['uploaded_video']; ?>" type='video/mp4'>
            </video>
        <?php } ?>
    </div>
</section>