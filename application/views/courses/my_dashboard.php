<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <a href="<?php echo $back_url; ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i> Documents</a>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        </div>
                    </div>

                </div>
                <div class="page-header">
                    <h1 class="section-ttile">Review & Sign <?php echo $doc == 'o' ? 'Offer Letter' : 'Assigned Document';?></h1>
                    <strong>Information:</strong> If you are unable to view the course, kindly reload the page.
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div id="jstopdf" class="hr-box" style="background: #fff;">
                            <div class="alert alert-info">
                                <strong><?php echo ucwords($document['document_title']); ?> <?php echo $document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="You must complete this document to finish the onboarding process."></i>' : '' ;?></strong>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12" id="required_fields_div">
                                        <?php if($document['document_type'] == 'hybrid_document' || $document['offer_letter_type'] == 'hybrid_document') { ?>
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

                                                if (in_array($document_extension, ['pdf', 'csv'])) {
                                                    $allowed_mime_types = ''; ?>
                                                    <iframe src="<?php echo  'https://docs.google.com/gview?url='.AWS_S3_BUCKET_URL.$document_filename.'&embedded=true'; ?>" class="uploaded-file-preview js-hybrid-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>"/>
                                                <?php } else if (in_array($document_extension, ['doc', 'docx', 'xls', 'xlsx'])) { ?>
                                                    <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview js-hybrid-preview"
                                                            style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } else { ?>
                                                    <iframe src="<?php echo  'https://docs.google.com/gview?url='.AWS_S3_BUCKET_URL.$document_filename.'&embedded=true'; ?>" class="uploaded-file-preview js-hybrid-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } ?>

                                            </div>
                                            <br />
                                            <br />
                                            <div class="alert alert-info js-hybrid-preview">
                                                <strong>Description</strong>
                                            </div>
                                            <?php
                                            $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;
                                            if ($consent == 0 || ($consent == 1 && !empty($document['form_input_data']))) {
                                                echo html_entity_decode($document['document_description']);
                                            } elseif ($consent == 1) { ?>
                                                <iframe src="<?php echo $document['submitted_description']; ?>" name="printf" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } ?>
                                        <?php } else if($document['document_type'] == 'uploaded' || $document['offer_letter_type'] == 'uploaded') { ?>
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

                                                if (in_array($document_extension, ['pdf', 'csv'])) {
                                                    $allowed_mime_types = ''; ?>
                                                    <iframe src="<?php echo  'https://docs.google.com/gview?url='.AWS_S3_BUCKET_URL.$document_filename.'&embedded=true'; ?>" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>"/>
                                                <?php } else if (in_array($document_extension, ['doc', 'docx', 'xls', 'xlsx'])) { ?>
                                                    <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview"
                                                            style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } ?>
                                            </div>
                                        <?php } else {
                                            $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;
                                            if ($consent == 0 || ($consent == 1 && !empty($document['form_input_data']))) {
                                                echo html_entity_decode($document['document_description']);
                                            } elseif ($consent == 1) { ?>
                                                <iframe src="<?php echo $document['submitted_description']; ?>" name="printf" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php }

                                        }
                                        // add signature here for generated document ?>
                                        <?php if ($save_offer_letter_type == 'save_only') { ?>
                                            <form id="user_consent_form" enctype="multipart/form-data" method="post" action="">
                                                <input type="hidden" name="perform_action" value="sign_document" />
                                                <input type="hidden" name="page_content" value="">
                                                <input type="hidden" name="save_signature" id="save_signature" value="no">
                                                <input type="hidden" name="save_signature_initial" id="save_signature_initial" value="no">
                                                <input type="hidden" name="save_signature_date" id="save_signature_date" value="no">
                                                <input type="hidden" name="save_PDF" id="save_PDF" value="yes">
                                                <input type="hidden" name="save_input_values" id="save_input_values" value="">
                                                <input type="hidden" name="user_consent"  value="1">
                                                <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;
                                                
                                                if ($consent == 0) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-12 text-center">
                                                            <button onclick="func_save_document_only();" type="button" class="btn blue-button break-word-text disabled_consent_btn">Save Document</button>
                                                        </div>
                                                    </div>
                                                <?php } ?> 
                                            </form>
                                        <?php } else if (($document['signature_required'] == 1 || $save_offer_letter_type == 'consent_only') && ($document_type == 'generated' || $document_type == 'hybrid_document' || ($document_type == 'offer_letter' && ($document['offer_letter_type'] == 'generated' || $document['offer_letter_type'] == 'hybrid_document')))) { ?>
                                            <form id="user_consent_form" enctype="multipart/form-data" method="post" action="<?php echo $save_post_url; ?>">
                                                <input type="hidden" name="perform_action" value="sign_document" />
                                                <input type="hidden" name="page_content" value="">
                                                <input type="hidden" name="save_signature" id="save_signature" value="yes">
                                                <input type="hidden" name="save_signature_initial" id="save_signature_initial" value="yes">
                                                <input type="hidden" name="save_signature_date" id="save_signature_date" value="yes">
                                                <input type="hidden" name="save_PDF" id="save_PDF" value="yes">
                                                <input type="hidden" name="save_input_values" id="save_input_values" value="">
                                                <hr />
                                                <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;
                                                if ($consent == 0) { ?>
                                                    <div class="row">
                                                        <div class="col-xs-12 text-justify">
                                                            <?php                                                   
                                                                echo '<p>'.str_replace("{{company_name}}", $company_name, SIGNATURE_CONSENT_HEADING).'</p>';
                                                                echo '<p>'.SIGNATURE_CONSENT_TITLE.'</p>'; 
                                                                echo '<p>'.str_replace("{{company_name}}", $company_name, SIGNATURE_CONSENT_DESCRIPTION).'</p>'; 
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0; ?>
                                                            <label class="control control--checkbox">
                                                                 <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                                                <input <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?>  <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo $consent == 1 ? 'checked="checked"' : ''; ?> />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } ?>


                                                <?php if($signed_flag == false) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-12 text-center">
                                                            <button <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text disabled_consent_btn" <?php echo $consent == 1 ? 'disabled="disabled"' : ''; ?>><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                <?php } ?>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if($document['document_sid'] != 0){ ?> <!-- For All other than Manual Upload -->
                            <?php if (!empty($attached_video) && $attached_video['video_required'] == 1 && !empty($attached_video['video_source'])) { ?>
                                <div class="hr-box">
                                    <div class="alert alert-info">
                                        <strong>Attachment Video</strong>
                                    </div>
                                    <div class="hr-innerpadding">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <figure class="">
                                                    <?php $source = $attached_video['video_source']; ?>
                                                    <?php if($source == 'youtube') { ?>
                                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $attached_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                    <?php } elseif($source == 'vimeo') { ?>
                                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $attached_video['video_url']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                    <?php } else {?>
                                                        <video controls>
                                                            <source src="<?php echo base_url().'assets/uploaded_videos/'.$attached_video['video_url']; ?>" type='video/mp4'>
                                                        </video>
                                                    <?php } ?>
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php   if($document['acknowledgment_required'] == 1 || $document['download_required'] == 1 || $document['signature_required'] == 1 ) { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php if($document['acknowledgment_required'] == 1 && $document['signature_required'] == 0 && $save_offer_letter_type != 'consent_only') { ?>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading stev_blue">
                                                    <strong><?php echo $acknowledgment_action_title; ?></strong>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="document-action-required">
                                                        <?php echo $acknowledgment_action_desc; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php echo $acknowledgement_status;?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <form id="form_acknowledge_document" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="acknowledge_document" />
                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo $unique_sid; ?>" />
                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $document['user_type']; ?>" />
                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $document['user_sid']; ?>" />
                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['document_sid']; ?>" />
                                                        </form>
                                                        <?php if($document['acknowledged_date'] != NULL ) {
                                                            echo '<b>Acknowledged On: </b>';
                                                            echo convert_date_to_frontend_format($document['acknowledged_date']);
                                                        } ?>
                                                        <button onclick="<?php echo $acknowledgement_button_action;?>" type="button" class="btn <?php echo $acknowledgement_button_css; ?> pull-right"><?php echo $acknowledgement_button_txt;?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if($document['download_required'] == 1) { ?>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading stev_blue">
                                                    <strong><?php echo $download_action_title; ?></strong>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="document-action-required">
                                                        <?php echo $download_action_desc; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php echo $download_status; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php if($document['downloaded_date'] != NULL ) {
                                                            echo '<b>Downloaded On: </b>';
                                                            echo convert_date_to_frontend_format($document['downloaded_date']);
                                                        } ?>

                                                        <a target="_blank" href="<?php echo $download_button_action; ?>" id="download_btn_click" class="btn <?php echo $download_button_css; ?> pull-right"
                                                            onclick="save_print()">
                                                            <?php echo $download_button_txt;?>
                                                        </a>
                                                        <a target="_blank" href="<?php echo $print_button_action; ?>" class="btn pull-right <?php echo $download_button_css; ?>" style="margin-right: 10px;" id="print_btn_click">
                                                            print Document
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if($document['signature_required'] == 1 && ($document_type == 'uploaded' || $document['offer_letter_type'] == 'uploaded')) { ?>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading stev_blue">
                                                    <strong><?php echo $uploaded_action_title; ?></strong>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="document-action-required">
                                                        <?php echo $uploaded_action_desc; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php echo $uploaded_status; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php if($document['uploaded_date'] != NULL ) {
                                                            echo '<b>Uploaded On: </b>';
                                                            echo convert_date_to_frontend_format($document['uploaded_date']);
                                                        } ?>

                                                        <form id="form_upload_file" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo $unique_sid; ?>" />
                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $document['user_type']; ?>" />
                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $document['user_sid']; ?>" />
                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['document_sid']; ?>" />
                                                            <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $document['company_sid']; ?>" />

                                                            <div class="row">
                                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                    <div class="form-wrp width-280">
                                                                        <div class="form-group">
                                                                            <div class="upload-file form-control">
                                                                                <span class="selected-file" id="name_upload_file">No file selected</span>
                                                                                <input name="upload_file" id="upload_file" type="file" />
                                                                                <a href="javascript:;" style="background: #0000ff;">Choose File</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <button type="submit" class="btn <?php echo $uploaded_button_css; ?> btn-equalizer btn-block"><?php echo $uploaded_button_txt; ?></button>
                                                                    <?php if(!empty($document['uploaded_file'])) { ?>
                                                                        <?php $document_filename = $document['uploaded_file'];?>
                                                                        <?php $document_file = pathinfo($document_filename); ?>
                                                                        <?php $document_extension = $document_file['extension']; ?>
                                                                        <a class="btn blue-button btn-equalizer btn-block"
                                                                           href="javascript:void(0);"
                                                                           onclick="fLaunchModal(this);"
                                                                           data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                           data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                           data-file-name="<?php echo $document_filename; ?>"
                                                                           data-document-title="<?php echo $document_filename; ?>"
                                                                           data-download-sid="<?php echo $document['sid']; ?>"
                                                                           data-preview-ext="<?php echo $document_extension ?>">Preview Uploaded</a>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if($document['signature_required'] == 1 && $document_type == 'generated') { ?>
                                            <!--do nothing for now-->
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php   } ?>
                        <?php } ?>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

