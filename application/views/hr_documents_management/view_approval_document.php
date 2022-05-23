<?php
    $jsonToArray = json_decode($document_info['flow_json'], true); 
?>
<div class="main csPageWrap">
    <div class="container">
         <!-- row -->
         <div class="row">
            <div class="col-sm-6 col-xs-12">
                <a href="<?=base_url('hr_documents_management/approval_documents');?>" class="btn btn-info csRadius5">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back To Approval Documents
                </a>
            </div>
            <div class="col-sm-6 col-xs-12 text-right">
                <?php if(empty($currentApprover['approval_status'])): ?>
                <a href="javascript:;" data-action="Approve" data-sid="<?php echo $currentApproverId; ?>" data-doc_sid="<?php echo $document_sid; ?>" class="jsPerformAction btn btn-orange"><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;Approve</a>
                <a href="javascript:;" data-action="Reject" data-sid="<?php echo $currentApproverId; ?>" data-doc_sid="<?php echo $document_sid; ?>" class="jsPerformAction btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Reject</a>
                <?php endif; ?>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <br>
            <div class="col-sm-12">
                <!--  -->
                <?php $this->load->view('document_view'); ?>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <br>
            <div class="col-sm-12 col-md-12">
                <h2 class="csF20 csB7">Basic</h2>
            </div>
        </div>
        <!--  -->
        <div class="csPageBox csRadius5">
            <div class="csPageBoxBody">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <tr>
                                <th class="col-sm-3 csF16">Document Title</th>
                                <td><p class="text-left"><?php echo $document_title; ?></p></td>
                            </tr>
                            <tr>
                                <th class="col-sm-3">Document Type</th>
                                <td><p class="text-left"><?php echo ucwords($document_type); ?></p></td>
                            </tr>
                            <tr>
                                <th class="col-sm-3">Employee/Applicant Name</th>
                                <td><p class="text-left"><?=$document_info['user_type'] == "employee" ? getUserNameBySID($document_info['user_sid']) : getApplicantNameBySID($document_info['user_sid']);?></p></td>
                            </tr>
                            <tr>
                                <th class="col-sm-3">Type</th>
                                <td><p class="text-left"><?=ucfirst($document_info['user_type']);?></p></td>
                            </tr>
                            <tr>
                                <th class="col-sm-3">Assigned By</th>
                                <td><p class="text-left"><?php echo getUserNameBySID($assigned_by); ?></p></td>
                            </tr>
                            <tr>
                                <th class="col-sm-3">Assigned Date</th>
                                <td><p class="text-left"><?=formatDateToDB($assigned_date, DB_DATE_WITH_TIME, DATE_WITH_TIME);?></p></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
         <!--  -->
         <div class="row">
            <br>
            <div class="col-sm-12 col-md-6">
                <h2 class="csF20 csB7">Document</h2>
            </div>
        </div>
        <div class="csPageBox csRadius5">
            <div class="csPageBoxBody">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <tr>
                                <th class="col-sm-3">Acknowledgment Required </th>
                                <td><p class="text-left"><?php echo $document_info['acknowledgment_required'] ? 'Yes' : 'No'; ?></p></td>
                            </tr>
                            <tr>
                                <th class="col-sm-3">Download Required </th>
                                <td><p class="text-left"><?php echo $document_info['download_required'] ? 'Yes' : 'No'; ?></p></td>
                            </tr>
                            <tr>
                                <th class="col-sm-3">Signature Required </th>
                                <td><p class="text-left"><?php echo $document_info['signature_required'] ? 'Yes' : 'No'; ?></p></td>
                            </tr>
                            <tr>
                                <th class="col-sm-3">Document Required </th>
                                <td><p class="text-left"><?php echo $document_info['is_required'] ? 'Yes' : 'No'; ?></p></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <br>
            <div class="col-sm-12 col-md-6">
                <h2 class="csF20 csB7">Approver employees list</h2>
            </div>
        </div>
        <!--  -->
        <div class="csPageBox csRadius5">
            <div class="csPageBoxBody">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-lg-4">Employee</th>
                                    <th class="col-lg-2">Assign/Action Dates</th>
                                    <th class="col-lg-2">Status</th>
                                    <th class="col-lg-2">Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($approvers) && !empty($approvers)) { ?>
                                    <?php foreach ($approvers as $approver) { ?>
                                        <?php 
                                            //
                                            $status = 'Pending';
                                            $statusClass = 'text-warning';
                                            //
                                            if(isset($approver['note'])){
                                                if($approver['approval_status'] == 'Approve'){
                                                    $status = 'Approved';
                                                    $statusClass = 'text-success';
                                                }
                                                else if($approver['approval_status'] == 'Reject'){
                                                    $status = 'Rejected';
                                                    $statusClass = 'text-danger';
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    echo getUserNameBySID($approver['assigner_sid']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if (isset($approver['assign_on']) && $approver['assign_on'] != '0000-00-00 00:00:00') {
                                                        echo '<strong>Assigned on: </strong>'.reset_datetime(array('datetime' => $approver['assign_on'], '_this' => $this));
                                                    }
                                                    if (isset($approver['action_date']) && $approver['action_date'] != '0000-00-00 00:00:00') {
                                                        echo '<br /><strong>Action taken on: </strong>'.reset_datetime(array('datetime' => $approver['action_date'], '_this' => $this));
                                                    }
                                                ?>
                                            </td>
                                            <td class="<?=$statusClass;?>"><strong><?=$status;?></strong>
                                            </td>
                                            <td><?=isset($approver['note']) ? $approver['note'] : '-';?>
                                            </td>
                                        </tr>           
                                    <?php } ?>    
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="4" class="col-lg-12 text-center"><b>No Approval Document(s) Assign!</b></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>        

<!-- Preview Latest Document Modal Start -->
<div id="approval_action_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="approval_action_modal_title"></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="approver_action_sid" value="">
                <input type="hidden" id="approver_document_sid" value="">
                <div class="form-group full-width">
                    <label>Action<span class="hr-required red"> * </span></label>
                    <input type="text" value="" class="form-control" id="approver_action_status" readonly>
                </div>
                <div class="form-group full-width">
                    <label>Note</label>
                    <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="approver_action_note"></textarea>
                </div>
            </div>
            <div class="modal-footer" id="approval_action_modal_footer">
                <button id="jsSaveAction" class="btn btn-info">Save</button>
                <button id="jsCancelAction" class="btn btn-black">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->


<script>
    //
    $(document).on('click', '.jsPerformAction', function(event) {
        var action = $(this).data("action");
        var assigner_sid = $(this).data("sid");
        var document_sid = $(this).data("doc_sid");
        //
        var title = "Approve Document";

        if (action == "reject") {
            title = "Reject Document";
        }
        
        $('#approval_action_modal').modal('show');
        $('#approver_action_status').val(capitalizeFirstLetter(action));
        $('#approver_action_sid').val(assigner_sid);
        $('#approver_document_sid').val(document_sid);
        $("#approval_action_modal_title").html(title);
    });

    $(document).on('click', '#jsCancelAction', function(event) {
        $('#approval_action_modal').modal('hide');
        $('#approver_action_status').val("");
        $('#approver_action_sid').val("");
        $('#approver_document_sid').val("");
        CKEDITOR.instances.approver_action_note.setData('');
        $("#approval_action_modal_title").html("");
    });

    $(document).on('click', '#jsSaveAction', function(event) {
        //
        var action = $('#approver_action_status').val();
        var approver_sid = $('#approver_action_sid').val();
        var document_sid = $('#approver_document_sid').val();
        var action_note = CKEDITOR.instances.approver_action_note.getData();
        //
        var form_data = new FormData();
        form_data.append('approver_sid', approver_sid);
        form_data.append('approver_action', action);
        form_data.append('approver_note', action_note);
        form_data.append('document_sid', document_sid);
        //
        $.ajax({
            url: '<?= base_url('hr_documents_management/save_approval_document_action') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function (resp) {
                //
                if (resp.Status === false) {
                    $('#approval_action_modal').modal('hide');
                    alertify.alert("Notice", resp.Msg);
                    return;
                }
                //
                alertify.alert("Notice", resp.Msg, function(){
                    window.location.reload();
                });
            },
            error: function () {
            }
        });
    });

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>