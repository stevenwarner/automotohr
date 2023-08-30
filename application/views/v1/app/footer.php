<footer class="footer-section">
    <div class="footer-space-adj">
        <div class="row margin-bottom-20">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                <div>
                    <img src="<?= base_url('assets/v1/app/images/'); ?>Emboss-Logo.png" alt="embose logo" />
                    <p class="white-opacity-text">Hire & Manage Great People</p>
                    <p class="white-opacity-text text-left">AutomotoHR.com</p>
                </div>
                <div class="margin-top-60">
                    <p class="stay-connect">Stay Connect</p>


                    <div class="inline-flex">
                        <div class="anchor-span">
                            <?php
                            $y_url = get_slug_data('youtube_url', 'settings');
                            if (!empty($y_url)) {
                            ?>
                                <a href="<?php echo $y_url; ?>" class="simple-anchor-icons"><i class="fa-brands fa-youtube"></i></a>
                            <?php } ?>
                        </div>
                        <?php
                        $i_url = get_slug_data('instagram_url', 'settings');
                        if (!empty($i_url)) {
                        ?>
                            <div class="anchor-span">
                                <a href="<?php echo $i_url; ?>" class="simple-anchor-icons"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                        <?php } ?>
                        <div class="anchor-span">
                            <a href="<?php
                                        $t_url = get_slug_data('twitter_url', 'settings');
                                        if (!empty($t_url)) {
                                            echo $t_url;
                                        } else {
                                            echo "https://twitter.com/AutomotoSocial";
                                        }
                                        ?>" class="simple-anchor-icons"><i class="fa-brands fa-twitter"></i></a>
                        </div>
                        <div class="anchor-span">
                            <a href="<?php
                                        $f_url = get_slug_data('facebook_url', 'settings');
                                        if (!empty($f_url)) {
                                            echo $f_url;
                                        } else {
                                            echo "https://www.facebook.com/automotosocialjobs";
                                        }
                                        ?>" class="simple-anchor-icons"><i class="fa-brands fa-facebook"></i></a>
                        </div>
                        <div class="anchor-span">
                            <a href="<?php
                                        $l_url = get_slug_data('linkedin_url', 'settings');
                                        if (!empty($l_url)) {
                                            echo $l_url;
                                        } else {
                                            echo "https://www.linkedin.com/grp/home?gid=6735083&goback=%2Egna_6735083";
                                        }
                                        ?>" class="simple-anchor-icons"><i class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 section-space-adj">
                <p class="footer-text-three">
                    <a href="#"> Products</a>
                </p>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="product/people-operations"> Product Operations </a></p>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="product-recruitment"> Recruitment </a></p>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="product/hr-electronic-onboarding"> HR Electronic Onboarding </a></p>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="product-employee-management"> Employee Management </a></li>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="product-payroll"> Payroll </a></p>
                <p class=" second-text-adj f-w-500"><a href="product-compliance"> Compliance </a></p>

                <div class="margin-top-40 margin-bottom-30">
                    <p class="footer-text-three p-bottom-adj"><a href="affiliate_portal/login"> Affiliate Program</a></p>
                    <p class="footer-text-three p-bottom-adj"><a href="services/privacy-policy"> Privacy Policy </a></p>
                </div>

                <div class="book-demo-btn">
                    <a href="<?= base_url('/schedule_your_free_demo'); ?>"> Book A Free Demo</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-2">
                <div>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="#">Why Us?</a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= base_url('/services/about-us'); ?>">About Us</a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="#">Resources</a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= base_url('/contact_us'); ?>">Contact Us</a>
                    </p>
                    <div class="margin-top-40">
                        <p class="footer-text-three p-bottom-adj">
                            <a href="terms-of-service">Terms Of Service </a>
                        </p>
                        <p class="footer-text-three"><a href="services/sitemap">Sitemap</a></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                <div>
                    <p class="footer-text-three">Sales Support</p>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons "><i class="fa-solid fa-phone"></i></a>
                        </div>
                        <a class="icon-text-adj" href="#"><?php echo TALENT_NETWORK_SALE_CONTACTNO; ?></a>
                    </div>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                        <a class="icon-text-adj" href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>">accounts@automotohr.com </a>
                    </div>
                </div>
                <div class="margin-top-40">
                    <p class="footer-text-three">Technical Support</p>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-phone"></i></a>
                        </div>
                        <a class="icon-text-adj" href="#"><?php echo TALENT_NETWORK_SUPPORT_CONTACTNO; ?></a>
                    </div>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                        <a class="icon-text-adj" href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>">accounts@automotohr.com </a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="footer-hr" />
        <div class="row">
            <div class="col-sm-12 col-lg-6">
                <p class="margin-top-10">©2023 AutomotoHR.All Rights Reserved</p>
            </div>
            <div class="col-sm-12 col-lg-6 text-right">
                <p class="inline-block">Powered by</p>
                <img src="<?= base_url('assets/v1/app/images/'); ?>footerlogo.png" alt="footer logo" />
            </div>
        </div>
    </div>
</footer>
</body>
<?= $appJs ?? ''; ?>

</html>