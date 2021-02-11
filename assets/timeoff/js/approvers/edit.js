$(function(){
    //
    let obj = {
        employee: 0,
        type: '1',
        selectedEmployees: [],
        canApprove: 0,
        deactivate: 0
    },
    callOBJ = {
        Approver:{
            Main: {
                action: 'get_single_approver',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                public: 0,
            }
        },
    },
    approverId = 0;

    //
    window.timeoff.startEditprocess = startEditprocess;

    //
    $('input[name="js-is-department-edit"]').click(function(){
        if($(this).val() == 1){
            $('.js-team-row-edit').hide();
            $('.js-department-row-edit').show();
        } else{
            $('.js-department-row-edit').hide();
            $('.js-team-row-edit').show();
        }
    });
    
    //
    $('#js-save-edit-btn').click(function(e){
        //
        e.preventDefault();
        //
        obj.employee = getField('#js-employee-edit');
        obj.type = getField('.js-is-department-edit:checked');
        if(obj.type == 1)
            obj.selectedEmployees = getField('#js-departments-edit');
        else
            obj.selectedEmployees = getField('#js-teams-edit');

        obj.canApprove = $('#js-approve-100-percent-edit').prop('checked') === true ? 1 : 0;
        obj.deactivate = $('#js-archive-check-edit').prop('checked') === true ? 1 : 0;
        //
        if(obj.employee == 0){
            alertify.alert('WARNING!', 'Please select an employee.', () => {});
            return false;
        }
        //
        if(obj.selectedEmployees == 0){
            alertify.alert('WARNING!', 'Please select departments/teams.', () => {});
            return false;
        }
        //
        updateApprover(obj);
    });

    //
    function updateApprover(type){
        //
        ml(true, 'approver');
        //
        let post = Object.assign({}, type, {
            action: 'update_approver',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            public: 0,
            approverId: approverId
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

    //
    function startEditprocess(id){
        //
        approverId = id;
        //
        $.post(handlerURL, Object.assign(callOBJ.Approver.Main, { approverId: approverId}), (resp) => {
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
                //
                ml(false, 'approver');
                //
                alertify.alert('WARNING!', resp.Response, () => {});
                //
                return;
            }
            //
            obj.employee = resp.Data.employee_sid;
            obj.type= resp.Data.is_department;
            obj.selectedEmployees= resp.Data.department_sid.split(',');
            obj.canApprove= resp.Data.approver_percentage;
            obj.deactivate= resp.Data.is_archived;
            //
            $('#js-employee-edit').select2('val', obj.employee);
            //
            $(`.js-is-department-edit[value="${obj.type}"]`).prop('checked', true);
            //
            //
            if(obj.type == 1){
                $('#js-departments-edit').select2('val', obj.selectedEmployees);
            } else{
                $('#js-teams-edit').select2('val', obj.selectedEmployees);
            }
            $(`.js-${obj.type == 1 ? 'department' : 'team'}-row-edit`).show();
            //
            $('#js-approve-100-percent-edit').prop('checked', obj.canApprove == 1 ? true : false);
            $('#js-archived-edit').prop('checked', obj.deactivate === 1 ? true : false);
            //
            ml(false, 'approver');
        });
    }
})