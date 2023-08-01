<div class="footer width_100 d-flex flex-column align-items-center pb-5 auto-footer-background-color">
    <div class="partner_btn_main position-relative width_80">
        <div class="partner_btn px-5">
            <div class="position-relative">
                <div class="partner_btn_section_bubble"></div>
                <div class="partner_btn_section_bubble_pink"></div>
            </div>
            <div class="width_100 d-flex flex-md-row flex-column justify-content-md-between justify-content-center align-items-center px-5 auto-footer-height">
                <h1 class="title text-white text-md-start text-center auto-footer-width">
                    Can we send you a check every month?
                </h1>

                <button class="button py-3 explore_btn touch-btn d-flex me-4 mt-md-0 mt-4 auto-footer-button">
                    <p class="mb-0 btn-text">Become a Partners</p>

                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="width_80 d-flex flex-wrap justify-content-lg-between align-items-lg-start align-items-center flex-lg-row flex-column mt- pt-5">
        <div class="footer_logo mt-4">
            <img src="<?= base_url() ?>assets/assets_new/images/footer_logo.png" alt="" />
            <p class="text-white text-end">
                Hire & Manage Great People AutomotoHR.com
            </p>
            <div class="mt-5 d-flex flex-column align-items-lg-start align-items-center">

                <h1 class="text-white footer-auto-logo-heading">Stay Connect</h1>
                <div class="d-flex align-items-center mt-2 footer-auto-logo-heading">
                    <?php
                    $y_url = get_slug_data('youtube_url', 'settings');
                    if (!empty($y_url)) { ?>
                        <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-icon-common">
                            <a class="simple-anchor anchar_tag auto-youtube-icon" href="<?php echo $y_url; ?>"><i class="fa-brands fa-youtube"></i></a>
                        </div>
                    <?php } ?>
                    <?php
                    $i_url = get_slug_data('instagram_url', 'settings');
                    if (!empty($i_url)) {
                    ?>
                        <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-icon-common">
                            <a class="simple-anchor anchar_tag auto-youtube-icon" href="<?php echo $i_url; ?>"><i class="fa-brands fa-instagram"></i></a>
                        </div>

                    <?php } ?>

                    <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-icon-common">
                        <a class="simple-anchor anchar_tag auto-youtube-icon" href="<?php
                                                                                    $t_url = get_slug_data('twitter_url', 'settings');
                                                                                    if (!empty($t_url)) {
                                                                                        echo $t_url;
                                                                                    } else {
                                                                                        echo "https://twitter.com/AutomotoSocial";
                                                                                    }
                                                                                    ?>" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                    </div>

                    <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-icon-common">
                        <a class="simple-anchor anchar_tag auto-youtube-icon" href="<?php
                                                                                    $f_url = get_slug_data('facebook_url', 'settings');
                                                                                    if (!empty($f_url)) {
                                                                                        echo $f_url;
                                                                                    } else {
                                                                                        echo "https://www.facebook.com/automotosocialjobs";
                                                                                    }
                                                                                    ?>" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                    <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-icon-common">
                        <a class="simple-anchor anchar_tag auto-youtube-icon" href="<?php
                                                                                    $l_url = get_slug_data('linkedin_url', 'settings');
                                                                                    if (!empty($l_url)) {
                                                                                        echo $l_url;
                                                                                    } else {
                                                                                        echo "https://www.linkedin.com/grp/home?gid=6735083&goback=%2Egna_6735083";
                                                                                    }
                                                                                    ?>" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <ul class="text-white mt-lg-0 mt-md-5 mt-lg-5 no-margin-bottom-onmobile d-flex flex-column align-items-lg-start align-items-center no-padding-onmobile auto-footer-ul">
                <li>
                    <a class="text-white simple-anchor anchar_tag"> Solutions </a>
                </li>
                <li class="mt-3">
                    <a class="text-white simple-anchor anchar_tag" href="<?= base_url('services/privacy-policy') ?>">Privacy Policy</a>
                </li>
                <li class="mt-3">
                    <a class="text-white simple-anchor anchar_tag" href="<?= base_url('login') ?>">Login</a>
                </li>
            </ul>
        </div>
        <div class="mt-4">
            <ul class="text-white mt-lg-0 mt-lg-5 mt-md-5 d-flex flex-column align-items-lg-start align-items-center no-padding-onmobile auto-footer-ul">
                <li>
                    <a class="text-white simple-anchor anchar_tag" href="<?= base_url('contact_us') ?>">Contact Us</a>
                </li>
                <li class="mt-3">
                    <a class="text-white simple-anchor anchar_tag" href="<?= base_url('services/sitemap') ?>">Site Map</a>
                </li>
                <li class="mt-3">
                    <a class="text-white simple-anchor anchar_tag" href="<?= base_url('affiliate_portal/login') ?>">Affiliate Partner Login</a>
                </li>
                <li class="mt-3">
                    <a class="text-white simple-anchor anchar_tag" href="<?= base_url('affiliate-program') ?>">Can we send you a check every month?</a>
                </li>
                <li class="mt-3">
                    <a class="text-white simple-anchor anchar_tag" href="<?= base_url('affiliate-program') ?>">Join our automotoHR affiliate program now!</a>
                </li>
            </ul>
        </div>
        <div class="mt-lg-4 mt-md-4 mt-sm-1">
            <div class="mt-lg-0 mt-lg-5 mt-md-5 d-flex flex-column align-items-lg-start align-items-center">
                <h1 class="text-white auto-footer-h1">Sales Support</h1>
                <div class="d-flex align-items-center mt-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-phone">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <a href="javascript:void(0);" class="text-white heading anchar_tag ms-3">
                        <?php echo TALENT_NETWORK_SALE_CONTACTNO; ?>
                    </a>
                </div>
                <div class="d-flex align-items-center mt-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-phone">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>" class="text-white heading anchar_tag ms-3">
                        <?php echo TALENT_NETWORK_SALES_EMAIL; ?>
                    </a>
                </div>
            </div>
            <div class="mt-5 d-flex flex-column align-items-lg-start align-items-center">
                <h1 class="text-white auto-technicial">Technical Support</h1>
                <div class="d-flex align-items-center mt-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-phone">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <a href="javascript:void(0);" class="text-white heading anchar_tag ms-3">
                        <?php echo TALENT_NETWORK_SUPPORT_CONTACTNO; ?>
                    </a>
                </div>
                <div class="d-flex align-items-center mt-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center auto-fa-phone">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>" class="text-white heading anchar_tag ms-3">
                        <?php echo TALENT_NETWORK_SALES_EMAIL; ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="copyright_section_footer d-md-flex d-none justify-content-between align-items-center mt-5 pt-4">
            <h1 class="detail text-white">
                <?php
                if ($copyright_status == 1) {
                    $company_name = $copyright_company_name;
                } else {
                    $company_name = STORE_NAME;
                }
                ?>

                © <?php echo date('Y') . ' ' . $company_name; ?> All Rights Reserved.
            </h1>
            <div class="d-flex align-items-center">
                <h1 class="detail text-white me-3">Powered by</h1>
                <img src="<?= base_url() ?>assets/assets_new/images/footer_logo.png" class="auto-copy-right-common" alt="" />
            </div>
        </div>
        <div class="copyright_section_footer d-md-none d-flex flex-column align-items-center mt-5 pt-4">
            <div class="d-flex align-items-center">
                <h1 class="detail text-white me-3">Powered by</h1>
                <img src="<?= base_url() ?>assets/assets_new/images/footer_logo.png" class="auto-copy-right-common" alt="" />
            </div>
            <h1 class="detail text-white mt-4">
                © <?php echo date('Y') . ' ' . $company_name; ?> All Rights Reserved.
            </h1>
        </div>
    </div>
</div>


</main>
</body>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/fac56683c8.js" crossorigin="anonymous"></script>

</html>