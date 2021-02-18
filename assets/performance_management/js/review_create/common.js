/**
 * 
 */
const
    reviewOBJ = {
        id: 0,
        title: '',
        description: '',
        visibility: {
            roles: [],
            departments: [],
            teams: [],
            individuals: []
        },
        schedule: {
            frequency: 'onetime',
            reviewStartDate: '',
            reviewEndDate: '',
            repeatVal: 0,
            repeatType: 'days',
            continueReview: 0,
            reviewDue: 0,
            reviewDueType: 'days',
            customRuns: []
        },
        reviewees: {
            included: [],
            excluded: []
        },
        reviewers: {
            type: '',
            employees: {}
        },
        questions: [],
        targets: {
            reviewTitleHeader: $('#jsReviewTitleHeader'),
            reviewTitle: $('#jsReviewTitle'),
            reviewDescription: $('#jsReviewDescription'),
            reviewRepeatVal: $('#jsReviewRepeatVal'),
            reviewRepeatType: $('#jsReviewRepeatType'),
            reviewContinue: $('#jsReviewContinue'),
            reviewDue: $('#jsReviewDue'),
            finishLater: $('.jsFinishLater'),
            questionsView: $('.jsQuestionViewWrap')
        },
        // Functions
        // Clear review details
        clearReview: function() {
            this.title = '';
            this.description = '';
            this.questions = [];
        },
        // Set review title
        setTitle: function(title) {
            this.title = title;
            this.targets.reviewTitle.val(this.title);
            //
            this.targets.reviewTitleHeader.text(this.title != '' ? ` - ${this.title}` : '');
            //
            if (this.title == '') this.targets.finishLater.addClass('dn');
            else this.targets.finishLater.removeClass('dn');
        },
        // Set review questions
        setQuestions: function(questions, type, id) {
            if (type == 'add') this.questions.push(questions);
            else if (type == 'edit') {
                this.questions[id] = questions;
            } else if (type === undefined) this.questions = questions;
            //
            this.remakeQuestionsView();
        },
        // Set index
        setIndexValue: function(index, value, sub) {
            if (sub !== undefined) this[sub][index] = value;
            else this[index] = value;
        },
        // Add custom run
        addCustomRun: function(data) {
            //
            let index = -1;
            //
            if (this.schedule.customRuns.length === 0) {
                this.schedule.customRuns.push(data);
                return;
            } else {
                this.schedule.customRuns.map(function(d, k) {
                    if (d.id === data.id) index = k;
                });
                //
                if (index === -1) this.schedule.customRuns.push(data);
                else this.schedule.customRuns[index] = data;
            }
        },
        // Remove custom run
        removeCustomRun: function(index) {
            this.schedule.customRuns.map((d, k) => {
                if (d.id === index) this.schedule.customRuns.splice(k, 1);
            });

        },
        //
        sortQuestion: function(index, direction) {
            //
            if (direction == 'up') {
                const previous = Object.assign(reviewOBJ.questions[index - 1]);
                reviewOBJ.questions[index - 1] = reviewOBJ.questions[index];
                reviewOBJ.questions[index] = previous;
            } else {
                const next = Object.assign(reviewOBJ.questions[index + 1]);
                reviewOBJ.questions[index + 1] = reviewOBJ.questions[index];
                reviewOBJ.questions[index] = next;
            }
            //
            this.remakeQuestionsView();
        },
        //
        remakeQuestionsView: function() {
            //
            rows = '';
            if (this.questions.length === 0) {
                rows += `
            <div class="csQuestionRow">
                <h4 class="alert alert-info text-center">You haven't added any questions.</h4>
            </div>`;
            } else {
                //
                const ql = this.questions.length - 1;
                //
                this.questions.map(function(question, index) {
                    rows += getQuestionRow(question, index, ql);
                });
            }
            //
            this.targets.questionsView.html(rows);
            //
            $('.jsReviewStep[data-to="questions"]').click();
        }
    },

    //
    cp = new mVideoRecorder({
        recorderPlayer: 'jsVideoRecorder',
        previewPlayer: 'jsVideoPreview',
        recordButton: 'jsVideoRecordButton',
        playRecordedVideoBTN: 'jsVideoPlayVideo',
        removeRecordedVideoBTN: 'jsVideoRemoveButton',
        pauseRecordedVideoBTN: 'jsVideoPauseButton',
        resumeRecordedVideoBTN: 'jsVideoResumeButton',
    }),
    //
    cp2 = new mVideoRecorder({
        recorderPlayer: 'jsVideoRecorderEdit',
        previewPlayer: 'jsVideoPreviewEdit',
        recordButton: 'jsVideoRecordButtonEdit',
        playRecordedVideoBTN: 'jsVideoPlayVideoEdit',
        removeRecordedVideoBTN: 'jsVideoRemoveButtonEdit',
        pauseRecordedVideoBTN: 'jsVideoPauseButtonEdit',
        resumeRecordedVideoBTN: 'jsVideoResumeButtonEdit',
    }),

    /**
     * 
     */
    stepCaller = {
        'reviewers': loadReviewerStep,
        'questions': loadQuestionStep
    };


