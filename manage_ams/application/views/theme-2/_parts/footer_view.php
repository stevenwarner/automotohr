<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>	
<footer class="footer">
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3"><span class="employee_teammember_login"><a href="<?php echo EMPLOYER_LOGIN_LINK; ?>" target="_Black" title="<?php echo EMPLOYER_LOGIN_SUBTITLE; ?>"><?php echo EMPLOYER_LOGIN_TEXT; ?></a></span></div>
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-5">
                    <div class="copy-right-text">
                        <p>&copy <?php echo date('Y');?> <?php echo $domain_name; ?>. All Rights Reserved.</p>
                    </div>
                </div>
                <?php $this->load->view('common/social_links_theme_01_02_03'); ?>
            </div>           
        </div>
    </div>
    <?php if($footer_powered_by_logo == 1) { ?>
            <div class="row_copyright">
                <div class="container">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-5">
                        <a href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank"><div class="poweredby"><span>Powered by</span><img src="<?php echo base_url('assets/default/images/ahr_logo_138X80_wt.png'); ?>"></div></a>
                    </div>
                </div>
            </div>
    <?php } ?>
</footer>
<div class="clear"></div>
</div>
<!--Terms and conditions modal-->
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
<!--Privacy policy modal--> 
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
<?php   if ($embedded_code != '') { ?>
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
<?php   } ?>
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