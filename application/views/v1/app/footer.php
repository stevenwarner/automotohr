<footer class="footer-section">
    <?php $footerContent = getPageContent('footer');
    ?>

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

                <p class="p-bottom-adj second-text-adj f-w-500"><a href="<?= base_url($footerContent['page']['products']['productoperations']['slug']) ?>"> <?php echo $footerContent['page']['products']['productoperations']['title'] ?> </a></p>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="<?= base_url($footerContent['page']['products']['recruitment']['slug']) ?>"> <?php echo $footerContent['page']['products']['recruitment']['title'] ?> </a></p>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="<?= base_url($footerContent['page']['products']['electroniconboarding']['slug']) ?>"> <?php echo $footerContent['page']['products']['electroniconboarding']['title'] ?></a></p>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="<?= base_url($footerContent['page']['products']['employeemanagement']['slug']) ?>"> <?php echo $footerContent['page']['products']['employeemanagement']['title'] ?> </a></li>
                <p class="p-bottom-adj second-text-adj f-w-500"><a href="<?= base_url($footerContent['page']['products']['payroll']['slug']) ?>"> <?php echo $footerContent['page']['products']['payroll']['title'] ?> </a></p>
                <p class=" second-text-adj f-w-500"><a href="<?= base_url($footerContent['page']['products']['compliance']['slug']) ?>"> <?php echo $footerContent['page']['products']['compliance']['title'] ?> </a></p>

                <div class="margin-top-40 margin-bottom-30">
                    <p class="footer-text-three p-bottom-adj"><a href="<?= base_url($footerContent['page']['affiliateprogram']['slug']) ?>"> <?php echo $footerContent['page']['affiliateprogram']['title'] ?></a></p>
                    <p class="footer-text-three p-bottom-adj"><a href="<?= base_url($footerContent['page']['privacypolicy']['slug']) ?>"> <?php echo $footerContent['page']['privacypolicy']['title'] ?> </a></p>
                </div>

                <div class="book-demo-btn">
                    <a href="#freedemo"> Book A Free Demo</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-2">
                <div>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= base_url($footerContent['page']['whyus']['slug']) ?>"><?php echo $footerContent['page']['whyus']['title'] ?></a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= base_url($footerContent['page']['aboutus']['slug']) ?>"><?php echo $footerContent['page']['aboutus']['title'] ?></a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= base_url($footerContent['page']['resources']['slug']) ?>"><?php echo $footerContent['page']['resources']['title'] ?></a>
                    </p>
                    <p class="footer-text-three p-bottom-adj">
                        <a href="<?= base_url($footerContent['page']['contactus']['slug']) ?>"><?php echo $footerContent['page']['contactus']['title'] ?></a>
                    </p>
                    <div class="margin-top-40">
                        <p class="footer-text-three p-bottom-adj">
                            <a href="<?= base_url($footerContent['page']['terms']['slug']) ?>"><?php echo $footerContent['page']['terms']['title'] ?></a>
                        </p>
                        <p class="footer-text-three"><a href="<?= base_url($footerContent['page']['sitemap']['slug']) ?>"><?php echo $footerContent['page']['sitemap']['title'] ?></a></p>
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
                        <a class="icon-text-adj" href="#"><?php echo $footerContent['page']['sales']['title'] ?></a>
                    </div>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                        <a class="icon-text-adj" href="mailto:<?php echo $footerContent['page']['sales']['slug'] ?>"><?php echo $footerContent['page']['sales']['slug'] ?> </a>
                    </div>
                </div>
                <div class="margin-top-40">
                    <p class="footer-text-three">Technical Support</p>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-phone"></i></a>
                        </div>
                        <a class="icon-text-adj" href="#"><?php echo $footerContent['page']['technical']['title'] ?></a>
                    </div>
                    <div class="flex-center margin-bottom-20">
                        <div class="anchor-span">
                            <a href="#" class="simple-anchor-icons"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                        <a class="icon-text-adj" href="mailto:<?php echo $footerContent['page']['technical']['slug'] ?>"><?php echo $footerContent['page']['technical']['slug'] ?> </a>
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

<?= $pageJs ? GetScripts($pageJs) : ''; ?>
<?= $appJs ?? ''; ?>

</html>