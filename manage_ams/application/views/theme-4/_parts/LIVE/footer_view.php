        <footer class="footer">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="widget widget_menu">
                                <h3 class="widget-title"><?php echo (isset($footer_content['title']) && $footer_content['title'] != '' ? $footer_content['title'] : '');?></h3>
                                <div class="widget-details">
                                    <p><?php echo (isset($footer_content['content']) && $footer_content['content'] != '' ? $footer_content['content'] : '');?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="widget widget_latest-blog-post">
                                <h3 class="widget-title">Useful Links</h3><!-- /.widget-title -->
                                <div class="widget-details">
                                    <nav>
                                        <ul>
                                            <li><a href="<?php echo base_url();?>">Home</a></li>
                                            <?php foreach($pages as $page) {
                                                    $skip_pages = array (
                                                                            'page_01',
                                                                            'page_02',
                                                                            'page_03',
                                                                            'page_04'
                                                                        );
                                                
                                                if(! in_array($page['page_unique_name'], $skip_pages)) { ?>
                                                        <li><a href="<?php echo base_url(($page['page_name'] != '' ? $page['page_name'] : '' ));?>"><?php echo ($page['page_title'] != '' ? $page['page_title'] : '' ); ?></a></li>
                                                <?php } ?>
                                            <?php }
                                            if($employee_login_text_status==1) {?>
                                                <li><a href="<?php echo EMPLOYER_LOGIN_LINK; ?>" target="_blank"><?php echo ucfirst($employee_login_text); ?></a></li>
<!--                                                <li><a href="--><?php //echo EMPLOYER_LOGIN_LINK; ?><!--" target="_blank">--><?php //echo EMPLOYER_LOGIN_TEXT; ?><!--</a></li>-->

                                            <?php } if ($contact_us_page) { ?>
                                                <li <?php if ($this->uri->segment(1) == 'contact_us') { ?>class="active" <?php } ?>><a href="<?php echo base_url('/contact_us'); ?>"></i>Contact</a></li>
                                            <?php } ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="widget widget_photo_stream">
                                <?php if($enable_facebook_footer==1 || $enable_twitter_footer==1 || $enable_google_footer==1 || $enable_linkedin_footer==1 || $enable_youtube_footer == 1 || $enable_instagram_footer == 1 || $enable_glassdoor_footer == 1) {?>
                                        <h3 class="widget-title">Follow Us</h3><!-- /.widget-title -->
                                        <div class="widget-details">
                                            <nav>
                                                <ul>
                                                    <?php if($enable_facebook_footer==1) { ?>
                                                        <li><a href="<?php echo $facebook_footer; ?>" target="_blank"><span><i class="fa fa-facebook"></i></span>Facebook</a></li>
                                                    <?php } if($enable_twitter_footer==1){?>
                                                        <li><a href="<?php echo $twitter_footer; ?>" target="_blank"><span><i class="fa fa-twitter"></i></span>Twitter</a></li>
                                                    <?php } if($enable_google_footer==1){?>
                                                        <li><a href="<?php echo $google_footer; ?>" target="_blank"><span><i class="fa fa-google-plus"></i></span>Google+</a></li>
                                                    <?php } if($enable_linkedin_footer==1){?>
                                                        <li><a href="<?php echo $linkedin_footer; ?>" target="_blank"><span><i class="fa fa-linkedin"></i></span>LinkedIn</a></li>
                                                    <?php } if($enable_youtube_footer == 1) { ?>
                                                        <li><a class="youtube"  href="<?php echo $youtube_footer; ?>" target="_blank"><span><i class="fa fa-youtube"></i></span>Youtube</a></li>
                                                    <?php } if ($enable_instagram_footer == 1) { ?>
                                                        <li><a class="instagram"  href="<?php echo $instagram_footer; ?>" target="_blank"><span><i class="fa fa-instagram"></i></span>Instagram</a></li>
                                                    <?php } if($enable_glassdoor_footer == 1) {?>
                                                        <li><a class="glassdoor"  href="<?php echo $glassdoor_footer; ?>" target="_blank"><img src="<?php echo base_url('assets/theme-4/images/glassdoor.png'); ?>"> Glassdoor</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </nav>
                                        </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="copy-right pull-left">
                        <p>&copy; <?php echo date('Y') . ' - ' . $company_details['CompanyName'] ;; ?>. All Rights Reserved</p>
                    </div>
                    <div class="lang-bar pull-right">
                        <div class="hr-lanugages">
                            <div id="google_translate_element"></div>
                        </div>
                    </div>
                    <?php if($footer_powered_by_logo == 1) { ?>
                            <div class="footer-menu pull-right">
                                <a href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                                    <div class="poweredby"><span>Powered by</span><img src="<?php echo base_url('assets/default/images/ahr_logo_138X80_wt.png'); ?>" style="width: 35%;"></div>
                                </a>
                            </div>
                    <?php } ?>
                </div>
            </div>
        </footer>
        <!-- Footer Section -->
        <div class="scrollToTop"><span><i class="fa fa-chevron-up"></i></span>Top</div>
        <!--Terms and conditions modal-->
        <div class="modal fade" id="terms_and_conditions" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-none">
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title modal-heading" id="myModalLabelFriend">Terms and Conditions for Job Seekers Using Talent Network</h4>
                    </div>
                    <div class="term-condition-content">
                        <?php $this->load->view('/common/terms_and_condition'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Privacy policy modal-->
        <div class="modal fade" id="privay_policy" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-none">
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title modal-heading" id="myModalLabelFriend">Talent Network Privacy Policy</h4>
                    </div>
                    <div class="term-condition-content">
                        <?php $this->load->view('/common/privacy_policy'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($embedded_code != '') { ?>
            <script type="text/javascript">
                var _gaq = _gaq || [];
                _gaq.push(['_setAccount', '<?php echo $embedded_code; ?>']);
                _gaq.push(['_trackPageview']);

                (function() {
                    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                })();
            </script>
        <?php } ?>
        <?php
        $pageName = $this->uri->segment(1);
        if(($pageName == strtolower(str_replace(' ', '_', $jobs_page_title))) ||  $pageName == 'jobs') { 
            $this->load->view($theme_name . '/_parts/apply_now_modal_for_index');            
        } else if($pageName == 'job_details') { ?>
            <?php $this->load->view($theme_name . '/_parts/apply_now_modal_for_job_details'); ?>
            <?php $this->load->view($theme_name . '/_parts/tell_a_friend_modal_for_job_details'); ?>
            <script type="text/javascript" src="<?php echo base_url('assets/theme-4/js/all.js'); ?>"></script>
<!--            <script src = "http://connect.facebook.net/en_US/all.js" ></script>-->
        <?php } ?>
<?php echo GOOGLE_TRANSLATE_SNIPPET; ?>
<?php   $company_sid = $company_details['sid']; 

        if($company_sid == 1662) { ?>           
            <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '166185077310698');
            fbq('track', 'PageView');
            </script>
            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=166185077310698&ev=PageView&noscript=1"/></noscript>
            <!-- End Facebook Pixel Code -->
<?php   } ?>
</body>
</html>