<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <!-- Page Title Start -->
                <header class="heading-title">
                    <h2 class="page-title">Never leave a great candidate waiting.</h2>
                </header>
                <!-- Page Title End -->               
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
                                <h2 class="post-title">NEVER LEAVE A GREAT CANDIDATE WAITING.</h2>
                                <p>In a world gone mobile, your hiring platform has to be mobile optimized if you expect to engage candidates and hiring managers. <?php echo STORE_NAME; ?> is built for the way people really work. We make it easy for you to manage your entire hiring process on the go from our new mobile optimized HR platform. Make referrals, schedule interviews, review and rate candidates, collaborate with your team and view results on the go, from your phone, tablet or any other mobile device.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/img-resource56-new.png" alt=""></figure>
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
                                <h2 class="post-title">MOBILE CAREERS. MOBILE JOB ADS. MOBILE APPLY.</h2>
                                <p>Let candidates search, view and 1-click apply for jobs anywhere, anytime. Less dropoffs.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/img-resource57-new.png" alt=""></figure>
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
                                <h2 class="post-title">HIRING TEAM MOBILE OPTIMIZED HR PLATFORM</h2>
                                <p>View jobs, make social referrals and give feedback all from your mobile devices.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/img-resource58-new.png" alt=""></figure>
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
                                <h2 class="post-title">EMAIL NOTIFICATIONS</h2>
                                <p>Stay on top of your hiring activity with email alerts and weekly digests.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/img-resource59.png" alt=""></figure>
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
