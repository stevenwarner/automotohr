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

//
getCompanyEmployees();