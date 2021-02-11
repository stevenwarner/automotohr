<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> back</a>
                </div>
                <?php if(isset($applicant)) { ?>
                    <div class="page-header">
                        <h1 class="section-ttile">My Hr Documents</h1>
                    </div>
                <?php } else if (isset($employee)) { ?>
                    <div class="page-header">
                        <h1 class="section-ttile">My Hr Documents</h1>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>