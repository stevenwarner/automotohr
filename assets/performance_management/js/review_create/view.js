$(function() {
    // Declerations
    let boxCount = 1;

    // Binds

    $('#jsReviewStartDate').datepicker({
        changeYear: true,
        changeMonth: true,
        minDate: 0,
        formatDate: dateTimeFormats.ymdf,
        onSelect: function(d) {
            $('#jsReviewEndDate').datepicker('option', 'minDate', d);
            reviewOBJ.setIndexValue('reviewStartDate', d, 'schedule');
        }
    });

    $('#jsReviewEndDate').datepicker({
        changeYear: true,
        changeMonth: true,
        minDate: 0,
        formatDate: dateTimeFormats.ymdf,
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
                reviewOBJ.setQuestions(o, 'add');
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
        reviewOBJ.setQuestions(question, 'add');
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

        $('#jsStartVideoRecordEdit').prop('checked', question.video_help !== undefined ? true : false);
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
                    $(`.jsQuestionRatingScaleValEdit[data-id="${i}"]`).val(question.label_question[i]);
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
    $('.jsReviewerType').change(function() {
        reviewOBJ.reviewers.type = $(this).val();
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
                                <input type="text" class="form-control csRadius100 jsRunVal"
                                    placeholder="0" class="jsRunVal" value="${d.val}" />
                            </div>
                            <div class="col-sm-2 col-xs-12">
                                <select class="jsRunType">
                                    <option ${d.type == 'days' ? 'selected' : ''} value="days">Days</option>
                                    <option ${d.type == 'weeks' ? 'selected' : ''} value="weeks">Weeks</option>
                                    <option ${d.type == 'months' ? 'selected' : ''} value="months">Months</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <h5>After Reviewee's Hire Date</h5>
                            </div>
                            <div class="col-sm-1 col-xs-12 pa10 jsRunRemove" title="Delete this row" placement="top">
                                <i class="fa fa-trash text-danger"></i>
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
                            <input type="text" class="form-control csRadius100 jsRunVal"
                                placeholder="0" class="jsRunVal" />
                        </div>
                        <div class="col-sm-2 col-xs-12">
                            <select class="jsRunType">
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <h5>After Reviewee's Hire Date</h5>
                        </div>
                        <div class="col-sm-1 col-xs-12 pa10 jsRunRemove" title="Delete this row" placement="top">
                            <i class="fa fa-trash text-danger"></i>
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
            $(`.jsQuestionRatingScaleVal${type}[data-id="${i}"]`).closest(`.jsQuestionRatingScaleValBox${type}`).show();
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








    // Hide loader
    ml(false, 'create_review');
});