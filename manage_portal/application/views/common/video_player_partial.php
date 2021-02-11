<div class="embed-responsive embed-responsive-16by9">
    <?php if($job_details['video_source'] == 'youtube') { ?>
        <iframe src="//www.youtube.com/embed/<?php echo $job_details['YouTube_Video']; ?>"></iframe>
    <?php } else if ($job_details['video_source'] == 'vimeo') { ?>
        <iframe src="https://player.vimeo.com/video/<?php echo $job_details['YouTube_Video']; ?>?title=0&byline=0&portrait=0&loop=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <script src="https://player.vimeo.com/api/player.js"></script>
    <?php } else { ?>
    	<video controls>
            <source src="<?php echo STORE_FULL_URL.'assets/uploaded_videos/'.$job_details['YouTube_Video']; ?>" type='video/mp4'>
        </video>    
    <?php } ?>
</div>