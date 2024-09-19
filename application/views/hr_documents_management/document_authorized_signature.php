<?php 
    //
    $s3_file = isset($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : $document['document_s3_name'];
    //
    if ($document['document_type'] != 'generated') {
        $d = get_required_url(
            $s3_file
        );
    } else {
        $d['preview_url'] = '';
    }
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?php if($user_type == 'applicant'){ ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/documents_assignment/'.$user_type .'/'.$user_sid.'/'.$job_list_sid); ?>"><i class="fa fa-chevron-left"></i>Document Assignment</a>
                                    <?php } else { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/documents_assignment/'.$user_type .'/'.$user_sid); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                    <?php } ?>
                                    <?php echo $title; ?>
                                </span>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 text-right" style="margin-bottom: 12px;">
                                    <a target="_blank" class="btn btn-success" href="<?php echo base_url('hr_documents_management/print_download_hybird_document/submitted/print/both').'/'.$document['sid']; ?>">Print</a>
                                    <a target="_blank" class="btn btn-success" href="<?php echo base_url('hr_documents_management/print_download_hybird_document/submitted/print/both').'/'.$document['sid']; ?>">Download</a>
                                </div>
                            </div> 
                                   
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong><?php echo $document['document_title']; ?></strong>
                                        </div>
                                        <div class="panel-body">
                                            <?php if($document['document_type'] == 'hybrid_document' || $document['offer_letter_type'] == 'hybrid_document') { ?>
                                                    <h5 class="alert alert-success"><strong>Section 1:</strong> Document</h5>
                                                    <iframe src="<?=$d['preview_url'];?>" frameborder="0" style="width: 100%; height: 550px;"></iframe>
                                                    <h5 class="alert alert-success"><strong>Section 2:</strong> Description</h5>
                                            <?php  } ?>
                                            <?php echo html_entity_decode($document['document_description']); ?>
                                            <form id="form_save_authorized_signature" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                <input type="hidden" id="perform_action" name="perform_action" value="save_authorized_signature" />
                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['document_sid']; ?>" />
                                                <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                                <input type="hidden" id="authorized_signature" name="authorized_signature" value="" />
                                                <input type="hidden" id="authorized_signature_init" name="authorized_signature_init" value="" />
                                            </form>
                                            <hr>
                                            <div class="message-action-btn">
                                                <?php if (!empty($document['authorized_signature'])) { ?>
                                                    <input type="button" value="Edit Authorized Signature" id="edit_authorized_signature" data-auth-signature="<?php echo $document['sid']; ?>" class="btn blue-button">
                                                <?php } ?>    
                                                <?php if($user_type == 'applicant') { ?>
                                                    <a class="cancel_button_black" id="authorized_cancel_button" href="<?php echo base_url('hr_documents_management/documents_assignment/'.$user_type .'/'.$user_sid.'/'.$job_list_sid); ?>">Cancel</a>
                                                <?php } else { ?>
                                                    <a class="cancel_button_black" id="authorized_cancel_button" href="<?php echo base_url('hr_documents_management/documents_assignment/'.$user_type .'/'.$user_sid); ?>">Cancel</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('iframeLoader'); ?>
<?php $this->load->view('hr_documents_management/authorized_signature_popup'); ?>



<script>
    $(document).ready(function () {

        var doc_type = '<?php echo $document['document_type']; ?>';
        if($('iframe').length !== 0 && doc_type != 'generated'){
            loadIframe('<?=$d['preview_url'];?>', 'iframe', true );
        }

        $('.show_authorized_signature_popup').attr('data-auth-signature','<?php echo $document['sid']; ?>');
       
        <?php if ($document['user_consent'] == 1 && !empty($document['form_input_data'])) { ?>
            var form_input_data = <?php echo $form_input_data; ?>;
            form_input_data = Object.entries(form_input_data);
        
            $.each(form_input_data, function(key ,input_value) { 
                var input_field_id = input_value[0]+'_id';  
                var input_field_val = input_value[1]; 
                var input_type =  $('#'+input_field_id).attr('data-type');

                if (input_type == 'text') {
                    $('#'+input_field_id).val(input_field_val);
                    $('#'+input_field_id).prop('disabled', true);
                } else if (input_type == 'checkbox') {
                    if (input_field_val == 'yes') {
                        $('#'+input_field_id).prop('checked', true);;
                    }
                    $('#'+input_field_id).prop('disabled', true);
                    
                } else if (input_type == 'textarea') {
                    $('#'+input_field_id).hide();
                    $('#'+input_field_id+'_sec').show();
                    $('#'+input_field_id+'_sec').html(input_field_val);
                } 
            });   
            
        <?php } ?>
    });

    $('.show_authorized_signature_popup').on('click', function () {
        $('#authorized_e_Signature_Modal').modal('show');

        var document_auth_sid = $(this).attr('data-auth-signature');
        $('#authorized_document_sid').val(document_auth_sid);
    }); 

    $('#edit_authorized_signature').on('click', function () {
        $('#authorized_e_Signature_Modal').modal('show');

        var document_auth_sid = $(this).attr('data-auth-signature');
        $('#authorized_document_sid').val(document_auth_sid);
    }); 

    $('.add_authorized_editable_date').on('click', function() {
        var document_auth_sid = $(this).attr('data-auth-sid');

        $('#authorized_editable_date_document_sid').val(document_auth_sid);
        $('#authorized_editable_date_Modal').modal('show');
    });

                  
</script>

<style>
    #edit_authorized_signature {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #0000ff;
        border: none;
        max-width: 210px;
        min-width: 97px;
        text-align: center;
        margin: 0 5px;
        border-radius: 5px;
        font-weight: 600;
        text-transform: capitalize;
        font-style: italic;
    }

    .cancel_button_black {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #000;
        border: none;
        max-width: 210px;
        min-width: 97px;
        text-align: center;
        margin: 0 5px;
        border-radius: 5px;
        font-weight: 600;
        text-transform: capitalize;
        font-style: italic;
    }
    
</style>