$(function() {
    //
    const filter = {
        reviewType: -1,
        reviewStatus: 'active',
        reviewTitle: -1,
        startDate: -1,
        endDate: -1,
        status: -1
    };
    //
    let XHR = null;

    /**
     * 
     */
    $('#jsFilterReviewName, #jsFilterReviewType, #jsFilterStatus').select2({
        minimumResultsForSearch: -1,
        selectionTitleAttribute: false
    });

    /**
     * 
     */
    $('#jsFilterStartDate').datepicker({
        changeYear: true,
        changeMonth: true,
        minDate: 0,
        formatDate: pm.dateTimeFormats.ymdf,
        onSelect: function(d) {
            $('#jsFilterEndDate').datepicker('option', 'minDate', d);
            filter.startDate = d;
        }
    });

    /**
     * 
     */
    $('#jsFilterEndDate').datepicker({
        changeYear: true,
        changeMonth: true,
        minDate: 0,
        formatDate: pm.dateTimeFormats.ymdf,
        onSelect: function(d) {
            filter.endDate = d;
        }
    });

    /**
     * 
     */
    $('.jsTabShifter').click(function(e) {
        //
        e.preventDefault();
        //
        filter.reviewStatus = $(this).data().id;
        //
        $('.jsTabShifter').removeClass('active');
        $(this).addClass('active');
        //
        loadReviews();
    });

    /**
     * 
     */
    $('#jsFilterReviewType').change(function() {
        filter.reviewType = $(this).val() || -1;
        loadReviews();
    });

    /**
     * 
     */
    $('#jsFilterReviewName').change(function() {
        filter.reviewTitle = $(this).val() || -1;
        loadReviews();
    });

    /**
     * 
     */
    $('#jsFilterStatus').change(function() {
        filter.status = $(this).val() || -1;
        loadReviews();
    });

    /**
     * 
     */
    $('.jsFilterApplyBtn').click(function() {
        loadReviews();
    });

    /**
     * 
     */
    $('.jsFilterResetBtn').click(function() {
        //
        filter.startDate = -1;
        filter.endDate = -1;
        //
        $('#jsFilterStartDate').val('');
        $('#jsFilterEndDate').val('');
        $('#jsFilterReviewType').select2('val', -1);
        $('#jsFilterReviewName').select2('val', -1);
        $('#jsFilterStatus').select2('val', -1);
    });

    /**
     * 
     */
    $(document).on('click', '.jsReviewStatus', function(event) {
        //
        event.preventDefault();
        //
        const
            reviewId = $(this).closest('tr').data().id,
            type = $(this).data().type;
        //
        if (reviewId === undefined) return;
        //
        ml(true, loaderName);
        //
        reviewStatusChange(reviewId, type)
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
                    handleError(getError('change_review_status_error'));
                    return
                }
                //
                handleSuccess(getError('change_review_status_success'));
                //
                loadReviews();
            });
    });

    /**
     * 
     */
    $(document).on('click', '.jsEndReview', function(event) {
        //
        event.preventDefault();
        //
        const
            reviewId = $(this).closest('tr').data().id
            //
        if (reviewId === undefined) return;
        //
        ml(true, loaderName);
        //
        endReview(reviewId)
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
                    handleError(getError('review_end_error'));
                    return
                }
                //
                handleSuccess(getError('review_end_success'));
                //
                loadReviews();
            });
    });

    /**
     * 
     */
    $(document).on('click', '.jsReopenReview', function(event) {
        //
        event.preventDefault();
        //
        const
            reviewId = $(this).closest('tr').data().id
            //
        if (reviewId === undefined) return;
        //
        ml(true, loaderName);
        //
        reopenReview(reviewId)
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
                    handleError(getError('review_reopen_error'));
                    return
                }
                //
                handleSuccess(getError('review_reopen_success'));
                //
                loadReviews();
            });
    });


    //
    loadReviews();


    /**
     * 
     */
    function loadReviews() {
        //
        if (XHR !== null) XHR.abort();
        //
        ml(true, loaderName);
        //
        XHR = $.post(
            pm.urls.handler, {
                action: 'get_review_listing',
                filter: filter
            },
            (resp) => {
                XHR = null;
                ml(false, loaderName);
                //
                if (resp.Redirect === true) {
                    handleRedirect();
                    return;
                }
                //
                if (resp.Status === false) {
                    handleError(getError());
                    return;
                }
                //
                setView(resp.Data);
            }
        );
        ml(false, 'review_listing');
    }

    /**
     * 
     */
    function setView(reviews) {
        //
        if (reviews.length === 0) {
            //
            $('#jsReviewWrap').html(`<tr><td colspan="5">${getNoShow('review_'+filter.reviewStatus)}</td></tr>`);
            $('.jsPageBoxFooter').html('');
            return;
        }
        //
        if (JSON.stringify(filter) == JSON.stringify({ reviewType: -1, reviewStatus: 'active', reviewTitle: -1, startDate: -1, endDate: -1, status: -1 })) {
            //
            let rows = '<option value="-1">[Select Review Title]</option>';
            //
            reviews.map((review) => {
                rows += `<option value="${review.sid}">${review.review_title}</option>`;
            });
            //
            $('#jsFilterReviewName').html(rows).select2({ minimumResultsForSearch: -1 });
        }

        //
        let rows = '';
        //
        reviews.map((review) => {
            //
            let status = '';
            if (filter.reviewStatus != 'draft') {
                var reviewerProgress = getProgress(review.reviewers, 'review');
                var feedbackProgress = getProgress(review.reviewers, 'review');
                //
                if (review.status == 'started') {
                    status = '<strong class="btn btn-xs alert-success">Running</strong>';
                } else if (review.status == 'ended') {
                    status = '<strong class="btn btn-xs alert-danger">Ended</strong>';
                }
            }
            //
            rows += `<tr data-id="${review.sid}">`;
            rows += `   <td><p class="csF16">${review.review_title} ${status}</p></td>`;
            rows += `   <td class="csF16">${convertDate(review.review_start_date)}</td>`;
            rows += `   <td>`;
            if (filter.reviewStatus != 'draft') {
                rows += `        <div class="progress csRadius100">`;
                rows += `           <div class="progress-bar" role="progressbar" aria-valuenow="${reviewerProgress.completed}" aria-valuemin="0" aria-valuemax="${reviewerProgress.total}" style="width: ${reviewerProgress.completed}%;"></div>`;
                rows += `        </div>`;
                rows += `        <small class="csF16">${reviewerProgress.completed}% Completed</small>`;
            }
            rows += `   </td>`;
            rows += `   <td>`;
            if (filter.reviewStatus != 'draft') {
                rows += `        <div class="progress csRadius100">`;
                rows += `           <div class="progress-bar" role="progressbar" aria-valuenow="${feedbackProgress.completed}" aria-valuemin="0" aria-valuemax="${feedbackProgress.total}" style="width: ${feedbackProgress.completed}%;"></div>`;
                rows += `        </div>`;
                rows += `        <small class="csF16">${feedbackProgress.completed}% Completed</small>`;
            }
            rows += `   </td>`;
            rows += `   <td>`;
            rows += `       <div class="csBTNBox">`;
            if (filter.reviewStatus == 'active') {
                rows += `           <a href="${pm.urls.pbase}review/${review.sid}" class="btn btn-black csF16"><i class="fa fa-eye csF16"></i> View</a>`;
            } else if (filter.reviewStatus == 'draft') {
                rows += `           <a href="${pm.urls.pbase}review/create/${review.sid}" class="btn btn-black csF16"><i class="fa fa-eye csF16"></i> Complete Review</a>`;
            }
            if (filter.reviewStatus != 'draft') {


                rows += `           <div class="dropdown">`;
                rows += `               <button class="btn dropdown-toggle" type="button" id="dropdownMenu${review.sid}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">`;
                rows += `                   <i class="fa fa-ellipsis-v"></i>`;
                rows += `               </button>`;
                rows += `               <ul class="dropdown-menu ulToLeft" aria-labelledby="dropdownMenu${review.sid}">`;
                if (review.is_template == 0) {
                    rows += `                   <li><a href="javascript:void(0)" class="jsSaveReviewAsTemplate csF16"><i class="fa csF16 fa-save"></i> Save As Template</a></li>`;
                }
                rows += `                   <li role="separator" class="divider"></li>`;
                rows += `                   <li><a href="javascript:void(0)" class="jsAddReviewers csF16"><i class="fa csF16 fa-plus-circle"></i> Add Reviewers</a></li>`;
                if (review.status == 'started') {
                    rows += `                   <li><a href="javascript:void(0)" class="jsEndReview csF16"><i class="fa csF16 fa-stop-circle"></i> End Review</a></li>`;
                }
                if (review.status == 'ended') {
                    rows += `                   <li><a href="javascript:void(0)" class="jsReopenReview csF16"><i class="fa csF16 fa-stop-circle"></i> Re-open Review</a></li>`;
                }
                if (review.is_archived == 0) {
                    rows += `                   <li><a href="javascript:void(0)" class="jsReviewStatus csF16" data-type="1"><i class="fa csF16 fa-archive"></i> Archive Review</a></li>`;
                } else {
                    rows += `                   <li><a href="javascript:void(0)" class="jsReviewStatus csF16" data-type="0"><i class="fa csF16 fa-check"></i> Activate Review</a></li>`;
                }
                rows += `               </ul>`;
                rows += `           </div>`;
                rows += `       </div>`;
            }
            rows += `   </td>`;
            rows += `</tr>`;
        });
        //
        $('#jsReviewWrap').html(rows);
    }


    /**
     * 
     */
    function reviewStatusChange(reviewId, type) {
        return new Promise((res) => {
            $.post(pm.urls.handler, {
                action: 'change_review_status',
                reviewId: reviewId,
                type: type
            }, (resp) => {
                res(resp);
            });
        });
    }

    /**
     * 
     */
    function endReview(reviewId) {
        return new Promise((res) => {
            $.post(pm.urls.handler, {
                action: 'end_review',
                reviewId: reviewId
            }, (resp) => {
                res(resp);
            });
        });
    }

    /**
     * 
     */
    function reopenReview(reviewId) {
        return new Promise((res) => {
            $.post(pm.urls.handler, {
                action: 'reopen_review',
                reviewId: reviewId
            }, (resp) => {
                res(resp);
            });
        });
    }

});