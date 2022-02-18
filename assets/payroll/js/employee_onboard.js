/**
 * Process employee onboard for payroll
 *
 * @package Employee Payroll Onboarding
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
$(function EmployeeOnboard() {
    /**
     * Set modal id
     * @type {string}
     */
    var modalId = "jsEmployeeOnboardModal";

    /**
     * Set modal loader key
     * @type {string}
     */
    var modalLoader = modalId + "Loader";

    /**
     * Holds the main table where employees will
     * be loaded
     * 
     * @type {object}
     */
    var mainViewRef = $('#jsPayrollEmployeesListingBox');

    /**
     * Holds the main table where employees will
     * be loaded
     * 
     * @type {object}
     */
    var mainViewCountRef = $('#jsPayrollEmployeesListingCount');

    /**
     * Saves the XHR (AJAX) object
     * @type {null|object}
     */
    var xhr = null;

    /**
     * Holds total employees count to move
     * @type number
     */
    var total_employees = 0;

    /**
     * Holds current employee position
     * @type number
     */
    var current_employee = 0;

    /**
     * Holds employees
     * @type array
     */
    var selectedIds = [];

    /**
     * Holds selected employee id
     * @type number
     */
    var selectedEmployeeId = 0;

    /**
     * Starts the process of adding employees on payroll
     * 
     */
    $('#jsPayrollEmployeeAddBtn').click(function(event) {
        //
        event.preventDefault();
        //
        total_employees = 0;
        current_employee = 0;
        selectedIds = [];
        //
        xhr = null;
        //
        Model({
            Id: modalId,
            Title: '<span class="' + modalId + 'Title">Add Employees To Payroll</span>',
            Body: '<div id="' + modalId + 'Body"></div>',
            Loader: modalLoader,
            Container: 'container',
            CancelClass: 'btn-cancel csW'
        }, getEmployeesForOnboard);
    });

    /**
     * 
     */
    $(document).on('click', '#jsMoveEmployeesToPayroll', function(event) {
        //
        event.preventDefault();
        //
        selectedIds = [];
        //
        if (!$('input[name="jsEmployeesList[]"]:checked').length) {
            return alertify.alert('Error!', 'Please select at least one employee.', function() {});
        }
        //
        $('input[name="jsEmployeesList[]"]:checked').map(function() {
            //
            selectedIds.push($(this).val());
        });
        //
        MoveEmployeesToPayroll();
    });

    /**
     * 
     */
    $(document).on('click', '.jsPayrollEmployeeDelete', function(event) {
        //
        event.preventDefault();
        //
        var employeeId = $(this).closest('.jsPayrollOnEmployeeRow').data('id');
        //
        return alertify.confirm(
            "Do you really want to delete this employee from payroll? <br /> This action is not revertible.",
            function() {
                PayrollEmployeeDelete(employeeId);
            }
        ).setHeader('Confirm!');
    });

    /**
     * 
     */
    $(document).on('click', '.jsPayrollEmployeeEdit', function(event) {
        //
        event.preventDefault();
        //
        selectedEmployeeId = $(this).closest('.jsPayrollOnEmployeeRow').data('id');
        //
        StartOnboardProcess();
    });

    /**
     * Load employees that are on payroll
     */
    function LoadPayrollEmployees() {
        //
        xhr = $.get(
                baseURI + 'payroll/get/' + (companyId) + '/payroll_employees'
            )
            .done(function(response) {
                //
                xhr = null;
                //
                LoadPayrollEmployeesView(response);
            })
            .fail(ErrorHandler);
    }

    /**
     * Load payroll employees view
     * 
     * @param {object} employees
     */
    function LoadPayrollEmployeesView(employees) {
        //
        mainViewCountRef.text("Total: " + (Object.keys(employees).length) + "");
        //
        var trs = '';
        //
        if (!Object.keys(employees).length) {
            trs += '<tr>';
            trs += '    <td colspan="4"><p class="alert alert-info text-center">No employees found.</p></td>';
            trs += '</tr>';
            //
            return mainViewRef.html(trs);
        }
        //
        for (var index in employees) {
            //
            var employee = employees[index];
            //
            trs += '<tr class="jsPayrollOnEmployeeRow" data-id="' + (employee['user_id']) + '">';
            trs += '    <td class="vam"><strong>' + (employee.full_name_with_role) + '</strong></td>';
            trs += '    <td class="text-right vam">' + (employee.payroll_employee_id) + '</td>';
            trs += '    <td class="text-right vam text-' + (employee.payroll_onboard_status['status'] == 'completed' ? 'success' : 'warning') + '"><strong>' + (employee.payroll_onboard_status['status'].toUpperCase()) + '</strong><br /><br />';
            trs += '        <table class="table table-striped table-condensed">';
            trs += '            <tbody>';
            //
            for (var index in employee.payroll_onboard_status.details) {
                var ss = employee.payroll_onboard_status.details[index];
                trs += '                <tr>';
                trs += '                    <th class="vam">' + (index.replace(/_/g, ' ').toUpperCase()) + '</th>';
                trs += '                    <td class="text-right vam text-' + (ss == 1 ? "success" : "danger") + '">' + (ss == 1 ? "Completed" : "Pending") + '</td>';
                trs += '                </tr>';
            };
            trs += '            </tbody>';
            trs += '        </table>';
            trs += '    </td>';
            trs += '    <td class="text-right vam">';
            trs += '        <button class="btn btn-warning jsPayrollEmployeeEdit"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Edit</button>';
            if (employee.payroll_onboard_status.status.toLowerCase() != 'completed') {
                trs += '        <button class="btn btn-danger jsPayrollEmployeeDelete"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Delete</button>';
            }
            trs += '    </td>';
            trs += '</tr>';
        }
        //
        return mainViewRef.html(trs);
    }

    /**
     * Fetched employees that are not on payroll
     */
    function getEmployeesForOnboard() {
        //
        xhr = $.get(
                baseURI + 'payroll/get/' + (companyId) + '/employees'
            )
            .done(function(response) {
                //
                LoadEmployeesForPayroll(response)
            })
            .error(ErrorHandler);
    }

    /**
     * Load add employee to payroll view
     * 
     * @param {object} employees 
     */
    function LoadEmployeesForPayroll(employees) {
        //
        var html = '';
        //
        html += '<div class="container">';
        //
        if (!Object.keys(employees).length) {
            //
            html += '<div class="row">';
            html += '   <div class="col-xs-12">';
            html += '       <p class="alert alert-info text-center"><strong>Looks like there are no employees that need to be on payroll.</strong>';
            html += '       </p>';
            html += '   </div>';
            html += '</div>';
            html += '</div>';
            //
            $('#' + (modalId) + 'Body').html(html);
            //
            return ml(false, modalLoader);
        }
        //
        html += '<div class="row">';
        html += '   <div class="col-xs-12">';
        html += '       <h3 class="alert pl0">Please select the employees that you want to be part of payroll.</h3>';
        html += '   </div>';
        html += '</div>';
        //
        for (var index in employees) {
            //
            var employee = employees[index];
            //
            html += '<div class="row" id="jsPayrollEmployeeRow' + (employee['user_id']) + '">';
            html += '   <div class="col-xs-12 col-md-12">';
            html += '       <label class="control control--checkbox">';
            html += '           <input type="checkbox" name="jsEmployeesList[]" class="jsEmployeesList" value="' + (employee['user_id']) + '" />';
            html += employee.full_name_with_role;
            html += '           <div class="control__indicator"></div>';
            html += '       </label>';
            html += '       <div id="jsEmployeeError' + (employee['user_id']) + '" class="text-danger"></div>';
            html += '   </div>';
            html += '</div>';
            html += '<br />';
        }
        //
        html += '<div class="row">';
        html += '   <div class="col-xs-12 text-right">';
        html += '       <button class="btn btn-success" id="jsMoveEmployeesToPayroll"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Onboard Selected Employees To Payroll</button>';
        html += '   </div>';
        html += '</div>';
        //
        html += '</div>';
        //
        $('#' + (modalId) + 'Body').html(html);
        //
        ml(false, modalLoader);
    }

    /**
     * Move employees to payroll
     */
    function MoveEmployeesToPayroll() {
        //
        total_employees = selectedIds.length;
        current_employee = 1;
        //
        MoveEmployeeToPayroll();
    }

    /**
     * Moves a single employee to payroll
     * @returns 
     */
    function MoveEmployeeToPayroll() {
        //
        if (current_employee > total_employees) {
            // Show success message
            return alertify.alert('Success', "The process has been completed.", function() {});
        }
        //
        xhr = $.post(
                baseURI + 'payroll/onboard_employee/' + companyId, {
                    employee_id: selectedIds[current_employee - 1],
                    need_response: true
                }
            )
            .done(function(response) {
                //
                if (response.errors) {
                    $('#jsEmployeeError' + (selectedIds[current_employee - 1]) + '').html("Error: " + response.errors.join('<br />'))
                } else {
                    $('#jsPayrollEmployeeRow' + (selectedIds[current_employee - 1]) + '').remove();
                    //
                    selectedIds.splice(current_employee - 1, 1);
                }
                //
                current_employee++;
                //
                MoveEmployeeToPayroll();
            })
            .error(ErrorHandler);
    }

    /**
     * Deletes an onboarding employee from
     * payroll
     * @param {number} employeeId 
     * @method LoadPayrollEmployees
     */
    function PayrollEmployeeDelete(employeeId) {
        //
        xhr = $.ajax({
                method: "DELETE",
                url: baseURI + 'payroll/onboard_employee/' + companyId + '/' + employeeId,
            }).done(function(response) {
                //
                if (response.errors) {
                    return alertify.alert('Error!', response.errors.join('<br />'), function() {});
                }
                //
                return alertify.alert('Success!', 'You have successfully deleted the employee from payroll.', function() {
                    LoadPayrollEmployees();
                });
            })
            .fail(ErrorHandler);
    }

    /**
     * Starts payroll onboard process
     */
    function StartOnboardProcess() {
        //
        var modalId = 'jsEmployeeOnboardModel';
        //
        Model({
            Id: modalId,
            Title: '<span class="' + modalId + 'Title">Onboard Process</span>',
            Body: '<div id="' + modalId + 'Body"></div>',
            Loader: modalId + 'Loader',
            Container: 'container',
            CancelClass: 'btn-cancel csW'
        }, AddUpdateCompanyEmployeeProfile);
    }

    /**
     * Add employee onboarding
     */
    function AddUpdateCompanyEmployeeProfile() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/get_company_employee_profile/" + companyId),
                data: { employee_id: selectedEmployeeId },
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    // $(".jsAddEmployeeCancel").click(ShowCompanyEmployeeList);
                    // $(".jsPayrollSaveCompanyEmployee").click(SaveCompanyEmployeeProfile);
                    //
                    $(".jsDatePicker").datepicker({
                        format: "m/d/Y",
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-100:+50",
                    });
                    //
                    ml(false, 'jsEmployeeOnboardModelLoader');
                });
            })
            .error(ErrorHandler);
    }

    /**
     * Handles XHR errors
     * @param {object} error 
     */
    function ErrorHandler(error) {
        //
        xhr = null;
        //
        alertify.alert(
            "Error!",
            "The system failed to process your request. (" + (error.status) + ")"
        );
    }

    /**
     * Get the base URL for the current
     * site
     * @param {string} url
     * @returns {string} generated url
     */
    function GetURL(url) {
        return window.location.origin + "/" + (url || "");
    }

    /**
     * Loads page onto the modal
     * @param {string}   content
     * @param {function} cb
     */
    function LoadContent(content, cb) {
        //
        $("#jsEmployeeOnboardModelBody").html(content);
        //
        !cb ? ml(false, modalLoader) : cb();
    }

    // Quickly load all the employees
    LoadPayrollEmployees();
});