<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
            <div class="row">
                <div class="inner-content">
                    <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                        <div class="dashboard-content">
                            <div class="dash-inner-block">
                                <div class="row">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="heading-title page-title">
                                            <h2 class="page-title"><?php echo ucwords($marketing_agency_documents[0]['full_name']); ?></h2>
                                            <a href="<?php echo base_url('manage_admin/marketing_agencies/edit_marketing_agency/' . $marketing_agency_sid)?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Market Agency</a>
                                        </div>
                                        <div class="add-new-company">
                                            <form id="form_documents">
                                                <?php if(!empty($marketing_agency_documents)) { ?>
                                                    <div class="heading-title">
                                                        <span class="page-title">Automated Forms</span>
                                                    </div>
                                                    <div class="row">  
                                                        <?php $marketing_agency_documents = $marketing_agency_documents[0]; ?>

                                                        <?php if(!empty($marketing_agency_documents['eula'])) { ?>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <label class="package_label" for="eula">
                                                                    <div class="img-thumbnail text-center package-info-box eq-height">
                                                                        <figure><i class="fa fa-file-o" style="font-size: 75px"></i></figure>
                                                                        <br />
                                                                        <div class="caption">
                                                                            <p>Affiliate End User License Agreement</p>
                                                                        </div>
                                                                        <input class="select-package"  type="checkbox" id="eula" name="forms[]" value="eula" data-k="<?php echo $marketing_agency_documents['eula']['verification_key']; ?>"  />
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        <?php }?>
                                                    </div>

                                                    <div class="heading-title">
                                                        <span class="page-title">Uploaded Documents</span>
                                                    </div>
                                                    <div class="row">
                                                        <?php if(!empty($marketing_agency_documents['uploaded_documents'])) { ?>
                                                            <?php foreach($marketing_agency_documents['uploaded_documents'] as $uploaded_document) { ?>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                    <label class="package_label" for="document_checkbox_<?php echo $uploaded_document['sid']; ?>">
                                                                        <div class="img-thumbnail text-center package-info-box eq-height">
                                                                            <figure>
                                                                                <i class="fa fa-file-o" style="font-size: 75px"></i>
                                                                            </figure>
                                                                            <br />
                                                                            <div class="caption">
                                                                                <p><?php echo $uploaded_document['document_name']; ?></p>
                                                                            </div>
                                                                            <input class="select-package"  type="checkbox" id="document_checkbox_<?php echo $uploaded_document['sid']; ?>" name="documents[]" value="document_<?php echo $uploaded_document['sid']; ?>" data-k="<?php echo $uploaded_document['verification_key']; ?>" />
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            <?php }?>
                                                        <?php } else { ?>
                                                            <div class="col-xs-12">
                                                                <h4>No Document Found</h4>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="heading-title">
                                                        <span class="page-title">Send Affiliate End User License Agreement</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row send-email-to">
                                                <div class="col-xs-12">
                                                    <ul class="nav nav-tabs">
                                                        <li class="active"><a data-toggle="tab" href="#to_single_email">To Email</a></li>
                                                        <li ><a data-toggle="tab" href="#to_company_admin">To Marketing Agency</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div id="to_single_email" class="tab-pane fade in active">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="hr-user-form">
                                                                        <form id="form_send_to_single_email" method="post" enctype="multipart/form-data" action="<?php echo base_url('manage_admin/marketing_agency_documents/send/' . $marketing_agency_sid); ?>">
                                                                            <input type="hidden" id="eula" name="eula" value="0" class="eula" />
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="send_to_single_email" />
                                                                            <input type="hidden" id="marketing_agency_sid" name="marketing_agency_sid" value="<?php echo $marketing_agency_sid; ?>" />
                                                                            <input type="hidden" id="marketing_agency_name" name="marketing_agency_name" value="<?php echo $marketing_agency_documents['full_name']; ?>" />

                                                                            <?php if(!empty($marketing_agency_documents['uploaded_documents'])) { ?>
                                                                                <?php foreach($marketing_agency_documents['uploaded_documents'] as $uploaded_document) { ?>
                                                                                    <input type="hidden" class="document_<?php echo $uploaded_document['sid']; ?>" id="document_<?php echo $uploaded_document['sid']; ?>" name="documents[]" value="0" />
                                                                                <?php }?>
                                                                            <?php } ?>

                                                                            <ul>
                                                                                <li>
                                                                                    <label for="email">Email <span class="hr-required">*</span></label>
                                                                                    <div class="hr-fields-wrap">
                                                                                        <input type="email" data-rule-email="true" required data-msg-email="Please Provide a valid Email" class="hr-form-fileds" name="marketing_agency_email" id="email" />
                                                                                    </div>
                                                                                </li>
<!--                                                                                <li>-->
<!--                                                                                    <label for="message">Message</label>-->
<!--                                                                                    <div class="hr-fields-wrap">-->
<!--                                                                                        <textarea style="padding:10px; height:200px;" class="hr-form-fileds ckeditor" name="message">--><?php //echo set_value('message'); ?><!--</textarea>-->
<!--                                                                                    </div>-->
<!--                                                                                </li>-->
                                                                                <li>
                                                                                    <button type="button" class="site-btn" onclick="fValidateDocumentsAndSubmit('form_send_to_single_email');">Send To Email</button>
                                                                                </li>
                                                                            </ul>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="to_company_admin" class="tab-pane fade">
                                                            <div class="hr-user-form">
                                                                <form id="form_send_to_admin" method="post" enctype="multipart/form-data" action="<?php echo base_url('manage_admin/marketing_agency_documents/send/' . $marketing_agency_sid); ?>">
                                                                    <input type="hidden" id="eula" name="eula" value="0" class="eula" />
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="send_to_market_agency" />
                                                                    <input type="hidden" id="marketing_agency_sid" name="marketing_agency_sid" value="<?php echo $marketing_agency_sid; ?>" />
                                                                    <input type="hidden" id="marketing_agency_name" name="marketing_agency_name" value="<?php echo $marketing_agency_documents['full_name']; ?>" />
                                                                    <input type="hidden" id="marketing_agency_email" name="marketing_agency_email" value="<?php echo $marketing_agency_documents['email']; ?>" />
                                                                    <?php if(!empty($marketing_agency_documents['uploaded_documents'])) { ?>
                                                                        <?php foreach($marketing_agency_documents['uploaded_documents'] as $uploaded_document) { ?>
                                                                            <input type="hidden" class="document_<?php echo $uploaded_document['sid']; ?>" id="document_<?php echo $uploaded_document['sid']; ?>" name="documents[]" value="0" />
                                                                        <?php }?>
                                                                    <?php } ?>
                                                                    <button type="button" class="site-btn" onclick="fValidateDocumentsAndSubmit('form_send_to_admin');">Send To Marketing Agency</button>
                                                                </form>
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
                </div>
            </div>
    </div>
</div>
<script type="text/javascript">
    function fValidateDocumentsAndSubmit(form_id){
        var checked_docs = $('.select-package:checked');

        if(checked_docs.length > 0){
            if(form_id == 'form_send_to_single_email'){
                if($('#email').val() == ''){
                    alertify.error('Please Provide Email');
                    return false;
                }
            }
            $('#'+form_id).submit();
        } else {
            alertify.error('Please select document(s)');
        }
    }

    $(document).ready(function () {
        $('.select-package').click(function () {
            $('.select-package:not(:checked)').parent().removeClass("selected-package");
            $('.select-package:checked').parent().addClass("selected-package");

            var checked =  $(this).prop('checked');
            if(checked){
                $('.'+ $(this).val()).val($(this).attr('data-k'));
            } else {
                $('.'+ $(this).val()).val(0);
            }
        });

        $('.select-package').trigger('click');
    });

    // Multiselect
    var config = {
      '.chosen-select'           : {}
    }
    
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
</script>