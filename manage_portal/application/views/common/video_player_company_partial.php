<div class="embed-responsive embed-responsive-16by9">
    <?php if($company_details['video_source'] == 'youtube') { ?>
        <iframe src="//www.youtube.com/embed/<?php echo $company_details['YouTubeVideo']; ?>"></iframe>
    <?php } else if ($company_details['video_source'] == 'vimeo') { ?>
        <iframe src="https://player.vimeo.com/video/<?php echo $company_details['YouTubeVideo']; ?>?title=0&byline=0&portrait=0&loop=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <script src="https://player.vimeo.com/api/player.js"></script>
    <?php } else { ?>
    	<video controls>
            <source src="<?php echo STORE_FULL_URL.'assets/uploaded_videos/'.$company_details['YouTubeVideo']; ?>" type='video/mp4'>
        </video>
   	<?php } ?> 	
</div>