<div class="main-content">
    <div class="container">
        <div class="row">
            <h1></h1>
        </div>
    </div>

    <div class="amr-universal-section affiliate-section">
        <!-- <?php if ($validate_body_flag == true) { ?>
            <?php if ($body_column_type == 'video_only') { ?>
                <div class="affiliate-signup-block full-width bg-gray" id="contact">
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
                <div class="affiliate-static-blocks bg-gray video-section-top-bottom-padding" id="about">
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
                <article class="v1 bg-gray affiliate-block-2 video-section-top-bottom-padding" id="join">
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
                <div class="affiliate-signup-block full-width bg-gray" id="contact">
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
        <?php } ?>-->  
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
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 affiliate-block-one">
		<?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="box-container">
                    <div class="vh-center-box affiliates-form-vh-center-box">
                        <h2 class="affiliate-post-title">We need to ask you for some personal information so that we can file all required tax paperwork and get you paid.</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row affiliate-form-box">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 affiliate-block-one ">
                <div class="box-container">
                    <div class="affiliate-form-heading">
                        <h3 class="affiliate-post-subtitle">Refer and Earn!</h3>
                        <p>30% Lifetime Commissions On Your Referrals for as long as they remain our client.</p>
                    </div>
                    <div class="form-wrp">
                        <form enctype="multipart/form-data" id="affiliated-form" method="post">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group rounded-input">
                                    <label for="firstname">First Name<span class="required">*</span></label>
                                    <input type="text" name="firstname" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group rounded-input">
                                    <label for="lastname">Last Name<span class="required">*</span></label>
                                    <input type="text" name="lastname" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group rounded-input">
                                    <label for="email">Email<span class="required">*</span></label>
                                    <input type="email" name="email" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group auto-height rounded-input">
                                    <label for="paypal_email">Paypal Email</label>
                                    <input type="email" name="paypal_email" class="form-control no-border">
                                    <div class="full-width paypal-account-info">
                                        <a style="color: #0000FF;" href=" https://www.paypal.com/welcome/signup/#/email_one_password" target="_blank">Create your FREE PayPal account now.</a>
<!--                                        <p class="text-muted">Method Of Promotion* ( How will you get the word out to your Network).</p>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="company">Company</label>
                                    <input type="text" name="company" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="street">Street<span class="required">*</span></label>
                                    <input type="text" name="street" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="city">City<span class="required">*</span></label>
                                    <input type="text" name="city" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="state">State / Province<span class="required">*</span></label>
                                    <input type="text" name="state" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="zip">Zip Code / Postal Code</label>
                                    <input type="text" name="zip" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="country">Country<span class="required">*</span></label>
<!--                                    <input type="text" name="country" class="form-control no-border">-->
<!--                                    <div class="Category_chosen">-->
                                        <select data-placeholder="Please Select Country" name="country" id="country" class="chosen-select chosen-rounded">
                                            <option class="ats_search_filter_inactive" value="">Please Select Country</option>
                                            <?php foreach($countries as $country) {?>
                                                <option class="ats_search_filter_inactive" value="<?php echo $country['country_name']?>">
                                                    <?php echo $country['country_name']?>
                                                </option>
                                            <?php }?>
                                        </select>
<!--                                    </div>-->
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="MOP">Method of Promotion</label> (How will you get the word out to your Network?)
                                    <input type="text" name="MOP" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="website">Your Website</label>
                                    <input type="text" name="website" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="info" class="affiliate-form-label">Contact Number<span class="required" aria-required="true">*</span></label>
                                    <input type="text" name="contact_number" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group rounded-input">
                                    <label for="no_of_names" class="affiliate-form-label">Do you have an email list? If so, how many names?</label>
                                    <input type="number" name="no_of_names" class="form-control no-border">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group auto-height rounded-input">
                                    <label class="affiliate-form-label">Please Upload W9 Form ( For Our U.S Affiliates )</label>
                                    <input type="file" name="w9_form" class="file-style">
                                    <p><a class="text-muted" href="https://www.irs.gov/pub/irs-pdf/fw9.pdf" target="_blank">W9 link: <span style="color: #0000FF;">https://www.irs.gov/pub/irs-pdf/fw9.pdf</span></a></p>
                                </div>
                                
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group auto-height rounded-input">
                                    <label class="affiliate-form-label">Please Upload W8 Form ( For Affiliates Outside of the U.S )</label>
                                    <input type="file" name="w8_form" class="file-style">
                                    <p><a class="text-muted" href="https://www.irs.gov/pub/irs-pdf/fw8ben.pdf" target="_blank">W8 link: <span style="color: #0000FF;">https://www.irs.gov/pub/irs-pdf/fw8ben.pdf</span></a></p>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group rounded-input autoheight">
                                    <label for="location_address" class="affiliate-form-label">Anything else you want us to know?: </label>
                                    <textarea name="info" cols="40" rows="10" class="form-control autoheight" id="location_address" style="border-radius: 15px;"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-default full-width affiliate-checkbox-area">
                                    <div class="panel-body">
                                        <p>Please Acknowledge that you have Read and Agree with the AutomotoHR Terms of Service and Privacy Policy and by adding a check mark below and proceeding with the application you are giving your express permission to contact you, store your personal data for the purpose of the AutomotoHR Affiliate Program tracking and payments. Please mark the box if you Agree.
                                            Please mark the box if you Agree.</p>
<!--                                        <label for="agreement" class="control control--checkbox">
                                            I do not agree to AutomotoHR contacting me in connection with AutomotoHR affiliate policy.
                                            <input type="checkbox" name="agreement" id="agreement">
                                            <input type="hidden" name="hidden_agree" id="hidden_agree" value="1">
                                            <div class="control__indicator"></div>
                                        </label>-->
                                        <label  for="terms_and_condition" class="control control--checkbox">
                                            By checking this box, you agree to our <a href="javascript:;" data-toggle="modal" data-target="#terms_and_conditions_apply_now">Terms of Service</a> and <a href="javascript:;" data-toggle="modal" data-target="#privay_policy_apply_now">Privacy Policy</a>.
                                            <input type="checkbox" name="terms_and_condition" id="terms_and_condition">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<!--                                <div class="panel panel-default full-width affiliate-checkbox-area">
                                    <div class="panel-body">
                                        <label  for="terms_and_condition" class="control control--checkbox">
                                            By checking this box, you agree to our terms of service and privacy policy.
                                            <input type="checkbox" name="terms_and_condition" id="terms_and_condition">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>-->
                                <input class="login-btn affiliate-submit-btn btn-disable" id="app-submit" type="submit" value="Submit Application">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 affiliate-block-one">
                <div class="box-container">
                    <div class="vh-center-box affiliates-form-vh-center-box">
                        <p class="affiliate-post-text">Thank you for your interest in joining the AutomotoHR Affiliate Program - we are looking forward to working with you!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$this->session->userdata('logged_in') && $title != 'Register') { ?> 
        <?php $this->load->view('main/demobuttons'); ?>
    <?php } ?> 
