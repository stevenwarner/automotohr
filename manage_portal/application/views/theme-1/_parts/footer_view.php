<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>	
<footer class="footer">
    <?php if($customize_career_site['status'] == 1 && $customize_career_site['footer'] == 0){ ?>
    <?php $this->load->view('common/automoto_footer'); ?>
    <?php }else{ ?>    
    <div class="copyright">
        <div class="container">
            <?php if($eeo_footer_text == 1) { ?>
                <div class="row">
                    <div class="col-sm-12 theme-1-footer-anchor">
                        <p>
                            <?php echo $company_details['CompanyName']; ?> is an Equal Employment Opportunity employer. All qualified applicants/employees will receive consideration for employment without regard to that individual's age, race, color, religion or creed, national origin or ancestry, sex (including pregnancy), sexual orientation, gender, gender identity, physical or mental disability, veteran status, genetic information, ethnicity, citizenship, or any other characteristic protected by law.
                        </p>
                        <p>
                            Disability Assistance (Requests on your application status will not receive replies.) 
                        </p>
                        <p>
                            <?php echo $company_details['CompanyName']; ?> is committed to being an Equal Employment Opportunity Employer and offers opportunities to all job seekers including any job seeker with a disability. If you need a reasonable accommodation to assist with your job search or application for employment, please contact us by sending an email to <a href="mailto:jobaccommodation@automotohr.com">jobaccommodation@AutomotoHR.com</a>. In your email please include a description of the specific accommodation you are requesting and the Job Title and Company of the position for which you are applying.
                        </p>
                        <p>
                            <a href="https://www.eeoc.gov/employers/upload/poster_screen_reader_optimized.pdf" target="_blank"><b>EEO is the Law Notice</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://www.dol.gov/ofccp/regs/compliance/posters/pdf/OFCCP_EEO_Supplement_Final_JRF_QA_508c.pdf" target="_blank"><b>EEO is the Law Supplement</b></a>
                        </p>
                        <p>
                            E-Verify
                        </p>
                        <p>
                            <?php echo $company_details['CompanyName']; ?> is in the program.
                        </p>
                        <p>
                            Right to work in <a href="https://www.e-verify.gov/sites/default/files/everify/posters/IER_RighttoWorkPoster.pdf" target="_blank">&nbsp;English&nbsp;</a> and <a href="https://www.e-verify.gov/sites/default/files/everify/posters/IER_RighttoWorkPosterES.pdf" target="_blank">&nbsp;Spanish</a>.
                        </p>
                    </div>    
                </div> 
            <?php } ?>      
            <div class="row">
                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3"><span class="employee_teammember_login"><a href="<?php echo EMPLOYER_LOGIN_LINK; ?>" target="_Black" title="<?php echo EMPLOYER_LOGIN_SUBTITLE; ?>"><?php echo EMPLOYER_LOGIN_TEXT; ?></a></span></div>
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-5">
                    <div class="copy-right-text">
                        <p>Copyright &copy; <?php echo date('Y');?> <?php echo $domain_name; ?> All Rights Reserved </p> 
                    </div>
                </div>                
                <?php $this->load->view('common/social_links_theme_01_02_03'); ?>
            </div>
        </div>
    </div>
<?php   if($footer_powered_by_logo == 1) { ?>
            <div class="row_copyright">
                <div class="container">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-5">
                        <a href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                            <div class="poweredby">
                                <span>Powered by <?php if ($footer_logo_type == 'text') { echo $footer_logo_text; } ?></span>
                                <?php if ($footer_logo_type == 'default') { ?>
                                    <img src="<?php echo base_url('assets/default/images/ahr_logo_138X80_wt.png'); ?>">
                                <?php } else if ($footer_logo_type == 'logo') { ?>
                                    <img src="<?php echo AWS_S3_BUCKET_URL . $footer_logo_image; ?>" class="upload_logo_image">
                                <?php } ?>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
<?php   }
} ?>
</footer>
<div class="clear"></div>
</div>
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

<!-- *** START *** -->
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
<!-- ***  END  *** -->
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


<?php $this->load->view('cookie'); ?>


</body>
</html>