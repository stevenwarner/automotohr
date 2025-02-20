<?php ?>
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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-user"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/employers/EmployeeStatusDetail') . "/" . $employee_detail["sid"]; ?>" class="btn black-btn float-right">Back</a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title employee_info_section">
                                        <h1 class="page-title">Company Name : <?php echo $companyName; ?></h1>
                                        <br>
                                        <h1 class="page-title">Employee Name : <?php echo $employeeName; ?></h1>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="edit-email-template">
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        <div class="edit-template-from-main">
                                            <?php echo form_open_multipart('', array('class' => 'form-horizontal js-form', 'id' => 'Changestatusform')); ?>
                                            <ul>
                                                <li>
                                                    <label>Employee Status <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select name="status" id="status" class="invoice-fields" aria-required="true" aria-invalid="false">
                                                            <option value="">Please Select</option>
                                                            <option value="5" <?= $status_data['employee_status'] == 5 ? 'selected="selected"' : ''; ?>>Active</option>
                                                            <option value="7" <?= $status_data['employee_status'] == 7 ? 'selected="selected"' : ''; ?>>Leave</option>
                                                            <option value="4" <?= $status_data['employee_status'] == 4 ? 'selected="selected"' : ''; ?>>Suspended</option>
                                                            <option value="2" <?= $status_data['employee_status'] == 2 ? 'selected="selected"' : ''; ?>>Retired</option>
                                                            <option value="8" <?= $status_data['employee_status'] == 8 ? 'selected="selected"' : ''; ?>>Rehired</option>
                                                            <option value="3" <?= $status_data['employee_status'] == 3 ? 'selected="selected"' : ''; ?>>Deceased</option>
                                                            <option value="1" <?= $status_data['employee_status'] == 1 ? 'selected="selected"' : ''; ?>>Terminated</option>
                                                            <!-- <option value="6" <?= $status_data['employee_status'] == 6 ? 'selected="selected"' : ''; ?>>Inactive</option> -->
                                                            <option value="9" <?= $status_data['employee_status'] == 9 ? 'selected="selected"' : ''; ?>>Transferred</option>

                                                        </select>
                                                        <?php echo form_error('status'); ?>
                                                    </div>
                                                </li>
                                                <li id="termination_reason_section">
                                                    <label>Termination Reason <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select name="terminated_reason" id="terminated_reason" class="invoice-fields" aria-required="true" aria-invalid="false">
                                                            <option value="">Please Select</option>

                                                            <optgroup label="General">
                                                                <option value="1" <?= $status_data['termination_reason'] == 1 ? 'selected="selected"' : ''; ?>>Resignation</option>
                                                                <option value="2" <?= $status_data['termination_reason'] == 2 ? 'selected="selected"' : ''; ?>>Fired</option>
                                                                <option value="3" <?= $status_data['termination_reason'] == 3 ? 'selected="selected"' : ''; ?>>Tenure Completed</option>
                                                                <option value="18" <?= $status_data['termination_reason'] == 18 ? 'selected="selected"' : ''; ?>>Store Closure</option>
                                                                <option value="19" <?= $status_data['termination_reason'] == 19 ? 'selected="selected"' : ''; ?>>Did Not Hire</option>
                                                                <option value="20" <?= $status_data['termination_reason'] == 20 ? 'selected="selected"' : ''; ?>>Separation</option>

                                                            </optgroup>

                                                            <optgroup label="Voluntary">
                                                                <option value="4" <?= $status_data['termination_reason'] == 4 ? 'selected="selected"' : ''; ?>>Personal</option>
                                                                <option value="5" <?= $status_data['termination_reason'] == 5 ? 'selected="selected"' : ''; ?>>Another Job</option>
                                                                <option value="6" <?= $status_data['termination_reason'] == 6 ? 'selected="selected"' : ''; ?>>Problem with Supervisor</option>
                                                                <option value="7" <?= $status_data['termination_reason'] == 7 ? 'selected="selected"' : ''; ?>>Relocation</option>
                                                                <option value="8" <?= $status_data['termination_reason'] == 8 ? 'selected="selected"' : ''; ?>>Work Schedule</option>
                                                                <option value="9" <?= $status_data['termination_reason'] == 9 ? 'selected="selected"' : ''; ?>>Retirement</option>
                                                                <option value="10" <?= $status_data['termination_reason'] == 10 ? 'selected="selected"' : ''; ?>>Return to School</option>
                                                                <option value="11" <?= $status_data['termination_reason'] == 11 ? 'selected="selected"' : ''; ?>>Pay</option>
                                                                <option value="12" <?= $status_data['termination_reason'] == 12 ? 'selected="selected"' : ''; ?>>Without Notice/Reason</option>
                                                            </optgroup>

                                                            <optgroup label="Involuntary">
                                                                <option value="13" <?= $status_data['termination_reason'] == 13 ? 'selected="selected"' : ''; ?>>Involuntary</option>
                                                                <option value="14" <?= $status_data['termination_reason'] == 14 ? 'selected="selected"' : ''; ?>>Violating Company Policy</option>
                                                                <option value="15" <?= $status_data['termination_reason'] == 15 ? 'selected="selected"' : ''; ?>>Attendance Issues</option>
                                                                <option value="16" <?= $status_data['termination_reason'] == 16 ? 'selected="selected"' : ''; ?>>Performance</option>
                                                                <option value="17" <?= $status_data['termination_reason'] == 17 ? 'selected="selected"' : ''; ?>>Workforce Reduction</option>
                                                            </optgroup>
                                                        </select>
                                                        <?php echo form_error('terminated_status'); ?>
                                                    </div>
                                                </li>
                                                <li id="termination_date_section">
                                                    <label>Termination Date <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" name="termination_date" value="<?php echo $status_data['termination_date'] != NULL ? date('m-d-Y', strtotime($status_data['termination_date'])) : ''; ?>" class="invoice-fields" id="termination_date" autocomplete="off" readonly>
                                                        <?php echo form_error('termination_date'); ?>
                                                    </div>
                                                </li>
                                                <li id="system_access_div">
                                                    <div class="hr-fields-wrap">
                                                        <label class="control control--checkbox"> Deactivate this Employees Access to the System
                                                            <input id="system_access" name="system_access" type="checkbox" value="1" <?= $employee_detail['active'] == 0 ? 'checked' : '' ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li id="inventary-termination">
                                                    <div class="hr-fields-wrap">
                                                        <label class="control control--checkbox"> Involuntary Termination
                                                            <input id="involuntary" name="involuntary" type="checkbox" value="1" <?= $status_data['involuntary_termination'] == 1 ? 'checked' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li id="do-not-hire">
                                                    <div class="hr-fields-wrap">
                                                        <label class="control control--checkbox"> Do Not Rehire
                                                            <input id="rehire" name="rehire" type="checkbox" value="1" <?= $status_data['do_not_hire'] == 1 ? 'checked' : ''; ?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Status Change Date <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" name="status_change_date" value="<?php echo date('m-d-Y', strtotime($status_data['status_change_date'])); ?>" class="invoice-fields" id="status_change_date" autocomplete="off" readonly="">
                                                        <?php echo form_error('status_change_date'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Status Change Details <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <textarea name="termination_details" rows="10" class="ckeditor" data-rulereuired="true"><?= $status_data['details'] ?></textarea>
                                                    </div>
                                                </li>
                                                <li class="">
                                                    <label>Documents :</label>
                                                    <?php if (sizeof($status_documents) > 0) { ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php foreach ($status_documents as $file) { ?>
                                                                <div class="form-control full-width" style="height: auto; float: left;margin: 5px 0 0 0;">
                                                                    <div class="pull-left">
                                                                        <span class="uploaded-file-name"><a href="<?= base_url('hr_documents_management/download_upload_document/' . $file['file_code']); ?>" target="_blank"><?php echo $file['file_name'] ?></a></span>
                                                                    </div>
                                                                    <div class="pull-right">
                                                                        <a data-toggle="tooltip" data-placement="top" class="btn btn-primary btn-sm" href="javascript:;" onclick="display_document(this);" document_title="<?php echo $file['file_name']; ?>" document_url="<?php echo AWS_S3_BUCKET_URL . $file['file_code']; ?>" print_url="<?php echo $file['file_code']; ?>" document_ext="<?php echo $file['file_type']; ?>">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                        <a href="javascript:;" title="Delete File" class="btn btn-danger delete-record" data-attr="<?= $file['sid'] ?>"> <i class="fa fa-times"></i></a>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="form-control full-width" style="height: auto; float: left">
                                                            <div class="pull-left">
                                                                <span class="uploaded-file-name">No documents founds</span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <label>Upload Related Document:</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="upload-file invoice-fields">
                                                            <span class="selected-file" id="name_docs">No file selected</span>
                                                            <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <div id="file-upload-div" class="file-upload-box"></div>
                                                        <div class="attached-files" id="uploaded-files" style="display: none;">
                                                            <table class="table table-bordered table-stripped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>File Name</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="uploaded-files-row">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Documents One After Other </div>
                                                        <div class="custom_loader">
                                                            <div id="loader" class="loader" style="display: none">
                                                                <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                                <span>Uploading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <input type="hidden" name="sid" value="">
                                                </li>
                                            </ul>
                                            <div class="row" style="float: right;">
                                                <div class="col-xs-12">
                                                    <input type="submit" name="submit" value="Update" class="btn btn-success" onclick="return validate_form()">
                                                    <a href="<?php echo base_url('manage_admin/employers/EmployeeStatusDetail') . "/" . $employee_detail["sid"]; ?>" class="btn black-btn">Cancel</a>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
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

<style type="text/css">
    .employee_info_section {
        margin: 8px 0px;
    }
</style>

<script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#status').change();
        <?php if ($employee_detail['active'] == 1) { ?>
            $('#system_access').prop('checked', false);
        <?php } ?>
    });
    $('#termination_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    });
    $('#status_change_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    });

    function validate_form() {
        var emp_status = $('#status').val();
        var terminated_reason_check = '';
        var termination_date_check = '';

        if (emp_status != 1) {
            terminated_reason_check = false;
            termination_date_check = false;
        } else {
            terminated_reason_check = true;
            termination_date_check = true;
        }

        $("#statusform").validate({
            ignore: ":hidden:not(select)",
            rules: {
                status: {
                    required: true
                },
                terminated_reason: {
                    required: terminated_reason_check
                },
                termination_date: {
                    required: termination_date_check
                },
                status_change_date: {
                    required: true
                }
            },
            messages: {
                status: {
                    required: 'Employee Status is required'
                },
                terminated_reason: {
                    required: 'Termination Reason is required'
                },
                termination_date: {
                    required: 'Termination Date is required'
                },
                status_change_date: {
                    required: 'Status Change Date is required'
                }
            },
            submitHandler: function(form) {
                var instances = $.trim(CKEDITOR.instances.termination_details.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Details Missing', "Please provide some details");
                    return false;
                }
                var emp_status = $('#status').val();
                if (emp_status != 1) {
                    $('#terminated_reason').val('');
                    $('#termination_date').val('');
                }
                form.submit();
            }
        });
    }

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            var ext = fileName.split('.').pop();
            if (ext != "PDF" && ext != "pdf" && ext != "docx" && ext != "xlsx") {
                $("#" + val).val(null);
                alertify.alert("Error", "Please select a valid document format.");
                $('#name_' + val).html('<p class="red">Only (PDF, Word, Excel) files are allowed!</p>');
                return false;
            } else {
                $('#name_' + val).html(fileName.substring(0, 45));
                $('.upload-file').hide();
                $('#uploaded-files').hide();
                $('#file-upload-div').append('<div class="form-col-100 invoice-fields"><div class="pull-left"> <span class="selected-file" id="name_docs">' + fileName + '</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
            }
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

    // $('#status')
    $(document).on('change', '#status', function() {
        var emp_status = $(this).val();
        if (emp_status != 1) {
            $('#termination_reason_section').hide();
            $('#termination_date_section').hide();
            $('#inventary-termination').hide();
            $('#do-not-hire').hide();
            $('#system_access_div').hide();
            $('#system_access').prop('checked', false);
            if (emp_status == 6) {
                $('#inventary-termination').show();
                $('#do-not-hire').show();
            } else {
                $('#involuntary').val(0);
                $('#involuntary').prop('checked', false);
                $('#rehire').val(0);
                $('#rehire').prop('checked', false);
            }
        } else {
            $('#system_access').prop('checked', true);
            $('#system_access_div').show();
            $('#termination_reason_section').show();
            $('#termination_date_section').show();
            $('#inventary-termination').show();
            $('#do-not-hire').show();
        }
    });

    $(document).on('click', '.delete-record', function() {
        var id = $(this).attr('data-attr');
        alertify.confirm('Confirmation', "Are you sure you want to delete this file?",
            function() {
                $.ajax({
                    url: '<?= base_url('employee_status/ajax_handler') ?>',
                    type: 'post',
                    data: {
                        id: id,
                        perform_action: 'delete_file'
                    },
                    success: function(data) {
                        alertify.alert('Success', 'File Deleted Successfully');
                        window.location.href = window.location.href;
                    },
                    error: function() {}
                });
            },
            function() {
                alertify.alert('Note', 'Document delete process canceled');
            });
    });

    function DoUpload() {
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('perform_action', 'file_upload');
        form_data.append('id', <?php echo $employeeSid; ?>);
        form_data.append('record-id', <?php echo $status_id; ?>);

        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('employee_status/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);

                $('.upload-file').show();
                $('#uploaded-files').show();
                // $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");

                if (data != "error") {
                    var row = "<tr><td>" + file_data['name'] + "</td><td>Uploaded</td></tr>";
                    $('#uploaded-files-row').append(row);
                    alertify.alert('Success', 'New document has been uploaded');
                } else {
                    alertify.alert('Error', 'Doc error');
                }
            },
            error: function() {}
        });
    }

    function display_document(source) {
        var iframe_url = '';
        var modal_content = '';
        var footer_content = '';
        var document_title = $(source).attr('document_title');
        var document_url = $(source).attr('document_url');
        var file_extension = $(source).attr('document_ext');
        var print_url = $(source).attr('print_url');
        var document_url_segment = print_url.split('.')[0];
        var unique_sid = '';
        var document_sid = '';

        if (document_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_url_segment + '.pdf" class="btn btn-success">Print</a>';
                    break;
                case 'doc':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_url_segment + '%2Edoc&wdAccPdf=0" class="btn btn-success">Print</a>';
                    break;
                case 'docx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_url_segment + '%2Edocx&wdAccPdf=0" class="btn btn-success">Print</a>';
                    break;
                case 'xls':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_url_segment + '%2Exls" class="btn btn-success">Print</a>';
                    break;
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_url_segment + '%2Exlsx" class="btn btn-success">Print</a>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('onboarding/print_applicant_upload_img/') ?>' + document_file_name + '" class="btn btn-success">Print</a>';
                    break;
                default:
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }
            footer_content = '<a target="_blank" class="btn btn-success" href="<?php echo base_url('Hr_documents_management/download_upload_document') ?>' + '/' + print_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_footer').append(footer_print_btn);
        $('#document_modal_title').html(document_title);
        $('#file_preview_modal').modal("toggle");
        $('#file_preview_modal').on("shown.bs.modal", function() {

            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }
</script>