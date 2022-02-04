<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default hr-documents-tab-content">
            <div class="panel-heading <?php echo !empty($verification_documents_history) ? 'btn-success' : ''; ?>">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_verification_documents_history" >
                        <span class="glyphicon glyphicon-plus"></span>
                        <?php echo isset($history_title) ? $history_title : 'Re-Assign Verification Fillable History';?>
                        <div class="pull-right total-records"><b><?php echo '&nbsp;Total: '.sizeof($verification_documents_history);?></b></div>
                    </a>
                </h4>
            </div>
            <div id="collapse_verification_documents_history" class="panel-collapse collapse">
                <div class="table-responsive">
                    <table class="table table-plane">
                        <thead>
                            <tr>
                                <th scope="column" class="col-lg-3">Document Name</th>
                                <th scope="column" class="col-lg-2 text-center">Sent On</th>
                                <th scope="column" class="col-lg-1 text-center">Completed On</th>
                                <th scope="column" class="col-lg-2 text-center">Status</th>
                                <th scope="column" class="col-lg-1 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($verification_documents_history)) {
                                foreach ($verification_documents_history as $document) { ?>
                                    <tr>
                                        <td class="col-lg-3">
                                            <?php echo $document['name'] . '<br>'; ?>
                                        </td>
                                        <td class="col-lg-1 text-center">
                                            <?php if (isset($document['assign_on']) && $document['assign_on'] != '0000-00-00 00:00:00') { ?>
                                                <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                <div class="text-center">
                                                    <?php 
                                                        echo reset_datetime(array('datetime' => $document['assign_on'], '_this' => $this)); 
                                                    ?>
                                                </div>
                                            <?php } else { ?>
                                                <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                            <?php } ?>
                                        </td>
                                        <td class="col-lg-2 text-center">
                                            <?php if (isset($document['submitted_on']) && $document['submitted_on'] != '0000-00-00 00:00:00') { ?>
                                                <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                <div class="text-center">
                                                    <?php 
                                                        echo reset_datetime(array('datetime' => $document['submitted_on'], '_this' => $this)); 
                                                    ?>
                                                </div>
                                            <?php } else { ?>
                                                <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                            <?php } ?>
                                        </td>
                                        <td class="col-lg-2 text-center">
                                            <?php 
                                                echo $document['status'];
                                            ?>
                                        </td>
                                        <td class="col-lg-2 text-center">
                                            <?php 
                                                $button_color = "btn-success";
                                                if (isset($on_page) && $on_page == "employee_DOC_page") {
                                                    $button_color = "btn-info";
                                                }
                                            ?>
                                            <button
                                                class="btn <?php echo $button_color; ?> btn-sm btn-block"
                                                onclick="preview_verification_doc_history(this);"
                                                data-history_id="<?php echo $document['sid'];?>"
                                                data-history_type="<?php echo $document['type'];?>">
                                                Preview Fillable
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7" class="col-lg-12 text-center"><b>No History Available</b></td>
                                </tr>
                            <?php }  ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Latest Document Modal Start -->
<div id="fillable_history_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="history_document_modal_title">
                    Fillable Verification History
                </h4>
            </div>
            <div class="modal-body"> 
                <div id="history_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="history_document_modal_footer">
                
            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<script type="text/javascript">
    function preview_verification_doc_history (source) {
        var history_id = $(source).data('history_id');
        var history_type = $(source).data('history_type');
        //
        $('#document_loader').show();
        $('#loader_text_div').text("Please wait while we are getting history ");
        //
        $.ajax({
            'url': '<?php echo base_url('hr_documents_management/get_verification_history_document'); ?>'+'/'+history_id+'/'+history_type,
            'type': 'GET',
            success: function (resp) {

                var document_title = resp.name;
                var document_view = resp.html;
                //
                $('#fillable_history_document_modal').modal('show');
                $("#history_document_modal_title").html(document_title);
                $("#history_document_preview").html(document_view);
                $("#history_document_preview").show(); 
                //
                $('#document_loader').hide();
                //

                // footer_content = '<a target="_blank" class="btn btn-success" href="' + print_url + '">Print</a>';
                // footer_content += '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                // $("#latest_document_modal_footer").html(footer_content);
            }
        });
    }

    $('#show_latest_preview_document_modal').on('hidden.bs.modal', function () {
        $("#history_document_modal_title").html("Fillable Verification History");
        $('#history_document_preview').html('');
        $('#history_document_preview').hide();
    });

    
</script>