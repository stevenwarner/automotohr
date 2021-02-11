        <footer class="footer">
            <div class="footer-bottom">
                <div class="container">
                    <div class="copy-right pull-left">
                        <p>&copy; <?php echo date('Y') . ' - ' . $company_details['CompanyName'] ; ?>. All Rights Reserved</p>
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
        <div class="scrollToTop"><span><i class="fa fa-chevron-up"></i></span>Top</div>
        <div class="modal fade" id="terms_and_conditions" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-none">
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title modal-heading" id="myModalLabelFriend">Terms and Conditions for Job Seekers Using Career Site</h4>
                    </div>
                    <div class="term-condition-content">
                        <?php $this->load->view('/common/terms_and_condition_apply_job'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="privay_policy" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-none">
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title modal-heading" id="myModalLabelFriend">Privacy Policy</h4>
                    </div>
                    <div class="term-condition-content">
                        <?php $this->load->view('/common/privacy_policy_apply_job'); ?>
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
        <?php }

        $pageName = $this->uri->segment(1);

        if(($pageName == strtolower(str_replace(' ', '_', $jobs_page_title))) ||  $pageName == 'job-feed'||  $pageName == 'job_feed') {
            $this->load->view($theme_name . '/_parts/apply_now_modal_for_index_iframe');
        } else if($pageName == 'job-feed-details' || $pageName == 'job_feed_details' ) { ?>
            <?php $this->load->view($theme_name . '/_parts/apply_now_modal_for_job_details_iframe'); ?>
            <?php $this->load->view($theme_name . '/_parts/tell_a_friend_modal_for_job_details_iframe'); ?>
            <script type="text/javascript" src="<?php echo base_url('assets/theme-4/js/all.js'); ?>"></script>
        <?php } ?>
<?php echo GOOGLE_TRANSLATE_SNIPPET; ?>
<?php   $company_sid = $company_details['sid']; ?>
        <div class="modal fade" id="terms_and_conditions_apply_now" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-none">
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title modal-heading">Terms and Conditions</h4>
                    </div>
                    <div class="term-condition-content">
                        <?php $this->load->view('/common/terms_and_condition_apply_job'); ?>
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
                        <h4 class="modal-title modal-heading">Privacy Policy</h4>
                    </div>
                    <div class="term-condition-content">
                        <?php $this->load->view('/common/privacy_policy_apply_job'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<script>
    $('#terms_and_conditions_apply_now').on('hide.bs.modal', function (e) {
      setTimeout(function(){
        $('body').addClass('modal-open');
      }, 1200);
    });

    $('#privay_policy_apply_now').on('hide.bs.modal', function (e) {
      setTimeout(function(){
        $('body').addClass('modal-open');
      }, 1200);
    });
</script>
</body>
</html>
