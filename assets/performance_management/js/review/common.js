/**
 * 
 */
const loaderName = 'review_listing';

/**
 * 
 */
$(document).on('click', '.jsAddReviewers', function(event) {
    //
    event.preventDefault();
    //
    const
        reviewId = $(this).data().id === undefined ? $(this).closest('tr').data().id : $(this).data().id;
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
        ml(false, 'jsAddRevieweeLoader');
    });
});

/**
 * 
 */
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
            handleSuccess(getError('Reviewee added.'), () => {
                window.location.reload();
            });
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
    html += `                <label>Select Reviewee <span class="csRequired"></span></label>`;
    html += `                <select id="jsAddReviewReviewee"></select>`;
    html += `            </div>`;
    html += `            <div class="form-group jsAddReviewDateBox dn">`;
    html += `                <label>Select Review Period <span class="csRequired"></span></label>`;
    html += `                <input type="text" readonly id="jsAddReviewStartDate" />`;
    html += `                <input type="text" readonly id="jsAddReviewEndDate" />`;
    html += `            </div>`;
    html += `            <div class="form-group jsAddReviewerBox dn">`;
    html += `                <label>Select Reviewers <span class="csRequired"></span></label>`;
    html += `                <select id="jsAddReviewReviewer" multiple></select>`;
    html += `            </div>`;
    html += `        </div>`;
    html += `        <!--  -->`;
    html += `        <div class="csPageFooter bbt pa10">`;
    html += `            <div class="form-group">`;
    html += `                <label></label>`;
    html += `                <button class="btn btn-orange jsAddRevieweeSave">Save</button>`;
    html += `                <button class="btn btn-black jsModalCancel">Cancel</button>`;
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

/**
 * 
 */
function getProgress(reviewerArray, reviewerType) {
    //
    const returnOBJ = {
        total: reviewerArray.length,
        completed: 0,
        pending: 0
    };
    //
    if (reviewerArray.length === 0) return returnOBJ;
    //
    reviewerArray.map((record) => {
        if (reviewerType == 'review') {
            if (record.completetion_status == 1 && record.reviewer_type == 'Review') returnOBJ.completed++;
            else returnOBJ.pending++;
        } else {
            if (record.completetion_status == 1 && record.reviewer_type == 'Feedback') returnOBJ.completed++;
            else returnOBJ.pending++;
        }
    });
    //
    return returnOBJ;
}

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

//
getCompanyEmployees();