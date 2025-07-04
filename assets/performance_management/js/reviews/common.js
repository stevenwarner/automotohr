/**
 * 
 */
const loaderName = 'review_listing';

/**
 * 
 */
function getCompanyEmployees() {
    $.get(
        `${pm.urls.handler}get/get_all_company_employees`,
        (resp) => {
            //
            if (resp.Redirect === true) {
                handleRedirect();
                return;
            }
            //
            pm.cemployees = resp.Data;
        }
    );
}

/**
 * 
 */
function getProgress(reviewerArray, reviewerType) {
    //
    const returnOBJ = {
        total: 0,
        completed: 0,
        pending: 0
    };
    //
    if (reviewerArray.length === 0) return returnOBJ;
    //
    reviewerArray.map((record) => {
        if (reviewerType == 'review') {
            if (record.completion_status == 1 && record.reviewer_type == 'Review') returnOBJ.completed++;
            else if (record.completion_status == 0 && record.reviewer_type == 'Review') returnOBJ.pending++;
        } else if (reviewerType == 'feedback') {
            if (record.completion_status == 1 && record.reviewer_type == 'Feeback') returnOBJ.completed++;
            else if (record.completion_status == 0 && record.reviewer_type == 'Feeback') returnOBJ.pending++;
        }
    });
    //
    returnOBJ.total = returnOBJ.completed + returnOBJ.pending;
    //
    returnOBJ.completed = Math.ceil((returnOBJ.completed * 100) / returnOBJ.total);
    returnOBJ.completed = Math.ceil(isNaN(returnOBJ.completed) ? 0 : returnOBJ.completed);
    returnOBJ.pending = Math.ceil((returnOBJ.pending * 100) / returnOBJ.total);
    //
    returnOBJ.total = 100;
    //
    return returnOBJ;
}

/**
 * 
 */
$(document).on('click', '.jsSaveReviewAsTemplate', function(event) {
    event.preventDefault();
    //
    const reviewId = $(this).closest('tr').data().id;
    //
    if (reviewId === undefined) return;
    //
    ml(true, loaderName);
    //
    saveReviewAsTemplate(reviewId)
        .then((res) => {
            //
            ml(false, loaderName);
            //
            if (res.Redirect === true) {
                handleRedirect();
                return
            }
            //
            if (res.Status === false) {
                handleError(getError('convert_review_to_template_error'));
                return
            }
            //
            handleSuccess(getError('convert_review_to_template_success'));
        });
});

/**
 * 
 */
$(document).on('click', '.jsAddReviewers', function(event) {
    //
    event.preventDefault();
    //
    const
        reviewId = $(this).closest('tr').data().id;
    //
    if (reviewId === undefined) return;
    //
    Modal({
        Id: 'jsAddReviewee',
        Title: "Add a Reviewee",
        Body: getAddRevieweeBody(reviewId),
        Loader: 'jsAddRevieweeLoader'
    }, () => {
        //
        let options = '<option value="0">[Select a Reviewee]</option>';
        //
        pm.cemployees.map((em) => {
            options += `<option value="${em.userId}">${remakeEmployeeName(em)}</option>`;
        });
        //
        $('#jsAddReviewReviewee').html(options).select2();
        $('#jsAddReviewReviewer').select2();
        //
        loadFonts();
        //
        ml(false, 'jsAddRevieweeLoader');
    });
});

