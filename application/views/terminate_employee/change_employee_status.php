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
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('employee_status' . '/' . $employer['sid']) ?>"><i class="fa fa-chevron-left"></i>Employee Status Panel</a>
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
                                            </li>
                                            <li class="form-col-100" id="termination_reason_section">
                                                <?php echo form_label('Termination Reason <span class="hr-required">*</span>', 'terminated_status'); ?>
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
                                            </li>
                                            <li class="form-col-100 autoheight" id="termination_date_section">
                                                <label>Termination Date <span class="hr-required">*</span></label>
                                                <?php echo form_input('termination_date', set_value('termination_date'), 'class="invoice-fields" id="termination_date" autocomplete="off" readonly'); ?>
                                                <?php echo form_error('termination_date'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight" id="system_access_div">
                                                <label class="control control--checkbox"> Deactivate this Employees Access to the System
                                                    <input id="system_access" name="system_access" type="checkbox" value="1" checked>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                            <li class="form-col-100 autoheight" id="inventary-termination">
                                                <label class="control control--checkbox"> Involuntary Termination
                                                    <input id="involuntary" name="involuntary" type="checkbox" value="1">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                            <li class="form-col-100 autoheight" id="do-not-hire">
                                                <label class="control control--checkbox"> Do Not Rehire
                                                    <input id="rehire" name="rehire" type="checkbox" value="1">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Status Change Date <span class="hr-required">*</span></label>
                                                <?php echo form_input('status_change_date', set_value('status_change_date'), 'class="invoice-fields" id="status_change_date" autocomplete="off" readonly'); ?>
                                                <?php echo form_error('status_change_date'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Status Change Details <span class="hr-required">*</span></label>
                                                <textarea name="termination_details" rows="10" class="ckeditor" data-rulereuired="true"></textarea>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Upload Related Document:</label>
                                                <div class="upload-file invoice-fields">
                                                    <span class="selected-file" id="name_docs">No file selected</span>
                                                    <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                                <div id="file-upload-div" class="file-upload-box"></div>
                                                <div class="attached-files" id="uploaded-files" style="display: none;"></div>
                                                <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Documents One After Other </div>
                                                <div class="custom_loader">
                                                    <div id="loader" class="loader" style="display: none">
                                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                        <span>Uploading...</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="hidden" name="id" value="<?php echo $employer['sid']; ?>">
                                                <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('employee_status' . '/' . $employer['sid']); ?>'" />
                                            </li>
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
    $(document).ready(function(){
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


        $("#loginform").validate({
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
            submitHandler: function (form) {
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
                    if($('#terminated_reason').val() == '' || $('#terminated_reason').val() == undefined){
                        alertify.alert('Termination Reason is required');
                        errorFlag = false;
                    }
                    if($('#termination_date').val() == ''){
                        alertify.alert('Termination Date is required');
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
                alertify.error("Please select a valid document format.");
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
    $(document).on('change', '#status', function () {
        var emp_status = $(this).val();
        if (emp_status != 1) {
            $('#termination_reason_section').hide();
            $('#termination_date_section').hide();
            $('#inventary-termination').hide();
            $('#do-not-hire').hide();
            $('#involuntary').val(0);
            $('#involuntary').prop('checked', false);
            $('#rehire').val(0);
            $('#rehire').prop('checked', false);
//            if (emp_status != 2 && emp_status != 3 && emp_status != 4) {
                $('#system_access_div').hide();
                $('#system_access').prop('checked', false);
//            }
            if(emp_status == 6){
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
        form_data.append('id', <?php echo $id; ?>);

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
            success: function (data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);

                $('.upload-file').show();
                $('#uploaded-files').show();
                // $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");

                if (data != "error") {
                    $('#uploaded-files').append('<li class="form-col-100 invoice-fields"> <div id="uploaded-files-name"><b>Name:</b> ' + file_data['name'] + '</div> <span class="pull-right"><b>Status:</b> Uploaded</span> </li>');
                    alertify.success('New document has been uploaded');
                } else {
                    alert('Doc error');
                }
            },
            error: function () {
            }
        });
    }
</script>
