<?php $activePages = getAllActivePages();?>
<main>
    <main>
        <div class="Sitemap-heading">
            <h1>Site Map</h1>
        </div>
        <div class="row site-map-page">
            <div class="col-xs-12 w-100 column-flex-center pb-5">
                <div class="w-80 padding_60">
                    <h3 class="home-heading text-lg-start text-center">Home page</h3>
                    <div class="row">
                        <div class="col-xxl-6 col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="subtitle_text text-lg-start text-center">
                                Products
                            </p>
                            <ul class="Sitemap_content light-li list_item_center">
                                <?php
                                if (in_array('people-operations', $activePages)) {
                                ?>
                                    <li><a href="<?php echo base_url('products/people-operations') ?>">People Operations</a></li>
                                <?php } ?>
                                <?php
                                if (in_array('recruitment', $activePages)) {
                                ?>
                                    <li><a href="<?php echo base_url('products/recruitment') ?>">Recruitment</a></li>
                                <?php } ?>

                                <?php
                                if (in_array('hr-electronic-onboarding', $activePages)) {
                                ?>
                                    <li><a href="<?php echo base_url('products/hr-electronic-onboarding') ?>">HR Electronic Onboarding</a></li>
                                <?php } ?>
                                <?php
                                if (in_array('employee-management', $activePages)) {
                                ?>
                                    <li><a href="<?php echo base_url('products/employee-management') ?>">Employee Management</a></li>
                                <?php } ?>

                                <?php
                                if (in_array('payroll', $activePages)) {
                                ?>
                                    <li><a href="<?php echo base_url('products/payroll') ?>">Payroll</a></li>
                                <?php } ?>
                                <?php
                                if (in_array('compliance', $activePages)) {
                                ?>
                                    <li><a href="<?php echo base_url('products/compliance') ?>">Compliance</a></li>
                                <?php } ?>
                            </ul>
                        </div>

                        <div class="col-xxl-6 col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="subtitle_text text-lg-start text-center">
                                <a class="subtitle_text dark-li PL-zero text-lg-start text-center" href="<?php echo base_url('schedule-your-no-obligation-consultation'); ?>">Schedule your No Obligation Consultation </a><br />
                                
                                <?php
                                if (in_array('affiliate-program', $activePages)) {
                                ?>
                                <a class="subtitle_text dark-li PL-zero text-lg-start text-center" href="<?php echo base_url('affiliate-program'); ?>">Join Our Affiliate Program </a><br />
                                <?php } ?>

                                <a class="subtitle_text dark-li PL-zero text-lg-start text-center" href="<?php echo base_url('affiliate_portal/login'); ?>"> Affiliate Log In </a>
                            </p>
                            <ul class="Sitemap_content light-li list_item_center">
                                <li><a href="<?php echo base_url('forgot-password') ?>"> Forgot Password</a></li>
                                <li><a href="<?php echo base_url('affiliate-program'); ?>"> Not an AutomotoHR Affiliate Yet?</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-xxl-6 col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="subtitle_text dark-li PL-zero text-lg-start text-center">
                            <?php
                                if (in_array('why-us', $activePages)) {
                                ?>
                                <li><a href="<?php echo base_url('why-us'); ?>">Why Us?</a></li>
                                <?php }?>
                                <?php
                                if (in_array('about-us', $activePages)) {
                                ?>
                                <li><a href="<?php echo base_url('about-us'); ?>">About Us</a></li>
                                <?php }?>
                                <li><a href="<?php echo base_url('contact-us'); ?>">Contact Us</a></li>
                                <li><a href="<?php echo base_url('resources'); ?>">Resources</a></li>
                                <?php
                                if (in_array('terms-of-service', $activePages)) {
                                ?>
                                <li><a href="<?php echo base_url('terms-of-service'); ?>">Terms Of Service</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <h3 class="subtitle_text text-lg-start text-center">
                                Log In
                            </h3>
                            <ul class="Sitemap_content list_item_center light-li">
                                <li><a href="<?php echo base_url('login') ?>"> Login </a></li>
                                <li><a href="<?php echo base_url('forgot-password') ?>"> Forgot Password </a></li>
                                <li><a href="<?php echo base_url('executive_admin'); ?>"> Executive Admin Login</a></li>
                                <li><a href="<?php echo base_url('schedule-your-no-obligation-consultation'); ?>"> Schedule your No Obligation Consultation</a></li>
                            </ul>
                            <h3 class="subtitle_text text-lg-start text-center">
                            <?php
                                if (in_array('privacy-policy', $activePages)) {
                                ?>
                                <a class="subtitle_text dark-li PL-zero text-lg-start text-center" href="<?php echo base_url('privacy-policy'); ?>"> Privacy Policy
                                    <?php }?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</main>