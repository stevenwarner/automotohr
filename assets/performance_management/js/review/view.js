$(function() {
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

    $('.jsRemoveReviewee').click(function(event) {
        //
        event.preventDefault();
        //
        let id = $(this).closest('tr').data('id');
        //
        alertify.confirm('Do you really want to remove this reviewee?', () => {
                $.post(
                    pm.urls.handler, {
                        action: 'remove_reviewee',
                        id: id,
                        reviewId: pm.Id
                    }, () => {
                        handleSuccess('You have successfully removed the reviewee.', () => {
                            window.location.reload();
                        });
                    }
                );
            }).setHeader('Confirm!')
            .set('labels', {
                ok: "Yes",
                cancel: "No"
            });
    });

    //
    $('.jsReviewPeriodReviewee').click(function(event) {
        //
        event.preventDefault();
        //
        let id = $(this).closest('tr').data('id');
        let name = $(this).closest('tr').data('name');
        let sd = $(this).closest('tr').data('sd');
        let ed = $(this).closest('tr').data('ed');
        //
        Modal({
            Id: 'jsChangeReviewDateModal',
            Loader: 'jsChangeReviewDateModalLoader',
            Title: 'Change Review Date For ' + name,
            Body: `
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="csF16 csB7">Review Period <span class="csRequired"></span></label>
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="csF16 csB7">Start Date</label>
                                <input type="text" class="form-control csF16" id="jsChangeReviewDateModalStartDate" value="${sd}" readonly placeholder="MM/DD/YYYY" />
                            </div>
                            <div class="col-sm-4">
                            <label class="csF16 csB7">Due Date</label>
                                <input type="text" class="form-control csF16" id="jsChangeReviewDateModalEndDate" value="${ed}" readonly placeholder="MM/DD/YYYY" />
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-orange csF16" id="jsChangeReviewDateModalSaveBTN" data-id="${id}"><i class="fa fa-save csF16"></i> Update</button>
                    </div>
                </div>
            </div>
            `
        }, function() {
            $('#jsChangeReviewDateModalStartDate').datepicker({
                changeYear: true,
                changeMonth: true,
                minDate: 0,
                formatDate: pm.dateTimeFormats.ymdf,
                onSelect: function(d) {
                    $('#jsChangeReviewDateModalEndDate').datepicker('option', 'minDate', d);
                }
            });
            //
            $('#jsChangeReviewDateModalEndDate').datepicker({
                changeYear: true,
                changeMonth: true,
                minDate: 0,
                formatDate: pm.dateTimeFormats.ymdf
            });
            //
            loadFonts();
            //
            ml(false, 'jsChangeReviewDateModalLoader');
        });
    });

    $(document).on('click', '#jsChangeReviewDateModalSaveBTN', function(event) {
        ///
        event.preventDefault();
        //
        ml(true, 'jsChangeReviewDateModalLoader');
        //
        $.post(
            pm.urls.handler, {
                action: 'update_review_period',
                reviewId: pm.Id,
                startDate: $('#jsChangeReviewDateModalStartDate').val(),
                endDate: $('#jsChangeReviewDateModalEndDate').val(),
                revieweeId: $(this).data('id')
            }, () => {
                ml(false, 'jsChangeReviewDateModalLoader');
                handleSuccess('You have successfully updated the review period.', () => {
                    window.location.reload();
                });
            }
        );

    });

});