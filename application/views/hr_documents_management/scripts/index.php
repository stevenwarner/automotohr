<?php
//
if (!isset($AllNotCompletedOfferLetters)) $AllNotCompletedOfferLetters = [];
if (!isset($AllCompletedOfferLetters)) $AllCompletedOfferLetters = [];
if (!isset($AllNotCompletedDocuments)) $AllNotCompletedDocuments = [];
if (!isset($AllCompletedDocuments)) $AllCompletedDocuments = [];
if (!isset($AllNoActionRequiredDocuments)) $AllNoActionRequiredDocuments = [];
if (!isset($all_documents)) $all_documents = [];
//
if (isset($GLOBALS['uofl'])) $AllNotCompletedOfferLetters = $GLOBALS['uofl'];
if (isset($GLOBALS['uofl'])) $AllCompletedOfferLetters = $GLOBALS['uofl'];
// if(isset($GLOBALS['ad'])) $AllCompletedDocuments = $GLOBALS['ad'];
//
if (isset($all_documents)) $all_documents = array_values($all_documents);
// if(isset($AllNotCompletedDocuments)) $AllNotCompletedDocuments = array_values($AllNotCompletedDocuments);
// if(isset($AllCompletedDocuments)) $AllCompletedDocuments = array_values($AllCompletedDocuments);
// if(isset($AllNoActionRequiredDocuments)) $AllNoActionRequiredDocuments = array_values($AllNoActionRequiredDocuments);
if (isset($AllNotCompletedOfferLetters)) $AllNotCompletedOfferLetters = array_values($AllNotCompletedOfferLetters);
if (isset($AllCompletedOfferLetters)) $AllCompletedOfferLetters = array_values($AllCompletedOfferLetters);

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
	#photo_gallery_modal,
	#popupmodal {
		z-index: 9999 !important;
	}

	.cs-required,
	.cs-error {
		font-weight: 900;
		color: #cc1100;
		font-size: 18px;
	}

	.cs-error {
		font-size: 14px;
	}

	/*Loader*/
	.loader {
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		width: 100%;
		background: rgba(255, 255, 255, .8);
		z-index: 9999 !important;
	}

	.loader i {
		text-align: center;
		top: 50%;
		left: 50%;
		font-size: 40px;
		position: relative;
	}

	/**/
	.js-page-partial .select2-container--default .select2-selection--multiple .select2-selection__rendered {
		height: auto !important;
	}

	.js-page-partial .select2-container--default .select2-selection--single {
		background-color: #fff !important;
		border: 1px solid #aaa !important;
	}
