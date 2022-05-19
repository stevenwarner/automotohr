<br>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption class="csF18 csB7">Document Details.</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Document Title</strong></td>
                    <td class="col-sm-9"><?=ucfirst($document_title);?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Document Type</strong></td>
                    <td class="col-sm-9"><?=ucfirst($document_type);?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Assigned By</strong></td>
                    <td class="col-sm-9"><?php echo getUserNameBySID($assigned_by); ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Assigned On</strong></td>
                    <td class="col-sm-9">
                        <?php 
                            if (isset($assigned_date) && $assigned_date != '0000-00-00 00:00:00') {
                                echo reset_datetime(array('datetime' => $assigned_date, '_this' => $this));
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Assigned To</strong></td>
                    <td class="col-sm-9"><?php echo $document_user_name; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Assigned To Type</strong></td>
                    <td class="col-sm-9"><?=ucfirst($document_user_type);?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption class="csF18 csB7">Add an Approver</caption>
            <tbody>
                <tr>
                    <td class="col-sm-9">
                        <div class="field-row">
                            <select id="jsSelectedApprover" class="form-control" >
                                <option value="0" >Please Select an Employee</option>
                                <?php foreach ($employeesList as $key => $employee) { ?>
                                    <option value="<?php echo $employee['sid']; ?>" ><?= addslashes(remakeEmployeeName($employee)); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td>
                        <a href="javascript:;" id="jsAddDocumentAssignerBtn" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add Employee To Flow</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption class="csF18 csB7">Approvers Details.</caption>
            <thead>
                <tr>
                    <td class="csB6">Name</td>
                    <td class="csB6">Note</td>
                    <td class="csB6">Status</td>
                    <td class="csB6">Action Date</td>
                    <td class="csB6">Action</td>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($approvers)) { ?>
                    <?php foreach ($approvers as $approver) { ?>
                        <tr>
                            <td class="col-sm-3 csB6"><?php echo getUserNameBySID($approver['assigner_sid']); ?></td>
                            <td class="col-sm-3"><?php echo !empty($approver['approval_note']) ? $approver['approval_note'] : "N/A"; ?></td>
                            <?php 
                                $color = "text-warning";
                                if ($approver['approval_status'] == "Approve") {
                                    $color = "text-success";
                                } else if ($approver['approval_status'] == "Reject") {
                                    $color = "text-danger";
                                }
                            ?>
                            <td class="col-sm-2 csB6 <?php echo $color; ?>">
                                <?php echo !empty($approver['approval_status']) ? $approver['approval_status'] : "Pending"; ?>
                            </td>
                            <td class="col-sm-2"><?php echo !empty($approver['action_date']) ? $approver['action_date'] : "N/A"; ?></td>
                            <td class="col-sm-2">
                                <?php if (empty($approver['approval_status'])) { ?> 
                                    <a 
                                        href="javascript:;" 
                                        class="btn btn-danger jsApproversDeleteBtn jsTooltip" 
                                        data-placement="top"  title="Delete Approver" 
                                        data-approver_row_sid="<?php echo $approver['sid']; ?>" 
                                        data-approver_sid="<?php echo $approver['assigner_sid']; ?>" 
                                        data-approver_name="<?php echo getUserNameBySID($approver['assigner_sid']); ?>">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                <?php } ?>
                                <?php if (!empty($approver['assigner_turn'])) { ?> 
                                    <a 
                                        href="javascript:;" 
                                        class="btn btn-success jsApproversSendEmailReminderBtn jsTooltip" 
                                        data-placement="top" 
                                        data-toggle="tooltip" 
                                        title="Send Email Reminder" 
                                        data-approver_sid="<?php echo $approver['assigner_sid']; ?>" 
                                        data-approver_name="<?php echo getUserNameBySID($approver['assigner_sid']); ?>">
                                        <i class="fa fa-paper-plane"></i>
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<!--  -->
<?php if (!empty($approvers_note)) { ?>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption class="csF18 csB7">Note</caption>
            <tbody>
                <tr>
                    <td class="col-sm-12"><?php echo $approvers_note;?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>
<!--  -->


<script type="text/javascript">
    $('#jsSelectedApprover').select2();
    //
    $("#jsAddDocumentAssignerBtn").click(function(){
        //
        event.preventDefault();
        //
        var approval_document_sid = "<?php echo $approval_document_sid; ?>";
        var document_sid = "<?php echo $document_sid; ?>";
        var user_sid = "<?php echo $document_user_sid; ?>";
        var user_type = "<?php echo $document_user_type; ?>";
        var approver_sid = $("#jsSelectedApprover").val();
        //
        if (approver_sid == 0){
            alertify.alert("Notice","Please select an employee");
        } else {
            $('.jsIPLoader[data-page="jsDocumentsApproversModalLoader"]').show(0);
            // Send ajax request to convert
            $.post("<?=base_url('hr_documents_management/approvers_handler');?>", {
                approvalDocumentId: approval_document_sid,
                documentId: document_sid,
                approverId: approver_sid,
                userType: user_type,
                userId: user_sid,
                action: "add_approver"
            }, function(resp) {
                //
                if (resp.Status === false) {
                    $('.jsIPLoader[data-page="jsDocumentsApproversModalLoader"]').hide(0);
                    alertify.alert("Notice", resp.Msg);
                    return;
                }
                //
                alertify.alert("Notice", resp.Msg, function(){
                    GetSpecificDocumentApprovers(resp.document_sid, resp.user_sid, resp.user_type, "jsDocumentsApproversModal");
                });
                
            })
        }
    });

    $(".jsApproversDeleteBtn").click(function(){
        //
        event.preventDefault();
        //
        var document_sid = "<?php echo $document_sid; ?>";
        var user_sid = "<?php echo $document_user_sid; ?>";
        var user_type = "<?php echo $document_user_type; ?>";
        var row_sid = $(this).data("approver_row_sid");
        var approver_sid = $(this).data("approver_sid");
        var approver_name = $(this).data("approver_name");
        //
        alertify.confirm('Confirmation', "Are you sure you want to delete approver ("+approver_name+") ?",
                function () {
                    $('.jsIPLoader[data-page="jsDocumentsApproversModalLoader"]').show(0);
                    // Send ajax request to convert
                    $.post("<?=base_url('hr_documents_management/approvers_handler');?>", {
                        documentId: document_sid,
                        rowId: row_sid,
                        documentId: document_sid,
                        approverId: approver_sid,
                        userType: user_type,
                        userId: user_sid,
                        action: "delete_approver"
                    }, function(resp) {
                        //
                        if (resp.Status === false) {
                            $('.jsIPLoader[data-page="jsDocumentsApproversModalLoader"]').hide(0);
                            alertify.alert("Notice", resp.Msg);
                            return;
                        }
                        //
                        alertify.alert("Notice", resp.Msg, function(){
                            GetSpecificDocumentApprovers(resp.document_sid, resp.user_sid, resp.user_type, "jsDocumentsApproversModal");
                        });
                        
                    })
                },
                function () {

                });
    });

    $(".jsApproversSendEmailReminderBtn").click(function(){
        //
        event.preventDefault();
        //
        var document_sid = "<?php echo $document_sid; ?>";
        var user_sid = "<?php echo $document_user_sid; ?>";
        var user_type = "<?php echo $document_user_type; ?>";
        var approver_sid = $(this).data("approver_sid");
        var approver_name = $(this).data("approver_name");
        //
        alertify.confirm('Confirmation', "Are you sure you want to send email reminder to ("+approver_name+") ?",
                function () {
                    $('.jsIPLoader[data-page="jsDocumentsApproversModalLoader"]').show(0);
                    // Send ajax request to convert
                    $.post("<?=base_url('hr_documents_management/approvers_handler');?>", {
                        documentId: document_sid,
                        approverId: approver_sid,
                        userType: user_type,
                        userId: user_sid,
                        action: "remind_approver"
                    }, function(resp) {
                        //
                        if (resp.Status === false) {
                            $('.jsIPLoader[data-page="jsDocumentsApproversModalLoader"]').hide(0);
                            alertify.alert("Notice", resp.Msg);
                            return;
                        }
                        //
                        alertify.alert("Notice", resp.Msg);
                        $('.jsIPLoader[data-page="jsDocumentsApproversModalLoader"]').hide(0);
                        
                    })
                },
                function () {

                });
    });
</script>