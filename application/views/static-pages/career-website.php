<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <!-- Page Title Start -->
                <header class="heading-title">
                    <h2 class="page-title">Make your website your best brand advocate.</h2>
                </header>
                <div class="career-page-builder">
                    <div class="top-text">
                        <h2>CAREER PAGE BUILDER</h2>
                        <p>Candidates want to work for organizations with an amazing web presence, and our customizable careers site helps you carry your brand through with an interface that looks great on desktop and mobile.</p>
                        <p>Our recruitment software will create a branded careers page for your organization that includes all of the openings you've created. With a click of a button, this page will update with each new opening you add.</p>
                        <p>Your branded careers site URL is <a href="javascript:;">companyname.<?php echo STORE_DOMAIN; ?></a>. You can customize your careers site within the recruiting software by uploading your company logo, adding a link to your website and including an introduction. You can easily share every opening on the site individually over social media and job search engines and boards. After sharing the link to your opening, candidates across all channels will visit this site and apply through the embedded application form. And because the site is responsive, it will look great on phones and tablets, too.</p>
                    </div>
                    <figure><img src="<?= base_url() ?>assets/images/career-website-mac.jpg" alt="<?php echo STORE_NAME; ?> theme4"></figure>
                </div> 
            </div>
        </div>
    </div>
    <div class="amr-universal-section">
        <div class="static-blocks">
            <div class="container">
                <div class="grid-columns">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <h2 class="post-title">KEEP YOUR JOBS UPDATED</h2>
                                <p>Add job widget to list jobs on your career page. By location, function, or other custom fields.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/career_website_1.png" alt=""></figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="static-blocks">
            <div class="container">
                <div class="grid-columns">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-right">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <h2 class="post-title">MAKE IT YOUR OWN</h2>
                                <p>Fully customize your career website to match your brand with our Posting API.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/select_your_theme_ahr.JPG" alt="Theme Management System - <?php echo STORE_NAME; ?>"></figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="static-blocks">
            <div class="container">
                <div class="grid-columns">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <h2 class="post-title">SEO OPTIMIZED JOB ADS</h2>
                                <p>Leverage your existing website traffic with auto-generated SEO job ads.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/career_website_3.png" alt=""></figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!$this->session->userdata('logged_in') && $title != 'Register') { ?> 
        <?php $this->load->view('main/demobuttons'); ?>
    <?php } ?>
</div>