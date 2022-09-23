<script type="text/javascript">
    //
    var t_user_sid = "<?=$user_sid;?>";
    var t_user_type = "<?=$user_type;?>";
    //
    $(document).on('click', '.jsShowDocumentHistory', ShowDocumentHistory);
    $(document).on('click', '.jsShowVarificationDocument', preview_user_document_history);
    //
    function ShowDocumentHistory () {
        var type = $(this).data("type");
        var doc_sid = 0;
        //
        if (type == "user_document") {
            doc_sid = $(this).data("doc_sid");
        }
        //
        $('#verification_document_history_modal .modal-title').text(type.toUpperCase()+' Document');
        //
        $('#verification_document_history_modal tbody').html('<tr><td colspan="12"><p class="alert alert-info text-center">Please wait while we fetch the document trail.</p></td></tr>');
        //
        $.get("<?=base_url('hr_documents_management/get_document_history/'.($user_sid).'/'.($user_type).'');?>/"+(type)+"/"+(doc_sid)+"")
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
                if(single.assign_on && single.assign_on != '0000-00-00 00:00:00'){
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
                if(single.submitted_on && single.submitted_on != '0000-00-00 00:00:00'){
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
                if(t_user_type == 'applicant' && type == 'eeoc'){
                    html +='         -';
                } else{
                    html +='         <button';
                    html +='             class="btn btn-success btn-sm btn-block"';
                    if (type == "user_document") {
                        html +='             onclick="preview_user_document_history(this);"';
                        html +='             data-history_id="'+(single.sid)+'"';
                        html +='             data-history_type="user_document">';
                        html +='             Preview Document';
                    } else {
                        html +='             onclick="preview_verification_doc_history_sep(this);"';
                        html +='             data-history_id="'+(single.sid)+'"';
                        html +='             data-history_type="'+(single.type)+'">';
                        html +='             Preview Fillable';
                    }
                    
                    html +='         </button>';
                }
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

    function preview_user_document_history (source) {
        var document_type = $(this).data("type");
        var doc_sid = $(this).data("doc_sid");
        var doc_status = $(this).data("status");
        var document_section = $(this).data("section");
        console.log(doc_status);
        //
        var url = '<?php echo base_url('hr_documents_management/get_verification_history_document'); ?>'+'/'+doc_sid+'/'+document_type;
        //
        if (doc_status == "Current") {
            url = '<?php echo base_url('hr_documents_management/get_all_completed_document'); ?>'+'/'+doc_sid+'/'+document_type+'/'+document_section;
        }
        //
        $('#document_loader').show();
        $('#loader_text_div').text("Please wait while we are getting document ");
        //
        $.ajax({
            'url': url,
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
</script>