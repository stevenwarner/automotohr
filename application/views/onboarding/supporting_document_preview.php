<div class="main" style="background: #fff;">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo $learning_center_url; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> My Learning Center</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Back to Video</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    </div>
                </div>
                <div class="page-header">
                    <h1 class="section-ttile"><?php echo $title;?></h1>
                    <strong>Information:</strong> If you are unable to view the document, kindly reload the page.
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div id="jstopdf" class="hr-box" style="background: #fff;">
                            <div class="alert alert-info">
                                <strong><?php echo ucwords($supporting_document['upload_file_title']); ?></strong>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12" id="required_fields_div">
                                        <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em;">
                                            <?php 
                                                $extension = strtolower($supporting_document['upload_file_extension']); 
                                                $path = $supporting_document['upload_file_name'];
                                            ?>
                                            <?php if ($extension == 'pdf') { ?>
                                                    <iframe src="<?php echo 'https://docs.google.com/gview?url=' . urlencode(AWS_S3_BUCKET_URL . $path) . '&embedded=true'; ?>"
                                                    id="preview_iframe" class="uploaded-file-preview"
                                                    style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } else if ($extension == 'doc' || $extension == 'docx' || $extension == 'xlsx') { ?>
                                                    <iframe sandbox="allow-same-origin allow-scripts allow-popups allow-forms" src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $path); ?>"
                                                    id="preview_iframe" class="uploaded-file-preview"
                                                    style="width:100%; height:80em;" frameborder="0"></iframe>
                                                    
                                            <?php } ?>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading stev_blue">
                        <strong>Supporting Document Action: Download</strong>
                    </div>
                    <div class="panel-body">
                        <div class="document-action-required">
                            <?php echo $preview_status; ?>                                           
                        </div>
                        <div class="document-action-required">
                            <?php echo $preview_on; ?>                                           
                        </div>
                        <div class="document-action-required">
                              <?php echo $download_status; ?>                                         
                        </div>

                        <div class="document-action-required">
                            <?php if($tracking_document['downloaded_date'] != NULL ) {
                                echo $download_on;
                            } ?>

                            <a target="_blank" href="<?php echo $download_button_action;?>" id="download_btn_aclick" class="btn <?php echo $download_button_css; ?> pull-right">
                                <?php echo $download_button_text; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





