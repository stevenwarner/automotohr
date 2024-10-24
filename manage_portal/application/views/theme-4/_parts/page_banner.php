<!-- Main Slider -->
<?php if (strtoupper($pageName) == 'JOBS' && $customize_career_site['status'] == 1 && $customize_career_site['menu'] == 0) { ?>
    <div style="margin-top:40px;"></div>
<?php } ?>
<?php if (strtoupper($pageName) == 'HOME' && !($customize_career_site['status'] == 1 && $customize_career_site['menu'] == 0)) { ?>
    <section class="main-slider text-center <?php if ($header_video_overlay == 0) {
                                                echo 'no_overlay';
                                            } ?>">
        <?php if ($section_01_meta['show_video_or_image'] == 'video') { ?>
            <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i class="fa fa-volume-off"></i></button>
        <?php } ?>
        <div class="head-overlay">
            <?php if (isset($section_01_meta['show_video_or_image']) && $section_01_meta['show_video_or_image'] == 'image') { ?>
                <img src="<?php echo AWS_S3_BUCKET_URL . (isset($section_01_meta['image']) && $section_01_meta['image'] != '' ? $section_01_meta['image'] : ''); ?>" />
            <?php } else if ($section_01_meta['show_video_or_image'] == 'video') { ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo (isset($section_01_meta['video']) && $section_01_meta['video'] != '' ? $section_01_meta['video'] : ''); ?>/?autoplay=0&loop=1&rel=0&controls=0&showinfo=0&enablejsapi=1"></iframe>
                </div>
            <?php } else if ($section_01_meta['show_video_or_image'] == 'vimeo_video') { ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $section_01_meta['vimeo_video']; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            <?php } else if ($section_01_meta['show_video_or_image'] == 'uploaded_video') { ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <video controls width="100%">
                        <source src="<?php echo UPLOADED_VIDEO_PATH . $section_01_meta['uploaded_video']; ?>" type='video/mp4'>
                    </video>
                </div>
            <?php } ?>


        </div>
        <div class="slider-text">
            <div class="slide-inner">
                <h2 class="slider-title" <?= isset($section_01_meta['do_capitalize']) && $section_01_meta['do_capitalize'] == 0 ? 'style="text-transform: none;"' : ''; ?>><?php echo (trim($section_01_meta['title']) != '' ? $section_01_meta['title'] : ''); ?></h2>
                <p class="slide-description" <?= isset($section_01_meta['do_capitalize']) && $section_01_meta['do_capitalize'] == 0 ? 'style="text-transform: none;"' : ''; ?>><?php echo (trim($section_01_meta['tag_line']) != '' ? $section_01_meta['tag_line'] : ''); ?></p>
            </div>
        </div>
    </section>
    <div class="down_btn"></div>
<?php } else if (strtoupper($pageName) == 'JOBS' && !($customize_career_site['status'] == 1 && $customize_career_site['menu'] == 0)) { ?>
    <?php if (!empty($jobs_page_banner)) { ?>
        <div class="comapny-video <?php if ($header_video_overlay == 0) {
                                        echo 'no_overlay';
                                    } ?>">
            <img src="<?php echo AWS_S3_BUCKET_URL . $jobs_page_banner['jobs_page_banner']; ?>">
        </div>
        <div class="down_btn"> </div>
    <?php } ?>
