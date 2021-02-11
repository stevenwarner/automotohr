<!-- Main Slider -->
<?php if(strtoupper($pageName) == 'HOME'){ ?>
    <section class="main-slider text-center">
        <?php if($section_01_meta['show_video_or_image'] == 'video') {?>
            <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i class="fa fa-volume-off"></i></button>
        <?php } ?>
        <div class="head-overlay">
            <?php if(isset($section_01_meta['show_video_or_image']) && $section_01_meta['show_video_or_image'] == 'image' ){ ?>
                <img src="<?php echo AWS_S3_BUCKET_URL . (isset($section_01_meta['image']) && $section_01_meta['image'] != '' ? $section_01_meta['image'] : '' ); ?>"/>
            <?php } else if($section_01_meta['show_video_or_image'] == 'video') {?>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo (isset($section_01_meta['video']) && $section_01_meta['video'] != '' ? $section_01_meta['video'] : '' );?>/?autoplay=1&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1"></iframe>
                </div>
            <?php } ?>
        </div>
        <div class="slider-text">
            <div class="slide-inner">
                <h2 class="slider-title"><?php echo (isset($section_01_meta['image']) && $section_01_meta['title'] != '' ? $section_01_meta['title'] : '' ); ?></h2>
                <p class="slide-description"><?php echo (isset($section_01_meta['image']) && $section_01_meta['tag_line'] != '' ? $section_01_meta['tag_line'] : '' ); ?></p>
            </div>
        </div>
    </section>
    <div class="down_btn"> </div>
<?php } else if(strtoupper($pageName) == 'JOBS') { ?>
    <?php if(!empty($jobs_page_banner)){ ?>
        <div class="comapny-video">
            <img src="<?php echo AWS_S3_BUCKET_URL . $jobs_page_banner['jobs_page_banner']; ?>">
        </div>
        <div class="down_btn"> </div>
    <?php } ?>
<?php } else if(strtoupper($pageName) == 'JOB_DETAILS') { ?>
    <!--<div class="job-detail-banner" style="<?php echo !empty($jobs_detail_page_banner_data) ? 'background: url('.AWS_S3_BUCKET_URL . $jobs_detail_page_banner_data['jobs_detail_page_banner'].')' : ''?>">-->
    <div class="<?php echo !empty($jobs_detail_page_banner_data) ? '' : 'job-detail-banner'?>">
        <?php if(!empty($jobs_detail_page_banner_data)){ ?>
            <div class="comapny-video">
                <img src="<?php echo AWS_S3_BUCKET_URL . $jobs_detail_page_banner_data['jobs_detail_page_banner']; ?>">
            </div>
            <div class="down_btn"> </div>
        <?php } ?>
        <div class="container">
            <div class="<?php echo !empty($jobs_detail_page_banner_data) ? 'detail-banner-caption' : 'detail-banner-caption'?>">
                <header class="heading-title">
                    <h1 class="job-title"><?php echo $job_details['Title']?></h1>
                </header>
                <div class="job-categories">
                    <span>Category:</span>
                    <ul>
                        <?php   $JobCategories = explode(',', $job_details['JobCategory']);
                                foreach($JobCategories as $JobCategory){
                                    echo '<li>' . $JobCategory . '</li>';
                                } ?>
                    </ul>
                </div>
                <div class="job-info">
                    <ul>
                        <li>Job Type: <?php echo $job_details['JobType']; ?></li>
                        <li>Job Views: <?php echo $job_details['views']; ?></li>
                        <li>Published: <?php echo $job_details['activation_date']; ?></li>
                        <?php if(!empty($job_details['Salary'])) {?>
                                <li>Salary: <?php   echo $job_details['Salary'];                                 
                                                    if(!empty($job_details['SalaryType'])) {
                                                        echo '&nbsp;'.  ucwords(str_replace('_', ' ', $job_details['SalaryType']));
                                                    } ?>
                                </li>
                        <?php } ?>
                        <?php if(!empty($job_details['Location_City']) || !empty($job_details['Location_State']) || !empty($job_details['Location_Country'])) { ?>
                                <li><span>Job Location:</span>
                                    <?php if (!empty($job_details['Location_City'])) {
                                        echo $job_details['Location_City'] . ', ';
                                    }
                                    if (!empty($job_details['Location_State'])) {
                                        echo $job_details['Location_State'] . ', ';
                                    }
                                    echo $job_details['Location_Country']; ?>
                                </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="down_btn <?php echo ($theme_name == "theme-4") ? 'same_color' : ''; ?>"> </div>
<?php } else if($pageName == 'TESTIMONIAL') { ?>

<?php } else { ?>
    <?php if($pageData['page_banner_status'] == 1){?>
        <?php if(!empty($pageData['page_banner'])){ ?>
            <div class="comapny-video">
                <img src="<?php echo AWS_S3_BUCKET_URL . $pageData['page_banner']; ?>">
            </div>
            <div class="down_btn"> </div>
        <?php } ?>
    <?php }else{?>
        <div class="job-detail-banner">
            <div class="container-fluid">
                <div class="detail-banner-caption default-banner">

                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>


<!-- Banner End -->
<!-- Main Slider -->



<script>
        var tag = document.createElement('script');
        tag.src = "//www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }


    function onPlayerReady(event) {
        event.target.playVideo();
        player.mute();
        player.setLoop(true);
    }


    var done = false;
    function onPlayerStateChange(event) {
        if(event.data == YT.PlayerState.ENDED){
            player.playVideo();
        }
    }

    function stopVideo() {
        player.stopVideo();
    }

    function muteVideo(){
        player.mute();
    }

    function unMuteVideo(){
        player.unMute();
    }

    function fChangeVolumeState(source){
        $(source).find('i').each(function () {
           if($(this).hasClass('fa-volume-off')){
               unMuteVideo();
               $(this).removeClass('fa-volume-off');
               $(this).addClass('fa-volume-up');
           }else if($(this).hasClass('fa-volume-up')){
               muteVideo();
               $(this).removeClass('fa-volume-up');
               $(this).addClass('fa-volume-off');
           }
        });
    }


    $(document).ready(function () {
        $('.down_btn').each(function(){
            $(this).on('click', function(){
                $('html, body').animate({scrollTop: $('.main').offset().top}, 1000);
            });
        });
    });

</script>
