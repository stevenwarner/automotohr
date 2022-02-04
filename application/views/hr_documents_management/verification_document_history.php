<!-- Preview Latest Document Modal Start -->
<div id="verification_document_history_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Fillable Verification Document History
                </h4>
            </div>
            <div class="modal-body"> 
                <div class="table-responsive full-width">
                    <table class="table table-plane table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="column" class="col-lg-3">Document Type</th>
                                <th scope="column" class="col-lg-2 text-right">Assigned On</th>
                                <th scope="column" class="col-lg-1 text-right">Completed On</th>
                                <th scope="column" class="col-lg-2 text-right">Status</th>
                                <th scope="column" class="col-lg-1 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->
<script type="text/javascript">
    function VerificationDocumentHistory (type, sid) {
        $('#verification_document_history_modal .modal-title').text(type.toUpperCase()+' Document');
        //
        $('#verification_document_history_modal tbody').html('<tr><td colspan="12"><p class="alert alert-info text-center">Please wait while we fetch the document trail.</p></td></tr>');
        //
        $.get("<?=base_url('eeoc/get_history/'.($user_sid).'/'.($user_type).'');?>/"+(type)+"")
        .success(function(resp){
            //
            var html = '';
            //
            if(resp.length === 0){
                html +=' <tr>';
                html +='    <td colspan="12">';
                html +='        <p class="alert alert-info text-center">';
                html +='         No history found against this document.';
                html +='        </p>';
                html +='    </td>';
                html +=' </tr>';
                //
                return  $('#verification_document_history_modal tbody').html(html);
            }
            //
            resp.map(function(single){

                html +=' <tr>';
                html +='     <td class="col-lg-3" style="vertical-align: middle">';
                html += single.name+'<br>';
                html +='     </td>';
                html +='     <td class="col-lg-1 text-center">';
                //
                if(single.assign_on != '0000-00-00 00:00:00'){
                    html +='<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>';
                    html +='<div class="text-center">';
                    html += single.assign_on;
                    html +='</div>';
                } else{
                    html +='<i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
                }
                html +='     </td>';
                html +='     <td class="col-lg-2 text-center"style="vertical-align: middle">';
                //
                if(single.submitted_on != '0000-00-00 00:00:00'){
                    html +='<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>';
                    html +='<div class="text-center">';
                    html += single.submitted_on;
                    html +='</div>';
                } else{
                    html +='<i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
                }
                html +='     </td>';
                html +='     <td class="col-lg-2 text-right"style="vertical-align: middle">';
                html += single.status;
                html +='     </td>';
                html +='     <td class="col-lg-2 text-right"style="vertical-align: middle">';
                <?php if($user_type == 'employee') { ?>
                html +='         <button';
                html +='             class="btn btn-success btn-sm btn-block"';
                html +='             onclick="preview_verification_doc_history_sep(this);"';
                html +='             data-history_id="'+(single.sid)+'"';
                html +='             data-history_type="'+(single.type)+'">';
                html +='             Preview Fillable';
                html +='         </button>';
                <?php } else { ?>
                    html +='         -';
                <?php } ?>
                html +='     </td>';
                html +=' </tr>';
            });
            //
            $('#verification_document_history_modal tbody').html(html);
        })
        .error();
        //
        $('#verification_document_history_modal').modal('show');
    }

    function preview_verification_doc_history_sep (source) {
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