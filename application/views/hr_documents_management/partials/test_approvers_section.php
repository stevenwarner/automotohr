<?php 
    $appCheckbox_idx = isset($appCheckbox_idx) ? $appCheckbox_idx : "jsCheckBox";
    $container_idx = isset($container_idx) ? $container_idx : "jsMainContainer";
    $addApprover_idx = isset($addApprover_idx) ? $addApprover_idx : "jsAddApprovers";
    $intApproverBox_idx = isset($intApproverBox_idx) ? $intApproverBox_idx : "jsIntApproverContainer";
    $extApproverBox_idx = isset($extApproverBox_idx) ? $extApproverBox_idx : "jsExtApproverContainer";
    $approverNote_idx = isset($approverNote_idx) ? $approverNote_idx : "jsApproverNoteContainer";
    $main_idx = isset($main_idx) ? $main_idx : "mainid";
?>
<div class="row" id="<?=$main_idx?>">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5 style="margin-top: 0; margin-bottom: 0;">
                    <strong>Approval Flow</strong>
                </h5>
            </div>
            <div class="panel-body">
                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <p class="text-danger"><strong><i aria-hidden="true">Note: When assigned this document will be sent to each of the selected approvers for their approval. The employee/applicant will not have visibility to this document until the document has been approved by all Approvers first.</i></strong></p>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <hr />
                    <div class="col-sm-12">
                        <label class="control control--checkbox">
                            <input type="checkbox" name="has_approval_flow" class="<?=$appCheckbox_idx;?>" /> This document needs approval
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <!--  -->
                <div class="<?=$container_idx;?>" style="display: none;">
                    <hr />
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="javascript:;" class="btn btn-success <?=$addEmployee_idx;?>"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add An Approver</a>
                        </div>
                    </div>
                    <!--  -->
                    <div class="<?=$intEmployeeBox_idx;?>"></div>
                    <div class="<?=$extEmployeeBox_idx;?>"></div>
                    <!--  -->
                    <div class="row">
                        <hr />
                        <div class="col-sm-12">
                            <label for="footer_content">Note</label>
                            <textarea class="form-control <?=$approverNote_idx;?>" name="assigner_note" rows="5" placeholder="A note for approvers"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>