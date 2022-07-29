<?php 
    $appCheckboxIdx = isset($appCheckboxIdx) ? $appCheckboxIdx : "jsCheckBox";
    $containerIdx = isset($containerIdx) ? $containerIdx : "jsMainContainer";
    $addApproverIdx = isset($addApproverIdx) ? $addApproverIdx : "jsAddApprovers";
    $intApproverBoxIdx = isset($intApproverBoxIdx) ? $intApproverBoxIdx : "jsIntApproverContainer";
    $extApproverBoxIdx = isset($extApproverBoxIdx) ? $extApproverBoxIdx : "jsExtApproverContainer";
    $approverNoteIdx = isset($approverNoteIdx) ? $approverNoteIdx : "jsApproverNoteContainer";
    $mainIdx = isset($mainIdx) ? $mainIdx : "mainid";
?>
<div class="row" id="<?=$mainIdx?>">
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
                            <input type="checkbox" name="has_approval_flow" class="<?=$appCheckboxIdx;?>" /> This document needs approval
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <!--  -->
                <div class="<?=$containerIdx;?>" style="display: none;">
                    <hr />
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="javascript:;" class="btn btn-success <?=$addEmployeeIdx;?>"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add An Approver</a>
                        </div>
                    </div>
                    <!--  -->
                    <div class="<?=$intEmployeeBoxIdx;?>"></div>
                    <div class="<?=$extEmployeeBoxIdx;?>"></div>
                    <!--  -->
                    <div class="row">
                        <hr />
                        <div class="col-sm-12">
                            <label for="footer_content">Note</label>
                            <textarea class="form-control <?=$approverNoteIdx;?>" name="approvers_note" rows="5" placeholder="A note for approvers"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>