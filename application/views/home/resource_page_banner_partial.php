<div class="page-banner 4">
    <?php if($page_data['page_banner_type'] == 'video') { ?>
        <figure>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $page_data['page_banner_video']; ?>/?autoplay=1&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1"></iframe>
            </div>
            <!--<figcaption>
                <h2><?php /*echo $page_data['header_text']; */?></h2>
                <h4><?php /*echo $page_data['header_sub_text']; */?></h4>
            </figcaption>-->
        </figure>
        <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i class="fa fa-volume-off"></i></button>
    <?php } else if ($page_data['page_banner_type'] == 'image') { ?>
        <figure>
            <img data-parallax='{"z": 200}' src="<?php echo AWS_S3_BUCKET_URL . $page_data['page_banner_image']; ?>" alt="">
            <!--<figcaption>
                <h2><?php /*echo $page_data['header_text']; */?></h2>
                <h4><?php /*echo $page_data['header_sub_text']; */?></h4>
            </figcaption>-->
        </figure>
    <?php } ?>
</div>