function DocumentApproverPrefill(assigners, documentID, modal) {
	//
	if (!assigners) {
		return '';
	}
	//
	assigners.split(',').map(function(sa) {
		var rowId = Math.round((Math.random() * 10000) + 1);
		var row = generateApproverRow(rowId, documentID, sa);
		$(""+(modal !== undefined ? modal : '')+" .jsEmployeesadditionalBox").append(row);

		$(''+(modal !== undefined ? modal : '')+' #js-employees-' + rowId).select2({
			closeOnSelect: false,
			allowHtml: true,
			allowClear: true,
		});

		$(''+(modal !== undefined ? modal : '')+' #js-employees-' + rowId).select2('val', sa);
		
	});
	//
	$(""+(modal !== undefined ? modal : '')+" .jsAssignerEmployeesNote").show();

}

// generates row
function generateApproverRow(rowId, documentID, approverID) {
	//
	var rows = '';
	rows += '<div class="row js-employee-' + (rowId) + ' row_id jsSelectedEmployee" data-id="' + (rowId) + '">';
	rows += '<br />';
	rows += '    <div class="cs-employee js-employee csMT">';
	rows += '        <div class="col-sm-10 col-sm-offset-0 text-left">';
	rows += '           <select id="js-employees-' + (rowId) + '" name="assigner[]" class="jsSelectedEmployee">';
	rows += '               <option value="0" >Please Select an Employee</option>';
	if(employeeList.length){
		employeeList.map(function(v){
			rows +='<option value="'+(v['sid'])+'">'+(remakeEmployeeName(v))+'</option>';
		});
	}
	rows += '           </select>';
	rows += '        </div>';
	rows += '        <div class="col-sm-2 col-sm-offset-0 text-right">';
	rows += '            <a href="javascript:;" class="btn btn-danger js-employee-delete-btn"><i class="fa fa-trash"></i></a>';
	rows += '        </div>';
	rows += '    </div>';
	rows += '<br />';
	rows += '<br />';
	if (documentID != 0 && documentID != undefined) {
		rows += '    <div class="col-sm-12" style="margin-top:8px;">';
		rows += '         <a href="javascript:;" class="btn btn-success btn-xs jsApproverViewStatus" data-doc_sid="'+documentID+'" data-app_sid="'+approverID+'" data-row_sid="'+rowId+'"><i class="fa fa-eye" style="font-size: 12px;"></i> Show Detail</a>';
		rows += '    </div>';
		rows += '    <div class="col-sm-12 jsApproverViewStatusBox" id="jsApproverViewStatus-'+rowId+'" style="display:none;">';
		rows += '    </div>';
	}
	rows += '</div>';

	//
	return rows;
}

$(document).on('click', '.jsApproverViewStatus', function(event) {
	$(".jsApproverViewStatusBox").html("");
	$(".jsApproverViewStatusBox").hide();
	$(".jsExternalApprover").hide();
	//
	var documentID = $(this).data("doc_sid");
	var approverID = $(this).data("app_sid");
	var rowID = $(this).data("row_sid");
	$.get(
        jsBaseURL+'hr_documents_management/get_document_approver_staus/'+documentID+'/'+approverID,
        (resp) => {
        	var rows = '';
        	rows += '<table class="table table-striped table-bordered">';
            rows += '	<tbody>';
            rows += '		<tr>'
            rows += '			<td class="col-sm-3"><strong>Status</strong></td>';
            if(resp.approver_info.approval_status) {
            	if (resp.approver_info.approval_status == "Approve") {
            		rows += '			<td class="col-sm-9 text-success">Approved</td>';
            	} else {
            		rows += '			<td class="col-sm-9 text-danger">Rejected</td>';
            	}
            } else {
            	rows += '			<td class="col-sm-9 text-warning">Pending</td>';
            }	
            rows += '		</tr>';  
            rows += '		<tr>'
            rows += '			<td class="col-sm-3"><strong>Note</strong></td>';
            rows += '			<td class="col-sm-9">'+resp.approver_info.note+'</td>';
            rows += '		</tr>';
            rows += '		<tr>'
            rows += '			<td class="col-sm-3"><strong>Assigned On</strong></td>';
            var assignOn = 'null';
            if(resp.approver_info.assign_on) {
            	assignOn = moment(resp.approver_info.assign_on).format('MMM Do YYYY, ddd H:m:s');
            }
            rows += '			<td class="col-sm-9">'+assignOn+'</td>';
            rows += '		</tr>';  
            var actionOn = 'null';
            if(resp.approver_info.action_date) {
            	actionOn = moment(resp.approver_info.action_date).format('MMM Do YYYY, ddd H:m:s');
            }
            rows += '		<tr>'
            rows += '			<td class="col-sm-3"><strong>Action Date</strong></td>';
            rows += '			<td class="col-sm-9">'+actionOn+'</td>';
            rows += '		</tr>';  
            rows += '	</tbody>';
            rows += '</table>';
            $('#jsApproverViewStatus-'+rowID).show();
            $('#jsApproverViewStatus-'+rowID).append(rows);
        }
    )
});

