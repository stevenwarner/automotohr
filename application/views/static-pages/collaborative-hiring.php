<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <!-- Page Title Start -->
                <header class="heading-title">
                    <h2 class="page-title">Engage your hiring teams, naturally.</h2>
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
                                <h2 class="post-title">COLLABORATIVE FEEDBACK</h2>
                                <p>Enable your hiring teams to make the best hires possible by providing them with the right level of information on jobs and candidates. Manage who can see and share sensitive data. Assign users to one of six different roles: Admin, Executive, Hiring Manager, Recruiter, Manager, Employee. Based on the role, each user has access to specific information according to their involvement in the hiring process.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/collaborative_hiring_1.png" alt=""></figure>
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
                                <h2 class="post-title">HIRING TEAM ROLES</h2>
                                <p>Enable your hiring teams to make the best hires possible by providing them with the right level of information on jobs and candidates. Manage who can see and share sensitive data. Assign users to one of five different roles: Executive, Hiring Manager, Recruiter, Coordinator or Interviewer. Based on the role, each user has access to specific information according to their involvement in the hiring process.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/collaborative_hiring_2.png" alt=""></figure>
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
                                <h2 class="post-title">RESTRICTED USER</h2>
                                <p>Manage which users can post jobs and spend money in the <?php echo STORE_NAME; ?> store.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/collaborative_hiring_3.png" alt=""></figure>
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
                                <h2 class="post-title">SCREENING QUESTIONS</h2>
                                <p>Customize a set of screening questions to meet your hiring needs. Screen candidates for specific skill sets, qualities or other attributes. Choose from a library of EEO and OFCCP compliant questions or, create your own. You can even create screening question sets for specific jobs or locations.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/collaborative_hiring_4.png" alt=""></figure>
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
                                <h2 class="post-title">JOB APPROVALS</h2>
                                <p>Now you can easily assign job approvers right from the <?php echo STORE_NAME; ?> Job Creation page - no need for a separate workflow. Job approvers can approve in just one click from an mail or the app. Once approved, post away!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/collaborative_hiring_5.png" alt=""></figure>
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