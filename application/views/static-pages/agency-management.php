<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <!-- Page Title Start -->
                <header class="heading-title">
                    <h2 class="page-title">Find and manage the best recruiters.</h2>
                </header>
                <!-- Page Title End -->
                <!-- ARM Resources Start -->
                <div class="amr-universal-section">
                    <article class="v1">
                        <div class="text">
                            <div class="amr-arrow-v1"><i class="fa fa-angle-right"></i></div>
                            <h2 class="post-title">MANAGE AGENCIES IN ONE PLACE</h2>
                            <p>Provide one place for all your recruiting agencies. Control your 
                                costs and delivery.</p>
                        </div>
                        <figure><img src="<?= base_url() ?>assets/images/img-resource1.png" alt="Image"></figure>
                    </article>
                    <article class="v2">
                        <div class="text">
                            <div class="amr-arrow-v2"><i class="fa fa-angle-left"></i></div>
                            <h2 class="post-title">MEASURE RECRUITER PERFORMANCE</h2>
                            <p>Understand time to submit, CV to interview ratio & 
                                overall cost per hire.</p>
                        </div>
                        <figure><img src="<?= base_url() ?>assets/images/img-resource2.png" alt="Image"></figure>
                    </article>
                    <article class="v1">
                        <div class="text">
                            <div class="amr-arrow-v1"><i class="fa fa-angle-right"></i></div>
                            <h2 class="post-title">TAP INTO OUR AGENCY MARKETPLACE</h2>
                            <p>Invite your preferred recruiter or discover new ones from 
                                our marketplace.</p>
                        </div>
                        <figure><img src="<?= base_url() ?>assets/images/img-resource3.png" alt="Image"></figure>
                    </article>
                </div>
                <!-- ARM Resources End -->
                <?php if (!$this->session->userdata('logged_in') && $title != 'Register') { ?> 
                    <?php $this->load->view('main/demobuttons'); ?>
                <?php } ?> 
            </div>
        </div>
    </div>
</div>