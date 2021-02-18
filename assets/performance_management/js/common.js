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
                //
                $('#ui-datepicker-div').remove();
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
        $('body').css('overflow-y', 'auto');
        //
        $('#ui-datepicker-div').remove();
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
 * @return {Void}
 */
function ml(doShow, p) {
    //
    p = p === undefined ? `.jsIPLoader` : `.jsIPLoader[data-page="${p}"]`;
    //
    if (doShow === undefined || doShow === false) $(p).hide();
    else $(p).show();
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
    let html = `
    <!-- Custom Modal -->
    <div class="csModal" id="${options.Id}">
        <div class="container-fluid">
            <div class="csModalHeader">
                <h3 class="csModalHeaderTitle">
                    ${options.Title}
                    <span class="csModalButtonWrap">
                    ${ options.Buttons !== undefined && options.Buttons.length !== 0 ? options.Buttons.join('') : '' }
                        <button class="btn btn-black btn-lg jsModalCancel" title="Close this window">Cancel</button>
                    </span>
                    <div class="clearfix"></div>
                </h3>
            </div>
            <div class="csModalBody">
                <div class="csIPLoader jsIPLoader" data-page="${options.Loader}"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                ${options.Body}
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    `;
    //
    $(`#${options.Id}`).remove();
    $('.csPageWrap').append(html);
    $(`#${options.Id}`).fadeIn(300);
    //
    $('body').css('overflow-y', 'hidden');
    $(`#${options.Id} .csModalBody`).css('top', $(`#${options.Id} .csModalHeader`).height() + 50);
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
        required_review_reviewers: "Please, select reviewers.",
        required_question: "Question is required.",
    };
    //
    if (isError === true)
        return { 'Status': false, 'Response': errorCodes[errorCode] === undefined ? 'We are unable to process your request at this moment. Please, try again in a few moments.' : errorCodes[errorCode], Redirect: false };
    else
        return errorCodes[errorCode] === undefined ? 'We are unable to process your request at this moment. Please, try again in a few moments.' : errorCodes[errorCode];
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
 * @param {Object}  question 
 * @param {Integer} index 
 * @param {Integer} questionsLength 
 */
function getQuestionRow(question, index, questionsLength) {
    //
    let rows = '';
    //
    rows += `<div class="csQuestionRow" data-id="${index}">`;
    rows += `   <div class="csFeedbackViewBox p10">`;
    rows += `       <h4 class="bbb pb10">`;
    rows += `           <strong>Question ${index+1}</strong>`;
    rows += `           <span class="csBTNBox">`;
    if (index != questionsLength) {
        rows += `           <i class="fa fa-long-arrow-down jsQuestionMoveDown" title="Move down" placement="top"></i>`;
    }
    if (index != 0) {
        rows += `           <i class="fa fa-long-arrow-up jsQuestionMoveUp" title="Move up" placement="top"></i>`;
    }
    rows += `           <span>|</span>`;
    rows += `           <i class="fa fa-clone jsQuestionClone" title="Clone this question" placement="top"></i>`;
    rows += `           <i class="fa fa-trash jsQuestionDelete"  title="Delete this question" placement="top"></i>`;
    rows += `           <i class="fa fa-pencil jsQuestionEdit" title="Edit this question" placement="top"></i>`;
    rows += `           </span>`;
    rows += `       </h4>`;
    rows += `       <h4><strong>${question.title}</strong></h4>`;
    if (!isEmpty(question.text)) {
        rows += `       <p>${question.text}</p>`;
    }
    if (!isEmpty(question.video_help)) {
        rows += `       <video controls="true" style="width: 250px;"><source src="${question.video_help}" type="video/webm"></source></video><br />`;
    }
    //
    if (question.not_applicable === 1) {
        rows += '<label class="control control--checkbox">';
        rows += '   <input type="checkbox" class="jsQuestionNA" /> Not Applicable';
        rows += '   <div class="control__indicator"></div>';
        rows += '</label><br />';
    }

    //
    if ($.inArray(question.question_type, ['text-n-rating', 'rating']) !== -1) {
        rows += '<ul>';
        for (let i = 1; i <= question.scale; i++) {
            rows += '<li>';
            rows += '   <div class="csFeedbackViewBoxTab">';
            rows += `       <p class="mb0">${i}</p>`;
            if (question.labels_flag === 1) {
                rows += `   <p>${question.label_question[i]}</p>`;
            }
            rows += '   </div>';
            rows += '</li>';
        }
        rows += '</ul>';
    }

    //
    if ($.inArray(question.question_type, ['multiple-choice-with-text', 'multiple-choice']) !== -1) {
        rows += '<br /><label class="control control--radio">';
        rows += `   <input type="radio" class="jsQuestionNA" name="jsQuestionMultipleChoice${index}" /> Yes`;
        rows += '   <div class="control__indicator"></div>';
        rows += '</label> &nbsp;&nbsp;';
        rows += '<label class="control control--radio">';
        rows += `   <input type="radio" class="jsQuestionNA" name="jsQuestionMultipleChoice${index}" /> No`;
        rows += '   <div class="control__indicator"></div>';
        rows += '</label>';
    }

    //
    if ($.inArray(question.question_type, ['rating', 'multiple-choice']) === -1) {
        rows += '<div class="csFeedbackViewBoxComment">';
        rows += '    <div class="row">';
        rows += '        <div class="col-sm-12 col-xs-12">';
        rows += '            <h5><strong>Feedback (Elaborate)</strong></h5>';
        rows += '            <textarea rows="3" class="form-control"></textarea>';
        rows += '        </div>';
        rows += '    </div>';
        rows += '</div>';
    }

    //
    rows += ''


    rows += `   </div>`;
    rows += `</div>`;

    return rows;
}

/**
 * Object.assign polyfil
 */
if (typeof Object.assign !== 'function') {
    // Must be writable: true, enumerable: false, configurable: true
    Object.defineProperty(Object, "assign", {
        value: function assign(target, varArgs) { // .length of function is 2
            'use strict';
            if (target === null || target === undefined) {
                throw new TypeError('Cannot convert undefined or null to object');
            }

            var to = Object(target);

            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];

                if (nextSource !== null && nextSource !== undefined) {
                    for (var nextKey in nextSource) {
                        // Avoid bugs when hasOwnProperty is shadowed
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        },
        writable: true,
        configurable: true
    });
}