<div class="main" style="background: #fff;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a href="<?php echo base_url('hr_documents_management/my_documents'); ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i>  Documents</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="page-header">
                    <h1 class="section-ttile">View <?php echo $title; ?></h1>
                    <strong>Information:</strong> If you are unable to view the document, kindly reload the page.
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 pull-right">
                                <a href="<?php echo $print_url; ?>" target="_blank" class="btn btn-block blue-button">Print Form</a>
                            </div>
                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 pull-right">
                                <a href="<?php echo $download_url; ?>" class="btn btn-block blue-button">Download Form</a>
                            </div>
                        </div>
                        <hr>
                        <div class="hr-box" style="background: #fff;">
                            <div class="alert alert-info">
                                <strong><?php echo ucwords($title); ?></strong>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12" id="required_fields_div">
                                        <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em;">    
                                            <?php if (in_array($document_extension, ['pdf', 'csv'])) { ?>
                                                <object style="width:100%; height:80em;" data="<?php echo 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_name . '&embedded=true'; ?>"></object>
                                            <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_name; ?>"/>
                                            <?php } else if (in_array($document_extension, ['doc', 'docx', 'xlsx', 'xlx'])) { ?>
                                                <object style="width:100%; height:80em;" data="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_name); ?>"></object>
                                            <?php } ?>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>
</div>