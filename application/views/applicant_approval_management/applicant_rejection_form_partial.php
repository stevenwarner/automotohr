<div id="applicant_model_content_<?php echo $applicant_job['sid']; ?>">
    <?php if($applicant_job['approval_status_type'] == 'first_request' || $applicant_job['approval_status_type'] == 're_request') { ?>
        <form id="form_applicant_rejection" method="post" enctype="multipart/form-data" action="<?php echo base_url('applicant_approval_management/ajax_responder'); ?>">
            <input type="hidden" id="perform_action" name="perform_action" value="reset_applicant_for_approval" />
            <input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo $applicant_job['portal_job_applications_sid']; ?>" />
            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $applicant_job['company_sid']; ?>" />
            <input type="hidden" id="approval_status" name="approval_status" value="rejected" />
            <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $applicant_job['job_sid']; ?>" />
            <div class="form-group">
                <label class="control control--radio rejection_type">
                    Rejected With Condition
                    <input type="radio" class="rejection_type" value="rejected_conditionally" name="approval_status_type" id="rejected_conditionally"  checked="checked"/>
                    <div class="control__indicator"></div>
                </label>
            </div>
            <div class="form-group">
                <label for="reason">Condition Details</label>
                <textarea data-rule-required="true" data-msg-required="Condition is Required" id="approval_status_reason" name="approval_status_reason" class="form-control reason form-col-100" rows="4"></textarea>

                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <label class="control control--radio rejection_type">
                    Rejected
                    <input type="radio" class="rejection_type" value="rejected_unconditionally" name="approval_status_type" id="rejected_unconditionally" />
                    <div class="control__indicator"></div>
                </label>
            </div>
            <div class="form-group">
                <button type="button" class="submit-btn" onclick="fValidateRejectionResponseForm();">Save</button>
            </div>
        </form>
    <?php } else { ?>
        <?php if ($applicant_job['approval_status_type'] == 'rejected_unconditionally') { ?>
            <?php if( $applicant_job['approval_status'] != 'approved') { ?>
                <div class="text-center">
                    <strong>Rejected!</strong>&nbsp; This applicant has been rejected.
                </div>
            <?php } else { ?>
                <form id="form_applicant_rejection" method="post" enctype="multipart/form-data" action="<?php echo base_url('applicant_approval_management/ajax_responder'); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="reset_applicant_for_approval" />
                    <input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo $applicant_job['portal_job_applications_sid']; ?>" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $applicant_job['company_sid']; ?>" />
                    <input type="hidden" id="approval_status" name="approval_status" value="rejected" />
                    <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $applicant_job['job_sid']; ?>" />
                    <div class="form-group">
                        <label class="control control--radio rejection_type">
                            Rejected With Condition
                            <input type="radio" class="rejection_type" value="rejected_conditionally" name="approval_status_type" id="rejected_conditionally"  checked="checked"/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="reason">Condition Details</label>
                        <textarea data-rule-required="true" data-msg-required="Condition is Required" id="approval_status_reason" name="approval_status_reason" class="form-control reason form-col-100" rows="4"></textarea>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="control control--radio rejection_type">
                            Rejected
                            <input type="radio" class="rejection_type" value="rejected_unconditionally" name="approval_status_type" id="rejected_unconditionally" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="button" class="submit-btn" onclick="fValidateRejectionResponseForm();">Save</button>
                    </div>
                </form>
            <?php } ?>
        <?php } elseif ($applicant_job['approval_status_type'] == 'rejected_conditionally') { ?>
            <?php if( $applicant_job['approval_status'] != 'approved') { ?>
                <div class="text-center">
                    <p>This applicant has been rejected until following conditions are met.</p>
                    <p><?php echo $applicant_job['approval_status_reason'] ?></p>
                </div>
                <hr />
                <label class="control control--checkbox" for="conditions_met_chekbox">
                    <input onclick="func_toggle_approval_response_textarea(this);" data-rule-required="true" type="checkbox" value="conditions_met" id="conditions_met_chekbox" name="conditions_met_chekbox"  />&nbsp;
                    Applicant has met all required conditions
                    <div class="control__indicator"></div>
                </label>
                <hr />
                <form id="form_applicant_rejection_response" method="post" enctype="multipart/form-data" action="<?php echo base_url('applicant_approval_management/ajax_responder'); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="employer_response_on_candidate_rejection" />
                    <input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo $applicant_job['portal_job_applications_sid']; ?>" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $applicant_job['company_sid']; ?>" />
                    <input type="hidden" id="approval_status" name="approval_status" value="pending" />
                    <input type="hidden" id="approval_status_type" name="approval_status_type" value="re_request" />
                    <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $applicant_job['job_sid']; ?>" />
                    <div class="form-group" id="response_container">
                        <label for="reason">Your Response</label>
                        <textarea data-rule-required="true" data-msg-required="Condition is Required" id="approval_status_reason_response" name="approval_status_reason_response" class="form-control reason form-col-100" rows="4"></textarea>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="submit-btn" onclick="func_validate_rejection_response_form();">Save</button>
                    </div>
                </form>
            <?php } else { ?>
                <form id="form_applicant_rejection" method="post" enctype="multipart/form-data" action="<?php echo base_url('applicant_approval_management/ajax_responder'); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="reset_applicant_for_approval" />
                    <input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo $applicant_job['portal_job_applications_sid']; ?>" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $applicant_job['company_sid']; ?>" />
                    <input type="hidden" id="approval_status" name="approval_status" value="rejected" />
                    <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $applicant_job['job_sid']; ?>" />
                    <div class="form-group">
                        <label class="control control--radio rejection_type">
                            Rejected With Condition
                            <input type="radio" class="rejection_type" value="rejected_conditionally" name="approval_status_type" id="rejected_conditionally"  checked="checked"/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="reason">Condition Details</label>
                        <textarea data-rule-required="true" data-msg-required="Condition is Required" id="approval_status_reason" name="approval_status_reason" class="form-control reason form-col-100" rows="4"></textarea>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="control control--radio rejection_type">
                            Rejected
                            <input type="radio" class="rejection_type" value="rejected_unconditionally" name="approval_status_type" id="rejected_unconditionally" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="button" class="submit-btn" onclick="fValidateRejectionResponseForm();">Save</button>
                    </div>
                </form>
            <?php } ?>
        <?php } elseif (($applicant_job['approval_status_type'] == 'approved')) { ?>
                    <form id="form_applicant_rejection" method="post" enctype="multipart/form-data" action="<?php echo base_url('applicant_approval_management/ajax_responder'); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="reset_applicant_for_approval" />
                    <input type="hidden" id="applicant_id" name="applicant_id" value="<?php echo $applicant_job['portal_job_applications_sid']; ?>" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $applicant_job['company_sid']; ?>" />
                    <input type="hidden" id="approval_status" name="approval_status" value="rejected" />
                    <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $applicant_job['job_sid']; ?>" />
                    <div class="form-group">
                        <label class="control control--radio rejection_type">
                            Rejected With Condition
                            <input type="radio" class="rejection_type" value="rejected_conditionally" name="approval_status_type" id="rejected_conditionally"  checked="checked"/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="reason">Condition Details</label>
                        <textarea data-rule-required="true" data-msg-required="Condition is Required" id="approval_status_reason" name="approval_status_reason" class="form-control reason form-col-100" rows="4"></textarea>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="control control--radio rejection_type">
                            Rejected
                            <input type="radio" class="rejection_type" value="rejected_unconditionally" name="approval_status_type" id="rejected_unconditionally" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="button" class="submit-btn" onclick="fValidateRejectionResponseForm();">Save</button>
                    </div>
                </form>
        <?php } ?>
    <?php } ?>
</div>

