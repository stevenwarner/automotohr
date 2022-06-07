<div class="row">
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
                        <p class="text-danger"><strong><i aria-hidden="true">Note: On assignment the document will be sent to the selected approvals for approval. The employee/applicant will not have the visibility to document unless the document is approved.</i></strong></p>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <hr />
                    <div class="col-sm-12">
                        <label class="control control--checkbox">
                            <input type="checkbox" name="has_approval_flow" id="jsHasApprovalFlow" /> This document needs approval
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <!--  -->
                <div class="jsApproverFlowContainer" style="display: none;">
                    <hr />
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="javascript:;" id="jsAddDocumentAssigner" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add An Approver</a>
                        </div>
                    </div>
                    <!--  -->
                    <div class="jsEmployeesadditionalBox"></div>
                    <div class="jsEmployeesadditionalExternalBox"></div>
                    <!--  -->
                    <div class="row">
                        <hr />
                        <div class="col-sm-12">
                            <label for="footer_content">Note</label>
                            <textarea class="form-control" id="assigner_note" name="assigner_note" rows="5" placeholder="A note for approvers"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>