</div>
<div class="modal fade" id="terms_and_conditions_apply_now" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-none">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-heading">Affiliate Program - Terms of Service</h4>
            </div>
            <div class="term-condition-content">
                <?php $this->load->view('affiliates/terms_of_service'); ?>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="privay_policy_apply_now" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-none">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-heading">Affiliate Program - Privacy Policy</h4>
            </div>
            <div class="term-condition-content">
                <?php $this->load->view('affiliates/privacy_policy'); ?>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- ***  END  *** -->
<script type="text/javascript">
    $(document).ready(function () {
//        $(document).on('change', '#agreement', function () {
//            if (!$('input[name="agreement"]').prop('checked')) {
//                $('#hidden_agree').val('1');
//		$('#app-submit').click();
//            } else {
//                $('#hidden_agree').val('');
//		$('#app-submit').click();
//            }
//        });

        $('.file-style').filestyle({
            text: 'Upload File',
            btnClass: 'btn-success',
            placeholder: "No file selected"
        });
        
        $("#affiliated-form").validate({
            ignore: [],
            rules: {
                firstname: {
                    required: true
                },
                lastname: {
                    required: true
                },
                email: {
                    required: true
                },
                street: {
                    required: true
                },
                city: {
                    required: true
                },
                state: {
                    required: true
                },
                country: {
                    required: true
                },
//                hidden_agree: {
//                    required: true
//                },
                contact_number: {
                    required: true
                },
                terms_and_condition: {
                    required: true
                },
                w8_form: {
                    extension: "docx|rtf|doc|pdf|PDF"
                },
                w9_form: {
                    extension: "docx|rtf|doc|pdf|PDF"
                }
            },
            messages: {
                firstname: {
                    required: 'First Name is required!'
                },
                lastname: {
                    required: 'Last Name is required!'
                },
                email: {
                    required: 'Email is required!'
                },
                street: {
                    required: 'Street Address is required!'
                },
                city: {
                    required: 'City is required!'
                },
                state: {
                    required: 'State / Province is required!'
                },
                country: {
                    required: 'Country is required!'
                },
//                hidden_agree: {
//                    required: 'Please agree with our affiliate policy!'
//                },
                contact_number: {
                    required: 'Contact number is required!'
                },
                terms_and_condition: {
                    required: 'Please Agree with our terms and policy!'
                },
                w8_form: {
                    extension: "Only .doc, .docx, and .pdf files are allowed."
                },
                w9_form: {
                    extension: "Only .doc, .docx, and .pdf files are allowed."
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
        
        $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
    });
</script>