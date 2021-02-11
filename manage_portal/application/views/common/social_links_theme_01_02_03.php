<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
    <div class="social-links">
        <ul>
            <?php if($enable_facebook_footer==1) { ?>
                <li><a class="facebook" href="<?php echo $facebook_footer; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
            <?php } if($enable_twitter_footer==1){?>
                <li><a class="twitter" href="<?php echo $twitter_footer; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
            <?php } if($enable_google_footer==1){?>
                <li><a class="google-plus" href="<?php echo $google_footer; ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
            <?php } if($enable_linkedin_footer==1){?>
                <li><a class="linkedin" href="<?php echo $linkedin_footer; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
            <?php } if($enable_youtube_footer == 1) { ?>
                <li><a class="youtube"  href="<?php echo $youtube_footer; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
            <?php } if ($enable_instagram_footer == 1) { ?>
                <li><a class="instagram"  href="<?php echo $instagram_footer; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
            <?php } if($enable_glassdoor_footer == 1) {?>
                <li><a class="glassdoor"  href="<?php echo $glassdoor_footer; ?>" target="_blank"><img src="<?php echo base_url('assets/theme-1/images/glassdoor.png'); ?>"></a></li>
            <?php } ?>
        </ul>
    </div>
</div>