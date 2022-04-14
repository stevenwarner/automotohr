<script type="text/javascript">
	$(document).on('click', '#jsAddDocumentAssigner', function(event) {
	    //
	    $(".jsAssignerEmployeesNote").show();
	    var rowId = Math.round((Math.random() * 10000) + 1);
	    var row = generateRow(rowId);
	    $(".jsEmployeesadditionalBox").append(row);

	    $('#js-employees-'+rowId).select2({
	        closeOnSelect : false,
	        allowHtml: true,
	        allowClear: true,
	    });
	});

	 // generates row
	function generateRow(rowId){
	    //
	    var rows = '';
	    rows += '<div class="row js-employee-'+( rowId )+' row_id" data-id="'+( rowId )+'">';
	    rows += '    <div class="cs-employee js-employee csMT">';
	    rows += '        <div class="col-sm-11 col-xs-12 ">';
	    rows += '           <select id="js-employees-'+( rowId )+'" name="assigner[]" class="jsSelectedEmployee">';
	    rows += '               <option value="0" >Please Select an Employee</option>';
	                            <?php foreach ($employeesList as $key => $employee) { ?>
	    rows += '                   <option value="<?php echo $employee['sid']; ?>" ><?= addslashes(remakeEmployeeName($employee)); ?></option>';
	                            <?php } ?>
	    rows += '           </select>';
	    rows += '        </div>';
	    rows += '        <div class="col-sm-1 col-xs-12">';
	    rows += '            <a href="javascript:;" class="btn btn-danger js-employee-delete-btn"><i class="fa fa-trash"></i></a>';
	    rows += '        </div>';
	    rows += '    </div>';
	    rows += '</div>';
	   
	    //
	    return rows;
	}

	$(document).on('click', '.js-employee-delete-btn', function(e){
	    e.preventDefault();
	    var _this = $(this);

	    if($(this).closest('.cs-employee').find('.js-text').val() == ''){
	        $(this).closest('.cs-employee').remove();
	        return;
	    }
	    //
	    alertify.confirm('Do you want to delete this row?', function(){
	        _this.closest('.cs-employee').remove();
	    })
	});

	$(document).on('close', '.jsSelectedEmployee', function(event) {
	    //
	    var employee_sid = $(this).val();
	    console.log(employee_sid)
	    
	});


	function DOGenerate(){
		//
		var assigners = "<?=$document_info['document_approval_employees'];?>";
		//
		if(!assigners){
			return '';
		}
		//
		assigners.split(',').map(function(sa){

			var rowId = Math.round((Math.random() * 10000) + 1);
			var row = generateRow(rowId);
			$(".jsEmployeesadditionalBox").append(row);
	
			$('#js-employees-'+rowId).select2({
				closeOnSelect : false,
				allowHtml: true,
				allowClear: true,
			});
			$('#js-employees-'+rowId).select2('val', sa);
		});
		$(".jsAssignerEmployeesNote").show();
		$("#assigner_note").val("<?=$document_info['document_approval_note'];?>");

	}

	DOGenerate();
</script>