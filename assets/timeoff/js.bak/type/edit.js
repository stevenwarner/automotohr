$(function(){
    //
    let typeOBJ = {
        type: 0,
        policies: 0,
        deactivate: 0,
    },
    callOBJ = {
        Type:{
            Main: {
                action: 'get_single_type',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                public: 0,
            }
        },
    },
    typeId = 0;

    //
    window.timeoff.startEditprocess = startEditprocess;
    
    
    //
    $('#js-save-edit-btn').click(function(e){
        //
        e.preventDefault();
        //
        typeOBJ.type = getField('#js-type-edit');
        typeOBJ.policies = getField('#js-policies-edit');
        typeOBJ.deactivate = $('#js-archived-edit').prop('checked') === true ? 1 : 0;
        //
        if(typeOBJ.type == 0){
            alertify.alert('WARNING!', 'Type name is required.', () => {});
            return false;
        }
        //
        editType(typeOBJ);
    });

    //
    function editType(type){
        //
        ml(true, 'type');
        //
        let post = Object.assign({}, type, {
            action: 'update_type',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            public: 0,
            typeId: typeId
        });
        //
        $.post(handlerURL, post, (resp) => {
            ml(false, 'type');
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
        typeId = id;
        //
        $.post(handlerURL, Object.assign(callOBJ.Type.Main, { typeId: typeId}), (resp) => {
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
                ml(false, 'type');
                //
                alertify.alert('WARNING!', resp.Response, () => {});
                //
                return;
            }
            //
            $('#js-type-edit').val(resp.Data.type);
            //
            $('#js-policies-edit').select2();
            $('#js-policies-edit').select2('val', resp.Data.policies.length == window.timeoff.policies.length ? 'all' : resp.Data.policies);
            //
            $('#js-archived-edit').prop('checked', resp.Data.is_archived == 1 ? true: false);
            //
            ml(false, 'type');
        });
    }
})