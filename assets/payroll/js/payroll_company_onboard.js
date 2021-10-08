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
    var companyId = 15598;

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
     * Triggers company onboard process
     */
    $('.jsPayrollCompanyOnboard').click(function(event) {
        //
        event.preventDefault();
        //
        companyId = $(this).data('cid') || 0;
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
    $(document).on('click', '.jsPayrollCancel', function(event) {
        //
        event.preventDefault();
        //
        return alertify.confirm('Any unsaved changes to this content will be lost. Are you sure you want to close this page?', function() {
            //
            xhr = null;
            //
            $('#' + modalId).find('.jsModalCancel').click();
        }).setHeader('Confirm!');
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
        }, CheckPayrollStatus);
    }

    /**
     * Check if payroll is already enabled
     * 
     * @method WelcomeJourney
     * @method CompanyDetailPage
     */
    function CheckPayrollStatus() {
        //
        $.ajax({
                method: "get",
                url: GetURL('get_payroll_page/status/' + companyId)
            })
            .done(function(resp) {
                //
                $('.' + (modalId) + 'Title').html(resp.name);
                //
                if (resp.payroll_enabled == '0') {
                    return WelcomeJourney();
                }
                //
                $('.' + (modalId) + 'Title').html(resp.name + ' - Company Details');
                CompanyDetailPage();
            })
            .error(HandleError);
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
                url: GetURL('get_payroll_page/welcome'),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
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
                url: GetURL('get_payroll_page/employees/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
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
     * @param {object} event 
     */
    function OnboardPage(event) {
        //
        if (event) {
            event.preventDefault();
            //
            var ids = [];
            //
            if ($('.jsPayrollEmployees:checked').length) {
                $('.jsPayrollEmployees:checked').map(function() { ids.push($(this).val()); });
            }
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
                url: GetURL('get_payroll_page/onboard/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
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
                url: GetURL('get_payroll_page/admin/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
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
            return alertify.alert("Warning!", "First name is mendatory.");
        }
        //
        if (o.last_name.length === 0) {
            return alertify.alert("Warning!", "Last name is mendatory.");
        }
        //
        if (o.email_address.length === 0) {
            return alertify.alert("Warning!", "Email is mendatory.");
        }
        //
        if (!validateEmail(o.email_address)) {
            return alertify.alert("Warning!", "Email is not valid.");
        }
        //
        if (o.phone_number && o.phone_number.length !== 10) {
            return alertify.alert("Warning!", "Phone number must be 10 digits long.");
        }
        //
        ml(true, modalLoader);
        //
        $.ajax({
                method: "POST",
                url: GetURL('save_payroll_admin/' + companyId),
                data: o
            })
            .done(function() {
                //
                return alertify.alert('Success', 'You have successfully added the admin.', function() {
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
                url: GetURL('get_payroll_page/admin_view/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
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
        //
        xhr = $.ajax({
                method: "post",
                url: GetURL('get_payroll_page/company_address/' + companyId),
                data: { employees: GetItem('PayrollEmployees' + companyId) || [] }
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
                // 
                if (resp.errors) {
                    return alertify.alert('Error!', typeof resp.errors !== undefined ? resp.errors.join('<br/>') : resp.errors);
                }
                CompanyDetailPage();
            })
            .error(HandleError);
    }

    /**
     * Company details
     */
    function CompanyDetailPage() {
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "GET",
                url: GetURL('get_payroll_page/company_details/' + companyId),
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                LoadContent(resp, function() {
                    //
                    $('.jsPayrollSaveCompanyLocation').click(SaveCompanyLocation);
                    //  
                    ml(false, modalLoader);
                });
            })
            .error(HandleError);
    }

    /**
     * Add/update company location
     * @param {object} event 
     * @returns 
     */
    function SaveCompanyLocation(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.street_1 = $('.jsStreet1').val().trim();
        o.street_2 = $('.jsStreet2').val().trim();
        o.city = $('.jsCity').val().trim();
        o.state = $('.jsState option:selected').val();
        o.zip = $('.jsZip').val().trim();
        o.phone_number = $('.jsPhoneNumber').val().trim();
        o.mailing_address = $('.jsMailingAddress').prop('checked') ? 1 : 0;
        o.filing_address = $('.jsFilingAddress').prop('checked') ? 1 : 0;
        // Validation
        if (!o.street_1) {
            return alertify.alert('Warning!', 'Street 1 is mendatory.');
        }
        if (!o.city) {
            return alertify.alert('Warning!', 'City is mendatory.');
        }
        if (!o.state) {
            return alertify.alert('Warning!', 'State is mendatory.');
        }
        if (!o.zip) {
            return alertify.alert('Warning!', 'Zip is mendatory.');
        }
        //
        ml(true, modalLoader);
        //
        xhr = $.ajax({
                method: "post",
                url: GetURL('get_payroll_page/set_company_location/' + companyId),
                data: o
            })
            .done(function(resp) {
                //
                xhr = null;
                //
                ml(false, modalLoader);
                // 
                if (resp.errors) {
                    return alertify.alert('Error!', typeof resp.errors !== undefined ? resp.errors.join('<br/>') : resp.errors);
                }
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

    //
    StartOnboardProcess();

});