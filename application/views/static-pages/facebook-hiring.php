<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <header class="heading-title">
                    <h2 class="page-title">Leverage the power of Facebook.</h2>
                </header>
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
                                <h2 class="post-title">EASILY ADD A SOCIAL CAREER SITE</h2>
                                <p>Add a careers tab to your company Facebook page. Fans can search and apply for jobs.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/facebook_hiring_1.png" alt=""></figure>
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
                                <h2 class="post-title">TAP INTO A BILLION CANDIDATES</h2>
                                <p>Market your job postings like a targeted Facebook ad campaign.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 pull-left">
                        <div class="box-container">
                            <div class="vh-center-box">
                                <figure><img  class="img-responsive" src="<?= base_url() ?>assets/images/facebook_hiring_2.png" alt=""></figure>
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