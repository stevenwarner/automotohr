$(function() {
    //
    let XHR = null;
    //
    const questions = answers || {};

    /**
     * 
     */
    $('#jsFilterReviewName').select2({
        minimumResultsForSearch: -1,
        selectionTitleAttribute: false
    });

    /**
     * 
     */
    $('.jsQuestionNA').click(function() {
        //
        saveQuestion(
            $(this).closest('.jsQuestionBox').data('id'),
            'not_applicable',
            $(this).prop('checked') == true ? 1 : 0
        );
    });

    /**
     * 
     */
    $('.jsQuestionMultiple').click(function() {
        //
        saveQuestion(
            $(this).closest('.jsQuestionBox').data('id'),
            'radio',
            $(this).val()
        );
    });

    /**
     * 
     */
    $('.jsQuestionBox li').click(function() {
        //
        $(this).closest('.jsQuestionBox').find('li').removeClass('active');
        $(this).addClass('active');
        //
        saveQuestion(
            $(this).closest('.jsQuestionBox').data('id'),
            'rating',
            $(this).find('.jsQuestionRating').data('id')
        );
    });

    /**
     * 
     */
    $('.jsQuestionText').blur(function() {
        //
        saveQuestion(
            $(this).closest('.jsQuestionBox').data('id'),
            'text',
            $(this).val().trim()
        );
    });

    /**
     * 
     */
    $('.jsQuestionSaveBtn').click(function() {
        ml(true, loaderName);
        saveBulkAnswer(questions)
    });


    /**
     * 
     */
    $('.jsQuestionCancelBtn, .jsQuestionFLBtn').click(function(event) {
        //
        event.preventDefault();
        alertify.confirm(
                'Any unsaved changes will be lost.',
                () => {
                    window.location.href = `${pm.urls.pbase}review/${pm.Id}`;
                }
            )
            .setHeader('Confirm!')
            .set('labels', {
                ok: "Yes",
                cancel: "No"
            });
    });



    /**
     * 
     */
    function saveQuestion(
        questionId,
        index,
        value
    ) {
        if (questions[questionId] === undefined) {
            questions[questionId] = {
                not_applicable: 0,
                radio: 'no',
                rating: 0,
                text: ''
            };
        }
        //
        const oldQuestion = JSON.stringify(questions[questionId]);
        //
        questions[questionId][index] = value;
        //
        if (oldQuestion !== JSON.stringify(questions[questionId]))
            saveAnswer(questions[questionId], questionId);
    }


    /**
     * 
     */
    function saveAnswer(question, questionId) {
        //
        $.post(
            pm.urls.handler, {
                action: "save_answer",
                question: question,
                questionId: questionId,
                revieweeId: pm.Pem
            }, (resp) => {
                if (resp.Redirect === true) {
                    handleRedirect();
                    return;
                }
                //
                alertify.success(`Answer saved.`);
            }
        );
    }

    /**
     * 
     */
    function saveBulkAnswer(questions) {
        ml(true, loaderName);
        //
        $.post(
            pm.urls.handler, {
                action: "save_bulk_answer",
                questions: questions,
                revieweeId: pm.Pem
            }, (resp) => {
                ml(false, loaderName);
                if (resp.Redirect === true) {
                    handleRedirect();
                    return;
                }
                //
                handleSuccess(getError('answer_save_success'));
                return;
            }
        );
    }

});