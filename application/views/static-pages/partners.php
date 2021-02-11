<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <!-- Page Title Start -->
                <header class="heading-title">
                    <h2 class="page-title">Become A Partner-<br>Join the <?php echo STORE_NAME; ?> Marketplace & Gain Access To Potential Auto <br>Industry Customers that are actively searching for Your Services.</h2>
                </header>
                <!-- Page Title End -->
                <!-- ARM Partners Start -->
                <div class="partners-section">
                    <article>
                        <!-- AMR Separator Start -->
                        <div class="arm-separator">
                            <span><i class="fa fa-angle-down"></i></span>
                        </div>
                        <!-- AMR Separator End -->
                        <div class="partners-box">
                            <h2>Job Boards</h2>
                            <p><?php echo STORE_NAME; ?> features the best-performing niche & generalist job boards all in one place. Start seeing jobs posted from our growing customer base today.</p>
                            <a class="site-btn" href="<?php echo base_url(); ?>contact_us">get started</a>
                        </div>
                    </article>
                    <article>
                        <!-- AMR Separator Start -->
                        <div class="arm-separator">
                            <span><i class="fa fa-angle-down"></i></span>
                        </div>
                        <!-- AMR Separator End -->
                        <div class="partners-box">
                            <h2>HR</h2>
                            <p>Our recruiter marketplace connects you to <?php echo STORE_NAME; ?> customers with jobs to fill - as well as to state-of-the-art tools for managing your requisitions. Join today.</p>
                            <a class="site-btn" href="<?php echo base_url(); ?>contact_us">get started</a>
                        </div>
                    </article>
                    <article>
                        <!-- AMR Separator Start -->
                        <div class="arm-separator">
                            <span><i class="fa fa-angle-down"></i></span>
                        </div>
                        <!-- AMR Separator End -->
                        <div class="partners-box">
                            <h2>Databases</h2>
                            <p><?php echo STORE_NAME; ?> connects employers seeking talent with databases of passive and active candidates. We provide the customers and you provide candidate profiles. Start today.</p>
                            <a class="site-btn" href="<?php echo base_url(); ?>contact_us">get started</a>
                        </div>
                    </article>
                    <article>
                        <!-- AMR Separator Start -->
                        <div class="arm-separator">
                            <span><i class="fa fa-angle-down"></i></span>
                        </div>
                        <!-- AMR Separator End -->
                        <div class="partners-box">
                            <h2>Assessments</h2>
                            <p>Thousands of employers manage their applicant flow using <?php echo STORE_NAME; ?> each day. Help them make better hiring decisions by selling your screening tools to them directly. Get started today.</p>
                            <a class="site-btn" href="<?php echo base_url(); ?>contact_us">get started</a>
                        </div>
                    </article>
                </div>
                <!-- ARM Partners End -->                 
            </div>
        </div>
    </div>
    <?php if (!$this->session->userdata('logged_in') && $title != 'Register') { ?> 
        <?php $this->load->view('main/demobuttons'); ?>
    <?php } ?>
</div>