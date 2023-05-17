/**
 * Process employee onboard for payroll
 *
 * @package Payroll Admins
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
$(function Admin() {
    /**
     * Set modal id
     * @type {string}
     */
    var modalId = "jsAdminOnboardModal";

    /**
     * Set modal loader key
     * @type {string}
     */
    var modalLoader = modalId + "Loader";

    /**
     * Saves the XHR (AJAX) object
     * @type {null|object}
     */
    var xhr = null;

    /**
     * Saves the employee list
     * @type {array}
     */
    var employeeList = [];

    /**
     * Starts the process of adding employees on payroll
     * 
     */
    $('#jsPayrollAdminAddBtn').click(function(event) {
        //
        event.preventDefault();
        //
        xhr = null;
        //
        Model({
            Id: modalId,
            Title: '<span class="' + modalId + 'Title">Add Admin To Payroll</span>',
            Body: '<div id="' + modalId + 'Body" class="csPageWrap"></div>',
            Loader: modalLoader,
            Container: 'container',
            CancelClass: 'btn-cancel csW'
        }, getEmployeesForOnboard);
    });

    /**
     * Handle employee selection
     */
    $(document).on('change', '#jsPayrollAdminEmployees', function() {
        //
        if ($(this).val() === 0) {
            return;
        }
        //
        var id = $(this).val();
        //
        employeeList.map(function(emp) {
            //
            if (emp.user_id == id) {
                $('#jsPayrollAdminFirstName').val(emp.first_name);
                $('#jsPayrollAdminLastName').val(emp.last_name);
                $('#jsPayrollAdminEmail').val(emp.email);
            }
        });
    });

    /**
     * 
     */
    $(document).on('click', '.jsPayrollSaveAdmin', function(event) {
        //
        event.preventDefault();
        //
        var obj = {
            first_name: $('#jsPayrollAdminFirstName').val().trim(),
            last_name: $('#jsPayrollAdminLastName').val().trim(),
            email: $('#jsPayrollAdminEmail').val().trim()
        };
        // Validation
        if (!obj.first_name) {
            return alertify.alert('Warning!', 'First name is required.', ECB);
        }
        if (!obj.last_name) {
            return alertify.alert('Warning!', 'Last name is required.', ECB);
        }
        if (!obj.email) {
            return alertify.alert('Warning!', 'Email address is required.', ECB);
        }
        //
        xhr = $.post(
                baseURI + 'payroll/' + (companyId) + '/admin',
                obj
            )
            .success(function() {
                //
                xhr = null;
                //
                return alertify.alert(
                    'Success!',
                    'A new admin is successfully added.',
                    function() {
                        //
                        $(modalId).find('.jsModalCancel').click();
                        //
                        window.location.reload();
                    }
                );
            })
            .fail(ErrorHandler);
        //
        ml(true, modalLoader);
    });

    /**
     * Fetched employees that are not on payroll
     */
    function getEmployeesForOnboard() {
        //
        if (employeeList.length) {
            return LoadAdmins(employeeList);
        }
        //
        xhr = $.get(
                baseURI + 'payroll/get/' + (companyId) + '/employees'
            )
            .done(function(response) {
                //
                xhr = null;
                //
                LoadAdmins(response)
            })
            .error(ErrorHandler);
    }

    /**
     * Generates the view
     * @param {array} employees 
     * @returns 
     */
    function LoadAdmins(employees) {
        //
        if (!employees.length) {
            employeeList = [];
            return;
        }
        //
        employeeList = employees;
        //
        var rows = '';
        //
        rows += '<div class="container">';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Select Employees</label>';
        rows += '          <select id="jsPayrollAdminEmployees">';
        rows += '               <option value="0">[Select Employee]</option>';
        employeeList.map(function(emp) {
            rows += '           <option value="' + (emp.user_id) + '">' + (emp.full_name_with_role) + '</option>';
        });
        rows += '          </select>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <div class="col-sm-12">';
        rows += '      <hr />';
        rows += '          <label>First Name <span class="csRequired"></span></label>';
        rows += '          <input type="text" required placeholder="John" class="form-control" id="jsPayrollAdminFirstName" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Last Name <span class="csRequired"></span></label>';
        rows += '          <input type="text" required placeholder="Doe" class="form-control" id="jsPayrollAdminLastName" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12">';
        rows += '          <label>Email Address <span class="csRequired"></span></label>';
        rows += '          <input type="email" required placeholder="john.doe@example.com" class="form-control" id="jsPayrollAdminEmail" />';
        rows += '      </div>';
        rows += '   </div>';
        rows += '   <div class="row">';
        rows += '      <br />';
        rows += '      <div class="col-sm-12 text-right">';
        rows += '          <button class="btn btn-orange jsPayrollSaveAdmin"><i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save</button>';
        rows += '          <button class="btn btn-black jsModalCancel "><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel</button>';
        rows += '      </div>';
        rows += '   </div>';
        rows += '</div>';
        //
        $('#' + (modalId) + 'Body').html(rows);
        //
        $('#jsPayrollAdminEmployees').select2();
        //
        ml(false, modalLoader);
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
            "The system failed to process your request. (" + (error.status) + ")",
            ECB
        );
    }

    /**
     * Alertify callback error
     * @returns
     */
    function ECB() {}
});