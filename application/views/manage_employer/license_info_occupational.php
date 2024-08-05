<?php if (!$load_view) { ?>
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
                                <?php if ($this->session->flashdata('message')) { ?>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="flash_error_message">
                                                <div class="alert alert-success alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <?php echo $this->session->flashdata('message'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="dashboard-conetnt-wrp">
                                    <div class="tagline-heading">
                                        <span class="pull-right">
                                            <button class="btn btn-success JsSendReminderEmailLI form-control" data-id="<?= $user_sid; ?>" data-type="<?= $user_type; ?>" data-slug="occupational-license">Send An Email Reminder</button>
                                        </span>
                                        <h4>Add License / Certification</h4>
                                    </div>
                                    <div class="tagline-heading">
                                        <span class="pull-right" id="jsGeneralDocumentArea"></span>
                                    </div>
                                    <form id="form_license_info" method="POST" enctype="multipart/form-data">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="form-col-50-left">
                                                    <label>License Number</label>
                                                    <input type="text" name="license_number" value="<?php echo isset($license_info['license_number']) ?  $license_info['license_number'] : ''; ?>" class="invoice-fields" />
                                                </li>
                                                <li class="form-col-50-right">
                                                    <label>License Type</label>
                                                    <input type="text" id="license_type" name="license_type" value="<?php echo isset($license_info['license_type']) ? $license_info['license_type'] : ''; ?>" class="invoice-fields" />
                                                </li>

                                                <li class="form-col-50-left">
                                                    <label>License Authority</label>
                                                    <input type="text" name="license_authority" value="<?php echo isset($license_info['license_authority']) ? $license_info['license_authority'] : ''; ?>" class="invoice-fields" />
                                                </li>
                                                <li class="form-col-50-right">
                                                    <label>Upload Scanned License Image</label>
                                                    <div class="upload-file invoice-fields">
                                                        <span class="selected-file" id="name_license_file">No Document</span>
                                                        <input type="file" name="license_file" id="license_file" />
                                                        <a href="javascript:;">Choose File</a>
                                                    </div>
                                                    <em style="color:rgb(255, 155, 0);"><i class="fa fa-warning"></i>&nbsp;Maximum file size allowed: 10MB</em>
                                                </li>
                                                <li class="form-col-50-left">
                                                    <label>Issue Date</label>
                                                    <input class="invoice-fields startdate" readonly="" type="text" name="license_issue_date" value="<?php echo isset($license_info['license_issue_date']) ? $license_info['license_issue_date'] : ''; ?>">
                                                </li>
                                                <?php if (isset($employer['access_level'])) {
                                                    if ($employer['access_level'] == 'employee' || $employer['access_level'] == 'Employee') { ?>
                                                        <li class="form-col-50-right">
                                                            <label>Date of Birth</label>
                                                            <input class="invoice-fields startdate" readonly="" type="text" name="dob" id="dob" value="<?php echo isset($employer["dob"]) ? date('m/d/Y', strtotime(str_replace('-', '/', $employer["dob"]))) : ''; ?>">
                                                        </li>

                                                    <?php }
                                                } elseif (isset($employer['user_type']) && ($employer['user_type'] == 'Applicant' || $employer['user_type'] == 'applicant')) {
                                                    ?>
                                                    <li class="form-col-50-right">
                                                        <label>Date of Birth</label>
                                                        <input class="invoice-fields startdate" readonly="" type="text" name="dob" value="<?php echo isset($employer["dob"]) ? date('m/d/Y', strtotime(str_replace('-', '/', $employer["dob"]))) : ''; ?>" id="dob">
                                                    </li>
                                                <?php } ?>
                                                <li class="form-col-50-left">
                                                    <label>Expiration Date</label>
                                                    <input class="invoice-fields expdate" readonly="" type="text" name="license_expiration_date" value="<?php echo isset($license_info['license_expiration_date']) ? $license_info['license_expiration_date'] : ''; ?>">
                                                </li>
                                                <li class="<?php if (isset($employer['access_level']) && ($employer['access_level'] == 'employee' || $employer['access_level'] == 'Employee')) {
                                                                echo 'form-col-50-right';
                                                            } else {
                                                                echo 'form-col-50-right';
                                                            } ?>  autoheight send-email indefinite">

                                                    <input type="checkbox" name="license_indefinite" <?php echo isset($license_info['license_indefinite']) ? 'checked="checked"' : ''; ?> id="Indefinite" />
                                                    <label for="Indefinite">Indefinite</label>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label>License Notes</label>
                                                    <textarea class="invoice-fields" name="license_notes" style="height:200px; padding:10px;"><?php echo isset($license_info['license_notes']) ? $license_info['license_notes'] : ''; ?></textarea>
                                                </li>

                                                <?php if (isset($license_info['license_file']) && $license_info['license_file'] != "") { ?>
                                                    <li class="form-col-100 autoheight license-pic">
                                                        <label>Already Scanned License Image</label>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?= getFilePathForIframe($license_info['license_file'], true, [
                                                                    'style="width: 50%"'
                                                                ]); ?>

                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <div class="btn-panel">
                                                    <input type="submit" class="submit-btn" value="Save">
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
        $('#dob').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selectedDate) {
                $('#dob').datepicker('option', 'minDate', selectedDate);
            }
        });

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


        $(document).ready(function() {
            $('#form_license_info').validate({
                rules: {
                    license_file: {
                        accept: 'image/png,image/bmp,image/gif,image/jpeg,image/tiff,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation'
                    }
                },
                messages: {
                    license_file: {
                        accept: 'Only (.jpg .jpeg .png .jpe .docx .doc .ppt .pptx .rtf .xls .xlsx .pdf) are allowed.'
                    }
                }
            });

            $('body').on('change', 'input[type=file]', function() {
                var selected_file = $(this).val();
                var selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);

                var id = $(this).attr('id');
                $('#name_' + id).html(selected_file);
            });
        });

        $('.eventdate').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();
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
    'generalActionType' => 'occupational_license',
    'companySid' => $company_sid,
    'userSid' => $user_sid,
    'userType' => $user_type
]); ?>