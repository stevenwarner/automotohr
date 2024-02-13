let cmnOBJ = {
    Employees:{
        Main: {
            action: 'get_company_employees_for_approvers',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    },
    DepartmentTeam:{
        Main: {
            action: 'get_company_departments_and_teams',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    },
    
};

//
fetchEmployees();
fetchDepartmentTeams();

//
if(page == 'add') loadAddPage();

//
// $('.jsViewPoliciesBtn').click(loadViewPage);

//
$('#js-add-type-btn').click((e) => {
    //
    e.preventDefault();
    //
    loadAddPage();
});
//

//
$(document).on('click', '.js-edit-row-btn', (e) => {
    //
    e.preventDefault();
    //
    loadEditPage($(e.target).closest('tr').data('id'));
});

//
$('.js-view-type-btn').click(function(e){
    e.preventDefault();
    //
    alertify.confirm(
        'WARNING!', 
        'Any unsaved changes will be lost.',
        loadViewPage,
        () => {}
    ).set('labels', {
        ok: "Leave",
        cancel: "No, I will stay"
    });
});


// Employees
function fetchEmployees(){
    $.post(handlerURL, cmnOBJ.Employees.Main, function(resp){
        //
        if(resp.Redirect === true){
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if(resp.Status === false){
            console.log('Failed to load employees.');
            return;
        }
        //
        window.timeoff.employees = resp.Data;
        //
        let rows = '', dRows;
        //
        rows += '<option value="all">All</option>';
        //
        window.timeoff.employees.map(function(v){
            let status = "";
			if (v.terminated_status === "1") {
				status = " - Terminated";
			} else if (v.active === "0") {
				status = " - Deactivated";
			}
            rows += '<option value="'+( v.user_id )+'">'+( remakeEmployeeName( v ) )+status+'</option>';
            dRows += '<option value="'+( v.user_id )+'">'+( remakeEmployeeName( v ) )+status+'</option>';
        });
        //
        $('#js-employee-add').html(dRows);
        $('#js-employee-add').select2();
        $('#js-employee-add').select2MultiCheckboxes({
            templateSelection: function(selected, total) {
                total--;
                return "Selected " +( $.inArray('all', $('#js-employee-add').val()) !== -1 ? total : selected.length )+ " of " + total;
            }
        });
        //
        $('#js-employee-edit').html(dRows);
        $('#js-employee-reset').html(dRows);
        $('#js-filter-employee').html(rows);
        $('#js-filter-employee').select2();
    });
}


// Departments
function fetchDepartmentTeams(){
    $.post(handlerURL, cmnOBJ.DepartmentTeam.Main, function(resp){
        //
        if(resp.Redirect === true){
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if(resp.Status === false){
            console.log('Failed to load employees.');
            return;
        }
        //
        let rows = '';
        //
        window.timeoff.dt = resp.Data;
        //
        rows += '<option value="all">All</option>';
        //
        if(window.timeoff.dt.Departments.length){
            //
            window.timeoff.dt.Departments.map(function(v){
                rows += `<option value="${v.department_id}">${v.title}</option>`;
            });
            //
            $('#js-filter-departments').html(rows);
            $('#js-departments-add').html(rows);
            $('#js-departments-edit').html(rows);
        }
        //
        rows = '<option value="all">All</option>';
        //
        if(window.timeoff.dt.Teams.length){
            //
            window.timeoff.dt.Teams.map(function(v){
                rows += `<option value="${v.team_id}">${v.title}</option>`;
            });
            //
            $('#js-filter-teams').html(rows);
            $('#js-teams-add').html(rows);
            $('#js-teams-edit').html(rows);
        }
        //
        $('#js-filter-departments').select2();
        $('#js-filter-teams').select2();
    });
}


// Pages
// Add policy page
function loadAddPage(){
    //
    page = 'add';
    // Show loader
    ml(true, 'approver');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-add').fadeIn(500);
    // Check if polciies are loaded
    if(
        window.timeoff.employees === undefined
    ){
        setTimeout(loadAddPage, 2000);
        return;
    }

    // Reset view
    //
    $('#js-employee-add').select2();
    $('#js-employee-add').select2('val', null);
    //
    $('.js-is-department-add').prop('checked', false);
    $('.js-department-row-add, .js-team-row-add').hide();
    //
    $('#js-departments-add').select2();
    $('#js-departments-add').select2('val', null);
    //
    $('#js-teams-add').select2();
    $('#js-teams-add').select2('val', null);
    //
    $('#js-approve-100-percent-add').prop('checked', false);
    $('#js-archived-add').prop('checked', false);
    //
    ml(false, 'approver');
}

//
function loadViewPage(){
    //
    page = 'view';
    //
    ml(true, 'policy');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-view').fadeIn(500);
    //
    window.timeoff.fetchCompanyApprovers();
}

//
function loadEditPage(typeId){
    //
    page = 'edit';
    // Show loader
    ml(true, 'approver');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-edit').fadeIn(500);
    // Check if polciies are loaded
    if(
        window.timeoff.employees === undefined
    ){
        setTimeout(loadEditPage, 2000);
        return;
    }

    // Reset view
    //
    $('#js-employee-edit').select2();
    $('#js-employee-edit').select2('val', null);
    //
    $('.js-is-department-edit').prop('checked', false);
    $('.js-department-row-edit, .js-team-row-edit').hide();
    //
    $('#js-departments-edit').select2();
    $('#js-departments-edit').select2('val', null);
    //
    $('#js-teams-edit').select2();
    $('#js-teams-edit').select2('val', null);
    //
    $('#js-approve-100-percent-edit').prop('checked', false);
    $('#js-archived-edit').prop('checked', false);
    //
    window.timeoff.startEditprocess(typeId);
}