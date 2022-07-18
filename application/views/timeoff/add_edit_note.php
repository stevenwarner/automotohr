<!-- Note Modal -->
<div id="jsAddNoteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="loader" id="jsAddNoteModalLoader" style="display: none;">
        <i aria-hidden="true" class="fa fa-spinner fa-spin"></i>
    </div>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Time off Note</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                <input type="hidden" id="jsRequestSid" >
                <input type="hidden" id="jsEmployeeSid" >
                <div class="row">
                    <div class="col-xs-12">
                        <label>Comment <span class="cs-required">*</span></label>
                    </div>    
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <textarea class="invoice-fields autoheight" maxlength="250" id="jsNoteSection" cols="78" rows="6"></textarea>    
                    </div>
                </div>        
            </div>
            <div id="document_modal_footer" class="modal-footer">
                <button type="button" class="btn btn-success pull-right" id="jsSaveEmployeeNote">Save</button>
                <button type="button" class="btn black-btn" data-dismiss="modal" aria-label="Close" style="margin-right: 8px;">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	//
    $(document).on('click', '.jsEditNote', function() {
        let request_sid = $(this).closest('.jsBox').data('id');
        let employeeSid = $(this).attr('data-empSid');
        let obj = {
            action: 'get_employee_note',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeSid,
            requestId: request_sid
        };
        //
        $.post(
            handlerURL,
            obj,
            function(resp) {
                $("#jsRequestSid").val(request_sid);
                $("#jsEmployeeSid").val(employeeSid);
                $("#jsNoteSection").val(resp.Comment);
                $("#jsAddNoteModal").modal("show");
            }
        );
        
    });

    //
    $(document).on('click', '#jsSaveEmployeeNote', function() {
        //
        $('#jsAddNoteModalLoader').show();
        // 
        if ($("#jsNoteSection").val().trim().length == 0){
            alertify.alert("Notice","Comment is required!")
        } else {
            $('#jsSaveEmployeeNote').prop('disabled', true);
            //
            let obj = {
                action: 'update_employee_note',
                companyId: companyId,
                employerId: employerId,
                employeeId: $("#jsEmployeeSid").val(),
                requestId: $("#jsRequestSid").val(),
                comment: $("#jsNoteSection").val()
            };
            //
            $.post(
                handlerURL,
                obj,
                function(resp) {
                    alertify.alert(
                        'SUCCESS!',
                        resp.Response,
                        function() {
                            $("#jsAddNoteModal").modal('hide');
                           // window.location.reload();
                            fetchTimeOffs();
                        }
                    )
                }
            );
        } 
    });
</script>