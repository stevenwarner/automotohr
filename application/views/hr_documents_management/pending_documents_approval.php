<?php 
    $show_empty_box = true; 
    $document_d_base = base_url('hr_documents_management/review_approval_document');
    
?>
<div class="main csPageWrap">
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-sm-12">
                <a href="<?=base_url('dashboard');?>" class="btn btn-info csRadius5">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard
                </a>
            </div>
        </div>
        <div class="row">
            <br>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-9">
                <!--  -->
                <div class="panel panel-default ems-documents">
                    <div class="panel-heading">
                        <strong class="csF20 csB7">Documents to be approve for assign</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-lg-2 text-right">Employee/Applicant</th>
                                    <th class="col-lg-2">Document</th>
                                    <th class="col-lg-2">Note</th>
                                    <th class="col-lg-2">Assign/Action Dates</th>
                                    <th class="col-lg-2">Status</th>
                                    <th class="col-lg-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($assign_approvals) && !empty($assign_approvals)) { ?>
                                    <?php foreach ($assign_approvals as $key => $document) { ?>
                                        <?php 
                                            //
                                            $status = 'Pending';
                                            $statusClass = 'text-warning';
                                            //
                                            if(isset($document['approval_note'])){
                                                if($document['approval_status'] == 'Approve'){
                                                    $status = 'Approved';
                                                    $statusClass = 'text-success';
                                                }
                                                else if($document['approval_status'] == 'Reject'){
                                                    $status = 'Rejected';
                                                    $statusClass = 'text-danger';
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td class="vam">
                                                <?php 
                                                    if ($document['user_type'] == "employee") {
                                                        echo getUserNameBySID($document['user_sid']);
                                                    } else {
                                                        echo getApplicantNameBySID($document['user_sid']);
                                                    }
                                                    echo ' ['.ucfirst($document['user_type']).']';
                                                ?>
                                            </td>
                                            <td class="vam">
                                                <?php
                                                    echo $document['document_title'];
                                                    echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter / Pay Plan)</b>' : '';
                                                    
                                                ?>
                                            </td>
                                            <td class="vam">
                                                <?php 
                                                    echo implode(' ', array_slice(explode(' ', $document['assigner_note']), 0, 10));
                                                ?>
                                            </td>
                                            <td class="vam">
                                                <?php 
                                                    if (isset($document['assign_on']) && $document['assign_on'] != '0000-00-00 00:00:00') {
                                                        echo "<br><b>Assigned on: </b>" . reset_datetime(array('datetime' => $document['assign_on'], '_this' => $this));
                                                    }
                                                    if (isset($document['approval_note'])) {
                                                        echo "<br><b>Action taken on: </b>" . reset_datetime(array('datetime' => $document['action_date'], '_this' => $this));
                                                    }
                                                ?>
                                            </td>
                                            <td class="vam <?=$statusClass;?>">
                                                <strong><?=$status;?></strong>
                                            </td>
                                            <td class="text-center vam">
                                                <?php if(!isset($document['approval_note'])): ?>
                                                <a href="javascript:;" data-action="Approve" data-sid="<?php echo $document['approver_sid'];?>" data-doc_sid="<?php echo $document['document_sid'] ?>" class="jsPerformAction btn btn-orange"><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;Approve</a>
                                                <a href="javascript:;" data-action="Reject" data-sid="<?php echo $document['approver_sid'];?>" data-doc_sid="<?php echo $document['document_sid'] ?>" class="jsPerformAction btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Reject</a>
                                                <?php endif; ?>
                                                <a target="_blank" href="<?php echo $document_d_base . '/' . $document['document_sid']; ?>" class="btn btn-info csRadius5"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Document</a> 
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
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/common.js"></script>


<script type="text/javascript">
    //
    var modalId = "";
    var modalLoader = "";
    var document_sid = "";
    //
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

    $(document).on('click', '#jsViewPendingDocument', function(event) {
        document_sid = $(this).data("doc_sid");
        modalId = "jsApprovalDocumentModal";
        modalLoader = modalId + 'Loader';
        //
        Model({
            Id: modalId,
            Title: '<span class="' + modalId + 'Title"></span>',
            Body: '<div id="' + modalId + 'Body"></div>',
            Loader: modalLoader,
            Container: 'container-fluid',
            CancelClass: 'btn-cancel csW'
        }, WelcomeJourney);
    });

    /**
     * Start the payroll process
     * includes
     * @method GetURL
     */
    function WelcomeJourney() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: '<?php echo $document_d_base; ?>' + "/" + document_sid,
            })
            .done(function(resp) {
                $('#' + (modalId) + 'Body').html(resp.html);
                //
                ml(false, modalLoader);
            })
            .error();
    }

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
</script>