/**
 * Process employee onboard for payroll
 *
 * @package Employee Payroll Onboarding
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
$(function EmployeeOnboard() {
    /**
     * Set default company Id
     * @type {number}
     */
    var companyId = $("#jsPayrollEmployeesTable").data("company_sid");

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
     * Saves the XHR (AJAX) object
     * @type {null|object}
     */
    var xhr = null;

    /**
     * Saves the application key object
     * @type {null|string}
     */
    var API_KEY;

    /**
     * Saves the application key object
     * @type {null|string}
     */
    var API_URL;

    /**
     * Saves the application page location
     * @type {null|int}
     */
    var locationSid;

    /**
     * Saves the employee Id
     * @type {null|int}
     */
    var employeeId;

    /**
     * Saves the employee bank row ID
     * @type {null|int}
     */
    var addBankID;

    /**
     * Saves the split type
     * @type {null|int}
     */
    var splitType;

    /**
     * Saves the employee job sid
     * @type {null|int}
     */
    var employeeJobID;

    /**
     * Saves the employee Hire Date
     * @type {null|string}
     */
    var employeeHireDate;

    /**
     * Saves reference of function
     * @type {Array}
     */
    var payrollEvents = [StartEmployeeOnboarding];
    var payrollEventsMessages = [
        "Please wait while we add the selected employees to payroll.."
    ];

    //
    $(document).on('click', '.jsPayrollDeleteEmployee', function(event) {
        //
        event.preventDefault();
        //
        var employeeId = $(this).closest('.jsPayrollEmployeeRow').data('id');
        //
        //
        alertify.confirm(
            'Are you sure you want to delete the selected employee from payroll?',
            function() {
                DeleteEmployeeFromPayroll(employeeId);
            }
        ).setHeader('Confirm');
    });

    /**
     * Trigger when cancel is pressed
     */
    $(document).on("change", ".jsPaymentMethod", function(event) {
        //
        var type = $(this).val();
        //
        if (type == "Check") {
            $(".jsBaseOnDD").hide();
        }

        if (type == "Direct Deposit") {
            $(".jsBaseOnDD").show();
        }
    });
    /**
     * Trigger when side bar event click
     */
    $(document).on("click", ".jsNavBarAction", function(event) {
        //
        event.preventDefault();
        //
        xhr = null;
        var type = $(this).data("id");

        if (type == "employee") {
            StartEmployeeOnboarding();
        }

        if (type == "employee_profile") {
            if (employeeID) {
                GoToEmployeeSection(0);
            } else {
                return alertify.alert(
                    "Note!",
                    "Please select any employee first.",
                    ECB
                );
            }
        }

        if (type == "employee_address") {
            if (employeeID) {
                GoToEmployeeSection(1);
            } else {
                return alertify.alert(
                    "Note!",
                    "Please select any employee first.",
                    ECB
                );
            }
        }

        if (type == "employee_compensation") {
            if (employeeID) {
                GoToEmployeeSection(2);
            } else {
                return alertify.alert(
                    "Note!",
                    "Please select any employee first.",
                    ECB
                );
            }
        }

        if (type == "employee_federal_tax") {
            if (employeeID) {
                GoToEmployeeSection(3);
            } else {
                return alertify.alert(
                    "Note!",
                    "Please select any employee first.",
                    ECB
                );
            }
        }

        if (type == "employee_state_tax") {
            if (employeeID) {
                GoToEmployeeSection(4);
            } else {
                return alertify.alert(
                    "Note!",
                    "Please select any employee first.",
                    ECB
                );
            }
        }

        if (type == "employee_payment") {
            if (employeeID) {
                GoToEmployeeSection(5);
            } else {
                return alertify.alert(
                    "Note!",
                    "Please select any employee first.",
                    ECB
                );
            }
        }
    });
    /**
     * 
     */
    $(".jsSyncWithGusto").click(function(event) {
        var company_sid = $(this).data("company_sid");
        //
        companyId = company_sid;
        SyncCompanyOnboarding();
    });
    /**
     * 
     */
    $(document).on("click", ".jsPayrollAddProcess", function(event) {
        //
        event.preventDefault();
        //
        var employee_sid = $(this)
            .closest(".jsPayrollEmployeeRow")
            .data("id");
        employeeID = employee_sid;
        employeeId = employee_sid;
        //
        StartOnboardProcess();
    });

    /**
     * Loads the onboard view
     * 
     * @method Model
     */
    function StartOnboardProcess() {
        //
        xhr = null;
        //
        Model({
            Id: modalId,
            Title: '<span class="' + modalId + 'Title"></span>',
            Body: '<div id="' + modalId + 'Body"></div>',
            Loader: modalLoader,
            Container: 'container-fluid',
            CancelClass: 'btn-cancel csW'
        }, AddUpdateCompanyEmployeeProfile);
    }

    /**
     * Start the payroll process
     * @method GetURL
     */
    function WelcomeJourney() {
        // Hold until the old AJAX is completed
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/welcome"),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    $(".jsPayrollLoadSelectEmployees").click(EmployeeSelectPage);
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Loads Employee listing page
     * @param {object} event
     */
    function EmployeeSelectPage(event) {
        //
        event.preventDefault();
        // Hold until the old AJAX is completed
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/employees/" + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    $(".jsPayrollBackToWelcome").click(WelcomeJourney);
                    //
                    $(".jsPayrollLoadOnboaard").click(OnboardPage);
                    //
                    var preSelected = GetItem("PayrollEmployees" + companyId);
                    //
                    if (preSelected !== null && preSelected.length > 0) {
                        //
                        for (var index in preSelected) {
                            $('.jsPayrollEmployees[value="' + preSelected[index] + '"]').prop(
                                "checked",
                                true
                            );
                        }
                    }
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Loads Employee listing page
     * @param {object} event
     */
    function OnboardPage(event) {
        //
        if (event) {
            event.preventDefault();
            //
            var ids = [];
            //
            if ($(".jsPayrollEmployees:checked").length) {
                $(".jsPayrollEmployees:checked").map(function() {
                    ids.push($(this).val());
                });
            }
            //
            SaveItem("PayrollEmployees" + companyId, ids);
        }
        // Hold until the old AJAX is completed
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/onboard/" + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    if ($(".jsPayrollMoveCompanyToPayroll").length) {
                        $(".jsPayrollMoveCompanyToPayroll").click(InitialOnboard);
                    }
                    //
                    if ($(".jsPayrollSetAdmin").length) {
                        $(".jsPayrollSetAdmin").click(PayrollAdminPage);
                    }
                    if ($(".jsPayrollViewAdmin").length) {
                        $(".jsPayrollViewAdmin").click(PayrollAdminViewPage);
                    }
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Move company to payroll and
     * save token to DB
     * @param {object} event
     */
    function InitialOnboard(event) {
        //
        event.preventDefault();
        //
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        $("#jsIPLoaderTextArea").text(payrollEventsMessages[0]);
        //
        payrollEvents[0]();
    }

    /**
     * Start employee onboarding
     */
    function StartEmployeeOnboarding() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/start_employee_onboarding/" + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsAddCompanyEmployee").click(ShowCompanyEmployeeList);
                    $(".jsPayrollEmployeeOnboard").click(SendEmployeeToOnboardProcess);

                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Start employee onboarding
     */
    function ShowCompanyEmployeeList() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/show_company_employee_list/" + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(SendEmployeeToOnboardProcess);
                    $(".jsEmployeeListCancel").click(StartEmployeeOnboarding);
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Add employee onboarding
     */
    function SendEmployeeToOnboardProcess() {
        employeeID = $(this).data("employee_id");
        //
        var level = $(this).data("level");
        //
        GoToEmployeeSection(level);
    }

    function GoToEmployeeSection(level) {
        if (level == 0) {
            AddUpdateCompanyEmployeeProfile();
        } else if (level == 1) {
            UpdateCompanyEmployeeAddress();
        } else if (level == 2) {
            UpdateCompanyEmployeeCompensation();
        } else if (level == 3) {
            UpdateEmployeeFederalTax();
        } else if (level == 4) {
            UpdateEmployeeStateTax();
        } else if (level == 5) {
            UpdateEmployeePaymentMethod();
        }
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
                data: { employee_id: employeeID },
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsAddEmployeeCancel").click(ShowCompanyEmployeeList);
                    $(".jsPayrollSaveCompanyEmployee").click(SaveCompanyEmployeeProfile);
                    //
                    $(".jsDatePicker").datepicker({
                        format: "m/d/Y",
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-100:+50",
                    });
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
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
        o.StartDate = $(".jsStartDate").val().trim();
        o.SSN = $(".jsEmployeeSSN").val().replace(/[^\d]/g, "");
        o.DOB = $(".jsEDOB").val().trim();
        o.EWA = $(".jsEWD option:selected").val();
        o.Email = $(".jsEmail").val().trim();
        o.CompanyId = companyId;
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
        // if (!o.StartDate) {
        //     return alertify.alert(
        //         "Warning!",
        //         "Start date is mandatory.",
        //         ECB
        //     );
        // }
        if (!o.SSN) {
            return alertify.alert("Warning!", "SSN is mandatory.", ECB);
        }
        if (o.SSN.length != 9) {
            return alertify.alert(
                "Warning!",
                "SSN number must be of 9 digits.",
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
        // if (!o.EWA) {
        //     return alertify.alert(
        //         "Warning!",
        //         "Employee work address is required.",
        //         ECB
        //     );
        // }

        ml(true, modalLoader);

        xhr = $.ajax({
                method: "POST",
                headers: { "Content-Type": "application/json", Key: API_KEY },
                url: API_URL + "/" + employeeID,
                data: JSON.stringify(o),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
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
                    UpdateCompanyEmployeeCompensation
                );
            })
            .error(HandleError);
    }

    /**
     * Get employee onboarding compensation
     */
    function UpdateCompanyEmployeeCompensation() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_compensation/" + companyId
                ),
                data: { employee_id: employeeID },
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                employeeJobID = resp.JOB_ID;
                employeeHireDate = resp.JOB_HIRE_DATE;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(SendEmployeeToOnboardProcess);
                    $(".jsPayrollSaveEmployeeJobInfo").click(SaveCompanyEmployeeJob);
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
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
        o.Rate = $(".jsAmount").val().trim();
        o.FlsaStatus = $(".jsEmployeeType option:selected").val();
        o.PaymentUnit = $(".jsSalaryType option:selected").val();
        o.EffectiveDate = employeeHireDate;
        o.CompanyId = companyId;
        // Validation
        if (!o.Title) {
            return alertify.alert(
                "Warning!",
                "Job title is mandatory.",
                ECB
            );
        }
        if (!o.FlsaStatus) {
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
        if (!o.PaymentUnit) {
            return alertify.alert(
                "Warning!",
                "Salary type is mandatory.",
                ECB
            );
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "POST",
                headers: { "Content-Type": "application/json", Key: API_KEY },
                // url: API_URL + "/" + employeeID + "/jobs",
                url: API_URL + "/" + employeeJobID,
                data: JSON.stringify(o),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
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
                    UpdateCompanyEmployeeAddress
                );
            })
            .error(HandleError);
        //
    }

    /**
     * Get employee onboarding address
     */
    function UpdateCompanyEmployeeAddress() {
        //
        ml(true, modalLoader);
        //
        console.log(employeeID);
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/get_company_employee_address/" + companyId),
                data: { employee_id: employeeID },
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(SendEmployeeToOnboardProcess);
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
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Save company Employee address
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
        o.CompanyId = companyId;
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

        if (o.PhoneNumber && o.PhoneNumber.length != 10) {
            return alertify.alert(
                "Warning!",
                "Phone number must be of 10 digits.",
                ECB
            );
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "POST",
                headers: { "Content-Type": "application/json", Key: API_KEY },
                url: API_URL + "/" + employeeID + "/home_address",
                data: JSON.stringify(o),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
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
                    UpdateEmployeeFederalTax
                );
            })
            .error(HandleError);
        //
    }



    function UpdateEmployeeFederalTax() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_federal_tax/" + companyId
                ),
                data: { employee_id: employeeID },
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(SendEmployeeToOnboardProcess);
                    $(".jsPayrollSaveEmployeeFederalTax").click(
                        SaveCompanyEmployeeFederalTax
                    );
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
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
        o.CompanyId = companyId;
        // Validation
        if (!o.FederalFilingStatus) {
            return alertify.alert(
                "Warning!",
                "Federal Filing Status is mandatory.",
                ECB
            );
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "POST",
                headers: { "Content-Type": "application/json", Key: API_KEY },
                url: API_URL + "/" + employeeID + "/federal_tax",
                data: JSON.stringify(o),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
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
                    UpdateEmployeeStateTax
                );
            })
            .error(HandleError);
        //
    }

    //
    var est;

    function UpdateEmployeeStateTax() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_state_tax/" + companyId
                ),
                data: { employee_id: employeeID },
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    xhr = $.ajax({
                            method: "GET",
                            headers: { "Content-Type": "application/json", Key: API_KEY },
                            url: API_URL + "/" + companyId + "/" + employeeID + "/state_tax",
                        })
                        .done(function(resp) {
                            //
                            est = resp;
                            //
                            xhr = null;
                            //
                            ml(false, modalLoader);
                            //
                            $("#JSStateName").text(resp.State_Name);
                            var html = "";
                            resp.questions.map(function(q) {
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
                                    html += '<input type="' + q.input_question_format.type + '" class="form-control jsStateField js' + q.key + '" data-field_key="' + q.key + '" value="' + (value) + '">';
                                }

                                html += '    </div>';
                                html += '</div>';
                                html += '</br>';
                            });
                            //
                            $("#JSQusetionSection").html(html);
                        })
                        .error(HandleError);
                    //
                    $(".jsPayrollEmployeeOnboard").click(SendEmployeeToOnboardProcess);
                    $(".jsPayrollSaveEmployeeStateTax").click(
                        SaveCompanyEmployeeStateTax
                    );
                    //
                });
            })
            .error(HandleError);
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
        o.state_name = est.State_Name;
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
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "POST",
                headers: { "Content-Type": "application/json", Key: API_KEY },
                url: API_URL + "/" + employeeID + "/state_tax",
                data: JSON.stringify(o),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
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
                    UpdateEmployeePaymentMethod
                );
            })
            .error(HandleError);
        //
    }

    function UpdateEmployeePaymentMethod() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_payment_method/" + companyId
                ),
                data: { employee_id: employeeID },
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsPayrollEmployeeOnboard").click(SendEmployeeToOnboardProcess);
                    $(".jsPayrollEmployeePaymentMethod").click(
                        SaveCompanyEmployeePaymentMethod
                    );
                    $(".jsAddEmployeeBankAccount").click(AddEmployeeBankAccount);
                    $(".jsDeleteEmployeeBankAccount").click(DeleteEmployeeoBankAccount);
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
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
        o.SplitType = $(".jsSplitType option:selected").val();
        o.CompanyId = companyId;
        // Validation
        if (!o.PaymentMethod || o.PaymentMethod == 0) {
            return alertify.alert(
                "Warning!",
                "Please select payment method.",
                ECB
            );
        }
        //
        if (o.PaymentMethod == "Direct Deposit") {
            if (!o.SplitType || o.SplitType == 0) {
                return alertify.alert(
                    "Warning!",
                    "Please select split type.",
                    ECB
                );
            }
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "POST",
                headers: { "Content-Type": "application/json", Key: API_KEY },
                url: API_URL + "/" + employeeID + "/payment_method",
                data: JSON.stringify(o),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
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
                        $('.jsModalCancel ').click();
                    }
                );
            })
            .error(HandleError);
        //
    }

    function AddEmployeeBankAccount() {
        //
        ml(true, modalLoader);
        //
        addBankID = $(this).data("account_id");
        splitType = $(".jsSplitType option:selected").val();
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL(
                    "get_payroll_page/get_company_employee_bank_detail/" + companyId
                ),
                data: { employee_id: employeeID, row_id: addBankID, type: splitType },
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
                //
                LoadContent(resp.html, function() {
                    //
                    $(".jsBackToPaymentMethod").click(UpdateEmployeePaymentMethod);
                    $(".jsSaveEmployeeBankInfo").click(SaveEmployeeBankDetail);
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
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
        o.RoutingNumber = $(".jsRoutingNumber").val().replace(/[^\d]/g, "");
        o.AccountNumber = $(".jsAccountNumber").val().replace(/[^\d]/g, "");
        o.AccountType = $(".jsAccountType option:selected").val();
        o.AccountName = $(".jsAccountName").val().trim();
        o.SplitType = splitType;
        o.SplitAmount = $(".jsSplitAmount").val().replace(/[^\d]/g, "");
        o.CompanyId = companyId;
        o.DDSID = addBankID;
        // Validation

        if (!o.AccountName) {
            return alertify.alert(
                "Warning!",
                "Account name is mandatory.",
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
        if (!o.AccountType) {
            return alertify.alert(
                "Warning!",
                "Please, select the account type.",
                ECB
            );
        }
        if (!o.SplitAmount) {
            return alertify.alert(
                "Warning!",
                "Split amount or percentage is mandatory.",
                ECB
            );
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "POST",
                headers: { "Content-Type": "application/json", Key: API_KEY },
                url: API_URL + "/" + employeeID + "/bank_accounts",
                data: JSON.stringify(o),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
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
                    UpdateEmployeePaymentMethod
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
    function DeleteEmployeeoBankAccount(event) {
        //
        event.preventDefault();
        //

        //
        var o = {};
        o.payroll_bank_uuid = $(this).data("account_id");
        o.DDID = $(this).data("ddid");
        o.CompanyId = companyId;
        var message = [];
        message.push("Are you sure you want to delete this bank Account?");
        console.log(o.DDID);
        if (o.DDID > 0) {
            message.push(
                "This action may also delete the direct deposit bank account."
            );
        }
        alertify.confirm(
            "Confirmation",
            message.join("<br/>"),
            function() {
                ml(true, modalLoader);
                //
                xhr = $.ajax({
                        method: "DELETE",
                        headers: { "Content-Type": "application/json", Key: API_KEY },
                        url: API_URL +
                            "/" +
                            employeeID +
                            "/bank_accounts/" +
                            o.payroll_bank_uuid,
                        data: JSON.stringify(o),
                    })
                    .done(function(resp) {
                        //
                        xhr = null;
                        //
                        ml(false, modalLoader);
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
                            UpdateEmployeePaymentMethod
                        );
                    })
                    .error(HandleError);
            },
            function() {}
        );
    }

    function SyncCompanyOnboarding() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                headers: { "Content-Type": "application/json", Key: API_KEY },
                url: API_URL + "/sync_company/" + companyId,
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
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
                //
                return alertify.alert("Success!", resp.response);
            })
            .error(HandleError);
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
        $("#" + modalId + "Body").html(content);
        //
        !cb ? ml(false, modalLoader) : cb();
    }

    /**
     * Handles XHR errors
     * @param {object} error
     */
    function HandleError(error) {
        //
        xhr = null;
        //
        alertify.alert(
            "Error!",
            "The system failed to process your request. (" + error.status + ")"
        );
    }

    /**
     * validate email
     * @param {string} email
     * @returns
     */
    function validateEmail(email) {
        const re =
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    /**
     * Saves data to local storage
     * @param {string} slug
     * @param {any}    data
     */
    function SaveItem(slug, data) {
        //
        localStorage.setItem(slug, JSON.stringify(data));
        //
        return GetItem(slug);
    }

    /**
     * Gets data from local storage
     * @param {string} slug
     */
    function GetItem(slug) {
        //
        return JSON.parse(localStorage.getItem(slug));
    }

    /**
     * Alertify callback error
     * @returns
     */
    function ECB() {}

    //
    function GetAPICreds() {
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL("get_payroll_page/get-api-creds")
            })
            .done(function(resp) {
                //
                xhr = null;
                API_KEY = resp.API_KEY;
                API_URL = resp.EMPLOYEE_URL;
            })
            .error(HandleError);
    }

    //
    function DeleteEmployeeFromPayroll(employeeId) {
        ml(true, modalLoader);
        console.log(API_URL, API_KEY)
            //
        xhr = $.ajax({
                url: API_URL + '/' + employeeId,
                method: "DELETE",
                headers: { "Content-Type": "application/json", Key: API_KEY },
            })
            .success(function(resp) {
                console.log(resp)
            })
            .fail(HandleError);
    }

    //
    GetAPICreds();
});