<!-- <div class="row clearfix"></div> -->
<div class="page-banner 2 affiliates-banner text-center flash_error_message_new">
    <!-- <?php $this->load->view('templates/_parts/admin_flash_message'); ?> -->
    <figure class="full-width">
        <?php if ($validate_flag == false) { ?>
            <img src="<?= base_url() ?>assets/images/affiliates/bannar-2.jpg" alt="246"/>
        <?php } elseif ($validate_flag == true) { ?>
            <?php if($video_source == 'youtube_video') { ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="player" class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $video_url; ?>/?autoplay=1&amp;loop=1&amp;rel=0&amp;controls=0&amp;showinfo=0&amp;enablejsapi=1"></iframe>
                </div>
            <?php } elseif($video_source == 'vimeo_video') { ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $video_url; ?>?title=0&byline=0&portrait=0&autoplay=1&loop=1&muted=1&controls=0" frameborder="0"webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>  
                </div>
            <?php } else { ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <video  autoplay loop muted>
                        <source src="<?php echo base_url().'assets/uploaded_videos/super_admin' .$video_url; ?>" type='video/mp4'>
                        <p class="vjs-no-js">
                          To view this video please enable JavaScript, and consider upgrading to a web browser that
                          <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                        </p>
                    </video>
                </div>
            <?php } ?>
        <?php } ?> 
        <figcaption class="affiliates-header-text-new">
            <h2>IS IT HARD TO FIND & KEEP AMAZING TALENT?</h2>
            <h4>AUTOMOTOHR HELPS YOU DIFFERENTIATE YOUR BUSINESS FROM EVERYONE ELSE AND SHOW QUALIFIED CANDIDATES WHY THEY SHOULD WORK FOR YOU.</h4>
            
            <div class="text-center">
                <a class="btn affiliate-signup-btn-new schedule_free_demo">SCHEDULE YOUR FREE DEMO</a>
                <p class="contact-at">Got Questions Give Us a Call <a href="tel:888 794-0794">(888) 794-0794</a>  ext 2</p>
            </div>
        </figcaption>
    </figure>
    <?php if ($validate_flag == true) { ?>
        <?php if($video_source == 'youtube_video') { ?>
            <button class="btn-mute-unmute" style="" onclick="fChangeVolumeState(this);"><i
            class="fa fa-volume-off"></i></button>      
        <?php } elseif($video_source == 'vimeo_video') { ?>
            <button class="btn-mute-unmute" style="" onclick="vimeovolumestate(this);"><i
            class="fa fa-volume-off"></i></button>    
        <?php } else { ?>
            <button class="btn-mute-unmute" style="" onclick="uploadedvolumestate(this);"><i
            class="fa fa-volume-off"></i></button>
        <?php } ?>
    <?php } ?>
</div>

