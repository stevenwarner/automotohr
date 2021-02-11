let cmnOBJ = {
    Categories:{
        Main: {
            action: 'get_company_types_list',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    },
    Employees:{
        Main: {
            action: 'get_company_employees',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    },
    Policies:{
        Main: {
            action: 'get_policy_list_by_company',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    }
};

//
fetchCategories();
fetchPolicies();

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
// $('.js-view-type-btn').click((e) => {
//     //
//     e.preventDefault();
//     //
//     loadViewPage();
// });

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
//
$(document).on('click', '.js-edit-row-btn', (e) => {
    //
    e.preventDefault();
    //
    loadEditPage($(e.target).closest('tr').data('id'));
});

//
function getTypeNames( ids ){
    if(ids.length == 0) return 'Not Assigned';
    if(window.timeoff.categories.length == 0) return 'Not Assigned';
    //
    let row = '';
    //
    window.timeoff.categories.map(function(v){
        if(v.type_id == ids) row = v.type_name+', ';
    });
    //
    return row.substring(0, row.length - 2);
}

// Fetch the categories
function fetchCategories(){
    $.post(handlerURL,  cmnOBJ.Categories.Main, function(resp){
        //
        if(resp.Redirect === true){
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if(resp.Status === false){
            console.log('No categories found.');
            return;
        }
        //
        window.timeoff.categories = resp.Data;
        //
        let rows = '';
        //
        rows += '<option value="-1" selected="true">All</option>';
        //
        window.timeoff.categories.map(function(v){
            rows += `<option value="${v.type_id}">${v.type_name}</option>`;
        });
        //
        $('#js-filter-types').html(rows);
        $('#js-filter-types').select2();
    });
}

// Polciies
function fetchPolicies(){
    $.post(handlerURL, cmnOBJ.Policies.Main, function(resp){
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
        window.timeoff.policies = resp.Data;
        //
        rows += '<option value="all">All</option>';
        //
        resp.Data.map(function(v){
            rows += `<option value="${v.policy_id}">${v.policy_title}</option>`;
        });
        //
        $('#js-filter-policies').html(rows);
        $('#js-filter-policies').select2();
        //
        $('#js-policies-add').html(rows);
        $('#js-policies-edit').html(rows);
    });
}


// Pages
// Add policy page
function loadAddPage(){
    //
    page = 'add';
    // Show loader
    ml(true, 'type');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-add').fadeIn(500);
    // Check if polciies are loaded
   
    // Reset view
    //
    $('#js-type-add').val('');
    //
    $('#js-policies-add').select2();
    $('#js-policies-add').select2('val', null);
    //
    $('#js-archived-add').prop('checked', false);
    //
    ml(false, 'type');
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
    window.timeoff.fetchCompanyTypes();
}

//
function loadEditPage(typeId){
    //
    page = 'edit';
    // Show loader
    ml(true, 'type');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-edit').fadeIn(500);
    // Check if polciies are loaded
    if(
        window.timeoff.policies === undefined
    ){
        setTimeout(loadEditPage, 2000);
        return;
    }

    // Reset view
    //
    $('#js-type-edit').val('');
    //
    $('#js-policies-edit').select2();
    $('#js-policies-edit').select2('val', null);
    //
    $('#js-archived-edit').prop('checked', false);
    //
    window.timeoff.startEditprocess(typeId);
}

