$(function(){
    //
    let typeOBJ = {
        type: 0,
        policies: 0,
        deactivate: 0,
    };
    
    //
    $('#js-save-add-btn').click(function(e){
        //
        e.preventDefault();
        //
        typeOBJ.type = getField('#js-type-add');
        typeOBJ.policies = getField('#js-policies-add');
        typeOBJ.deactivate = $('#js-archived-add').prop('checked') === true ? 1 : 0;
        //
        if(typeOBJ.type == 0){
            alertify.alert('WARNING!', 'Type name is required.', () => {});
            return false;
        }
        //
        addType(typeOBJ);
    });

    //
    function addType(type){
        //
        ml(true, 'type');
        //
        let post = Object.assign({}, type, {
            action: 'create_type',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            public: 0
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
})