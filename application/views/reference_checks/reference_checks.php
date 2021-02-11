<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="well well-sm">
                                    <a href="<?php echo base_url('add_reference_checks') . '/' . $users_type . '/' . $user_sid . '/' . $job_list_sid; ?>" class="delete-all-btn active-btn" ><i class="glyphicon glyphicon-plus"></i>&nbsp;Add New Reference</a>
                                    <div class="clear"></div>
                                </div>
                                <div class="table-responsive table-outer">
                                    <?php if (count($reference_checks) > 0) { ?>
                                        <div class="">
                                            <table class="table table-bordered table-hover table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Ref. Name</th>
                                                        <th>Relation</th>
                                                        <th>Phone Number</th>
                                                        <th>Time To Call</th>
                                                        <th>Conducted By</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($reference_checks as $reference_check) { ?>
                                                        <tr>
                                                            <td><?php echo ucwords($reference_check['reference_type']); ?></td>
                                                            <td><?php echo ucwords($reference_check['reference_name']); ?></td>
                                                            <td><?php echo ucwords($reference_check['reference_relation']); ?></td>
                                                            <td><?=phonenumber_format($reference_check['reference_phone']);?></td>
                                                            <td><?php echo ucwords($reference_check['best_time_to_call']); ?></td>
                                                            <td><?php echo ucwords($reference_check['questionnaire_conducted_by']); ?></td>
                                                            <td>
                                                                <form method="post" id="form_delete_reference<?php echo $reference_check['sid']; ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="delete_reference" />
                                                                    <input type="hidden" id="sid" name="sid" value="<?php echo $reference_check['sid']; ?>" />
                                                                </form>
                                                                <form method="post" id="form_send_questionnaire_link_by_email_<?php echo $reference_check['sid']; ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="send_questionnaire_link_by_email" />
                                                                    <input type="hidden" id="sid" name="sid" value="<?php echo $reference_check['sid']; ?>" />
                                                                </form>
                                                                <?php $editUrl = base_url('edit_reference_checks') . '/' . $users_type . '/' . $user_sid . '/' . $job_list_sid . '/' . $reference_check['sid']; ?>
                                                                <?php $questionnaireUrl = base_url('reference_checks_questionnaire') . '/' . $users_type . '/' . $user_sid . '/' . $job_list_sid . '/' . $reference_check['sid']; ?>
                                                                <a class="action-btn enable-bs-tooltip" title="Edit Reference" href="<?php echo $editUrl ?>"><i class="fa fa-pencil"></i></a>
                                                                <button class="action-btn remove enable-bs-tooltip" title="Delete Reference" onclick="fDeleteReference(<?php echo $reference_check['sid']; ?>);"><i class="fa fa-remove"></i></button>
                                                                <a class="action-btn enable-bs-tooltip" title="Fill Reference Check Questionnaire" href="<?php echo $questionnaireUrl; ?>" id=""><i class="fa fa-eye"></i></a>
                                                                <button class="action-btn enable-bs-tooltip" title="Send Questionnare Form Link By Email" id="" onclick="fSendQuestionnaireLink(<?php echo $reference_check['sid']; ?>);"><i class="fa fa-paper-plane" ></i></button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else { ?>
                                        <div class="no-job-found">
                                            <ul>
                                                <li>
                                                    <h3 style="text-align: center;">No Reference Checks Found! </h3>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>
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
    function fSendQuestionnaireLink(iReferenceId) {
        alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to send Questionnaire Link By Email?',
                function () { //Ok
                    $('#form_send_questionnaire_link_by_email_' + iReferenceId).submit();
                },
                function () { //Cancel
                }).set({
            'labels': {
                'ok': 'Yes!'
            }
        });
    }

    function fDeleteReference(iReferenceId) {
        alertify.confirm(
                'Are you Sure?',
                'Are you Sure You Want To Delete This Reference?',
                function () { //ok
                    $('#form_delete_reference' + iReferenceId).submit();
                },
                function () { //cancel
                }).set({
            'labels': {
                'ok': 'Yes!'
            }
        });
    }
</script>