// 
$(function() {

    var obj = {
        Title: '',
        Description: '',
        Visibility: {
            roles: [],
            departments: [],
            teams: [],
            employees: []
        },
        Schedule: {
            frequency_type: 'onetime',
            start_date: '',
            end_date: '',
            recur_value: 0,
            recur_type: 'days',
            review_due: 0,
            custom_runs: []
        },
        Reviewees: {
            included: [],
            excluded: []
        },
        Reviewers: {
            reviewer_type: 'reporting_manager',
            reviwees: {}
        },
        Questions: {},
        Share_feedback: true
    };

    window.REVIEW = obj;
    //
    $('#jsReviewStartDateInp').datepicker({
        changeYear: true,
        changeMonth: true,
        onselect: function(d) {
            $('#jsReviewEndDateInp').datepicker("set", "minDate", d)
        }
    });
    $('#jsReviewEndDateInp').datepicker({
        changeYear: true,
        changeMonth: true,
    });

    //
    stepMover('template');

    // Events
    //
    $('.jsTemplateQuestionsView').click(function(event) {
        //
        event.preventDefault();
        //
        var data = $(this).closest('.csTemplateWrap').data();
        //
        Modal({
            Id: 'jsTemplateQuestionView',
            Title: data.name,
            Loader: 'jsTemplateQuestionViewLoader',
            Body: '<div id="jsTemplateQuestionViewBody"></div>',
            Cancel: 'Close'
        }, loadTemplateQuestions.bind(this, 'jsTemplateQuestionView', data.type, data.id));
    });

    //
    $('.jsTemplateQuestionsSelect').click(function(event) {
        //
        event.preventDefault();
        //
        ml(true, 'review', 'Please wait we are setting the review.');
        //
        $('.csTemplateWrap').removeClass('active');
        //
        $(this).closest('.csTemplateWrap').addClass('active');
        //
        //
        var data = $(this).closest('.csTemplateWrap').data();
        //
        $.get(pm.urls.pbase + 'get-single-template/' + (data.type) + '/' + (data.id) + '?format=json')
            .done(function(resp) {
                //
                obj.Questions = resp.data.questions;
                obj.Title = resp.data.name;
                //
                $('#jsReviewTitleTxt').text(': ' + obj.Title);
                $('#jsReviewTitleInp').val(obj.Title);
                //
                stepMover('schedule');
            });
    });

    //
    $('#jsReviewCreateNewBtn').click(function(event) {
        //
        event.preventDefault();
        //
        obj.Title = '';
        obj.questions = [];
        //
        $('#jsReviewTitleTxt').text('');
        $('#jsReviewTitleInp').val('');
        //
        $('.csTemplateWrap').removeClass('active');
        //
        stepMover('schedule');
    });

    //
    $('.jsPageSectionBtn').click(function(event) {
        //
        event.preventDefault();
        //
        stepMover($(this).data('to'));
    });

    //
    $('#jsReviewScheduleSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        var o = {
            title: $('#jsReviewTitleInp').val().trim(),
            description: $('#jsReviewDescriptionInp').val().trim(),
            roles: $('#jsReviewRolesInp').val() || [],
            departments: $('#jsReviewDepartmentsInp').val() || [],
            teams: $('#jsReviewTeamsInp').val() || [],
            employees: $('#jsReviewEmployeesInp').val() || [],
            frequency_type: '',
            start_date: '',
            end_date: '',
            recur_type: '',
            recur_value: '',
            review_due: '',
            custom_runs: ''
        };
        //

    });

    // Functions
    //
    function loadTemplateQuestions(targetId, type, id) {
        //
        $.get(pm.urls.pbase + 'get-template-questions/' + type + '/' + id).done(function(resp) {
            $('#' + targetId + 'Body').html(resp);
            ml(false, 'jsTemplateQuestionViewLoader');
        });
    }

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

    //
    function stepMover(to) {
        $('.jsPageSection').fadeOut(0);
        $('.jsPageSection[data-page="' + (to) + '"]').show(0);
        ml(false, 'review');
    }
});