/**
 * Click
 * 
 * Review step
 * 
 * @param {Object} e 
 */
$('.jsReviewStep').click(function(e) {
    e.preventDefault();
    //
    const step = $(this).data('to');
    //
    validateStep(step, () => {
        //
        $('li.jsReviewStep').removeClass('active');
        $('li.jsReviewStep i').remove();
        //
        if (e.currentTarget.localName == 'li') {
            $(this).addClass('active');
            $(this).append('<i class="fa fa-long-arrow-right"></i>');
        } else {
            $(`li.jsReviewStep[data-to="${step}"]`).addClass('active');
            $(`li.jsReviewStep[data-to="${step}"]`).append('<i class="fa fa-long-arrow-right"></i>');
        }
        //
        if (reviewOBJ.title != '') {
            $('.jsFinishLater').show();
        } else {
            $('.jsFinishLater').hide();
        }
        //
        if (stepCaller[step] !== undefined) stepCaller[step]();
        //
        $('.jsPageSection').hide(0);
        $(`.jsPageSection[data-key="${step}"]`).show(0);
    })
});

/**
 * Click
 * 
 * Review back step
 * 
 * @param {Object} e 
 */
$('.jsReviewBackStep').click(function(e) {
    e.preventDefault();
    //
    const step = $(this).data('to');
    //
    if (reviewOBJ.title != '') {
        $('.jsFinishLater').show();
    } else {
        $('.jsFinishLater').hide();
    }
    //
    $('.jsPageSection').hide(0);
    $(`.jsPageSection[data-key="${step}"]`).show(0);
});

/**
 * Get template questions preview
 * 
 * @param  {Integer}  id
 * @param  {String}   type
 * @return {Promise}
 */
function getTemplateQuestionsPreview(id, type) {
    return new Promise(function(res) {
        $.get(`${urls.handler}get/template-questions-h/${id}/${type}`)
            .done(function(resp) { res(resp); })
            .fail(function(resp) {
                res(getMessage(resp.status, true));
            });
    });
}

/**
 * Get template by id
 * 
 * @param  {Integer}  id
 * @param  {String}   type
 * @return {Promise}
 */
function getTemplateQuestions(id, type) {
    return new Promise(function(res) {
        $.get(`${urls.handler}get/template/${id}/${type}`)
            .done(function(resp) { res(resp); })
            .fail(function(resp) {
                res(getMessage(resp.status, true));
            });
    });
}

/**
 * Validate step
 * 
 * @param  {String}   step
 * @param  {Function} cb
 * @return {Void}
 */
