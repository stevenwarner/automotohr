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


//
getCompanyEmployees();