<div class="main-content clearfix">
    <div class="amr-universal-section affiliate-section">
        <div class="affiliate-static-blocks" id="about">
            <div class="container-fluid">
                <div class="grid-columns">
                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-right affiliate-block-one">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <h2 class="affiliate-post-title">AutomotoHR &#8211; See it to Believe it</h2>
                                <h3 class="affiliate-post-subtitle">How do the best job candidates view your company?</h3>
                                <p>Improve Your Employer Brand</p>
                                <p>Hiring is not just about getting more applications, it’s about getting better ones that will make your competitors jealous.</p>
                                <p>When it comes to attracting top candidates, your employer brand influences the applicants you receive.</p>
                                <p>Of course you think your company is awesome, but how do job candidates perceive your employer brand?</p>
                                <p>Whether your career page needs a revamp or you’re just looking for new inspiration to ignite your employer brand, we’re here to help.</p>
                                <p>So, how does your employer brand shape up?<br />
                                Hop on a call with one of our Talent experts to learn how you can improve your employer brand and start attracting top talent today. We will take the time and walk you through the many ways that AutomotoHR can be tailor made and customized to your specific sourcing and hiring needs.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/affiliates/monitors_ahr.png" alt=""></figure>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <article class="v1 bg-gray affiliate-block-2 maliha_shaukat" id="join">
            <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/affiliates/affiliate-3.png" alt=""></figure>
            <div class="text affiliate-block-4">
                <div class="info-box">
                    <div class="affiliate-block-2-contant">
                        <h2 class="ls_title">Join one of our Hiring Success advisors, for a personal full length Demo &amp; see how to:</h2>
                        <ol class="ls_olstyle ls_fnt17">
                            <li>Build your Company Career pages in minutes</li>
                            <li>Post to all of your preferred job boards</li>
                            <li>Hire on the go with your mobile optimized hiring system</li>
                            <li>Easily evaluate candidates and collaborate with teams to make the best hire</li>
                            <li>Maintain all of your Companies pertinent and legally required hiring information, government compliance forms and data all in one place, with a single log in.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </article>
        <?php if ($validate_body_flag == true) { ?>
            <?php if ($body_column_type == 'video_only') { ?>
                <div class="affiliate-signup-block full-width video-section-top-bottom-padding" id="contact">
                    <figure>
                        <?php if($body_video_source == 'youtube_video') { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $body_video_url; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                        <?php } elseif($body_video_source == 'vimeo_video') { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $body_video_url; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0"webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>  
                            </div>
                        <?php } else { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <video id="my-video" class="video-js" controls preload="auto" poster="<?php echo base_url().'assets/images/affiliates/affiliate-0.png'; ?>" data-setup="{}">
                                    <source src="<?php echo base_url().'assets/uploaded_videos/super_admin' .$body_video_url; ?>" type='video/mp4'>
                                    <p class="vjs-no-js">
                                      To view this video please enable JavaScript, and consider upgrading to a web browser that
                                      <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                    </p>
                                </video>
                            </div>
                        <?php } ?>
                    </figure>
                </div>
            <?php } elseif ($body_column_type == 'left_right') { ?>
                <div class="affiliate-static-blocks video-section-top-bottom-padding" id="about">
                    <div class="container-fluid">
                        <div class="grid-columns">
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-right affiliate-block-one">
                                <div class="box-container">
                                    <div class="vh-center-box">
                                        <h2 class="ls_title"><?php echo $body_title; ?></h2>
                                        <p><?php echo $body_content; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                                <div class="box-container">
                                    <div class="vh-center-box">
                                        <figure>
                                            <?php if($body_video_source == 'youtube_video') { ?>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $body_video_url; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                </div>
                                            <?php } elseif($body_video_source == 'vimeo_video') { ?>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $body_video_url; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0"webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>  
                                                </div>
                                            <?php } else { ?>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <video id="my-video" class="video-js" controls preload="auto" poster="<?php echo base_url().'assets/images/affiliates/affiliate-0.png'; ?>" data-setup="{}">
                                                        <source src="<?php echo base_url().'assets/uploaded_videos/super_admin' .$body_video_url; ?>" type='video/mp4'>
                                                        <p class="vjs-no-js">
                                                          To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                          <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                                        </p>
                                                    </video>
                                                </div>
                                            <?php } ?>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } elseif ($body_column_type == 'right_left') { ?>
                <article class="v1 bg-gray affiliate-block-2 static-block-background-remove video-section-top-bottom-padding" id="join">
                    <figure>
                        <?php if($body_video_source == 'youtube_video') { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $body_video_url; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                        <?php } elseif($body_video_source == 'vimeo_video') { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $body_video_url; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=1&muted=0&controls=1" frameborder="0"webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>  
                            </div>
                        <?php } else { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <video id="my-video" class="video-js" controls preload="auto" poster="<?php echo base_url().'assets/images/affiliates/affiliate-0.png'; ?>" data-setup="{}">
                                    <source src="<?php echo base_url().'assets/uploaded_videos/super_admin' .$body_video_url; ?>" type='video/mp4'>
                                    <p class="vjs-no-js">
                                      To view this video please enable JavaScript, and consider upgrading to a web browser that
                                      <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                    </p>
                                </video>
                            </div>
                        <?php } ?>
                    </figure>
                    <div class="text affiliate-block-4">
                        <div class="info-box">
                            <div class="affiliate-block-2-contant">
                                <h2 class="ls_title"><?php echo $body_title; ?></h2>
                                <p><?php echo $body_content; ?></p>
                            </div>
                        </div>
                    </div>
                </article>
            <?php } elseif ($body_column_type == 'top_bottom') { ?>
                <div class="affiliate-signup-block full-width static-block-background-remove video-section-top-bottom-padding" id="contact">
                    <figure>
                        <?php if($body_video_source == 'youtube_video') { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $body_video_url; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                        <?php } elseif($body_video_source == 'vimeo_video') { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $body_video_url; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0"webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>  
                            </div>
                        <?php } else { ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <video id="my-video" class="video-js" controls preload="auto" poster="<?php echo base_url().'assets/images/affiliates/affiliate-0.png'; ?>" data-setup="{}">
                                    <source src="<?php echo base_url().'assets/uploaded_videos/super_admin' .$body_video_url; ?>" type='video/mp4'>
                                    <p class="vjs-no-js">
                                      To view this video please enable JavaScript, and consider upgrading to a web browser that
                                      <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                    </p>
                                </video>
                            </div>
                        <?php } ?>
                    </figure>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 affiliate-block-one" style="text-align: center;">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <h2 class="ls_title"><?php echo $body_title; ?></h2>
                                <p><?php echo $body_content; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>  
        <?php } ?>
        <div class="affiliate-signup-block full-width" id="contact">
            <figure><img  class="img-responsive affiliate-6-img" src="<?= base_url() ?>assets/images/affiliates/affiliate-5.png" alt=""></figure>
            <div class="text text-center">
                <h2 class="affiliate-post-title">Hire &amp; Manage Great People</h2>
                <a class="btn affiliate-signup-btn-new schedule_free_demo">LEARN MORE</a>
                <p class="contact-at">Got Questions Give Us a Call <a href="tel:888 794-0794">(888) 794-0794</a> ext 2</p>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix"></div>
<!--    <div class="col-sm-12">
            <div class="page-header-area">
                <span class="page-heading down-arrow">Contact one of our Talent Network Partners at</span>
            </div>
            <div class="col-sm-6">
                <div class="wrapper">
                    <div class="upper-footer-txt">
                        <h5>Sales Support</h5>
                        <p>
                            <span><?php echo TALENT_NETWORK_SALE_CONTACTNO; ?></span>
                            <br>
                            <span>
                                <a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope"></i><?php echo TALENT_NETWORK_SALES_EMAIL; ?></a>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="wrapper">
                    <div class="upper-footer-txt">
                        <h5>Technical Support</h5>
                        <p>
                            <span><?php echo TALENT_NETWORK_SUPPORT_CONTACTNO; ?></span>
                            <br>
                            <span>
                                <a href="mailto:<?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i class="fa fa-envelope"></i><?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?></a>
                            </span>
                        </p>
                    </div>
                </div>
            </div>   
    </div>-->
<?php $this->load->view('main/demobuttons'); ?>
<?php $this->load->view('static-pages/schedule_free_demo_popup'); ?>

<script src="//f.vimeocdn.com/js/froogaloop2.min.js"></script>
<script type="text/javascript">
    var isAuidoMuted;

    $( document ).ready(function() {
        isAuidoMuted = true;
    });
        
    $('.schedule_free_demo').on('click', function () {
       $('#popup1').modal('show');   
    });

    $("#button-about").click(function() {
        $('html, body').animate({
            scrollTop: $("#about").offset().top
        }, 1000);
    });

    $("#button-join").click(function() {
        $('html, body').animate({
            scrollTop: $("#join").offset().top
        }, 1000);
    });

    $("#button-contact").click(function() {
        $('html, body').animate({
            scrollTop: $("#contact").offset().top
        }, 1000);
    });

    function fChangeVolumeState(source) {
        $(source).find('i').each(function () {
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

    function vimeovolumestate(source){
        var vimeo_iframe = $('#vimeo_player')[0];
        var player = $f(vimeo_iframe);

        if ( isAuidoMuted === true ){
            player.addEvent('ready', function() {
                player.api('setVolume', 0.5);
            });
            isAuidoMuted = false;
            
        } else {
            player.addEvent('ready', function() {
                player.api('setVolume', 0);
            });
            isAuidoMuted = true;
        }
    }

    function uploadedvolumestate(source){
        
        if ( isAuidoMuted === true ){
            $("video").prop('muted', false);
            isAuidoMuted = false;
            
        } else {
            $("video").prop('muted', true);
            isAuidoMuted = true;
        }
    }


</script>
   