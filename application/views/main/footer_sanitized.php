<?php if ($loadView) { ?>
    <?php
    $company_sid = '';

    if ($this->uri->segment(2) == 'my_offer_letter' || $this->uri->segment(2) == 'send_requested_resume') {
        $company_sid = $company_info['sid'];
    } else {
        $company_sid = $session['company_detail']['sid'];
    }

    $footer_logo_data = get_footer_logo_data($company_sid);
    $footer_logo_status = $footer_logo_data['footer_powered_by_logo'];
    $footer_logo_type = $footer_logo_data['footer_logo_type'];
    $footer_logo_text = $footer_logo_data['footer_logo_text'];
    $footer_logo_image = $footer_logo_data['footer_logo_image'];

    $footer_copyright_data = get_footer_copyright_data($company_sid);
    $copyright_status = $footer_copyright_data['copyright_company_status'];
    $copyright_company_name = $footer_copyright_data['copyright_company_name']; ?>

    <footer class="ob-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                        <div class="hr-lanugages">
                            <div id="google_translate_element"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                        <div class="copy-right-text">
                            <?php if ($copyright_status == 1) {
                                $company_name = $copyright_company_name;
                            } else {
                                $company_name = STORE_NAME;
                            }

                            $footer_copyright_text = "Copyright &copy; " . date('Y') . ' ' . $company_name . " All Rights Reserved"; ?>
                            <p title="<?php echo $footer_copyright_text; ?>"><?php echo $footer_copyright_text; ?></p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 text-center">
                        <?php if ($footer_logo_status == 1) { ?>
                            <a class="<?php if ($footer_logo_type == 'text') {
                                echo 'copy-right-text text-white';
                            } else {
                                echo 'footer-text-logo';
                            } ?>" href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                                <?php if ($footer_logo_type == 'default') { ?>
                                    Powered by <img src="<?php echo base_url('assets/images/ahr_logo_138X80_wt.png'); ?>">
                                <?php } else if ($footer_logo_type == 'text') { ?>
                                        Powered by <?php echo $footer_logo_text; ?>
                                <?php } else if ($footer_logo_type == 'logo') { ?>
                                            Powered by <img src="<?php echo AWS_S3_BUCKET_URL . $footer_logo_image; ?>"
                                                class="upload_logo_image">
                                <?php } ?>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                        <div class="social-links">
                            <ul>
                                <li>
                                    <a class="google-plus" href="<?php
                                    $g_url = get_slug_data('google_plus_url', 'settings');

                                    if (!empty($g_url)) {
                                        echo $g_url;
                                    } else {
                                        echo "https://plus.google.com/u/0/b/102383789585278120218/+Automotosocialjobs/posts";
                                    } ?>" target="_blank"><i class="fa fa-google-plus"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="twitter" href="<?php
                                    $t_url = get_slug_data('twitter_url', 'settings');

                                    if (!empty($t_url)) {
                                        echo $t_url;
                                    } else {
                                        echo "https://twitter.com/AutomotoSocial";
                                    } ?>" target="_blank"><i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="facebook" href="<?php
                                    $f_url = get_slug_data('facebook_url', 'settings');

                                    if (!empty($f_url)) {
                                        echo $f_url;
                                    } else {
                                        echo "https://www.facebook.com/automotosocialjobs";
                                    }
                                    ?>" target="_blank"><i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                                <li><a class="linkedin" href="<?php
                                $l_url = get_slug_data('linkedin_url', 'settings');
                                if (!empty($l_url)) {
                                    echo $l_url;
                                } else {
                                    echo "https://www.linkedin.com/grp/home?gid=6735083&goback=%2Egna_6735083";
                                }
                                ?>" target="_blank"><i class="fa fa-linkedin"></i></a>
                                </li>
                                <?php
                                $y_url = get_slug_data('youtube_url', 'settings');
                                if (!empty($y_url)) {
                                    ?>
                                    <li><a class="youtube" href="<?php echo $y_url; ?>" target="_blank"><i
                                                class="fa fa-youtube"></i></a></li>
                                <?php } ?>
                                <?php
                                $i_url = get_slug_data('instagram_url', 'settings');
                                if (!empty($i_url)) {
                                    ?>
                                    <li><a class="instagram" href="<?php echo $i_url; ?>" target="_blank"><i
                                                class="fa fa-instagram"></i></a></li>
                                <?php } ?>
                                <?php $gl_url = get_slug_data('glassdoor_url', 'settings');
                                if (!empty($gl_url)) { ?>
                                    <li><a class="glassdoor" href="<?php echo $gl_url; ?>" target="_blank"><img
                                                src="<?= base_url() ?>assets/images/glassdoor.png"></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

<?php } else { ?>
    <div class="copyright hidden-print">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="copy-right-text">
                        <?php
                        if ($copyright_status == 1) {
                            $company_name = $copyright_company_name;
                        } else {
                            $company_name = STORE_NAME;
                        }
                        ?>
                        <p>Copyright &copy; <?php echo date('Y') . ' ' . $company_name; ?> All Rights Reserved</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 text-center">
                    <?php if ($footer_logo_status == 1) { ?>
                        <a class="<?php if ($footer_logo_type == 'text') {
                            echo 'copy-right-text text-white';
                        } else {
                            echo 'footer-text-logo';
                        } ?>" href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                            <?php if ($footer_logo_type == 'default') { ?>
                                Powered by <img src="<?php echo base_url('assets/images/ahr_logo_138X80_wt.png'); ?>">
                            <?php } else if ($footer_logo_type == 'text') { ?>
                                    Powered by <?php echo $footer_logo_text; ?>
                            <?php } else if ($footer_logo_type == 'logo') { ?>
                                        Powered by <img src="<?php echo AWS_S3_BUCKET_URL . $footer_logo_image; ?>"
                                            class="upload_logo_image">
                            <?php } ?>
                        </a>
                    <?php } else { ?>
                        <a class="footer-text-logo" href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                            Powered by <img src="<?php echo base_url('assets/images/ahr_logo_138X80_wt.png'); ?>">
                        </a>
                    <?php } ?>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="social-links">
                        <ul>
                            <li><a class="google-plus" href="<?php
                            $g_url = get_slug_data('google_plus_url', 'settings');
                            if (!empty($g_url)) {
                                echo $g_url;
                            } else {
                                echo "https://plus.google.com/u/0/b/102383789585278120218/+Automotosocialjobs/posts";
                            }
                            ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li><a class="twitter" href="<?php
                            $t_url = get_slug_data('twitter_url', 'settings');
                            if (!empty($t_url)) {
                                echo $t_url;
                            } else {
                                echo "https://twitter.com/AutomotoSocial";
                            }
                            ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a class="facebook" href="<?php
                            $f_url = get_slug_data('facebook_url', 'settings');
                            if (!empty($f_url)) {
                                echo $f_url;
                            } else {
                                echo "https://www.facebook.com/automotosocialjobs";
                            }
                            ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li><a class="linkedin" href="<?php
                            $l_url = get_slug_data('linkedin_url', 'settings');
                            if (!empty($l_url)) {
                                echo $l_url;
                            } else {
                                echo "https://www.linkedin.com/grp/home?gid=6735083&goback=%2Egna_6735083";
                            }
                            ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <?php
                            $y_url = get_slug_data('youtube_url', 'settings');
                            if (!empty($y_url)) {
                                ?>
                                <li><a class="youtube" href="<?php echo $y_url; ?>" target="_blank"><i
                                            class="fa fa-youtube"></i></a></li>
                            <?php } ?>
                            <?php
                            $i_url = get_slug_data('instagram_url', 'settings');
                            if (!empty($i_url)) {
                                ?>
                                <li><a class="instagram" href="<?php echo $i_url; ?>" target="_blank"><i
                                            class="fa fa-instagram"></i></a></li>
                            <?php } ?>
                            <?php $gl_url = get_slug_data('glassdoor_url', 'settings');
                            if (!empty($gl_url)) { ?>
                                <li><a class="glassdoor" href="<?php echo $gl_url; ?>" target="_blank"><img
                                            src="<?= base_url() ?>assets/images/glassdoor.png"></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
<?php if ($apiURL): ?>
    <script>
        apiAccessToken = "<?= $apiAccessToken; ?>";
        apiURL = "<?= $apiURL; ?>";
    </script>
<?php endif; ?>
<!-- JS -->
<?php if (!$loadJsFiles) { ?>
    <script src="<?= main_url("public/v1/plugins/jquery/jquery-3.7.1.min.js?v=3.0"); ?>"></script>
<?php } ?>
<script src="<?= main_url("public/v1/plugins/bootstrap/js/bootstrap.min.js?v=3.0"); ?>"></script>
<script src="<?= main_url("public/v1/plugins/moment/moment.min.js?v=3.0"); ?>"></script>
<script src="<?= main_url("public/v1/plugins/moment/moment-timezone.min.js?v=3.0"); ?>"></script>
<!-- JS Bundles -->
<?= $pageJs ? GetScripts($pageJs) : ""; ?>
<?= bundleJs([
    "js/app_helper",
    "v1/app/js/global",
], "public/v1/app/", "global", true); ?>
<!--  -->
<?= $appJs ?? ""; ?>
<?php $this->load->view("v1/attendance/footer_scripts"); ?>
</body>

</html>