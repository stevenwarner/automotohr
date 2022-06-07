<div class="main" style="background: #fff;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a href="<?php echo $back_url; ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i> Documents</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="page-header">
                    <h1 class="section-ttile">Review & Sign <?php echo $doc == 'o' ? 'Offer Letter' : 'Assigned Document'; ?>
                        <span class="pull-right">
                            <a class="btn btn-info btn-orange btn-block csRadius5" target="_blank" href="<?= $print_url; ?>"><i class="fa fa-print" aria-hidden="true"></i> Print</a>

                            <a class="btn btn-black btn-block csRadius5" target="_blank" href="<?= $download_url; ?>"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                        </span>
                    </h1>
                    <strong>Information:</strong> If you are unable to view the document, kindly reload the page.
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php
                        if ($doc_type == 'w4') {
                            $this->load->view('form_w4/index_ems_2020_preview');
                        }
                        if ($doc_type == 'w9') {
                            $this->load->view('form_w9/index_preview');
                        }
                        if ($doc_type == 'i9') {
                            $this->load->view('form_i9/index_ems_preview');
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>