function validateStep(step, cb) {
    switch (step) {
        case "reviewees":
            if (reviewOBJ.title == '') {
                alertify.alert('WARNING!', getError('required_review_title'), function() {});
                return false;
            }
            //
            if (reviewOBJ.schedule.frequency != 'custom') {
                if (reviewOBJ.schedule.reviewStartDate == '') {
                    alertify.alert('WARNING!', getError('required_review_start_date'), function() {});
                    return false;
                }
                if (reviewOBJ.schedule.reviewEndDate == '') {
                    alertify.alert('WARNING!', getError('required_review_end_date'), function() {});
                    return false;
                }
            }
            //
            if (reviewOBJ.schedule.frequency == 'repeat') {
                if (reviewOBJ.schedule.repeatVal == 0) {
                    alertify.alert('WARNING!', getError('required_review_repeat_val'), function() {});
                    return false;
                }
            }
            //
            if (reviewOBJ.schedule.frequency == 'custom') {
                if (reviewOBJ.schedule.reviewDue == 0) {
                    alertify.alert('WARNING!', getError('required_review_due_val'), function() {});
                    return false;
                }
            }
            // Save the review
            saveReview('schedule');
            break;
        case "reviewers":
            if (reviewOBJ.reviewees.included.length == 0) {
                alertify.alert('WARNING!', getError('required_review_reviewees'), function() {});
                return false;
            }
            saveReview('reviewees');
            break;
        case "questions":
            if (Object.keys(reviewOBJ.reviewers.employees).length == 0) {
                alertify.alert('WARNING!', getError('required_review_reviewers'), function() {});
                return false;
            }
            saveReview('reviewees');
            break;
    }
    //
    cb();
}

/**
 * Get employees list with dnt
 * 
 * @return {Promise}
 */
function getEmployeeListWithDnT() {
    return new Promise(function(res) {
        $.get(`${urls.handler}get/employeeListWithDnT`)
            .done(function(resp) { res(resp); })
            .fail(function(resp) {
                res(getMessage(resp.status, true));
            });
    });
}

/**
 * Make employee view
 * 
 * @param  {Boolean|Undefined} byPass
 * @return {Void}
 */
