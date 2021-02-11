<script>
    //
    var employeeList = <?=json_encode($employeeList);?>;
    var departmentList = <?=json_encode($departmentList);?>;
    var teamList = <?=json_encode($teamList);?>;
    var rolesList = <?=json_encode([
        'hiring_manager' => 'Hiring Manager',
        'admin' => 'Admin'
    ]);?>;
    //
    function getVisibility(){
        let html = '';
        //
        let roleOPT = '';
        let employeesOPT = '';
        let departmentOPT = '';
        let teamOPT = '';
        //
        $.each(rolesList, (ind, role) => {
            roleOPT += `<option value="${ind}">${role}</option>`;
        });
        $.each(employeeList, (ind, emp) => {
            if(emp.access_level_plus == 1 || emp.access_level == 'Manager' || emp.access_level == 'Employee') return;
            employeesOPT += `<option value="${emp.sid}">${remakeEmployeeName(emp)}</option>`;
        });
        $.each(departmentList, (ind, emp) => {
            departmentOPT += `<option value="-1">All Departments</option>`;
            departmentOPT += `<option value="${emp.sid}">${emp.name}</option>`;
        });
        $.each(teamList, (ind, emp) => {
            teamOPT += `<option value="-1">All Teams</option>`;
            teamOPT += `<option value="${emp.sid}">${emp.name}</option>`;
        });

        //
        html += `<div class="row">
                    <div class="col-xs-12" style="margin-bottom: 10px;">
                        <label class="control control--checkbox">
                            Visible To Payroll Plus
                            <input type="checkbox" id="js-modify-visible-to-payroll" name="jmvtp" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>`;
        //
        html += `<div class="row">
                    <div class="col-xs-12">
                        <label>Select Roles <i class="fa fa-question-circle" data-toggle="propover" title="Note" data-content="This document will be visible to the selected Roles."></i></label>
                        <select multiple="true" id="js-modify-roles">${roleOPT}</select>
                    </div>
                </div>
                `;
        html += `<div class="row">
                    <div class="col-xs-12">
                        <label>Select Specific Employee(s) <i class="fa fa-question-circle" data-toggle="propover" title="Note" data-content="This document will be visible to the selected Employee(s)."></i></label>
                        <select multiple="true" id="js-modify-selected-employees">${employeesOPT}</select>
                    </div>
                </div>
                `;
        html += `<div class="row">
            <div class="col-xs-12">
                <label>Select Department(s) <i class="fa fa-question-circle" data-toggle="propover" title="Note" data-content="This document will be visible to the selected Department(s)."></i></label>
                <select multiple="true" id="js-modify-selected-departments">${departmentOPT}</select>
            </div>
        </div>
        `;
        html += `<div class="row">
            <div class="col-xs-12">
                <label>Select Team(s) <i class="fa fa-question-circle" data-toggle="propover" title="Note" data-content="This document will be visible to the selected Team(s)."></i></label>
                <select multiple="true" id="js-modify-selected-teams">${teamOPT}</select>
            </div>
        </div>
        `;

        return html;
    }
</script>

<style>.select2-container--default .select2-selection--multiple .select2-selection__rendered{ height: auto !important;}</style>