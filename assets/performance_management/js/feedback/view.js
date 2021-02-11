$(function() {
    // 
    $('#jsFilterGoalType').select2({
        minimumResultsForSearch: -1,
        selectionTitleAttribute: false
    }).tooltip('disable');

    // 
    $('#jsFilterReviewPeriod').select2({
        minimumResultsForSearch: -1,
        selectionTitleAttribute: false
    }).tooltip('disable');

    $('[data-content]').popover({
        trigger: 'hover',
        placement: 'top'
    })

})