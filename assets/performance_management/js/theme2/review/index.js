$(function() {
    //
    $('#jsQuestionAttachmentUpload').mFileUploader({
        allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv', 'webm', 'mp4'],
        fileLimit: -1,
        path: true,
        onSuccess: function(file) {
            $('#jsQuestionAttachmentUploadRow').removeClass('dn');
        },
        onError: function() {
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
        ml(true, 'save_question');
        //
        $.post(pm.urls.pbase + 'save_answer', question)
            .done(function(resp) {

            });

        console.log(question);

    });

    //
    ml(false, 'save_question');
});