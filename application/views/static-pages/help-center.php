<div class="main-content">
    <div class="row">					
        <div class="col-md-12">
            <!-- Page Title Start -->
            <div class="hr-heading-strip">
                <div class="dual-color-heading">
                    <h2><?php echo STORE_NAME; ?><span> Help Center</span></h2>
                </div>
            </div>

<!--            <div class="col-md-10" style="float:none;margin:auto auto;">
                <figure class="align-medium">
                    <img src="<?= base_url() ?>assets/images/help-center.jpg" alt="" style="width:100%;">
                </figure>
                <br>
            </div>-->

            <!-- Page Title End -->
            <?php //if (!$this->session->userdata('logged_in') && $title != 'Register') { ?>
                <?php $this->load->view('main/demobuttons'); ?>
            <?php //} ?>

        </div>
    </div>
</div>