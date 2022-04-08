<?php 
    $show_empty_box = true; 
    $document_d_base = base_url('hr_documents_management/review_approval_document');
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-9">
                <div class="panel panel-default ems-documents">
                    <div class="panel-heading">
                        <strong>Approval Documents</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-plane cs-w4-table">
                            <thead>
                                <tr>
                                    <th class="col-lg-2">Document Title</th>
                                    <th class="col-lg-2 hidden-xs">User Name</th>
                                    <th class="col-lg-2 hidden-xs">User Type</th>
                                    <th class="col-lg-2 hidden-xs">Note</th>
                                    <th class="col-lg-4 col-xs-12 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($assign_approvals) && !empty($assign_approvals)) { ?>
                                    <?php foreach ($assign_approvals as $key => $document) { ?>
                                        <tr>
                                            <td class="">
                                                <?php
                                                    echo $document['document_title'];
                                                    echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                    if (isset($document['assign_on']) && $document['assign_on'] != '0000-00-00 00:00:00') {
                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assign_on'], '_this' => $this));
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if ($document['user_type'] == "employee") {
                                                        echo getUserNameBySID($document['user_sid']);
                                                    } else {
                                                        echo getApplicantNameBySID($document['user_sid']);
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    echo ucfirst($document['user_type']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    echo implode(' ', array_slice(explode(' ', $document['note']), 0, 10));
                                                ?>
                                            </td>
                                            <td class="text-center hidden-xs">
                                                <a href="javascript:;" data-action="approve" data-sid="<?php echo $document['sid'] ?>" data-doc_sid="<?php echo $document['document_sid'] ?>" class="jsPerformAction btn btn-success">Approve</a>
                                                <a href="javascript:;" data-action="reject" data-sid="<?php echo $document['sid'] ?>" data-doc_sid="<?php echo $document['document_sid'] ?>" class="jsPerformAction btn btn-warning">Reject</a>
                                                <a href="<?php echo $document_d_base . '/' . $document['portal_document_assign_sid']; ?>" class="btn btn-info">View Document</a>
                                            </td>
                                        </tr>           
                                    <?php } ?>    
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="7" class="col-lg-12 text-center"><b>No Approval Document(s) Assign!</b></td>
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

<?php $this->load->view('loader_new', ['id' => 'jsApprovalStatusLoader']); ?>

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

<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/SystemModal.js"></script>


<script type="text/javascript">
    ml(false, 'jsApprovalStatusLoader');
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
        $('#approval_action_modal').modal('hide');
        ml(true, 'jsApprovalStatusLoader');
        //
        var action = $('#approver_action_status').val();
        var assigner_sid = $('#approver_action_sid').val();
        var document_sid = $('#approver_document_sid').val();
        var action_note = CKEDITOR.instances.approver_action_note.getData();
        //
        var form_data = new FormData();
        form_data.append('sid', assigner_sid);
        form_data.append('action', action);
        form_data.append('note', action_note);
        form_data.append('document_sid', document_sid);
        //
        $.ajax({
            url: '<?= base_url('hr_documents_management/save_approval_document_action') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function (data) {
                ml(false, 'jsApprovalStatusLoader');
                
            },
            error: function () {
            }
        });
    });

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>