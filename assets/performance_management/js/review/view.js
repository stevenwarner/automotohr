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
        loadReviews();
    });

    /**
     * 
     */
    $('#jsFilterReviewName').change(function() { filter.reviewTitle = $(this).val() || -1; });

    /**
     * 
     */
    $('#jsFilterReviewType').change(function() { filter.reviewType = $(this).val() || -1; });

    /**
     * 
     */
    $('#jsFilterStatus').change(function() { filter.status = $(this).val() || -1; });

    //
    loadReviews();


    /**
     * 
     */
    function loadReviews() {
        ml(false, 'review_listing');
    }

});