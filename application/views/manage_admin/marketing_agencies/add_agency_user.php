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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-briefcase"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/marketing_agencies'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Marketing Agencies</a>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding add-new-company">
                                            <form id="form_marketing_agency" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['full_name']) ? $marketing_agency['full_name'] : '' ); ?>
                                                            <?php echo form_label('Full Name <span class="hr-required">*</span>', 'full_name'); ?>
                                                            <?php echo form_input('full_name', set_value('full_name', $temp), 'class="hr-form-fileds" data-rule-required="true" autocomplete="off"'); ?>
                                                            <?php echo form_error('full_name');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['email']) ? $marketing_agency['email'] : '' ); ?>
                                                            <?php echo form_label('Email <span class="hr-required">*</span>', 'email'); ?>
                                                            <input data-rule-email="true" data-rule-required="true" type="email" id="email" name="email" value="<?php echo set_value('email', $temp); ?>" autocomplete="off" class="hr-form-fileds" />
                                                            <?php echo form_error('email');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['contact_number']) ? $marketing_agency['contact_number'] : '' ); ?>
                                                            <?php echo form_label('Contact Number', 'contact_number'); ?>
                                                            <?php echo form_input('contact_number', set_value('contact_number', $temp), 'class="hr-form-fileds" autocomplete="off"'); ?>
                                                            <?php echo form_error('contact_number');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['website']) ? $marketing_agency['website'] : '' ); ?>
                                                            <?php echo form_label('Website', 'method_of_promotion'); ?>
                                                            <?php echo form_input('website', set_value('website', $temp), 'class="hr-form-fileds" autocomplete="off"'); ?>
                                                            <?php echo form_error('website');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['method_of_promotion']) ? $marketing_agency['method_of_promotion'] : '' ); ?>
                                                            <?php echo form_label('Method of Promotion', 'method_of_promotion'); ?>
                                                            <?php echo form_input('method_of_promotion', set_value('method_of_promotion', $temp), 'class="hr-form-fileds" autocomplete="off"'); ?>
                                                            <?php echo form_error('method_of_promotion');?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php $temp = ( isset($marketing_agency['zip_code']) ? $marketing_agency['zip_code'] : '' ); ?>
                                                            <?php echo form_label('Zip code', 'zip_code'); ?>
                                                            <?php echo form_input('zip_code', set_value('zip_code', $temp), 'class="hr-form-fileds" autocomplete="off"'); ?>
                                                            <?php echo form_error('zip_code');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight">
                                                            <?php $temp = ( isset($marketing_agency['address']) ? $marketing_agency['address'] : '' ); ?>
                                                            <?php echo form_label('Address', 'address'); ?>
                                                            <?php echo form_textarea('address', set_value('address', $temp), 'class="hr-form-fileds field-row-autoheight"'); ?>
                                                            <?php echo form_error('address');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight">
                                                            <?php $temp = ( isset($marketing_agency['notes']) ? $marketing_agency['notes'] : '' ); ?>
                                                            <?php echo form_label('Notes', 'notes'); ?>
                                                            <?php echo form_textarea('notes', set_value('notes', $temp), 'class="hr-form-fileds field-row-autoheight" autocomplete="off"'); ?>
                                                            <?php echo form_error('notes');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <?php echo form_label('User Name', 'username'); ?>
                                                            <input type="text" autocomplete="off" id="username" name="username" class="hr-form-fileds" value="<?php echo isset($marketing_agency['username']) ? $marketing_agency['username'] : ''?>">
                                                            <input type="hidden" id="db-username" value="<?php echo isset($marketing_agency['username']) ? $marketing_agency['username'] : ''?>">
                                                            <?php echo form_error('username');?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <?php echo form_label('Access Level', 'access_level'); ?>
                                                            <select class="invoice-fields" name="access_level">
                                                                <?php foreach($user_groups as $group){
                                                                    echo '<option value="'.ucwords($group['name']).'">'.ucwords($group['name']).'</option>';
                                                                }?>
                                                            </select>
                                                            <?php echo form_error('access_level');?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <label class="full-width">&nbsp;</label>
