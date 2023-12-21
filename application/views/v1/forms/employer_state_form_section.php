

<div id="show_latest_preview_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="jsFormTitle"></h4>
            </div>
            <div class="modal-body">
                <div id="jsStateEmployerSection">
                    
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
     $('.jsEmployerStateSectionPrefill').on('click', function() {
        var stateFormId = $(this).attr('form_sid');
        //
        $.ajax({
			url: '<?php echo base_url('hr_documents_management/get_state_employer_section'); ?>' + '/' + stateFormId,
			method: "GET",
		})
			.success(function (response) {
                let title = response.title
				$('#show_latest_preview_document_modal').modal('show');
                    $("#jsStateEmployerSection").html(response.view);
                    $("#jsFormTitle").html(title);
			});    
    });
</script>