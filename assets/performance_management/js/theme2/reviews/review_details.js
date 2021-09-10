$(function ReviewDetailController() {
    //
    var
        reviewId,
        reviewTitle,
        LOADER = 'jsReviewDetailsModalLoader';
    //
    $(document).on('click', '.jsShowReviews', function(event) {
        //
        event.preventDefault();
        //
        reviewId = $(this).closest('.jsReviewBox').data('id');
        //
        reviewTitle = $(this).closest('.jsReviewBox').data('title');
        //
        Modal({
            Id: 'jsReviewDetailsModal',
            Title: 'Details of <b>' + (reviewTitle) + '</b>',
            Body: '<div id="jsReviewDetailsModalBody"></div>',
            Loader: "jsReviewDetailsModalLoader"
        }, function() {
            //
            $.get(
                pm.urls.pbase + 'review/detail/' + reviewId
            ).done(function(resp) {
                //
                $('#jsReviewDetailsModalBody').html(resp);
                //
                ml(false, LOADER);
            });
        });
    });
});