<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <!-- Page Title Start -->
                <header class="heading-title">
                    <h2 class="page-title">Manage your candidate lifecycle from <br>applicant to interview to hire.
                    </h2>
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
                                <h2 class="post-title">ALL CANDIDATES IN ONE PLACE</h2>
                                <p>All candidates from all sources in one place. From career page to job boards to social referrals.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/tiles2.png" alt=""></figure>
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
                                <h2 class="post-title">COMPLETE APPLICANT TRACKING</h2>
                                <p>Provide a compliant audit trail of the entire applicant history.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/candidate_management_2.png" alt=""></figure>
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
                                <h2 class="post-title">INTERVIEW SCHEDULING</h2>
                                <p>Schedule interviews with multiple people and share calendar invites to all parties.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/candidate_management_3.png" alt=""></figure>
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
                                <h2 class="post-title">SINGLE CANDIDATE PROFILE</h2>
                                <p>View the complete and accurate history of a candidate’s relationship with your company. See all positions a candidate has applied to, including past ratings and full profile information.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url('assets/images/candidate_management_steven.png'); ?>" alt=""></figure>
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
                                <h2 class="post-title">JOB NOTES</h2>
                                <p>Create or edit notes to store more information about jobs. Notes are now easily searchable on the job list page.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/candidate_management_5.png" alt=""></figure>
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
                                <h2 class="post-title">CENTRALIZED MESSAGING</h2>
                                <p>Centralize all candidate communication in <?php echo STORE_NAME; ?>. Email correspondence is copied into <?php echo STORE_NAME; ?>, and attachments are automatically added to the candidate profile. Quickly access all candidate messages to get the full history of communication at your fingertips.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/candidate_management_6.png" alt=""></figure>
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