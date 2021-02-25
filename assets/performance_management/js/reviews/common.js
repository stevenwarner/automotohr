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
        ml(false, 'jsAddRevieweeLoader');
    });
});

//
$(document).on('change', '#jsAddReviewReviewee', function() {
    if ($(this).val() == null) {

    }
    //
    getRevieweeReviewers(
        $('#jsAddReviewReviewId').val(),
        $(this).val()
    );
    //
    ml(true, 'jsAddRevieweeLoader');
});

/**
 * 
 */
function getAddRevieweeBody(reviewId) {
    let html = '';
    //
    html += `<div class="container-fluid">`;
    html += `    <form>`;
    html += `        <!--  -->`;
    html += `        <div class="csPageBody">`;
    html += `            <div class="form-group">`;
    html += `                <label>Select Reviewee</label>`;
    html += `                <select id="jsAddReviewReviewee"></select>`;
    html += `            </div>`;
    html += `            <div class="form-group jsReviewerBox dn">`;
    html += `                <label>Select Reviewers</label>`;
    html += `                <select id="jsAddReviewReviewer" multiple></select>`;
    html += `            </div>`;
    html += `        </div>`;
    html += `        <!--  -->`;
    html += `        <div class="csPageFooter bbt pa10">`;
    html += `            <div class="form-group">`;
    html += `                <label></label>`;
    html += `                <button class="btn btn-black">Cancel</button>`;
    html += `                <button class="btn btn-orange">Save</button>`;
    html += `                <input type="hidden" value="${reviewId}" id="jsAddReviewReviewId" />`;
    html += `            </div>`;
    html += `        </div>`;
    html += `    </form>`;
    html += `</div>`;
    //
    return html;
}

//
getCompanyEmployees();