function DocumentExternalApproverPrefill(documentID) {
	$.get(
        jsBaseURL+'hr_documents_management/get_document_external_default_approver/'+documentID,
        (resp) => {
        	if (resp.external_approvers) {
	        	var rows = "";
	        	rows += '<hr>';
	        	rows += '<p class="text-danger csF16" style="margin-bottom:0px">';
	        	rows += ' 	<strong>';
	        	rows += '		<i aria-hidden="true">Document External Approvers</i>';
	        	rows += ' 	</strong>';
	        	rows += '</p>';
	        	//
	        	resp.external_approvers.map(function(v, i){
	        		//
	        		rows += '<div class="row">';
					rows += '	<br />';
					rows += '    <div class="cs-employee js-employee csMT">';
					rows += '        <div class="col-sm-10 col-sm-offset-0 text-left">';
					rows += '        	<div class="">';                
					rows += '        		<input type="text" class="form-control tag" readonly="" value="'+v.approver_name+'">';            
					rows += '        	</div>';
					rows += '        </div>';
					rows += '    </div>';
					rows += '<br />';
					if (documentID != 0 && documentID != undefined) {
						rows += '    <div class="col-sm-12" style="margin-top:8px;">';
						rows += '         <a href="javascript:;" class="btn btn-success btn-xs jsApproverViewExternalStatus" data-approver_email="'+i+'"><i class="fa fa-eye" style="font-size: 12px;"></i> Show Detail</a>';
						rows += '    </div>';
						//
						rows += '    <div class="col-sm-12">';
			            rows += '<table class="table table-striped table-bordered jsExternalApprover" id="jsApproverViewExternalStatus_'+i+'" style="display:none;">';
			            rows += '	<tbody>';
			            rows += '		<tr>'
			            rows += '			<td class="col-sm-3"><strong>Status</strong></td>';
			            if(v.approval_status) {
			            	if (v.approval_status == "Approve") {
			            		rows += '			<td class="col-sm-9 text-success">Approved</td>';
			            	} else {
			            		rows += '			<td class="col-sm-9 text-danger">Rejected</td>';
			            	}
			            } else {
			            	rows += '			<td class="col-sm-9 text-warning">Pending</td>';
			            }	
			            rows += '		</tr>';  
			            rows += '		<tr>'
			            rows += '			<td class="col-sm-3"><strong>Note</strong></td>';
			            rows += '			<td class="col-sm-9">'+v.note+'</td>';
			            rows += '		</tr>';
			            rows += '		<tr>'
			            rows += '			<td class="col-sm-3"><strong>Assigned On</strong></td>';
			            var assignOn = 'null';
			            if(v.assign_on) {
			            	assignOn = moment(v.assign_on).format('MMM Do YYYY, ddd H:m:s');
			            }
			            rows += '			<td class="col-sm-9">'+assignOn+'</td>';
			            rows += '		</tr>';  
			            var actionOn = 'null';
			            if(v.action_date) {
			            	actionOn = moment(v.action_date).format('MMM Do YYYY, ddd H:m:s');
			            }
			            rows += '		<tr>'
			            rows += '			<td class="col-sm-3"><strong>Action Date</strong></td>';
			            rows += '			<td class="col-sm-9">'+actionOn+'</td>';
			            rows += '		</tr>';  
			            rows += '	</tbody>';
			            rows += '</table>';
			            rows += '</div>';
					}
					//
					rows += '</div>';
				});
				//
				$(".jsEmployeesadditionalExternalBox").append(rows);
			}			
        }
    )

}

$(document).on('click', '.jsApproverViewExternalStatus', function(event) {
	$(".jsExternalApprover").hide();
	$(".jsApproverViewStatusBox").html("");
	$(".jsApproverViewStatusBox").hide();
	//
	var approver_id = $(this).data("approver_email");
	$("#jsApproverViewExternalStatus_"+approver_id).show();
});

function refreshApprovalSection (modalSid) {
	//
	$(""+(modalSid !== undefined ? modalSid : '')+" #jsHasApprovalFlow").prop('checked', false);
	$(""+(modalSid !== undefined ? modalSid : '')+" .jsEmployeesadditionalBox").html("");
	$(""+(modalSid !== undefined ? modalSid : '')+" #assigner_note").val("");
	$(""+(modalSid !== undefined ? modalSid : '')+" .jsApproverFlowContainer").hide();
}