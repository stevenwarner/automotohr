<?php
//
$s3_file = isset($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : $document['document_s3_name'];
//
if ($document['document_type'] != 'generated' && $document['offer_letter_type'] != 'generated') {
    $d = get_required_url(
        $s3_file
    );
} else {
    $d['preview_url'] = '';
}

$document_status = 'assigned';

if ($document['user_consent'] == 1) {
    $document_status = 'submitted';
}
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="row">

                        <div class="col-lg-1 col-md-1 col-xs-1 col-sm-1">
                            <br />
                            <a href="<?= base_url('employee_management_system'); ?>" class="btn btn-info csRadius5">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                Dashboard
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                            <br />
                            <a class="btn btn-info btn-block mb-2 csRadius5" href="<?php echo base_url('authorized_document'); ?>">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                Assigned Documents
                            </a>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-xs-12" style="margin-bottom: 12px;">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <?php
                                echo $assign_doc_user_name . " <b>(" . $assign_doc_user_type . ")</b>";
                                ?>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right">
                                <a target="_blank" class="btn btn-info" href="<?php echo base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/' . $document_status . '/' . 'assigned_document/print'; ?>">Print</a>
                                <a target="_blank" class="btn btn-info" href="<?php echo base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/' . $document_status . '/' . 'assigned_document/download'; ?>">Download</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="row">
                                <div class="col-xs-12 jsBody">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong><?php echo $document['document_title']; ?></strong>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($document['document_type'] == 'hybrid_document' || $document['offer_letter_type'] == 'hybrid_document') { ?>
                                                <h5 class="alert alert-success"><strong>Section 1:</strong> Document</h5>
                                                <iframe src="<?= $d['preview_url']; ?>" frameborder="0" style="width: 100%; height: 550px;"></iframe>
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
                                            <?php
                                            if ($assignedDocuments && !empty($document['authorized_signature'])) {
                                                echo '<p class="text-left"><strong>Signed By:</strong> ' . ($assignedDocuments) . '</p>';
                                                echo '<p class="text-left"><strong>Signed At:</strong> ' . (date_with_time($document['authorized_signature_date'])) . '</p>';
                                                echo '<hr />';
                                            }
                                            ?>
                                            <div class="message-action-btn">
                                                <?php if (!empty($document['authorized_signature']) && $assignedDocuments == 1) { ?>
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

<?php $this->load->view('iframeLoader'); ?>
<?php
if (!$assignedDocuments || empty($document['authorized_signature'])) {
    $this->load->view('hr_documents_management/authorized_signature_popup');
}
?>

<script>
    $(document).ready(function() {
        var doc_type = '<?php echo $document['document_type']; ?>';
        if ($('iframe').length !== 0 && doc_type != 'generated') {
            loadIframe('<?= $d['preview_url']; ?>', 'iframe', true);
        }

        if ($('.jsBody').find('select').length >= 0) {
            $('.jsBody').find('select').map(function(i) {
                //
                $(this).addClass('js_select_document');
                $(this).prop('name', 'selectDD' + i);
            });
        }

        $('.show_authorized_signature_popup').attr('data-auth-signature', '<?php echo $document['sid']; ?>');
        $('.add_authorized_editable_date').attr('data-auth-sid', '<?php echo $document['sid']; ?>');

        <?php if ($document['user_consent'] == 1 && !empty($document['form_input_data'])) { ?>
            var form_input_data = <?php echo $form_input_data; ?>;
            form_input_data = Object.entries(form_input_data);

            $.each(form_input_data, function(key, input_value) {
                // for fillables
                if (input_value[0] === "supervisor") {
                    $("input.js_supervisor").val(input_value[1]);
                } else if (input_value[0] === "department") {
                    $("input.js_department").val(input_value[1]);
                } else if (input_value[0] === "last_work_date") {
                    $("input.js_last_work_date").val(input_value[1]);
                } else if (input_value[0] === "reason_to_leave_company") {
                    $("textarea.js_reason_to_leave_company").val(input_value[1]);
                } else if (input_value[0] === "forwarding_information") {
                    $("textarea.js_forwarding_information").val(input_value[1]);
                }
                var input_field_id = input_value[0] + '_id';
                var input_field_val = input_value[1];
                var input_type = $('#' + input_field_id).attr('data-type');

                if (input_type == 'text') {
                    $('#' + input_field_id).val(input_field_val);
                    $('#' + input_field_id).prop('disabled', true);
                } else if (input_type == 'checkbox') {
                    if (input_field_val == 'yes') {
                        $('#' + input_field_id).prop('checked', true);;
                    }
                    $('#' + input_field_id).prop('disabled', true);

                } else if (input_type == 'textarea') {
                    $('#' + input_field_id).hide();
                    $('#' + input_field_id + '_sec').show();
                    $('#' + input_field_id + '_sec').html(input_field_val);
                } else if (input_value[0].match(/select/) !== -1) {
                    if (input_value[1] != null) {
                        let cc = get_select_box_value(input_value[0], input_value[1]);
                        $(`select.js_select_document[name="${input_value[0]}"]`).html('');
                        $(`select.js_select_document[name="${input_value[0]}"]`).hide(0);
                        $(`select.js_select_document[name="${input_value[0]}"]`).after(`<strong style="font-size: 20px;">${cc}</strong>`)
                    }
                }
            });

            function get_select_box_value(select_box_name, select_box_val) {
                var data = select_box_val;
                let cc = '';

                if (select_box_val.indexOf(',') > -1) {
                    data = select_box_val.split(',');
                }


                if ($.isArray(data)) {
                    let modify_string = '';
                    $.each(data, function(key, value) {
                        if (modify_string == '') {
                            modify_string = ' ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                        } else {
                            modify_string = modify_string + ', ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                        }
                    });
                    cc = modify_string;
                } else {
                    cc = $(`select.js_select_document[name="${select_box_name}"] option[value="${select_box_val}"]`).text();
                }

                return cc;
            }


        <?php } ?>
    });

    $('.show_authorized_signature_popup').on('click', function() {
        $('#authorized_e_Signature_Modal').modal('show');

        var document_auth_sid = $(this).attr('data-auth-signature');
        $('#authorized_document_sid').val(document_auth_sid);
    });

    $('#edit_authorized_signature').on('click', function() {
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

    .btn-success {
        background-color: #3554dc !important;
    }
</style>