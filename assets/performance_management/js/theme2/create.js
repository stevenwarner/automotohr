// 
$(function() {
    //
    $('.jsTemplateQuestionsView').click(function(event) {
        //
        event.preventDefault();
        //
        var data = $(this).closest('.csTemplateWrap').data();
        //
        console.log(data);
        //
        Modal({
            Id: 'jsTemplateQuestionView',
            Title: data.name,
            Loader: 'jsTemplateQuestionViewLoader',
            Body: '<div id="jsTemplateQuestionViewBody"></div>'
        }, loadTemplateQuestions.bind(this, 'jsTemplateQuestionView', data.type, data.id));
    });

    //
    $('.jsTemplateQuestionsSelect').click(function(event) {
        //
        event.preventDefault();
        //
        $('.csTemplateWrap').removeClass('active');
        //
        $(this).closest('.csTemplateWrap').addClass('active');
    });


    //
    function loadTemplateQuestions(targetId, type, id) {
        //
        $.get(pm.urls.pbase + 'get-template-questions/' + type + '/' + id, function(resp) {
            $('#' + targetId + 'Body').html(resp);
            ml(false, 'jsTemplateQuestionViewLoader');
        });
    }
});