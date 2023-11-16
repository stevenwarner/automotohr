<?php $pageHeader = getPageContent('header', true)["page"]["sections"]["section_1"]; ?>
<?php $pageFooter = getPageContent('footer', true)["page"]["sections"]; ?>
<?php $salesSupportDetail = getPageContent('contact_us', false)["page"]["sections"]["section_0"]; ?>
<footer class="footer-section">
    <div class="footer-space-adj">
        <div class="row margin-bottom-20">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                <div>
                    <img src="<?= image_url('/'); ?>Emboss-Logo.png" alt="embose logo" />
                    <p class="white-opacity-text">
                        <?= $pageFooter["section_0"]["logoText"]; ?>
                    </p>
                </div>
                <div class="margin-top-60">
                    <p class="stay-connect">
                        <?= $pageFooter["section_1"]["mainHeading"]; ?>
                    </p>
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
                    <a href="#"> Solutions</a>
                </p>

                <p class="p-bottom-adj second-text-adj f-w-500">
                    <a href="<?= main_url($pageHeader["subMenu1Link"]); ?>">
                        <?= ($pageHeader["subMenu1Text"]); ?>
                    </a>
                </p>
                <p class="p-bottom-adj second-text-adj f-w-500">
                    <a href="<?= main_url($pageHeader["subMenu2Link"]); ?>">
                        <?= ($pageHeader["subMenu2Text"]); ?>
                    </a>
                </p>
                <p class="p-bottom-adj second-text-adj f-w-500">
                    <a href="<?= main_url($pageHeader["subMenu3Link"]); ?>">
                        <?= ($pageHeader["subMenu3Text"]); ?>
                    </a>
                </p>
                <p class="p-bottom-adj second-text-adj f-w-500">
                    <a href="<?= main_url($pageHeader["subMenu4Link"]); ?>">
                        <?= ($pageHeader["subMenu4Text"]); ?>
                    </a>
                </p>
                <p class="p-bottom-adj second-text-adj f-w-500">
                    <a href="<?= main_url($pageHeader["subMenu5Link"]); ?>">
                        <?= ($pageHeader["subMenu5Text"]); ?>
                    </a>
                </p>
                <p class=" second-text-adj f-w-500">
                    <a href="<?= main_url($pageHeader["subMenu6Link"]); ?>">
                        <?= ($pageHeader["subMenu6Text"]); ?>
                    </a>
                </p>

                <div class="margin-top-40 margin-bottom-30">
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= main_url($pageFooter["section_2"]["menu1Link"]); ?>">
                            <?= ($pageFooter["section_2"]["menu1Text"]); ?>
                        </a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= main_url($pageFooter["section_2"]["menu2Link"]); ?>">
                            <?= ($pageFooter["section_2"]["menu2Text"]); ?>
                        </a>
                    </p>
                </div>

                <div class="book-demo-btn">
                    <a href="javascript:void(0)" class="jsButtonAnimate jsScheduleDemoPopup">
                        <?= ($pageFooter["section_2"]["menu3Text"]); ?>
                    </a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-2">
                <div>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= main_url($pageFooter["section_2"]["menu4Link"]); ?>">
                            <?= ($pageFooter["section_2"]["menu4Text"]); ?>
                        </a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= main_url($pageFooter["section_2"]["menu5Link"]); ?>">
                            <?= ($pageFooter["section_2"]["menu5Text"]); ?>
                        </a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= main_url($pageFooter["section_2"]["menu6Link"]); ?>">
                            <?= ($pageFooter["section_2"]["menu6Text"]); ?>
                        </a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= main_url($pageFooter["section_2"]["menu7Link"]); ?>">
                            <?= ($pageFooter["section_2"]["menu7Text"]); ?>
                        </a>
                    </p>
                    <div class="margin-top-40">
                        <p class="footer-text-three p-bottom-adj">
                            <a href="<?= main_url($pageFooter["section_2"]["menu8Link"]); ?>">
                                <?= ($pageFooter["section_2"]["menu8Text"]); ?>
                            </a>
                        </p>
                        <p class="footer-text-three">
                            <a href="<?= main_url($pageFooter["section_2"]["menu9Link"]); ?>">
                                <?= ($pageFooter["section_2"]["menu9Text"]); ?>
                            </a>
                        </p>
                        <p class="footer-text-three">
                            <a href="<?= main_url($pageFooter["section_2"]["menu10Link"]); ?>">
                                <?= ($pageFooter["section_2"]["menu10Text"]); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                <div>
                    <p class="footer-text-three">Sales</p>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons "><i class="fa-solid fa-phone"></i></a>
                        </div>
                        <a class="icon-text-adj" href="tel:<?= $salesSupportDetail["phoneNumberSales"]; ?>">
                            <?= $salesSupportDetail["phoneNumberSales"]; ?>
                        </a>
                    </div>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                        <a class="icon-text-adj" href="mailto:<?= $salesSupportDetail["emailSales"]; ?>"><?= $salesSupportDetail["emailSales"]; ?> </a>
                    </div>
                </div>
                <div class="margin-top-40">
                    <p class="footer-text-three">Technical</p>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-phone"></i></a>
                        </div>
                        <a class="icon-text-adj" href="tel:<?= $salesSupportDetail["phoneNumberTechnical"]; ?>"><?= $salesSupportDetail["phoneNumberTechnical"]; ?></a>
                    </div>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                        <a class="icon-text-adj" href="mailto:<?= $salesSupportDetail["emailAddressTechnical"]; ?>"><?= $salesSupportDetail["emailAddressTechnical"]; ?></a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="footer-hr" />
        <div class="row">
            <div class="col-sm-12 col-lg-6">
                <p class="margin-top-10">
                    ©2023 AutomotoHR All Rights Reserved
                </p>
            </div>
            <div class="col-sm-12 col-lg-6 text-right">
                <p class="inline-block">Powered by</p>
                <img src="<?= image_url('footerlogo.png'); ?>" alt="footer logo" />
            </div>
        </div>
    </div>
</footer>
<?php $this->load->view("v1/app/partials/schedule_demo_form_popup"); ?>
</body>

<?= $pageJs ? GetScripts($pageJs) : ''; ?>
<?= $appJs ?? ''; ?>

</html>