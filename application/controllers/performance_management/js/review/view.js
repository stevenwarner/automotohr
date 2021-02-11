$(function() {
    // 
    $('#jsFilterDepartments').select2({
        minimumResultsForSearch: -1,
        selectionTitleAttribute: false
    }).tooltip('disable');

    // 
    $('#jsFilterTeams').select2({
        selectionTitleAttribute: false
    }).tooltip('disable');
})