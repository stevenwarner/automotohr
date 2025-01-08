<style>
	.cs-copy{ position: absolute; right: 24px; top: 10px; display: none; }
	.has-copy input { cursor: pointer; }
	.has-copy:hover .cs-copy { display: block; }
	#js-d-loader{ z-index: 9999 !important; }
</style>

<?php 
	// Any change on this file will reflect
	// in employee/applicant document center,
	// onboarding process, manage EMS, EMS.
	// Only entertain Hybrid documents and 
	// hybrid offer letters

	// Setting defaults if not set
	if(!isset($all_documents)) $all_documents = [];
	if(!isset($offer_letters)) $offer_letters = [];
	if(!isset($company_offer_letters)) $company_offer_letters = [];
	if(!isset($assigned_documents)) $assigned_documents = [];
	if(!isset($assigned_offer_letters)) $assigned_offer_letters = [];
	if(!isset($uncompleted_offer_letter)) $uncompleted_offer_letter = [];
	if(!isset($uncompleted_payroll_documents)) $uncompleted_payroll_documents = [];
	if(!isset($assigned_documents_history)) $assigned_documents_history = [];
	if(!isset($assigned_offer_letter_history)) $assigned_offer_letter_history = [];
	// Fetching arrays from partial views
	if(isset($GLOBALS['ad'])) $assigned_documents = array_unique( array_merge( $assigned_documents, $GLOBALS['ad'] ), SORT_REGULAR );
	if(isset($GLOBALS['uofl'])) $assigned_offer_letters = array_merge( $assigned_offer_letters, $GLOBALS['uofl'] );
	if(isset($all_uploaded_generated_doc)) $all_documents = array_merge($all_documents, $all_uploaded_generated_doc);
	// Merging all assigned/submitted offer letters
	$assigned_offer_letters = array_unique( array_merge( $assigned_offer_letters, $uncompleted_offer_letter ), SORT_REGULAR );
	$offer_letters = array_unique( array_merge( $offer_letters, $company_offer_letters ), SORT_REGULAR );
	// Original Data
	if(sizeof($all_documents)) foreach ($all_documents as $k => $v) $all_documents[$k]['document_description'] = html_entity_decode($v['document_description']); 
	if(sizeof($offer_letters)) foreach ($offer_letters as $k => $v) $offer_letters[$k]['letter_body'] = html_entity_decode($v['letter_body']); 
	// Assigned/Submitted Data
	if(sizeof($assigned_documents)) {
		foreach ($assigned_documents as $k => $v) {
			$body = $v['document_description'];
			$isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}|{{authorized_editable_date}}/i', $body);
			$assigned_documents[$k]['document_description'] =  html_entity_decode(getGeneratedDocumentURL($v, "uncompleted", $isAuthorized)['html_body']);
			if ($v['user_consent'] == 1) {
				$assigned_documents[$k]['submitted_document_description'] =  html_entity_decode(getGeneratedDocumentURL($v, "completed", $isAuthorized)['html_body']);
			}
			
		}
	}
	// if(sizeof($assigned_documents)) foreach ($assigned_documents as $k => $v) $assigned_documents[$k]['document_description'] = html_entity_decode($v['document_description']); 
	if(sizeof($assigned_documents_history)) foreach ($assigned_documents_history as $k => $v) $assigned_documents_history[$k]['document_description'] = html_entity_decode($v['document_description']); 
	if(sizeof($assigned_offer_letters)) foreach ($assigned_offer_letters as $k => $v) $assigned_offer_letters[$k]['document_description'] = html_entity_decode($v['document_description']); 
	if(sizeof($assigned_offer_letter_history)) foreach ($assigned_offer_letter_history as $k => $v) $assigned_offer_letter_history[$k]['document_description'] = html_entity_decode($v['document_description']); 
?>

