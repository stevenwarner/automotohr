$(function(){
    //
    let obj = {
        employee: 0,
        type: '1',
        selectedEmployees: [],
        canApprove: 0,
        deactivate: 0
    };

    //
    $('input[name="js-is-department-add"]').click(function(){
        if($(this).val() == 1){
            $('.js-team-row-add').hide();
            $('.js-department-row-add').show();
        } else{
            $('.js-department-row-add').hide();
            $('.js-team-row-add').show();
        }
    });
    
    //
    $('#js-save-add-btn').click(function(e){
        //
        e.preventDefault();
        //
        obj.employee = getField('#js-employee-add');
        obj.type = getField('.js-is-department-add:checked');
        if(obj.type == 1)
            obj.selectedEmployees = getField('#js-departments-add');
        else
            obj.selectedEmployees = getField('#js-teams-add');

        obj.canApprove = $('#js-approve-100-percent-add').prop('checked') === true ? 1 : 0;
        obj.deactivate = $('#js-archive-check-add').prop('checked') === true ? 1 : 0;
        //
        if(obj.employee == 0){
            alertify.alert('WARNING!', 'Please select employee.', () => {});
            return false;
        }
        //
        if(obj.selectedEmployees == 0){
            alertify.alert('WARNING!', 'Please select departments/teams.', () => {});
            return false;
        }
        //
        addApprover(obj);
    });

    //
    function addApprover(type){
        //
        ml(true, 'approver');
        //
        let post = Object.assign({}, type, {
            action: 'create_approver',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            public: 0
        });
        //
        $.post(handlerURL, post, (resp) => {
            ml(false, 'approver');
            //
            if(resp.Redirect === true){
                //
                alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                    window.location.reload();
                });
                return;
            }
            // On fail
            if(resp.Status === false){
                alertify.alert('WARNING!', resp.Response, () => {});
                return;
            }
            // On success
            alertify.alert('SUCCESS!', resp.Response, () => {
                loadViewPage();
            });
            return;
        });
    }
})