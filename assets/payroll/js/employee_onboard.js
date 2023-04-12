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
     * Holds state name
     * @type string
     */
    var selectedStateName = '';

    /**
     * Holds bank id
     * @type number
     */
    var selectedBankId = 0;

    /**
     * Holds employee onboard status
     * @type number
     */
    var onboardStatus = 0;

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
     * Trigger when cancel is pressed
     */
    $(document).on("change", ".jsPaymentMethod", function() {
        //
        var type = $(this).val();
        //
        if (type == "Check") {
            $(".jsBaseOnC").show();
            $(".jsBaseOnDD").hide();
        }

        if (type == "Direct Deposit") {
            $(".jsBaseOnC").hide();
            $(".jsBaseOnDD").show();
        }
    });

    /**
     * 
     */
    $(document).on('click', '.jsNavBarAction', function(event) {
        //
        event.preventDefault();
        //
        if (onboardStatus == 0) {
            return false;
        }
        //
        var slug = $(this).data('id');
        //
        var o = {
            employee_profile: AddUpdateCompanyEmployeeProfile,
            employee_compensation: UpdateCompanyEmployeeCompensation,
            employee_address: UpdateCompanyEmployeeAddress,
            employee_federal_tax: UpdateEmployeeFederalTax,
            employee_state_tax: UpdateEmployeeStateTax,
            employee_payment: UpdateEmployeePaymentMethod
        };
        //
        o[slug]();
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
            if (typeof employee !== 'object') { continue; }
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
            trs += '    <td class="text-center vam">';
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
            if (typeof employee !== 'object') { continue; }
            //
            html += '<div class="row" id="jsPayrollEmployeeRow' + (employee['user_id']) + '">';
            html += '   <div class="col-xs-12 col-md-12">';
            html += '       <label class="control control--checkbox">';
            html += '           <input type="checkbox" ' + (employee.missing_fields.length ? 'disabled' : '') + ' name="' + (employee.missing_fields.length ? 'disabled' : 'jsEmployeesList[]') + '" class="jsEmployeesList" value="' + (employee['user_id']) + '" />';
            html += employee.full_name_with_role;
            if (employee.missing_fields.length) {
                html += ' --- <span class="text-danger">Missing Fields [' + (employee.missing_fields.join(', ')) + ']</span>';
            }
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
            //
            ml(false, modalLoader);
            // Show success message
            return alertify.alert('Success', "The process has been completed.", function() {});
        }
        //
        ml(true, modalLoader);
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
        }, CheckOnboardStatus);
    }

    /**
     * 
     */
    function CheckOnboardStatus() {
        //
        onboardStatus = 0;
        //
        xhr = $.get(
                baseURI + 'payroll/onboard_status/' + companyId + '/' + selectedEmployeeId
            )
            .done(function(resp) {
                //
                if (resp.response.details.personal_profile == 1) {
                    onboardStatus = 1;
                }
            })
            .error(ErrorHandler);
        //
        AddUpdateCompanyEmployeeProfile();
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
                    $(".jsPayrollSaveCompanyEmployee").click(SaveCompanyEmployeeProfile);
                    //
                    $(".jsDOBPicker").datepicker({
                        format: "m/d/Y",
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-80:+0",
                    });
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
     * Save company Employee
     * @param {object} event
     * @returns
     */
    function SaveCompanyEmployeeProfile(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.FirstName = $(".jsFirstName").val().trim();
        o.MiddleInitial = $(".jsMiddleName").val().trim();
        o.LastName = $(".jsLastName").val().trim();
        o.StartDate = $(".jsStartDate").val() || '';
        o.SSN = $(".jsEmployeeSSN").val().replace(/[^\d]/g, "");
        o.DOB = $(".jsEDOB").val() || '';
        o.EWA = $(".jsEWD option:selected").val() || 0;
        o.Email = $(".jsEmail").val().trim();

        // Validation
        if (!o.FirstName) {
            return alertify.alert(
                "Warning!",
                "First name is mandatory.",
                ECB
            );
        }
        if (!o.LastName) {
            return alertify.alert(
                "Warning!",
                "Last name is mandatory.",
                ECB
            );
        }
        if (!o.Email) {
            return alertify.alert(
                "Warning!",
                "Email address is mandatory.",
                ECB
            );
        }
        if (!o.SSN) {
            return alertify.alert("Warning!", "SSN is mandatory.", ECB);
        }
        if (o.SSN.length != 9) {
            return alertify.alert(
                "Warning!",
                "SSN number must be of 9 digits long.",
                ECB
            );
        }
        if (!o.DOB) {
            return alertify.alert(
                "Warning!",
                "Date of birth is mandatory.",
                ECB
            );
        }

        o.employeeId = selectedEmployeeId;

        ml(true, 'jsEmployeeOnboardModelLoader');

        xhr = $.ajax({
                method: "POST",
                url: baseURI + 'payroll/onboard_employee/profile/' + companyId,
                data: o,
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, 'jsEmployeeOnboardModelLoader');
                //
                if (!resp.status) {
                    return alertify.alert(
                        "Error!",
                        typeof resp.errors === "object" ?
                        resp.errors.join("<br/>") :
                        resp.errors,
                        ECB
                    );
                }
                //
                return alertify.alert(
                    "Success!",
                    resp.response,
                    function() {
                        if (resp.move) {
                            UpdateCompanyEmployeeCompensation();
                            return true;
                        }
                    }
                );
            })
            .error(ErrorHandler);
    }

    /**
     * Save company Employee job
     * @param {object} event
     * @returns
     */
    function SaveCompanyEmployeeJob(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Title = $(".jsJobTitle").val().trim();
        o.Rate = $(".jsAmount").val().trim().replace(/[^0-9.]/g, '');
        o.FlsaStatus = $(".jsEmployeeType option:selected").val();
        o.PaymentUnit = $(".jsSalaryType option:selected").val();
        o.employeeId = selectedEmployeeId;
        // Validation
        if (!o.Title) {
            return alertify.alert(
                "Warning!",
                "Job title is mandatory.",
                ECB
            );
        }
        if (!o.FlsaStatus || o.FlsaStatus == 0) {
            return alertify.alert(
                "Warning!",
                "Employee type is mandatory.",
                ECB
            );
        }
        if (!o.Rate) {
            return alertify.alert(
                "Warning!",
                "Salary amount is mandatory.",
                ECB
            );
        }
        if (!o.PaymentUnit || o.PaymentUnit == 0) {
            return alertify.alert(
                "Warning!",
                "Salary type is mandatory.",
                ECB
            );
        }
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "POST",
                url: baseURI + 'payroll/onboard_employee/compensation/' + companyId,
                data: o
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, 'jsEmployeeOnboardModelLoader');
                //
                if (!resp.status) {
                    return alertify.alert(
                        "Error!",
                        typeof resp.errors === "object" ?
                        resp.errors.join("<br/>") :
                        resp.errors,
                        ECB
                    );
                }
                //
                return alertify.alert(
                    "Success!",
                    resp.response,
                    UpdateCompanyEmployeeAddress
                );
            })
            .error(ErrorHandler);
        //
    }

    /**
     * Save Employee home address
     * @param {object} event
     * @returns
     */
    function SaveCompanyEmployeeAddress(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Street1 = $(".jsStreet1").val().trim();
        o.Street2 = $(".jsStreet2").val().trim();
        o.Country = "USA";
        o.City = $(".jsCity").val().trim();
        o.State = $(".jsState option:selected").val();
        o.Zipcode = $(".jsZip").val().trim();
        o.PhoneNumber = $(".jsPhoneNumber").val().replace(/[^\d]/g, "");
        o.employeeId = selectedEmployeeId;
        // Validation
        if (!o.Street1) {
            return alertify.alert(
                "Warning!",
                "Street 1 is mandatory.",
                ECB
            );
        }
        if (!o.City) {
            return alertify.alert("Warning!", "City is mandatory.", ECB);
        }
        if (!o.State) {
            return alertify.alert("Warning!", "State is mandatory.", ECB);
        }
        if (!o.Zipcode) {
            return alertify.alert("Warning!", "Zip is mandatory.", ECB);
        }
        if (o.Zipcode.length != 5) {
            return alertify.alert("Warning!", "Zip must be 5 characters long.", ECB);
        }

        if (o.PhoneNumber && o.PhoneNumber.length != 10) {
            return alertify.alert(
                "Warning!",
                "Phone number must be of 10 digits long.",
                ECB
            );
        }
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "POST",
                url: baseURI + 'payroll/onboard_employee/home_address/' + companyId,
                data: o
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, 'jsEmployeeOnboardModelLoader');
                //
                if (!resp.status) {
                    return alertify.alert(
                        "Error!",
                        typeof resp.errors === "object" ?
                        resp.errors.join("<br/>") :
                        resp.errors,
                        ECB
                    );
                }
                //
                return alertify.alert(
                    "Success!",
                    resp.response,
                    UpdateEmployeeFederalTax
                );
            })
            .error(ErrorHandler);
        //
    }

    /**
     * Save company Employee Federal tax
     * @param {object} event
     * @returns
     */
    function SaveCompanyEmployeeFederalTax(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.FederalFilingStatus = $(".jsFederalFilingStatus option:selected").val();
        o.MultipleJobs = $(".jsMultipleJobs option:selected").val();
        o.DependentTotal = $(".jsDependentTotal").val();
        o.OtherIncome = $(".jsOtherIncome").val();
        o.Deductions = $(".jsDeductions").val();
        o.ExtraWithholding = $(".jsExtraWithholding").val();
        o.employeeId = selectedEmployeeId;
        // Validation
        if (!o.FederalFilingStatus || o.FederalFilingStatus == 0) {
            return alertify.alert(
                "Warning!",
                "Federal Filing Status is mandatory.",
                ECB
            );
        }
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "POST",
                url: baseURI + 'payroll/onboard_employee/federal_tax/' + companyId,
                data: o,
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, 'jsEmployeeOnboardModelLoader');
                //
                if (!resp.status) {
                    return alertify.alert(
                        "Error!",
                        typeof resp.errors === "object" ?
                        resp.errors.join("<br/>") :
                        resp.errors,
                        ECB
                    );
                }

                return alertify.alert(
                    "Success!",
                    resp.response,
                    UpdateEmployeeStateTax
                );
            })
            .error(HandleError);
        //
    }

    /**
     * Save company Employee Federal tax
     * @param {object} event
     * @returns
     */
    function SaveCompanyEmployeeStateTax(event) {
        //
        event.preventDefault();
        //
        var o = {};
        //
        o.employeeId = selectedEmployeeId;
        //
        o.state_name = selectedStateName;
        //
        $('.jsStateField').each(function() {
            var key = $(this).data('field_key');
            o[key] = key == 'withholding_allowance' ? parseInt($(".js" + key).val()) : $(".js" + key).val();
        });
        //
        for (key in o) {
            //
            if (!o[key]) {
                return alertify.alert('Error!', key.replace(/_/ig, ' ') + " is mandatory", ECB);
            }
            //
            if (key === 'withholding_allowance' && o[key] > 99) {
                return alertify.alert('Error!', "Withholding Allowance must be less than or equal to 99", ECB);
            }
        }
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "POST",
                url: baseURI + 'payroll/onboard_employee/state_tax/' + companyId,
                data: o,
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, 'jsEmployeeOnboardModelLoader');
                //
                if (!resp.status) {
                    return alertify.alert(
                        "Error!",
                        typeof resp.errors === "object" ?
                        resp.errors.join("<br/>") :
                        resp.errors,
                        ECB
                    );
                }
                //
                return alertify.alert(
                    "Success!",
                    resp.response,
                    UpdateEmployeePaymentMethod
                );
            })
            .error(ErrorHandler);
    }

    /**
     * Save company Employee Bank Detail
     * @param {object} event
     * @returns
     */
    function SaveEmployeeBankDetail(event) {
        //
        event.preventDefault();
        //
        var o = {};
        //
        o.RoutingNumber = $(".jsRoutingNumber").val().replace(/[^\d]/g, "");
        o.AccountNumber = $(".jsAccountNumber").val().replace(/[^\d]/g, "");
        o.AccountType = $(".jsAccountType option:selected").val();
        o.BankName = $(".jsBankName").val().trim();
        o.employeeId = selectedEmployeeId;
        o.bankId = selectedBankId;
        // Validation
        if (!o.BankName) {
            return alertify.alert(
                "Warning!",
                "Bank name is mandatory.",
                ECB
            );
        }
        if (!o.RoutingNumber) {
            return alertify.alert(
                "Warning!",
                "Routing number is mandatory.",
                ECB
            );
        }
        if (o.RoutingNumber.length !== 9) {
            return alertify.alert(
                "Warning!",
                "Routing number must be of 9 digits.",
                ECB
            );
        }
        if (!o.AccountNumber) {
            return alertify.alert(
                "Warning!",
                "Account number is mandatory.",
                ECB
            );
        }
        if (o.AccountNumber.length !== 9) {
            return alertify.alert(
                "Warning!",
                "Account number must be of 9 digits.",
                ECB
            );
        }
        if (!o.AccountType || o.AccountType == '0') {
            return alertify.alert(
                "Warning!",
                "Please, select the account type.",
                ECB
            );
        }
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "POST",
                url: baseURI + 'payroll/onboard_employee/bank_account/' + companyId,
                data: o,
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, 'jsEmployeeOnboardModelLoader');
                //
                if (!resp.status) {
                    return alertify.alert(
                        "Error!",
                        typeof resp.errors === "object" ?
                        resp.errors.join("<br/>") :
                        resp.errors,
                        ECB
                    );
                }

                return alertify.alert(
                    "Success!",
                    resp.response,
                    UpdateEmployeePaymentMethod
                );
            })
            .error(ErrorHandler);
        //
    }

    /**
     * Save company Employee payment method
     * @param {object} event
     * @returns
     */
    function SaveCompanyEmployeePaymentMethod(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.PaymentMethod = $(".jsPaymentMethod option:selected").val();
        o.employeeId = selectedEmployeeId;
        // Validation
        if (!o.PaymentMethod || o.PaymentMethod == 0) {
            return alertify.alert(
                "Warning!",
                "Please select a payment method.",
                ECB
            );
        }
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "POST",
                url: baseURI + 'payroll/onboard_employee/onboard/' + companyId,
                data: o,
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, 'jsEmployeeOnboardModelLoader');
                //
                if (!resp.status) {
                    return alertify.alert(
                        "Error!",
                        typeof resp.response === "object" ?
                        resp.response.join("<br/>") :
                        resp.response,
                        ECB
                    );
                }

                return alertify.alert(
                    "Success!",
                    resp.response,
                    () => {
                        $('.jsModalCancel').click();
                        window.location.reload();
                    }
                );
            })
            .error(HandleError);
        //
    }

    /**
     * Delete company Employee Bank Detail
     * @param {object} event
     * @returns
     */
    function DeleteEmployeeBankAccount(event) {
        //
        event.preventDefault();
        //
        var message = [];
        //
        var bankUID = $(this).data("uid");
        //
        message.push("Are you sure you want to delete this bank Account?");
        //
        alertify.confirm(
            "Confirm!",
            message.join("<br/>"),
            function() {
                ml(true, 'jsEmployeeOnboardModelLoader');
                //
                xhr = $.ajax({
                        method: "DELETE",
                        url: baseURI + 'payroll/onboard_employee/bank_account/' + companyId + '/' + (selectedEmployeeId) + '/' + bankUID
                    })
                    .done(function(resp) {
                        //
                        xhr = null;
                        //
                        ml(false, 'jsEmployeeOnboardModelLoader');
                        //
                        if (!resp.status) {
                            return alertify.alert(
                                "Error!",
                                typeof resp.errors === "object" ?
                                resp.errors.join("<br/>") :
                                resp.errors,
                                ECB
                            );
                        }
                        //
                        return alertify.alert(
                            "Success!",
                            resp.response,
                            UpdateEmployeePaymentMethod
                        );
                    })
                    .error(ErrorHandler);
            },
            function() {}
        );
    }

    /**
     * Get employee onboarding compensation
     */
    function UpdateCompanyEmployeeCompensation() {
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_compensation/" + companyId
                ),
                data: { employee_id: selectedEmployeeId },
            })
            .done(function(resp) {
                //
                xhr = null;
                employeeJobID = resp.JOB_ID;
                employeeHireDate = resp.JOB_HIRE_DATE;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(AddUpdateCompanyEmployeeProfile);
                    $(".jsPayrollSaveEmployeeJobInfo").click(SaveCompanyEmployeeJob);
                    //
                    ml(false, 'jsEmployeeOnboardModelLoader');
                });
            })
            .error(ErrorHandler);
    }

    /**
     * Get employee onboarding address
     */
    function UpdateCompanyEmployeeAddress() {
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/get_company_employee_address/" + companyId),
                data: { employee_id: selectedEmployeeId },
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(UpdateCompanyEmployeeCompensation);
                    $(".jsPayrollSaveEmployeeAddressInfo").click(
                        SaveCompanyEmployeeAddress
                    );
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
     * Get employee federal tax
     */
    function UpdateEmployeeFederalTax() {
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_federal_tax/" + companyId
                ),
                data: { employee_id: selectedEmployeeId },
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(UpdateCompanyEmployeeAddress);
                    $(".jsPayrollSaveEmployeeFederalTax").click(
                        SaveCompanyEmployeeFederalTax
                    );
                    //
                    ml(false, 'jsEmployeeOnboardModelLoader');
                });
            })
            .error(ErrorHandler);
    }

    /**
     * Get employee's state tax
     */
    function UpdateEmployeeStateTax() {
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_state_tax/" + companyId
                ),
                data: { employee_id: selectedEmployeeId },
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    LoadEmployeeStateTax();
                    //
                    $(".jsPayrollEmployeeOnboard").click(UpdateEmployeeFederalTax);
                    $(".jsPayrollSaveEmployeeStateTax").click(
                        SaveCompanyEmployeeStateTax
                    );
                });
            })
            .error(ErrorHandler);
    }

    /**
     *  Get employee's payment method
     */
    function UpdateEmployeePaymentMethod() {
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_payment_method/" + companyId
                ),
                data: { employee_id: selectedEmployeeId },
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(UpdateEmployeeStateTax);
                    $(".jsPayrollEmployeePaymentMethod").click(
                        SaveCompanyEmployeePaymentMethod
                    );
                    $(".jsAddEmployeeBankAccount").click(AddEmployeeBankAccount);
                    $(".jsDeleteEmployeeBankAccount").click(DeleteEmployeeBankAccount);
                    //
                    ml(false, 'jsEmployeeOnboardModelLoader');
                });
            })
            .error(ErrorHandler);
    }

    /**
     * 
     */
    function LoadEmployeeStateTax() {
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        xhr = $.get(
                baseURI + 'payroll/onboard_employee/state_tax/' + companyId + '/' + selectedEmployeeId
            )
            .done(function(resp) {
                //
                xhr = null;
                //
                $("#JSStateName").text(resp.response.state_name);
                //
                selectedStateName = resp.response.state_name;
                //
                var html = "";
                //
                resp.response.state_json.questions.map(function(q) {
                    //
                    var value = 0;
                    //
                    if (q.answers.length) {
                        value = q.answers[0]['value'];
                    }
                    //
                    html += '<div class="row">';
                    html += '    <div class="col-md-12 col-xs-12">';
                    html += '        <label class="csF16 csB7">';
                    html += q.label
                    html += '        </label>';
                    html += '        <p class="csF16">';
                    html += q.description;
                    html += '        </p>';
                    //
                    if (q.input_question_format.type == "Select") {
                        html += '<select class="form-control jsStateField js' + q.key + '" data-field_key="' + q.key + '">';
                        q.input_question_format.options.map(function(op) {
                            html += '<option value="' + op.value + '" ' + (value == op.value ? "selected" : "") + '>' + op.label + '</option>';
                        })
                        html += '</select>';
                    } else {
                        html += '<input type="' + q.input_question_format.type + '" min="0" class="form-control jsStateField js' + q.key + '" data-field_key="' + q.key + '" value="' + (value) + '">';
                    }

                    html += '    </div>';
                    html += '</div>';
                    html += '</br>';
                });
                //
                $("#JSQusetionSection").html(html);
                //
                ml(false, 'jsEmployeeOnboardModelLoader');
            })
            .error(ErrorHandler);
    }

    /**
     * 
     */
    function AddEmployeeBankAccount() {
        //
        ml(true, 'jsEmployeeOnboardModelLoader');
        //
        selectedBankId = $(this).data("account_id");
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_bank_detail/" + companyId
                ),
                data: { employee_id: selectedEmployeeId, row_id: selectedBankId },
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsBackToPaymentMethod").click(UpdateEmployeePaymentMethod);
                    $(".jsSaveEmployeeBankInfo").click(SaveEmployeeBankDetail);
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

    /**
     * Alertify callback error
     * @returns
     */
    function ECB() {}

    // Quickly load all the employees
    LoadPayrollEmployees();
});