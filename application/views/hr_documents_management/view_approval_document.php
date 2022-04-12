<div class="csPageWrap">
    <div class="container">
        <!--  -->
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h1 class="csB7">Approval Documents</h1>
            </div>
            <div class="col-md-6 col-sm-12 text-right" style="margin-top: 30px;">
                <a href="javascript:;" data-action="approve" data-sid="<?php echo $document_info['sid'] ?>" data-doc_sid="<?php echo $document_info['document_sid'] ?>" class="jsPerformAction btn btn-success">Approve</a>
                <a href="javascript:;" data-action="reject" data-sid="<?php echo $document_info['sid'] ?>" data-doc_sid="<?php echo $document_info['document_sid'] ?>" class="jsPerformAction btn btn-warning">Reject</a>
            </div>
        </div>
        <!--  -->
        <div class="csPageBox csRadius5">
            <div class="csPageBoxBody">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <tr>
                                <th class="col-sm-3">Document Title</th>
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
                        </table>
                    </div>
                </div>

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
                                    <th class="col-lg-4">Employee Name</th>
                                    <th class="col-lg-2">Assign On</th>
                                    <th class="col-lg-2">Assign By</th>
                                    <th class="col-lg-2">Approver Status</th>
                                    <th class="col-lg-2">Action Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($assigners) && !empty($assigners)) { ?>
                                    <?php foreach ($assigners as $assigner) { ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    echo getUserNameBySID($assigner['assigner_sid']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if (isset($assigner['assign_on']) && $assigner['assign_on'] != '0000-00-00 00:00:00') {
                                                        echo reset_datetime(array('datetime' => $assigner['assign_on'], '_this' => $this));
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    echo getUserNameBySID($document_info['assigned_by']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    echo $assigner['approval_status'] ? $assigner['approval_status'] : 'Pending';
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if (isset($assigner['action_date']) && $assigner['action_date'] != '0000-00-00 00:00:00') {
                                                        echo reset_datetime(array('datetime' => $assigner['action_date'], '_this' => $this));
                                                    }else{
                                                        echo '-';
                                                    }
                                                ?>
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
        $('#approval_action_modal').modal('hide');
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
                window.location.reload();
                
            },
            error: function () {
            }
        });
    });

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>