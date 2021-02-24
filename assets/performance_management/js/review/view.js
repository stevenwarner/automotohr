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

    //
    loadReviews();


    /**
     * 
     */
    function loadReviews() {
        //
        if (XHR !== null) XHR.abort();
        //
        XHR = $.post(
            pm.urls.handler, {
                action: 'get_review_listing'
            },
            (resp) => {
                XHR = null;
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
            $('#jsReviewWrap').html(`<tr><td colspan="5"><p class="alert alert-info text-center">No Reviews found.</p></td></tr>`);
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
            const reviewerProgress = getProgress(review.reviewers, 'review');
            const feedbackProgress = getProgress(review.reviewers, 'review');
            //
            let status = '';
            //
            if (review.status == 'started') {
                status = '<strong class="btn btn-xs alert-success">Running</strong>';
            } else if (review.status == 'ended') {
                status = '<strong class="btn btn-xs alert-danger">Ended</strong>';
            }
            //
            rows += `<tr data-id="${review.sid}">`;
            rows += `   <td><p>${review.review_title} ${status}</p></td>`;
            rows += `   <td>${convertDate(review.review_start_date)}</td>`;
            rows += `   <td>`;
            rows += `        <div class="progress csRadius100">`;
            rows += `           <div class="progress-bar" role="progressbar" aria-valuenow="${reviewerProgress.completed}" aria-valuemin="0" aria-valuemax="${reviewerProgress.total}" style="width: ${reviewerProgress.completed}%;"></div>`;
            rows += `        </div>`;
            rows += `        <small>${reviewerProgress.completed}% Not Completed</small>`;
            rows += `   </td>`;
            rows += `   <td>`;
            rows += `        <div class="progress csRadius100">`;
            rows += `           <div class="progress-bar" role="progressbar" aria-valuenow="${feedbackProgress.completed}" aria-valuemin="0" aria-valuemax="${feedbackProgress.total}" style="width: ${feedbackProgress.completed}%;"></div>`;
            rows += `        </div>`;
            rows += `        <small>${feedbackProgress.completed}% Not Completed</small>`;
            rows += `   </td>`;
            rows += `   <td>`;
            rows += `       <div class="csBTNBox">`;
            rows += `           <a href="${pm.urls.pbase}review/${review.sid}" class="btn btn-black"><i class="fa fa-eye"></i> View</a>`;
            rows += `           <div class="dropdown">`;
            rows += `               <button class="btn dropdown-toggle" type="button" id="dropdownMenu${review.sid}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">`;
            rows += `                   <i class="fa fa-ellipsis-v"></i>`;
            rows += `               </button>`;
            rows += `               <ul class="dropdown-menu ulToLeft" aria-labelledby="dropdownMenu${review.sid}">`;
            rows += `                   <li><a href="${pm.urls.pbase}download/review/${review.sid}">Download Report</a></li>`;
            rows += `                   <li><a href="${pm.urls.pbase}print/review/${review.sid}">Print</a></li>`;
            rows += `                   <li><a href="javascript:void(0)" class="jsSaveReviewAsTemplate">Save As Template</a></li>`;
            rows += `                   <li role="separator" class="divider"></li>`;
            rows += `                   <li><a href="javascript:void(0)" class="jsAddReviewers">Add Reviewers</a></li>`;
            rows += `                   <li><a href="javascript:void(0)" class="jsEndReview">End Review</a></li>`;
            rows += `                   <li><a href="javascript:void(0)" class="jsArchiveReview">Archive Review</a></li>`;
            rows += `               </ul>`;
            rows += `           </div>`;
            rows += `       </div>`;
            rows += `   </td>`;
            rows += `</tr>`;
        });
        //
        $('#jsReviewWrap').html(rows);
    }

});