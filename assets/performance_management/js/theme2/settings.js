$(function() {
    //
    $('#jsReviewRolesInp').select2({ closeOnSelect: false });
    $('#jsReviewDepartmentsInp').select2({ closeOnSelect: false });
    $('#jsReviewTeamsInp').select2({ closeOnSelect: false });
    $('#jsReviewEmployeesInp').select2({ closeOnSelect: false });

    //
    $('.jsUpdateSettings').click(function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.roles = $('#jsReviewRolesInp').val() || [];
        o.departments = $('#jsReviewDepartmentsInp').val() || [];
        o.teams = $('#jsReviewTeamsInp').val() || [];
        o.employees = $('#jsReviewEmployeesInp').val() || [];
        o.companyId = pm.companyId;
        o.employerId = pm.employerId;
        //
        console.log(o);
        ml(true, 'settings');
        //
        $.post(
            pm.urls.pbase + 'update_settings',
            o
        ).done(function(resp) {
            //
            ml(false, 'settings');
            //
            if (resp.Status === false) {
                handleError(resp.Msg);
                return;
            }
            //
            handleSuccess(resp.Msg, function() {
                window.location.reload();
            });
        });
    });


    //
    ml(false, 'settings');
});