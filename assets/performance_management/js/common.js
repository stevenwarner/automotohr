//
window.performanceManagement = {
    PaginationOBJ: {}
};
//
// Mobile menu
$('.csMobile span i').click(function(e) { $('.csVertical').toggle(); });
//
function getImageURL(img) {
    if (img == '' || img == null) {
        return `${baseURL}assets/images/img-applicant.jpg`;
    } else return `${awsURL}${img}`;
}

//
function getEmployeeId(i, n) {
    return n == '' || n == null ? i : n;
}

//
function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function($1) {
        return $1.toUpperCase();
    });
}

// 
function isEmpty(str) {
    return str == '' || str == null || str == undefined ? true : false;
}

//
$(document).on('keyup', '.js-number', function() {
    $(this).val(
        $(this).val().replace(/[^0-9]/, '')
    );
});

//
$('.jsToggle').click(function(e) {
    //
    e.preventDefault();
    //
    if ($(this).find('i').hasClass('fa-minus-circle')) {
        $(this).find('i')
            .removeClass('fa-minus-circle')
            .addClass('fa-plus-circle');
        //
        $(`div[data-target="${$(this).data('target')}"]`).hide();
    } else {
        $(this).find('i')
            .removeClass('fa-plus-circle')
            .addClass('fa-minus-circle');
        //
        $(`div[data-target="${$(this).data('target')}"]`).show();
    }
});

