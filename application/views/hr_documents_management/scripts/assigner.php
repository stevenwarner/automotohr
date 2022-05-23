<script>
	$(function() {
		// 
		var selectedApprovers = {};
		//
		var approvalContainer = $('.jsApproverFlowContainer');

		//
		$('#jsHasApprovalFlow').click(function(){
			//
			if($(this).prop('checked')){
				//
				approvalContainer.show(0);
			} else{
				//
				approvalContainer.hide(0);
				$('.jsEmployeesadditionalBox').html('');
				$('#assigner_note').val('');
			}
		});


		//
		$(document).on('click', '#jsAddDocumentAssigner', function(event) {
			//
			$(".jsAssignerEmployeesNote").show();
			var rowId = Math.round((Math.random() * 10000) + 1);
			var row = generateRow(rowId);
			$('.jsEmployeesadditionalBox').prepend(row);
			//
			if(Object.keys(selectedApprovers).length){
				Object.keys(selectedApprovers).map(function(sa){
					$('.js-employee-'+(rowId)+' option[value="'+(sa)+'"]').remove();
				});
			}
			//
			$('#js-employees-' + rowId).select2({
				closeOnSelect: false,
				allowHtml: true,
				allowClear: true,
			});
		});

		//
		$(document).on('select2:select', '.jsSelectedEmployee', function(event) {
			//
			selectedApprovers[$(this).val()] = true;
		});


		//
		$(document).on('click', '.js-employee-delete-btn', function(e) {
			//
			e.preventDefault();
			//
			var _this = $(this);

			if ($(this).closest('.cs-employee').find('.js-text').val() == '') {
				$(this).closest('.cs-employee').remove();
				return;
			}
			//
			alertify.confirm('Do you want to delete this approver?', function() {
				_this.closest('.cs-employee').remove();
			});
		});



		// generates row
		function generateRow(rowId) {
			//
			var rows = '<br />';
			rows += '<div class="row js-employee-' + (rowId) + ' row_id" data-id="' + (rowId) + '">';
			rows += '    <div class="cs-employee js-employee csMT">';
			rows += '        <div class="col-sm-10 col-sm-offset-0 text-left">';
			rows += '           <select id="js-employees-' + (rowId) + '" name="assigner[]" class="jsSelectedEmployee">';
			rows += '               <option value="0" >Please Select an Employee</option>';
			<?php foreach ($employeesList as $key => $employee) { ?>
				rows += '                   <option value="<?php echo $employee['sid']; ?>" ><?= addslashes(remakeEmployeeName($employee)); ?></option>';
			<?php } ?>
			rows += '           </select>';
			rows += '        </div>';
			rows += '        <div class="col-sm-2 col-sm-offset-0 text-right">';
			rows += '            <a href="javascript:;" class="btn btn-danger js-employee-delete-btn"><i class="fa fa-trash"></i></a>';
			rows += '        </div>';
			rows += '    </div>';
			rows += '</div>';

			//
			return rows;
		}
		
		function DOGenerate(assigners) {
			//
			if (!assigners) {
				return '';
			}
			//
			assigners.split(',').map(function(sa) {

				var rowId = Math.round((Math.random() * 10000) + 1);
				var row = generateRow(rowId);
				$(".jsEmployeesadditionalBox").append(row);

				$('#js-employees-' + rowId).select2({
					closeOnSelect: false,
					allowHtml: true,
					allowClear: true,
				});
				$('#js-employees-' + rowId).select2('val', sa);
			});
			$(".jsAssignerEmployeesNote").show();
			$("#assigner_note").val("<?= $document_info['document_approval_note']; ?>");

		}

		<?php if(isset($document_info['document_approval_employees'])): ?>
		DOGenerate("<?= $document_info['document_approval_employees']; ?>");
		<?php endif; ?>
	});
</script>