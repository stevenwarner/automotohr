// Create create review object
const reviewOBJ = {
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
    reviewers: {},
    questions: [],
    targets: {
        reviewTitleHeader: $('#jsReviewTitleHeader'),
        reviewTitle: $('#jsReviewTitle'),
        reviewDescription: $('#jsReviewDescription'),
        reviewRepeatVal: $('#jsReviewRepeatVal'),
        reviewRepeatType: $('#jsReviewRepeatType'),
        reviewContinue: $('#jsReviewContinue'),
        reviewDue: $('#jsReviewDue'),
        finishLater: $('.jsFinishLater')
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
    setQuestions: function(questions) {
        this.questions = questions;
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
    //
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
            break;
    }
    //
    cb();
}