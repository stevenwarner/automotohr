/**
 * Process company onboard for payroll
 * 
 * @package Company Payroll Onboarding
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
$(function PayrollCompanyOnboard() {
    /**
     * Set default company Id
     * @type {number}
     */
    var companyId;

    /**
     * Set modal id
     * @type {string}
     */
    var modalId = 'jsPayrollCompanyOnboardModal';

    /**
     * Set modal loader key
     * @type {string}
     */
    var modalLoader = modalId + 'Loader';

    /**
     * Saves the XHR (AJAX) object
     * @type {null|object}
     */
    var xhr = null;

    /**
     * Saves reference of function
     * @type {Array}
     */
    var payrollEvents = [AddCompanyEmployees, FinishCompanyOnboarding];
    var payrollEventsMessages = [
        "Please wait while we set up the payroll.",
        "Please wait while we are adding company address.",
        "Please wait while we are adding company federal tax.",
        "Please wait while we are adding company Bank detail.",
        "Please wait while we add the selected employees to payroll..",
        "Please wait we are adding employee one by one."
    ];

    /**
     * Saves the Current employee;
     * @type {null|string}
     */
    var CURRENT_EMPLOYEE;

    /**
     * Saves the total selected employee;
     * @type {null|string}
     */
    var SELECTED_EMPLOYEE;

    /**
     * Triggers company onboard process
     */
    $('.jsPayrollCompanyOnboard, .jsAddCompanyToGusto').click(function (event) {
        //
        event.preventDefault();
        //
        companyId = $(this).data('cid') || 0;
        if ($(this).data('company_sid')) {
            companyId = $(this).data('company_sid');
        }
        //
        if (companyId == 0) {
            return alertify.alert(
                "Warning!",
                "System failed to verify company reference."
            );
        }
        //
        StartOnboardProcess();
    });

    /**
     * Trigger when cancel is pressed
     */
    $(document).on('click', '.jsPayrollCancel', function (event) {
        //
        event.preventDefault();
        //
        return alertify.confirm('Any unsaved changes to this content will be lost. Are you sure you want to close this page?', function () {
            //
            xhr = null;
            //
            $('#' + modalId).find('.jsModalCancel').click();

        }).setHeader('Confirm!');
    });

    $('.jsSyncWithGusto').click(function (event) {
        var company_sid = $(this).data("company_sid");
        //
        companyId = company_sid;
        SyncCompanyOnboarding();
    });

    /**
     * Trigger when cancel is pressed
     */
    $(document).on('click', '.jsPayrollConfirmContinue', FinishCompanyOnboarding);


    /**
     * Loads the onboard view
     * includes
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
            Container: 'container',
            CancelClass: 'btn-cancel csW'
        }, WelcomeJourney);
    }


    /**
     * Start the payroll process
     * includes
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
            url: GetURL('get_gusto_onboarding_page/welcome'),
        })
            .done(function (resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function () {
                    //
                    $('.jsPayrollLoadSelectEmployees').click(EmployeeSelectPage);
                    //
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Loads Employee listing page
     * includes
     * 
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
            url: GetURL('get_payroll_employees/' + companyId + '/payroll_onboarding'),
        })
            .done(function (resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function () {
                    //
                    $('.jsPayrollBackToWelcome').click(WelcomeJourney);
                    //
                    $('.jsPayrollLoadOnboaard').click(OnboardPage);
                    //
                    var preSelected = GetItem('PayrollEmployees' + companyId);
                    //
                    if (preSelected !== null && preSelected.length > 0) {
                        //
                        for (var index in preSelected) {
                            $('.jsPayrollEmployees[value="' + (preSelected[index]) + '"]').prop('checked', true);
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
     * includes
     * @param {object} event 
     */
    function OnboardPage(event) {
        //
        if (event) {
            event.preventDefault();
            //
            var ids = [];
            //
            if ($('.jsEmployeesList:checked').length) {
                $('.jsEmployeesList:checked').map(function () { ids.push($(this).val()); });
            }
            console.log(ids);
            //
            SaveItem('PayrollEmployees' + companyId, ids);
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
            url: GetURL('get_gusto_onboarding_page/onboard/' + companyId),
        })
            .done(function (resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function () {
                    //
                    if ($('.jsPayrollMoveCompanyToPayroll').length) {
                        $('.jsPayrollMoveCompanyToPayroll').click(InitialOnboard);
                    }
                    //
                    if ($('.jsPayrollSetAdmin').length) {
                        $('.jsPayrollSetAdmin').click(PayrollAdminPage);
                    }
                    if ($('.jsPayrollViewAdmin').length) {
                        $('.jsPayrollViewAdmin').click(PayrollAdminViewPage);
                    }
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Load primary admin page
     * @param {object} event 
     */
    function PayrollAdminPage(event) {
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
            url: GetURL('get_gusto_onboarding_page/admin/' + companyId),
        })
            .done(function (resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function () {
                    //
                    $('.jsPayrollSaveAdmin').click(SavePayrollAdmin);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Create a new admin
     * @param {object} event 
     * @returns 
     */
    function SavePayrollAdmin(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.first_name = $('.jsAdminFirstName').val().replace(/[^a-zA-Z]/g, '');
        o.last_name = $('.jsAdminLastName').val().replace(/[^a-zA-Z]/g, '');
        o.email_address = $('.jsAdminEmailAddress').val().trim();
        o.phone_number = $('.jsAdminPhoneNumber').val().replace(/[^\d]/g, '');
        // Validate incoming data
        if (o.first_name.length === 0) {
            return alertify.alert("Warning!", "First name is mandatory.", AlertifyHandler);
        }
        //
        if (o.last_name.length === 0) {
            return alertify.alert("Warning!", "Last name is mandatory.", AlertifyHandler);
        }
        //
        if (o.email_address.length === 0) {
            return alertify.alert("Warning!", "Email is mandatory.", AlertifyHandler);
        }
        //
        if (!validateEmail(o.email_address)) {
            return alertify.alert("Warning!", "Email is not valid.", AlertifyHandler);
        }
        //
        if (o.phone_number && o.phone_number.length !== 10) {
            return alertify.alert("Warning!", "Phone number must be 10 digits long.", AlertifyHandler);
        }
        //
        ml(true, modalLoader);
        //
        $.ajax({
            method: "POST",
            url: GetURL('save_payroll_admin/' + companyId),
            data: o
        })
            .done(function () {
                //
                return alertify.alert('Success', 'You have successfully added the payroll admin.', function () {
                    OnboardPage();
                });
            })
            .error(HandleError);
    }

    /**
     * Load primary admin page
     * @param {object} event 
     */
    function PayrollAdminViewPage(event) {
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
            url: GetURL('get_gusto_onboarding_page/admin_view/' + companyId),
        })
            .done(function (resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function () {
                    //
                    $('.jsPayrollBackToOnboard').click(OnboardPage);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Move company to payroll and 
     * save token to DB
     * includes
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
     * Move company to payroll and 
     * save token to DB
     * @param {object} event
     */
    function AddNewCompany() {
        //
        if (xhr !== null) {
            return;
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "post",
            url: GetURL('gusto/onboard_company/' + companyId),
            data: { companyId: companyId }
        })
            .done(function (resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
                // 
                if (resp.errors) {
                    return alertify.alert('Error!', ShowError(resp.errors));
                }
                //
                if (SELECTED_EMPLOYEE > 0) {
                    return moveEmployeeToGusto();
                }
                //
                ShowCompleteProcessPage();
            })
            .error(HandleError);
    }

    /**
     * includes
     */
    function AddCompanyEmployees() {
        //
        $("#jsIPLoaderTextArea").text(payrollEventsMessages[0]);
        //
        var preSelected = GetItem('PayrollEmployees' + companyId);
        //
        if (preSelected !== null && preSelected.length > 0) {
            //
            CURRENT_EMPLOYEE = 0;
            SELECTED_EMPLOYEE = preSelected.length;
        }
        //
        AddNewCompany();
    }

    function moveEmployeeToGusto() {
        // 
        var preSelected = GetItem('PayrollEmployees' + companyId);
        //
        $("#jsIPLoaderTextArea").text("Please wait we are adding employee " + (CURRENT_EMPLOYEE + 1) + " out of " + (preSelected.length) + "");
        //
        xhr = $.ajax({
            method: "POST",
            url: GetURL('gusto/onboard_employee/' + companyId),
            data: { employee_id: preSelected[CURRENT_EMPLOYEE] }
        })
            .done(function () {
                xhr = null;
                if (CURRENT_EMPLOYEE < (preSelected.length - 1)) {
                    CURRENT_EMPLOYEE++;
                    moveEmployeeToGusto();
                } else {
                    ShowCompleteProcessPage();
                }
            });
    }

    function FinishCompanyOnboarding() {
        $('#' + modalId).hide();
        alertify.alert('Success!', 'Payroll is activated against this store.', function () {
            location.reload();
        });
    }

    function ShowCompleteProcessPage() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
            method: "GET",
            url: GetURL('get_gusto_onboarding_page/get_prosess_complete_page/' + companyId),
        })
            .done(function (resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function () {
                    //
                    ml(false, modalLoader);
                    //
                });
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
        return window.location.origin + '/' + (url || '');
    }

    /**
     * Loads page onto the modal
     * @param {string}   content 
     * @param {function} cb 
     */
    function LoadContent(content, cb) {
        //
        $('#' + (modalId) + 'Body').html(content);
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
            "The system failed to process your request. (" + (error.status) + ")"
        );
    }

    /**
     * validate email
     * @param {string} email 
     * @returns 
     */
    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
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
    function AlertifyHandler() {
        //
        return true;
    }

    /**
     * Handle payroll errors
     * @param {*} errors 
     * @returns 
     */
    function ShowError(errors) {
        //
        if (typeof errors !== undefined) {
            //
            if (typeof errors[0] === 'object') {
                return errors[0]['full_message'];
            }
            //
            return errors.join('<br/>');
        }
        return errors;
    }
});