<?php } else if (strtoupper($pageName) == 'JOB_DETAILS') { ?>
    <!--    <div class="job-detail-banner" style="--><?php //echo !empty($jobs_detail_page_banner_data) && $jobs_detail_page_banner_data['banner_type'] == 'custom' ? 'background: url('.AWS_S3_BUCKET_URL . $jobs_detail_page_banner_data['jobs_detail_page_banner'].')' : ''
                                                        ?><!--">-->
    <?php if (($customize_career_site['status'] == 1 && $customize_career_site['menu'] == 0)) { ?>
        <div class="text-center" style="position:absolute;width:100%;z-index:10;padding-bottom:50px;">
            <a href="<?= base_url('jobs') ?>" class="site-btn bg-color">Back</a>
        </div>
    <?php } ?>
    <div class="<?php echo !empty($jobs_detail_page_banner_data) && $jobs_detail_page_banner_data['banner_type'] == 'custom' ? '' : 'job-detail-banner' ?>">
        <?php $li_color = '';
        $banner_type = 'default';
        $li_position = '';

        if (!empty($jobs_detail_page_banner_data) && $jobs_detail_page_banner_data['banner_type'] == 'custom') { ?>
            <div class="banner-wrp">
                <div class="comapny-video <?php if ($header_video_overlay == 0) {
                                                echo 'no_overlay';
                                            } ?>">
                    <img src="<?php echo AWS_S3_BUCKET_URL . $jobs_detail_page_banner_data['jobs_detail_page_banner']; ?>">
                </div>

                <div class="down_btn <?php echo ($theme_name == "theme-4") ? 'same_color' : ''; ?> theme4_custom_banner_arrow"></div>

                <div class="text-center banner-btn-apply">
                    <a href="javascript:;" class="site-btn bg-color apply-now-large" data-toggle="modal" data-target="#myModal">apply now</a>
                    <?php if ($indeedApplyButtonDetails): ?>
                        <a class="site-btn apply-now-large" style="background: none;" href="javascript:void(0)">
                            <div
                                data-indeed-apply-widget-type="AWI"
                                data-indeed-apply-sourceJobPostingId="<?= $indeedApplyButtonDetails["indeed_posting_id"]; ?>"
                                data-indeed-apply-partnerApiToken="56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781"
                                data-indeed-apply-encryptedJobUrl="<?= urlencode(current_url()) ?>"
                                data-indeed-apply-hl="en"
                                data-indeed-apply-co="US"
                                data-indeed-apply-newTab="true">
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php $li_color = '#000'; //use black instead of button color - suggested by Steven
            $banner_type = 'custom';
            $li_position = 'position: static;';
            //if($theme4_job_title_color!=NULL) {
            //$li_color = $theme4_job_title_color; 
            //}
        } ?>
        <div class="container">
            <div class="<?php echo $banner_type == 'custom' ? '' : 'detail-banner-caption' ?>">
                <header class="heading-title mt-3" style="<?php echo $banner_type == 'custom' ? 'text-align: center; margin: 0' : '' ?>">
                    <h1 class="job-title <?php echo $banner_type == 'custom' ? 'custom-theme4-job-title' : '' ?>"><?php echo $job_details['Title'] ?></h1>
                </header>

                <?php if ($banner_type == 'default') { ?>
                    <div class="job-categories">
                        <span style="color: <?php echo $li_color; ?>">Category:</span>
                        <ul>
                            <?php $JobCategories = explode(',', $job_details['JobCategory']);
                            foreach ($JobCategories as $JobCategory) { ?>
                                <li style="color: <?php echo $li_color; ?>"><?php echo $JobCategory; ?></li>
                            <?php   } ?>
                        </ul>
                    </div>
                <?php   } ?>

                <div class="job-info" style="<?php echo $banner_type == 'custom' ? 'text-align: center; margin: 5px 0 0 0; border-bottom: 1px solid #d0d0d0; padding: 0 0 15px 0;' : '' ?>">
                    <ul style="<?php echo $banner_type == 'custom' ? 'float: none;' : '' ?>">
                        <li style="color: <?php echo $li_color; ?>; <?php echo $li_position; ?>">Job Type: <span style="<?php echo $banner_type == 'custom' ? 'font-size: 13px; margin: 0 5px 0 0; text-transform: capitalize;' : '' ?>"><?php echo $job_details['JobType']; ?></span></li>

                        <?php if ($banner_type == 'custom') { ?>
                            <li style="color: <?php echo $li_color; ?>; <?php echo $li_position; ?>">Category: <span style="<?php echo $banner_type == 'custom' ? 'font-size: 13px; margin: 0 5px 0 0; text-transform: capitalize;' : '' ?>"><?php echo $job_details['JobCategory']; ?></span></li>
                        <?php           } ?>

                        <!--                        <li style="color: <?php echo $li_color; ?>">Job Views: <span style="<?php echo $banner_type == 'custom' ? 'font-size: 13px; margin: 0 5px 0 0; text-transform: capitalize;' : '' ?>"><?php echo $job_details['views']; ?></span></li>-->
                        <li style="color: <?php echo $li_color; ?>; <?php echo $li_position; ?>">Published: <span style="<?php echo $banner_type == 'custom' ? 'font-size: 13px; margin: 0 5px 0 0; text-transform: capitalize;' : '' ?>"><?php echo $job_details['activation_date']; ?></span></li>

                        <?php if (!empty($job_details['Salary'])) { ?>
                            <li style="color: <?php echo $li_color; ?>; <?php echo $li_position; ?>">Salary: <span style="<?php echo $banner_type == 'custom' ? 'font-size: 13px; margin: 0 5px 0 0; text-transform: capitalize;' : '' ?>">
                                    <?php echo $job_details['Salary'];

                                    if (!empty($job_details['SalaryType'])) {
                                        echo '&nbsp;' .  ucwords(str_replace('_', ' ', $job_details['SalaryType']));
                                    } ?>
                                </span></li>
                        <?php           } ?>

                        <?php if (!empty($job_details['Location_City']) || !empty($job_details['Location_State']) || !empty($job_details['Location_Country'])) { ?>
                            <li style="color: <?php echo $li_color; ?>; <?php echo $li_position; ?>"><span>Job Location:</span>

                                <?php if (!empty($job_details['Location_City'])) { ?>
                                    <span style="<?php echo $banner_type == 'custom' ? 'font-size: 13px; margin: 0 5px 0 0; text-transform: capitalize;' : '' ?>">
                                        <?php echo $job_details['Location_City'] . ', '; ?>
                                    </span>
                                <?php               }

                                if (!empty($job_details['Location_State'])) { ?>
                                    <span style="<?php echo $banner_type == 'custom' ? 'font-size: 13px; margin: 0 5px 0 0; text-transform: capitalize;' : '' ?>">
                                        <?php echo $job_details['Location_State'] . ', '; ?>
                                    </span>
                                <?php               } ?>
                                <span style="<?php echo $banner_type == 'custom' ? 'font-size: 13px; margin: 0 5px 0 0; text-transform: capitalize; ' : '' ?>">
                                    <?php echo $job_details['Location_Country']; ?>
                                </span>
                            </li>
                        <?php           } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php if (empty($jobs_detail_page_banner_data)) { ?>
        <div class="down_btn <?php echo ($theme_name == "theme-4") ? 'same_color' : ''; ?>"></div>
    <?php   } ?>

