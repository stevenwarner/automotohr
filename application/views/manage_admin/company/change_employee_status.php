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
                                                            <option value="5">Active</option>
                                                            <option value="7">Leave</option>
                                                            <option value="4">Suspended</option>
                                                            <option value="2">Retired</option>
                                                            <option value="8">Rehired</option>
                                                            <option value="3">Deceased</option>
                                                            <option value="1">Terminated</option>
                                                            <!-- <option value="6">Inactive</option> -->
                                                            <option value="9">Transferred</option>

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
                                                                <option value="1">Resignation</option>
                                                                <option value="2">Fired</option>
                                                                <option value="3">Tenure Completed</option>
                                                                <option value="18">Store Closure</option>
                                                                <option value="19">Did Not Hire</option>
                                                                <option value="20">Separation</option>

                                                            </optgroup>

                                                            <optgroup label="Voluntary">
                                                                <option value="4">Personal</option>
                                                                <option value="5">Another Job</option>
                                                                <option value="6">Problem with Supervisor</option>
                                                                <option value="7">Relocation</option>
                                                                <option value="8">Work Schedule</option>
                                                                <option value="9">Retirement</option>
                                                                <option value="10">Return to School</option>
                                                                <option value="11">Pay</option>
                                                                <option value="12">Without Notice/Reason</option>
                                                            </optgroup>

                                                            <optgroup label="Involuntary">
                                                                <option value="13">Involuntary</option>
                                                                <option value="14">Violating Company Policy</option>
                                                                <option value="15">Attendance Issues</option>
                                                                <option value="16">Performance</option>
                                                                <option value="17">Workforce Reduction</option>
                                                            </optgroup>
                                                        </select>
                                                        <?php echo form_error('terminated_status'); ?>
                                                    </div>
                                                </li>
                                                <li id="termination_date_section">
                                                    <label>Termination Date <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" name="termination_date" value="" class="invoice-fields" id="termination_date" autocomplete="off" readonly>
                                                        <?php echo form_error('termination_date'); ?>
                                                    </div>
                                                </li>
                                                <li id="system_access_div">
                                                    <div class="hr-fields-wrap">
                                                        <label class="control control--checkbox"> Deactivate this Employees Access to the System
                                                            <input id="system_access" name="system_access" type="checkbox" value="1" checked>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li id="inventary-termination">
                                                    <div class="hr-fields-wrap">
                                                        <label class="control control--checkbox"> Involuntary Termination
                                                            <input id="involuntary" name="involuntary" type="checkbox" value="1">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li id="do-not-hire">
                                                    <div class="hr-fields-wrap">
                                                        <label class="control control--checkbox"> Do Not Rehire
                                                            <input id="rehire" name="rehire" type="checkbox" value="1">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Status Change Date <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input('status_change_date', set_value('status_change_date'), 'class="invoice-fields" id="status_change_date" autocomplete="off" readonly'); ?>
                                                        <?php echo form_error('status_change_date'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Status Change Details <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <textarea name="termination_details" rows="10" class="ckeditor" data-rulereuired="true"></textarea>
                                                    </div>
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
                                                    <input type="submit" name="submit" value="Save" class="btn btn-success" onclick="return validate_form()">
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
    $('#status_change_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    });
    //
    $('#termination_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    });
    //
    $(document).ready(function() {
        $('#termination_reason_section').hide();
        $('#termination_date_section').hide();
        $('#inventary-termination').hide();
        $('#do-not-hire').hide();
        $('#system_access_div').hide();
    });

    function validate_form() {
        var emp_status = $('#status').val();
        var terminated_reason_check = '';
        var termination_date_check = '';


        $("#Changestatusform").validate({
            ignore: ":hidden:not(select)",
            rules: {
                status: {
                    required: true
                },
                status_change_date: {
                    required: true
                }
            },
            messages: {
                status: {
                    required: 'Employee Status is required'
                },
                status_change_date: {
                    required: 'Status Change Date is required'
                }
            },
            submitHandler: function(form) {

                var instances = $.trim(CKEDITOR.instances.termination_details.getData());
                var errorFlag = true;
                if (instances.length === 0) {
                    alertify.alert('Error! Details Missing', "Please provide some details");
                    return false;
                }
                if (emp_status != 1) {
                    $('#terminated_reason').val('');
                    $('#termination_date').val('');
                } else {
                    if ($('#terminated_reason').val() == '' || $('#terminated_reason').val() == undefined) {
                        alertify.alert('Note', 'Termination Reason is required');
                        errorFlag = false;
                    }
                    if ($('#termination_date').val() == '') {
                        alertify.alert('Note', 'Termination Date is required');
                        errorFlag = false;
                    }
                    return errorFlag;
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
        //
        var emp_status = $(this).val();
        //
        if (emp_status != 1) {
            $('#termination_reason_section').hide();
            $('#termination_date_section').hide();
            $('#inventary-termination').hide();
            $('#do-not-hire').hide();
            $('#involuntary').val(0);
            $('#involuntary').prop('checked', false);
            $('#rehire').val(0);
            $('#rehire').prop('checked', false);
            $('#system_access_div').hide();
            $('#system_access').prop('checked', false);
            if (emp_status == 6) {
                $('#inventary-termination').show();
                $('#do-not-hire').show();
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

    function DoUpload() {
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('perform_action', 'file_upload');
        form_data.append('id', <?php echo $employeeSid; ?>);

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
                    alertify.alert("success", 'New document has been uploaded');
                } else {
                    alertify.alert('Error', 'Doc error');
                }
            },
            error: function() {}
        });
    }
</script>