</style>
<?php $this->load->view("hr_documents_management/scripts/approvers"); ?>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/approver.js"></script>
<script src="<?= base_url('assets/approverDocument/index.js'); ?>"></script>
<script>
	//
	function remakeEmployeeName(o, i) {
		//
		var r = '';
		//
		if (i == undefined) r += o.first_name + ' ' + o.last_name;
		//
		if (o.job_title != '' && o.job_title != null) r += ' (' + (o.job_title) + ')';
		//
		r += ' [';
		//
		if (typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
		//
		if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
		else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
		else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
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

	let do_fillable = false;

	function func_show_instructions_modal() {
		var myRequest;
		var myData = {
			'perform_action': 'get_instructions'
		};
		var myUrl = '<?php echo base_url("photo_gallery/ajax_responder"); ?>';



		myRequest = $.ajax({
			url: myUrl,
			type: 'POST',
			data: myData,
			dataType: 'json'
		});

		myRequest.done(function(response) {

			$('#popupmodallabel').html(response.title);
			$('#popupmodalbody').html(response.view);

			$('#popupmodal .modal-dialog').css('width', '75%');
			$('#popupmodal').modal('toggle');
		});
	}
	//
	$(function() {
		//
		var megaOBJ = {
				'CompanySid': <?= $company_sid; ?>,
				'CompanyName': "<?= $company_name; ?>",
				'EmployerSid': <?= $employer_sid; ?>,
				'EmployeeSid': <?= $EmployeeSid; ?>,
				'Type': '<?= $Type; ?>',
				'Action': ''
			},
			file = {},
			modelFor = 'document';
		refreshPage = "<?= isset($doRefresh) ? $doRefresh : 1; ?>",
			currentOfferLetter = {},
			selectedTemplate = {},
			atarget = null,
			activeModalId = null,
			offerLetters = <?= json_encode($offerLetters); ?>,
			allDocuments = <?= json_encode($all_documents); ?>,
			allEmployees = <?= json_encode($managers_list); ?>,
			jsBaseURL = '<?= base_url(); ?>',
			tabDocs = {
				notCompletedDocuments: <?= json_encode($AllNotCompletedDocuments); ?>,
				completedDocuments: <?= json_encode($AllCompletedDocuments); ?>,
				noActionDocuments: <?= json_encode($AllNoActionRequiredDocuments); ?>,
				notCompletedOfferLetters: <?= json_encode($AllNotCompletedOfferLetters); ?>,
				completedOfferLetters: <?= json_encode($AllCompletedOfferLetters); ?>,
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
		$(document).on('select2:select', '.js-templates', UseTemplate);

		// Modify & Assign / Revoke triggers
		$(document).on('click', '.js-modify-assign-document-btn', StartModifyAndAssignProcess);
		$(document).on('change', '#js-modify-assign-document-input', GetFileForUpload);
		$(document).on('click', '.js-modify-assign-document-file', ShowUploadedFilePreview);
		$(document).on('click', '.js-modify-revoke-document-btn', RevokeDocument);
		$(document).on('click', '.js-modify-assign-submit-btn', AssignDocument);
		$(document).on('click', '.js-modify-assign-offer-letter-btn', StartModifyAndAssignOfferLetterProcess);
		$(document).on('click', '.js-modify-assign-offer-letter-submit-btn', AssignOfferLetter);

		// Modify assigned document
		// for assigned document
		$('.js-modify-assigned-document-btn').click(StartModifyAssignedDocumentProcess);
		$(document).on('click', '.js-modify-assigned-submit-btn', AssignedDocument);


		// Functions

		// Revoke document
		function RevokeDocument(
			e
		) {
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
						"<?= base_url('hr_documents_management/revoke_document'); ?>", {
							sid: sid,
							employeeSid: megaOBJ.EmployeeSid
						},
						function(resp) {
							alertify.alert('SUCCESS!', 'Document revoked successfully.', function() {
								//
								if (refreshPage == 1) window.location.reload();
								//
								resetView(target, sid, 'revoke');
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
		) {
			switch (action) {
				case 'revoke':
					target.html('<button class="btn btn-warning btn-block js-modify-assign-document-btn" data-id="' + (documentSid) + '">Modify & Reassign</button>');
					break;
				case 'assign':
					$('#' + (activeModalId) + '').modal('hide');
					atarget.html('<button class="btn btn-danger btn-block js-modify-revoke-document-btn" data-id="' + (documentSid) + '">Revoke</button>');
					break;
			}
		}

		// Modify and Assign
		async function StartModifyAndAssignProcess(
			e
		) {
			//
			e.preventDefault();
			//
			var
				d = getDocument($(this).data('id')),
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

			do_fillable = false;

			//
			if (d.fillable_documents_slug) {
				do_descpt = false;
			}

			//
			rows += '<h4 id="gen_document_label">Are you sure you want to assign this document: [<b>' + (d.document_title) + '</b>]</h4>';
			rows += '<b> Please review this document and make any necessary modifications.</b>';
			rows += '<hr />';
			//
			// Check if the document type
			//	console.log(getUploadContent());

			if (d.fillable_documents_slug) {
				do_fillable = true;
				let rowData = await getFillableDocumentContent(d.fillable_documents_slug);
				rows += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Description</label>'
				rows += rowData.key;
			}


			if (do_upload) rows += getUploadContent();

			if (do_descpt) rows += getGeneratedContent('js-modify-assign-document-description');


			<?php if (ASSIGNEDOCIMPL) { ?>

				let signerFlag = false;
				if (do_descpt || do_fillable) {
					signerFlag = true;

				}

				rows += getConsentTypes(signerFlag);
			<?php } ?>
			if (do_descpt) rows += getSigners();

			if (d.fillable_documents_slug == 'written-employee-counseling-report-form' || d.fillable_documents_slug == 'notice-of-separation' ) {
				rows += getSigners();
			}

			//
			if (d.fillable_documents_slug == 'employee-performance-evaluation') {
				rows += getSigners();
			}

			rows += getVisibilty(do_descpt);
			//
			rows += `<?php echo $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckboxIdx" => "jsHasApprovalFlowAND", "containerIdx" => "jsApproverFlowContainerAND", "addEmployeeIdx" => "jsAddDocumentApproversAND", "intEmployeeBoxIdx" => "jsEmployeesadditionalBoxAND", "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBoxAND", "approverNoteIdx" => "jsApproversNoteAND", 'mainId' => 'testApproversAND'], true); ?>`;
			//
			rows += getEmailContent();
			rows += getRequiredRow();
			rows += getSignatureRequiredRow();
			rows += getSettings();
			if (do_descpt) rows += getTags();
			// if(do_descpt) {
			//
			if ($('#jsRoles').data('select2')) {
				$('#jsRoles').data('select2').destroy()
				$('#jsRoles').remove()
			}
			//
			if ($('#jsDepartments').data('select2')) {
				$('#jsDepartments').data('select2').destroy()
				$('#jsDepartments').remove()
			}
			//
			if ($('#jsTeams').data('select2')) {
				$('#jsTeams').data('select2').destroy()
				$('#jsTeams').remove()
			}
			//
			if ($('#jsEmployees').data('select2')) {
				$('#jsEmployees').data('select2').destroy()
				$('#jsEmployees').remove()
			}
			// }
			//

			let signerDivID = '#js-modify-assign-document-signers';

			if (d.fillable_documents_slug == 'written-employee-counseling-report-form' || d.fillable_documents_slug == 'notice-of-separation') {
				signerDivID = '#js-modify-assign-document-signers';
			}
			do_descpt ? signerDivID = '#js-modify-assign-document-signers' : '';


			Modal(
				'Modify & Assign This Document',
				rows,
				'<button class="btn btn-success js-modify-assign-submit-btn">Assign This Document</button>',
				'modify-assign-document-modal',
				do_descpt ? ['js-modify-assign-document-description'] : [],
				[signerDivID],
				function() {
					//
					do_descpt ? CKEDITOR.instances['js-modify-assign-document-description'].setData(d.document_description) : '';
					$('#js-modify-assign-document-signature option[value="' + (d.signature_required) + '"]').prop('selected', true);
					$('#js-modify-assign-document-download option[value="' + (d.download_required) + '"]').prop('selected', true);
					$('#js-modify-assign-document-acknowledgment option[value="' + (d.acknowledgment_required) + '"]').prop('selected', true);
					//

					if (d.signers != null && d.signers != '') {
						do_descpt ? $('#js-modify-assign-document-signers').select2('val', d.signers.split(',')) : '';
					} else if (d.managers_list != null && d.managers_list != '') {
						do_descpt ? $('#js-modify-assign-document-signers').select2('val', d.managers_list.split(',')) : '';
					}

					//
					if (d.fillable_documents_slug == 'written-employee-counseling-report-form' || d.fillable_documents_slug == 'notice-of-separation') {
						if (d.signers != null && d.signers != '') {} else if (d.managers_list != null && d.managers_list != '') {}
					}


					//
					if (d.visible_to_payroll) {
						$('#jsVisibleToPayroll').prop('checked', true);
					}
					//
					// if(do_descpt) {
					$('#jsRoles').select2({
						closeOnSelect: false
					});
					$('#jsDepartments').select2({
						closeOnSelect: false
					});
					$('#jsTeams').select2({
						closeOnSelect: false
					});
					$('#jsEmployees').select2({
						closeOnSelect: false
					});
					//
					if (d.is_available_for_na) {
						$('#jsRoles').select2('val', d.is_available_for_na.split(','));
					}
					//
					if (d.allowed_departments) {
						$('#jsDepartments').select2('val', d.allowed_departments.split(','));
					}
					//
					if (d.allowed_teams) {
						$('#jsTeams').select2('val', d.allowed_teams.split(','));
					}
					//
					if (d.allowed_employees) {
						$('#jsEmployees').select2('val', d.allowed_employees.split(','));
					}
					// }

					if (d.is_available_for_na) {
						$('#jsRoles').select2('val', d.is_available_for_na.split(','));
					}
					//
					if (d.allowed_departments) {
						$('#jsDepartments').select2('val', d.allowed_departments.split(','));
					}
					//
					if (d.allowed_teams) {
						$('#jsTeams').select2('val', d.allowed_teams.split(','));
					}
					//
					if (d.allowed_employees) {
						$('#jsEmployees').select2('val', d.allowed_employees.split(','));
					}
					//
					$('#modify-assign-document-modal [name="setting_is_confidential"]').prop('checked', d.is_confidential == '1' ? true : false);
					//

					$('#modify-assign-document-modal #confidentialSelectedEmployees').select2({
						closeOnSelect: false
					});
					//
					if (d.confidential_employees) {
						$('#modify-assign-document-modal #confidentialSelectedEmployees').select2('val', d.confidential_employees.split(','));
					}


					$('#modify-assign-document-modal [name="js-modify-assign-document-required"][value=1]').prop('checked', d.is_required == '1' ? true : false);
					$('#modify-assign-document-modal [name="js-modify-assign-document-required"][value=0]').prop('checked', d.is_required == '0' ? true : false);


					// Approver  Flow
					var approverPrefill = {};
					var approverSection = approverSection = {
						appCheckboxIdx: '.jsHasApprovalFlowAND',
						containerIdx: '.jsApproverFlowContainerAND',
						addEmployeeIdx: '.jsAddDocumentApproversAND',
						intEmployeeBoxIdx: '.jsEmployeesadditionalBoxAND',
						extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBoxAND',
						approverNoteIdx: '.jsApproversNoteAND',
						employeesList: <?= json_encode($employeesList); ?>,
						documentId: 0
					};
					//
					if (d.has_approval_flow && d.has_approval_flow == 1) {
						approverPrefill.isChecked = true;
						approverPrefill.approverNote = d.document_approval_note;
						approverPrefill.approversList = d.document_approval_employees.split(',');
						//
						approverSection.prefill = approverPrefill;
					}
					//
					$("#jsModifyAndAssignNewDocument").documentApprovalFlow(approverSection);
					//
					$('.jsModifyModalLoader').fadeOut(300);
					//
					$('#modify_assign_document').mFileUploader({
						fileLimit: -1, // Default is '2MB', Use -1 for no limit (Optional)
						allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'], //(Optional)
						placeholderImage: d.uploaded_document_s3_name // Default is empty ('') but can be set any image  (Optional)

					});
				}
			);
		}

		//
		function AssignDocument(
			e
		) {
			//
			e.preventDefault();
			//
			let file = $('#modify_assign_document').mFileUploader('get');
			//
			var obj = {};
			//


			obj.documentTitle = selectedTemplate.document_title;
			obj.documentType = selectedTemplate.document_type;
			obj.fillable_documents_slug = selectedTemplate.fillable_documents_slug;

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
			if (selectedTemplate.document_type == 'generated' || selectedTemplate.document_type == 'hybrid_document')
				if (do_fillable == false) {
					obj.desc = CKEDITOR.instances['js-modify-assign-document-description'].getData();
				}

			if (selectedTemplate.document_type == 'uploaded' || selectedTemplate.document_type == 'hybrid_document') {
				obj.file = file.name === undefined ? selectedTemplate.uploaded_document_s3_name : file;
				obj.fileOrigName = selectedTemplate.uploaded_document_original_name;
			}
			// Visibility
			obj.visibleToPayroll = $('#jsVisibleToPayroll').prop('checked') ? 1 : 0;
			obj.roles = $('#jsRoles').val() || '';
			obj.departments = $('#jsDepartments').val() || '';
			obj.teams = $('#jsTeams').val() || '';
			obj.employees = $('#jsEmployees').val() || '';
			obj.setting_is_confidential = $('#modify-assign-document-modal [name="setting_is_confidential"]').prop('checked') ? 'on' : 'off';
			obj.confidentialSelectedEmployees = '';
			//
			obj.confidentialSelectedEmployees = $('#modify-assign-document-modal #confidentialSelectedEmployees').val() || '';
			//
			// approver flow
			var approverInfo = $('#jsModifyAndAssignNewDocument').documentApprovalFlow('get');
			$('#jsModifyAndAssignNewDocument').documentApprovalFlow('clear');
			//
			obj.has_approval_flow = "off";
			obj.approvers_note = "";
			obj.approvers_list = "";
			//
			if (approverInfo.isChecked) {
				obj.has_approval_flow = 'on';
				obj.approvers_note = approverInfo.approverNote;
				obj.approvers_list = approverInfo.approversList.toString();
			}
			//
			var post = new FormData();
			//
			$.each(megaOBJ, function(index, val) {
				post.append(index, val);
			});
			$.each(obj, function(index, val) {
				post.append(index, val);
			});
			//
			$('.jsModifyModalLoader').fadeIn(300);
			//
			$.ajax({
				url: "<?= base_url('hr_documents_management/assign_document'); ?>",
				type: 'POST',
				processData: false,
				contentType: false,
				data: post
			}).done(function(resp) {
				//
				$('.jsModifyModalLoader').fadeOut(300);
				alertify.alert('SUCCESS!', 'Document assigned successfully ' + (obj.sendEmail == 'yes' ? ' and an email notification is sent' : '') + '.', function() {
					//
					if (refreshPage == 1) window.location.reload();
					//
					resetView(undefined, obj.documentSid, 'assign');
				});
			});
		}

		// Modify Assigned Document
		async function StartModifyAssignedDocumentProcess(
			e
		) {
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
			if (!Object.keys(d).length) {
				d = getAssignedDocument(
					$(this).data('id'),
					"noActionDocuments"
				)
				//
				if (!Object.keys(d).length) {
					return alertify.alert(
						"Error!",
						"You don't have permission to this document.",
						function() {}
					)
				}
			}
			//

			selectedTemplate = d;


			//
			do_upload = d.document_type == 'uploaded' || d.document_type == 'hybrid_document' || d.offer_letter_type == 'uploaded' || d.offer_letter_type == 'hybrid_document' ? true : false;
			do_descpt = d.document_type == 'generated' || d.document_type == 'hybrid_document' || d.offer_letter_type == 'generated' || d.offer_letter_type == 'hybrid_document' ? true : false;
			//do_fillable = false;

			//
			if (d.fillable_documents_slug) {
				do_descpt = false;
			}

			//
			rows += '<h4 id="gen_document_label">Are you sure you want to assign this document: [<b>' + (d.document_title) + '</b>]</h4>';
			rows += '<b> Please review this document and make any necessary modifications.</b>';
			rows += '<hr />';
			//
			// Check if the document type
			//
			if (d.fillable_documents_slug) {
				do_fillable = true;
				let rowData = await getFillableDocumentContent(d.fillable_documents_slug);
				rows += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Description</label>'
				rows += rowData.key;
			}


			if (do_upload) rows += getUploadContent();


			if (do_descpt) rows += getGeneratedContent('js-modify-assigned-document-description');



			<?php if (ASSIGNEDOCIMPL) { ?>
				let signerFlag = false;
				if (do_descpt || do_fillable) {
					signerFlag = true;

				}

				rows += getConsentTypes(signerFlag);
			<?php } ?>
			if (do_descpt) rows += getSigners('js-modify-assigned-document-signers');
			//
			//
			if (d.fillable_documents_slug == 'written-employee-counseling-report-form' || d.fillable_documents_slug == 'notice-of-separation') {
				rows += getSigners('js-modify-assigned-document-signers');
			}

			rows += getVisibilty(do_descpt);
			//
			if (d.approver_document == 1) {
				rows += getApproversManager();
			}
			//
			rows += `<?php echo $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckboxIdx" => "jsHasApprovalFlowMAD", "containerIdx" => "jsApproverFlowContainerMAD", "addEmployeeIdx" => "jsAddDocumentApproversMAD", "intEmployeeBoxIdx" => "jsEmployeesadditionalBoxMAD", "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBoxMAD", "approverNoteIdx" => "jsApproversNoteMAD", 'mainId' => 'testApproversMAD'], true); ?>`;
			//
			rows += getEmailContent();
			//
			if ($(this).data('type').toLowerCase().match(/notcompleted/ig) === null) {
				rows += getResetBox();
			}
			rows += getRequiredRow();
			rows += getSignatureRequiredRow();
			rows += getSettings();


			if (do_descpt) rows += getTags();


			//
			// if(do_descpt){
			if ($('#jsRoles').data('select2')) {
				$('#jsRoles').data('select2').destroy()
				$('#jsRoles').remove()
			}
			//
			if ($('#jsDepartments').data('select2')) {
				$('#jsDepartments').data('select2').destroy()
				$('#jsDepartments').remove()
			}
			//
			if ($('#jsTeams').data('select2')) {
				$('#jsTeams').data('select2').destroy()
				$('#jsTeams').remove()
			}
			//
			if ($('#jsEmployees').data('select2')) {
				$('#jsEmployees').data('select2').destroy()
				$('#jsEmployees').remove()
			}
			//
			let select2s = ['#jsRoles', '#jsEmployees', '#jsDepartments', '#jsTeams'];
			//
			if (do_descpt) {
				select2s.push('#js-modify-assigned-document-signers');
			}
			//

			if (d.fillable_documents_slug == 'written-employee-counseling-report-form' || d.fillable_documents_slug == 'notice-of-separation') {
				select2s.push('#js-modify-assigned-document-signers');
			}

			//
			if (d.approver_document == 1) {
				select2s.push('#js-modify-assign-document-approvers');
			}
			//
			Modal(
				'Modify Assigned Document',
				rows,
				'<button class="btn btn-success js-modify-assigned-submit-btn">Update</button>',
				'modify-assigned-document-modal',
				do_descpt ? ['js-modify-assigned-document-description'] : [],
				select2s,
				function() {
					//
					do_descpt ? CKEDITOR.instances['js-modify-assigned-document-description'].setData(d.document_description) : '';
					$('#js-modify-assign-document-signature option[value="' + (d.signature_required) + '"]').prop('selected', true);
					$('#js-modify-assign-document-download option[value="' + (d.download_required) + '"]').prop('selected', true);
					$('#js-modify-assign-document-acknowledgment option[value="' + (d.acknowledgment_required) + '"]').prop('selected', true);
					do_descpt ? $('#js-modify-assigned-document-signers').select2('val', d.managersList != null && d.managersList != '' ? d.managersList.split(',') : null) : '';

					//

					if (d.fillable_documents_slug == 'written-employee-counseling-report-form' || d.fillable_documents_slug == 'notice-of-separation') {
						$('#js-modify-assigned-document-signers').select2('val', d.managersList != null && d.managersList != '' ? d.managersList.split(',') : null)
					}


					$('.js-modify-assign-document-required[value="' + (selectedTemplate.is_required) + '"]').prop('checked', true);
					$('.js-modify-assign-document-signature-required[value="' + (selectedTemplate.is_signature_required) + '"]').prop('checked', true);
					//
					if (d.document_type == "offer_letter") {
						$('#jsApprovalSection').hide();
					}
					//
					// Approver  Flow
					var approverPrefill = {};
					var approverSection = approverSection = {
						appCheckboxIdx: '.jsHasApprovalFlowMAD',
						containerIdx: '.jsApproverFlowContainerMAD',
						addEmployeeIdx: '.jsAddDocumentApproversMAD',
						intEmployeeBoxIdx: '.jsEmployeesadditionalBoxMAD',
						extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBoxMAD',
						approverNoteIdx: '.jsApproversNoteMAD',
						employeesList: <?= json_encode($employeesList); ?>,
						documentId: d.sid
					};
					//
					if (d.has_approval_flow && d.has_approval_flow == 1) {
						approverPrefill.isChecked = true;
						approverPrefill.approverNote = d.document_approval_note;
						approverPrefill.approversList = d.document_approval_employees.split(',');
						//
						approverSection.prefill = approverPrefill;
					}
					//
					$("#jsModifyAssignedDocument").documentApprovalFlow(approverSection);
					//
					$('#jsVisibleToPayroll').prop('checked', selectedTemplate.visible_to_payroll == 0 ? false : true);
					//
					if (do_descpt) {
						$('#jsRoles').select2('val', []);
						$('#jsEmployees').select2('val', []);
						$('#jsDepartments').select2('val', []);
						$('#jsTeams').select2('val', []);
						if (d.allowed_roles != '0' && d.allowed_roles != null) $('#jsRoles').select2('val', d.allowed_roles.split(','));
						if (d.allowed_employees != '0' && d.allowed_employees != null) $('#jsEmployees').select2('val', d.allowed_employees.split(','));
						if (d.allowed_departments != '0' && d.allowed_departments != null) $('#jsDepartments').select2('val', d.allowed_departments.split(','));
						if (d.allowed_teams != '0' && d.allowed_teams != null) $('#jsTeams').select2('val', d.allowed_teams.split(','));
						//
						if (d.is_document_authorized == 1 && d.managersList != undefined) {
							$('#js-modify-assigned-document-signers').select2('val', d.managersList.split(','));
						}
						//
						if (d.approver_document == 1) {
							$('#js-modify-assign-document-approvers').select2('val', d.approver_managers.split(','));
						}

					}
					$('.modify-assigned-document-modal-loader').fadeOut(300);
					$('[data-toggle="propover"]').popover({
						trigger: 'hover',
						placement: 'right'
					});
					//
					$('#modify-assigned-document-modal [name="setting_is_confidential"]').prop('checked', d.is_confidential == "1" ? true : false);
					$('#modify-assigned-document-modal #confidentialSelectedEmployees').select2({
						closeOnSelect: false
					});
					//
					if (d.confidential_employees) {
						$('#modify-assigned-document-modal #confidentialSelectedEmployees').select2('val', d.confidential_employees.split(','));
					}
					//
					$('.jsModifyModalLoader').fadeOut(300);
				}
			);

			var allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'];
			//
			if (d.document_type == 'offer_letter') {
				allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
			}
			//
			$('#modify_assign_document').mFileUploader({
				fileLimit: -1,
				allowedTypes: allowedTypes,
				placeholderImage: d.document_s3_name != '' && d.document_s3_name != null ? d.document_s3_name : ''
			});
		}


		//
		function AssignedDocument(
			e
		) {
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
			obj.documentType = selectedTemplate.document_type == 'offer_letter' ? selectedTemplate.offer_letter_type : selectedTemplate.document_type;
			obj.managerList = $('#js-modify-assigned-document-signers').val();
			obj.isSignature = $('#js-modify-assign-document-signature').val();
			obj.isDownload = $('#js-modify-assign-document-download').val();
			obj.isAcknowledged = $('#js-modify-assign-document-acknowledgment').val();
			obj.documentSid = selectedTemplate.sid;
			// obj.visibleToPayroll = selectedTemplate.visible_to_payroll;
			obj.sendEmail = $('.js-modify-assign-document-send-email:checked').val();
			if (selectedTemplate.document_type == 'generated' || selectedTemplate.document_type == 'hybrid_document' || selectedTemplate.offer_letter_type == 'generated')
				if (do_fillable == false) {
					obj.desc = CKEDITOR.instances['js-modify-assigned-document-description'].getData();
				}

			if (selectedTemplate.document_type == 'uploaded' || selectedTemplate.document_type == 'hybrid_document' || selectedTemplate.offer_letter_type == 'uploaded') {
				obj.file = file.name === undefined ? selectedTemplate.document_s3_name : file;
				obj.fileOrigName = selectedTemplate.document_original_name;
			}
			//
			obj.isSignature = obj.isSignature === undefined ? 0 : obj.isSignature;
			obj.managerList = obj.managerList === undefined ? null : obj.managerList;
			obj.reset = obj.reset === undefined ? null : obj.reset;
			//
			obj.mainDocumentId = selectedTemplate.document_sid;
			obj.isRequired = $('.js-modify-assign-document-required:checked').val();
			obj.isSignatureRequired = $('.js-modify-assign-document-signature-required:checked').val();
			// Visibility
			obj.visibleToPayroll = $('#jsVisibleToPayroll').prop('checked') ? 1 : 0;
			obj.selected_roles = $('#jsRoles').val() || '';
			obj.selected_departments = $('#jsDepartments').val() || '';
			obj.selected_teams = $('#jsTeams').val() || '';
			obj.selected_employees = $('#jsEmployees').val() || '';
			obj.is_confidential = $('#modify-assigned-document-modal [name="setting_is_confidential"]').prop('checked') ? 'on' : 'off';
			obj.confidentialSelectedEmployees = $('#modify-assigned-document-modal #confidentialSelectedEmployees').val() || '';
			//
			// approver flow
			var approverInfo = $('#jsModifyAssignedDocument').documentApprovalFlow('get');
			$('#jsModifyAssignedDocument').documentApprovalFlow('clear');
			//
			obj.has_approval_flow = "off";
			obj.approvers_note = "";
			obj.approvers_list = "";
			//
			if (approverInfo.isChecked) {
				obj.has_approval_flow = 'on';
				obj.approvers_note = approverInfo.approverNote;
				obj.approvers_list = approverInfo.approversList.toString();
			}
			//
			var post = new FormData();
			//
			$.each(megaOBJ, function(index, val) {
				post.append(index, val);
			});
			$.each(obj, function(index, val) {
				post.append(index, val);
			});

			
			//
			$('.jsModifyModalLoader').fadeIn(300);
			$.ajax({
				url: "<?= base_url('hr_documents_management/update_assigned_document'); ?>",
				type: 'POST',
				processData: false,
				contentType: false,
				data: post
			}).done(function(resp) {
				//
				$('.jsModifyModalLoader').fadeOut(300);
				alertify.alert('SUCCESS!', '' + (selectedTemplate.document_type == 'offer_letter' ? 'Offer Letter / Pay Plan' : 'Document') + ' updated successfully ' + (obj.sendEmail == 'yes' ? ' and an email notification is sent' : '') + '.', function() {
					window.location.reload();
				});
			});
		}

		// Modify and Assign
		// offer letter
		function StartModifyAndAssignOfferLetterProcess(
			e
		) {
			//
			e.preventDefault();
			//
			var
				d = getOfferLetter($(this).data('id')),
				rows = '',
				do_upload,
				do_descpt;
			// 
			selectedTemplate = d;
			//
			modelFor = 'offer_letter';
			//
			do_upload = d.letter_type == 'uploaded' || d.letter_type == 'hybrid_document' ? true : false;
			do_descpt = d.letter_type == 'generated' || d.letter_type == 'hybrid_document' ? true : false;
			//
			rows += '<h4 id="gen_document_label">Are you sure you want to assign this document: [<b>' + (d.letter_name) + '</b>]</h4>';
			rows += '<b> Please review this document and make any necessary modifications.</b>';
			rows += '<hr />';
			//
			// Check if the document type
			if (do_upload) rows += getUploadContent();
			if (do_descpt) rows += getGeneratedContent('js-modify-assign-offer-letter-description');
			<?php if (ASSIGNEDOCIMPL) { ?>
				rows += getConsentTypes(do_descpt);
			<?php } ?>
			if (do_descpt) rows += getSigners('js-modify-assign-offer-letter-signers');
			rows += getVisibilty(do_descpt);
			//
			rows += `<?php echo $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckboxIdx" => "jsHasApprovalFlowAOL", "containerIdx" => "jsApproverFlowContainerAOL", "addEmployeeIdx" => "jsAddDocumentApproversAOL", "intEmployeeBoxIdx" => "jsEmployeesadditionalBoxAOL", "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBoxAOL", "approverNoteIdx" => "jsApproversNoteAOL", 'mainId' => 'testApproversAOL'], true); ?>`;

			rows += `<?php echo $this->load->view('hr_documents_management/partials/settings', ['is_confidential' =>  $document_info['is_confidential']], TRUE); ?>`;

			rows += getEmailContent();
			if (do_descpt) rows += getTags();
			//
			// if(do_descpt){
			//
			if ($('#jsRoles').data('select2')) {
				$('#jsRoles').data('select2').destroy()
				$('#jsRoles').remove()
			}
			//
			if ($('#jsDepartments').data('select2')) {
				$('#jsDepartments').data('select2').destroy()
				$('#jsDepartments').remove()
			}
			//
			if ($('#jsTeams').data('select2')) {
				$('#jsTeams').data('select2').destroy()
				$('#jsTeams').remove()
			}
			//
			if ($('#jsEmployees').data('select2')) {
				$('#jsEmployees').data('select2').destroy()
				$('#jsEmployees').remove()
			}
			// }
			//
			Modal(
				'Modify & Assign This Offer Letter / Pay Plan',
				rows,
				'<button class="btn btn-success js-modify-assign-offer-letter-submit-btn">Assign This Offer Letter / Pay Plan</button>',
				'modify-assign-offer-letter-modal',
				do_descpt ? ['js-modify-assign-offer-letter-description'] : [],
				do_descpt ? ['#js-modify-assign-offer-letter-signers'] : [],
				function() {
					//
					do_descpt ? CKEDITOR.instances['js-modify-assign-offer-letter-description'].setData(d.letter_body) : '';
					$('#js-modify-assign-offer-letter-signature option[value="' + (d.signature_required) + '"]').prop('selected', true);
					$('#js-modify-assign-offer-letter-download option[value="' + (d.download_required) + '"]').prop('selected', true);
					$('#js-modify-assign-offer-letter-acknowledgment option[value="' + (d.acknowledgment_required) + '"]').prop('selected', true);
					do_descpt ? $('#js-modify-assign-offer-letter-signers').select2('val', d.signers != null && d.signers != '' ? d.signers.split(',') : null) : '';

					//
					if (d.visible_to_payroll) {
						$('#jsVisibleToPayroll').prop('checked', true);
					}
					//
					// if(do_descpt){
					$('#jsRoles').select2({
						closeOnSelect: false
					});
					$('#jsDepartments').select2({
						closeOnSelect: false
					});
					$('#jsTeams').select2({
						closeOnSelect: false
					});
					$('#jsEmployees').select2({
						closeOnSelect: false
					});


					//Approval flow
					//
					// Approver  Flow
					var approverPrefill = {};
					var approverSection = approverSection = {
						appCheckboxIdx: '.jsHasApprovalFlowAOL',
						containerIdx: '.jsApproverFlowContainerAOL',
						addEmployeeIdx: '.jsAddDocumentApproversAOL',
						intEmployeeBoxIdx: '.jsEmployeesadditionalBoxAOL',
						extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBoxAOL',
						approverNoteIdx: '.jsApproversNoteAOL',
						employeesList: <?= json_encode($employeesList); ?>,
						documentId: 0
					};
					//
					if (d.has_approval_flow && d.has_approval_flow == 1) {
						approverPrefill.isChecked = true;
						approverPrefill.approverNote = d.document_approval_note;
						approverPrefill.approversList = d.document_approval_employees.split(',');
						//
						approverSection.prefill = approverPrefill;
					}
					//
					$("#jsModifyAndAssignOfferLetter").documentApprovalFlow(approverSection);
					//
					if (d.is_available_for_na) {
						$('#jsRoles').select2('val', d.is_available_for_na.split(','));
					}
					//
					if (d.allowed_departments) {
						$('#jsDepartments').select2('val', d.allowed_departments.split(','));
					}
					//
					if (d.allowed_teams) {
						$('#jsTeams').select2('val', d.allowed_teams.split(','));
					}
					//
					if (d.allowed_employees) {
						$('#jsEmployees').select2('val', d.allowed_employees.split(','));
					}
					//
					$('#modify-assign-offer-letter-modal #confidentialSelectedEmployees').select2();
					//
					if (d.is_confidential == 1) {
						$('#modify-assign-offer-letter-modal [name="setting_is_confidential"]').prop('checked', true);

					}
					//
					$('#modify-assign-offer-letter-modal [name="setting_is_confidential"]').prop('checked', d.is_confidential == "1" ? true : false);
					// 
					$('#modify-assign-offer-letter-modal #confidentialSelectedEmployees').select2({
						closeOnSelect: false
					});
					//
					if (d.confidential_employees) {
						$('#modify-assign-offer-letter-modal #confidentialSelectedEmployees').select2('val', d.confidential_employees.split(','));
					}
					//
					$('.jsSelectedEmployee').select2();
					//
					$('.modify-assign-offer-letter-modal-loader').fadeOut(300);

				}
			);
		}

		//
		function AssignOfferLetter(
			e
		) {
			//
			e.preventDefault();
			//
			var obj = {};
			//
			obj.documentTitle = selectedTemplate.letter_name;
			obj.documentType = selectedTemplate.letter_type;
			obj.managerList = $('#js-modify-assign-offer-letter-signers').val();
			obj.isSignature = $('#js-modify-assign-document-signature').val();
			obj.isDownload = $('#js-modify-assign-document-download').val();
			obj.isAcknowledged = $('#js-modify-assign-document-acknowledgment').val();
			obj.documentSid = selectedTemplate.sid;
			obj.visibleToPayroll = selectedTemplate.visible_to_payroll;
			obj.sendEmail = $('.js-modify-assign-document-send-email:checked').val();
			if (selectedTemplate.letter_type == 'generated' || selectedTemplate.letter_type == 'hybrid_document')
				obj.desc = CKEDITOR.instances['js-modify-assign-offer-letter-description'].getData();
			if (selectedTemplate.letter_type == 'uploaded' || selectedTemplate.letter_type == 'hybrid_document') {
				obj.file = file.name === undefined ? selectedTemplate.uploaded_document_s3_name : file;
				obj.fileOrigName = selectedTemplate.uploaded_document_original_name;
			}
			// Visibility
			//obj.hasApprovalFlow = $('#jsHasApprovalFlow').prop('checked') ? 1 : 0;
			obj.roles = $('#jsRoles').val() || '';
			obj.departments = $('#jsDepartments').val() || '';
			obj.teams = $('#jsTeams').val() || '';
			obj.employees = $('#jsEmployees').val() || '';

			//Document Settings
			obj.setting_is_confidential = $('#modify-assign-offer-letter-modal [name="setting_is_confidential"]').prop('checked') ? 'on' : 'off';
			obj.confidentialSelectedEmployees = $('#modify-assign-offer-letter-modal #confidentialSelectedEmployees').val();

			//
			// approver flow
			var approverInfo = $('#jsModifyAndAssignOfferLetter').documentApprovalFlow('get');
			$('#jsModifyAndAssignOfferLetter').documentApprovalFlow('clear');
			//
			obj.has_approval_flow = "off";
			obj.approvers_note = "";
			obj.approvers_list = "";
			//
			if (approverInfo.isChecked) {
				obj.has_approval_flow = 'on';
				obj.approvers_note = approverInfo.approverNote;
				obj.approvers_list = approverInfo.approversList.toString();
			}


			//
			var assigners = new Array();
			//
			$('.jsSelectedEmployee').each(function(i) {
				var approver_id = $(this).val();
				//
				if (approver_id != 0) {
					assigners.push(approver_id);
				}
				//
			});
			//
			if (assigners.length > 0) {
				obj.assigner = assigners;
				obj.assigner_note = $('#assigner_note').val();
			}
			//
			var post = new FormData();
			//
			$.each(megaOBJ, function(index, val) {
				post.append(index, val);
			});
			$.each(obj, function(index, val) {
				post.append(index, val);
			});
			//
			$('.jsModifyModalLoader').fadeIn(300);
			$.ajax({
				url: "<?= base_url('hr_documents_management/assign_offer_letter_new'); ?>",
				type: 'POST',
				processData: false,
				contentType: false,
				data: post
			}).done(function(resp) {
				//
				$('.jsModifyModalLoader').fadeOut(300);
				alertify.alert('SUCCESS!', 'Offer Letter / Pay Plan assigned successfully ' + (obj.sendEmail == 'yes' ? ' and an email notification is sent' : '') + '.', function() {
					window.location.reload();
				});
			});
		}

		// Visibility
		function getVisibilty(is_generated) {
			//
			var html = '';
			//
			html += '<div class="row">';
			html += '    <div class="col-sm-12">';
			html += '        <div class="panel panel-default">';
			html += '            <div class="panel-heading">';
			html += '                <h5>';
			html += '                    <strong>Visibility</strong>&nbsp;<i class="fa fa-question-circle-o csClickable jsHintBtn" aria-hidden="true"  data-target="visibilty"></i>';
			html += '                    <p class="jsHintBody" data-hint="visibilty"><br /><?= getUserHint('visibility_hint'); ?></p>';
			html += '                </h5>';
			html += '            </div>';
			html += '            <div class="panel-body">';
			html += '                <!-- Payroll -->';
			html += '                <label class="control control--checkbox">';
			html += '                    Visible To Payroll';
			html += '                    <input type="checkbox" name="visible_to_payroll" id="jsVisibleToPayroll" />';
			html += '                    <div class="control__indicator"></div>';
			html += '                </label>';
			// if(is_generated){

			html += '                <hr />';
			html += '                <!-- Roles -->';
			html += '                <label>Roles</label>';
			html += '                <select name="roles[]" id="jsRoles" multiple>';
			//
			var roles = <?= json_encode(getRoles()); ?>;
			//
			if (Object.keys(roles).length) {
				$.each(roles, function(i, v) {
					html += ' <option value="' + (i) + '">' + (v) + '</option>';
				});
			}
			html += '                </select>';
			html += '                <br />';
			html += '                <br />';
			html += '                <!-- Departments -->';
			html += '                <label>Departments</label>';
			html += '                <select name="departments[]" id="jsDepartments" multiple>';
			if (departmentList && departmentList.length) {
				departmentList.map(function(v) {
					html += '<option value="' + (v['sid']) + '">' + (v['name']) + '</option>';
				});
			}
			html += '                </select>';
			html += '                <br />';
			html += '                <br />';
			html += '                <!-- Teams -->';
			html += '                <label>Teams</label>';
			html += '                <select name="teams[]" id="jsTeams" multiple>';
			if (teamList && teamList.length) {
				teamList.map(function(v) {
					html += '<option value="' + (v['sid']) + '">' + (v['name']) + '</option>';
				});
			}
			html += '                </select>';
			html += '                <br />';
			html += '                <br />';
			html += '                <!-- Employees -->';
			html += '                <label>Employees</label>';
			html += '                <select name="employees[]" id="jsEmployees" multiple>';
			if (employeeList.length) {
				employeeList.map(function(v) {
					html += '<option value="' + (v['sid']) + '">' + (remakeEmployeeName(v)) + '</option>';
				});
			}
			html += '                </select>';
			// }
			html += '            </div>';
			html += '        </div>';
			html += '    </div>';
			html += '</div>';
			//
			return html;
		}

		//
		function getResetBox() {
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
		function getConsentTypes(do_descpt) {
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
			if (do_descpt) {
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
			} else {
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
		function getSigners(sid) {
			//
			sid = sid === undefined ? 'js-modify-assign-document-signers' : sid;
			//
			var rows = '';
			//
			rows += '<div class="form-group">';
			rows += '	<label>Authorized Management Signers</label>';
			rows += '	<select id="' + (sid) + '" multiple="true">';

			var
				i = 0,
				il = allEmployees.length;
			//
			for (i; i < il; i++) {
				rows += '<option value="' + (allEmployees[i]['sid']) + '">' + (remakeEmployeeName(allEmployees[i])) + '</option>';
			}

			rows += '   </select>';
			rows += '</div>';
			//
			return rows;
		}

		//
		function getApproversManager() {
			//
			var rows = '';
			//
			rows += '<div class="form-group">';
			rows += '	<label>Document Approver Manager</label>';
			rows += '	<select id="js-modify-assign-document-approvers" multiple="true">';

			var
				i = 0,
				il = allEmployees.length;
			//
			for (i; i < il; i++) {
				rows += '<option value="' + (allEmployees[i]['sid']) + '">' + (remakeEmployeeName(allEmployees[i])) + '</option>';
			}

			rows += '   </select>';
			rows += '</div>';
			//
			return rows;
		}

		//
		function getEmailContent() {
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
		function getUploadContent() {
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
		function getGeneratedContent(sid) {
			var rows = '';
			//
			rows += '<div class="row">';
			rows += '<div class="col-sm-12">';
			rows += '<div class="form-group">';
			rows += '	<br />';
			rows += '	<label>Description <span class="hr-required red">*</span></label>';
			rows += '	<textarea id="' + (sid) + '"></textarea>';
			rows += '</div>';
			rows += '</div>';
			rows += '</div>';
			//
			return rows;
		}

		//
		function GetFileForUpload(e) {
			file = e.target.files[0];
			//
			if ($.inArray(file.type, allowedTypes) === -1) {
				file = {};
				$(this).parent().parent().find('p.js-error').text('Invalid file format. Please upload the right format file.');
			}
		}

		//
		function getTags() {
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
			rows += '    <div class="tags-arae">';
			rows += '        <div class="col-md-12">';
			rows += '            <h5><strong>Pay Plan / Offer Letter tags:</strong></h5>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{flat_rate_technician}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">';
			rows += '            </div>';
			rows += '        </div>';
			rows += '        <div class="col-md-6">';
			rows += '            <div class="form-group autoheight">';
			rows += '                <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">';
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
		) {
			var
				i = 0,
				il = allDocuments.length;
			//
			for (i; i < il; i++) {
				if (allDocuments[i]['sid'] == sid) return allDocuments[i];
			}
			return {};
		}


		// Get document
		function getAssignedDocument(
			documentSid,
			type
		) {
			var
				i = 0,
				il = Object.keys(tabDocs[type]).length;
			//
			for (i; i < il; i++) {
				if (tabDocs[type][i]['document_sid'] == documentSid) return tabDocs[type][i];
			}
			return {};
		}

		// Offer letter create steps
		// Step 1 = Generate View
		// Step 2 = Validate & Submit
		//
		function StartOfferLetterProcess(e) {
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
				['#js-template-signers', '#js-templates-add'],
				function() {
					//
					var
						rolesRows = '',
						departmentRows = '',
						teamsRows = '',
						employeeRows = '';
					//
					$.each(rolesList, function(i, v) {
						//
						rolesRows += '<option value="' + (i) + '">' + (v) + '</option>';
					});

					//
					if (departmentList) {
						departmentList.map(function(v) {
							//
							departmentRows += '<option value="' + (v.sid) + '">' + (v.name) + '</option>';
						});
					}

					//
					if (teamList) {
						teamList.map(function(v) {
							//
							teamsRows += '<option value="' + (v.sid) + '">' + (v.name) + '</option>';
						});
					}

					//
					employeeList.map(function(v) {
						//
						employeeRows += '<option value="' + (v.sid) + '">' + (remakeEmployeeName(v)) + '</option>';
					});

					//
					$('#js-roles-offer-letter-add')
						.html(rolesRows)
						.select2({
							closeOnSelect: false
						});

					//
					$('#js-department-offer-letter-add')
						.html(departmentRows)
						.select2({
							closeOnSelect: false
						});

					//
					$('#js-teams-offer-letter-add')
						.html(teamsRows)
						.select2({
							closeOnSelect: false
						});

					//
					$('#js-employees-offer-letter-add')
						.html(employeeRows)
						.select2({
							closeOnSelect: false
						});

					$("#js-popup #confidentialSelectedEmployees").select2();

					$("#jsAddSpecificOfferLetter").documentApprovalFlow({
						appCheckboxIdx: '.jsHasApprovalFlowAOL',
						containerIdx: '.jsApproverFlowContainerAOL',
						addEmployeeIdx: '.jsAddDocumentApproversAOL',
						intEmployeeBoxIdx: '.jsEmployeesadditionalBoxAOL',
						extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBoxAOL',
						approverNoteIdx: '.jsApproversNoteAOL',
						employeesList: <?= json_encode($employeesList); ?>,
						documentId: 0
					});
				}
			);
			//

			//--- Automatically assign after Days:

			$('input[name="assign-in-days"]').val(0);
			$('input[name="assign-in-months"]').val(0);
			$('.js-type').hide();
			$('input[value="months"]').prop('checked', false);
			$('input[value="days"]').prop('checked', true);
			$('.js-type-days').show();

			//
			$('input[name="assign_type"]').click(function() {
				$('.js-type').hide(0).val(0);
				$('.js-type-' + ($(this).val()) + '').show(0);
			});
			//---------------------



			$('.js-template-type[value="uploaded"]').click();
			$('.js-template-send-email[value="no"]').click();
			//
			iLoader('hide', '.js-popup');
		}

		// Validate and save offer letter
		function ValidateAndSaveOfferLetter(e) {
			//
			e.preventDefault();
			var assigners = new Array();
			//
			$('.js-error').text('');
			//
			var upload_file = $('#upload_document').mFileUploader('get');
			//
			var o = {
				name: $('#js-template-name').val().trim(),
				body: CKEDITOR.instances['js-template-body'].getData().trim(),
				guidence: CKEDITOR.instances['js-template-guidence'].getData().trim(),
				// file : file,
				file: upload_file,
				type: $('.js-template-type:checked').val().trim(),
				signers: $('#js-template-signers').val(),
				signature: $('#js-template-signature').val(),
				download: $('#js-template-download').val(),
				acknowledgment: $('#js-template-acknowledgment').val(),
				sortOrder: $('#js-template-sort-order').val().trim(),
				sendEmail: $('.js-template-send-email:checked').val(),
				isRequired: $('.js-template-required:checked').val(),
				isSignatureRequired: $('.js-template-signature-required:checked').val(),
				setting_is_confidential: $('#js-popup [name="setting_is_confidential"]').prop('checked') ? 'on' : 'off',
				confidentialSelectedEmployees: $('#js-popup #confidentialSelectedEmployees').val(),

				//Automatically assign after Days
				assign_in_months: $('#js-popup [name="assign-in-months"]').val(),
				assign_in_days: $('#js-popup [name="assign-in-days"]').val(),
				assign_type: $('#js-popup [name="assign_type"]:checked').val(),

				assign: $(this).data('value'),
				fromTemplate: false,
				// Visibility
				payroll: $('#js-payroll-offer-letter-add').prop('checked') ? 1 : 0,
				roles: $('#js-roles-offer-letter-add').val(),
				departments: $('#js-department-offer-letter-add').val(),
				teams: $('#js-teams-offer-letter-add').val(),
				employees: $('#js-employees-offer-letter-add').val(),
				// Approval flow
				has_approval_flow: 'off',
				approvers_note: '',
				approvers_list: ''
			};
			var approverInfo = $("#jsAddSpecificOfferLetter").documentApprovalFlow("get");
			//
			if (approverInfo.isChecked === true) {
				o.has_approval_flow = 'on';
				o.approvers_note = approverInfo.approverNote;
				o.approvers_list = approverInfo.approversList.toString();
			}
			//
			if (o.type == 'template') {
				o.type = selectedTemplate.letter_type;
				o.fromTemplate = 1;
			}
			//
			var proceed = true;
			// Validate 
			if (o.name == '') {
				$('#js-template-name').parent().find('p.js-error').text('This field is required.');
				proceed = false;
			}
			// For generated documents
			if (o.type == 'generated' || o.type == 'hybrid_document') {
				if (o.body == '') {
					$('#js-template-body').parent().find('p.js-error').text('This field is required.');
					proceed = false;
				}
			}
			// For uploaded documents
			if (o.type == 'uploaded' || o.type == 'hybrid_document') {
				if (o.file.name === undefined) {
					$('#js-offer-letter-file-add').parent().parent().find('p.js-error').text('This field is required.');
					proceed = false;
				}
			}

			if (o.type != 'generated') {
				if ($.isEmptyObject(upload_file)) {
					alertify.alert('ERROR!', 'Please select a file to upload.', () => {});
					proceed = false;
				} else if (upload_file.hasError === true) {
					alertify.alert('ERROR!', 'Please select a valid file format.', () => {});
					proceed = false;
				}
			}
			//
			if (o.file.s3Name !== undefined) o.file = JSON.stringify(o.file);
			//
			if (!proceed) return;
			//
			iLoader('show', '.js-popup');
			//
			// console.log(megaOBJ);
			// console.log(o);
			var post = new FormData();
			$.each(megaOBJ, function(index, val) {
				post.append(index, val);
			});
			$.each(o, function(index, val) {
				post.append(index, val);
			});
			//
			$.ajax({
					url: '<?= base_url('hr_documents_management/offer_letter_add'); ?>',
					type: 'POST',
					processData: false,
					contentType: false,
					data: post,
				})
				.done(function(resp) {
					iLoader('hide', '.js-popup');
					//
					if (resp.Status === false) {
						alertify.alert('ERROR!', resp.Response);
						return;
					}
					//
					alertify.alert('SUCCESS!', resp.Response, function() {
						window.location.reload();
					});
				});
		}

		// Offer letter edit steps
		// Step 1 = Generate View
		// Step 2 = Validate & Submit
		//
		function StartOfferLetterEditProcess(e) {
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
				function() {
					if (d.signers != null) {
						$('#js-template-signers-edit').select2('val', d.signers.split(','));
					}
					//
					var
						rolesRows = '',
						departmentRows = '',
						teamsRows = '',
						employeeRows = '';
					//
					$.each(rolesList, function(i, v) {
						//
						rolesRows += '<option value="' + (i) + '">' + (v) + '</option>';
					});

					//
					departmentList.map(function(v) {
						//
						departmentRows += '<option value="' + (v.sid) + '">' + (v.name) + '</option>';
					});

					//
					teamList.map(function(v) {
						//
						teamsRows += '<option value="' + (v.sid) + '">' + (v.name) + '</option>';
					});

					//
					employeeList.map(function(v) {
						//
						employeeRows += '<option value="' + (v.sid) + '">' + (remakeEmployeeName(v)) + '</option>';
					});

					//
					$('#js-roles-offer-letter-edit')
						.html(rolesRows)
						.select2({
							closeOnSelect: false
						});

					//
					$('#js-department-offer-letter-edit')
						.html(departmentRows)
						.select2({
							closeOnSelect: false
						});

					//
					$('#js-teams-offer-letter-edit')
						.html(teamsRows)
						.select2({
							closeOnSelect: false
						});

					//
					$('#js-employees-offer-letter-edit')
						.html(employeeRows)
						.select2({
							closeOnSelect: false
						});

					// Set the predefault
					$('#js-roles-offer-letter-edit').select2('val', d.allowed_roles != '' || d.allowed_roles == null ? d.allowed_roles.split(',') : null);
					$('#js-department-offer-letter-edit').select2('val', d.allowed_departments != '' || d.allowed_departments == null ? d.allowed_departments.split(',') : null);
					$('#js-teams-offer-letter-edit').select2('val', d.allowed_teams != '' || d.allowed_teams == null ? d.allowed_teams.split(',') : null);
					$('#js-employees-offer-letter-edit').select2('val', d.allowed != '' || d.allowed == null ? d.allowed.split(',') : null);

				}
			);
			//
			$('.js-template-type-edit[value="' + (d.letter_type) + '"]').click();
			//
			$('#js-template-name-edit').val(d.letter_name);
			$('#js-template-body-edit').val(d.letter_body);
			$('#js-template-guidence-edit').val(d.guidence);
			$('#js-template-acknowledgment-edit option[value="' + (d.acknowledgment_required) + '"]').prop('selected', true);
			$('#js-template-download-edit option[value="' + (d.download_required) + '"]').prop('selected', true);
			$('#js-template-signature-edit option[value="' + (d.signature_required) + '"]').prop('selected', true);
			$('#js-template-sort-order-edit').val(d.sort_order);
			//
			iLoader('hide', '.js-popup-edit');
		}

		// Validate and update offer letter
		function ValidateAndUpdateOfferLetter(e) {
			//
			e.preventDefault();
			//
			$('.js-error').text('');
			//
			var o = {
				sid: currentOfferLetter.sid,
				name: $('#js-template-name-edit').val().trim(),
				body: CKEDITOR.instances['js-template-body-edit'].getData().trim(),
				guidence: CKEDITOR.instances['js-template-guidence-edit'].getData().trim(),
				file: file,
				type: $('.js-template-type-edit:checked').val().trim(),
				signature: $('#js-template-signature-edit').val(),
				download: $('#js-template-download-edit').val(),
				acknowledgment: $('#js-template-acknowledgment-edit').val(),
				isRequired: $('.js-template-required-edit:checked').val(),
				isSignatureRequired: $('.js-template-signature-required-edit:checked').val(),
				sortOrder: $('#js-template-sort-order-edit').val().trim(),

				// Visibility
				payroll: $('#js-payroll-offer-letter-edit').prop('checked'),
				roles: $('#js-roles-offer-letter-edit').val(),
				departments: $('#js-department-offer-letter-edit').val(),
				teams: $('#js-teams-offer-letter-edit').val(),
				employees: $('#js-employees-offer-letter-edit').val()
			};
			//
			var proceed = true;
			// Validate 
			if (o.name == '') {
				$('#js-template-name-edit').parent().find('p.js-error').text('This field is required.');
				proceed = false;
			}
			// For generated documents
			if (o.type == 'generated' || o.type == 'hybrid_document') {
				if (o.body == '') {
					$('#js-template-body-edit').parent().find('p.js-error').text('This field is required.');
					proceed = false;
				}
			}
			//
			if (!proceed) return;
			iLoader('show', '.js-popup-edit');
			//
			var post = new FormData();
			$.each(megaOBJ, function(index, val) {
				post.append(index, val);
			});
			$.each(o, function(index, val) {
				post.append(index, val);
			});
			//
			$.ajax({
					url: '<?= base_url('hr_documents_management/offer_letter_edit'); ?>',
					type: 'POST',
					processData: false,
					contentType: false,
					data: post,
				})
				.done(function(resp) {
					iLoader('hide', '.js-popup-edit');
					//
					if (resp.Status === false) {
						alertify.alert('ERROR!', resp.Response);
						return;
					}
					//
					alertify.alert('SUCCESS!', resp.Response, function() {
						window.location.reload();
					});
				});
		}

		// Generate body for add offer letter
		function getOfferLetterBody(
			type
		) {

			//
			var obj = {
				title: '',
				body: ''
			};
			//
			obj.title += '<i class="fa fa-file-text-o"></i>&nbsp;&nbsp;';
			obj.title += 'Upload/Generate an Offer Letter / Pay Plan';

			//
			if (type == 'add') {
				//
				obj.body += `<?php $this->load->view('hr_documents_management/templates/offer_letter', ['offer_letters' => $offerLetters]); ?>`;
				//
			} else if (type == 'edit') {
				obj.body += `<?php $this->load->view('hr_documents_management/templates/offer_letter_edit', ['offer_letters' => $offerLetters]); ?>`;
			}

			return obj;
		}

		// Validate uploaded file
		function ValidateUploadedFile(
			e
		) {
			//
			$(this).parent().parent().find('p.js-error').text('');
			//
			file = {};
			//
			file = e.target.files[0];
			//
			if ($.inArray(file.type, allowedTypes) === -1) {
				file = {};
				$(this).parent().parent().find('p.js-error').text('Invalid file format. Please upload the right format file.');
			}
		}

		//
		function ShowUploadedFilePreview(e) {
			e.preventDefault();
			//
			var s3Name = selectedTemplate !== undefined ? selectedTemplate.uploaded_document_s3_name : currentOfferLetter.uploaded_document_s3_name;
			var origName = selectedTemplate !== undefined ? selectedTemplate.uploaded_document_original_name : currentOfferLetter.uploaded_document_original_name;
			//
			if (selectedTemplate.document_s3_name !== undefined) {
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
		function UseTemplate(e) {
			var l = getOfferLetter($(this).val());
			if (Object.keys(l).length !== 0)
				//
				selectedTemplate = l;
			$('#remove_image').hide(0);
			$('#remove_image').parent().find('.cs-error').css('padding-left', '5px');
			//
			if (l.letter_type == 'uploaded') {
				$('.js-for-uploaded').show(0);
				$('.js-for-generated').hide(0);
				$('#remove_image').show(0);
				$('#remove_image').parent().find('.cs-error').css('padding-left', '50px');
			} else if (l.letter_type == 'generated') {
				$('.js-for-generated').show(0);
				$('.js-for-uploaded').hide(0);
			} else if (l.letter_type == 'hybrid_document') {
				$('.js-for-generated').show(0);
				$('.js-for-uploaded').show(0);
				$('#remove_image').show(0);
				$('#remove_image').parent().find('.cs-error').css('padding-left', '50px');
			}
			//
			file = {
				name: l.uploaded_document_original_name,
				s3Name: l.uploaded_document_s3_name
			};
			//
			$('#js-template-name').val(l.letter_name);
			CKEDITOR.instances['js-template-body'].setData(l.letter_body);
			CKEDITOR.instances['js-template-body'].setData(l.guidence);
			$('#js-template-acknowledgment option[value="' + (l.acknowledgment_required) + '"]').prop('selected', true);
			$('#js-template-download option[value="' + (l.download_required) + '"]').prop('selected', true);
			$('#js-template-signature option[value="' + (l.signature_required) + '"]').prop('selected', true);
			$('#js-template-sort-order').val(l.sort_order);
			//
			if (l.signers !== null) $('#js-template-signers').select2('val', l.signers.split(','));

			$('#js-popup [name="setting_is_confidential"]').prop('checked', l.is_confidential == "1" ? true : false);
			//
			$('#js-popup #confidentialSelectedEmployees').select2({
				closeOnSelect: false
			});
			//
			if (l.confidential_employees) {
				$('#js-popup #confidentialSelectedEmployees').select2('val', l.confidential_employees.split(','));
			} else {
				$('#js-popup #confidentialSelectedEmployees').select2('val', "");
			}
			//
			if (l.is_available_for_na) {
				$('#js-roles-offer-letter-add').select2('val', l.is_available_for_na.split(','));
			} else {
				$('#js-roles-offer-letter-add').select2('val', "");
			}
			//
			if (l.allowed_departments) {
				$('#js-department-offer-letter-add').select2('val', l.allowed_departments.split(','));
			} else {
				$('#js-department-offer-letter-add').select2('val', "");
			}
			//
			if (l.allowed_teams) {
				$('#js-teams-offer-letter-add').select2('val', l.allowed_teams.split(','));
			} else {
				$('#js-teams-offer-letter-add').select2('val', "");
			}
			//
			if (l.allowed_employees) {
				$('#js-employees-offer-letter-add').select2('val', l.allowed_employees.split(','));
			} else {
				$('#js-employees-offer-letter-add').select2('val', "");
			}
			//
			var approverPrefill = {};
			var approverSection = {
				appCheckboxIdx: '.jsHasApprovalFlowAOL',
				containerIdx: '.jsApproverFlowContainerAOL',
				addEmployeeIdx: '.jsAddDocumentApproversAOL',
				intEmployeeBoxIdx: '.jsEmployeesadditionalBoxAOL',
				extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBoxAOL',
				approverNoteIdx: '.jsApproversNoteAOL',
				employeesList: <?= json_encode($employeesList); ?>,
				documentId: 0
			};


			// Approval flow 
			if (l.has_approval_flow == 1) {
				approverPrefill.isChecked = true;
				approverPrefill.approverNote = l.document_approval_note;
				approverPrefill.approversList = l.document_approval_employees.split(',');
				//
				approverSection.prefill = approverPrefill;
			} else {
				approverPrefill.isChecked = false;
				approverPrefill.approverNote = "";
				approverPrefill.approversList = '';
				//
				approverSection.prefill = approverPrefill;
			}
			//
			// $("#jsOfferLetterModal").documentApprovalFlow(approverSection);
			$("#jsAddSpecificOfferLetter").documentApprovalFlow(approverSection);
			//---------Automatically assign after Days
			$('input[name="assign-in-days"]').val(0);
			$('input[name="assign-in-months"]').val(0);
			$('.js-type').hide();
			$('input[value="days"]').prop('checked', false);
			$('input[value="months"]').prop('checked', false);
			$('.js-type-' + l.automatic_assign_type).show();
			$('input[value="' + l.automatic_assign_type + '"').prop('checked', true);
			$('.js-type-' + l.automatic_assign_type).find('input').val(l.automatic_assign_in);
			//
			$('input[name="assign_type"]').click(function() {
				$('.js-type').hide(0).val(0);
				$('.js-type-' + ($(this).val()) + '').show(0);
			});
			//---------------------

		}

		//
		function getUploadedFileAPIUrl(
			f,
			o
		) {
			if (f == null || f == '') return {};
			// Get file extension
			var
				r = {},
				full = "<?= AWS_S3_BUCKET_URL; ?>" + f,
				t = f.split('.');
			t = t[t.length - 1].toLowerCase().trim();
			//
			if ($.inArray(t, ['csv', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx']) !== -1) {
				r = {
					URL: 'https://view.officeapps.live.com/op/embed.aspx?src=' + (full) + '',
					PrintURL: 'https://view.officeapps.live.com/op/embed.aspx?src=' + (full) + '',
					DownloadURL: "<?= base_url('hr_documents_management/download_upload_document'); ?>/" + f,
					Extension: t,
					Target: '.js-preview-iframe',
					Type: 'iframe',
					getHTML: () => '<iframe src="' + (r.URL) + '" frameborder="0" style="width: 100%; height: 500px;" class="js-preview-iframe"></iframe>',
					getPrintHTML: () => '<a href="' + (r.PrintURL) + '" target="_blank" class="btn btn-success btn-sm">Print</a>',
					getDownloadHTML: () => '<a href="' + (r.DownloadURL) + '" class="btn btn-success btn-sm">Download</a>',
					getButtonHTML: () => r.getPrintHTML() + ' &nbsp; ' + r.getDownloadHTML()
				};
			} else if ($.inArray(t, ['jpe', 'jpeg', 'png', 'gif', 'jpg', 'jpe', 'jpeg', 'png', 'gif']) !== -1) {
				r = {
					URL: full,
					PrintURL: full,
					DownloadURL: "<?= base_url('hr_documents_management/download_upload_document'); ?>/" + f,
					Extension: t,
					Target: '.js-preview-iframe',
					Type: 'image',
					getHTML: () => '<img src="' + (r.URL) + '" style="max-width: 100%; display: block; margin: auto;" class="js-preview-iframe" />',
					getPrintHTML: () => '<a href="' + (r.PrintURL) + '" target="_blank" class="btn btn-success btn-sm">Print</a>',
					getDownloadHTML: () => '<a href="' + (r.DownloadURL) + '" class="btn btn-success btn-sm">Download</a>',
					getButtonHTML: () => r.getPrintHTML() + ' &nbsp; ' + r.getDownloadHTML()
				};
			} else {
				r = {
					URL: 'https://docs.google.com/gview?url=' + (full) + '&embedded=true',
					PrintURL: 'https://docs.google.com/gview?url=' + (full) + '&embedded=true',
					DownloadURL: "<?= base_url('hr_documents_management/download_upload_document'); ?>/" + f,
					Extension: t,
					Type: 'iframe',
					Target: '.js-preview-iframe',
					getHTML: () => '<iframe src="' + (r.URL) + '" frameborder="0" style="width: 100%; height: 500px;" class="js-preview-iframe"></iframe>',
					getPrintHTML: () => '<a href="' + (r.PrintURL) + '" target="_blank" class="btn btn-success btn-sm">Print</a>',
					getDownloadHTML: () => '<a href="' + (r.DownloadURL) + '" class="btn btn-success btn-sm">Download</a>',
					getButtonHTML: () => r.getPrintHTML() + ' &nbsp; ' + r.getDownloadHTML()
				}
			}
			//
			return r;
		}

		// Set view
		function SetView(e) {
			//
			var
				value = $(this).val(),
				type = $(this).data('type');
			//
			if (value == 'uploaded') {
				$('.jsAddOLTypeLabel_signature').text('Upload Required');
				$('.jsAddOLTypeHint').text('Enable the Re-Upload Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.');
				$('.js-for-uploaded').show(0);
				$('.js-for-generated').hide(0);
				$('.js-template-row').hide(0);
			} else if (value == 'generated') {
				$('.jsAddOLTypeLabel_signature').text('Signature Required');
				$('.jsAddOLTypeHint').text('Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.');
				$('.js-for-generated').show(0);
				$('.js-for-uploaded').hide(0);
				$('.js-template-row').hide(0);
			} else if (value == 'template') {
				$('.jsAddOLTypeLabel_signature').text('Signature Required');
				$('.jsAddOLTypeHint').text('Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.');
				$('.js-template-row').show(0);
			} else if (value == 'hybrid_document') {
				$('.jsAddOLTypeLabel_signature').text('Signature Required');
				$('.jsAddOLTypeHint').text('Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.');
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
		) {
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
			rows += '<div class="modal fade js-page-partial" id="' + (sid) + '">';
			rows += '	<div class="modal-dialog modal-lg">';
			rows += '		<!-- loader --><div class="loader jsModifyModalLoader ' + (sid) + '-loader"><i class="fa fa-spinner fa-spin"></i></div>';
			rows += '		<div class="modal-content">';
			rows += '			<div class="modal-header modal-header-bg">';
			rows += '				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			rows += '				<h4 class="modal-title">' + (title) + '</h4>';
			rows += '			</div>';
			rows += '			<div class="modal-body">';
			rows += contents;
			rows += '			</div>';
			rows += '			<div class="modal-footer">';
			rows += '				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
			rows += footerButtons;
			rows += '			</div>';
			rows += '		</div>';
			rows += '	</div>';
			rows += '</div>';
			//
			$('#' + sid).remove();
			//
			$('body').append(rows);
			//
			$('#' + sid).modal({
				backdrop: 'static',
				keyboard: false
			});
			//
			$('.jsModifyModalLoader').show();
			//
			var allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'];
			//
			if (modelFor == 'offer_letter') {
				allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
			}
			//
			$('#upload_document').mFileUploader({
				fileLimit: '2MB', // Default is '2MB', Use -1 for no limit (Optional)
				allowedTypes: allowedTypes, //(Optional)
				text: 'Click / Drag to upload', // (Optional)
				onSuccess: (file, event) => {}, // file will the uploaded file object and event will be the actual event  (Optional)
				onError: (errorCode, event) => {}, // errorCode will either 'size' or 'type' and event will be the actual event  (Optional)
				placeholderImage: '' // Default is empty ('') but can be set any image  (Optional)
			});
			//
			if (cks.length !== 0) $.each(cks, function(index, val) {
				CKEDITOR.replace(val);
			});
			if (sels.length !== 0) $.each(sels, function(index, val) {
				$(val).select2({
					closeOnSelect: $(val).prop('multiple') ? false : true
				});
			});

			if (cb !== undefined) cb();
		}

		// Helping functions
		// Create unique ID
		function uuidv4() {
			return 'xxxxxxxx-xx'.replace(/[xy]/g, function(c) {
				var r = Math.random() * 16 | 0,
					v = c == 'x' ? r : (r & 0x3 | 0x8);
				return v.toString(16);
			});
		}

		//
		function getOfferLetter(
			sid
		) {
			let
				i = 0,
				il = offerLetters.length;
			//
			if (il == 0) return {};
			//
			for (i; i < il; i++) {
				if (offerLetters[i]['sid'] == sid) return offerLetters[i];
			}
			return {};
		}

		// Copy text to clipboard
		$(document).on('click', '.has-copy', function(e) {
			//
			var copyToClipboard = str => {
				const el = document.createElement('textarea'); // Create a <textarea> element
				el.value = str; // Set its value to the string that you want copied
				el.setAttribute('readonly', ''); // Make it readonly to be tamper-proof
				el.style.position = 'absolute';
				el.style.left = '-9999px'; // Move outside the screen to make it invisible
				document.body.appendChild(el); // Append the <textarea> element to the HTML document
				const selected =
					document.getSelection().rangeCount > 0 // Check if there is any content selected previously
					?
					document.getSelection().getRangeAt(0) // Store selection if found
					:
					false; // Mark as false to know no selection existed before
				el.select(); // Select the <textarea> content
				document.execCommand('copy'); // Copy - only works as a result of a user action (e.g. click events)
				document.body.removeChild(el); // Remove the <textarea> element
				if (selected) { // If a selection existed before copying
					document.getSelection().removeAllRanges(); // Unselect everything on the HTML document
					document.getSelection().addRange(selected); // Restore the original selection
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
		) {
			//
			type = type === undefined ? '.js-inner-loader-letter' : type + '-loader';
			//
			switch (sh) {
				case 'hide':
				case false:
				case 'false':
					$(type).fadeOut(300);
					break;
				default:
					$(type).fadeIn(300);
					break;
			}
		}


		// 
		function getRequiredRow() {
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
		function getSignatureRequiredRow() {
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

		function getSettings() {
			return `<?php $this->load->view('hr_documents_management/partials/settings'); ?>`;
		}

		// I9 manage
		$('.jsManageI9').click(function(event) {
			//
			event.preventDefault();
			//
			var i9 = <?php echo !empty($i9_form) ? json_encode($i9_form) : (!empty($i9_form_data) ? json_encode($i9_form_data) : json_encode(['is_required' => 0, 'is_signature_required' => 0])); ?>;
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
				function() {
					//
					iLoader('hide', '.js-i9-edit');
				}
			);
		});

		//
		$(document).on('click', '#js-update-i9-settings', function(event) {
			//
			event.preventDefault();
			//
			var i9 = <?php echo !empty($i9_form) ? json_encode($i9_form) : (!empty($i9_form_data) ? json_encode($i9_form_data) : json_encode(['is_required' => 0, 'is_signature_required' => 0])); ?>;
			//
			var o = {};
			o.id = i9.sid;
			o.isRequired = $('.js-i9-required:checked').val();
			o.isSignatureRequired = $('.js-i9-signature-required:checked').val();
			o.formType = "i9";
			//
			iLoader('show', '.js-i9-edit');
			//
			$.post("<?php echo base_url('hr_documents_management/update_form_settings'); ?>", o)
				.done(function(resp) {
					//
					iLoader('hide', '.js-i9-edit');
					//
					if (resp == 'success') {
						alertify.alert('SUCCESS!', 'You have successfully updated the I9 form settings.', function() {
							window.location.reload();
						});
					}
				});
		});


		// W9 manage
		$('.jsManageW9').click(function(event) {
			//
			event.preventDefault();
			//
			var w9 = <?php echo !empty($w9_form) ? json_encode($w9_form) : (!empty($w9_form) ? json_encode($w9_form) : json_encode(['is_required' => 0, 'is_signature_required' => 0])); ?>;
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
				function() {
					//
					iLoader('hide', '.js-w9-edit');
				}
			);
		});

		//
		$(document).on('click', '#js-update-w9-settings', function(event) {
			//
			event.preventDefault();
			//
			var w9 = <?php echo !empty($w9_form) ? json_encode($w9_form) : (!empty($w9_form) ? json_encode($w9_form) : json_encode(['is_required' => 0, 'is_signature_required' => 0])); ?>;
			//
			var o = {};
			o.id = w9.sid;
			o.isRequired = $('.js-w9-required:checked').val();
			o.isSignatureRequired = $('.js-w9-signature-required:checked').val();
			o.formType = "w9";
			//
			iLoader('show', '.js-w9-edit');
			//
			$.post("<?php echo base_url('hr_documents_management/update_form_settings'); ?>", o)
				.done(function(resp) {
					//
					iLoader('hide', '.js-w9-edit');
					//
					if (resp == 'success') {
						alertify.alert('SUCCESS!', 'You have successfully updated the w9 form settings.', function() {
							window.location.reload();
						});
					}
				});
		});


		// W4 manage
		$('.jsManageW4').click(function(event) {
			//
			event.preventDefault();
			//
			var w4 = <?php echo !empty($w4_form) ? json_encode($w4_form) : (!empty($w4_form) ? json_encode($w4_form) : json_encode(['is_required' => 0, 'is_signature_required' => 0])); ?>;
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
				function() {
					//
					iLoader('hide', '.js-w4-edit');
				}
			);
		});

		//
		$(document).on('click', '#js-update-w4-settings', function(event) {
			//
			event.preventDefault();
			//
			var w4 = <?php echo !empty($w4_form) ? json_encode($w4_form) : (!empty($w4_form) ? json_encode($w4_form) : json_encode(['is_required' => 0, 'is_signature_required' => 0])); ?>;
			//
			var o = {};
			o.id = w4.sid;
			o.isRequired = $('.js-w4-required:checked').val();
			o.isSignatureRequired = $('.js-w4-signature-required:checked').val();
			o.formType = "w4";
			//
			iLoader('show', '.js-w4-edit');
			//
			$.post("<?php echo base_url('hr_documents_management/update_form_settings'); ?>", o)
				.done(function(resp) {
					//
					iLoader('hide', '.js-w4-edit');
					//
					if (resp == 'success') {
						alertify.alert('SUCCESS!', 'You have successfully updated the w4 form settings.', function() {
							window.location.reload();
						});
					}
				});
		});

		//
		function getSettingBody(key, o) {
			//
			html = '';
			html += '<div class="row">';
			html += '	<div class="col-sm-12">';
			html += '		<div class="form-group">';
			html += '			<label>Is this document required?</label>';
			html += '			<p class="help-text">If marked yes, then the applicant needs to complete this document to complete the onboarding process.</p>';
			html += '			<label class="control control--radio">';
			html += '				<input type="radio" class="js-' + (key) + '-required" name="js-' + (key) + '-required" ' + (o.isRequired == 0 ? 'checked' : '') + ' value="0" /> No &nbsp;';
			html += '				<div class="control__indicator"></div>';
			html += '			</label>';
			html += '			<label class="control control--radio">';
			html += '				<input type="radio"  class="js-' + (key) + '-required" name="js-' + (key) + '-required" ' + (o.isRequired == 1 ? 'checked' : '') + ' value="1" /> Yes &nbsp;';
			html += '				<div class="control__indicator"></div>';
			html += '			</label>';
			html += '		</div>';
			html += '		<div class="form-group hidden">';
			html += '			<label>Is signature required?</label>';
			html += '			<p class="help-text"><p class="help-text">If marked yes, then the applicant needs to add e-sign this document to complete the onboarding process.</p>';
			html += '			<label class="control control--radio">';
			html += '				<input type="radio" class="js-' + (key) + '-signature-required" name="js-' + (key) + '-signature-required" ' + (o.isSignatureRequired == 0 ? 'checked' : '') + ' value="0" /> No &nbsp;';
			html += '				<div class="control__indicator"></div>';
			html += '			</label>';
			html += '			<label class="control control--radio">';
			html += '				<input type="radio"  class="js-' + (key) + '-signature-required" name="js-' + (key) + '-signature-required" ' + (o.isSignatureRequired == 1 ? 'checked' : '') + ' value="1" /> Yes &nbsp;';
			html += '				<div class="control__indicator"></div>';
			html += '			</label>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';
			//
			return html;
		}
	});




	//

	function getFillableDocumentContent(fillableSlug) {
		var rows = '';

		return new Promise(function(resolve) {
			// push the file to server
			$.ajax({
					url: '<?php echo base_url('v1/fillable_documents/previeFillableAjax/'); ?>' + fillableSlug,
					method: "GET",
				})
				.success(function(response) {
					resolve({
						'key': response
					});
					//resolve(response);
				})
				.fail(function() {
					resolve({});
				});
		});

	}
</script>

<style>
	#modify-assign-document-modal .select2-container--default .select2-selection--multiple .select2-selection__rendered {
		height: auto !important;
	}
</style>