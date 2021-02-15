$(function() {
    // Declerations
    let boxCount = 1;

    //
    const cp = new mVideoRecorder({
        recorderPlayer: 'jsVideoRecorder',
        previewPlayer: 'jsVideoPreview',
        recordButton: 'jsVideoRecordButton',
        playRecordedVideoBTN: 'jsVideoPlayVideo',
        removeRecordedVideoBTN: 'jsVideoRemoveButton',
        pauseRecordedVideoBTN: 'jsVideoPauseButton',
        resumeRecordedVideoBTN: 'jsVideoResumeButton',
    });

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
    $('#jsReviewRepeatType').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterIndividuals').select2();
    $('#jsFilterDepartments').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterTeams').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterEmploymentType').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterJobTitles').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterExcludeNewHires').select2({ minimumResultsForSearch: -1 });
    $('#jsFilterExcludeEmployees').select2();
    $('#jsReviewSpecificReviewers').select2();
    $('#jsQuestionType').select2({ minimumResultsForSearch: -1 });
    $('#jsQuestionRatingScale').select2({ minimumResultsForSearch: -1 });


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
                o.video_help = file;
                //
                reviewOBJ.setQuestions(o, 'add');
            });
        } else {
            //
            reviewOBJ.setQuestions(o, 'add');
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
    function loadRating() {
        $('.jsQuestionRatingValBox').removeClass('dn');
        //
        $(`.jsQuestionRatingScaleValBox`).hide();
        let i = 1;
        //
        while (i <= $('#jsQuestionRatingScale').val()) {
            //
            $(`.jsQuestionRatingScaleVal[data-id="${i}"]`).closest('.jsQuestionRatingScaleValBox').show();
            //
            i++;
        }
    }

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