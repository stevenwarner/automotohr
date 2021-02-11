<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile'.'/'.$employer['sid'])?>"><i class="fa fa-chevron-left"></i>Employee Profile</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="job-title-text">
                                <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open('', array('id' => 'loginform')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100">
                                                <?php echo form_label('Employee Status <span class="hr-required">*</span>', 'terminated_status'); ?>
                                                <select name="status" id="status" class="invoice-fields" aria-required="true" aria-invalid="false" disabled>
                                                    <option value="">Please Select</option>
                                                    <option value="1" <?= $termination_record['employee_status'] == 1 ? 'selected="selected"' : '';?>>Terminated</option>
                                                    <option value="2" <?= $termination_record['employee_status'] == 2 ? 'selected="selected"' : '';?>>Retired</option>
                                                    <option value="3" <?= $termination_record['employee_status'] == 3 ? 'selected="selected"' : '';?>>Deceased</option>
                                                    <option value="4" <?= $termination_record['employee_status'] == 4 ? 'selected="selected"' : '';?>>Suspended</option>
                                                </select>
                                                <?php echo form_error('terminated_status'); ?>
                                            </li>
                                            <li class="form-col-100">
                                                <?php echo form_label('Termination Reason <span class="hr-required">*</span>', 'terminated_status'); ?>
                                                <select name="terminated_reason" id="terminated_reason" class="invoice-fields" aria-required="true" aria-invalid="false" disabled>
                                                    <option value="">Please Select</option>
                                                    <option value="1" <?= $termination_record['termination_reason'] == 1 ? 'selected="selected"' : '';?>>Resignation</option>
                                                    <option value="2" <?= $termination_record['termination_reason'] == 2 ? 'selected="selected"' : '';?>>Fired</option>
                                                    <option value="3" <?= $termination_record['termination_reason'] == 3 ? 'selected="selected"' : '';?>>Tenure Completed</option>
                                                </select>
                                                <?php echo form_error('terminated_status'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Termination Date <span class="hr-required">*</span></label>
                                                <?php echo form_input('termination_date', set_value('termination_date',date('m-d-Y', strtotime($termination_record['termination_date']))), 'class="invoice-fields" id="termination_date" autocomplete="off" readonly'); ?>
                                                <?php echo form_error('termination_date'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox"> Involuntary Termination
                                                    <input id="involuntary" name="involuntary" type="checkbox" value="1" <?php echo $termination_record['involuntary_termination'] ? 'checked' : ''?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox"> Do Not Rehire
                                                    <input id="rehire" name="rehire" type="checkbox" value="1" <?php echo $termination_record['do_not_hire'] ? 'checked' : ''?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Status Change Date <span class="hr-required">*</span></label>
                                                <?php echo form_input('status_change_date', set_value('status_change_date',date('m-d-Y', strtotime($termination_record['status_change_date']))), 'class="invoice-fields" id="status_change_date" autocomplete="off" readonly'); ?>
                                                <?php echo form_error('status_change_date'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Termination Details <span class="hr-required">*</span></label>
                                                <p><?php echo html_entity_decode($termination_record['details']);?></p>
                                            </li>
                                            <?php if(sizeof($documents)>0) {?>
                                            <li class="form-col-100 autoheight">
                                                <div class="hr-box applied-jobs margin-top">
                                                    <div class="hr-box-header">
                                                        <strong>Reasoning Document</strong>
                                                    </div>
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th class="col-xs-8">Name</th>
                                                                <th class="col-xs-4 text-center">Actions</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach ($documents as $doc) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <p>
                                                                            <strong><?php echo ucwords($doc['file_name']); ?></strong>
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <div class="text-center">
                                                                            <a href="<?php echo AWS_S3_BUCKET_URL . $doc['file_code']; ?>"
                                                                               download class="btn btn-success">
                                                                                &nbsp;Download</a>
                                                                            <a class="btn btn-danger"
                                                                               href="javascript:;"
                                                                               onclick="delete_file(<?= $doc['sid']; ?>);">
                                                                                &nbsp;Delete</a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php }?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php }?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script type="text/javascript">

    function validate_form() {
        $("#loginform").validate({
            ignore: ":hidden:not(select)",
            rules: {
                status: {
                    required: true
                }
            },
            messages: {
                status: {
                    required: 'This field is required'
                }
            },
            submitHandler: function (form) {
                var instances = $.trim(CKEDITOR.instances.termination_details.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Details Missing', "Please provide some details");
                    return false;
                }
                form.submit();
            }
        });
    }

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-col-100 autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">' + fileName + '</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload() {
        $('.upload-file').show();

        if ($('#uploaded-files').html() != '') {
            $('#uploaded-files').show();
        }

        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload() {
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('perform_action', 'file_upload');
        form_data.append('id', <?php echo $id; ?>);

        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('terminate/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function (data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);

                $('.upload-file').show();
                $('#uploaded-files').show();
//                $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");

                if (data != "error") {
                    $('#uploaded-files').append('<li class="form-col-100 autoheight"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> ' + file_data['name'] + '</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </li>');
                    alertify.success('New document has been uploaded');
                } else {
                    alert('Doc error');
                }
            },
            error: function () {
            }
        });
    }

    function delete_file(sid){
        alertify.confirm('Confirm Delete!','Are you sure you want to delete this document',
        function(){
            $.ajax({
                url: '<?= base_url('terminate/ajax_handler') ?>',
                type: 'post',
                data: {
                    id: sid,
                    perform_action: 'delete_file'
                },
                success: function (data) {
                    if (data != "error") {
                        alertify.success('File Deleted Successfully');
                        window.location.href = window.location.href;
                    } else {
                        alertify.error('Something went wrong');
                    }
                },
                error: function () {
                    alertify.error('File Not Deleted');
                }
            });
        }, function () {
                alertify.error('Cancelled');
        });
    }
</script>