//
$(document).on('change', '#jsAddReviewReviewee', function() {
    //
    if ($(this).val() == null) {
        $('.jsAddReviewerBox').hide();
        return;
    }
    //
    ml(true, 'jsAddRevieweeLoader');
    //
    getRevieweeReviewers(
        $('#jsAddReviewReviewId').val(),
        $(this).val()
    ).then((resp) => {
        //
        ml(false, 'jsAddRevieweeLoader');
        //
        if (resp.Redirect === true) {
            handleRedirect();
            return;
        }
        //
        if (resp.Status === false) {
            handleError(getError('add_reviewee_error'));
            return;
        }
        //
        if (resp.Data.length === 1) {
            $('.jsAddReviewDateBox').removeClass('dn');
        }
        //
        let options = '';
        //
        pm.cemployees.map((em) => {
            options += `<option value="${em.userId}" ${$.inArray(em.userId, resp.Data) !== -1 ? 'disabled="true"' : ''} >${remakeEmployeeName(em)}</option>`;
        });
        //
        $('#jsAddReviewReviewer').html(options).select2();
        $('.jsAddReviewerBox').show();
    });
});

/**
 * 
 */
$(document).on('click', '.jsAddRevieweeSave', function(event) {
    //
    event.preventDefault();
    //
    const
        revieweeId = $('#jsAddReviewReviewee').val(),
        reviewerIds = $('#jsAddReviewReviewer').val()
        //
    if (revieweeId === null) {
        handleError(getError('add_reviewer_reviewee_error'));
        return;
    }
    //
    if (reviewerIds === null) {
        handleError(getError('add_reviewer_reviewer_error'));
        return;
    }
    //
    saveReviewers($('#jsAddReviewReviewId').val(), revieweeId, reviewerIds)
        .then((resp) => {

        });
    //
    ml(true, 'jsAddRevieweeLoader');
});

/**
 * 
 */
function getAddRevieweeBody(reviewId) {
    let html = '';
    //
    html += `<div class="container">`;
    html += `    <form>`;
    html += `        <!--  -->`;
    html += `        <div class="csPageBody">`;
    html += `            <div class="form-group">`;
    html += `                <label class="csF16 csB7">Select Reviewee <span class="csRequired"></span></label>`;
    html += `                <select id="jsAddReviewReviewee"></select>`;
    html += `            </div>`;
    html += `            <div class="form-group jsAddReviewDateBox dn">`;
    html += `                <label class="csF16 csB7">Select Review Period <span class="csRequired"></span></label>`;
    html += `                <input type="text" class="csF16" readonly id="jsAddReviewStartDate" />`;
    html += `                <input type="text" class="csF16" readonly id="jsAddReviewEndDate" />`;
    html += `            </div>`;
    html += `            <div class="form-group jsAddReviewerBox dn">`;
    html += `                <label class="csF16 csB7">Select Reviewers <span class="csRequired"></span></label>`;
    html += `                <select id="jsAddReviewReviewer" multiple></select>`;
    html += `            </div>`;
    html += `        </div>`;
    html += `        <!--  -->`;
    html += `        <div class="csPageFooter bbt pa10">`;
    html += `            <div class="form-group">`;
    html += `                <label></label>`;
    html += `                <button class="btn btn-orange jsAddRevieweeSave csF16"><i class="fa fa-save csF16"></i> Save</button>`;
    html += `                <button class="btn btn-black jsModalCancel csF16"><i class="fa fa-times-circle csF16"></i> Cancel</button>`;
    html += `                <input type="hidden" value="${reviewId}" id="jsAddReviewReviewId" />`;
    html += `            </div>`;
    html += `        </div>`;
    html += `    </form>`;
    html += `</div>`;
    //
    return html;
}

/**
 * 
 */
function getRevieweeReviewers(reviewId, revieweeId) {
    return new Promise((res) => {
        $.get(
            `${pm.urls.handler}get/get_reviewers_list/${reviewId}/${revieweeId}`,
            (resp) => {
                res(resp);
            }
        )
    });
}

/**
 * 
 */
function saveReviewers(reviewId, revieweeId, reviewerId) {
    return new Promise((res) => {
        $.post(
            `${pm.urls.handler}`, {
                action: 'add_reviewer',
                reviewId: reviewId,
                revieweeId: revieweeId,
                reviewerId: reviewerId
            }, (resp) => {
                res(resp);
            }
        )
    });
}

//
getCompanyEmployees();