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
                                        <h1 class="page-title"><i class="fa fa-file-o"></i>Forms And Documents</h1>
                                        <a href="<?php echo base_url('manage_admin/marketing_agencies/edit_marketing_agency/' . $marketing_agency_sid)?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Marketing Agency</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="heading-title">
                                                    <h2 class="page-title"><?php echo ucwords($company_documents[0]['full_name']); ?></h2>
                                                </div>
                                                <div class="hr-box">
                                                    <div class="hr-box-header"><h4 class="hr-registered">Marketing Agency Documents</h4></div>
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 250px;" class="text-left" colspan="1" rowspan="2">Document Name</th>
                                                                    <th class="text-center" colspan="2">Status</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $company_documents = $company_documents[0];
                                                                $eula_status = 'not sent';
                                                                if (!empty($company_documents['eula'])) {
                                                                    $eula_status = $company_documents['eula']['status'];
                                                                }?>
                                                                    <tr>
                                                                        <td>
                                                                            Affiliate End User License Agreement<br>
                                                                            <?php   if ($company_documents['status'] == 1) { ?>
                                                                                        <span style="color:green;">Active</span>
                                                                            <?php   } else { ?>
                                                                                        <span style="color:red;">In-Active</span>
                                                                            <?php   } ?>

                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $eula_status)); ?>"><?php echo ucwords(str_replace('-', ' ', $eula_status)) ?></span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if (strtolower($eula_status) == 'not sent' || strtolower($eula_status) == 'generated' || strtolower($eula_status) == 'pre-filled' || strtolower($eula_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($eula_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </td>
                                                                        <?php if (strtolower($eula_status) == 'not sent') { ?>
                                                                            <td class="text-center">
                                                                                <form id="form_generate_eula_<?php echo $company_documents['sid'] ?>" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_form" />
                                                                                    <input type="hidden" id="form_to_send" name="form_to_send" value="eula" />
                                                                                    <input type="hidden" id="marketing_agency_sid" name="marketing_agency_sid" value="<?php echo $company_documents['sid']; ?>" />
                                                                                    <button type="button" class="hr-edit-btn btn-block" onclick="fSendForm('eula', 'generate', '<?php echo ucwords($company_documents['full_name']); ?>', '<?php echo $company_documents['sid']; ?>');">Generate</button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'generated') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_affiliate_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Pre-fill</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'pre-filled' || strtolower($eula_status) == 'sent') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_affiliate_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Edit</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'signed') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_affiliate_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/view'); ?>" class="hr-edit-btn btn-block">View</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            Form W9 Fillable<br>
                                                                            <?php   if ($company_documents['status'] == 1) { ?>
                                                                                        <span style="color:green;">Active</span>
                                                                            <?php   } else { ?>
                                                                                        <span style="color:red;">In-Active</span>
                                                                            <?php   } ?>

                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span>
                                                                                <?php echo ucwords(str_replace('-', ' ', $form_w9_status)) ?>
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php $w9_status = strtolower($form_w9_status); ?>
                                                                            <?php if ($w9_status == 'not sent') { ?>
                                                                                <img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                                            <?php } elseif ($w9_status == 'sent') { ?>
                                                                                <img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                                            <?php } elseif ($w9_status == 'filled') { ?>
                                                                                <img src="<?php echo site_url('assets/manage_admin/images/bulb-green.png'); ?>">
                                                                            <?php } ?>
                                                                        </td>
                                                                        <?php if ($w9_status == 'not sent') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('manage_admin/marketing_agency_documents/assign_w9_form'.'/'.$marketing_agency_sid); ?>" class="hr-edit-btn btn-block">Sent</a>
                                                                            </td>
                                                                        <?php } elseif ($w9_status == 'sent' || $w9_status == 'filled') { ?>
                                                                            <td class="text-center">
                                                                                <a href="<?=base_url();?>/manage_admin/marketing_agency_documents/print_download_w9_form/<?=$pre_form['sid'];?>/print" class="btn btn-success" target="_blank" placement="top" title="Print W9"><i class="fa fa-print"></i></a>
                                                                                <a href="<?=base_url();?>/manage_admin/marketing_agency_documents/print_download_w9_form/<?=$pre_form['sid'];?>/download" class="btn btn-success" target="_blank" placement="top" title="Download W9"><i class="fa fa-download"></i></a>
                                                                                <a href="javascript:void(0)"  onclick="view_w9_function('<?php echo $marketing_agency_sid; ?>')" class="btn btn-success" placement="top" title="View W9"><i class="fa fa-eye"></i></a>
                                                                            </td>    
                                                                        <?php } ?>
                                                                    </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>                                           
                                                </div>
                                            </div>
                                        </div>
                                        <hr />

                                        <?php if (isset($company_sid) && $company_sid > 0) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="heading-title">
                                                        <h2 class="page-title">Documents</h2>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="hr-box-header"></div>
                                                            <div class="table-responsive table-outer">
                                                                <table class="table table-bordered table-hover table-stripped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center col-lg-3">Document Name</th>
                                                                            <th class="text-center col-lg-4">Admin Uploaded</th>
                                                                            <th class="text-center col-lg-4">Client Uploaded</th>
                                                                            <th class="text-center col-lg-1">Status</th>
                                                                            <th class="text-center col-lg-3">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (isset($companies_uploaded_documents) && !empty($companies_uploaded_documents)) { ?>
                                                                            <?php foreach ($companies_uploaded_documents as $document) { ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo $document['document_name']; ?></td>
                                                                                    <td class="text-center">

                                                                                        <?php echo convert_date_to_frontend_format($document['insert_date'], true); ?> <br />
                                                                                        <?php if ($document['aws_document_name'] != '') { ?>
                                                                                            
                                                                                            <a target="_blank" href="<?=AWS_S3_BUCKET_URL.$document['aws_document_name'];?>" class="btn btn-success" placement="top" title="Print document"><i class="fa fa-print"></i></a>
                                                                                            <a href="<?=base_url('manage_admin/Marketing_agency_documents/download/'.( $document['aws_document_name'] ).'')?>" class="btn btn-success" placement="top" title="Download document"><i class="fa fa-download"></i></a>

                                                                                            <a href="javascript:void(0);" onclick="fLaunchModal(this);" class=" btn btn-success" data-preview-url="<?php echo $document['aws_document_name']; ?>" data-download-url="<?php echo $document['aws_document_name']; ?>" data-document-title="<?php echo $document['document_name']; ?>"  title="View document"><i class = "fa fa-eye"></i></a>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php echo $document['client_upload_date'] != '' && $document['client_upload_date'] != '0000-00-00 00:00:00' ? convert_date_to_frontend_format($document['client_upload_date'], true) : 'N/A'; ?> <br />
                                                                                        <?php if ($document['client_aws_filename'] != '') { ?>
                                                                                            <a target="_blank" href="<?=AWS_S3_BUCKET_URL.$document['client_aws_filename'];?>" class="btn btn-success" placement="top" title="Print document"><i class="fa fa-print"></i></a>
                                                                                            <a href="<?=base_url('manage_admin/Marketing_agency_documents/download/'.( $document['client_aws_filename'] ).'')?>" class="btn btn-success" placement="top" title="Download document"><i class="fa fa-download"></i></a>

                                                                                            <a href="javascript:void(0);" onclick="fLaunchModal(this);" class=" btn btn-success" data-preview-url="<?php echo $document['client_aws_filename']; ?>" data-download-url="<?php echo $document['client_aws_filename']; ?>" data-document-title="<?php echo $document['client_aws_filename']; ?>"  title="View document"><i class = "fa fa-eye"></i></a>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="text-center"><?php echo ucwords($document['status']); ?></td>
                                                                                    <td class="text-center">
                                                                                        <?php if(!$document['active_status']){?>
                                                                                            <a href="javascript:void(0);" class="btn btn-success active" id="<?php echo $document['sid'];?>" data-attr="<?php echo $document['sid'];?>">Activate</a>
                                                                                        <?php } else{ ?>
                                                                                            <a href="javascript:void(0);" class="btn btn-danger deactive" id="<?php echo $document['sid'];?>" data-attr="<?php echo $document['sid'];?>">De-Activate</a>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td colspan="5" class="no-data">No Documents</td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="hr-box-header hr-box-footer"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="heading-title">
                                                                <h2 class="page-title">Upload Additional Document</h2>
                                                            </div>
                                                            <div class="row">

                                                                <form id="form_upload_document" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                        <div class="upload-file form-control">
                                                                            <span class="selected-file" id="name_docs">No file selected</span>
                                                                            <input name="documents_and_forms" id="docs" type="file" onchange="check_file('docs')">
                                                                            <a href="javascript:;">Choose File</a>
                                                                        </div>
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
                                                                        <input type="hidden" id="marketing_agency_sid" name="marketing_agency_sid" value="<?php echo $marketing_agency_sid; ?>" />
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                        <button class="btn btn-success btn-block" type="button" onclick="fValidateDocument();">Upload</button>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
<?php $this->load->view('manage_admin/marketing_agency_documents/w9_view_model'); ?> 

<script>


    $(document).on('click','.active',function(){
        var id = $(this).attr('data-attr');
        $.ajax({
            type:'POST',
            url: '<?= base_url('manage_admin/marketing_agency_documents/ajax_responder')?>',
            data:{
                sid: id,
                status: 1
            },
            success: function(data){
                $('#'+id).removeClass('btn-success');
                $('#'+id).addClass('btn-danger');
                $('#'+id).addClass('deactive');
                $('#'+id).removeClass('active');
                $('#'+id).html('De-Activate');
            },
            error: function(){

            }
        });
    });

    $(document).on('click','.deactive',function(){
        var id = $(this).attr('data-attr');
        $.ajax({
            type:'POST',
            url: '<?= base_url('manage_admin/marketing_agency_documents/ajax_responder')?>',
            data:{
                sid: id,
                status: 0
            },
            success: function(data){
                $('#'+id).removeClass('btn-danger');
                $('#'+id).addClass('btn-success');
                $('#'+id).addClass('active');
                $('#'+id).removeClass('deactive');
                $('#'+id).html('Activate');
            },
            error: function(){

            }
        });
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    $(document).keypress(function(e) {
        if(e.which == 13) { // enter pressed
            $('#search_btn').click();
        }
    });

    $(document).ready(function(){
        $('#name').on('keyup', update_url);
        $('#name').on('blur', update_url);
        $('#search_btn').on('click', function(e){
            e.preventDefault();
            update_url();
            window.location = $(this).attr('href').toString();
        });
    });

    function update_url(){
        var url = '<?php echo isset($company_sid) ? base_url('manage_admin/documents/'.$company_sid) : base_url('manage_admin/documents/0'); ?>';
        var name = $('#name').val();

        name = name == '' ? 'all' : name;
        url = url + '/' + encodeURIComponent(name);
        $('#search_btn').attr('href', url);
    }

    function fSendForm(form_name, action, company_name, company_sid) {
        alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to Generate <strong> Affiliate End User License</strong> form to <strong>' + company_name + '</strong> ?',
                function () {
                    var form_id = 'form_' + action + '_' + form_name + '_' + company_sid;
                    console.log(form_id);
                    $('#' + form_id).submit();
                },
                function () {
                    //cancel
                }
        )
    }

    function fValidateDocument() {
        $('#form_upload_document').validate({
            rules: {
                'documents_and_forms': {
                    required: true,
                    extension: 'docx|rtf|doc|pdf'
                }
            },
            messages: {
                'documents_and_forms': {
                    required: 'Please Select a File',
                    extension: 'File can be .doc, .docx, .pdf only'
                }
            }
        });

        if ($('#form_upload_document').valid()) {
            $('#form_upload_document').submit();
        } else {
            alertify.error('Invalid File Type');
        }
    }

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

            footer_content = '<a class="btn btn-success" target="_blank" href="<?php echo AWS_S3_BUCKET_URL; ?>' + document_download_url + '">Print</a>';
            footer_content += '<a class="btn btn-success" href="<?=base_url('manage_admin/Marketing_agency_documents/download');?>/' + document_download_url + '">Download</a>';
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

    function view_w9_function (sid) {
        $('#w9_document_modal').appendTo("body").modal('show');
    }


    $('[title]').tooltip({ placement: 'top'})
</script>