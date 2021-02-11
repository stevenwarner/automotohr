<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('authorized_document'); ?>">
                                        <i class="fa fa-chevron-left"></i>
                                        Assigned Documents
                                    </a>
                                    Manage Assigned Document
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 text-right" style="margin-bottom: 12px;">
                            <a target="_blank" class="btn btn-success" href="<?php echo base_url('hr_documents_management/perform_action_on_document_content').'/'.$document['sid'].'/'.'submitted/assigned_document/print'; ?>">Print</a>
                            <a target="_blank" class="btn btn-success" href="<?php echo base_url('hr_documents_management/perform_action_on_document_content').'/'.$document['sid'].'/'.'submitted/assigned_document/download'; ?>">Download</a>
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <strong><?php echo $document['document_title']; ?></strong>
                                        </div>
                                        <div class="panel-body">
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
                                                <a class="cancel_button_black" href="<?php echo base_url('authorized_document'); ?>">Cancel</a>
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

<?php $this->load->view('hr_documents_management/authorized_signature_popup'); ?>

<script>
    $(document).ready(function () {

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