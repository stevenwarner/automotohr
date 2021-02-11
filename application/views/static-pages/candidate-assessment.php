<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <!-- Page Title Start -->
                <header class="heading-title">
                    <h2 class="page-title">Assess candidates to make the right hire.</h2>
                </header>
                <!-- Page Title End -->
                <!-- ARM Resources Start -->
                <div class="amr-universal-section">
                    <article class="v1">
                        <div class="text">
                            <div class="amr-arrow-v1"><i class="fa fa-angle-right"></i></div>
                            <h2 class="post-title">ASSESSMENT ON-DEMAND</h2>
                            <p>One platform with assessments available on-demand. No integration or contract needed.</p>
                        </div>
                        <figure><img src="<?= base_url() ?>assets/images/img-resource11-new.png" alt=""></figure>
                    </article>
                    <article class="v2">
                        <div class="text">
                            <div class="amr-arrow-v2"><i class="fa fa-angle-left"></i></div>
                            <h2 class="post-title">BEHAVIORAL AND SKILLS TESTING</h2>
                            <p>Evaluate candidate aptitude with a variety of skills tests to determine job and cultural fit.</p>
                        </div>
                        <figure><img src="<?= base_url() ?>assets/images/img-resource12.png" alt=""></figure>
                    </article>
                    <article class="v1">
                        <div class="text">
                            <div class="amr-arrow-v1"><i class="fa fa-angle-right"></i></div>
                            <h2 class="post-title">REFERENCE CHECKS</h2>
                            <p>Collect structured 360 degree feedback from previous employers and colleagues.</p>
                        </div>
                        <figure><img src="<?= base_url() ?>assets/images/img-resource13.png" alt=""></figure>
                    </article>
                </div>
                <!-- ARM Resources End -->                 
            </div>
        </div>
    </div>
    <?php if (!$this->session->userdata('logged_in') && $title != 'Register') { ?> 
        <?php $this->load->view('main/demobuttons'); ?>
    <?php } ?>
</div>