<script>
// Document Attachment
var allowedTypes = [
    'application/pdf',
    'image/png',
    'image/jpg',
    'image/jpeg',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
],
localDocument = {},
attachedDocuments = {};
// window.attachedDocuments = attachedDocuments;
// window.setAttachmentTable = setAttachmentTable;
// Document attachment timeoff
$(document).on('click', '.js-timeoff-attachment', startAttachDocumentProcess);
$(document).on('change', '#js-timeoff-document-add, #js-timeoff-document-draft, #js-timeoff-document-edit', verifyAndSaveDocumentLocally);
$(document).on('click', '#js-save-attachment-add, #js-save-attachment-draft, #js-save-attachment-edit', verifyAndSaveDocument);
$(document).on('click', '.js-attachment-delete', removeDocument);
$(document).on('click', '.js-attachment-edit', editDocument);
$(document).on('click', '#js-attachment-view-add, #js-attachment-view-draft, #js-attachment-view-edit, #js-attachment-view-view', viewDocument);
//
function startAttachDocumentProcess(e){
    attachmentMode = $(this).data('type');
    localDocument = {};
    $('#js-timeoff-attachment-modal-'+( attachmentMode )+'').remove();
    //
    var uploadForm = '';
    uploadForm += '<form>';
    uploadForm += '    <div class="form-group">';
    uploadForm += '        <label>Document Name <span class="cs-required">*</span></label>';
    uploadForm += '        <input type="text" class="form-control" required="true" id="js-timeoff-document-title-'+( attachmentMode )+'"/>';
    uploadForm += '    </div>';
    uploadForm += '    <div class="form-group">';
    uploadForm += '        <label>Browse Document <span class="cs-required">*</span></label>';
    uploadForm += '        <div class="upload-file invoice-fields">';
    uploadForm += '            <input style="height: 38px;" type="file" name="document" id="js-timeoff-document-'+( attachmentMode )+'" required="true">';
    uploadForm += '             <p id="name_document-'+( attachmentMode )+'"></p>';
    uploadForm += '            <a href="javascript:;" style="line-height: 38px; height: 38px;" style="background-color: #3554dc !important;">Choose File</a>';
    uploadForm += '        </div>';
    uploadForm += '        <p>Allowed supporting document types .doc, .docx, .pdf, .png, .jpg, .jpeg</p>';
    uploadForm += '    </div>';
    uploadForm += '</form>';
    //
    var 
    rows = '<div class="modal fade" id="js-timeoff-attachment-modal-'+( attachmentMode )+'">';
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
    rows +='                    <button type="button" class="btn btn-success"  style="background-color: #3554dc !important;" id="js-save-attachment-'+( attachmentMode )+'">Attach Document</button>';
    rows +='                    <button type="button" class="btn btn-success"  style="background-color: #3554dc !important; display: none;" id="js-save-placeholder-attachment-'+( attachmentMode )+'">Attaching Document...</button>';
    rows +='                </div>';
    rows +='            </div>';
    rows +='     </div>';
    rows +='</div>';
    //
    if(attachmentMode == 'add'){
        $('#js-timeoff-modal').modal('hide');
    } else if(attachmentMode == 'draft'){
        $('#js-timeoff-draft-modal').modal('hide');
    } else if(attachmentMode == 'edit'){
        $('#js-timeoff-edit-modal').modal('hide');
    }
    //
    $('body').append(rows);
    $('#js-timeoff-attachment-modal-'+( attachmentMode )+'').modal();
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
    $('#name_document-'+( attachmentMode )+'').text(file.name);
    //
    if($('#js-timeoff-document-title-'+( attachmentMode )+'').val().trim() == ''){
        $('#js-timeoff-document-title-'+( attachmentMode )+'').val(
            cleanDocumentTitle(file.name)
        )
    }
    //
    localDocument = {
        id: getRandomId(),
        file: file,
        type: 'uploaded',
        slug: file.name.replace(/[^0-9a-zA-Z.]/g, '_').toLowerCase(),
        title: $('#js-timeoff-document-title-'+( attachmentMode )+'').val().trim()
    };
}
//
function verifyAndSaveDocument(e){
    console.log(attachedDocuments);
    if(Object.keys(localDocument).length == 0 || $('#js-timeoff-document-title-'+( attachmentMode )+'').val().trim() == ''){
        alertify.alert('ERROR!', 'All fields are mandatory.', function(){ return; });
        return;
    }
    //
    localDocument['title'] = $('#js-timeoff-document-title-'+( attachmentMode )+'').val().trim();
    //
    $('#js-save-attachment-'+( attachmentMode )+'').hide();
    $('#js-save-placeholder-attachment-'+( attachmentMode )+'').show().css('opacity', '.6');
    //
    if($('#js-timeoff-attachment-modal-'+( attachmentMode )+'').find('#js-file-id-'+( attachmentMode )+'').length !== 0){
        localDocument['id'] = Number($('#js-timeoff-attachment-modal-'+( attachmentMode )+'').find('#js-file-id-'+( attachmentMode )+'').val());
    }
    //
    attachedDocuments[localDocument.id] = localDocument;
    localDocument = {};
    // Set table
    setAttachmentTable();
}
//
function setAttachmentTable(){
    // attachedDocuments = Object.keys(attachedDocuments).length == 0 ? window.attachedDocuments : attachedDocuments;
    var target = $('#js-attachment-listing-'+( attachmentMode )+'').find('tbody');
    //
    $('.js-no-records-'+( attachmentMode )+'').hide();
    $('.js-attachments-'+( attachmentMode )+'').remove();
    //
    var rows = '';
    Object.keys(attachedDocuments).map(function(attachment){
        rows += '<tr class="js-attachments-'+( attachmentMode )+'" data-id="'+( attachedDocuments[attachment].id )+'">';
        rows += '   <td>'+( attachedDocuments[attachment].title )+'</td>';
        rows += '   <td>'+( attachedDocuments[attachment].type.ucfirst() )+'</td>';
        rows += '   <td>';
        if(attachmentMode != 'view' && attachedDocuments[attachment].type != 'generated'){
            rows += '       <button class="btn btn-success js-attachment-edit"  style="background-color: #3554dc !important;">Edit</button>';
            rows += '       <button class="btn btn-danger js-attachment-delete">Delete</button>';
        }
        if(attachmentMode == 'view'){
            rows += '       <button class="btn btn-info btn-5" id="js-attachment-view-'+(attachmentMode)+'">View</button>';
        }
        rows += '   </td>';
        rows += '</tr>';
    });
    //
    target.prepend(rows);
    //
    $('#js-save-placeholder-attachment').hide();
    $('#js-save-attachment').show();
    $('#js-timeoff-attachment-modal-'+( attachmentMode )+'').modal('hide');
}
//
function removeDocument(){
    var _this = $(this);
    alertify.confirm('Do you really want to remove this document?', function(){
        delete attachedDocuments[_this.closest('tr').data('id')];
        _this.closest('tr').remove();
        if(Object.keys(attachedDocuments).length == 0)  $('.js-no-records-'+( attachmentMode )+'').show();
    }).set('labels', {
        'ok': 'Yes',
        'cancel': 'No'
    });
}
//
function editDocument(){
    var file = attachedDocuments[$(this).closest('tr').data('id')];
    localDocument = file;
    $('#js-timeoff-attachment-modal-'+( attachmentMode )+'').remove();
    //
    var uploadForm = '';
    uploadForm += '<form>';
    uploadForm += '    <div class="form-group">';
    uploadForm += '        <label>Document Name <span class="cs-required">*</span></label>';
    uploadForm += '        <input type="text" class="form-control" required="true" id="js-timeoff-document-title-'+( attachmentMode )+'" value="'+( file.title )+'"/>';
    uploadForm += '    </div>';
    uploadForm += '    <div class="form-group">';
    uploadForm += '        <label>Browse Document <span class="cs-required">*</span></label>';
    uploadForm += '        <div class="upload-file invoice-fields">';
    uploadForm += '            <input style="height: 38px;" type="file" name="document" id="js-timeoff-document-'+( attachmentMode )+'" required="true">';
    uploadForm += '             <p id="name_document-'+( attachmentMode )+'">'+( file.file.name )+'</p>';
    uploadForm += '            <a href="javascript:;" style="line-height: 38px; height: 38px;" style="background-color: #3554dc !important;">Choose File</a>';
    uploadForm += '        </div>';
    uploadForm += '        <p>Allowed supporting document types .doc, .docx, .pdf, .png, .jpg, .jpeg</p>';
    uploadForm += '    </div>';
    uploadForm += '</form>';
    //
    var 
    rows = '<div class="modal fade" id="js-timeoff-attachment-modal-'+( attachmentMode )+'">';
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
    rows +='                    <input type="hidden" id="js-file-id'+( attachmentMode )+'" value="'+( $(this).closest('tr').data('id') )+'" />';
    rows +='                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
    rows +='                    <button type="button" class="btn btn-success" style="background-color: #3554dc !important;" id="js-save-attachment-'+( attachmentMode )+'">Update Attached Document</button>';
    rows +='                    <button type="button" class="btn btn-success" style="background-color: #3554dc !important; display: none;" id="js-save-placeholder-attachment-'+( attachmentMode )+'">Updating Attached Document...</button>';
    rows +='                </div>';
    rows +='            </div>';
    rows +='     </div>';
    rows +='</div>';

    if(attachmentMode == 'add'){
        $('#js-timeoff-modal').modal('hide');
    } else if(attachmentMode == 'draft'){
        $('#js-timeoff-draft-modal').modal('hide');
    } else if(attachmentMode == 'edit'){
        $('#js-timeoff-edit-modal').modal('hide');
    }
    //
    $('body').append(rows);
    $('#js-timeoff-attachment-modal-'+( attachmentMode )+'').modal();       
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
                iframe = '<iframe src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }else if(file.file.name.match(/(.png|.jpg|.jpeg)$/) !== null){
                URL = "<?=AWS_S3_BUCKET_URL;?>"+( file.file.name )+"";
                iframe = '<img src="'+( URL )+'" style="width: 100%;">'
            }else{
                URL = "https://docs.google.com/gview?url=<?=AWS_S3_BUCKET_URL;?>"+( file.file.name )+"&embedded=true";
                iframe = '<iframe src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }

            loadModal(file, iframe, URL);
        }else if(file.file.s3_filename != null ){
            // Generate modal content
            var URL = '';
            var iframe = '';
            if(file.file.s3_filename.match(/(.doc|.docx|.ppt|.pptx)$/) !== null){
                URL = "https://view.officeapps.live.com/op/embed.aspx?src=<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"&embedded=true";
                iframe = '<iframe src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }else if(file.file.s3_filename.match(/(.png|.jpg|.jpeg)$/) !== null){
                URL = "<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"";
                iframe = '<img src="'+( URL )+'" style="width: 100%;">'
            }else{
                URL = "https://docs.google.com/gview?url=<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"&embedded=true";
                iframe = '<iframe src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
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
        modal = '<div class="modal fade" id="js-attachment-viewer-modal">';
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
            modal +='         <a href="<?=base_url('hr_documents_management/download_upload_document');?>/'+( file.file.name )+'" class="btn btn-info btn-5">Download</a>';
            modal +='         <a href="'+( URL )+'" target="_blank" class="btn btn-info btn-5">Print</a>';
        }else if(file.file.s3_filename != null){
            modal +='         <a href="<?=base_url('hr_documents_management/download_upload_document');?>/'+( file.file.s3_filename )+'" class="btn btn-info btn-5">Download</a>';
            modal +='         <a href="'+( URL )+'" target="_blank" class="btn btn-info btn-5">Print</a>';
        }else{
            modal +='         <a href="<?=base_url('timeoff/download/document');?>/'+( file.id )+'" target="_blank" class="btn btn-info btn-5">Download</a>';
            modal +='         <a href="<?=base_url('timeoff/print/document');?>/'+( file.id )+'" target="_blank" class="btn btn-info btn-5">Print</a>';
        }
        modal +='                </div>';
        modal +='            </div>';
        modal +='     </div>';
        modal +='</div>';
        //
        if(attachmentMode == 'add'){
            $('#js-timeoff-modal').modal('hide');
        } else if(attachmentMode == 'draft'){
            $('#js-timeoff-draft-modal').modal('hide');
        } else if(attachmentMode == 'edit'){
            $('#js-timeoff-edit-modal').modal('hide');
        } else if(attachmentMode == 'view'){
            $('#js-timeoff-view-modal').modal('hide');
        }
        // Show modal content
        $('#js-attachment-viewer-modal').remove();
        $('body').append(modal);
        $('#js-attachment-viewer-modal').modal();
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

$(document).on('hidden.bs.modal', '#js-timeoff-attachment-modal-add', function(){
    $('body').addClass('modal-open');
    $('#js-timeoff-modal').modal('show');
});
$(document).on('hidden.bs.modal', '#js-timeoff-attachment-modal-draft', function(){
    $('body').addClass('modal-open');
    $('#js-timeoff-draft-modal').modal('show');
});
$(document).on('hidden.bs.modal', '#js-timeoff-attachment-modal-edit', function(){
    $('body').addClass('modal-open');
    $('#js-timeoff-edit-modal').modal('show');
});
$(document).on('hidden.bs.modal', '#js-attachment-viewer-modal', function(){
    $('body').addClass('modal-open');
    $('#js-timeoff-view-modal').modal('show');
});
$(document).on('hidden.bs.modal', '#js-timeoff-modal, #js-timeoff-draft-modal, #js-timeoff-view-modal, #js-timeoff-edit-modal', function(){
    $('body').removeClass('modal-open');
    $('body').css('padding-right', 0);
    // attachedDocuments = {};
    // localDocument = {};
});
</script>
<style>
.modal-open{ overflow: hidden !important; }
</style>