//
function remakeEmployeeName(
    o,
    d
) {
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


// Loader
function ml(doShow, p) {
    //
    p = p === undefined ? `.jsIPLoader` : `.jsIPLoader[data-page="${p}"]`;
    //
    if (doShow === undefined || doShow === false) $(p).hide();
    else $(p).show();
}

//
function getField(f) {
    let field = $(f).val();
    if (!field || field == 0 || field == null) return 0;
    return field;
}

// Modal
function Modal(
    options,
    cb
) {
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

//
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


// Pagination
// Pagination
// Get previous page
$(document).on('click', '.js-pagination-prev', pagination_event);
// Get first page
$(document).on('click', '.js-pagination-first', pagination_event);
// Get last page
$(document).on('click', '.js-pagination-last', pagination_event);
// Get next page
$(document).on('click', '.js-pagination-next', pagination_event);
// Get page
$(document).on('click', '.js-pagination-shift', pagination_event);
// TODO convert it into a plugin
function load_pagination(limit, list_size, target_ref, page_type) {
    //
    var obj = window.timeoff.PaginationOBJ[page_type];
    // parsing to int
    limit = parseInt(limit);
    obj['page'] = parseInt(obj['page']);
    // get paginate array
    var page_array = paginate(obj['count'], obj['Main']['page'], limit, list_size);
    // append the target ul
    // to top and bottom of table
    var rows = '';
    rows += '<div class="">';
    rows += '<div class="col-lg-12">';
    rows += '   <div class="row pto-pagination">';
    rows += '       <div class="col-xs-12 col-lg-3">';
    rows += '           <div class="pagination-left-content js-showing-target">';
    rows += '               <div class="js-show-record"></div>';
    rows += '           </div>';
    rows += '       </div>';
    rows += '       <div class="col-xs-12 col-lg-9">';
    rows += '           <nav aria-label="Pagination">';
    rows += '               <ul class="pagination cs-pagination js-pagination"></ul>';
    rows += '           </nav>';
    rows += '       </div>';
    rows += '   </div>';
    rows += '</div>';
    rows += '</div>';

    target_ref.html(rows);
    // set rows append table
    var target = target_ref.find('.js-pagination');
    var targetShowing = target_ref.find('.js-showing-target');
    // get total items number
    var total_records = page_array.total_pages;
    // load pagination only there
    // are more than one page
    if (obj['count'] >= limit) {
        // generate li for
        // pagination
        var rows = '';
        // move to one step back
        rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['Main']['page'] == 1 ? '' : 'js-pagination-first') + '">First</a></li>';
        rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['Main']['page'] == 1 ? '' : 'js-pagination-prev') + '">&laquo;</a></li>';
        // generate 5 li
        $.each(page_array.pages, function(index, val) {
            rows += '<li class="' + (val == obj['Main']['page'] ? 'active page-item' : '') + '"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" data-page="' + (val) + '" class="' + (obj['Main']['page'] != val ? 'js-pagination-shift' : '') + '">' + (val) + '</a></li>';
        });
        // move to one step forward
        rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['Main']['page'] == page_array.total_pages ? '' : 'js-pagination-next') + '">&raquo;</a></li>';
        rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="' + (page_type) + '" class="' + (obj['Main']['page'] == page_array.total_pages ? '' : 'js-pagination-last') + '">Last</a></li>';
        // append to ul
        target.html(rows);
    }
    // append showing of records
    targetShowing.html('<p>Showing ' + (page_array.start_index + 1) + ' - ' + (page_array.end_index != -1 ? (page_array.end_index + 1) : 1) + ' of ' + (obj['count']) + '</p>');
}
// Paginate logic
function paginate(total_items, current_page, page_size, max_pages) {
    // calculate total pages
    var total_pages = Math.ceil(total_items / page_size);

    // ensure current page isn't out of range
    if (current_page < 1) current_page = 1;
    else if (current_page > total_pages) current_page = total_pages;

    var start_page, end_page;
    if (total_pages <= max_pages) {
        // total pages less than max so show all pages
        start_page = 1;
        end_page = total_pages;
    } else {
        // total pages more than max so calculate start and end pages
        var max_pagesBeforecurrent_page = Math.floor(max_pages / 2);
        var max_pagesAftercurrent_page = Math.ceil(max_pages / 2) - 1;
        if (current_page <= max_pagesBeforecurrent_page) {
            // current page near the start
            start_page = 1;
            end_page = max_pages;
        } else if (current_page + max_pagesAftercurrent_page >= total_pages) {
            // current page near the end
            start_page = total_pages - max_pages + 1;
            end_page = total_pages;
        } else {
            // current page somewhere in the middle
            start_page = current_page - max_pagesBeforecurrent_page;
            end_page = current_page + max_pagesAftercurrent_page;
        }
    }

    // calculate start and end item indexes
    var start_index = (current_page - 1) * page_size;
    var end_index = Math.min(start_index + page_size - 1, total_items - 1);

    // create an array of pages to ng-repeat in the pager control
    var pages = Array.from(Array((end_page + 1) - start_page).keys()).map(i => start_page + i);

    // return object with all pager properties required by the view
    return {
        total_items: total_items,
        // current_page: current_page,
        // page_size: page_size,
        total_pages: total_pages,
        start_page: start_page,
        end_page: end_page,
        start_index: start_index,
        end_index: end_index,
        pages: pages
    };
}
//
function pagination_event() {
    //
    var i = $(this).data('page-type');
    // When next is press
    if ($(this).hasClass('js-pagination-next') === true) {
        window.timeoff.PaginationOBJ[i]['Main']['page'] = window.timeoff.PaginationOBJ[i]['Main']['page'] + 1;
        window.timeoff.PaginationOBJ[i]['cb']($(this));
    } else if ($(this).hasClass('js-pagination-prev') === true) {
        window.timeoff.PaginationOBJ[i]['Main']['page'] = window.timeoff.PaginationOBJ[i]['Main']['page'] - 1;
        window.timeoff.PaginationOBJ[i]['cb']($(this));
    } else if ($(this).hasClass('js-pagination-first') === true) {
        window.timeoff.PaginationOBJ[i]['Main']['page'] = 1;
        window.timeoff.PaginationOBJ[i]['cb']($(this));
    } else if ($(this).hasClass('js-pagination-last') === true) {
        window.timeoff.PaginationOBJ[i]['Main']['page'] = window.timeoff.PaginationOBJ[i]['pages'];
        window.timeoff.PaginationOBJ[i]['cb']($(this));
    } else if ($(this).hasClass('js-pagination-shift') === true) {
        window.timeoff.PaginationOBJ[i]['Main']['page'] = parseInt($(this).data('page'));
        window.timeoff.PaginationOBJ[i]['cb']($(this));
    }
}

// 
$('.jsFilterBtn').click(function(e) {
    e.preventDefault();
    $(`.${$(this).data('target')}`).toggle();
});

// strips tags
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
        required_review_title: "Review title is required.",
        required_review_start_date: "Review start date is required.",
        required_review_end_date: "Review end date is required.",
        required_review_repeat_val: "Review recur value is required.",
        required_review_due_val: "Review due value is required.",
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