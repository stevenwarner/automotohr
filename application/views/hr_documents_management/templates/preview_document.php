<?php $company_name = ucwords($session['company_detail']['CompanyName']); ?>
<?php $pdBtn = getPDBTN($document, 'btn-info'); ?> 
<div class="main" style="background: #fff;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a href="<?php echo $back_url; ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i>  Documents</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="page-header">
                    <h1 class="section-ttile">Review & Sign <?php echo $doc == 'o' ? 'Offer Letter' : 'Assigned Document'; ?>
                        <span class="pull-right">
                            <a 
                                class="btn btn-info btn-orange btn-block csRadius5"
                                target="_blank" 
                                href="<?= $print_url; ?>" 
                            ><i class="fa fa-print" aria-hidden="true"></i> Print</a>

                            <a 
                                class="btn btn-black btn-block csRadius5"
                                target="_blank" 
                                href="<?= $download_url; ?>" 
                            ><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                        </span>
                    </h1>
                    <strong>Information:</strong> If you are unable to view the document, kindly reload the page.
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div id="jstopdf" class="hr-box" style="background: #fff; word-break: break-all;">
                            <div class="alert alert-info js-hybrid-iframe">
                                <strong><?php echo ucwords($document['document_title']); ?></strong>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12" id="required_fields_div" style="padding: 0 30px">
                                        <?php if ((isset($document['document_type']) && $document['document_type'] == 'hybrid_document') || (isset($document['letter_type']) && $document['letter_type'] == 'hybrid_document')) { ?>

                                            <?php 
                                                $document_filename = !empty($document['document_s3_name']) ? $document['document_s3_name'] : '';
                                                $document_file = pathinfo($document_filename);
                                                $document_extension = strtolower($document['document_extension']);

                                                //
                                                $t = explode('.', $document_filename);
                                                $de = $t[sizeof($t) - 1];
                                                //
                                                if($de != $document_extension) $document_extension = $de;
                                            ?>     

                                            <?php if (in_array($document_extension, ['csv'])) { ?>
                                                <iframe src="<?php echo 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" class="uploaded-file-preview js-hybrid-iframe"  style="width:100%; height:80em;" frameborder="0"></iframe>

                                            <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                <img class="img-responsive js-hybrid-iframe" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>"/>
                                            <?php } else if (in_array($document_extension, ['doc', 'docx', 'xlsx', 'xlx', 'pptx', 'ppt'])) { ?>
                                                <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } else { ?>
                                                <iframe src="<?php echo 'https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $document_filename); ?>&embedded=true" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } ?>

                                            <div class="alert alert-info js-hybrid-iframe">
                                                <strong>Description</strong>
                                            </div>

                                            <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0; ?>

                                            <?php echo html_entity_decode($document['document_description']); ?>

                                        <?php } else if ($document['document_type'] == 'uploaded' || $document['offer_letter_type'] == 'uploaded') { ?>
                                            <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em;">
                                                <?php 
                                                    $document_filename = !empty($document['document_s3_name']) ? $document['document_s3_name'] : '';
                                                    $document_file = pathinfo($document_filename);
                                                    $document_extension = strtolower($document['document_extension']);

                                                    //
                                                    $t = explode('.', $document_filename);
                                                    $de = $t[sizeof($t) - 1];
                                                    //
                                                    if($de != $document_extension) $document_extension = $de;
                                                ?>     

                                                <?php if (in_array($document_extension, ['csv'])) { ?>
                                                    <iframe src="<?php echo 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>

                                                <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>"/>
                                                <?php } else if (in_array($document_extension, ['doc', 'docx', 'xlsx', 'xlx', 'pptx', 'ppt'])) { ?>
                                                    <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } else { ?>
                                                    <iframe src="<?php echo 'https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $document_filename); ?>&embedded=true" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <?php echo html_entity_decode($document['document_description']); ?>
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

<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>


