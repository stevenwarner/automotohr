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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a id="back_btn" href="<?php echo base_url('manage_admin/affiliates')?>" class="black-btn pull-right"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-title">Application Details</span>
                                                </div>
                                            </div>
<!--                                            <div class="row">-->
<!--                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">-->
<!--                                                    <span class="page-title"> Reported By &nbsp;&nbsp;: --><?php //echo $que_ans[0]['report_type'] == 'confidential' ? ucwords($com_emp[0]['fname'] . " " . $com_emp[0]['lname']) : 'Anonymous';?><!--</span>-->
<!--                                                </div>-->
<!--                                            </div>-->
                                        </div>
                                    </div>
<!--                                    <div class="row">-->
<!--                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">-->
<!--                                            -->
<!--                                        </div>-->
<!--                                    </div>-->


                                        <?php if(sizeof($affiliation)>0) {
                                            $affiliation = $affiliation[0]; 
//                                            echo '<pre>'; print_r($affiliation); exit; 
                                            
                                            if (empty($affiliation['w8_form'])) {
                                                $w8_form_link = "javascript:void(0);";
                                                $w8_form_title = "W8-Form not found!";
                                            } else {
                                                $w8_form_link = AWS_S3_BUCKET_URL . $affiliation['w8_form'];
                                                $w8_form_title = 'W8 Form for '.ucfirst($affiliation['first_name']).' '.ucfirst($affiliation['last_name']);
                                            } 
                                            
                                            if (empty($affiliation['w9_form'])) {
                                                $w9_form_link = "javascript:void(0);";
                                                $w9_form_title = "W9 Form not found!";
                                            } else {
                                                $w9_form_link = AWS_S3_BUCKET_URL . $affiliation['w9_form'];
                                                $w9_form_title = 'W9 Form for '.ucfirst($affiliation['first_name']).' '.ucfirst($affiliation['last_name']);
                                            } ?>
                                                <div class="hr-widget" id="attachment_view">
                                                    <div class="attachment-header">
            <!--                                            <div class="form-title-section">
                                                            <h4>Attachments</h4>
                                                            <div class="form-btns">
                                                                <input type="button" value="edit" id="attachment_edit_button">
                                                            </div>
                                                        </div>-->
                                                        <div class="file-container">
                                                            <a data-toggle="modal" onclick="fLaunchModal(this);" data-preview-url="<?php echo $affiliation['w8_form']; ?>" data-download-url="<?php echo $affiliation['w8_form']; ?>" data-document-title="<?php echo $w8_form_title; ?>" href="javascript:void(0);">
                                                                <article>
                                                                    <figure><img src="<?php echo base_url() ?>assets/images/attachment-img.png"></figure>
                                                                    <div class="text">W8-Form</div>
                                                                </article>
                                                            </a>
                                                            <a data-toggle="modal" onclick="fLaunchModal(this);" data-preview-url="<?php echo $affiliation['w9_form']; ?>" data-download-url="<?php echo $affiliation['w9_form']; ?>" data-document-title="<?php echo $w9_form_title; ?>" href="javascript:void(0);">
                                                                <article>
                                                                    <figure><img src="<?php echo base_url() ?>assets/images/attachment-img.png"></figure>
                                                                    <div class="text">W9-Form</div>
                                                                </article>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">First Name</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo ucfirst($affiliation['first_name'])?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Last Name</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo ucfirst($affiliation['last_name'])?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Email</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['email']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Paypal Email</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['paypal_email']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Company</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['company'] != NULL && !empty($affiliation['company']) ? $affiliation['company'] : 'N/A'?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Street</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['street']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">City</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['city']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">State</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['state']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Zip Code</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['zip_code']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Country</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['country']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Method Of Promotion</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['method_of_promotion']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Website</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['website']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Contact Number</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['contact_number']?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Email List</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo $affiliation['email_list'] != NULL && !empty($affiliation['email_list']) ? $affiliation['email_list'] : 'Not Provided'?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Status</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php if($affiliation['status'] == 1){
                                                            echo 'Accepted';
                                                        }else if($affiliation['status'] == 2){
                                                            echo 'Rejected';
                                                        }else{
                                                            echo 'Pending';
                                                        }?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Requested Date</label>
                                                        <input type="text" readonly="readonly" name="incident_name"  value="<?php echo date('d M, Y',strtotime($affiliation['request_date']));?>" class="hr-form-fileds">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="notes">Special Notes</label>
                                                        <textarea name="notes" cols="40" rows="10" readonly="readonly" class="hr-form-fileds field-row-autoheight valid" aria-invalid="false"><?php echo $affiliation['special_notes'] !=NULL && !empty($affiliation['special_notes']) ? $affiliation['special_notes'] : 'N/A'?></textarea>
                                                    </div>
                                                </div>

                                                <?php if($affiliation['status'] == 0) { ?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<!--                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
                                                        <a href="javascipt:;" <?php echo $affiliation['status'] != 0 ? 'disabled="disabled" style="pointer-events: none;"' : ''?> id="<?= $affiliation['sid']?>" class="btn btn-success btn-sm accept" title="Accept">Accept</a>

                                                        <a href="javascipt:;" <?php echo $affiliation['status'] != 0 ? 'disabled="disabled" style="pointer-events: none;"' : ''?> id="<?= $affiliation['sid']?>" class="btn btn-danger btn-sm reject" title="Reject">Reject</a>
<!--                                                        </div>-->
                                                    </div>
                                                <?php } ?>
                                            <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="w8form_modal" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog modal-lg" style="min-height: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">W8-FORM</h4>
                <?php   if ($w8_form_link != "W8-Form not found!") { ?>
                    <a href="<?php echo AWS_S3_BUCKET_URL . $w8_form_link; ?>" download="download" >Download</a>
                <?php   } ?>
            </div>
            <?php   if($w8_form_link != "W8-Form not found!") { ?>
                        <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?= $w8_form_link ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
            <?php   } else { ?>
                        <span class="nofile-found">W8-Form not found!</span>
            <?php   } ?>
        </div>
    </div>
