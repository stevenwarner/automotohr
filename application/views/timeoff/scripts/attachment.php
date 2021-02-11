// Modal Attachments Start
    // Document Attachment
    var allowedTypes = [
        'application/pdf',
        'image/png',
        'image/jpg',
        'image/jpeg',
        'pplication/msword'
    ],
    FMLACategories = {
        health: 'Certification of Health Care Provider for Employee’s Serious Health Condition',
        medical: 'Notice of Eligibility and Rights & Responsibilities',
        designation: 'Designation Notice'
    },
    localDocument = {},
    attachedDocuments = {},
    FMLA = {};
    // Document attachment timeoff
    $(document).on('click', '.js-timeoff-attachment', startAttachDocumentProcess);
    $(document).on('change', '#js-timeoff-document', verifyAndSaveDocumentLocally);
    $(document).on('click', '#js-save-attachment', verifyAndSaveDocument);
    $(document).on('change', '#js-timeoff-upload', verifyAndSaveFMLALocally);
    $(document).on('click', '#js-save-fmla', verifyAndSaveFMLADocument);
    $(document).on('click', '.js-attachment-delete', removeDocument);
    $(document).on('click', '.js-attachment-edit', editDocument);
    $(document).on('click', '.js-attachment-employer-section', employersSection);
    $(document).on('click', '.js-attachment-view', viewDocument);
    $(document).on('click', '.js-attachment-upload', uploadDocument);
    $(document).on('hidden.bs.modal', '#js-attachment-view-modal', function (e) { emt.modal.modal('show'); });
    $(document).on('hidden.bs.modal', '#js-timeoff-attachment-modal', function (e) { emt.modal.modal('show'); });
    $(document).on('hide.bs.modal', '#js-timeoff-attachment-es-modal', function(){ emt.modal.modal('show'); });
    $(document).on('hide.bs.modal', '#js-timeoff-attachment-upload-modal', function(){ emt.modal.modal('show'); });
    // Making sure modal is always scrollable
    $(document).on('show.bs.modal', function(){
        $('body').css('overflow-y', 'hidden');
        $('.modal').css('overflow-y', 'auto');
    });
    $(document).on('click', '#js-fmla-es-save', saveEmployerSection);
    //
    function startAttachDocumentProcess(e){
        localDocument = {};
        $('#js-timeoff-attachment-modal').remove();
        //
        var uploadForm = '';
        uploadForm += '<form>';
        uploadForm += '    <div class="form-group">';
        uploadForm += '        <label>Document Name <span class="cs-required">*</span></label>';
        uploadForm += '        <input type="text" class="form-control" required="true" id="js-timeoff-document-title"/>';
        uploadForm += '    </div>';
        uploadForm += '    <div class="form-group">';
        uploadForm += '        <label>Browse document <span class="cs-required">*</span></label>';
        uploadForm += '        <div class="upload-file invoice-fields">';
        uploadForm += '            <input style="height: 38px;" type="file" name="document" id="js-timeoff-document" required="true">';
        uploadForm += '             <p id="name_document"></p>';
        uploadForm += '            <a href="javascript:;" style="line-height: 38px; height: 38px;">Choose File</a>';
        uploadForm += '        </div>';
        uploadForm += '        <p>Allowed supporting document types .doc, .docx, .pdf, .png, .jpg, .jpeg</p>';
        uploadForm += '    </div>';
        uploadForm += '</form>';
        //
        var 
        rows = '<div class="modal fade" id="js-timeoff-attachment-modal">';
        rows +='    <div class="modal-dialog">';
        rows +='            <div class="modal-content">';
        rows +='                <div class="modal-header modal-header-bg">';
        rows +='                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        rows +='                    <h4 class="modal-title">Supporting Document</h4>';
        rows +='                </div>';
        rows +='                <div class="modal-body">';
        rows +=  uploadForm
        rows +='                </div>';
        rows +='                <div class="modal-footer">';
        rows +='                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        rows +='                    <button type="button" class="btn btn-success" id="js-save-attachment">Attach Document</button>';
        rows +='                    <button type="button" class="btn btn-success" id="js-save-placeholder-attachment" style="display: none;">Attaching Document...</button>';
        rows +='                </div>';
        rows +='            </div>';
        rows +='     </div>';
        rows +='</div>';
        //
        emt.modal.modal('hide');
        //
        $('body').append(rows);
        $('#js-timeoff-attachment-modal').modal();
    }
    //
    function verifyAndSaveDocumentLocally(e){
        var file = this.files[0];
        localDocument = {};
        //
        if($.inArray(file.type, allowedTypes) === -1){
            alertify.alert('ERROR!', 'Invalid format.', function(){ return; });
            return;
        }
        //
        $('#name_document').text(file.name);
        //
        if($('#js-timeoff-document-title').val().trim() == ''){
            $('#js-timeoff-document-title').val(
                cleanDocumentTitle(file.name)
            )
        }
        //
        localDocument = {
            id: getRandomId(),
            file: file,
            created_at: moment().format('MM-DD-YYYY'),
            type: 'uploaded',
            slug: file.name.replace(/[^0-9a-zA-Z.]/g, '_').toLowerCase(),
            title: $('#js-timeoff-document-title').val().trim()
        };
    }
    //
    function verifyAndSaveDocument(e){
        if(Object.keys(localDocument).length == 0 || $('#js-timeoff-document-title').val().trim() == ''){
            alertify.alert('ERROR!', 'All fields are mandatory.', function(){ return; });
            return;
        }
        //
        localDocument['title'] = $('#js-timeoff-document-title').val().trim();
        //
        $('#js-save-attachment').hide();
        $('#js-save-placeholder-attachment').show().css('opacity', '.6');
        // Make form Object
        var obj = new FormData();
        obj.append('action', 'attach_document_to_request');
        obj.append('requestSid', currentRequest.Info.requestId);
        obj.append('companySid', <?=$companyData['sid'];?>);
        obj.append('employeeSid', <?=$employerData['sid'];?>);
        obj.append('title', localDocument['title']);
        obj.append('attachment', localDocument['file']);
        //
        if($('#js-timeoff-attachment-modal').find('#js-file-id').length !== 0){
            obj.append('attachmentSid', $('#js-timeoff-attachment-modal').find('#js-file-id').val());
        }
        // Save document to request
        $.ajax({
            url: baseURI+'handler',
            processData: false,
            method: 'POST',
            contentType: false,
            data: obj
        }).done(function(resp){
            if(typeof(doReferesh) != 'undefined'){
                window.location.reload();
            }
            if(obj.has('attachmentSid') === false){
                $('.js-attachments-count').text(
                    parseInt($('.js-attachments-count').text()) + 1
                );
            }
            if(resp.S3_filename !== undefined)
            localDocument['s3_filename'] = resp.S3_filename;
            localDocument.id = resp.AttachmentSid;
            attachedDocuments[localDocument.id] = localDocument;
            // Set table
            setAttachmentTable();
        });
    }
    //
    function setAttachmentTable(){
        var target = $('#js-attachment-page').find('tbody');
        //
        $('.js-no-records').hide();
        $('.js-attachments').remove();
        //
        var rows = '';
        Object.keys(attachedDocuments).map(function(attachment){
            //
            let sd  = attachedDocuments[attachment].serialized_data;
            //
            if(typeof(attachedDocuments[attachment].serialized_data) === 'string'){
                //
                sd = JSON.parse(attachedDocuments[attachment].serialized_data);
            }

            rows += '<tr class="js-attachments" data-id="'+( attachedDocuments[attachment].id )+'">';
            rows += '   <td>'+( attachedDocuments[attachment].title )+'</td>';
            rows += '   <td>'+( attachedDocuments[attachment].type.ucwords() )+'</td>';
            rows += '   <td>'+( moment(attachedDocuments[attachment].created_at, 'MM-DD-YYYY').format('MMM DD YYYY, ddd') )+'</td>';
            rows += '   <td>';
            if(attachedDocuments[attachment].type == 'uploaded'){
                rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                rows += '       <button class="btn btn-success js-attachment-edit">Edit</button>';
                rows += '       <button class="btn btn-danger js-attachment-delete">Delete</button>';
            }else if(attachedDocuments[attachment].s3_filename != null){
                rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                rows += '       <button class="btn btn-success js-attachment-upload">Upload FMLA</button>';
            }else{
                rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                if(attachedDocuments[attachment].showEmployerSection){
                    if(sd.hasOwnProperty('employer') === false){
                        rows += '       <button class="btn btn-info js-attachment-employer-section" style="background-color: #0000ff;">Employers Section - Not Completed</button>';
                    }else{
                        rows += '       <button class="btn btn-success js-attachment-employer-section">Employers Section - Completed</button>';
                    }
                }
                rows += '       <button class="btn btn-success js-attachment-upload">Upload FMLA</button>';
            }
            rows += '   </td>';
            rows += '</tr>';
        });
        //
        target.prepend(rows);
        //
        $('#js-save-placeholder-attachment').hide();
        $('#js-save-attachment').show();
        $('#js-timeoff-attachment-modal').modal('hide');
    }
    // Set attachment views
    function setAttachments(){
        //
        var 
        attachments = currentRequest.Attachments;

        var rows = '';
        rows += '<div class="pto-foot-print-listing full-width">';
        rows += '    <div class="row">';
        rows += '        <div class="col-sm-12">';
        rows += '           <h3>Documents <span class="pull-right"><button class="btn btn-success js-timeoff-attachment"><i class="fa fa-plus"></i>&nbsp; Add Document</button></span></h3>';
        rows += '        </div>';
        rows += '    </div>';
        rows += '    <div class="row">';
        rows += '        <div class="col-sm-12">';
        rows += '            <div class="responsive">';
        rows += '                   <table class="table table-striped">';
        rows += '                         <thead>';
        rows += '                           <tr>';
        rows += '                              <th>Document Title</th>';
        rows += '                              <th>Document Type</th>';
        rows += '                              <th>Created At</th>';
        rows += '                              <th>Action</th>';
        rows += '                           </tr>';
        rows += '                         </thead>';
        rows += '                         <tbody id="js-attachment-tbody">';
        rows += '                         </tbody>';
        rows += '                   </table>';
        rows += '            </div>';
        rows += '        </div>';
        rows += '    </div>';
        rows += '</div>';

        $('#js-attachment-data-area').html(rows);
        //
        rows = '';
        rows = '<tr class="js-no-records"><td colspan="4"><p class="alert alert-info text-center">No attachments found.</p></td></tr>';
        if(attachments.length !== 0){
            rows = '';
            attachments.map(function(attachment){
                let sd = attachment.serialized_data == null ? {} : JSON.parse(attachment.serialized_data);

                attachment.showEmployerSection = sd.hasOwnProperty('type') && attachment.document_title.toLowerCase() == 'health' ? true : false;

                attachedDocuments[attachment.sid] = {
                    id: attachment.sid,
                    created_at: moment(attachment.created_at).format('MM-DD-YYYY'),
                    type: attachment.document_type,
                    serialized_data: attachment.serialized_data,
                    file: {
                        name: sd.hasOwnProperty('type') ? sd.type : attachment.s3_filename,
                        s3_filename: attachment.s3_filename
                    },
                    showEmployerSection: attachment.showEmployerSection,
                    slug: attachment.document_title.replace(/[^0-9a-zA-Z.]/g, '_').toLowerCase(),
                    title: attachment.document_type == 'generated' ? FMLACategories[attachment.document_title.toLowerCase()] : attachment.document_title
                };

                //
                rows += '<tr class="js-attachments" data-id="'+( attachment.sid )+'">';
                rows += '   <td>'+( attachment.document_type == 'generated' ? FMLACategories[attachment.document_title.toLowerCase()] : attachment.document_title )+'</td>';
                rows += '   <td>'+( attachment.document_type.ucwords() )+'</td>';
                rows += '   <td>'+( moment(attachment.created_at).format('MMM DD YYYY, ddd') )+'</td>';
                rows += '   <td>';
                if(attachment.document_type == 'uploaded'){
                    rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                    rows += '       <button class="btn btn-success js-attachment-edit">Edit</button>';
                    rows += '       <button class="btn btn-danger js-attachment-delete">Delete</button>';
                }else if(attachment.s3_filename != null){
                    rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                    rows += '       <button class="btn btn-success js-attachment-upload">Upload FMLA</button>';
                }else{
                    rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                    if(attachment.showEmployerSection){
                        if(sd.hasOwnProperty('employer') === false){
                            rows += '       <button class="btn btn-info js-attachment-employer-section" style="background-color: #0000ff;">Employers Section - Not Completed</button>';
                        }else{
                            rows += '       <button class="btn btn-success js-attachment-employer-section">Employers Section - Completed</button>';
                        }
                    }
                    rows += '       <button class="btn btn-success js-attachment-upload">Upload FMLA</button>';
                }
                rows += '   </td>';
                rows += '</tr>';
            });
        }
        $('#js-attachment-tbody').html(rows);
    }
    //
    function removeDocument(){
        var _this = $(this);
        alertify.confirm('Do you really want to remove this document?', function(){
            //
            $.post(
                baseURI+'handler', {
                    companySid: <?=$companyData['sid'];?>,
                    action: 'remove_attachment',
                    attachmentSid: _this.closest('tr').data('id')
                }, function(resp) {
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    $('.js-attachments-count').text(
                        parseInt($('.js-attachments-count').text()) - 1
                    );
                    //
                    delete attachedDocuments[_this.closest('tr').data('id')];
                    _this.closest('tr').remove();
                    if(Object.keys(attachedDocuments).length == 0)  $('.js-no-records').show();
                    //
                    alertify.alert('SUCCESS', resp.Response);
            });
        }).set('labels', {
            'ok': 'Yes',
            'cancel': 'No'
        });
    }
    //
    function editDocument(){
        var file = attachedDocuments[$(this).closest('tr').data('id')];
        localDocument = file;
        $('#js-timeoff-attachment-modal').remove();
        //
        var uploadForm = '';
        uploadForm += '<form>';
        uploadForm += '    <div class="form-group">';
        uploadForm += '        <label>Document Name <span class="cs-required">*</span></label>';
        uploadForm += '        <input type="text" class="form-control" required="true" id="js-timeoff-document-title" value="'+( file.title )+'"/>';
        uploadForm += '    </div>';
        uploadForm += '    <div class="form-group">';
        uploadForm += '        <label>Browse document <span class="cs-required">*</span></label>';
        uploadForm += '        <div class="upload-file invoice-fields">';
        uploadForm += '            <input style="height: 38px;" type="file" name="document" id="js-timeoff-document" required="true">';
        uploadForm += '             <p id="name_document">'+( file.file.name )+'</p>';
        uploadForm += '            <a href="javascript:;" style="line-height: 38px; height: 38px;">Choose File</a>';
        uploadForm += '        </div>';
        uploadForm += '        <p>Allowed supporting document types .doc, .docx, .pdf, .png, .jpg, .jpeg</p>';
        uploadForm += '    </div>';
        uploadForm += '</form>';
        //
        var 
        rows = '<div class="modal fade" id="js-timeoff-attachment-modal">';
        rows +='    <div class="modal-dialog">';
        rows +='            <div class="modal-content">';
        rows +='                <div class="modal-header modal-header-bg">';
        rows +='                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        rows +='                    <h4 class="modal-title">Supporting Document</h4>';
        rows +='                </div>';
        rows +='                <div class="modal-body">';
        rows +=  uploadForm
        rows +='                </div>';
        rows +='                <div class="modal-footer">';
        rows +='                    <input type="hidden" id="js-file-id" value="'+( $(this).closest('tr').data('id') )+'" />';
        rows +='                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        rows +='                    <button type="button" class="btn btn-success" id="js-save-attachment">Update Attached Document</button>';
        rows +='                    <button type="button" class="btn btn-success" id="js-save-placeholder-attachment" style="display: none;">Updating Attached Document...</button>';
        rows +='                </div>';
        rows +='            </div>';
        rows +='     </div>';
        rows +='</div>';
        //
        emt.modal.modal('hide');
        //
        $('body').append(rows);
        $('#js-timeoff-attachment-modal').modal();       
    }
    //
    function viewDocument(){
        var file = attachedDocuments[$(this).closest('tr').data('id')];
        if(file.type === 'uploaded' ){
            // Generate modal content
            var URL = '';
            var iframe = '';
            if(file.file.name.match(/(.doc|.docx|.ppt|.pptx)$/) !== null){
                URL = "https://view.officeapps.live.com/op/embed.aspx?src=<?=AWS_S3_BUCKET_URL;?>"+( file.file.name )+"&embedded=true";
                iframe = '<iframe id="js-attachment-iframe" src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }else if(file.file.name.match(/(.png|.jpg|.jpeg)$/) !== null){
                URL = "<?=AWS_S3_BUCKET_URL;?>"+( file.file.name )+"";
                iframe = '<img src="'+( URL )+'" style="width: 100%;">'
            }else{
                URL = "https://docs.google.com/gview?url=<?=AWS_S3_BUCKET_URL;?>"+( file.file.name )+"&embedded=true";
                iframe = '<iframe id="js-attachment-iframe" src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }

            loadModal(file, iframe, URL);
        }else if(file.file.s3_filename != null ){
            // Generate modal content
            var URL = '';
            var iframe = '';
            if(file.file.s3_filename.match(/(.doc|.docx|.ppt|.pptx)$/) !== null){
                URL = "https://view.officeapps.live.com/op/embed.aspx?src=<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"&embedded=true";
                iframe = '<iframe id="js-attachment-iframe" src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }else if(file.file.s3_filename.match(/(.png|.jpg|.jpeg)$/) !== null){
                URL = "<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"";
                iframe = '<img src="'+( URL )+'" style="width: 100%;">'
            }else{
                URL = "https://docs.google.com/gview?url=<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"&embedded=true";
                iframe = '<iframe id="js-attachment-iframe" src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }

            loadModal(file, iframe, URL);
        }else{
            // for generated
            $.post(baseURI+'handler', {
                action: 'get_generated_fmla_view',
                companySid: <?=$companyData['sid'];?>,
                fmla: file
            }, function(resp) {
                //
                if(resp.Status === false){
                    console.log('Failed to load view');
                    return;
                }
                //
                loadModal(file, resp.View);
            });
        }
    }
    //
    function loadModal(file, iframe, URL){
        //
        var 
        modal = '<div class="modal fade" id="js-attachment-view-modal">';
        modal +='    <div class="modal-dialog modal-lg">';
        modal +='            <div class="modal-content">';
        modal +='                <div class="modal-header modal-header-bg">';
        modal +='                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        modal +='                    <h4 class="modal-title">'+( file.title )+'</h4>';
        modal +='                </div>';
        modal +='                <div class="modal-body">';
        modal +=  iframe
        modal +='                </div>';
        modal +='                <div class="modal-footer">';
        if(file.type === 'uploaded'){
            modal +='         <a href="<?=base_url('hr_documents_management/download_upload_document');?>/'+( file.file.name )+'" class="btn btn-success">Download</a>';
            modal +='         <a href="'+( URL )+'" target="_blank" class="btn btn-success">Print</a>';
        }else if(file.file.s3_filename != null){
            modal +='         <a href="<?=base_url('hr_documents_management/download_upload_document');?>/'+( file.file.s3_filename )+'" class="btn btn-success">Download</a>';
            modal +='         <a href="'+( URL )+'" target="_blank" class="btn btn-success">Print</a>';
        }else{
            modal +='         <a href="<?=base_url('timeoff/download/document');?>/'+( file.id )+'" target="_blank" class="btn btn-success">Download</a>';
            modal +='         <a href="<?=base_url('timeoff/print/document');?>/'+( file.id )+'" target="_blank" class="btn btn-success">Print</a>';
        }
        modal +='                </div>';
        modal +='            </div>';
        modal +='     </div>';
        modal +='</div>';
        //
        emt.modal.modal('hide');
        // Show modal content
        $('#js-attachment-view-modal').remove();
        $('body').append(modal);
        $('#js-attachment-view-modal').modal();

        setTimeout(function(){
            loadIframe('iframe#js-attachment-iframe', URL);
        }, 3000);
    }
    //
    function employersSection(){
        var file = attachedDocuments[$(this).closest('tr').data('id')];
        if(typeof(file.serialized_data) === 'string'){
            file.serialized_data = JSON.parse(file.serialized_data);
        }
        //
        var 
        rows = '<div class="modal fade" id="js-timeoff-attachment-es-modal">';
        rows +='    <div class="modal-dialog modal-lg">';
        rows +='            <div class="modal-content">';
        rows +='                <div class="modal-header modal-header-bg">';
        rows +='                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        rows +='                    <h4 class="modal-title">Supporting Document</h4>';
        rows +='                </div>';
        rows +='                <div class="modal-body">';
        rows += getFMLAEmployersSection(
            file.serialized_data
        );
        rows +='                </div>';
        rows +='                <div class="modal-footer">';
        rows +='                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>';
        rows +='                    <button type="button" class="btn btn-success" id="js-fmla-es-save" data-id="'+( file.id )+'" data-type="'+( file.serialized_data.type )+'">Save</button>';
        rows +='                    <button type="button" class="btn btn-success js-fmla-placeholder-btn" style="display: none;">Saving...</button>';
        rows +='                </div>';
        rows +='            </div>';
        rows +='     </div>';
        rows +='</div>';
        //
        $('#js-timeoff-attachment-es-modal').remove();
        emt.modal.modal('hide');
        $('body').append(rows);
        $('#js-timeoff-attachment-es-modal').modal();
    }
    //
    function saveEmployerSection(){
        if($(this).data('type') == 'health'){
            if($('#js-fmla-health-employername').val().trim() == ''){
                alertify.alert('ERROR!', 'Employer name is mandatory.');
                return;
            }
            if($('#js-fmla-health-employercontact').val().trim() == ''){
                alertify.alert('ERROR!', 'Employer contact is mandatory.');
                return;
            }
            if($('#js-fmla-health-employeejobtitle').val().trim() == ''){
                alertify.alert('ERROR!', 'Employee job title is mandatory.');
                return;
            }
            if($('#js-fmla-health-workschedule').val().trim() == ''){
                alertify.alert('ERROR!', 'Work schedule is mandatory.');
                return;
            }
            if($('#js-fmla-health-employeejob').val().trim() == ''){
                alertify.alert('ERROR!', 'Employee essential job functions field is mandatory.');
                return;
            }

            $('#js-fmla-es-save').hide();
            $('.js-fmla-placeholder-btn').show().css('opcaity', '.6');
            //
            var employer = {
                employername: $('#js-fmla-health-employername').val().trim(),
                employercontact: $('#js-fmla-health-employercontact').val().trim(),
                employeejobtitle: $('#js-fmla-health-employeejobtitle').val().trim(),
                workschedule: $('#js-fmla-health-workschedule').val().trim(),
                employeejob: $('#js-fmla-health-employeejob').val().trim(),
                jobdescription: $('#js-fmla-health-jobdescription').prop('checked') == true ? 1 : 0
            };
            //
            attachedDocuments[$('#js-fmla-es-save').data('id')]['serialized_data']['employer'] = employer;
            //
            $.post(baseURI+'handler',{
                action: 'update_fmla',
                companySid: <?=$employerData['sid'];?>,
                requestSid: currentRequest.Info.requestId,
                fmla: employer
            }, function(resp) {
                //
                $('#js-fmla-es-save').show();
                $('.js-fmla-placeholder-btn').hide();
                //
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function(){
                    if(typeof(doReferesh) != 'undefined'){
                        window.location.reload();
                    }
                    setAttachmentTable();
                    $('#js-timeoff-attachment-es-modal').modal('hide');
                });
            });
        }
    }
    //
    function getFMLAEmployersSection(fmla){
        var rows = '',
        emp = !fmla.hasOwnProperty('employer') ? undefined : fmla.employer;
        if(fmla.type == 'health'){
            console.log(emp);
            rows = `
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Section 1. For Completion by the EMPLOYER <br>INSTRUCTIONS to the EMPLOYER:</strong> The Family and Medical Leave Act (FMLA) provides that an employer may require an employee seeking FMLA protections because of a need for leave due to a serious health condition to submit a medical certification issued by the employee’s health care provider. Please complete Section I before giving this form to your employee. Your response is voluntary. While you are not required to use this form, you may not ask the employee to provide more information than allowed under the FMLA regulations, 29 C.F.R. §§ 825.306-825.308. Employers must generally maintain records and documents relating to medical certifications, recertifications, or medical histories of employees created for FMLA purposes as confidential medical records in separate files/records from the usual personnel files and in accordance with 29 C.F.R. § 1630.14(c)(1), if the Americans with Disabilities Act applies, and in accordance with 29 C.F.R. § 1635.9, if the Genetic Information Nondiscrimination Act applies.
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group autoheight">
                                        <label>Employer Name <span class="cs-required">*</span> </label>
                                        <input type="text" value="${emp == undefined ? '' : emp.employername}" id="js-fmla-health-employername" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group autoheight">
                                        <label>Employer Contact <span class="cs-required">*</span> </label>
                                        <input type="text" value="${emp == undefined ? '' : emp.employercontact}" id="js-fmla-health-employercontact"  name="emp_contact" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group autoheight">
                                        <label>Employee’s job title <span class="cs-required">*</span> </label>
                                        <input type="text" value="${emp == undefined ? '' : emp.employeejobtitle}" id="js-fmla-health-employeejobtitle"  name="emp_job_title" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group autoheight">
                                        <label>Regular work schedule <span class="cs-required">*</span> </label>
                                        <input type="text" value="${emp == undefined ? '' : emp.workschedule}" id="js-fmla-health-workschedule"  name="emp_reg_work_sch" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Employee’s essential job functions <span class="cs-required">*</span> </label>
                                        <input type="text" value="${emp == undefined ? '' : emp.employeejob}" id="js-fmla-health-employeejob" name="emp_esse_job_func" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label>Check if job description is attached <span class="cs-required">*</span> </label>
                                            </div>    
                                            <div class="col-lg-4">
                                                <div class="form-group autoheight">
                                                    <label class="control control--checkbox">
                                                        Yes
                                                        <input type="checkbox" name="jd_attach" ${emp != undefined && emp.jobdescription == 1 ? 'checked="true"' : ''}  id="js-fmla-health-jobdescription"/>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }
        return rows;
    } 
    //
    function getRandomId(min, max) {
        min = Math.ceil(min == undefined ? 1 : min);
        max = Math.floor(max == undefined ? 9 :max);
        return Math.floor(Math.random() * 1000 * 1000 * (max - min)) + min; //The maximum is exclusive and the minimum is inclusive
    }
    //
    function cleanDocumentTitle(title){
        return title.substr(0, title.lastIndexOf('.')).replace(/[^0-9a-zA-Z]/g, ' ').replace(/\s+/g, ' ').trim();
    }
    //
    function uploadDocument(e){
        $('#js-timeoff-attachment-upload-modal').remove();
        //
        var uploadForm = '';
        uploadForm += '<form>';
        uploadForm += '    <div class="form-group">';
        uploadForm += '        <label>Browse document <span class="cs-required">*</span></label>';
        uploadForm += '        <div class="upload-file invoice-fields">';
        uploadForm += '            <input style="height: 38px;" type="file" name="document" id="js-timeoff-upload" required="true">';
        uploadForm += '             <p id="js_name_document"></p>';
        uploadForm += '             <a href="javascript:;" style="line-height: 38px; height: 38px;">Choose File</a>';
        uploadForm += '        </div>';
        uploadForm += '        <p>Allowed types .doc, .docx, .pdf, .png, .jpg, .jpeg</p>';
        uploadForm += '    </div>';
        uploadForm += '</form>';
        //
        var 
        rows = '<div class="modal fade" id="js-timeoff-attachment-upload-modal">';
        rows +='    <div class="modal-dialog">';
        rows +='            <div class="modal-content">';
        rows +='                <div class="modal-header modal-header-bg">';
        rows +='                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        rows +='                    <h4 class="modal-title">Upload Signed FMLA</h4>';
        rows +='                </div>';
        rows +='                <div class="modal-body">';
        rows +=  uploadForm
        rows +='                </div>';
        rows +='                <div class="modal-footer">';
        rows +='                    <input type="hidden" id="js-file-id" value="'+( $(this).closest('tr').data('id') )+'" />';
        rows +='                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        rows +='                    <button type="button" class="btn btn-success" id="js-save-fmla">Upload FMLA</button>';
        rows +='                    <button type="button" class="btn btn-success" id="js-save-placeholder-fmla" style="display: none;">Uploading FMLA...</button>';
        rows +='                </div>';
        rows +='            </div>';
        rows +='     </div>';
        rows +='</div>';
        //
        emt.modal.modal('hide');
        //
        $('body').append(rows);
        $('#js-timeoff-attachment-upload-modal').modal();
    }
    //
    function verifyAndSaveFMLALocally(e){
        var file = this.files[0];
        FMLA = {};
        //
        if($.inArray(file.type, allowedTypes) === -1){
            alertify.alert('ERROR!', 'Invalid format.', function(){ return; });
            return;
        }
        //
        $('#js_name_document').text(file.name);
       
        //
        FMLA = {file: file};
    }
    //
    function verifyAndSaveFMLADocument(e){
        if(Object.keys(FMLA).length === 0){
            alertify.alert('ERROR!', 'Please, select a file.', function(){ return; });
            return;
        }
        //
        $('#js-save-fmla').hide();
        $('#js-save-placeholder-fmla').show().css('opacity', '.6');
        // Make form Object
        var obj = new FormData();
        obj.append('action', 'update_fmla_attachment');
        obj.append('requestSid', currentRequest.Info.requestId);
        obj.append('companySid', <?=$companyData['sid'];?>);
        obj.append('employeeSid', <?=$employerData['sid'];?>);
        obj.append('attachment', FMLA.file);
        //
        obj.append('attachmentSid', $('#js-timeoff-attachment-upload-modal').find('#js-file-id').val());
        // Save document to request
        $.ajax({
            url: baseURI+'handler',
            processData: false,
            method: 'POST',
            contentType: false,
            data: obj
        }).done(function(resp){
            if(typeof(doReferesh) != 'undefined'){
                window.location.reload();
            }
            attachedDocuments[resp.AttachmentSid]['file']['s3_filename'] = resp.S3_filename;
            $('#js-timeoff-attachment-upload-modal').modal('hide');
            // // Set table
            setAttachmentTable();
        });
    }
    // Modal Attachments End
    //
    // FMLAs
    $('.js-fmla-check').click(function(e){
        if($(this).val() == 'yes') $('.js-fmla-box').show();
        else{ $('.js-fmla-type-check').prop('checked', false);  fmla = {}; $('.js-fmla-box').hide(); }
    });


    function loadIframe (target, url) {
        try {
            if($(target).contents().find("body").text().trim() == '') {
                $(target).prop('src', url);
                setTimeout(function(){
                    loadIframe(target, url);
                },3000);
            } 
        }
        catch(err) {
            console.log('iframe preview not load successfully')
        } 
    }