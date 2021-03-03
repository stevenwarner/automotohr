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



});