<?php } else if ($pageName == 'TESTIMONIAL') { ?>

<?php } else { ?>
    <?php if ($pageData['page_banner_status'] == 1 && !($customize_career_site['status'] == 1 && $customize_career_site['menu'] == 0)) { ?>
        <?php if (!empty($pageData['page_banner'])) { ?>
            <div class="comapny-video <?php if ($header_video_overlay == 0) {
                                            echo 'no_overlay';
                                        } ?>">
                <img src="<?php echo AWS_S3_BUCKET_URL . $pageData['page_banner']; ?>">
            </div>
            <div class="down_btn"> </div>
        <?php } ?>
    <?php } else if (!($customize_career_site['status'] == 1 && $customize_career_site['menu'] == 0)) { ?>
        <div class="job-detail-banner">
            <div class="container-fluid">
                <div class="detail-banner-caption default-banner"></div>
            </div>
        </div>
    <?php } ?>
<?php } ?>


<?php $this->load->view('flash_message'); ?>

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
        if (event.data != YT.PlayerState.PLAYING) {
            player.playVideo();
        }
    }

    function stopVideo() {
        player.stopVideo();
    }

    function muteVideo() {
        player.mute();
    }

    function unMuteVideo() {
        player.unMute();
    }

    function fChangeVolumeState(source) {
        $(source).find('i').each(function() {
            if ($(this).hasClass('fa-volume-off')) {
                unMuteVideo();
                $(this).removeClass('fa-volume-off');
                $(this).addClass('fa-volume-up');
            } else if ($(this).hasClass('fa-volume-up')) {
                muteVideo();
                $(this).removeClass('fa-volume-up');
                $(this).addClass('fa-volume-off');
            }
        });
    }


    $(document).ready(function() {
        $('.down_btn').each(function() {
            $(this).on('click', function() {
                $('html, body').animate({
                    scrollTop: $('.main').offset().top
                }, 1000);
            });
        });
    });
</script>