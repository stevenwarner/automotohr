// Document Attachment
var allowedTypes = [
    'application/pdf',
    'image/png',
    'image/jpg',
    'image/jpeg',
    'pplication/msword'
],
localDocument = {},
attachedDocuments = {};
// Document attachment timeoff
$(document).on('click', '.js-timeoff-attachment', startAttachDocumentProcess);
$(document).on('change', '#js-timeoff-document', verifyAndSaveDocumentLocally);
$(document).on('click', '#js-save-attachment', verifyAndSaveDocument);
$(document).on('click', '.js-attachment-delete', removeDocument);
$(document).on('click', '.js-attachment-edit', editDocument);
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
    $('#js-timeoff-modal').modal('hide');
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
    //
    if($('#js-timeoff-attachment-modal').find('#js-file-id').length !== 0){
        localDocument['id'] = Number($('#js-timeoff-attachment-modal').find('#js-file-id').val());
    }
    //
    attachedDocuments[localDocument.id] = localDocument;
    localDocument = {};
    // Set table
    setAttachmentTable();
}
//
function setAttachmentTable(){
    var target = $('#js-attachment-listing').find('tbody');
    //
    $('.js-no-records').hide();
    $('.js-attachments').remove();
    //
    var rows = '';
    Object.keys(attachedDocuments).map(function(attachment){
        rows += '<tr class="js-attachments" data-id="'+( attachedDocuments[attachment].id )+'">';
        rows += '   <td>'+( attachedDocuments[attachment].title )+'</td>';
        rows += '   <td>'+( attachedDocuments[attachment].type )+'</td>';
        rows += '   <td>';
        rows += '       <button class="btn btn-success js-attachment-edit">Edit</button>';
        rows += '       <button class="btn btn-danger js-attachment-delete">Delete</button>';
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
//
function removeDocument(){
    var _this = $(this);
    alertify.confirm('Do you really want to remove this document?', function(){
        delete attachedDocuments[_this.closest('tr').data('id')];
        _this.closest('tr').remove();
        if(Object.keys(attachedDocuments).length == 0)  $('.js-no-records').show();
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
    $('#js-timeoff-modal').modal('hide');
    //
    $('body').append(rows);
    $('#js-timeoff-attachment-modal').modal();       
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

$(document).on('hidden.bs.modal', '#js-timeoff-attachment-modal', function(){
    $('#js-timeoff-modal').modal('show');
});