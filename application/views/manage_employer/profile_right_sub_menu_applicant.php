<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <aside class="side-bar">
        <a href="<?php echo base_url('application_tracking_system/active/all/all/all/all') ?>">
            <header class="sidebar-header">
                <h1>Application Tracking</h1>
            </header>
        </a>
        <div class="hr-widget">
            <div class="browse-attachments">
                <ul>
                    <li>
                        <h4>Background Check</h4>
                        <?php if ($company_background_check == 1) { ?>
                            <a href="<?php echo base_url('background_check') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                            <?php
                        } else {
                            $_SESSION['applicant_id'] = $applicant_info['sid'];
                            ?>
                            <a href="<?php echo base_url('background_check/activate') ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        <?php } ?>
                    </li>
                    <li>
                        <h4>Drug Testing</h4>
                        <?php if ($company_background_check == 1) { ?>
                            <a href="<?php echo base_url('drug_test') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                            <?php
                        } else {
                            $_SESSION['applicant_id'] = $applicant_info['sid'];
                            ?>
                            <a href="<?php echo base_url('background_check/activate') ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                        <?php } ?>
                    </li>
                    <li>
                        <h4>Behavioral Assessment</h4>
                        <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Reference Check</h4>
                        <a href="<?php echo base_url('reference_checks') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Skills Test</h4>
                        <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Video Interview</h4>
                        <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Add Schedule</h4>
                        <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <form action="<?php echo base_url('applicant_profile/send_reference_request_email'); ?>" method="post" id="form_request_references_<?php echo $applicant_info['sid']; ?>">
                            <input type="hidden" id="perform_action" name="perform_action" value="send_add_reference_request_email" />
                            <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_info['sid']; ?>" />
                        </form>
                        <h4>References Request</h4>
                        <a href="javascript:;" onclick="fSendAddReferencesRequestEmail(<?php echo $applicant_info['sid']; ?>);">Send<i class="fa fa-chevron-circle-right"></i></a>
                    </li>


                    <?php if ($kpa_onboarding_check == 1) { ?>
                        <li>
                            <h4>Outsourced HR Compliance and Onboarding</h4>
                            <form action="<?php echo base_url('applicant_profile/send_kpa_onboarding'); ?>" method="post" id="form_kpa_onboarding_<?php echo $applicant_info['sid']; ?>">
                                <input type="hidden"  name="kpa_action" value="send_kpa_onboarding_email" />
                                <input type="hidden" name="applicant_sid" value="<?php echo $applicant_info['sid']; ?>" />
                            </form>
                            <a href="javascript:;" onclick="fSendKpaOnboardingEmail(<?php echo $applicant_info['sid']; ?>);">Send<i class="fa fa-chevron-circle-right"></i></a>
                        </li>
                    <?php }
                    ?>

                </ul>
            </div>
        </div>
        <div class="hr-widget">
            <div class="browse-attachments">
                <ul>
                    <li>
                        <h4>Full Employment Application</h4>
                        <a href="<?php echo base_url('applicant_full_employment_application') . '/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>WOTC New Hire Tax Credits</h4>
                        <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Emergency Contacts</h4>
                        <a href="<?php echo base_url('emergency_contacts') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Occupational License Info</h4>
                        <a href="<?php echo base_url('occupational_license_info') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Drivers License Info</h4>
                        <a href="<?php echo base_url('drivers_license_info') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Equipment Info</h4>
                        <a href="<?php echo base_url('equipment_info') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>W4 form and Tax withholding</h4>
                        <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>i9 Employment Verification</h4>
                        <a href="<?php echo base_url('i9form') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <li>
                        <h4>Dependents</h4>
                        <a href="<?php echo base_url('dependants') . '/applicant/' . $applicant_info['sid']; ?>">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    <!--
                    <li>
                        <h4>Benefit Elections</h4>
                        <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>

                    <li>
                        <h4>Payroll</h4>
                        <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                    </li>
                    -->
                </ul>
            </div>
        </div>
    </aside>
</div>
<script>
    $(document).ready(function () {

    });

    function fSendAddReferencesRequestEmail(iApplicantID) {
        alertify.confirm(
                'Are You Sure?',
                'Are You Sure You Want to Send Add References Request Email to This Applicant?',
                function () {
                    //Ok Scripts
                    console.log('OK.' + iApplicantID);
                    $('#form_request_references_' + iApplicantID).submit();
                },
                function () {
                    //Cancel Scripts
                }).set({
            labels: {
                ok: 'Yes!'
            }
        });
    }


    function fSendKpaOnboardingEmail(iApplicantID) {
        alertify.confirm(
                'Are You Sure?',
                'Are You Sure You Want to Send Outsourced HR Onboarding Request Email to This Applicant?',
                function () {
                    $('#form_kpa_onboarding_' + iApplicantID).submit();
                },
                function () {
                    //Cancel Scripts
                }).set({
            labels: {
                ok: 'Yes!'
            }
        });
    }
</script>