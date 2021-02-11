<div class="embed-responsive embed-responsive-16by9">
    <?php if($job_details['video_source'] == 'youtube') { ?>
        <iframe src="//www.youtube.com/embed/<?php echo $job_details['YouTube_Video']; ?>"></iframe>
    <?php } else { ?>
        <iframe src="https://player.vimeo.com/video/<?php echo $job_details['YouTube_Video']; ?>?title=0&byline=0&portrait=0&loop=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <script src="https://player.vimeo.com/api/player.js"></script>
    <?php } ?>
</div>