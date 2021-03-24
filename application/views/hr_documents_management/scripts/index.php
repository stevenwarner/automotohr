<?php 
	//
	if(!isset($AllNotCompletedOfferLetters)) $AllNotCompletedOfferLetters = [];
	if(!isset($AllCompletedOfferLetters)) $AllCompletedOfferLetters = [];
	if(!isset($AllNotCompletedDocuments)) $AllNotCompletedDocuments = [];
	if(!isset($AllCompletedDocuments)) $AllCompletedDocuments = [];
	if(!isset($AllNoActionRequiredDocuments)) $AllNoActionRequiredDocuments = [];
	if(!isset($all_documents)) $all_documents = [];
	//
	if(isset($GLOBALS['uofl'])) $AllNotCompletedOfferLetters = $GLOBALS['uofl'];
	if(isset($GLOBALS['uofl'])) $AllCompletedOfferLetters = $GLOBALS['uofl'];
	// if(isset($GLOBALS['ad'])) $AllCompletedDocuments = $GLOBALS['ad'];
	//
	if(isset($all_documents)) $all_documents = array_values($all_documents);
	// if(isset($AllNotCompletedDocuments)) $AllNotCompletedDocuments = array_values($AllNotCompletedDocuments);
	// if(isset($AllCompletedDocuments)) $AllCompletedDocuments = array_values($AllCompletedDocuments);
	// if(isset($AllNoActionRequiredDocuments)) $AllNoActionRequiredDocuments = array_values($AllNoActionRequiredDocuments);
	if(isset($AllNotCompletedOfferLetters)) $AllNotCompletedOfferLetters = array_values($AllNotCompletedOfferLetters);
	if(isset($AllCompletedOfferLetters)) $AllCompletedOfferLetters = array_values($AllCompletedOfferLetters);

	//
	$AllNotCompletedDocuments = array_values($GLOBALS['notCompletedDocumentsList']);
	$AllCompletedDocuments = array_values($GLOBALS['completedDocumentsList']);
	$AllNoActionRequiredDocuments = array_values($GLOBALS['noActionRequiredDocumentsList']);

?>

