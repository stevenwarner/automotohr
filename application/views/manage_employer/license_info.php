<?php

//$load_view = 'old';
//
//if ($this->session->userdata('logged_in')) {
//    if (!isset($session)) {
//        $session = $this->session->userdata('logged_in');
//    }
//    $access_level = $session['employer_detail']['access_level'];
//
//    if ($access_level == 'Employee') {
//        $load_view = 'new';
//    }
//}\
?>
<style>
    .csbgbl {
        -webkit-filter: blur(5px);
        filter: blur(5px)
    }
</style>
<?php if (!$load_view) { ?>
    <?php
    //
    $dob = isset($employer["dob"]) ? date('m/d/Y', strtotime(str_replace('-', '/', $employer["dob"]))) : '';
    //
    if ($_ssv) {
        //
        if (isset($license_info['license_number'])) {
            $license_info['license_number'] = ssvReplace($license_info['license_number']);
        }
        //
        // if(isset($license_info['license_expiration_date'])){
        //     $license_info['license_expiration_date'] = ssvReplace($license_info['license_expiration_date'], true);
        // }
        // //
        // if(isset($license_info['license_issue_date'])){
        //     $license_info['license_issue_date'] = ssvReplace($license_info['license_issue_date'], true);
        // }
        //
        if ($dob != '') $dob = ssvReplace($dob, true);
    }
    ?>

    <div class="main-content">
        <div class="dashboard-wrp">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                                <div class="page-header-area margin-top">
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>

                                        <?php echo $title; ?></span>
                                </div>
                                <div class="dashboard-conetnt-wrp">
                                    <div class="tagline-heading">
                                        <span class="pull-right">
                                            <button class="btn btn-success JsSendReminderEmailLI form-control" data-id="<?= $user_sid; ?>" data-type="<?= $user_type; ?>" data-slug="drivers-license">Send An Email Reminder</button>
                                        </span>
                                        <h4>Add License / Certification</h4>
                                    </div>
                                    <div class="tagline-heading">
                                        <span class="pull-right" id="jsGeneralDocumentArea"></span>
                                    </div>
                                    <form method="POST" enctype="multipart/form-data" id="form_driver_license">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="form-col-50-left">
                                                    <label>Type</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="license_type">
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "Sales License") { ?> selected <?php } ?> value="Sales License">Sales License
                                                            </option>
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "Commercial Driver’s License") { ?> selected <?php } ?> value="Commercial Driver’s License">
                                                                Commercial Driver’s License</option>
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "Non-commercial Driver’s License") { ?> selected <?php } ?> value="Non-commercial Driver’s License">
                                                                Non-commercial Driver’s License</option>
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "Restricted Driver’s License") { ?> selected <?php } ?> value="Restricted Driver’s License">
                                                                Restricted Driver’s License</option>
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "Basic Driver’s License") { ?> selected <?php } ?> value="Basic Driver’s License">Basic
                                                                Driver’s License</option>
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "Identification Card") { ?> selected <?php } ?> value="Identification Card">
                                                                Identification Card</option>
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "College Diploma") { ?> selected <?php } ?> value="College Diploma">College Diploma
                                                            </option>
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "Training") { ?> selected <?php } ?> value="Training">Training</option>
                                                            <option <?php if (isset($license_info['license_type']) && $license_info['license_type'] == "LSR Training") { ?> selected <?php } ?> value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="form-col-50-right">
                                                    <label>License Authority</label>
                                                    <input type="text" name="license_authority" value="<?php
                                                                                                        if (isset($license_info['license_authority'])) {
                                                                                                            echo $license_info['license_authority'];
                                                                                                        }
                                                                                                        ?>" class="invoice-fields">
                                                </li>
                                                <li class="form-col-50-left">
                                                    <label>Class</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="license_class">
                                                            <option <?php if (isset($license_info['license_class']) && $license_info['license_class'] == "None") { ?> selected <?php } ?> value="None">None</option>
                                                            <option <?php if (isset($license_info['license_class']) && $license_info['license_class'] == "Class A") { ?> selected <?php } ?> value="Class A">Class A</option>
                                                            <option <?php if (isset($license_info['license_class']) && $license_info['license_class'] == "Class B") { ?> selected <?php } ?> value="Class B">Class B</option>
                                                            <option <?php if (isset($license_info['license_class']) && $license_info['license_class'] == "Class C") { ?> selected <?php } ?> value="Class C">Class C</option>
                                                            <option <?php if (isset($license_info['license_class']) && $license_info['license_class'] == "Other") { ?> selected <?php } ?> value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="form-col-50-right">
                                                    <label>Number <span class="required" aria-required="true">*</span></label>
                                                    <input type="text" name="license_number" id="license_number" value="<?php
                                                                                                                        if (isset($license_info['license_number'])) {
                                                                                                                            echo $license_info['license_number'];
                                                                                                                        }
                                                                                                                        ?>" class="invoice-fields">
                                                </li>
                                                <li class="form-col-50-left">
                                                    <label>Issue Date <span class="required" aria-required="true">*</span></label>
                                                    <input class="invoice-fields startdate" readonly="" id="dr_license_issue_date" type="text" name="license_issue_date" value="<?php if (isset($license_info['license_issue_date'])) {
                                                                                                                                                                                    echo $license_info['license_issue_date'];
                                                                                                                                                                                } ?>">
                                                </li>

                                                <?php if (isset($employer['access_level'])) {
                                                    if ($employer['access_level'] == 'employee' || $employer['access_level'] == 'Employee') { ?>
                                                        <li class="form-col-50-right">
                                                            <label>Date of Birth <span class="required" aria-required="true">*</span></label>
                                                            <input class="invoice-fields" id="date_of_birth" readonly="" type="text" name="dob" value="<?= $dob; ?>">
                                                        </li>

                                                    <?php }
                                                } elseif (isset($employer['user_type']) && ($employer['user_type'] == 'Applicant' || $employer['user_type'] == 'applicant')) {
                                                    ?>
                                                    <li class="form-col-50-right">
                                                        <label>Date of Birth</label>
                                                        <input class="invoice-fields" id="date_of_birth" readonly="" type="text" name="dob" value="<?= $dob; ?>">
                                                    </li>
                                                <?php } ?>
                                                <li class="form-col-50-left">
                                                    <label>Expiration Date <span class="required" aria-required="true">*</span></label>
                                                    <input class="invoice-fields expdate" readonly="" id="dr_license_expiration_date" type="text" name="license_expiration_date" value="<?php if (isset($license_info['license_expiration_date'])) {
                                                                                                                                                                                            echo $license_info['license_expiration_date'];
                                                                                                                                                                                        } ?>">
                                                </li>
                                                <li class="<?php if (isset($employer['access_level']) && ($employer['access_level'] == 'employee' || $employer['access_level'] == 'Employee')) {
                                                                echo 'form-col-50-right';
                                                            } else {
                                                                echo 'form-col-50-right';
                                                            } ?>  autoheight send-email indefinite">

                                                    <input type="checkbox" name="license_indefinite" <?php if (isset($license_info['license_indefinite'])) { ?> checked <?php } ?> id="Indefinite">
                                                    <label for="Indefinite">Indefinite</label>
                                                </li>

                                                <li class="form-col-100 autoheight">
                                                    <label>License Notes</label>
                                                    <textarea class="invoice-fields" name="license_notes" style="height:200px; padding:10px;">
                                                    <?php
                                                    if (isset($license_info['license_notes'])) {
                                                        echo $license_info['license_notes'];
                                                    }
                                                    ?>
                                                </textarea>
                                                </li>
                                                <li class="form-col-50-left">
                                                    <label>Upload Scanned License Image</label>
                                                    <div class="upload-file invoice-fields">
                                                        <span class="selected-file" id="name_license_file">No
                                                            Document</span>
                                                        <input type="file" name="license_file" id="license_file" onchange="check_file('license_file')">
                                                        <a href="javascript:;">Choose File</a>
                                                    </div>
                                                    <em style="color:rgb(255, 155, 0);"><i class="fa fa-warning"></i>&nbsp;Maximum file size allowed:
                                                        10MB</em>
                                                </li>
                                                <?php if (isset($license_info['license_file']) && $license_info['license_file'] != "") { ?>
                                                    <li class="form-col-100 autoheight license-pic">
                                                        <label>Already Scanned License Image</label>
                                                        <div id="remove_image">
                                                            <?= getFilePathForIframe($license_info['license_file'], true, [
                                                                'style="width: 50%"'
                                                            ]); ?>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <div class="btn-panel">
                                                    <input type="button" class="submit-btn" value="Save" id="btn_update_driver_license">
                                                    <input type="button" class="submit-btn btn-cancel" value="Cancel" onClick="document.location.href = '<?php echo base_url($cancel_url); ?>'">
                                                </div>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view($left_navigation); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Disable clicks
        $('.csbgbl').bind('contextmenu', function(e) {
            return false;
        });
        $('#date_of_birth').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selectedDate) {
                $('#date_of_birth').datepicker('option', 'minDate', selectedDate);
            }
        });

        $('.eventdate').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

        $('.startdate').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();


        $('.expdate').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+100",
        }).val();


        function check_file(val) {
            var fileName = $("#" + val).val();
            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                if (val == 'license_file') {
                    if (
                        $.inArray(ext.toLowerCase(), ["jpg", "jpeg", "png", "jpe", "docx", "doc", "ppt", "pptx", "rtf", "xls", "xlsx", "pdf"]) === -1
                    ) {
                        $("#" + val).val(null);
                        alertify.error("Please select a valid Image format.");
                        $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png .jpe .docx .doc .ppt .pptx .rtf .xls .xlsx .pdf) allowed!</p>');
                        return false;
                    } else
                        return true;
                }
            } else {
                $('#name_' + val).html('No file selected');
            }
        }


        //
        $("#btn_update_driver_license").click(function() {

            if ($('#license_number').val().trim() == '') {
                alertify.alert('License Number is Required.');
                return false;
            }
            if ($('#dr_license_issue_date').val().trim() == '') {
                alertify.alert('Issue Date is Required.');
                return false;
            }
            if ($('#date_of_birth').val().trim() == '') {
                alertify.alert('Date Of Birth is Required.');
                return false;
            }
            if ($('#dr_license_expiration_date').val().trim() == '') {
                alertify.alert('Expiration Date is Required.');
                return false;
            }


            $("#form_driver_license").submit();


        });
    </script>

<?php } else { ?>
    <?php $this->load->view('onboarding/license_info'); ?>
<?php } ?>

<style type="text/css">
    .indefinite {
        margin-top: 31px !important;
    }
</style>
<?php $this->load->view('hr_documents_management/general_document_assignment_single', [
    'generalActionType' => 'drivers_license',
    'companySid' => $company_sid,
    'userSid' => $user_sid,
    'userType' => $user_type
]); ?>