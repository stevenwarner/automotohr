//
let fontList = {
    csF12: 12,
    csF14: 14,
    csF16: 16,
    csF18: 18,
    csF20: 20,
    csF22: 22,
    csF24: 24,
    csF26: 26,
    csF28: 28
};

/**
 * Click
 * 
 * Triggers when page modal closes
 * 
 * @param  {Object} e
 * @return {Void}
 */
$(document).on('click', '.jsModalCancel', (e) => {
    //
    e.preventDefault();
    //
    if ($(e.target).data('ask') != undefined) {
        //
        alertify.confirm(
            'Any unsaved changes will be lost.',
            () => {
                //
                $(e.target).closest('.csModal').fadeOut(300);
                //
                $('body').css('overflow-y', 'auto');
            }
        ).set('labels', {
            ok: 'LEAVE',
            cancel: 'NO, i WILL STAY'
        }).set(
            'title', 'Notice!'
        );
    } else {
        //
        $(e.target).closest('.csModal').fadeOut(300);
        //
        $('.csModal').remove();
        //
        $('body').css('overflow-y', 'auto');
    }
});

/**
 * Click
 * 
 * Triggers on filter button
 * 
 * @param  {Object} e
 * @return {Void}
 */
$('.jsFilterBtn').click(function(e) {
    e.preventDefault();
    $(`.${$(this).data('target')}`).toggle();
});

/**
 * Click
 * 
 * Triggers on top menu on mobile
 * 
 * @param  {Object} e
 * @return {Void}
 */
$('.csMobile span i').click(function(e) { $('.csVertical').toggle(); });

/**
 * Click
 * 
 * Handle tab shift
 * 
 */
$('.jsShiftTab').click(function(e) {
    ml(true, 'review_incexc');
    //
    e.preventDefault();
    $('.jsShiftTab').removeClass('active');
    $(this).addClass('active');
    //
    $('.jsTabSection').addClass('dn');
    $(`.jsTabSection[data-id="${$(this).data('target')}"]`).removeClass('dn');
    //
    ml(false, 'review_incexc');
});

/**
 * Check if input is empty or not
 * 
 * @param  {String} str 
 * @return {Boolean}  
 */
function isEmpty(str) {
    return str == '' || str == null || str == undefined ? true : false;
}

/**
 * Make image url
 * 
 * @param  {String} img 
 * @return {String}
 */
function getImageURL(img) {
    if (img == '' || img == null) {
        return `${pm.urls.base}assets/images/img-applicant.jpg`;
    } else return `${pm.urls.aws }${img}`;
}

/**
 * Make employee number
 * 
 * @param  {Integer} i 
 * @param  {Integer} n 
 * @return {Integer}
 */
function getEmployeeId(i, n) {
    return n == '' || n == null ? i : n;
}

/**
 * Capitalize words
 * 
 * @param  {String} str 
 * @return {String} 
 */
function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function($1) {
        return $1.toUpperCase();
    });
}

/**
 * Make employee name and role
 * 
 * @param  {Object}  o 
 * @param  {Boolean} d 
 * @return {String}
 */