</div>

<div id="w9form_modal" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog modal-lg" style="min-height: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">W8-FORM</h4>
                <?php   if ($w9_form_link != "W9-Form not found!") { ?>
                    <a href="<?php echo AWS_S3_BUCKET_URL . $w9_form_link; ?>" download="download" >Download</a>
                <?php   } ?>
            </div>
            <?php   if($w9_form_link != "W9-Form not found!") { ?>
                        <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?= $w9_form_link ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
            <?php   } else { ?>
                        <span class="nofile-found">W9-Form not found!</span>
            <?php   } ?>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function () {
        $('#admin').chosen();
        $('#assigned-admin').submit(function () {
            if($('#admin').val()==null){
                alertify.error('Please Assign An Admin');
                return false;
            }
        });
        $('.accept').click(function(){
            var id = $(this).attr('id');
            alertify.confirm('Confirmation', "Are you sure you want to Accept this Application?",
                function () {
                    $.ajax({
                        type: 'POST',
                        data:{
                            status:1,
                            id: id
                        },
                        url: '<?= base_url('manage_admin/affiliates/accept_reject')?>',
                        success: function(data){
                            if(data == 'exist'){
                                window.location.href = '<?php echo base_url('manage_admin/affiliates/view_details/')?>/' + id;
                            }else{
                                window.location.href = '<?php echo base_url('manage_admin/marketing_agencies/edit_marketing_agency/')?>/' + data;
                            }
                        },
                        error: function(){

                        }
                    });
                },
                function () {
                    alertify.error('Canceled');
                });
        });
        $('.reject').click(function(){
            var id = $(this).attr('id');
            alertify.confirm('Confirmation', "Are you sure you want to Reject this Application?",
                function () {
                    $.ajax({
                        type: 'POST',
                        data:{
                            status:2,
                            id: id
                        },
                        url: '<?= base_url('manage_admin/affiliates/accept_reject')?>',
                        success: function(data){
                            location.reload();
                        },
                        error: function(){

                        }
                    });
                },
                function () {
                    alertify.error('Canceled');
                });
        });
    });
    
    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var type = document_preview_url.split(".");
        var file_type = type[type.length - 1];
        var modal_content = '';
        var footer_content = '';
        var iframe_url = 'https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '&embedded=true';
        var is_document = false;

        if (document_preview_url != '') {
            if (file_type == 'jpg' || file_type == 'jpe' || file_type == 'jpeg' || file_type == 'png' || file_type == 'gif'){
                modal_content = '<img src="<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '" style="width:100%; height:500px;" />';
            } else {
                is_document = true;
                modal_content = '<iframe id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
            }
            
            footer_content = '<a class="btn btn-success" href="<?php echo AWS_S3_BUCKET_URL; ?>' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#file_preview_modal').modal("toggle");

        if (is_document) {
            document.getElementById('preview_iframe').contentWindow.location = iframe_url;
        }
    }
</script>