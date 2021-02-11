let cmnOBJ = {
    Employees: {
        Main: {
            action: 'get_company_employees',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    },
    Policies: {
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
fetchEmployees();
fetchPolicies();


// Employees
function fetchEmployees() {
    $.post(handlerURL, cmnOBJ.Employees.Main, function(resp) {
        //
        if (resp.Redirect === true) {
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if (resp.Status === false) {
            window.timeoff.employees = [];
            console.log('Failed to load employees.');
            return;
        }
        //
        window.timeoff.employees = resp.Data;
        //
        let rows = '';
        //
        rows += '<option value="all">All</option>';
        //
        window.timeoff.employees.map(function(v) {
            rows += '<option value="' + (v.user_id) + '">' + (remakeEmployeeName(v)) + '</option>';
        });
        //
        $('#js-employee-add').html(rows);
        $('#js-employee-add').select2();
        $('#js-employee-add').select2MultiCheckboxes({
            templateSelection: function(selected, total) {
                total--;
                return "Selected " + ($.inArray('all', $('#js-employee-add').val()) !== -1 ? total : selected.length) + " of " + total;
            }
        });
        //
        $('#js-employee-edit').html(rows);
        $('#js-employee-reset').html(rows);
        $('#js-filter-employee').html(rows);
        $('#js-filter-employee').select2();
    });
}


// Polciies
function fetchPolicies() {
    $.post(handlerURL, cmnOBJ.Policies.Main, function(resp) {
        //
        if (resp.Redirect === true) {
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if (resp.Status === false) {
            console.log('Failed to load policies.');
            return;
        }
        //
        let rows = '';
        //
        rows += '<option value="all">All</option>';
        //
        resp.Data.map(function(v) {
            rows += `<option value="${v.policy_id}">${v.policy_title}</option>`;
        });
        //
        $('#js-filter-policies').html(rows);
        $('#js-filter-policies').select2();
    });
}