function remakeEmployeeName(o, d) {
    //
    let r = '';
    //
    if (d === undefined) r += o.first_name + ' ' + o.last_name;
    //
    if (o.job_title != '' && o.job_title != null) r += ' (' + (o.job_title) + ')';
    //
    r += ' [';
    //
    if (typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
    //
    if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
    else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
    else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
    else r += o['access_level'];
    //
    r += ']';
    //
    return r;
}

/**
 * Page loader
 * 
 * @param  {Boolean} doShow 
 * @param  {String}  p 
 * @param  {String}  msg
 * @return {Void}
 */
function ml(doShow, p, msg) {
    //
    p = p === undefined ? `.jsIPLoader` : `.jsIPLoader[data-page="${p}"]`;
    //
    if (doShow === undefined || doShow === false) $(p).hide();
    else {
        $(p).show();
        $(p).find('.jsIPLoaderBox p > span').html(msg === undefined ? 'Please wait while we are processing your request.' : msg);
    }
}

/**
 * Modal page
 * 
 * @param   {Object}   options 
 * @param   {Function} cb 
 * @returns {Void}
 */
function Modal(options, cb) {
    //
    var html = '';
    html += '<!-- Custom Modal -->';
    html += '<div class="csModal" id="' + (options.Id) + '">';
    html += '    <div class="container">';
    html += '        <div class="csModalHeader">';
    html += '            <h3 class="csModalHeaderTitle csF20 csB7">';
    html += options.Title;
    html += '                <span class="csModalButtonWrap">';
    html += options.Buttons !== undefined && options.Buttons.length !== 0 ? options.Buttons.join('') : '';
    html += '                    <button class="btn btn-black btn-lg jsModalCancel csF16"><em class="fa fa-times-circle csF16"></em> ' + (options.Cancel ? options.Cancel : 'Cancel') + '</button>';
    html += '                </span>';
    html += '                <div class="clearfix"></div>';
    html += '            </h3>';
    html += '        </div>';
    html += '        <div class="csModalBody">';
    html += '            <div class="csIPLoader jsIPLoader" data-page="' + (options.Loader) + '"><i class="fa fa-circle-o-notch fa-spin"></i></div>';
    html += options.Body;
    html += '        </div>';
    html += '        <div class="clearfix"></div>';
    html += '    </div>';
    html += '</div>';
    //
    $('.csModal').remove();
    $('.csPageWrap').append(html);
    $("#" + (options.Id) + "").fadeIn(300);
    //
    $('body').css('overflow-y', 'hidden');
    $("#" + (options.Id) + " .csModalBody").css('top', $("#" + (options.Id) + " .csModalHeader").height() + 50);
    if (typeof(cb) === 'function') cb();
}

/**
 * Strip html tags
 * 
 * @param  {String} str 
 * @return {String}
 */
function strip_tags(str) {
    return $('<div/>').html(str).text();
}

/**
 * Get errors
 * 
 * @param  {Integer} errorCode 
 * @param  {Boolean} isError 
 * @return {Object|String}
 */
function getError(errorCode, isError) {
    //
    const errorCodes = {
        404: 'We are unable to process your request at this moment. Please, try again in a few moments.',
        redirect: 'Your session is expired. Please, re-login to continue the work.',
        confirm_delete: "Do you really want to delete this row?",
        finish_later_review: "You have successfully saved the review as a draft. You can edit the review from the reviews screen.",
        required_review_title: "Review title is required.",
        required_review_start_date: "Review start date is required.",
        required_review_end_date: "Review end date is required.",
        required_review_repeat_val: "Review recur value is required.",
        required_review_due_val: "Review due value is required.",
        required_review_reviewees: "Please, at least select one reviewee.",
        required_review_reviewers_type: "Please, select reviewer type.",
        required_review_reviewers: "Please, select reviewers.",
        required_question: "Question is required.",
        review_saved: "You have successfully created a review.",
        convert_review_to_template_error: "Something went wrong while saving the review as template.",
        convert_review_to_template_success: "You have successfully saved the review as a new template.",
        change_review_status_error: "Something went wrong while changing the review status.",
        change_review_status_success: "You have successfully changed the review status.",
        review_end_error: "Something went wrong while ending the review.",
        review_end_success: "You have successfully ended the review.",
        review_reopen_error: "Something went wrong while re-opening the review.",
        review_reopen_success: "You have successfully re-opened the review.",
        add_reviewee_error: "Something went wrong while validating the reviewee.",
        add_reviewer_reviewee_error: "Please select a reviewee.",
        add_reviewer_reviewer_error: "Please, select a reviewer.",
        goal_title: "Please, add a goal title.",
        goal_start_date: "Please, add a goal start date.",
        goal_end_date: "Please, add a goal end date.",
        goal_type: "Please, select a goal type.",
        goal_employee: "Please, select an employee.",
        goal_team: "Please, select a team.",
        goal_department: "Please, select a department.",
        goal_target: "Please, select a goal target.",
        save_goal_error: "Something went wrong while adding the goal.",
        save_goal_success: "You have successfully added a new goal.",
        goal_closed_success: "You have successfully closed the goal.",
        goal_open_success: "You have successfully opened the goal.",
        visibility_updated: "You have successfully updated the visibility.",
        comment_missing: "Please, write a comment beforing saving it.",
        goal_update_success: "You have successfully updated the goal.",
        answer_save_success: "You have successfully updated the answers.",
        review_archived: "Looks like, you haven't marked any review as archived.",
        review_draft: "Looks like, you don't have any reviews in draft.",
        review_active: "Looks like, you haven't created any reviews.",
        goal_1: "Looks like, you haven't created any goals for employees.",
        goal_2: "Looks like, you haven't created any goals for company.",
        goal_3: "Looks like, you haven't created any goals for departments.",
        goal_4: "Looks like, you haven't created any goals for teams.",
    };
    //
    if (isError === true)
        return { 'Status': false, 'Response': errorCodes[errorCode] === undefined ? 'We are unable to process your request at this moment. Please, try again in a few moments.' : errorCodes[errorCode], Redirect: false };
    else
        return errorCodes[errorCode] === undefined ? 'We are unable to process your request at this moment. Please, try again in a few moments.' : errorCodes[errorCode];
}

/**
 * 
 */
function getNoShow(type) {
    let html = '';
    let buttons = '';
    let message;
    html += '<h1 class="alert text-center csF24 csB7">{{message}}<br /><br />';
    html += '{{buttons}}';
    html += '</h1>';
    //
    message = getError(type);
    //
    if (
        type == 'review_archived' ||
        type == 'review_draft' ||
        type == 'review_active'
    ) {
        buttons = '<a href="' + (pm.urls.base) + 'performance-management/review/create" class="btn btn-orange csF16"><em class="fa fa-plus-circle csF16"></em> Crate A Review</a>';
    } else if (
        type == 'goal_1' ||
        type == 'goal_2' ||
        type == 'goal_3' ||
        type == 'goal_4'
    ) {
        buttons = '<button class="btn btn-orange jsCreateGoal csF16"><em class="fa fa-plus-circle csF16"></em> Create A Goal</button>';
    }
    //
    return html.replace(/{{message}}/i, message).replace(/{{.*}}/ig, buttons);
}

/**
 * Handle Redirect
 * 
 * @return {Void}
 */
function handleRedirect() {
    alertify.alert(
        'NOTICE!',
        getError('redirect')['Response'],
        function() {
            window.location.reload();
        }
    );
}

/**
 * Handle Error
 * 
 * @return {Void}
 */
function handleError(msg) {
    alertify.alert(
        'WARNING!',
        msg,
        function() {}
    );
}

/**
 * Handle Success
 * 
 * @return {Void}
 */
function handleSuccess(msg, cb) {
    alertify.alert(
        'SUCCESS!',
        msg,
        function() {
            if (cb !== undefined) cb();
        }
    );
}

/**
 * Clean numeric input
 * 
 * @param  {String} in
 * @return {Void}
 */
function nb(i) {
    return i.replace(/[^0-9]/g, '');
}

/**
 * Get column as array
 * 
 * @param  {name} column 
 * @return {Array}
 */
Array.prototype.arrayColumn = function(column) {
    return this.map(function(el) {
        // gets corresponding 'column'
        if (el.hasOwnProperty(column)) return el[column];
        // removes undefined values
    }).filter(function(el) { return typeof el != 'undefined'; });
};


/**
 * 
 */
function convertDate(inp) {
    return moment(inp, pm.dateTimeFormats.ymd).format(pm.dateTimeFormats.mdy);
}


/**
 * 
 */
function saveReviewAsTemplate(reviewId) {
    return new Promise((res) => {
        $.post(pm.urls.handler, {
            action: 'convert_review_to_template',
            reviewId: reviewId
        }, (resp) => {
            res(resp);
        });
    });
}


/**
 * 
 */
$('.jsFilterBTN').click(function(event) {
    event.preventDefault();
    $(`#${$(this).data().target}`).toggle();
});

/**
 * 
 */
$(document).on('click', '.jsPageSectionBTN', function(event) {
    //
    event.preventDefault();
    //
    $('.jsPageSection').fadeOut(0);
    $(`.jsPageSection[data-key="${$(this).data().target}"]`).fadeIn(0);
});

/**
 * 
 */
function getMeasureSymbol(unit) {
    return unit == 1 ? '%' : (unit == 3 ? '$' : '');
}


//
$('.jsCalendarView').click(function(e) {
    //
    e.preventDefault();
    // 
    Modal({
        Id: 'calendarModal',
        Title: 'Calendar',
        Body: `<div class="container"><iframe src="${pm.urls.base}calendar/my_events/iframe" width="100%" height="${$(window).height() - 90}"></iframe></div>`,
        Loader: 'jsCalendarLoader'
    }, () => {
        ml(false, 'jsCalendarLoader')
    });
});

/**
 * 
 */
$('.jsDecreaseSize').click(function(event) {
    //
    event.preventDefault();
    //
    let newList = {};
    //
    $.each(getFontList(), function(i, v) {
        //
        let newSize = v;
        //
        newSize--;
        //
        if (newSize >= 11) {
            newList[i] = newSize;
        } else {
            newList[i] = v;
        }
    });
    //
    setFontList(newList);
    //
    loadFonts();
});

/**
 * 
 */
$('.jsIncreaseSize').click(function(event) {
    //
    event.preventDefault();
    //
    let newList = {};
    //
    $.each(getFontList(), function(i, v) {
        //
        let newSize = v;
        //
        newSize++;
        //
        if (newSize > 29) {
            newList[i] = v;
        } else {
            newList[i] = newSize;
        }
    });
    //
    setFontList(newList);
    //
    loadFonts();
});

/**
 * 
 */
$('.jsResetSize').click(function(event) {
    //
    event.preventDefault();
    //
    //
    setFontList(fontList);
    //
    loadFonts();
});

$('.select2').select2({
    placeholder: "Please select"
})

//
function getFontList() {
    //
    if (localStorage.getItem('myFontList') === null) return fontList;
    //
    return JSON.parse(localStorage.getItem('myFontList'));
}

//
function setFontList(l) {
    //
    localStorage.setItem('myFontList', JSON.stringify(l));
}

//
function loadFonts() {
    //
    let s2 = '16';
    $.each(getFontList(), function(i, v) {
        if (i == 'csF16') {
            s2 = v;
        }
        $(`.${i}`).attr('style', 'font-size: ' + v + 'px !important');
    });
    //
    $('.select2-container').attr('style', 'font-size:' + (s2) + 'px !important');
    $('.select2-container--default .select2-selection--multiple .select2-selection__choice').attr('style', 'font-size:' + (s2) + 'px !important');
    //
    if (JSON.stringify(getFontList()) === JSON.stringify(fontList)) {
        $('.jsResetSize').hide(0);
    } else {
        $('.jsResetSize').show(0);
    }
}
// Load default fonts
loadFonts();