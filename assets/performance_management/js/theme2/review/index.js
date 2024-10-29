$(function() {
    var tFile;
    //
    $('#jsQuestionAttachmentUpload').mFileUploader({
        allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'],
        fileLimit: -1,
        path: true,
        onSuccess: function(file) {

            tFile = file;
            //
            $('#jsQuestionAttachmentUploadRow').removeClass('dn');
        },
        onError: function(error) {
            tFile = undefined;
            $('#jsQuestionAttachmentUploadRow').addClass('dn');
        },
        onClear: function() {
            tFile = undefined;
            $('#jsQuestionAttachmentUploadRow').addClass('dn');
        }
    });

    //
    $('.jsReviewRating').click(function(event) {
        //
        event.preventDefault();
        //
        question.rating = $(this).data('id');
        //
        $('.jsReviewRating').removeClass('active')
        $(this).addClass('active')
    });

    //
    $('#jsQuestionAttachmentUploadRow').click(function(event) {
        //
        uploadFile(tFile, true);
    });

    //
    $('.jsReviewChoice').click(function(event) {
        //
        event.preventDefault();
        //
        question.multiple_choice = $(this).data('id');
    });

    //
    $('.jsReviewText').keyup(function(event) {
        //
        event.preventDefault();
        //
        question.text = $(this).val().trim();
    });

    //
    $('.jsReviewSave').click(function(event) {
        //
        event.preventDefault();
        //
        if ($('.jsReviewChoice').length > 0 && question.multiple_choice === undefined) {
            handleError('Please select one of the options.');
            return;
        }
        //
        if ($('.jsReviewRating').length > 0 && question.rating === undefined) {
            handleError('Please select a rating.');
            return;
        }
        //
        if ($('.jsReviewText').length > 0 && question.text === undefined) {
            handleError('Please provide feedback.');
            return;
        }
        //
        if (totalPages - 1 == completedPages || page == 0) {
            question.completed = 1;
        }
        //
        ml(true, 'save_question');
        //
        $.post(pm.urls.pbase + 'save_answer', question)
            .done(function(resp) {
                ml(false, 'save_question');
                //
                var text = 'Your answer has been saved.';
                //
                if (!isManager && page == totalPages) {
                    text = 'You have successfully provided feedback.';



                    
                }
                handleSuccess(text, function() {
                    //
                    if (isManager && page == totalPages) {
                        window.location = window.location.origin + window.location.pathname + '?page=feedback';
                    } else {
                        if (page != totalPages) {
                            window.location = window.location.origin + window.location.pathname + '?page=' + (++page);
                        } else {
                            window.location = window.location.origin + '/performance-management/reviews';
                        }
                    }
                });
            });
    });


    function uploadFile(file, doSave) {
        //
        ml(true, 'save_question');
        var fd = new FormData();
        var files = file;
        fd.append('file', files);
        if (doSave) {
            fd.append('questionId', question.questionId);
            fd.append('reviewId', question.reviewId);
            fd.append('revieweeId', question.revieweeId);
            fd.append('reciewerId', question.reviewerId);
        }
        //
        $.ajax({
            method: "POST",
            url: pm.urls.pbase + 'upload_question_file',
            contentType: false,
            processData: false,
            data: fd,
            success: function(resp) {
                question.attachments.push(resp);
                //
                var rows = '';
                rows += '<tr data-id="' + (resp) + '">';
                rows += '<td style="vertical-align: middle">';
                rows += '    <p class="csF16">' + (resp) + '</p>';
                rows += '</td>';
                rows += '<td style="vertical-align: middle">';
                rows += '    <button class="btn btn-orange csF14 jsPreviewAttachment">';
                rows += '        <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Preview';
                rows += '    </button>';
                rows += '</td>';
                rows += '</tr>';
                //
                $('#jsAttachmentBody').prepend(rows);
                //
                tFile = undefined;
                $('#jsQuestionAttachmentUploadRow').addClass('dn');
                //
                ml(false, 'save_question');
            }
        });
    }

    $('.jsPreviewAttachment').click(function(event) {
        //
        event.preventDefault();
        //
        var fileName = $(this).closest('tr').data().id;
        //
        var iframeURL = '';
        //
        var tmp = fileName.split('.');
        //
        var extension = tmp[tmp.length - 1];
        //
        var downloadBTN = '<a href="' + (pm.urls.base) + 'hr_documents_management/download_upload_document/' + (fileName) + '" style="margin-top: -5px;" target="blank" class="btn btn-orange btn-lg"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; Download</a>';
        //
        if ($.inArray(extension, ['xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx']) !== -1) {
            iframeURL = encodeURI('https://view.officeapps.live.com/op/view.aspx?srchttps://automotohrattachments.s3.amazonaws.com/' + fileName)
        } else {
            iframeURL = 'https://docs.google.com/gview?url=https://automotohrattachments.s3.amazonaws.com/' + fileName + '&embedded=true'
        }
        //
        var body = '';
        body += '<div class="container">';
        body += '   <div class="row">';
        body += '       <div class="col-xs-12">';
        body += '       <iframe src="' + (iframeURL) + '"  width="100%" height="600"></iframe>';
        body += '       </div>';
        body += '   </div>';
        body += '</div>';
        //
        Modal({
            Id: "jsPreviewModal",
            Title: "Preview - " + fileName,
            Buttons: [downloadBTN],
            Loader: "jsPreviewModalLoader",
            Body: body
        }, function() {
            ml(false, 'jsPreviewModalLoader');
        });
    });

    //
    ml(false, 'save_question');
});