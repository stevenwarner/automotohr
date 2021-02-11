$(function() {
    // 
    $('#jsFilterReviewType').select2({
        minimumResultsForSearch: -1,
        selectionTitleAttribute: false
    }).tooltip('disable');

    // 
    $('#jsFilterReviewName').select2({
        selectionTitleAttribute: false
    }).tooltip('disable');
})