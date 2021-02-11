<?php
$company_name = ucwords($session['company_detail']['CompanyName']);
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message');?>
                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a href="<?php echo base_url('hr_documents_management/my_documents'); ?>"
                        class="btn btn-block blue-button"><i class="fa fa-angle-left"></i> Documents</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header"><span class="section-ttile"><?php echo $title; ?></span></div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info">
                            <strong><?=$pre_form['document_name'];?></strong>
                        </div>
                        <div class="hr-innerpadding">
                            <iframe src="<?=AWS_S3_BUCKET_URL.$pre_form['s3_filename'];?>?embedded=true" style="width: 100%; height: 80rem;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
