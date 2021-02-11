
<form id="form_hire_applicant_send_documents" enctype="multipart/form-data" method="post">
    <input type="hidden" id="applicant_sid" name="id" value="<?php echo $applicant_sid; ?>" />
    <input type="hidden" id="company_sid" name="cid" value="<?php echo $company_sid; ?>" />
    <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $job_sid; ?>" />
    <input type="hidden" id="email" name="email" value="<?php echo $email; ?>" />
    <input type="hidden" id="action" name="action" value="hire_now" />

    <fieldset class="confirm-hireed-employee">

        <label class="control control--checkbox">
            Are you sure you want to Send Onboarding Docs to this applicant?
            <input type="checkbox" required id="myCheckbox" onclick="check_status(this);"/>
            <div class="control__indicator"></div>
        </label>

        <div class="btn-panel">
            <ul>
                <li>
                    <input onclick="func_validate_hire_form_and_submit();" id="yes-btn" class="btn btn-success disabled" disabled="disabled" type="button" value="Yes!"/>
                </li>
            </ul>
            <label>Note: When you click "Yes" and confirm that you want to send HR Docs to this candidate their profile
                will be moved out of the Applicant tracking system and into the Employee/Team member onboarding
                area.</label>
        </div>
    </fieldset>
</form>