<!--                                                            <a href="javascript:;" id="send-cred" class="btn btn-primary text-right">Send Login Request</a>-->
                                                        </div>
                                                    </div>
                                                    <?php //} ?>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-right">
                                                        <div class="field-row">
                                                            <label class="full-width">&nbsp;</label>
                                                            <?php if(isset($marketing_agency)) { ?>
                                                                <button onclick="func_validate_and_submit_form();" type="button" class="btn btn-success">Update</button>
                                                            <?php } else { ?>
                                                                <button onclick="func_validate_and_submit_form();" type="button" class="btn btn-success">Create</button>
                                                            <?php } ?>
                                                            <a href="<?php echo base_url('manage_admin/marketing_agencies/get_agency_users/'.$this->uri->segment(4)); ?>" class="btn black-btn">Cancel</a>

<!--                                                            --><?php //if(isset($marketing_agency)) {
//                                                                if (sizeof($agreement_flag) > 0) {
//                                                                    $link = base_url('form_affiliate_end_user_license_agreement/'.$agreement_flag[0]['verification_key'].'/pre_fill');
//                                                                    $btn_text = $agreement_flag[0]['status'] == 'signed' ? 'View Signed Document' : 'View Document';
//                                                                    echo '<a href="'.$link.'" class="btn btn-success" target="_blank">'.$btn_text.'</a>';
//                                                                } else { ?>
<!--                                                                    <input type="button" value="View And Send" class="btn btn-success" onclick="func_send_affiliate_agreement();">-->
<!--                                                                --><?php //}
//                                                            }?>
                                                        </div>
                                                    </div>

                                                </div>
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

<script type="text/javascript">
    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
        } else {
            $('#name_' + val).html('No file selected');
        }
    }
    $(document).ready(function () {
        $('#form_marketing_agency').validate();

        $('#send-cred').click(function() {
            var formname = $('#username').val();
            var dbname = $('#db-username').val();
            alertify.confirm('Confirmation', "Are you sure you want to send Login Request?",function () {
                if ((formname == '' || formname == null) && (dbname == '' || dbname == null)) {
                    alertify.alert('Please Provide Username');
                    return false;
                } else if (dbname != '' && dbname != null) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('manage_admin/marketing_agencies/send_login_request')?>',
                        data: {
                            username: dbname,
                            id: '<?= isset($marketing_agency) ? $marketing_agency['sid'] : '';?>',
                            flag: 'db'
                        },
                        success: function (data) {
                            console.log(data);
                            alertify.success('Login Request have been sent successfully');
                            window.location.href = window.location.href;
                        },
                        error: function () {

                        }
                    });
                } else if (formname != '' && formname != null) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('manage_admin/marketing_agencies/send_login_request')?>',
                        data: {
                            username: formname,
                            id: '<?= isset($marketing_agency) ? $marketing_agency['sid'] : '' ;?>',
                            flag: 'form'
                        },
                        success: function (data) {
                            console.log(data);
                            if (data == 'exist') {
                                alertify.alert('Username already exist');
                            } else {
                                $('#db-username').val(formname);
                                alertify.success('Login Request have been sent successfully');
                                window.location.href = window.location.href;
                            }
                        },
                        error: function () {

                        }
                    });
                }
            },
            function () {
                alertify.error('Cancelled');
            });
        });
    });

    function func_validate_and_submit_form(){
        $('#form_marketing_agency').validate();

        if($('#form_marketing_agency').valid()){
            $('#form_marketing_agency').submit();
        }
    }

    function func_send_affiliate_agreement(){
        alertify.confirm('Confirmation', "Are you sure you want to Pre-Fill this agreement?",function () {
            $('#form_generate_prefill').submit();
        },
        function () {
            alertify.error('Cancelled');
        });
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