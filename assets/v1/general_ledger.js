//
$(function () {
	$('#jsStartDate').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoApply: true,
        locale: {
            format: "MM-DD-YYYY",
        },
    })

    $('#jsEndDate').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoApply: true,
        locale: {
            format: "MM-DD-YYYY",
        },
    });

    $('#btn_apply_filters').on('click', function(e) {
        e.preventDefault();
        generate_search_url();
        window.location = $(this).attr('href').toString();
    });

    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {
        //
        var start_date_applied = $('#jsStartDate').val();
        var end_date_applied = $('#jsEndDate').val();
        //
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';



        var url = baseURL + 'general_ledger/' +  start_date_applied + '/' + end_date_applied;

        $('#btn_apply_filters').attr('href', url);
    }
});