<?php foreach ($offerLetters as $k => $v) $offerLetters[$k]['letter_body'] = html_entity_decode($v['letter_body']); ?>
<?php foreach ($all_documents as $k => $v) $all_documents[$k]['document_description'] = html_entity_decode($v['document_description']); ?>
<?php foreach ($AllNoActionRequiredDocuments as $k => $v) $AllNoActionRequiredDocuments[$k]['document_description'] = html_entity_decode($v['document_description']); ?>
<?php foreach ($AllCompletedDocuments as $k => $v) $AllCompletedDocuments[$k]['document_description'] = html_entity_decode($v['document_description']); ?>
<?php foreach ($AllNotCompletedDocuments as $k => $v) $AllNotCompletedDocuments[$k]['document_description'] = html_entity_decode($v['document_description']); ?>
<?php foreach ($AllNotCompletedOfferLetters as $k => $v) $AllNotCompletedOfferLetters[$k]['document_description'] = html_entity_decode($v['document_description']); ?>
<?php foreach ($AllCompletedOfferLetters as $k => $v) $AllCompletedOfferLetters[$k]['document_description'] = html_entity_decode($v['document_description']); ?>
<style>
	#photo_gallery_modal, #popupmodal{ z-index: 9999 !important; }
	.cs-required, .cs-error{ font-weight: 900; color: #cc1100; font-size: 18px; }
	.cs-error{ font-size: 14px; }

	/*Loader*/
	.loader{ position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; background: rgba(255,255,255,.8); z-index: 9999 !important; }
    .loader i{ text-align: center; top: 50%; left: 50%; font-size: 40px; position: relative; }

    /**/
    .js-page-partial .select2-container--default .select2-selection--multiple .select2-selection__rendered{ height: auto !important; }
    .js-page-partial .select2-container--default .select2-selection--single{ background-color: #fff !important; border: 1px solid #aaa !important; }
</style>
<script>
	//
	function remakeEmployeeName(o, i){
	        //
	        var r = '';
	        //
	        if(i == undefined) r += o.first_name+' '+o.last_name;
	        //
	        if(o.job_title != '' && o.job_title != null) r+= ' ('+( o.job_title )+')';
	        //
	        r += ' [';
	        //
	        if(typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
	        //
	        if(o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1)  r += o['access_level']+' Plus / Payroll';
	        else if(o['access_level_plus'] == 1) r += o['access_level']+' Plus';
	        else if(o['pay_plan_flag'] == 1) r += o['access_level']+' Payroll';
	        else r += o['access_level'];
	        //
	        r += ']';
	        //
	        return r;
	    }
</script>
<?php $this->load->view('hr_documents_management/partials/visibilityjs', [
		'employeeList' => $managers_list,
		'departmentList' => $departments,
		'teamList' => $teams
	]); ?>
<script>
	//
	function func_show_instructions_modal(){
        var myRequest;
        var myData = { 'perform_action' : 'get_instructions' };
        var myUrl = '<?php echo base_url("photo_gallery/ajax_responder"); ?>';



        myRequest = $.ajax({
           url : myUrl,
            type: 'POST',
            data: myData,
            dataType: 'json'
        });

        myRequest.done(function (response) {

            $('#popupmodallabel').html(response.title);
            $('#popupmodalbody').html(response.view);

            $('#popupmodal .modal-dialog').css('width', '75%');
            $('#popupmodal').modal('toggle');
        });
    }
    //
	$(function(){
		//
		var megaOBJ = {
			'CompanySid' : <?=$company_sid;?>,
			'CompanyName' : "<?=$company_name;?>",
			'EmployerSid' : <?=$employer_sid;?>,
			'EmployeeSid' : <?=$EmployeeSid;?>,
			'Type' : '<?=$Type;?>',
			'Action' : ''
		}, 
		file = {},
		modelFor = 'document';
		refreshPage = "<?=isset($doRefresh) ? $doRefresh : 1;?>",
		currentOfferLetter = {},
		selectedTemplate = {},
		atarget = null,
		activeModalId = null,
		offerLetters = <?=json_encode($offerLetters);?>,
		allDocuments = <?=json_encode($all_documents);?>,
		allEmployees = <?=json_encode($managers_list);?>,
		tabDocs = {
			notCompletedDocuments: <?=json_encode($AllNotCompletedDocuments);?>,
			completedDocuments: <?=json_encode($AllCompletedDocuments);?>,
			noActionDocuments: <?=json_encode($AllNoActionRequiredDocuments);?>,
			notCompletedOfferLetters: <?=json_encode($AllNotCompletedOfferLetters);?>,
			completedOfferLetters: <?=json_encode($AllCompletedOfferLetters);?>,
		},
		allowedTypes = [
	        'application/pdf',
			'image/png',
			'image/jpg',
			'image/jpeg',
			'application/msword',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
	    ];

		// Triggers
		//
		$('.js-offer-letter-btn').click(StartOfferLetterProcess);
		$(document).on('click', '.js-template-type, .js-template-type-edit', SetView);
		$(document).on('click', '.js-save-offer-letter', ValidateAndSaveOfferLetter);
		$(document).on('click', '#js-save-offer-letter-edit', ValidateAndUpdateOfferLetter);
		$(document).on('change', '#js-offer-letter-file-add', ValidateUploadedFile);
		$('.js-offer-letter-edit-btn').click(StartOfferLetterEditProcess);
		$(document).on('click', '.js-show-current-document', ShowUploadedFilePreview);
		$(document).on('select2:select', '.js-templates',  UseTemplate);

		// Modify & Assign / Revoke triggers
		$(document).on('click', '.js-modify-assign-document-btn', StartModifyAndAssignProcess);
		$(document).on('change', '#js-modify-assign-document-input',  GetFileForUpload);
		$(document).on('click', '.js-modify-assign-document-file',  ShowUploadedFilePreview);
		$(document).on('click', '.js-modify-revoke-document-btn',  RevokeDocument);
		$(document).on('click', '.js-modify-assign-submit-btn',  AssignDocument);
		$(document).on('click', '.js-modify-assign-offer-letter-btn', StartModifyAndAssignOfferLetterProcess);
		$(document).on('click', '.js-modify-assign-offer-letter-submit-btn',  AssignOfferLetter);

		// Modify assigned document
		$('.js-modify-assigned-document-btn').click(StartModifyAssignedDocumentProcess);
		$(document).on('click', '.js-modify-assigned-submit-btn',  AssignedDocument);


		// Functions

		// Revoke document
		function RevokeDocument(
			e
		){
			e.preventDefault();
			//
			var 
				sid = $(this).data('id'),
				target = $(this).closest('td');
			//
			alertify.confirm(
				'Are you sure you want to revoke this document?', 
				() => {
					$.post(
						"<?=base_url('hr_documents_management/revoke_document');?>", 
						{ 
							sid: sid,
							employeeSid: megaOBJ.EmployeeSid
						}, 
						function(resp) {
							alertify.alert('SUCCESS!', 'Document revoked successfully.', function(){
								//
								if(refreshPage == 1) window.location.reload();
								//
								resetView( target, sid, 'revoke' );
							});
						}
					);
				}
			).set('labels', {
				ok: 'Yes',
				cancel: 'No'
			})
		}

		//
		function resetView(
			target,
			documentSid,
			action
		){
			switch (action) {
				case 'revoke':
					target.html('<button class="btn btn-warning btn-block js-modify-assign-document-btn" data-id="'+( documentSid )+'">Modify & Reassign</button>');
				break;
				case 'assign':
					$('#'+( activeModalId )+'').modal('hide');
					atarget.html('<button class="btn btn-danger btn-block js-modify-revoke-document-btn" data-id="'+( documentSid )+'">Revoke</button>');
				break;
			}
		}


		// Modify Assigned Document
		function StartModifyAssignedDocumentProcess(
			e
		){
			//
			e.preventDefault();
			//
			var 
				d = getAssignedDocument( 
					$(this).data('id'),
					$(this).data('type')
				),
			 	rows = '',
			 	do_upload,
			 	do_descpt;
			//
			selectedTemplate = d;
			console.log(d);
			//
			do_upload = d.document_type == 'uploaded' || d.document_type == 'hybrid_document' || d.offer_letter_type == 'uploaded' || d.offer_letter_type == 'hybrid_document' ? true : false;
			do_descpt = d.document_type == 'generated' || d.document_type == 'hybrid_document' || d.offer_letter_type == 'generated' || d.offer_letter_type == 'hybrid_document' ? true : false;
			//
			rows += '<h4 id="gen_document_label">Are you sure you want to assign this document: [<b>'+( d.document_title )+'</b>]</h4>';
			rows += '<b> Please review this document and make any necessary modifications.</b>';
			rows += '<hr />';
			//
			// Check if the document type
			if(do_upload) rows += getUploadContent();
			if(do_descpt) rows += getGeneratedContent('js-modify-assigned-document-description');
			<?php if(ASSIGNEDOCIMPL){ ?>
			rows += getConsentTypes(do_descpt);
			<?php } ?>
			if(do_descpt) rows += getSigners('js-modify-assigned-document-signers');
			//
			rows += getVisibility();
			//
			rows += getEmailContent();
			//
			if( $(this).data('type').toLowerCase().match(/notcompleted/ig) === null ){
				rows += getResetBox();
			}
			rows += getRequiredRow();
			rows += getSignatureRequiredRow();
			if(do_descpt) rows += getTags();
			//
			let select2s = ['#js-modify-roles', '#js-modify-selected-employees', '#js-modify-selected-departments', '#js-modify-selected-teams'];
			//
			if(do_descpt){ 
				select2s.push('#js-modify-assigned-document-signers');
			}
			//
			Modal(
				'Modify Assigned Document',
				rows,
				'<button class="btn btn-success js-modify-assigned-submit-btn">Update</button>',
				'modify-assigned-document-modal',
				do_descpt ? ['js-modify-assigned-document-description'] : [],
				select2s,
				function(){
					//
					do_descpt ? CKEDITOR.instances['js-modify-assigned-document-description'].setData(d.document_description) : '';
					$('#js-modify-assign-document-signature option[value="'+( d.signature_required )+'"]').prop('selected', true);
					$('#js-modify-assign-document-download option[value="'+( d.download_required )+'"]').prop('selected', true);
					$('#js-modify-assign-document-acknowledgment option[value="'+( d.acknowledgment_required )+'"]').prop('selected', true);
					do_descpt ? $('#js-modify-assigned-document-signers').select2('val', d.signers != null && d.signers != ''  ? d.signers.split(',') : null) : '';
					$('#js-modify-roles').select2('val', []);
					$('#js-modify-visible-to-payroll').prop('checked', selectedTemplate.visible_to_payroll == 0 ? false : true);
					$('.js-modify-assign-document-required[value="'+( selectedTemplate.is_required)+'"]').prop('checked', true);
					$('.js-modify-assign-document-signature-required[value="'+( selectedTemplate.is_signature_required )+'"]').prop('checked', true);
					$('#js-modify-selected-employees').select2('val', []);
					$('#js-modify-selected-departments').select2('val', []);
					$('#js-modify-selected-teams').select2('val', []);
					if(d.is_available_for_na != '0' && d.is_available_for_na != null) $('#js-modify-roles').select2('val', d.is_available_for_na.split(',') );
					if(d.allowed_employees != '0' && d.allowed_employees != null) $('#js-modify-selected-employees').select2('val', d.allowed_employees.split(',') );
					if(d.allowed_departments != '0' && d.allowed_departments != null) $('#js-modify-selected-departments').select2('val', d.allowed_departments.split(',') );
					if(d.allowed_teams != '0' && d.allowed_teams != null) $('#js-modify-selected-teams').select2('val', d.allowed_teams.split(',') );
					$('.modify-assigned-document-modal-loader').fadeOut(300);
					$('[data-toggle="propover"]').popover({
						trigger: 'hover',
						placement: 'right'
					});
					$('.jsModifyModalLoader').fadeOut(300);
				}
			);

			var allowedTypes = ['jpg','jpeg','png','gif','pdf','doc','docx','rtf','ppt','xls','xlsx','csv'];
			//
			if (d.document_type == 'offer_letter') {
				allowedTypes = ['jpg','jpeg','png','gif','pdf','doc','docx'];
			}
			//
			$('#modify_assign_document').mFileUploader({
	            fileLimit: -1,
	            allowedTypes: allowedTypes,
	            placeholderImage: d.document_s3_name != '' && d.document_s3_name != null ? d.document_s3_name : ''
	        });
		}


		// Modify and Assign
		function StartModifyAndAssignProcess(
			e
		){
			//
			e.preventDefault();
			//
			var 
				d = getDocument( $(this).data('id') ),
			 	rows = '',
			 	do_upload,
			 	do_descpt;
			//
			selectedTemplate = d;
			//
			atarget = $(this).closest('td');
			//
			activeModalId = 'modify-assign-document-modal';
			//
			do_upload = d.document_type == 'uploaded' || d.document_type == 'hybrid_document' ? true : false;
			do_descpt = d.document_type == 'generated' || d.document_type == 'hybrid_document' ? true : false;
			//
			rows += '<h4 id="gen_document_label">Are you sure you want to assign this document: [<b>'+( d.document_title )+'</b>]</h4>';
			rows += '<b> Please review this document and make any necessary modifications.</b>';
			rows += '<hr />';
			//
			// Check if the document type
			if(do_upload) rows += getUploadContent();
			if(do_descpt) rows += getGeneratedContent('js-modify-assign-document-description');
			<?php if(ASSIGNEDOCIMPL){ ?>
			rows += getConsentTypes(do_descpt);
			<?php } ?>
			if(do_descpt) rows += getSigners();
			rows+= getEmailContent();
			rows += getRequiredRow();
			rows += getSignatureRequiredRow();
			if(do_descpt) rows += getTags();
			//
			Modal(
				'Modify & Assign This Document',
				rows,
				'<button class="btn btn-success js-modify-assign-submit-btn">Assign This Document</button>',
				'modify-assign-document-modal',
				do_descpt ? ['js-modify-assign-document-description'] : [],
				do_descpt ? ['#js-modify-assign-document-signers'] : [],
				function(){
					//
					do_descpt ? CKEDITOR.instances['js-modify-assign-document-description'].setData(d.document_description) : '';
					$('#js-modify-assign-document-signature option[value="'+( d.signature_required )+'"]').prop('selected', true);
					$('#js-modify-assign-document-download option[value="'+( d.download_required )+'"]').prop('selected', true);
					$('#js-modify-assign-document-acknowledgment option[value="'+( d.acknowledgment_required )+'"]').prop('selected', true);
					do_descpt ? $('#js-modify-assign-document-signers').select2('val', d.signers != null && d.signers != ''  ? d.signers.split(',') : null) : '';
					$('.jsModifyModalLoader').fadeOut(300);

					$('#modify_assign_document').mFileUploader({
						fileLimit: -1, // Default is '2MB', Use -1 for no limit (Optional)
						allowedTypes: ['jpg','jpeg','png','gif','pdf','doc','docx','rtf','ppt','xls','xlsx','csv'],  //(Optional)
						placeholderImage: d.uploaded_document_s3_name // Default is empty ('') but can be set any image  (Optional)
					});
				}
			);
		}

		// Modify and Assign
		// offer letter
		function StartModifyAndAssignOfferLetterProcess(
			e
		){
			//
			e.preventDefault();
			//
			var 
				d = getOfferLetter( $(this).data('id') ),
			 	rows = '',
			 	do_upload,
			 	do_descpt;
			//
			selectedTemplate = d;
			modelFor = 'offer_letter';
			//
			do_upload = d.letter_type == 'uploaded' || d.letter_type == 'hybrid_document' ? true : false;
			do_descpt = d.letter_type == 'generated' || d.letter_type == 'hybrid_document' ? true : false;
			//
			rows += '<h4 id="gen_document_label">Are you sure you want to assign this document: [<b>'+( d.letter_name )+'</b>]</h4>';
			rows += '<b> Please review this document and make any necessary modifications.</b>';
			rows += '<hr />';
			//
			// Check if the document type
			if(do_upload) rows += getUploadContent();
			if(do_descpt) rows += getGeneratedContent('js-modify-assign-offer-letter-description');
			<?php if(ASSIGNEDOCIMPL){ ?>
			rows += getConsentTypes(do_descpt);
			<?php } ?>
			if(do_descpt) rows += getSigners('js-modify-assign-offer-letter-signers');
			rows+= getEmailContent();
			if(do_descpt) rows += getTags();
			//
			Modal(
				'Modify & Assign This Offer Letter / Pay Plan',
				rows,
				'<button class="btn btn-success js-modify-assign-offer-letter-submit-btn">Assign This Offer Letter / Pay Plan</button>',
				'modify-assign-offer-letter-modal',
				do_descpt ? ['js-modify-assign-offer-letter-description'] : [],
				do_descpt ? ['#js-modify-assign-offer-letter-signers'] : [],
				function(){
					//
					do_descpt ? CKEDITOR.instances['js-modify-assign-offer-letter-description'].setData(d.letter_body) : '';
					$('#js-modify-assign-offer-letter-signature option[value="'+( d.signature_required )+'"]').prop('selected', true);
					$('#js-modify-assign-offer-letter-download option[value="'+( d.download_required )+'"]').prop('selected', true);
					$('#js-modify-assign-offer-letter-acknowledgment option[value="'+( d.acknowledgment_required )+'"]').prop('selected', true);
					do_descpt ? $('#js-modify-assign-offer-letter-signers').select2('val', d.signers != null && d.signers != ''  ? d.signers.split(',') : null) : '';
					$('.modify-assign-offer-letter-modal-loader').fadeOut(300);
				}
			);
		}


		//
		function getResetBox(){
			return '';
			var rows = '';
			//
			rows += '<div class="form-group">';
			rows += '	<hr />';
			rows += '	<label>Remove submitted document?</label>';
			rows += '	<br />';
			rows += '	<label class="control control--radio">';
			rows += '	    <input type="radio" class="js-modify-assigned-document-reset" name="js-modify-assigned-document-reset" value="no" checked="true" /> No &nbsp;';
			rows += '	    <div class="control__indicator"></div>';
			rows += '	</label>';
			rows += '	<label class="control control--radio">';
			rows += '	    <input type="radio"  class="js-modify-assigned-document-reset" name="js-modify-assigned-document-reset" value="yes" /> Yes &nbsp;';
			rows += '	    <div class="control__indicator"></div>';
			rows += '	</label>';
			rows += '</div>';
			//
			return rows;
		}

		//
		function getConsentTypes(do_descpt){
			//
			var rows = '';
			//
			rows += '<div class="row">';
			rows += '<div class="col-sm-12">';
			rows += '<hr />';
			rows += '<div class="form-group">';
			rows += '	<label>Acknowledgment Required</label>';
			rows += '	<div class="hr-select-dropdown">';
			rows += '		<select id="js-modify-assign-document-acknowledgment" class="form-control">';
			rows += '			<option value="0">No</option>';
			rows += '			<option value="1">Yes</option>';
			rows += '		</select>';
			rows += '	</div>';
			rows += '	<p class="help-text">Enable the Acknowledgment Requirement, if you need a confirmation that a Document has been received by the Employee or Onboarding Candidate.</p>';
			rows += '</div>';
			rows += '</div>';
			rows += '</div>';
			rows += '<div class="row">';
			rows += '<div class="col-sm-12">';
			rows += '<!-- 4 -->';
			rows += '<div class="form-group">';
			rows += '	<label>Download Required</label>';
			rows += '	<div class="hr-select-dropdown">';
			rows += '		<select id="js-modify-assign-document-download" class="form-control">';
			rows += '			<option value="0">No</option>';
			rows += '			<option value="1">Yes</option>';
			rows += '		</select>';
			rows += '	</div>';
			rows += '	<p class="help-text">Enable the Download Required, if you need the Employee or Onboarding Candidate to download this form.</p>';
			rows += '</div>';
			rows += '</div>';
			rows += '</div>';
			//
			if(do_descpt){
				rows += '<div class="row">';
				rows += '<div class="col-sm-12">';
				rows += '<!-- 5 -->';
				rows += '<div class="form-group">';
				rows += '	<label>Signature Required</label>';
				rows += '	<div class="hr-select-dropdown">';
				rows += '		<select id="js-modify-assign-document-signature" class="form-control">';
				rows += '			<option value="0">No</option>';
				rows += '			<option value="1">Yes</option>';
				rows += '		</select>';
				rows += '	</div>';
				rows += '	<p class="help-text">Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.</p>';
				rows += '</div>';
				rows += '</div>';
				rows += '</div>';
			} else{
				rows += '<div class="row">';
				rows += '<div class="col-sm-12">';
				rows += '<!-- 5 -->';
				rows += '<div class="form-group">';
				rows += '	<label>Re-Upload Required</label>';
				rows += '	<div class="hr-select-dropdown">';
				rows += '		<select id="js-modify-assign-document-signature" class="form-control">';
				rows += '			<option value="0">No</option>';
				rows += '			<option value="1">Yes</option>';
				rows += '		</select>';
				rows += '	</div>';
				rows += '	<p class="help-text">Enable the Re-Upload Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.</p>';
				rows += '</div>';
				rows += '</div>';
				rows += '</div>';
			}
			//
			return rows;
		}


		//
		function AssignedDocument(
			e
		){
			//
			e.preventDefault();
			//
			var obj = {};
			//
			let file = $('#modify_assign_document').mFileUploader('get');
			//
			obj.reset = $('.js-modify-assigned-document-reset:checked').val();
			//
			obj.documentTitle = selectedTemplate.document_title;
			obj.documentType =  selectedTemplate.document_type == 'offer_letter' ? selectedTemplate.offer_letter_type : selectedTemplate.document_type ;
			obj.managerList = $('#js-modify-assigned-document-signers').val();
			obj.isSignature = $('#js-modify-assign-document-signature').val();
			obj.isDownload = $('#js-modify-assign-document-download').val();
			obj.isAcknowledged = $('#js-modify-assign-document-acknowledgment').val();
			obj.documentSid = selectedTemplate.sid;
			// obj.visibleToPayroll = selectedTemplate.visible_to_payroll;
			obj.sendEmail = $('.js-modify-assign-document-send-email:checked').val();
			if(selectedTemplate.document_type == 'generated' || selectedTemplate.document_type == 'hybrid_document' || selectedTemplate.offer_letter_type == 'generated')
			obj.desc = CKEDITOR.instances['js-modify-assigned-document-description'].getData();
			if(selectedTemplate.document_type == 'uploaded' || selectedTemplate.document_type == 'hybrid_document' || selectedTemplate.offer_letter_type == 'uploaded'){
				obj.file = file.name === undefined ? selectedTemplate.document_s3_name : file;
				obj.fileOrigName = selectedTemplate.document_original_name;
			}
			//
			obj.isSignature = obj.isSignature === undefined ? 0 : obj.isSignature;
			obj.managerList = obj.managerList === undefined ? null : obj.managerList;
			obj.reset = obj.reset === undefined ? null : obj.reset;
			//
			obj.selected_employees = $('#js-modify-selected-employees').val();
			obj.selected_departments = $('#js-modify-selected-departments').val();
			obj.selected_teams = $('#js-modify-selected-teams').val();
			obj.selected_roles = $('#js-modify-roles').val();
			obj.visible_to_payroll = $('#js-modify-visible-to-payroll').prop('checked') ? 1 : 0;
			obj.mainDocumentId = selectedTemplate.document_sid;
			obj.isRequired = $('.js-modify-assign-document-required:checked').val();
			obj.isSignatureRequired = $('.js-modify-assign-document-signature-required:checked').val();
			//
			var post = new FormData();
			//
			$.each(megaOBJ, function(index, val) { post.append(index, val); });
			$.each(obj, function(index, val) { post.append(index, val); });
			//
			$('.jsModifyModalLoader').fadeIn(300);
			$.ajax({
				url: "<?=base_url('hr_documents_management/update_assigned_document');?>",
				type: 'POST',
				processData: false,
				contentType: false,
				data: post
			}).done( function(resp){
				//
				$('.jsModifyModalLoader').fadeOut(300);
				alertify.alert('SUCCESS!', ''+( selectedTemplate.document_type == 'offer_letter' ? 'Offer Letter / Pay Plan' : 'Document' )+' updated successfully '+( obj.sendEmail == 'yes' ? ' and an email notification is sent' : ''  )+'.', function(){
					window.location.reload();
				});
			});
		}


		//
		function AssignDocument(
			e
		){
			//
			e.preventDefault();
			//
			let file = $('#modify_assign_document').mFileUploader('get');
			//
			var obj = {};
			//
			obj.documentTitle = selectedTemplate.document_title;
			obj.documentType =  selectedTemplate.document_type;
			obj.managerList = $('#js-modify-assign-document-signers').val();
			obj.isSignature = $('#js-modify-assign-document-signature').val();
			obj.isDownload = $('#js-modify-assign-document-download').val();
			obj.isAcknowledged = $('#js-modify-assign-document-acknowledgment').val();
			obj.specific = selectedTemplate.is_specific;
			obj.documentSid = selectedTemplate.sid;
			obj.visibleToPayroll = selectedTemplate.visible_to_payroll;
			obj.sendEmail = $('.js-modify-assign-document-send-email:checked').val();
			obj.isRequired = $('.js-modify-assign-document-required:checked').val();
			obj.isSignatureRequired = $('.js-modify-assign-document-signature-required:checked').val();
			if(selectedTemplate.document_type == 'generated' || selectedTemplate.document_type == 'hybrid_document')
			obj.desc = CKEDITOR.instances['js-modify-assign-document-description'].getData();
			if(selectedTemplate.document_type == 'uploaded' || selectedTemplate.document_type == 'hybrid_document'){
				obj.file = file.name === undefined ? selectedTemplate.uploaded_document_s3_name : file;
				obj.fileOrigName = selectedTemplate.uploaded_document_original_name;
			}
			//
			var post = new FormData();
			//
			$.each(megaOBJ, function(index, val) { post.append(index, val); });
			$.each(obj, function(index, val) { post.append(index, val); });
			//
			$('.jsModifyModalLoader').fadeIn(300);
			$.ajax({
				url: "<?=base_url('hr_documents_management/assign_document');?>",
				type: 'POST',
				processData: false,
				contentType: false,
				data: post
			}).done( function(resp){
				//
				$('.jsModifyModalLoader').fadeOut(300);
				alertify.alert('SUCCESS!', 'Document assigned successfully '+( obj.sendEmail == 'yes' ? ' and an email notification is sent' : ''  )+'.', function(){
					//
					if(refreshPage == 1) window.location.reload();
					//
					resetView( undefined, obj.documentSid, 'assign' );
				});
			});
		}

		//
		function AssignOfferLetter(
			e
		){
			//
			e.preventDefault();
			//
			var obj = {};
			//
			obj.documentTitle = selectedTemplate.letter_name;
			obj.documentType =  selectedTemplate.letter_type;
			obj.managerList = $('#js-modify-assign-offer-letter-signers').val();
			obj.isSignature = $('#js-modify-assign-document-signature').val();
			obj.isDownload = $('#js-modify-assign-document-download').val();
			obj.isAcknowledged = $('#js-modify-assign-document-acknowledgment').val();
			obj.documentSid = selectedTemplate.sid;
			obj.visibleToPayroll = selectedTemplate.visible_to_payroll;
			obj.sendEmail = $('.js-modify-assign-document-send-email:checked').val();
			if(selectedTemplate.letter_type == 'generated' || selectedTemplate.letter_type == 'hybrid_document')
			obj.desc = CKEDITOR.instances['js-modify-assign-offer-letter-description'].getData();
			if(selectedTemplate.letter_type == 'uploaded' || selectedTemplate.letter_type == 'hybrid_document'){
				obj.file = file.name === undefined ? selectedTemplate.uploaded_document_s3_name : file;
				obj.fileOrigName = selectedTemplate.uploaded_document_original_name;
			}
			//
			var post = new FormData();
			//
			$.each(megaOBJ, function(index, val) { post.append(index, val); });
			$.each(obj, function(index, val) { post.append(index, val); });
			//
			$('.jsModifyModalLoader').fadeIn(300);
			$.ajax({
				url: "<?=base_url('hr_documents_management/assign_offer_letter_new');?>",
				type: 'POST',
				processData: false,
				contentType: false,
				data: post
			}).done( function(resp){
				//
				$('.jsModifyModalLoader').fadeOut(300);
				alertify.alert('SUCCESS!', 'Offer Letter / Pay Plan assigned successfully '+( obj.sendEmail == 'yes' ? ' and an email notification is sent' : ''  )+'.', function(){
					window.location.reload();
				});
			});
		}

		//
		function getSigners(sid){
			//
			sid = sid === undefined ? 'js-modify-assign-document-signers' : sid;
			//
			var rows = '';
			//
			rows += '<div class="form-group">';
			rows += '	<label>Authorized Management Signers</label>';
			rows += '	<select id="'+( sid )+'" multiple="true">';

			var 
				i = 0,
				il = allEmployees.length;
			//
			for(i; i < il; i++){
				rows += '<option value="'+( allEmployees[i]['sid'] )+'">'+( remakeEmployeeName( allEmployees[i] ) )+'</option>';
			}

			rows += '   </select>';
			rows += '</div>';
			//
			return rows;
		}

		//
		function getEmailContent(){
			var rows = '';
			//
			rows += '<div class="form-group">';
			rows += '	<hr />';
			rows += '	<label>Send an email notification?</label>';
			rows += '	<br />';
			rows += '	<label class="control control--radio">';
			rows += '	    <input type="radio" class="js-modify-assign-document-send-email" name="js-modify-assign-document-send-email" value="no" checked="true" /> No &nbsp;';
			rows += '	    <div class="control__indicator"></div>';
			rows += '	</label>';
			rows += '	<label class="control control--radio">';
			rows += '	    <input type="radio"  class="js-modify-assign-document-send-email" name="js-modify-assign-document-send-email" value="yes" /> Yes &nbsp;';
			rows += '	    <div class="control__indicator"></div>';
			rows += '	</label>';
			rows += '</div>';
			//
			return rows;
		}

		//
		function getUploadContent(){ 
			var rows = '';
			//
			// rows += '<div class="row">';
			// rows += '<div class="col-sm-12">';
			// rows += '<div class="form-group js-for-uploaded">';
			// rows += '	<label>Browse Document <span class="staric">*</span></label>';
			// rows += '    <div class="upload-file" style="height: 46px; border: 1px solid #ccc;">';
			// rows += '        <input type="file" name="document" id="js-modify-assign-document-input" required />';
			// rows += '        <div class="profile-picture">';
			// rows += '            <a href="javascript:;" class="action-btn js-modify-assign-document-file">';
			// rows += '                <i class="fa fa-lightbulb-o fa-2x"></i>';
			// rows += '                <span class="btn-tooltip">View Current Document</span>';
			// rows += '            </a>';
			// rows += '        </div>';
			// rows += '        <p class="cs-error" style="padding: 5px; padding-left: 50px;">Allowed formats (doc, docx, xls, xlsx, pdf)</p>';
			// rows += '        <p class="name_document"></p>';
			// rows += '        <a href="javascript:;">Choose File</a>';
			// rows += '    </div>';
			// rows += '    <p class="cs-error js-error"></p>';
			// rows += '</div>';
			// rows += '</div>';
			// rows += '</div>';
			//
			rows += '<div class="row">';
			rows += '<div class="col-sm-12">';
			rows += '<div class="form-group js-for-uploaded">';
			rows += '	<label>Browse Document <span class="staric">*</span></label>';
			rows += '	<input style="display: none;" type="file" name="document" id="modify_assign_document">';
			rows += '</div>';
			rows += '</div>';
			rows += '</div>';
			//
			return rows; 
		}

		//
		function getGeneratedContent(sid){
			var rows = '';
			//
			rows += '<div class="row">';
			rows += '<div class="col-sm-12">';
			rows += '<div class="form-group">';
			rows += '	<br />';
			rows += '	<label>Description <span class="hr-required red">*</span></label>';
			rows += '	<textarea id="'+( sid )+'"></textarea>';
			rows += '</div>';
			rows += '</div>';
			rows += '</div>';
			//
			return rows; 
		}

		//
		function GetFileForUpload(e){
			file = e.target.files[0];
			//
			if($.inArray(file.type, allowedTypes) === -1){
				file = {};
				$(this).parent().parent().find('p.js-error').text('Invalid file format. Please upload the right format file.');
			}
		}

		//
		function getTags(){
			var rows = '';
			//
			rows += '<div class="form-group"><br />';
			rows += '<div class="offer-letter-help-widget full-width form-group">';
			rows += '    <div class="how-it-works-insturction">';
			rows += '        <strong>How it\'s Works :</strong>';
			rows += '        <p class="how-works-attr">1. Generate new Electronic Document</p>';
			rows += '        <p class="how-works-attr">2. Enable Document Acknowledgment</p>';
			rows += '        <p class="how-works-attr">3. Enable Electronic Signature</p>';
			rows += '        <p class="how-works-attr">4. Insert multiple tags where applicable</p>';
			rows += '        <p class="how-works-attr">5. Save the Document</p>';
			rows += '    </div>';
			rows += '    <div class="tags-arae">';
			rows += '        <div class="col-md-12">';
			rows += '            <h5><strong>Company Information Tags:</strong></h5>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{company_name}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{company_address}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{company_phone}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '            <input type="text" class="form-control tag" readonly="" value="{{career_site_url}}">';
			rows += '                </div>';
			rows += '        </div>';
			rows += '    </div>';
			rows += '    <div class="tags-arae">';
			rows += '        <div class="col-md-12">';
			rows += '            <h5><strong>Employee / Applicant Tags :</strong></h5>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{first_name}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{last_name}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{email}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{job_title}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '    </div>';
			rows += '    <div class="tags-arae">';
			rows += '        <div class="col-md-12">';
			rows += '            <h5><strong>Signature tags:</strong></h5>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{signature}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{inital}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{sign_date}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{short_text}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{text}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{text_area}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '    </div>';
			rows += '</div>';
			rows += '</div>';

			//
			return rows; 
		}


		// Get document
		function getDocument(
			sid
		){
			var 
				i = 0,
				il = allDocuments.length;
			//
			for( i; i < il; i++ ){
				if(allDocuments[i]['sid'] == sid) return allDocuments[i];
			}
			return {};
		}


		// Get document
		function getAssignedDocument(
			documentSid,
			type
		){
			var 
				i = 0,
				il = Object.keys(tabDocs[type]).length;
			//
			for( i; i < il; i++ ){
				if(tabDocs[type][i]['document_sid'] == documentSid) return tabDocs[type][i];
			}
			return {};
		}




		// Offer letter create steps
		// Step 1 = Generate View
		// Step 2 = Validate & Submit
		//
		function StartOfferLetterProcess(e){
			//
			e.preventDefault();
			//
			var o = getOfferLetterBody('add');
			//
			file = {};
			selectedTemplate = {};
			modelFor = 'offer_letter';
			//
			Modal(
				o.title,
				o.body,
				'<button class="btn btn-success js-save-offer-letter" data-value="save_assign">Save / Assign Offer Letter</button>',
				'js-popup',
				['js-template-body', 'js-template-guidence'],
				['#js-template-signers', '#js-templates-add']
			);
			//
			$('.js-template-type[value="uploaded"]').click();
			$('.js-template-send-email[value="no"]').click();
			//
			iLoader( 'hide', '.js-popup' );
		}

		// Offer letter edit steps
		// Step 1 = Generate View
		// Step 2 = Validate & Submit
		//
		function StartOfferLetterEditProcess(e){
			//
			e.preventDefault();
			//
			var d = getOfferLetter($(this).data('id'));
			var o = getOfferLetterBody('edit');
			//
			currentOfferLetter = d;
			modelFor = 'offer_letter';
			//
			Modal(
				o.title,
				o.body,
				'<button class="btn btn-success" id="js-save-offer-letter-edit">Update Offer Letter ss</button>',
				'js-popup-edit',
				['js-template-body-edit', 'js-template-guidence-edit'],
				['#js-template-signers-edit'],
				function(){
					if(d.signers != null){
						$('#js-template-signers-edit').select2('val', d.signers.split(','));
					}
				}
			);
			//
			$('.js-template-type-edit[value="'+( d.letter_type )+'"]').click();
			//
			$('#js-template-name-edit').val( d.letter_name );
			$('#js-template-body-edit').val( d.letter_body );
			$('#js-template-guidence-edit').val( d.guidence );
			$('#js-template-acknowledgment-edit option[value="'+( d.acknowledgment_required )+'"]').prop( 'selected', true );
			$('#js-template-download-edit option[value="'+( d.download_required )+'"]').prop( 'selected', true );
			$('#js-template-signature-edit option[value="'+( d.signature_required )+'"]').prop( 'selected', true );
			$('#js-template-sort-order-edit').val( d.sort_order );
			//
			iLoader( 'hide', '.js-popup-edit' );
		}

		// Generate body for add offer letter
		function getOfferLetterBody(
			type
		){
			//
			var obj = {
				title: '',
				body: ''
			};
			//
			obj.title += '<i class="fa fa-file-text-o"></i>&nbsp;&nbsp;';
			obj.title += 'Upload/Generate an Offer Letter / Pay Plan';
			//
			if(type == 'add')
			obj.body += `<?php $this->load->view('hr_documents_management/templates/offer_letter', ['offer_letters' => $offerLetters]); ?>`;
			else if(type == 'edit')
			obj.body += `<?php $this->load->view('hr_documents_management/templates/offer_letter_edit', ['offer_letters' => $offerLetters]); ?>`;

			return obj;
		}

		// Validate uploaded file
		function ValidateUploadedFile(
			e
		){
			//
			$(this).parent().parent().find('p.js-error').text('');
			//
			file = {};
			//
			file = e.target.files[0];
			//
			if($.inArray(file.type, allowedTypes) === -1){
				file = {};
				$(this).parent().parent().find('p.js-error').text('Invalid file format. Please upload the right format file.');
			}
		}

		// Validate and save offer letter
		function ValidateAndSaveOfferLetter(e){
			//
			e.preventDefault();
			//
			$('.js-error').text('');
			//
			var upload_file = $('#upload_document').mFileUploader('get');

			//
			var o = {
				name : $('#js-template-name').val().trim(),
				body : CKEDITOR.instances['js-template-body'].getData().trim(),
				guidence : CKEDITOR.instances['js-template-guidence'].getData().trim(),
				// file : file,
				file : upload_file,
				type : $('.js-template-type:checked').val().trim(),
				signers : $('#js-template-signers').val(),
				signature : $('#js-template-signature').val(),
				download : $('#js-template-download').val(),
				acknowledgment : $('#js-template-acknowledgment').val(),
				sortOrder : $('#js-template-sort-order').val().trim(),
				sendEmail : $('.js-template-send-email:checked').val(),
				isRequired : $('.js-template-required:checked').val(),
				isSignatureRequired : $('.js-template-signature-required:checked').val(),
				assign : $(this).data('value'),
				fromTemplate: false
			};
			//
			if(o.type == 'template') {
				o.type = selectedTemplate.letter_type;
				o.fromTemplate = 1;
			}
			//
			var proceed = true;
			// Validate 
			if( o.name == '' ){
				$('#js-template-name').parent().find('p.js-error').text('This field is required.');
				proceed = false;
			}
			// For generated documents
			if(o.type == 'generated' || o.type == 'hybrid_document'){
				if( o.body == '' ){
					$('#js-template-body').parent().find('p.js-error').text('This field is required.');
					proceed = false;
				}
			}
			// For uploaded documents
			if(o.type == 'uploaded' || o.type == 'hybrid_document'){
				if( o.file.name === undefined ){
					$('#js-offer-letter-file-add').parent().parent().find('p.js-error').text('This field is required.');
					proceed = false;
				}
			}

			if(o.type != 'generated') {
				if ($.isEmptyObject(upload_file)) {
					alertify.alert('ERROR!', 'Please select a file to upload.', () => {});
            		proceed = false;
				} else if (upload_file.hasError === true) {
					alertify.alert('ERROR!', 'Please select a valid file format.', () => {});
            		proceed = false;
				}
			}
			//
			if(o.file.s3Name !== undefined) o.file = JSON.stringify(o.file);
			//
			if(!proceed) return;
			//
			iLoader( 'show', '.js-popup' );
			//
			// console.log(megaOBJ);
			// console.log(o);
			var post = new FormData();
			$.each(megaOBJ, function(index, val) { post.append(index, val); });
			$.each(o, function(index, val) { post.append(index, val); });
			//
			$.ajax({
				url: '<?=base_url('hr_documents_management/offer_letter_add');?>',
				type: 'POST',
				processData: false,
				contentType: false,
				data: post,
			})
			.done(function(resp) {
				iLoader( 'hide', '.js-popup' );
				//
				if(resp.Status === false){
					alertify.alert('ERROR!', resp.Response);
					return;
				}
				//
				alertify.alert('SUCCESS!', resp.Response, function(){
					window.location.reload();
				});
			});
		}

		// Validate and update offer letter
		function ValidateAndUpdateOfferLetter(e){
			//
			e.preventDefault();
			//
			$('.js-error').text('');
			//
			var o = {
				sid : currentOfferLetter.sid,
				name : $('#js-template-name-edit').val().trim(),
				body : CKEDITOR.instances['js-template-body-edit'].getData().trim(),
				guidence : CKEDITOR.instances['js-template-guidence-edit'].getData().trim(),
				file : file,
				type : $('.js-template-type-edit:checked').val().trim(),
				signature : $('#js-template-signature-edit').val(),
				download : $('#js-template-download-edit').val(),
				acknowledgment : $('#js-template-acknowledgment-edit').val(),
				isRequired : $('.js-template-required-edit:checked').val(),
				isSignatureRequired : $('.js-template-signature-required-edit:checked').val(),
				sortOrder : $('#js-template-sort-order-edit').val().trim()
			};
			//
			var proceed = true;
			// Validate 
			if( o.name == '' ){
				$('#js-template-name-edit').parent().find('p.js-error').text('This field is required.');
				proceed = false;
			}
			// For generated documents
			if(o.type == 'generated' || o.type == 'hybrid_document'){
				if( o.body == '' ){
					$('#js-template-body-edit').parent().find('p.js-error').text('This field is required.');
					proceed = false;
				}
			}
			//
			if(!proceed) return;
			iLoader( 'show', '.js-popup-edit' );
			//
			var post = new FormData();
			$.each(megaOBJ, function(index, val) { post.append(index, val); });
			$.each(o, function(index, val) { post.append(index, val); });
			//
			$.ajax({
				url: '<?=base_url('hr_documents_management/offer_letter_edit');?>',
				type: 'POST',
				processData: false,
				contentType: false,
				data: post,
			})
			.done(function(resp) {
				iLoader( 'hide', '.js-popup-edit' );
				//
				if(resp.Status === false){
					alertify.alert('ERROR!', resp.Response);
					return;
				}
				//
				alertify.alert('SUCCESS!', resp.Response, function(){
					window.location.reload();
				});
			});
		}

		//
		function ShowUploadedFilePreview(e){
			e.preventDefault();
			//
			var s3Name = selectedTemplate !== undefined ? selectedTemplate.uploaded_document_s3_name : currentOfferLetter.uploaded_document_s3_name;
			var origName = selectedTemplate !== undefined ? selectedTemplate.uploaded_document_original_name : currentOfferLetter.uploaded_document_original_name;
			//
			if(selectedTemplate.document_s3_name !== undefined){
				s3Name = selectedTemplate.document_s3_name;
				origName = selectedTemplate.document_original_name;
			}
			var f = getUploadedFileAPIUrl(
				s3Name
			);
			//
			Modal(
				origName,
				f.getHTML(),
				f.getButtonHTML(),
				'js-offer-letter-iframe-popup'
			);
			//
			loadIframe(
				f.URL,
				f.Target,
				true
			);
			//
			$('.js-offer-letter-iframe-popup-loader').hide(0);
		}

		//
		function UseTemplate(e){
			var l = getOfferLetter( $(this).val() );
			if(Object.keys(l).length !== 0) console.log(l);
			//
			selectedTemplate = l;
			$('#remove_image').hide(0);
			$('#remove_image').parent().find('.cs-error').css('padding-left', '5px');
			//
			if(l.letter_type == 'uploaded'){
				$('.js-for-uploaded').show(0);
				$('.js-for-generated').hide(0);
				$('#remove_image').show(0);
				$('#remove_image').parent().find('.cs-error').css('padding-left', '50px');
			} else if(l.letter_type == 'generated'){
				$('.js-for-generated').show(0);
				$('.js-for-uploaded').hide(0);
			} else if(l.letter_type == 'hybrid_document'){
				$('.js-for-generated').show(0);
				$('.js-for-uploaded').show(0);
				$('#remove_image').show(0);
				$('#remove_image').parent().find('.cs-error').css('padding-left', '50px');
			}
			//
			file = {
				name : l.uploaded_document_original_name,
				s3Name: l.uploaded_document_s3_name
			};
			//
			$('#js-template-name').val( l.letter_name );
			CKEDITOR.instances['js-template-body'].setData( l.letter_body );
			CKEDITOR.instances['js-template-body'].setData( l.guidence );
			$('#js-template-acknowledgment option[value="'+( l.acknowledgment_required )+'"]').prop( 'selected', true );
			$('#js-template-download option[value="'+( l.download_required )+'"]').prop( 'selected', true );
			$('#js-template-signature option[value="'+( l.signature_required )+'"]').prop( 'selected', true );
			$('#js-template-sort-order').val( l.sort_order );
			//
			if( l.signers !== null ) $('#js-template-signers').select2( 'val', l.signers.split(',') );
		}

		//
		function getUploadedFileAPIUrl(
			f,
			o
		){
			if(f == null || f == '') return {};
			// Get file extension
			var 
			r = {},
			full = "<?=AWS_S3_BUCKET_URL;?>" +  f,
			t = f.split('.');
			t = t[t.length - 1].toLowerCase().trim();
			//
			if($.inArray(t, ['csv', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx']) !== -1){
				r = { 
					URL: 'https://view.officeapps.live.com/op/embed.aspx?src='+( full )+'', 
					PrintURL: 'https://view.officeapps.live.com/op/embed.aspx?src='+( full )+'', 
					DownloadURL: "<?=base_url('hr_documents_management/download_upload_document');?>/" + f, 
					Extension: t,
					Target: '.js-preview-iframe',
					Type: 'iframe',
					getHTML: () => '<iframe src="'+( r.URL )+'" frameborder="0" style="width: 100%; height: 500px;" class="js-preview-iframe"></iframe>',
					getPrintHTML: () => '<a href="'+( r.PrintURL )+'" target="_blank" class="btn btn-success btn-sm">Print</a>', 
					getDownloadHTML: () => '<a href="'+( r.DownloadURL )+'" class="btn btn-success btn-sm">Download</a>', 
					getButtonHTML: () => r.getPrintHTML() +' &nbsp; '+  r.getDownloadHTML() 
				};
			} else if($.inArray(t, ['jpe','jpeg','png','gif','jpg','jpe','jpeg','png','gif']) !== -1){
				r = { 
					URL: full, 
					PrintURL: full, 
					DownloadURL: "<?=base_url('hr_documents_management/download_upload_document');?>/" + f, 
					Extension: t,
					Target: '.js-preview-iframe',
					Type: 'image',
					getHTML: () => '<img src="'+( r.URL )+'" style="max-width: 100%; display: block; margin: auto;" class="js-preview-iframe" />',
					getPrintHTML: () => '<a href="'+( r.PrintURL )+'" target="_blank" class="btn btn-success btn-sm">Print</a>', 
					getDownloadHTML: () => '<a href="'+( r.DownloadURL )+'" class="btn btn-success btn-sm">Download</a>',
					getButtonHTML: () => r.getPrintHTML() +' &nbsp; '+  r.getDownloadHTML() 
				}; 
			} else {
				r = {
					URL: 'https://docs.google.com/gview?url='+( full )+'&embedded=true', 
					PrintURL: 'https://docs.google.com/gview?url='+( full )+'&embedded=true', 
					DownloadURL: "<?=base_url('hr_documents_management/download_upload_document');?>/" + f, 
					Extension: t,
					Type: 'iframe',
					Target: '.js-preview-iframe',
					getHTML: () => '<iframe src="'+( r.URL )+'" frameborder="0" style="width: 100%; height: 500px;" class="js-preview-iframe"></iframe>',
					getPrintHTML: () => '<a href="'+( r.PrintURL )+'" target="_blank" class="btn btn-success btn-sm">Print</a>',
					getDownloadHTML: () => '<a href="'+( r.DownloadURL )+'" class="btn btn-success btn-sm">Download</a>', 
					getButtonHTML: () => r.getPrintHTML() +' &nbsp; '+  r.getDownloadHTML() 
				}
			}
			//
			return r;
		}

		// Set view
		function SetView(e){
			//
			var 
			value = $(this).val(),
			type = $(this).data('type');
			//
			if(value == 'uploaded'){
				$('.js-for-uploaded').show(0);
				$('.js-for-generated').hide(0);
				$('.js-template-row').hide(0);
			} else if( value == 'generated'){
				$('.js-for-generated').show(0);
				$('.js-for-uploaded').hide(0);
				$('.js-template-row').hide(0);
			} else if( value == 'template' ){
				$('.js-template-row').show(0);
			} else if( value == 'hybrid_document'){
				$('.js-for-uploaded').show(0);
				$('.js-for-generated').show(0);
				$('.js-template-row').hide(0);
				$('.js-guidence-box').hide(0);
			}
		}

		// Modal generator
		function Modal(
			title,
			contents,
			footerButtons,
			sid,
			cks,
			sels,
			cb
		){
			//
			sid = sid == undefined ? uuidv4() : sid;
			title = title == undefined ? '' : title;
			contents = contents == undefined ? '' : contents;
			footerButtons = footerButtons == undefined ? '' : footerButtons;
			cks = cks == undefined ? [] : cks;
			sels = sels == undefined ? [] : sels;
			//
			var rows = '';
			//
			rows += '<div class="modal fade js-page-partial" id="'+( sid )+'">';
			rows += '	<div class="modal-dialog modal-lg">';
			rows += '		<!-- loader --><div class="loader jsModifyModalLoader '+( sid )+'-loader"><i class="fa fa-spinner fa-spin"></i></div>';
			rows += '		<div class="modal-content">';
			rows += '			<div class="modal-header modal-header-bg">';
			rows += '				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			rows += '				<h4 class="modal-title">'+( title )+'</h4>';
			rows += '			</div>';
			rows += '			<div class="modal-body">';
			rows += 			contents;
			rows += '			</div>';
			rows += '			<div class="modal-footer">';
			rows += '				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
			rows += 			footerButtons;
			rows += '			</div>';
			rows += '		</div>';
			rows += '	</div>';
			rows += '</div>';
			//
			$('#'+sid).remove();
			//
			$('body').append(rows);
			//
			$('#'+sid).modal();
			//
			$('.jsModifyModalLoader').show();
			//
			var allowedTypes = ['jpg','jpeg','png','gif','pdf','doc','docx','rtf','ppt','xls','xlsx','csv'];
			//
			if (modelFor == 'offer_letter') {
				allowedTypes = ['jpg','jpeg','png','gif','pdf','doc','docx'];
			}
			//
			$('#upload_document').mFileUploader({
	            fileLimit: '2MB', // Default is '2MB', Use -1 for no limit (Optional)
	            allowedTypes: allowedTypes,  //(Optional)
	            text: 'Click / Drag to upload', // (Optional)
	            onSuccess: (file, event) => {}, // file will the uploaded file object and event will be the actual event  (Optional)
	            onError: (errorCode, event) => {}, // errorCode will either 'size' or 'type' and event will be the actual event  (Optional)
	            placeholderImage: '' // Default is empty ('') but can be set any image  (Optional)
	        });
			//
			if(cks.length !== 0) $.each(cks, function(index, val) { CKEDITOR.replace(val); });
			if(sels.length !== 0) $.each(sels, function(index, val) { $(val).select2({ closeOnSelect: $(val).prop('multiple') ? false : true }); });

			if(cb !== undefined) cb();
		}

		// Helping functions
		// Create unique ID
		function uuidv4() {
		  	return 'xxxxxxxx-xx'.replace(/[xy]/g, function(c) {
		    	var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
		    	return v.toString(16);
		  	});
		}

		//
		function getOfferLetter(
			sid
		){
			let 
			i = 0,
			il = offerLetters.length;
			//
			if(il == 0) return {};
			//
			for(i; i < il; i++){
				if(offerLetters[i]['sid'] == sid) return offerLetters[i];
			}
			return {};
		}

		// Copy text to clipboard
		$(document).on('click', '.has-copy', function(e){
			//
			var copyToClipboard = str => {
				const el = document.createElement('textarea');  // Create a <textarea> element
				el.value = str;                                 // Set its value to the string that you want copied
				el.setAttribute('readonly', '');                // Make it readonly to be tamper-proof
				el.style.position = 'absolute';                 
				el.style.left = '-9999px';                      // Move outside the screen to make it invisible
				document.body.appendChild(el);                  // Append the <textarea> element to the HTML document
				const selected =            
					document.getSelection().rangeCount > 0        // Check if there is any content selected previously
				    ? document.getSelection().getRangeAt(0)     // Store selection if found
				    : false;                                    // Mark as false to know no selection existed before
				el.select();                                    // Select the <textarea> content
				document.execCommand('copy');                   // Copy - only works as a result of a user action (e.g. click events)
				document.body.removeChild(el);                  // Remove the <textarea> element
				if (selected) {                                 // If a selection existed before copying
					document.getSelection().removeAllRanges();    // Unselect everything on the HTML document
				    document.getSelection().addRange(selected);   // Restore the original selection
				    //
				    $(this).css('color', '#81cb35').css('color', '#000000');
				}
			};
			//
			copyToClipboard(
				$(this).find('input').val()
			);
		});

		//
		function iLoader(
			sh,
			type
		){
			//
			type = type === undefined ? '.js-inner-loader-letter' : type+'-loader';
			//
			switch (sh) {
				case 'hide':
				case false:
				case 'false':
					$(type).fadeOut(300);
				break;
				default: $(type).fadeIn(300); break;
			}
		}	


		// 
		function getRequiredRow(){
			var rows = '';
			//
			rows += '<div class="form-group">';
			rows += '	<hr />';
			rows += '	<label>Is the document required?</label>';
			rows += '	<p class="help-text">If marked yes, then the applicant needs to complete this document to complete the onboarding process.</p>';
			rows += '	<label class="control control--radio">';
			rows += '	    <input type="radio" class="js-modify-assign-document-required" name="js-modify-assign-document-required" value="0" checked="true" /> No &nbsp;';
			rows += '	    <div class="control__indicator"></div>';
			rows += '	</label>';
			rows += '	<label class="control control--radio">';
			rows += '	    <input type="radio"  class="js-modify-assign-document-required" name="js-modify-assign-document-required" value="1" /> Yes &nbsp;';
			rows += '	    <div class="control__indicator"></div>';
			rows += '	</label>';
			rows += '</div>';
			//
			return rows;
		}
		
		// 
		function getSignatureRequiredRow(){
			var rows = '';
			//
			rows += '<div class="form-group hidden">';
			rows += '	<hr />';
			rows += '	<label>Is the signature required?</label>';
			rows += '	<p class="help-text">If marked yes, then the applicant needs to add e-sign this document to complete the onboarding process.</p>';
			rows += '	<label class="control control--radio">';
			rows += '	    <input type="radio" class="js-modify-assign-document-signature-required" name="js-modify-assign-document-signature-required" value="0" checked="true" /> No &nbsp;';
			rows += '	    <div class="control__indicator"></div>';
			rows += '	</label>';
			rows += '	<label class="control control--radio">';
			rows += '	    <input type="radio"  class="js-modify-assign-document-signature-required" name="js-modify-assign-document-signature-required" value="1" /> Yes &nbsp;';
			rows += '	    <div class="control__indicator"></div>';
			rows += '	</label>';
			rows += '</div>';
			//
			return rows;
		}

		// I9 manage
		$('.jsManageI9').click(function(event){
			//
			event.preventDefault();
			//
			var i9 = <?php echo !empty($i9_form) ? json_encode($i9_form) : ( !empty($i9_form_data) ? json_encode($i9_form_data) : json_encode([ 'is_required' => 0, 'is_signature_required' => 0])) ;?>;
			//
			Modal(
				'Manage I9 document',
				getSettingBody('i9', {
					isRequired: i9.is_required,
					isSignatureRequired: i9.is_signature_required
				}),
				'<button class="btn btn-success" id="js-update-i9-settings">Update</button>',
				'js-i9-edit',
				[],
				[],
				function(){
					//
					iLoader( 'hide', '.js-i9-edit' );
				}
			);
		});

		//
		$(document).on('click', '#js-update-i9-settings', function(event){
			//
			event.preventDefault();
			//
			var i9 = <?php echo !empty($i9_form) ? json_encode($i9_form) : ( !empty($i9_form_data) ? json_encode($i9_form_data) : json_encode([ 'is_required' => 0, 'is_signature_required' => 0])) ; ?>;
			//
			var o = {};
			o.id = i9.sid;
			o.isRequired = $('.js-i9-required:checked').val();
			o.isSignatureRequired = $('.js-i9-signature-required:checked').val();
			o.formType = "i9";
			//
			iLoader( 'show', '.js-i9-edit' );
			//
			$.post("<?php echo base_url('hr_documents_management/update_form_settings');?>", o)
			.done(function(resp){
				//
				iLoader( 'hide', '.js-i9-edit' );
				//
				if(resp == 'success'){
					alertify.alert('SUCCESS!', 'You have successfully updated the I9 form settings.', function(){
						window.location.reload();
					});
				}
			});
		});


		// W9 manage
		$('.jsManageW9').click(function(event){
			//
			event.preventDefault();
			//
			var w9 = <?php echo !empty($w9_form) ? json_encode($w9_form) :  ( !empty($w9_form) ? json_encode($w9_form) : json_encode([ 'is_required' => 0, 'is_signature_required' => 0])) ; ?>;
			//
			Modal(
				'Manage W9 document',
				getSettingBody('w9', {
					isRequired: w9.is_required,
					isSignatureRequired: w9.is_signature_required
				}),
				'<button class="btn btn-success" id="js-update-w9-settings">Update</button>',
				'js-w9-edit',
				[],
				[],
				function(){
					//
					iLoader( 'hide', '.js-w9-edit' );
				}
			);
		});

		//
		$(document).on('click', '#js-update-w9-settings', function(event){
			//
			event.preventDefault();
			//
			var w9 = <?php echo !empty($w9_form) ? json_encode($w9_form) : ( !empty($w9_form) ? json_encode($w9_form) : json_encode([ 'is_required' => 0, 'is_signature_required' => 0])) ; ?>;
			//
			var o = {};
			o.id = w9.sid;
			o.isRequired = $('.js-w9-required:checked').val();
			o.isSignatureRequired = $('.js-w9-signature-required:checked').val();
			o.formType = "w9";
			//
			iLoader( 'show', '.js-w9-edit' );
			//
			$.post("<?php echo base_url('hr_documents_management/update_form_settings');?>", o)
			.done(function(resp){
				//
				iLoader( 'hide', '.js-w9-edit' );
				//
				if(resp == 'success'){
					alertify.alert('SUCCESS!', 'You have successfully updated the w9 form settings.', function(){
						window.location.reload();
					});
				}
			});
		});


		// W4 manage
		$('.jsManageW4').click(function(event){
			//
			event.preventDefault();
			//
			var w4 = <?php echo !empty($w4_form) ? json_encode($w4_form) : ( !empty($w4_form) ? json_encode($w4_form) : json_encode([ 'is_required' => 0, 'is_signature_required' => 0])) ; ?>;
			//
			Modal(
				'Manage W4 document',
				getSettingBody('w4', {
					isRequired: w4.is_required,
					isSignatureRequired: w4.is_signature_required
				}),
				'<button class="btn btn-success" id="js-update-w4-settings">Update</button>',
				'js-w4-edit',
				[],
				[],
				function(){
					//
					iLoader( 'hide', '.js-w4-edit' );
				}
			);
		});

		//
		$(document).on('click', '#js-update-w4-settings', function(event){
			//
			event.preventDefault();
			//
			var w4 = <?php echo !empty($w4_form) ? json_encode($w4_form) : ( !empty($w4_form) ? json_encode($w4_form) : json_encode([ 'is_required' => 0, 'is_signature_required' => 0])) ; ?>;
			//
			var o = {};
			o.id = w4.sid;
			o.isRequired = $('.js-w4-required:checked').val();
			o.isSignatureRequired = $('.js-w4-signature-required:checked').val();
			o.formType = "w4";
			//
			iLoader( 'show', '.js-w4-edit' );
			//
			$.post("<?php echo base_url('hr_documents_management/update_form_settings');?>", o)
			.done(function(resp){
				//
				iLoader( 'hide', '.js-w4-edit' );
				//
				if(resp == 'success'){
					alertify.alert('SUCCESS!', 'You have successfully updated the w4 form settings.', function(){
						window.location.reload();
					});
				}
			});
		});

		//
		function getSettingBody(key, o){
			//
			html = '';
			html += '<div class="row">';
			html += '	<div class="col-sm-12">';
			html += '		<div class="form-group">';
			html += '			<label>Is this document required?</label>';
			html += '			<p class="help-text">If marked yes, then the applicant needs to complete this document to complete the onboarding process.</p>';
			html += '			<label class="control control--radio">';
			html += '				<input type="radio" class="js-'+(key)+'-required" name="js-'+(key)+'-required" '+( o.isRequired == 0 ? 'checked' : '' )+' value="0" /> No &nbsp;';
			html += '				<div class="control__indicator"></div>';
			html += '			</label>';
			html += '			<label class="control control--radio">';
			html += '				<input type="radio"  class="js-'+(key)+'-required" name="js-'+(key)+'-required" '+( o.isRequired == 1 ? 'checked' : '' )+' value="1" /> Yes &nbsp;';
			html += '				<div class="control__indicator"></div>';
			html += '			</label>';
			html += '		</div>';
			html += '		<div class="form-group hidden">';
			html += '			<label>Is signature required?</label>';
			html += '			<p class="help-text"><p class="help-text">If marked yes, then the applicant needs to add e-sign this document to complete the onboarding process.</p>';
			html += '			<label class="control control--radio">';
			html += '				<input type="radio" class="js-'+(key)+'-signature-required" name="js-'+(key)+'-signature-required" '+( o.isSignatureRequired == 0 ? 'checked' : '' )+' value="0" /> No &nbsp;';
			html += '				<div class="control__indicator"></div>';
			html += '			</label>';
			html += '			<label class="control control--radio">';
			html += '				<input type="radio"  class="js-'+(key)+'-signature-required" name="js-'+(key)+'-signature-required" '+( o.isSignatureRequired == 1 ? 'checked' : '' )+' value="1" /> Yes &nbsp;';
			html += '				<div class="control__indicator"></div>';
			html += '			</label>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';
			//
			return html;
		}
	});
</script>

<style>
	#modify-assign-document-modal .select2-container--default .select2-selection--multiple .select2-selection__rendered{ height: auto !important; }
</style>