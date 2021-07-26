$(function() {
    //
    $('#jsQuestionAttachmentUpload').mFileUploader({
        allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'],
        fileLimit: -1,
        path: true,
        onSuccess: function(file) {
            //
            ml(true, 'save_question');
            //
            uploadFile(file);
            //
            $('#jsQuestionAttachmentUploadRow').removeClass('dn');
        },
        onError: function(error) {
            $('#jsQuestionAttachmentUploadRow').addClass('dn');
        },
        onClear: function() {
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
        if (totalPages == completedPages) {
            question.completed = 1;
        }
        //
        ml(true, 'save_question');
        //
        $.post(pm.urls.pbase + 'save_answer', question)
            .done(function(resp) {
                ml(false, 'save_question');
                handleSuccess("Answer saved.", function() {
                    //
                    if (isManager && page == totalPages) {
                        window.location = window.location.origin + window.location.pathname + '?page=feedback';
                    } else {
                        if (page != totalPages) {
                            window.location = window.location.origin + window.location.pathname + '?page=' + (++page);
                        } else {
                            window.location.reload();
                        }
                    }
                });
            });
    });


    function uploadFile(file) {
        var fd = new FormData();
        var files = file;
        fd.append('file', files);
        //
        $.ajax({
            method: "POST",
            url: pm.urls.pbase + 'upload_question_file',
            contentType: false,
            processData: false,
            data: fd,
            success: function(resp) {
                question.attachments.push(resp);
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
        if ($.inArray(extension, ['xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx']) !== -1) {
            iframeURL = encodeURI('https://view.officeapps.live.com/op/view.aspx?srchttps://automotohrattachments.s3.amazonaws.com/' + fileName)
        } else {
            iframeURL = 'https://docs.google.com/gview?url=https://automotohrattachments.s3.amazonaws.com/' + fileName + '&embedded=true'
        }
        //
        Modal({
            Id: "jsPreviewModal",
            Title: "Preview - " + fileName,
            Loader: "jsPreviewModalLoader",
            Body: '<iframe src="' + (iframeURL) + '"  width="100%" height="600"></iframe>'
        }, function() {
            ml(false, 'jsPreviewModalLoader');
        });
    });

    //
    ml(false, 'save_question');
});