<script>
	
	$(function(){

		window.getUploadedFileAPIUrl = getUploadedFileAPIUrl;
		// Set all documents
		var 
		holder = {
			'document': {
				'original': <?=json_encode(array_values($all_documents));?>,
				'assigned_history': <?=json_encode(array_values($assigned_documents_history));?>,
				'assigned': <?=json_encode(
					array_values(
						$assigned_documents
					)
				);?>,
				'submitted': []
			},
			'offer_letter' : {
				'original': <?=json_encode(array_values($offer_letters));?>,
				'assigned': <?=json_encode(array_values($assigned_offer_letters));?>,
				'assigned_history': <?=json_encode(array_values($assigned_offer_letter_history));?>,
				'submitted': []
			}
		};
		//
		holder.document.submitted = holder.document.assigned;
		holder.offer_letter.submitted = holder.offer_letter.assigned;
		//
		window.holder = holder;
		//
		var activeDocument = {};

		if(window.hasOwnProperty('Selectize')){
			//
			var upemployees = $('#js-employees-modal').selectize({
		        plugins: ['remove_button'],
		        delimiter: ',',
		        allowEmptyOption:false,
		        persist: true,
		        create: false
		    });
			
			//
			var upexecutives = $('#js-executives-modal').selectize({
		        plugins: ['remove_button'],
		        delimiter: ',',
		        allowEmptyOption:false,
		        persist: true,
		        create: false
		    });

			//
		    var updepartments = $('#js-departments-modal').selectize({
		        plugins: ['remove_button'],
		        delimiter: ',',
		        allowEmptyOption:false,
		        persist: true,
		        create: false
		    });

		    var up_emp = upemployees[0].selectize;
		    var up_executives = upexecutives[0].selectize;
		    var up_dept = updepartments[0].selectize;
		}
		//
		CKEDITOR.replace('js-description-modal');

		// Bulk assign document trigger
		$(document).on('click', '.js-bulk-assign-btn', bulkAssignHybridDocument);
		// Bulk assign document trigger
		$(document).on('click', '.js-hybrid-preview', previewHybridDocument);

		// Copy magic quotes
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
		$('.js-toggle-modal-btn').click(function(e) {
			//
			e.preventDefault();
			//
			if($(this).hasClass('fa-minus')){
				$(this).removeClass('fa-minus').addClass('fa-plus');
				$(this).parent().parent().parent().find('.panel-body').hide();
			} else{
				$(this).removeClass('fa-plus').addClass('fa-minus');
				$(this).parent().parent().parent().find('.panel-body').show();
			}
		});

		//
		$(document).on('click', '.js-assign-type-modal', function(){
	        if($(this).val() == 'department'){
	            $('.js-employee-box-modal').hide();
	            $('.js-department-box-modal').show();
	            $('.js-executives-box-modal').hide();
	        } else if($(this).val() == 'executives'){
				$('.js-employee-box-modal').hide();
	            $('.js-department-box-modal').hide();
	            $('.js-executives-box-modal').show();
	        } else{
				$('.js-department-box-modal').hide();
	            $('.js-executives-box-modal').hide();
	            $('.js-employee-box-modal').show();
	        }
	    });

	    //
	    $('#js-preview-hybrid-modal').on('modal.bs.hide', function(){
	    	$('.js-preview-hyrid-modal-title').html('');
			$('.js-uploaded-document .js-text').text('');
			$('.js-uploaded-document .js-button').html('');
			$('.js-uploaded-document .panel-body').hide();
			$('.js-uploaded-document .js-toggle-modal-btn').removeClass('fa-plus').addClass('fa-minus');
			$('.js-generated-document .js-button').html('');
			$('.js-generated-document .panel-body').hide();
			$('.js-generated-document .js-toggle-modal-btn').removeClass('fa-minus').addClass('fa-plus');
	    });

	    // Submit Form
	    $('#js-bulk-assign-btn-modal').click(assignBulkDocument);

		// Bulk assign document
		function bulkAssignHybridDocument(
			e
		){
			//
			e.preventDefault();
			//
			var 
			status = $(this).data('document') == undefined ? 'original' : $(this).data('document'),
			type = $(this).data('type') == undefined ? 'document' : $(this).data('type') ;
			// 
			var d = getDocument( $(this).data('id'), 'sid', type, status );
			//
			activeDocument = d;
			// Reset modal
	        $('.js-department-box-modal').hide();
	        $('.js-executives-box-modal').hide();
	        $('.js-employee-box-modal').show();
	        $('.js-assign-type-modal[value="employee"]').prop('checked', true);
	        $('.js-notification-email-hybrid[value="yes"]').prop('checked', true);
	        //
	        $('.js-bulk-assign-title-modal').html('Modify and Bulk Assign This Document');
	        $('.js-document-label-modal').html("Are you sure you want to bulk assign this document: [<b>"+( d.document_title )+ "</b>]?");
	        //
	        CKEDITOR.instances['js-description-modal'].setData(
	        	type == 'document' ? d.document_description : d.letter_body
	        );
	        //
	        $('#js-bulk-assign-hybrid-modal').modal('toggle');
	        //
	        $.ajax({
	            type:'POST',
	            url: '<?= base_url('hr_documents_management/get_document_employees')?>',
	            data: {
	                doc_sid: $(this).data('id'),
	                doc_type: 'hybrid_document'
	            },
	            success: function (data) {
	                // var employees = JSON.parse(data);
	                var employees = data.Employees;
	                var executives = data.Executives;
	                var departments = data.Departments;
	                if(employees.length == 0){
	                    up_emp.clearOptions();
	                    up_emp.load(function(callback) {
	                        var arr = [{}];
	                        arr[0] = {
	                            value: '',
	                            text: 'Please Select Employee'
	                        }
	                        callback(arr);
	                        up_emp.addItems('');
	                        up_emp.refreshItems();
	                    });
	                    $('#js-bulk-assign-msg-modal').show();
	                    $('#js-bulk-assign-btn-modal').hide();
	                    up_emp.disable();
	                } else{
	                    $('#js-bulk-assign-msg-modal').hide();
	                    $('#js-bulk-assign-btn-modal').show();
	                    up_emp.enable();
	                    up_emp.clearOptions();
	                    up_emp.load(function(callback) {

	                        var arr = [{}];
	                        var j = 0;

	                        arr[j++] = {
	                            value: -1,
	                            text: 'All'
	                        };

	                        for (var i = 0; i < employees.length; i++) {
	                            arr[j++] = {
	                                value: employees[i].sid,
	                                text: (employees[i].first_name + ' ' + employees[i].last_name)+( employees[i].job_title != '' && employees[i].job_title != null ? ' ('+employees[i].job_title+')' : ''  )+ ' ['+ remakeAccessLevel(employees[i])+']'
	                            }
	                        }

	                        callback(arr);
	                        up_emp.refreshItems();
	                    });
	                }
					
					if(executives.length == 0){
	                    up_executives.clearOptions();
	                    up_executives.load(function(callback) {
	                        var arr = [{}];
	                        arr[0] = {
	                            value: '',
	                            text: 'Please Select Executive'
	                        }
	                        callback(arr);
	                        up_executives.addItems('');
	                        up_executives.refreshItems();
	                    });
	                    // $('#js-bulk-assign-msg-modal').show();
	                    // $('#js-bulk-assign-btn-modal').hide();
	                    up_executives.disable();
	                } else{
	                    // $('#js-bulk-assign-msg-modal').hide();
	                    // $('#js-bulk-assign-btn-modal').show();
	                    up_executives.enable();
	                    up_executives.clearOptions();
	                    up_executives.load(function(callback) {

	                        var arr = [{}];
	                        var j = 0;

	                        arr[j++] = {
	                            value: -1,
	                            text: 'All'
	                        };

	                        for (var i = 0; i < executives.length; i++) {
	                            arr[j++] = {
	                                value: executives[i].sid,
	                                text: (executives[i].first_name + ' ' + executives[i].last_name)+( executives[i].job_title != '' && executives[i].job_title != null ? ' ('+executives[i].job_title+')' : ''  )+ ' ['+ remakeAccessLevel(executives[i])+']'
	                            }
	                        }

	                        callback(arr);
	                        up_executives.refreshItems();
	                    });
	                }

	                if(departments.length == 0 || employees.length == 0 || executives.length === 0){
	                    up_dept.clearOptions();
	                    up_dept.load(function(callback) {
	                        var arr = [{}];
	                        arr[0] = {
	                            value: '',
	                            text: 'Please Select a Department'
	                        }
	                        callback(arr);
	                        up_dept.addItems('');
	                        up_dept.refreshItems();
	                    });
	                    // $('#js-bulk-assign-msg-modal').show();
	                    // $('#js-bulk-assign-btn-modal').hide();
	                    up_dept.disable();
	                } else{
	                    // $('#js-bulk-assign-msg-modal').hide();
	                    // $('#js-bulk-assign-btn-modal').show();
	                    up_dept.enable();
	                    up_dept.clearOptions();
	                    up_dept.load(function(callback) {

	                        var arr = [{}];
	                        var j = 0;

	                        arr[j++] = {
	                            value: -1,
	                            text: 'All'
	                        };

	                        for (var i = 0; i < departments.length; i++) {
	                            arr[j++] = {
	                                value: departments[i].sid,
	                                text: departments[i].name
	                            }
	                        }

	                        callback(arr);
	                        up_dept.refreshItems();
	                    });
	                }
	            },
	            error: function () {

	            }
	        });
		}

		// Assign bulk document
		function assignBulkDocument(
			e
		){
			//
			e.preventDefault();
			//
			var o = {};
			o.action = 'bulk_assign';
            o.departments = up_dept.getValue();
			o.documentSid = activeDocument.sid;
			o.description = CKEDITOR.instances['js-description-modal'].getData();
			o.employees = up_emp.getValue();
			o.executives = up_executives.getValue();
			o.auth_sign_sid = 0;
			o.assign_type = $('.js-assign-type-modal:checked').val();
			o.sendEmails = $('.js-notification-email-hybrid:checked').val();
			o.tyoe = activeDocument.document_description === undefined ? 'offer_letter' : 'document';

			// For offer letter
			o.documentType = 'hybrid_document';
			o.userType = 'employee';

            if($('#js-bulk-assign-hybrid-modal').find('.js-assign-type-modal:checked').val() == 'department'){
                if(o.departments.length == 0){
                    alertify.alert('ERROR!', 'Please select atleast one department.');
                    return;
                }
            } else if($('#js-bulk-assign-hybrid-modal').find('.js-assign-type-modal:checked').val() == 'executives'){
                if(o.executives.length == 0){
                    alertify.alert('ERROR!', 'Please select atleast one executive.');
                    return;
                }
            }else{
                if(o.employees.length ==  0){
                    alertify.alert('ERROR!', 'Please select atleast one employee.');
                    return;
                }
            }
            //
            loader('show');
            // Create a AJAX request
            $.post("<?=base_url('hr_documents_management/handler');?>",
            	o, 
            	function(resp) {
            		//
            		loader();
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


            return;
		}

		// Show hybrid document
		function previewHybridDocument(
			e
		){
			//
			var 
			status = $(this).data('document') == undefined ? 'original' : $(this).data('document'),
			type = $(this).data('type') == undefined ? 'document' : $(this).data('type') ;
			from = $(this).data('from') == undefined ? 'assigned' : $(this).data('from') ;
			//
			$('#js-preview-hybrid-modal .js-generated-document .js-toggle-modal-btn').removeClass('fa-minus').addClass('fa-plus');
			// $('#js-preview-hybrid-modal .js-generated-document .panel-body').hide(0);
			//
			$('#js-preview-hybrid-modal .js-uploaded-document .js-toggle-modal-btn').removeClass('fa-minus').addClass('fa-plus');
			// $('#js-preview-hybrid-modal .js-uploaded-document .panel-body').hide(0);
			//
			var d = getDocument( 
				$(this).data('id'), 
				'sid', 
				type,
				status
			);
			//
			// Set 
			var s3_file = d.uploaded_file !== undefined && d.uploaded_file !== null ? d.uploaded_file : ( d.uploaded_document_s3_name !== undefined && d.uploaded_document_s3_name !== null ? d.uploaded_document_s3_name : d.document_s3_name );
			//
			if(s3_file == null) return;
			//
			$('#js-preview-hybrid-modal .js-preview-hyrid-modal-title').html( (d.letter_name !== undefined ? d.letter_name : d.document_title ) + ' <strong>(Hybrid Document)</strong>');
			//
			var f = getUploadedFileAPIUrl(
				s3_file,
				status
			);
			//
			//
			var g = getGeneratedOBJ(
				d,
				status,
				type
			);

			//
			var buttons = '';
			buttons += '<a href="<?=base_url('hr_documents_management/pd');?>/'+( status )+'/print/'+( d.sid )+'" target="_blank" class="btn btn-success">Print</a>';
			buttons += '<a href="<?=base_url('hr_documents_management/pd');?>/'+( status )+'/download/'+( d.sid )+'" class="btn btn-success">Download</a>';
			//
			$('.js-preview-hyrid-modal-title').html(d.document_title);
			// File section
			$('.js-uploaded-document .js-text').html('Document '+s3_file);
			// $('.js-uploaded-document .js-button').html( f.getButtonHTML() );
			$('.js-uploaded-document .panel-body').html( f.getHTML() );
			// Content section
			// if(status == 'submitted'){
			// 	//
			// 	// var g = getUploadedFileAPIUrl(
			// 	// 	d.submitted_description,
			// 	// 	status
			// 	// );
			// 	// g = '<iframe src="'+( d.submitted_description )+'" frameborder="0" style="width: 100%; height: 500px;"></iframe>';
			// 	//
			// 	buttons = '';
			// 	// buttons += '<a href="<?=base_url('hr_documents_management/pd');?>/'+( status )+'/print/description/'+( d.sid )+'" target="_blank" class="btn btn-success btn-sm">Print</a>&nbsp;';
			// 	// buttons += '<a href="<?=base_url('hr_documents_management/pd');?>/'+( status )+'/download/description/'+( d.sid )+'" target="_blank"  class="btn btn-success btn-sm">Download</a>';
			// 	//
			// 	// $('.js-generated-document .panel-body').html( g );
			// 	// $('.js-generated-document .js-button').html( buttons );
			// } else{ 
			// 	// $('.js-generated-document .panel-body').html( g.getHTML() );
			// 	// $('.js-generated-document .js-button').html( g.getButtonHTML() );
			// }
			$('.js-generated-document .panel-body').html( g.getHTML() );
			//
			$('.js-generated-document .js-text').html('<strong>Section 2: </strong> Description');
			// Full Download
			// $('#js-preview-hybrid-modal .modal-footer').prepend( buttons );
			//
			$('#js-preview-hybrid-modal').modal();
			//
			if(f.Type == 'iframe') loadIframe( f.URL, '.js-uploaded-document iframe', true);

			if (from == "company") {
				print_url = "<?=base_url('hr_documents_management/print_download_hybird_document/original/print/both');?>/"+( d.sid );
				download_url = "<?=base_url('hr_documents_management/print_download_hybird_document/original/print/both');?>/"+( d.sid );
			} else if (from == "company") {
				print_url = "<?=base_url('hr_documents_management/perform_action_on_document_content_new');?>/"+( d.sid )+"/"+( status )+"/company_offer_letter/print";
				download_url = "<?=base_url('hr_documents_management/perform_action_on_document_content_new');?>/"+( d.sid )+"/"+( status )+"/company_offer_letter/download";	
			} else {
				if(status == 'submitted'){
					print_url = "<?=base_url('hr_documents_management/print_download_hybird_document/submitted/print/both');?>/"+( d.sid );
					download_url = "<?=base_url('hr_documents_management/print_download_hybird_document/submitted/print/both');?>/"+( d.sid );
				} else {
					print_url = "<?=base_url('hr_documents_management/print_download_hybird_document/assigned/print/both');?>/"+( d.sid );
					download_url = "<?=base_url('hr_documents_management/print_download_hybird_document/assigned/print/both');?>/"+( d.sid );
				}
					
			}
			
			$("#hybrid_print_doc").attr("href",print_url)
			$("#hybrid_download_doc").attr("href",download_url)
			if(status == 'submitted'){
				$('#hybird_uploaded_doc').hide();
			}
		}


		// Helpers

		//
		function loader(showIt){
			if(showIt == 'show') $('#js-d-loader').fadeIn(300);
			else $('#js-d-loader').fadeOut(300);
		}

		// Get document
		function getDocument(
			matchValue,
			searchIndex,
			status,
			type
		){
			// Check and set search index
			searchIndex = searchIndex === undefined ? 'sid' : searchIndex;
			// Declare default values
			var 
			arr = holder[status][type],
			i = 0,
			il = arr.length;
			// Loop through documents array to find document
			for (i; i < il; i++) if(arr[i][searchIndex] == matchValue) return arr[i];
			// Return empty array if nothing is found
			return [];
		}

		// Logger
		function _e(
			m,
			s
		){
			if(s !== undefined) console.log('--------------------');
			console.log(m);
			if(s !== undefined) console.log('--------------------');
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

		//
		function getGeneratedOBJ(
			d,
			s,
			t
		){
			//
			var r = {};
			//
			r.PrintURL = '<?=base_url('hr_documents_management/pd');?>/'+( s )+'/print/description/' + d.sid+'/'+t;
			r.DownloadURL = '<?=base_url('hr_documents_management/pd');?>/'+( s )+'/download/description/' + d.sid+'/'+t;
			//
			r.getPrintHTML = () => '<a href="'+( r.PrintURL )+'" target="_blank" class="btn btn-success btn-sm">Print</a>';
			r.getDownloadHTML = () => '<a href="'+( r.DownloadURL )+'" target="_blank" class="btn btn-success btn-sm">Download</a>';
			r.getButtonHTML = () => r.getPrintHTML() +' &nbsp; '+ r.getDownloadHTML();
			//
			var documentBody = d.document_description;
			if (s == 'submitted') {
				documentBody = d.submitted_document_description;
			}
			r.getHTML = () => `
			<div class="row">
				<div class="col-sm-12">
					<div style="padding: 30px 50px; background-color: lightgrey; height: 600px; overflow-x: hidden; overflow-y: scroll;">
						<div style="padding: 20px; background-color: white; min-height: 900px;">
							${documentBody == undefined ? d.letter_body : documentBody}
						</div>
					</div>
				</div>
			</div>`;
			//
			return r;
		}
});

</script>

<!-- Bulk Assign Modal for Hybrid Documents -->
<div id="js-bulk-assign-hybrid-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title js-bulk-assign-title-modal">Bulk Assign This Document</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form action="javascript:void(0)">
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <h4 id="js-document-label-modal"></h4>
                                <b>Please review this document and make any necessary modifications. Modifications will not affect the Original Document.</b> <!--<br>The Modified document will only be sent to the <?= ucwords($user_type); ?> it was assigned to.-->
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <label>Document Description<span class="hr-required red"> * </span></label>
                                <textarea required style="padding:5px; height:200px; width:100%;" class="" id="js-description-modal" name="document_description"></textarea>
                            </div>
                        </div>
                        <!-- Department/Employee Check -->
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <h4>Assign document to </h4>
                                <label class="control control--radio">
                                    <input type="radio" name="assign_type" value="employee" class="js-assign-type-modal" /> Employee
                                    <div class="control__indicator"></div>
                                </label>
								<label class="control control--radio">
                                    <input type="radio" name="assign_type" value="executives" class="js-assign-type-modal" /> Executives
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="assign_type" value="department" class="js-assign-type-modal" /> Department &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 js-department-box-modal">
                            <div class="form-group full-width">
                                <label>Departments <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="departments[]" id="js-departments-modal" required>
                                        <option value="" selected>Please Select a Department</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12 js-employee-box-modal">
                            <div class="form-group full-width">
                                <label>Employees <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="employees[]" id="js-employees-modal" required>
                                        <option value="" selected>Please Select Employee</option>
                                    </select>
                                </div>

                            </div>
                        </div>
						<div class="col-lg-12 js-executives-box-modal">
                            <div class="form-group full-width">
                                <label>Executives <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="executives[]" id="js-executives-modal" required>
                                        <option value="" selected>Please Select Executives</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <!-- Department/Employee Check -->
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <h4>Send notification emails? </h4>
                                <label class="control control--radio">
                                    <input type="radio" name="notification_email" value="yes" class="js-notification-email-hybrid" /> Yes &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="notification_email" value="no" checked="true" class="js-notification-email-hybrid" /> No &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12" id="empty-emp" style="display: none;">
                            <span class="hr-required red">This Document Is Assigned To All Employees!</span>
                        </div>
                        <div class="col-lg-12">
                            <div class="offer-letter-help-widget full-width form-group">
                                <div class="how-it-works-insturction">
                                    <strong>How it Works :</strong>
                                    <p class="how-works-attr">1. Generate a new Electronic Document</p>
                                    <p class="how-works-attr">2. Enable a Document Acknowledgment</p>
                                    <p class="how-works-attr">3. Enable an Electronic Signature</p>
                                    <p class="how-works-attr">4. Insert multiple tags where applicable</p>
                                    <p class="how-works-attr">5. Save the Document</p>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Company Information Tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{company_name}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{company_address}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{company_phone}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{career_site_url}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Employee / Applicant Tags :</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{first_name}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{last_name}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{email}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{job_title}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Signature tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{signature}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{signature_print_name}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{inital}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{sign_date}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{authorized_signature}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{authorized_signature_date}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
									<div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{authorized_editable_date}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{text}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" title="Click to copy" readonly="" value="{{checkbox}}">
                                            <i class="fa fa-copy cs-copy js-copy" ></i>
                                        </div>
                                    </div>
									<div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" readonly="" value="{{short_text_required}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" readonly="" value="{{text_required}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" readonly="" value="{{text_area_required}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight has-copy">
                                            <input type="text" class="form-control tag" readonly="" value="{{checkbox_required}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Pay Plan / Offer Letter tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{flat_rate_technician}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" id="js-bulk-assign-msg-modal" style="display: none;"><span class="hr-required red">This Document Is Assigned To All Employees!</span></div>
                        <div class="col-md-12">
                            <div class="message-action-btn">
                                <input type="button" value="Bulk Assign This Document" id="js-bulk-assign-btn-modal" class="submit-btn"  value="Bulk Assign This Document`"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<!-- Preview Modal for Hybrid Documents -->
<div class="modal fade" id="js-preview-hybrid-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header modal-header-bg">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title js-preview-hyrid-modal-title">Document Name</h4>
			</div>
			<div class="modal-body js-preview-hyrid-modal-body">

				<!-- <p>
					<strong>Note:</strong>
					Hybrid document is divided into two sections; <br />
					1- Uploaded document <br />
					2- Description
				</p> -->
				<div>
					<a class="btn btn-success pull-right" id="hybrid_download_doc" target="_blank">Download</a>
					<a class="btn btn-success pull-right" style="margin-right: 6px;" id="hybrid_print_doc" target="_blank">Print</a>
				</div>
				<br>
				<hr>
				
				<div class="panel panel-success js-uploaded-document" id="hybird_uploaded_doc">
					<div class="panel-heading">
						<h3 class="panel-title">
							<!-- <i class="fa fa-minus js-toggle-modal-btn"></i> -->
							<span class="js-text"></span>
							<span class="pull-right js-button" style="margin-top: -7px;"></span>
						</h3>
					</div>
					<div class="panel-body"></div>
				</div>


				<div class="panel panel-success js-generated-document">
					<div class="panel-heading">
						<h3 class="panel-title">
							<!-- <i class="fa fa-plus js-toggle-modal-btn"></i> -->
							<span class="js-text">Description</span>
							<span class="pull-right js-button" style="margin-top: -7px;"></span>
						</h3>
					</div>
					<div class="panel-body" ></div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!--loader -->
<div id="js-d-loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are processing request
        </div>
    </div>
</div>