function makeEmployeeView(byPass) {
    //
    ml(true, 'review_incexc');
    //
    $('#jsReviewIncludedWrap').html('');
    $('#jsReviewExcludedWrap').html('');
    //
    const allEmployees = window.performanceManagement.employees;
    //
    let inc = [];
    let exc = [];

    if (byPass === true) {
        inc = allEmployees;
    } else {
        //
        const filter = {
            individuals: $('#jsFilterIndividuals').val() || [],
            departments: $('#jsFilterDepartments').val() || [],
            teams: $('#jsFilterTeams').val() || [],
            employmentTypes: $('#jsFilterEmploymentType').val() || [],
            jobTitles: $('#jsFilterJobTitles').val() || [],
            excludedIndividuals: $('#jsFilterExcludeEmployees').val() || [],
            excludedNewHires: $('#jsFilterExcludeNewHires').val() || 0
        };
        //
        allEmployees.map(function(em) {
            // Excluded employee
            //
            if ($.inArray(em.userId, filter.excludedIndividuals) !== -1) {
                exc.push(em);
                return;
            }
            //
            if (moment().diff(moment(em.joined_at, dateTimeFormats.ymd), 'days') <= filter.excludedNewHires) {
                exc.push(em);
                return;
            }

            // Included employee
            //
            if ($.inArray(em.userId, filter.individuals) !== -1) {
                inc.push(em);
                return;
            }
            //
            if (_.intersection(em.departmentIds, filter.departments).length > 0) {
                inc.push(em);
                return;
            }
            //
            if (_.intersection(em.teamIds, filter.teams).length > 0) {
                inc.push(em);
                return;
            }
            //
            if ($.inArray(em.employee_type, filter.employmentTypes) !== -1) {
                inc.push(em);
                return;
            }
            //
            if ($.inArray(em.job_title, filter.jobTitles) !== -1) {
                inc.push(em);
                return;
            }

            //
            inc.push(em);
        });
    }
    //
    reviewOBJ.reviewees.included = inc;
    reviewOBJ.reviewees.excluded = exc;
    //
    $('#jsIncudedEmployeeCount').text(`(${inc.length})`);
    $('#jsExcludedEmployeeCount').text(`(${exc.length})`);
    //
    if (inc.length > 0) {
        let rows = '';
        inc.map(function(ie) {
                    rows += `
            <tr>
                <td>
                    <div class="csEBox">
                        <figure>
                            <img src="${getImageURL(ie.profile_picture)}"
                                class="csRadius50" />
                        </figure>
                        <div class="csEBoxText">
                            <h4 class="mb0"><strong>${ie.first_name} ${ie.last_name}</strong></h4>
                            <p class="mb0">${remakeEmployeeName(ie, false)}</p>
                            <p>${moment(ie.joined_at, dateTimeFormats.ymt).format(dateTimeFormats.mdy)}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <h5>${ie.departmentIds.length > 0 ? $(`#jsFilterDepartments option[value="${ie.departmentIds[0]}"]`).text() : '-'}</h5>
                </td>
            </tr>
            `;
        });
        $('#jsReviewIncludedWrap').html(rows);
    } else{
        $('#jsReviewIncludedWrap').html('<tr><td colspan="2"><p class="alert alert-info text-center">No included employees were found.</p></td></tr>');
    }
    //
    if (exc.length > 0) {
        let rows = '';
        exc.map(function(ie) {
                    rows += `
            <tr>
                <td>
                    <div class="csEBox">
                        <figure>
                            <img src="${getImageURL(ie.profile_picture)}"
                                class="csRadius50" />
                        </figure>
                        <div class="csEBoxText">
                            <h4 class="mb0"><strong>${ie.first_name} ${ie.last_name}</strong></h4>
                            <p class="mb0">${remakeEmployeeName(ie, false)}</p>
                            <p>${moment(ie.joined_at, dateTimeFormats.ymt).format(dateTimeFormats.mdy)}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <h5>${ie.departmentIds.length > 0 ? $(`#jsFilterDepartments option[value="${ie.departmentIds[0]}"]`).text() : '-'}</h5>
                </td>
            </tr>
            `;
        });
        $('#jsReviewExcludedWrap').html(rows);
    } else{
        $('#jsReviewExcludedWrap').html('<tr><td colspan="2"><p class="alert alert-info text-center">No excluded employees were found.</p></td></tr>');
    }
    //
    ml(false, 'review_incexc');
}

/**
 * 
 */
function loadReviewerStep(){
    $('#jsReviewTotalRevieweeCount').text(`(${reviewOBJ.reviewees.included.length})`);
    //
    let rows = '';
    reviewOBJ.reviewees.included.map(function(em){
        rows += `
        <!-- Row -->
        <div class="csAddReviewerSection jsRevieweeRow" data-id="${em.userId}">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <div class="csEBox">
                        <figure>
                            <img src="${getImageURL(em.profile_picture)}" class="csRadius50" />
                        </figure>
                        <div class="csEBoxText">
                            <h4 class="mb0"><strong>${em.first_name} ${em.last_name}</strong></h4>
                            <p class="mb0">${remakeEmployeeName(em, false)}</p>
                            <p>${moment(em.joined_at, dateTimeFormats.ymd).format(dateTimeFormats.mdy)}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 col-xs-12">
                    <ul>
                        <li class="jsPopoverHover"><span>Included Reviewers (0)</span>,</li>
                        <li><span>Excluded Reviewers (0)</span></li>
                    </ul>
                    <button  class="btn  btn-link pl0">
                        <i class="fa fa-plus-circle"></i> Include Reviewer
                    </button>
                    <button  class="btn  btn-link pl0">
                        <i class="fa fa-plus-circle"></i> Exclude Reviewer
                    </button>
                    <div class="dn" class="jsReviewIncludeReviewerBox">
                        <select multiple="multiple"></select>
                    </div>
                    <div class="dn" class="jsReviewExcludeReviewerBox">
                        <select multiple="multiple"></select>
                    </div>
                </div>
            </div>
        </div>
        `;
    });
    //
    $('#jsReviewRevieweeWrap').html(rows);
    //
    $('.jsPopoverHover').popover({
        title: 'Included Reviewers',
        placement: "top auto"
        // template:
    });
};

/**
 * Start question
 */
function loadQuestionStep(){
    resetQuestionView();
}

/**
 * 
 */
function resetQuestionView() {
    //
    $('#jsQuestionType').select2({ minimumResultsForSearch: -1 });
    $('#jsQuestionRatingScale').select2({ minimumResultsForSearch: -1 });
    //
    $('#jsQuestionVal').val('');
    $('#jsQuestionVal').val('Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta excepturi?');
    $('#jsQuestionDescription').val('');
    $('#jsQuestionDescription').val('veritatis quia alias molestias eveniet necessitatibus hic porro odit saepe similique ipsa nisi quas corrupti accusantium itaque, molestiae harum reiciendis.');
    $('#jsStartVideoRecord').prop('checked', false);
    $('#jsQuestionType').select2('val', 'text');
    $('#jsQuestionRatingScale').select2('val', 1);
    $('.jsQuestionRatingScaleBox').addClass('dn');
    $('#jsQuestionUseLabels').prop('checked', false);
    $('#jsQuestionIncludeNA').prop('checked', false);
    $('.jsQuestionRatingValBox').addClass('dn');
    $('#jsQuestionReportingManager').prop('checked', false);
    $('#jsQuestionSelf').prop('checked', false);
    $('#jsQuestionPeer').prop('checked', false);
    $('.jsVideoRecorderBox').addClass('dn');
    //
    cp.close();
    cp.remove();
}

/**
 * 
 * @param {*} step 
 * @param {*} doRedirect 
 */
async function saveReview(step, doRedirect){
    //
    if(reviewOBJ.id == 0){
        reviewOBJ.id = await getSavedReviewId();
    }
    //
    switch(step){
        case "schedule":
            await updateReview({
                step: step,
                title: reviewOBJ.title, 
                description: reviewOBJ.description,
                schedule: reviewOBJ.schedule,
                visibility: reviewOBJ.visibility
            });
        break;
        case "reviewees":
            await updateReview({
                step: step,
                reviewees: {
                    included: reviewOBJ.reviewees.included.arrayColumn('userId') || [],
                    excluded: reviewOBJ.reviewees.excluded.arrayColumn('userId') || []
                }
            });
        break;
    }
    //
    if(doRedirect !== undefined){
        //
        alertify.alert('SUCCESS!', getError('finish_later_review'), function(){
            window.location.href = urls.base + '/performance-management/reviews';
        });
    }
}

/**
 * 
 */
function getSavedReviewId(){
    return new Promise((res) => {
        $.post(
            urls.handler,{
                action: 'save_review',
                title: reviewOBJ.title, 
                description: reviewOBJ.description,
                schedule: reviewOBJ.schedule,
                visibility: reviewOBJ.visibility
            }, (resp) => {
                if(resp.Redirect === true){
                    handleRedirect();
                    return;
                }
                reviewOBJ.id = resp.Data;
            }
        );
    });
}

/**
 * 
 * @param {*} data 
 */
function updateReview(data){
    return new Promise((res) => {
        $.post(
            urls.handler, Object.assign({action: 'update_review', id: reviewOBJ.id}, data), (resp) => {
                if(resp.Redirect === true){
                    handleRedirect();
                    return;
                }
                res();
            }
        );
    });
}

// Calls
// Get employees with D&T
getEmployeeListWithDnT()
    .then(function(resp) {
        if (resp.Data !== undefined && resp.Data.length > 0) {
            window.performanceManagement.employees = resp.Data;
            //
            let options = '';
            let tmpIds = {};
            //
            window.performanceManagement.employees.map(function(em) {
                tmpIds[em.userId] = em;
                options += `<option value="${em.userId}">${remakeEmployeeName(em)}</option>`;
            });
            $('#jsFilterIndividuals').html(options).select2();
            $('#jsFilterExcludeEmployees').html(options).select2();
            $('#jsReviewSpecificReviewers').html(options).select2();
            $('#jsReviewVisibilityIndividuals').html(options).select2({closeOnSelect: false});
            //
            makeEmployeeView(true);

            //
            if (typeof(dnt) !== 'undefined') {
                window.performanceManagement.departments = {};
                dnt.departments.map((rec) => {
                    window.performanceManagement.departments[rec.sid] = [];
                    //
                    rec.supervisor.split(',').map((i) => {
                        window.performanceManagement.departments[rec.sid].push(tmpIds[i]);
                    });
                });
                //
                window.performanceManagement.teams = {};
                dnt.teams.map((rec) => {
                    window.performanceManagement.teams[rec.sid] = [];
                    //
                    rec.team_lead.split(',').map((i) => {
                        window.performanceManagement.teams[rec.sid].push(tmpIds[i]);
                    });
                });
                //
            }
        }
    });