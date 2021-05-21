$(function() {
    // Declerations
    let boxCount = 1;

    // Binds
    $('#jsReviewStartDate').datepicker({
        changeYear: true,
        changeMonth: true,
        minDate: 0,
        formatDate: pm.dateTimeFormats.ymdf,
        onSelect: function(d) {
            $('#jsReviewEndDate').datepicker('option', 'minDate', d);
            reviewOBJ.setIndexValue('reviewStartDate', d, 'schedule');
        }
    });

    $('#jsReviewEndDate').datepicker({
        changeYear: true,
        changeMonth: true,
        minDate: 0,
        formatDate: pm.dateTimeFormats.ymdf,
        onSelect: function(d) {
            reviewOBJ.setIndexValue('reviewEndDate', d, 'schedule')
        }
    });

    //
    $('#jsReviewVisibilityRoles').select2({ minimumResultsForSearch: -1 });
    $('#jsReviewVisibilityDepartments').select2({ minimumResultsForSearch: -1 });
    $('#jsReviewVisibilityTeams').select2({ minimumResultsForSearch: -1 });
    $('#jsReviewRepeatType').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterIndividuals').select2();
    $('#jsFilterDepartments').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterTeams').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterEmploymentType').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterJobTitles').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterExcludeNewHires').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterExcludeEmployees').select2();
    $('#jsReviewSpecificReviewers').select2();

    // Events

    $('#jsQuestionVal').change(makeQuestionPreview);
    $('#jsQuestionDescription').change(makeQuestionPreview);
    $('#jsQuestionType').change(makeQuestionPreview);
    $('#jsQuestionRatingScale').change(makeQuestionPreview);
    $('#jsQuestionUseLabels').click(makeQuestionPreview);
    $('#jsQuestionIncludeNA').click(makeQuestionPreview);

    //
    //
    if (reviewOBJ.visibility.roles.length) {
        $('#jsReviewVisibilityRoles').select2('val', reviewOBJ.visibility.roles);
    }
    //
    if (reviewOBJ.visibility.departments.length) {
        $('#jsReviewVisibilityDepartments').select2('val', reviewOBJ.visibility.departments);
    }
    //
    if (reviewOBJ.visibility.teams.length) {
        $('#jsReviewVisibilityTeams').select2('val', reviewOBJ.visibility.teams);
    }

    /**
     * Click
     * 
     * Select tempate or use new 
     */
    $('.jsReviewType').click(function(e) { handleReviewChange($(this).val()); });

    /**
     * Click
     * 
     * View Questions
     */
    $('.jsTemplateQuestionsView').click(function(e) {
        e.stopPropagation();
        const t = $(this).closest('.jsTemplateBox').data();
        showTemplateQuestion(t.id, t.type, t.name);
    });

    /**
     * Click
     * 
     * Use template
     */
    $('.jsTemplateBox').click(function(e) {
        $('.jsTemplateBox').removeClass('csBoxActive');
        $(this).addClass('csBoxActive');
        prefillData($(this).data('id'), $(this).data('type'));
    });

    /**
     * 
     */
    $('.jsFinishLater').click(function() {
        ml(true, 'create_review');
        saveReview($('.jsPageSection:visible').find('.jsReviewBackStep').data().to, 1);
    });

    /**
     * Change
     * 
     * Set roles
     */
    $('#jsReviewVisibilityRoles').change(function() {
        reviewOBJ.visibility.roles = $(this).val() || [];
    });

    /**
     * Change
     * 
     * Set departments
     */
    $('#jsReviewVisibilityDepartments').change(function() {
        reviewOBJ.visibility.departments = $(this).val() || [];
    });

    /**
     * Change
     * 
     * Set teams
     */
    $('#jsReviewVisibilityTeams').change(function() {
        reviewOBJ.visibility.teams = $(this).val() || [];
    });

    /**
     * Change
     * 
     * Set individuals
     */
    $('#jsReviewVisibilityIndividuals').change(function() {
        reviewOBJ.visibility.individuals = $(this).val() || [];
    });

    /**
     * Click
     * 
     * Frequency change
     */
    $('.jsReviewFrequency').click(function() {
        reviewOBJ.setIndexValue('frequency', $(this).val(), 'schedule');
        //
        $('.jsFrequencyBox').hide(0);
        //
        if ($(this).val() == 'onetime') {
            $('.jsFrequencyORBox').show();
        } else if ($(this).val() == 'repeat') {
            $('.jsFrequencyORBox').show();
            $('.jsFrequencyRepeatBox').show();
        } else {
            $('.jsFrequencyCustom').show();
            //
            loadCustomRuns(reviewOBJ.schedule.customRuns);
            loadCustomRuns();
        }
    });

    /**
     * Keyup
     * 
     * Store title
     */
    $(reviewOBJ.targets.reviewTitle).keyup(function() {
        reviewOBJ.setTitle($(this).val());
    });

    /**
     * Keyup
     * 
     * Store description
     */
    $(reviewOBJ.targets.reviewDescription).keyup(function() {
        reviewOBJ.setIndexValue('description', $(this).val().trim());
    });

    /**
     * Keyup
     * 
     * Store repeat val
     */
    $(reviewOBJ.targets.reviewRepeatVal).keyup(function() {
        reviewOBJ.setIndexValue('repeatVal', nb($(this).val().trim()), 'schedule');
        $(this).val(nb($(this).val().trim()));
    });

    /**
     * Change
     * 
     * Store repeat type
     */
    $(reviewOBJ.targets.reviewRepeatType).change(function() {
        reviewOBJ.setIndexValue('repeatType', $(this).val(), 'schedule');
    });

    /**
     * Click
     * 
     * Store continue
     */
    $(reviewOBJ.targets.reviewContinue).change(function() {
        reviewOBJ.setIndexValue('continueReview', $(this).val() == 'on' ? 1 : 0, 'schedule');
    });

    /**
     * Keyup
     * 
     * Store review due in
     */
    $(reviewOBJ.targets.reviewDue).keyup(function() {
        reviewOBJ.setIndexValue('reviewDue', nb($(this).val().trim()), 'schedule');
        $(this).val(nb($(this).val().trim()));
    });

    /**
     * Click
     * 
     * Add a custom run
     * 
     * @param   {Object} e
     * @returns {Void}
     */
    $('.jsReviewCustomRunAdd').click(function(e) {
        e.preventDefault();
        loadCustomRuns();
    });

    /**
     * Click
     * 
     * Removes a custom run
     * 
     * @param   {Object} e
     * @returns {Void}
     */
    $(document).on('click', '.jsRunRemove', function(e) {
        e.preventDefault();
        //
        const d = $(this).closest('.jsRunBox');
        //
        if (d.find('input').val() == '') {
            d.remove();
            reviewOBJ.removeCustomRun(d.data('id'));
        } else {

            alertify.confirm(
                    getError('confirm_delete'),
                    function() {
                        d.remove();
                        reviewOBJ.removeCustomRun(d.data('id'));
                    }
                )
                .setHeader('CONFIRM!')
                .set('labels', { ok: "Yes", cancel: "No" });
        }
    });

    /**
     * Keyup
     * 
     * Saves on type
     * 
     * @returns {Void}
     */
    $(document).on('keyup', '.jsRunVal', function() {
        //
        const obj = {
            id: $(this).closest('.jsRunBox').data('id'),
            val: nb($(this).val()),
            type: $(this).closest('.jsRunBox').find('select option:selected').val()
        };
        $(this).val(obj.val);
        //
        if (obj.val != '') {
            reviewOBJ.addCustomRun(obj);
        }
    });

    /**
     * Change
     * 
     * Save on change
     * 
     * @returns {Void}
     */
    $(document).on('change', '.jsRunType', function() {
        //
        const obj = {
            id: $(this).closest('.jsRunBox').data('id'),
            val: nb($(this).closest('.jsRunBox').find('input').val()),
            type: $(this).val()
        };
        //
        if (obj.val != '') {
            reviewOBJ.addCustomRun(obj);
        }
    });

    /**
     * Change
     * 
     * 
     * @returns {Void}
     */
    $('.jsResetFilter').click(resetEmployeeView);
    $('#jsReviewRepeatType').change(makeEmployeeView);
    $('#jsFilterIndividuals').change(makeEmployeeView);
    $('#jsFilterDepartments').change(makeEmployeeView);
    $('#jsFilterTeams').change(makeEmployeeView);
    $('#jsFilterEmploymentType').change(makeEmployeeView);
    $('#jsFilterJobTitles').change(makeEmployeeView);
    $('#jsFilterExcludeNewHires').change(makeEmployeeView);
    $('#jsFilterExcludeEmployees').change(makeEmployeeView);


    /**
     * Record video process
     */
    $('#jsStartVideoRecord').click(function() {
        //
        if ($(this).prop('checked') === true) {
            //
            $('.jsVideoRecorderBox').removeClass('dn');
            cp.init();
        } else {
            $('.jsVideoRecorderBox').addClass('dn');
            cp.close();
            cp.remove();
        }
    });

    /**
     * When use label is selected
     */
    $('#jsQuestionType').change(function() {
        //
        if ($('#jsQuestionType').val() == 'rating' || $('#jsQuestionType').val() == 'text-n-rating') {
            $('.jsQuestionRatingScaleBox').removeClass('dn');
            if ($('#jsQuestionUseLabels').prop('checked') === true) {
                loadRating();
            }
        } else {
            $('.jsQuestionRatingScaleBox').addClass('dn');
        }
    });

    /**
     * When use label is selected
     */
    $('#jsQuestionRatingScale').change(function() {
        if ($('#jsQuestionUseLabels').prop('checked') === true) {
            loadRating();
        }
    });

    /**
     * When use label is selected
     */
    $('#jsQuestionUseLabels').click(function() {
        //
        if ($(this).prop('checked') === true && ($('#jsQuestionType').val() == 'rating' || $('#jsQuestionType').val() == 'text-n-rating')) {
            loadRating();
        } else {
            $('.jsQuestionRatingValBox').addClass('dn');
        }
    });

    /**
     * Click
     * 
     * Save question
     *
     */
    $('.jsSaveQuestion').click(function(e) {
        e.preventDefault();
        //
        let o = {
            title: $('#jsQuestionVal').val().trim(),
            text: $('#jsQuestionDescription').val().trim(),
            question_type: $('#jsQuestionType').val(),
            scale: $('#jsQuestionRatingScale').val(),
            label_question: getRatingScaleLabels(),
            labels_flag: $('#jsQuestionUseLabels').prop('checked') === true ? 1 : 0,
            not_applicable: $('#jsQuestionIncludeNA').prop('checked') === true ? 1 : 0,
            sort_order: reviewOBJ.questions.length
        };
        //
        if (o.title == '') {
            alertify.alert('WARNING!', getError('required_question'), () => {});
            return;
        }
        // Check and save video
        if ($('#jsStartVideoRecord').prop('checked') === true) {
            cp.getVideo().then(function(file) {
                if (file !== 'data:')
                    o.video_help = file;
                //
                let questionIndex = reviewOBJ.setQuestions(o, 'add');
                //
                if (o.video_help !== undefined) {
                    convertVideoToUrl(o.video_help, questionIndex);
                }
                resetQuestionView();
            });
        } else {
            //
            reviewOBJ.setQuestions(o, 'add');
            resetQuestionView();
        }
    });

    /**
     * Click
     * 
     * Move question one step down
     * 
     * @param {Object} e 
     */
    $(document).on('click', '.jsQuestionMoveUp', function(e) {
        e.preventDefault();
        //
        reviewOBJ.sortQuestion($(this).closest('.csQuestionRow').data('id'), 'up');
    });

    /**
     * Click
     * 
     * Move question one step up
     * 
     * @param {Object} e 
     */
    $(document).on('click', '.jsQuestionMoveDown', function(e) {
        e.preventDefault();
        //
        reviewOBJ.sortQuestion($(this).closest('.csQuestionRow').data('id'), 'down');
    });

    /**
     * Click
     * 
     * Clone a question
     * 
     * @param {Object} e 
     */
    $(document).on('click', '.jsQuestionClone', function(e) {
        e.preventDefault();
        //
        const question = Object.assign(reviewOBJ.questions[$(this).closest('.csQuestionRow').data('id')]);
        question['sort_order'] = reviewOBJ.questions.length;
        //
        let questionIndex = reviewOBJ.setQuestions(question, 'add');
        //
        if (question.video_help !== undefined) {
            convertVideoToUrl(question.video_help, questionIndex);
        }
    });


    /**
     * Click
     * 
     * Delete a question
     * 
     * @param {Object} e 
     */
    $(document).on('click', '.jsQuestionDelete', function(e) {
        e.preventDefault();
        // Lets confirm it
        alertify.confirm(getError('confirm_delete'), () => {
            //
            reviewOBJ.questions.splice(
                $(this).closest('.csQuestionRow').data('id'),
                1
            );
            //
            reviewOBJ.remakeQuestionsView();

        }).setHeader('CONFIRM!').set('labels', { ok: "Yes", cancel: "No" });
    });

    /**
     * Click
     * 
     * Edit question
     * 
     * @param {Object} e 
     */
    $(document).on('click', '.jsQuestionEdit', function(e) {
        e.preventDefault();
        //
        const question = reviewOBJ.questions[$(this).closest('.csQuestionRow').data('id')];
        //
        $('.jsPageSection').hide(0);
        $('.jsPageSection[data-key="edit_question"]').show(0);
        //
        $('#jsQuestionTypeEdit').select2({ minimumResultsForSearch: -1 });
        $('#jsQuestionRatingScaleEdit').select2({ minimumResultsForSearch: -1 });
        //
        $('#jsQuestionValEdit').val(question.title);
        $('#jsQuestionDescriptionEdit').val(question.text);

        $('#jsStartVideoRecordEdit').prop('checked', question.video_help !== undefined && question.video_help !== null ? true : false);
        $('#jsQuestionTypeEdit').select2('val', question.question_type);
        $('#jsQuestionRatingScaleEdit').select2('val', question.scale);
        $('#jsQuestionUseLabelsEdit').prop('checked', question.labels_flag == 1 ? true : false);
        $('#jsQuestionIncludeNAEdit').prop('checked', question.not_applicable == 1 ? true : false);
        //
        $('.jsQuestionRatingScaleValBoxEdit').hide(0);
        //
        if ($.inArray(question.question_type, ['rating', 'text-n-rating']) === -1) {
            $('.jsQuestionRatingScaleBoxEdit').addClass('dn');
            $('.jsQuestionRatingValBoxEdit').addClass('dn');
        } else {
            $('.jsQuestionRatingScaleBoxEdit').removeClass('dn');
            if (question.labels_flag == 1) {
                $('.jsQuestionRatingValBoxEdit').removeClass('dn');
                //
                for (let i = 1; i <= question.scale; i++) {
                    $(`.jsQuestionRatingScaleValEdit[data-id="${i}"]`).closest('.jsQuestionRatingScaleValBoxEdit').show();
                    $(`.jsQuestionRatingScaleValEdit[data-id="${i}"]`).val(question.label_question[i]).prop('disabled', true);
                }
            }
        }
        //
        $('.jsVideoPreviewBoxEdit').addClass('dn');
        if (question.video_help !== undefined) {
            $('.jsVideoRecorderBoxEdit').removeClass('dn');
            $('.jsVideoPreview2BoxEdit').removeClass('dn');
            $('#jsVideoRecorderEdit').show(0);
            $('#jsVideoPreview2Edit').prop('src', question.video_help);
            $('#jsVideoPreview2Edit').prop('controls', true);
            $('#').show();
            cp2.init();
        } else {
            $('.jsVideoRecorderBoxEdit').addClass('dn');
            $('.jsVideoPreviewBoxEdit').addClass('dn');
            $('#jsVideoRecorderEdit').hide(0);
        }
        //
        $('#jsQuestionId').val($(this).closest('.csQuestionRow').data('id'));
        //
        $('#jsQuestionReportingManagerEdit').prop('checked', false);
        $('#jsQuestionSelfEdit').prop('checked', false);
        $('#jsQuestionPeerEdit').prop('checked', false);
    });

    /**
     * Record video process
     */
    $('#jsStartVideoRecordEdit').click(function() {
        //
        if ($(this).prop('checked') === true) {
            //
            $('.jsVideoRecorderBoxEdit').removeClass('dn');
            cp2.init();
        } else {
            $('.jsVideoRecorderBoxEdit').addClass('dn');
            cp2.close();
            cp2.remove();
        }
    });

    /**
     * When use label is selected
     */
    $('#jsQuestionTypeEdit').change(function() {
        //
        if ($('#jsQuestionTypeEdit').val() == 'rating' || $('#jsQuestionTypeEdit').val() == 'text-n-rating') {
            $('.jsQuestionRatingScaleBoxEdit').removeClass('dn');
            if ($('#jsQuestionUseLabelsEdit').prop('checked') === true) {
                loadRating('edit');
            }
        } else {
            $('.jsQuestionRatingScaleBoxEdit').addClass('dn');
        }
    });

    /**
     * When use label is selected
     */
    $('#jsQuestionRatingScaleEdit').change(function() {
        if ($('#jsQuestionUseLabelsEdit').prop('checked') === true) {
            loadRating('edit');
        }
    });

    /**
     * When use label is selected
     */
    $('#jsQuestionUseLabelsEdit').click(function() {
        //
        if ($(this).prop('checked') === true && ($('#jsQuestionTypeEdit').val() == 'rating' || $('#jsQuestionTypeEdit').val() == 'text-n-rating')) {
            loadRating('edit');
        } else {
            $('.jsQuestionRatingValBoxEdit').addClass('dn');
        }
    });

    /**
     * Click
     * 
     * Save question
     *
     */
    $('.jsUpdateQuestion').click(function(e) {
        e.preventDefault();
        //
        let o = {
            title: $('#jsQuestionValEdit').val().trim(),
            text: $('#jsQuestionDescriptionEdit').val().trim(),
            question_type: $('#jsQuestionTypeEdit').val(),
            scale: $('#jsQuestionRatingScaleEdit').val(),
            label_question: getRatingScaleLabels(),
            labels_flag: $('#jsQuestionUseLabelsEdit').prop('checked') === true ? 1 : 0,
            not_applicable: $('#jsQuestionIncludeNAEdit').prop('checked') === true ? 1 : 0,
        };
        //
        if (o.title == '') {
            alertify.alert('WARNING!', getError('required_question'), () => {});
            return;
        }
        //
        const question = reviewOBJ.questions[$('#jsQuestionId').val()];
        o.sort_order = question.sort_order;

        // Check and save video
        if ($('#jsStartVideoRecordEdit').prop('checked') === true) {
            cp2.getVideo().then(function(file) {
                if (file !== 'data:')
                    o.video_help = file;
                else o.video_help = question.video_help;
                //
                reviewOBJ.setQuestions(o, 'edit', $('#jsQuestionId').val());
                //
                if (o.video_help !== undefined) {
                    convertVideoToUrl(o.video_help, $('#jsQuestionId').val());
                }
            });
        } else {
            //
            o.video_help = undefined;
            reviewOBJ.setQuestions(o, 'edit', $('#jsQuestionId').val());
        }
    });

    /**
     * 
     */
    $('.jsReviewerType').click(function() {
        reviewOBJ.reviewers.type = $(this).val();
        //
        $('#jsReviewSpecificReviewersBox').addClass('dn');
        $('#jsReviewSpecificReviewers').select2('val', null);
        //
        $('.jsReviewExcludeReviewerBox').addClass('dn');
        //
        $('.jsReviewIncludeReviewerBox').addClass('dn');
        //
        switch ($(this).val()) {
            case "reporting_manager":
                if ($(this).prop('checked') === true) {
                    $('.jsReviewerType[value="self_review"]').prop('checked', false);
                    $('.jsReviewerType[value="peer"]').prop('checked', false);
                    $('.jsReviewerType[value="specific"]').prop('checked', false);
                    addReportingManagers();
                } else {
                    cleanReviewers();
                }
                break;
            case "self_review":
                if ($(this).prop('checked') === true) {
                    $('.jsReviewerType[value="reporting_manager"]').prop('checked', false);
                    $('.jsReviewerType[value="peer"]').prop('checked', false);
                    $('.jsReviewerType[value="specific"]').prop('checked', false);
                    addSelf();
                } else {
                    cleanReviewers();
                }
                break;
            case "peer":
                if ($(this).prop('checked') === true) {
                    $('.jsReviewerType[value="reporting_manager"]').prop('checked', false);
                    $('.jsReviewerType[value="self_review"]').prop('checked', false);
                    $('.jsReviewerType[value="specific"]').prop('checked', false);
                    addPeer();
                } else {
                    cleanReviewers();
                }
                break;
            case "specific":
                cleanReviewers();
                if ($(this).prop('checked') === true) {
                    $('.jsReviewerType[value="self_review"]').prop('checked', false);
                    $('.jsReviewerType[value="peer"]').prop('checked', false);
                    $('.jsReviewerType[value="reporting_manager"]').prop('checked', false);
                    $('#jsReviewSpecificReviewersBox').removeClass('dn');
                } else {
                    $('#jsReviewSpecificReviewersBox').addClass('dn');
                }
                break;
            default:
                cleanReviewers();
                break;
        }
    });

    /**
     * 
     * @param {*} reviewType 
     */
    $('#jsReviewSpecificReviewers').change(function() {
        specificReviewers($(this).val());
    });

    /**
     * 
     */
    $(document).on('click', '.jsIncludeReviewer', function(e) {
        e.preventDefault();
        //
        const target = $(this).closest('.jsRevieweeRow');
        const employeeId = target.data().id;
        //
        let options = '';
        //
        let ids = getIncEclIds(reviewOBJ.reviewers.employees[employeeId]);
        const included = ids['included'];
        //
        pm.employees.map((em) => {
            if ($.inArray(em.Id, ids['excluded']) !== -1) {
                return;
            }
            options += `<option value="${em.Id}" ${$.inArray(em.Id, included) !== -1 ? 'selected' : ''}>${em.FirstName} ${em.LastName} ${em.FullRole}</option>`;
        });
        //
        target.find('.jsReviewIncludeReviewerBox select').html(options).select2({ closeOnSelect: false });
        //
        target.find('.jsReviewExcludeReviewerBox').addClass('dn');
        target.find('.jsReviewIncludeReviewerBox').removeClass('dn');
        target.find('.jsExcludedReviewer').removeClass('btn-orange').addClass('btn-black');
        $(this).removeClass('btn-black').addClass('btn-orange');
        //
        loadFonts();
    });

    /**
     * 
     */
    $(document).on('click', '.jsExcludedReviewer', function(e) {
        e.preventDefault();
        //
        const target = $(this).closest('.jsRevieweeRow');
        const employeeId = target.data().id;
        //
        let options = '';
        //
        let ids = getIncEclIds(reviewOBJ.reviewers.employees[employeeId]);
        const included = ids['included'];
        const excluded = ids['excluded'];
        const all = ids['all'];
        //
        if (all.length > 0) {
            all.map(function(em) {
                //
                options += `<option value="${em}" ${$.inArray(em, excluded) !== -1 ? 'selected' : ''}>${pm.allEmployeesOBJ[em].FirstName} ${pm.allEmployeesOBJ[em].LastName} ${pm.allEmployeesOBJ[em].FullRole}</option>`;
            });
        }
        //
        target.find('.jsReviewExcludeReviewerBox select').html(options).select2({ closeOnSelect: false });
        //
        target.find('.jsReviewIncludeReviewerBox').addClass('dn');
        target.find('.jsReviewExcludeReviewerBox').removeClass('dn');
        target.find('.jsIncludeReviewer').removeClass('btn-orange').addClass('btn-black');
        $(this).removeClass('btn-black').addClass('btn-orange');
        //
        //
        loadFonts();
    });

    /**
     * 
     */
    $(document).on('change', '.jsReviewIncludeReviewerBox select', function() {
        //
        const target = $(this).closest('.jsRevieweeRow');
        const employeeId = target.data().id;
        const reviewers = $(this).val();
        //
        let employees = [];
        //
        if (reviewers !== null) {
            pm.allEmployees.map((em) => {
                if ($.inArray(em.Id, reviewers) !== -1) employees.push(em.Id);
            });
        }
        //
        reviewOBJ.setReviewers(employeeId, 'custom', employees);
        // Set count
        setReviewerCount();
        //
        loadFonts();
    });

    /**
     * 
     */
    $(document).on('change', '.jsReviewExcludeReviewerBox select', function() {
        //
        const target = $(this).closest('.jsRevieweeRow');
        const employeeId = target.data().id;
        const reviewers = $(this).val();
        //
        let employees = [];
        //
        if (reviewers !== null) {
            pm.allEmployees.map((em) => {
                if ($.inArray(em.Id, reviewers) !== -1) employees.push(em.Id);
            });
        }
        //
        reviewOBJ.setReviewers(employeeId, 'excluded', employees);
        // Set count
        setReviewerCount();
        //
        loadFonts();
    });

    /**
     * 
     * @param {*} reviewType 
     */
    $('.jsReviewFeedback').click(function(e) {
        e.preventDefault();
        $('.jsReviewFeedback').removeClass('active');
        $(this).addClass('active');
        //
        reviewOBJ.feedback = $(this).data().share;
    });

    // Functions

    /**
     * Handle review type change 
     * 
     * @param  {String} reviewType (new|template)
     * @return {VOID}
     */
    function handleReviewChange(reviewType) {
        if (reviewType == 'new') {
            $('.jsTemplateWrap').hide(0);
            reviewOBJ.clearReview();
        } else {
            $('.jsTemplateWrap').show(0);
        }
    }

    /**
     * Show template Questions 
     * 
     * @param  {Integer} id
     * @param  {String}  type
     * @param  {String}  name
     * @return {VOID}
     */
    function showTemplateQuestion(id, type, title) {
        $('.csModal').remove();
        Modal({
            Id: 'jsTemplateQuestionsPreview',
            Title: `${title} (${ucwords(type)} Template)`,
            Button: [],
            Loader: 'jsTemplateQuestionsPreviewLoader',
            Body: '<div id="jsTemplateQuestionsPreviewBody"></div>'
        }, async function() {
            //
            const resp = await getTemplateQuestionsPreview(id, type);
            // On Redirect
            if (resp.Redirect === true) {
                $('#jsTemplateQuestionsPreview .jsModalCancel').click();
                handleRedirect();
                return;
            }
            // On Failure
            if (resp.Status === false) {
                $('#jsTemplateQuestionsPreview .jsModalCancel').click();
                alertify.alert('WARNING!', resp.Response, function() {});
                return;
            }
            // On Success
            $('#jsTemplateQuestionsPreviewBody').html(resp.Data);
            //
            //
            loadFonts();
            ml(false, 'jsTemplateQuestionsPreviewLoader');
        });
    }

    /**
     * Prefill data of review
     * 
     * @param {Integer} id
     */
    async function prefillData(id, type) {
        const resp = await getTemplateQuestions(id, type);
        // On Redirect
        if (resp.Redirect === true) {
            handleRedirect();
            return;
        }
        // On Failure
        if (resp.Status === false) {
            alertify.alert('WARNING!', resp.Response, function() {});
            return;
        }
        //
        reviewOBJ.setTitle(resp.Data.name);
        reviewOBJ.setQuestions(resp.Data.questions);
    }

    /**
     * Load custom runs
     * 
     * @param  {Array} runs
     * @return {Void}
     */
    function loadCustomRuns(runs) {
        let html = '';
        //
        if (runs !== undefined) {
            runs.map(function(d) {
                boxCount++;
                //
                html += `
                <div class="jsRunBox ma10" data-id="${d.id}">
                    <div class="row">
                        <div class="csRunBox1">
                            <div class="col-sm-2 col-xs-12">
                                <input type="text" class="form-control csRadius100 jsRunVal csF16"
                                    placeholder="0" value="${d.val}" />
                            </div>
                            <div class="col-sm-2 col-xs-12">
                                <select class="jsRunType">
                                    <option ${d.type == 'days' ? 'selected' : ''} value="days">Days</option>
                                    <option ${d.type == 'weeks' ? 'selected' : ''} value="weeks">Weeks</option>
                                    <option ${d.type == 'months' ? 'selected' : ''} value="months">Months</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <p class="csF16 csB1">After Reviewee's Hire Date.</p>
                            </div>
                            <div class="col-sm-1 col-xs-12 pa10 jsRunRemove" title="Delete this row" placement="top">
                                <i class="fa fa-trash text-danger csF16 csB1"></i>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            //
            $('#jsReviewCustomRunWrap').html(html);
        } else {
            //
            html += `
            <div class="jsRunBox ma10" data-id="${boxCount}">
                <div class="row">
                    <div class="csRunBox1">
                        <div class="col-sm-2 col-xs-12">
                            <input type="text" class="form-control csRadius100 jsRunVal csF16"
                                placeholder="0" />
                        </div>
                        <div class="col-sm-2 col-xs-12">
                            <select class="jsRunType">
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <p class="csF16 csB1">After Reviewee's Hire Date.</p>
                        </div>
                        <div class="col-sm-1 col-xs-12 pa10 jsRunRemove" title="Delete this row" placement="top">
                            <i class="fa fa-trash text-danger csF16 csB1"></i>
                        </div>
                    </div>
                </div>
            </div>`;
            //
            $('#jsReviewCustomRunWrap').append(html);
        }
        //
        $('.jsRunType').select2({ minimumResultsForSearch: -1 });
        //
        boxCount++;
    }

    /**
     * 
     */
    function loadRating(type) {
        type = type === undefined ? '' : type;
        $(`.jsQuestionRatingValBox${type}`).removeClass('dn');
        //
        $(`.jsQuestionRatingScaleValBox${type}`).hide();
        let i = 1;
        //
        while (i <= $(`#jsQuestionRatingScale${type}`).val()) {
            //
            $(`.jsQuestionRatingScaleVal${type}[data-id="${i}"]`).closest(`.jsQuestionRatingScaleValBox${type}`).show().prop('disabled', true);
            //
            i++;
        }
    }

    /**
     * 
     */
    function getRatingScaleLabels() {
        let d = {};

        $('.jsQuestionRatingScaleVal').map(function() {
            if ($(this).parent().is(":visible")) d[$(this).data('id')] = $(this).val();
        });

        return d;
    }

    /**
     * 
     */
    function addReportingManagers() {
        reviewOBJ.reviewees.included.map((rewiewee) => {
            //
            reviewOBJ.setReviewers(rewiewee.Id, 'reporting_manager', getReportingManagers(rewiewee.DT.Departments, rewiewee.DT.Teams));
        });
        // Set count
        setReviewerCount();
    }

    /**
     * 
     */
    function addSelf() {
        reviewOBJ.reviewees.included.map((rewiewee) => {
            //
            reviewOBJ.setReviewers(rewiewee.Id, 'self_review', [rewiewee.Id]);
        });
        // Set count
        setReviewerCount();
    }

    /**
     * 
     */
    function addPeer() {
        reviewOBJ.reviewees.included.map((rewiewee) => {
            reviewOBJ.setReviewers(rewiewee.Id, 'peer', getMyPeer(rewiewee.DT.Teams, rewiewee.Id));
        });
        // Set count
        setReviewerCount();
    }

    /**
     * 
     * @param {Integer} teamIds 
     */
    function getReportingManagers(departmentIds, teamIds) {
        let managers = []
            //
        pm.allEmployees.map(function(em) {
            if (_.intersection(departmentIds, em.Manager.Departments).length > 0 || _.intersection(teamIds, em.Manager.Teams).length > 0) {
                managers.push(em.Id);
            }
        });

        return managers;
    }

    /**
     * 
     * @param {Integer} teamIds
     * @param {Integer} employeeId
     */
    function getMyPeer(teamIds, employeeId) {
        //
        let employeeIds = [];
        //
        pm.allEmployees.map((em) => {
            if (_.intersection(em.DT.Teams, teamIds).length > 0 && em.Id != employeeId) {
                employeeIds.push(em.Id);
            }
        });
        //
        return employeeIds;
    }

    /**
     * 
     * @param {Array|Null} reviewers 
     */
    function specificReviewers(reviewers) {
        //
        let employees = [];
        //
        if (reviewers !== null) {
            pm.allEmployees.map((em) => {
                if ($.inArray(em.Id, reviewers) !== -1) employees.push(em.Id);
            });
        }
        //
        reviewOBJ.reviewees.included.map((reviewee) => {
            reviewOBJ.setReviewers(reviewee.Id, 'specific', employees);
        });

        // Set count
        setReviewerCount();
    }

    /**
     * 
     */
    function cleanReviewers() {
        reviewOBJ.reviewees.included.map((reviewee) => {
            if (reviewOBJ.reviewers.employees[reviewee.Id] !== undefined) {
                reviewOBJ.reviewers.employees[reviewee.Id]['reporting_manager'] = undefined;
                reviewOBJ.reviewers.employees[reviewee.Id]['self_review'] = undefined;
                reviewOBJ.reviewers.employees[reviewee.Id]['peer'] = undefined;
                reviewOBJ.reviewers.employees[reviewee.Id]['specific'] = undefined;
                //
                if (reviewOBJ.reviewers.employees[reviewee.Id]['custom'] === undefined) {
                    reviewOBJ.reviewers.employees[reviewee.Id]['exclude'] = undefined;
                }
            }
        });
        setReviewerCount();
    }

    /**
     * 
     * @param {Object} o 
     */
    function getIncEclIds(o) {
        o = o === undefined ? {} : o;
        //
        let e = {
            included: [],
            excluded: o.excluded || [],
            all: []
        };
        //
        if (o === null) return e;
        //
        if (o.reporting_manager !== undefined) e.included = o.reporting_manager;
        else if (o.self_review !== undefined) e.included = o.self_review;
        else if (o.peer !== undefined) e.included = o.peer;
        else if (o.specific !== undefined) e.included = o.specific;
        //
        if (o.custom !== undefined) {
            e.included = _.concat(e.included, o.custom);
        }
        //
        e.excluded = _.uniq(e.excluded);
        //
        if (e.excluded.length > 0) {
            // Remove excluded from included
            e.included.map(function(i, v) {
                if ($.inArray(v.Id, e.excluded) !== -1) {
                    e.included.splice(i, 1);
                }
            });
        }
        //
        e.included = _.uniq(e.included);
        //
        e.all = _.uniq(_.concat(e.included, e.excluded));
        //
        return e;
    }

    /**
     * 
     */
    function setReviewerCount() {
        $.each(reviewOBJ.reviewers.employees, function(i, reviewer) {
            //
            let e = getIncEclIds(reviewer);
            //
            $(`.jsRevieweeRow[data-id="${i}"]`).find('.jsIncludedCount').text(e.all.length - e.excluded.length);
            $(`.jsRevieweeRow[data-id="${i}"]`).find('.jsExcludedCount').text(e.excluded.length);
        });
    }

    //
    window.setReviewerCount = setReviewerCount;

    // Hide loader
    ml(false, 'create_review');
});